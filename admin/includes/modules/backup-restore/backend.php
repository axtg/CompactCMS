<?php
/* ************************************************************
Copyright (C) 2008 - 2010 by Xander Groesbeek (CompactCMS.nl)
Revision:	CompactCMS - v 1.4.1
	
This file is part of CompactCMS.

CompactCMS is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

CompactCMS is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

A reference to the original author of CompactCMS and its copyright
should be clearly visible AT ALL TIMES for the user of the back-
end. You are NOT allowed to remove any references to the original
author, communicating the product to be your own, without written
permission of the original copyright owner.

You should have received a copy of the GNU General Public License
along with CompactCMS. If not, see <http://www.gnu.org/licenses/>.
	
> Contact me for any inquiries.
> E: Xander@CompactCMS.nl
> W: http://community.CompactCMS.nl/forum
************************************************************ */

/* make sure no-one can run anything here if they didn't arrive through 'proper channels' */
if(!defined("COMPACTCMS_CODE")) { define("COMPACTCMS_CODE", 1); } /*MARKER*/

/*
We're only processing form requests / actions here, no need to load the page content in sitemap.php, etc. 
*/
define('CCMS_PERFORM_MINIMAL_INIT', true);


// Compress all output and coding
header('Content-type: text/html; charset=UTF-8');

// Define default location
if (!defined('BASE_PATH'))
{
	$base = str_replace('\\','/',dirname(dirname(dirname(dirname(dirname(__FILE__))))));
	define('BASE_PATH', $base);
}

// Include general configuration
/*MARKER*/require_once(BASE_PATH . '/lib/sitemap.php');

$do = getGETparam4IdOrNumber('do');

// Get permissions
$perm = $db->QuerySingleRowArray("SELECT * FROM ".$cfg['db_prefix']."cfgpermissions");

if ($perm['manageModBackup'] <= 0 || !checkAuth()) 
{
	die("No external access to file");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title>Back-up &amp; Restore module</title>
		<link rel="stylesheet" type="text/css" href="../../../img/styles/base.css,liquid.css,layout.css,sprite.css" />
		<script type="text/javascript" charset="utf-8">
function confirmation()
{
	var answer=confirm('<?php echo $ccms['lang']['backend']['confirmdelete']; ?>');
	if(answer)
	{
		try
		{
			return true;
		}
		catch(e)
		{
		}
	}
	else
	{
		return false;
	}
}
</script>
	</head>
<body>
	<div class="module">
<?php


/**
 *
 * Create requested backup archive
 *
 */
if(!empty($do) && $do=="backup" && isset($_POST['btn_backup']) && $_POST['btn_backup']=="dobackup" && checkAuth()) 
{
	// Include back-up functions
	/*MARKER*/require_once('./functions.php');
	
	$configBackup 		= array('../../../../content/','../../../../lib/templates/');
	$configBackupDir 	= '../../../../media/files/';
	$backupName 		= date('Ymd_His').'-data.zip';
	
	$createZip = new createZip;
	if (isset($configBackup) && is_array($configBackup) && count($configBackup)>0) {
	    foreach ($configBackup as $dir) {
	        $basename = basename($dir);
	        if (is_file($dir)) {
	            $fileContents = file_get_contents($dir);
	            $createZip->addFile($fileContents,$basename);
	        } else {
	            $createZip->addDirectory($basename."/");
	            $files = directoryToArray($dir,true);
	            $files = array_reverse($files);
	
	            foreach ($files as $file) {
	                $zipPath = explode($dir,$file);
	                $zipPath = $zipPath[1];
	                if (is_dir($file)) {
	                    $createZip->addDirectory($basename."/".$zipPath);
	                } else {
	                    $fileContents = file_get_contents($file);
	                    $createZip->addFile($fileContents,$basename."/".$zipPath);
	                }
	            }
	        }
	    }
	}
	
	$backup = new MySQL_Backup(); 
	$backup->server   = $cfg['db_host'];
	$backup->username = $cfg['db_user'];
	$backup->password = $cfg['db_pass'];
	$backup->database = $cfg['db_name'];
	
	// Get all current tables in database
	$tables = $db->GetTables();
	foreach ($tables as $table) {
    	$backup->tables[] = $table;
	}
	
	$backup->backup_dir = $configBackupDir;
	$sqldump = $backup->Execute(MSB_STRING,"",false);
	$createZip->addFile($sqldump,$cfg['db_name'].'-sqldump.sql');
	
	$fileName	= $configBackupDir.$backupName;
	$fd			= fopen ($fileName, "wb");
	$out		= fwrite ($fd, $createZip -> getZippedfile());
	fclose ($fd);
	
	echo "<p class=\"success center\">".$ccms['lang']['backend']['newfilecreated'].", <a href=\"../../../../media/files/$backupName\">".strtolower($ccms['lang']['backup']['download'])."</a>.</p>"; 
}

/**
 *
 * Delete current backup archives
 *
 */
if($do=="delete" && $_POST['btn_delete']=="dodelete" && checkAuth()) 
{
	if (!empty($_POST['file']))
	{
		// Only if current user has the rights
		if($_SESSION['ccms_userLevel']>=$perm['manageModBackup']) 
		{
			echo "<div class=\"module notice center\">";
			foreach ($_POST['file'] as $key => $value) 
			{
				$value = filterParam4Filename($value);
				if (!empty($value))
				{
					unlink('../../../../media/files/'.$value);
					echo ucfirst($value)." ".$ccms['lang']['backend']['statusremoved'].".<br/>";
				}
				else 
					die($ccms['lang']['auth']['featnotallowed']);
			}
			echo "</div>";
		} 
		else 
			die($ccms['lang']['auth']['featnotallowed']);
	} 
	else 
	{
		echo "<div class=\"module error center\">".$ccms['lang']['system']['error_selection']."</div>";
	}
}


?>
	
		<div class="span-6 colborder">
		<h2><?php echo $ccms['lang']['backup']['createhd']; ?></h2>
			<p><?php echo $ccms['lang']['backup']['explain'];?></p>
			<form action="<?php echo $_SERVER['PHP_SELF'];?>?do=backup" method="post" accept-charset="utf-8">
				<p><button type="submit" name="btn_backup" value="dobackup"><span class="ss_sprite ss_package_add"><?php echo $ccms['lang']['forms']['createbutton'];?></span></button></p>
			</form>
		</div>
		
		<div class="span-16 last">
		<h2><?php echo $ccms['lang']['backup']['currenthd'];?></h2>
			<form action="<?php echo $_SERVER['PHP_SELF'];?>?do=delete" method="post" accept-charset="utf-8">
				<table border="0" cellspacing="5" cellpadding="5">
					<tr>
						<?php 
						if($_SESSION['ccms_userLevel']>=$perm['manageModBackup']) 
						{ 
						?>
							<th class="span-1">&#160;</th>
						<?php 
						} 
						?>
						<th class="span-10"><?php echo $ccms['lang']['backup']['timestamp'];?></th>
						<th>&#160;</th>
					</tr>
					<?php 
					if ($handle = opendir('../../../../media/files/')) 
					{
						$i=0;
						while (false !== ($file = readdir($handle))) 
						{
					        if ($file != "." && $file != ".." && strpos($file, ".zip")) 
							{
						        // Alternate rows
			    				if($i%2 != 1) 
								{
									echo '<tr style="background-color: #E6F2D9;">';
								} 
								else 
								{ 
									echo '<tr>';
								} 
						        if($_SESSION['ccms_userLevel']>=$perm['manageModBackup']) 
								{
						        	echo '<td><input type="checkbox" name="file[]" value="'.$file.'" id="'.$i.'"></td>';
						        }
						        echo '<td>'.$file.'</td>';
						        echo '<td><span class="ss_sprite ss_package_green"><a href="../../../../media/files/'.$file.'" title="'.ucfirst($file).'">'.$ccms['lang']['backup']['download'].'</a></span></td>';
						        echo '</tr>';
								$i++;
							} 
					    }
					    closedir($handle);
					}
					?>
				</table>
			<?php 
			if($_SESSION['ccms_userLevel']>=$perm['manageModBackup']) 
			{
				if($i>0) 
				{ 
				?>
					<p><button type="submit" onclick="return confirmation();" name="btn_delete" value="dodelete"><span class="ss_sprite ss_package_delete"><?php echo $ccms['lang']['backend']['delete'];?></span></button></p>
				<?php 	
				} 
				else 
					echo $ccms['lang']['system']['noresults'];
			} 
			else 
				echo $ccms['lang']['auth']['featnotallowed'];
			?>
			</form>
		</div>
	</div>
</body>
</html>
