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

// Compress all output and coding
header('Content-type: text/html; charset=UTF-8');

// Include general configuration
require_once('../../../../lib/sitemap.php');

$canarycage	= md5(session_id());
$currenthost= md5($_SERVER['HTTP_HOST']);
$do 		= (isset($_GET['do'])?$_GET['do']:null);

// Get permissions
$perm = $db->QuerySingleRowArray("SELECT * FROM ".$cfg['db_prefix']."cfgpermissions");

 /**
 *
 * Create requested backup archive
 *
 */
if(!empty($do) && $_GET['do']=="backup" && isset($_POST['btn_backup']) && $_POST['btn_backup']=="dobackup" && checkAuth($canarycage,$currenthost)) {
	
	// Include back-up functions
	include_once('functions.php');
	
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
}

 /**
 *
 * Delete current backup archives
 *
 */
if($do=="delete" && !empty($_POST['file']) && $_POST['btn_delete']=="dodelete" && checkAuth($canarycage,$currenthost)) {
	
	// Only if current user has the rights
	if($_SESSION['ccms_userLevel']>=$perm['manageModBackup']) {
	
		echo "<div class=\"module notice center\">";
		foreach ($_POST['file'] as $key => $value) {
			unlink('../../../../media/files/'.$value);
			echo ucfirst($value)." ".$ccms['lang']['backend']['statusremoved'].".<br/>";
		}
		echo "</div>";
	} else die($ccms['lang']['auth']['featnotallowed']);
	
} elseif($do=="delete" && empty($_POST['file']) && $_POST['btn_delete']=="dodelete" && checkAuth($canarycage,$currenthost)) {
	echo "<div class=\"module error center\">".$ccms['lang']['system']['error_selection']."</div>";
}
?>
<?php if($perm['manageModBackup']>0&&checkAuth($canarycage,$currenthost)) { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title>Back-up &amp; Restore module</title>
		<link rel="stylesheet" type="text/css" href="../../../img/styles/base.css,liquid.css,layout.css,sprite.css" />
		<script type="text/javascript" charset="utf-8">function confirmation(){var answer=confirm('<?php echo $ccms['lang']['backend']['confirmdelete']; ?>');if(answer){try{return true;}catch(e){}}else{return false;}}</script>
	</head>
<body>
	<div class="module">
		<?php if(!empty($backupName)) { 
			echo "<p class=\"success center\">".$ccms['lang']['backend']['newfilecreated'].", <a href=\"../../../../media/files/$backupName\">".strtolower($ccms['lang']['backup']['download'])."</a>.</p>"; 
		} ?>
	
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
						<?php if($_SESSION['ccms_userLevel']>=$perm['manageModBackup']) { ?><th class="span-1">&#160;</th><?php } ?>
						<th class="span-5"><?php echo $ccms['lang']['backup']['timestamp'];?></th>
						<th>&#160;</th>
					</tr>
					<?php 
					if ($handle = opendir('../../../../media/files/')) {
						$i=0;
						while (false !== ($file = readdir($handle))) {
					        if ($file != "." && $file != ".." && strpos($file, ".zip")) {
						        $isEven = !($i % 2);
						        echo ($isEven=='1')?'<tr style="background-color: #E6F2D9;">':'<tr>';
						        if($_SESSION['ccms_userLevel']>=$perm['manageModBackup']) {
						        	echo '<td><input type="checkbox" name="file[]" value="'.$file.'" id="'.$i.'"></td>';
						        }
						        echo '<td>'.$file.'</td>';
						        echo '<td><span class="ss_sprite ss_package_green"><a href="../../../../media/files/'.$file.'" title="'.ucfirst($file).'">'.$ccms['lang']['backup']['download'].'</a></span></td>';
						        echo '</tr>';
					        } 
					    $i++; }
					    closedir($handle);
					}
					?>
				</table>
			<?php if($_SESSION['ccms_userLevel']>=$perm['manageModBackup']) { ?>
				<hr />
				<p><br/><button type="submit" onclick="return confirmation();" name="btn_delete" value="dodelete"><span class="ss_sprite ss_package_delete"><?php echo $ccms['lang']['backend']['delete'];?></span></button></p>
			<?php } ?>
			</form>
		</div>
		
	</div>
</body>
</html>
<?php } else die("No external access to file");?>