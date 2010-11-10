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
if (!defined('CCMS_PERFORM_MINIMAL_INIT')) { define('CCMS_PERFORM_MINIMAL_INIT', true); }


// Define default location
if (!defined('BASE_PATH'))
{
	$base = str_replace('\\','/',dirname(dirname(dirname(dirname(dirname(__FILE__))))));
	define('BASE_PATH', $base);
}

// Include general configuration
/*MARKER*/require_once(BASE_PATH . '/lib/sitemap.php');


// security check done ASAP
if(!checkAuth() || empty($_SESSION['rc1']) || empty($_SESSION['rc2'])) 
{ 
	die("No external access to file");
}



$do	= getGETparam4IdOrNumber('do');
$status = getGETparam4IdOrNumber('status');
$status_message = getGETparam4DisplayHTML('msg');


// Set the default template
$dir_temp = BASE_PATH . "/lib/templates/";
$get_temp = getGETparam4FullFilePath('template', $template[0].'.tpl.html');
$chstatus = is_writable($dir_temp.$get_temp); // @dev: to test the error feedback on read-only on Win+UNIX: add '|| 1' here.
	
// Check for filename	
if(!empty($get_temp)) 
{
	if(@fopen($dir_temp.$get_temp, "r")) 
	{
		$handle = fopen($dir_temp.$get_temp, "r");
		// PHP5+ Feature
		// $contents = stream_get_contents($handle);
		// PHP4 Compatibility
		$contents = @fread($handle, filesize($dir_temp.$get_temp));
		$contents = str_replace("<br />", "<br>", $contents);
		fclose($handle);
	} 
} 

// Get permissions
$perm = $db->QuerySingleRowArray("SELECT * FROM ".$cfg['db_prefix']."cfgpermissions");
if (!$perm) $db->Kill("INTERNAL ERROR: 1 permission record MUST exist!");

if($_SESSION['ccms_userLevel']<$perm['manageTemplate']) 
{
	$chstatus = false; // templates are viewable but NOT WRITABLE when user doesn't have permission to manage these.
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title>Template Editing module</title>
		<link rel="stylesheet" type="text/css" href="../../../img/styles/base.css,liquid.css,layout.css,sprite.css" />
<?php

// TODO: call edit_area_compressor.php only from the combiner: combine.inc.php when constructing the edit_area.js file for the first time.

?>
		<script type="text/javascript" src="../../edit_area/edit_area_compressor.php"></script>
		<script type="text/javascript">
editAreaLoader.init(
	{
		id:"content",
		allow_resize:'both',
		allow_toggle:false,
		word_wrap:true,
		start_highlight:true,
		<?php echo 'language:"'.$cfg['editarea_language'].'",'; ?>
		syntax:"html"
	});
</script>
		<script type="text/javascript">
function confirmation()
{
	var answer=confirm(<?php echo"'".$ccms['lang']['editor']['confirmclose']."'";?>);
	if(answer)
	{
		try
		{
			parent.MochaUI.closeWindow(parent.$('sys-tmp_ccms'));
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
		if($chstatus==0) 
		{ 
		?>
			<p class="error center"><?php echo $ccms['lang']['template']['nowrite']; ?></p>
		<?php 
		} 
		?>	
		<div class="span-13">
			<h1 class="editor"><?php echo $ccms['lang']['template']['manage']; ?></h1>
		</div>
		
		<div class="span-8 right">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" id="changeTmp" method="get" class="right" accept-charset="utf-8">
				<label for="template" style="display:inline;"><?php echo $ccms['lang']['backend']['template'];?></label>
				<select class="text" onChange="document.getElementById('changeTmp').submit();" id="template" name="template">
					<?php
					$x = 0; 
					while($x<count($template)) 
					{ 
					?>
						<optgroup label="<?php echo ucfirst($template[$x]); ?>">
							<option <?php echo ($get_temp==$template[$x].".tpl.html") ? "selected=\"selected\"" : ""; ?> value="<?php echo $template[$x]; ?>.tpl.html"><?php echo ucfirst($template[$x]).': '.strtolower($ccms['lang']['backend']['template']); ?></option>
							<?php 
							
							// Get CSS and other text-editable files which are part of the engine
							$cssfiles = array();
							if ($handle = opendir($dir_temp.$template[$x].'/')) 
							{
								while (false !== ($file = readdir($handle))) 
								{
							        if ($file != "." && $file != "..")
									{
										switch (strtolower(substr($file, strrpos($file, '.') + 1)))
										{
										case 'css':
										case 'js':
										case 'php':
										case 'html':
										case 'txt':
											$cssfiles[$x][] = $file;
											break;
											
										default:
											// don't list image files and such
											break;
										}
							        }
							    }
							    closedir($handle);
							}
							
							foreach ($cssfiles[$x] as $css) 
							{ 
							?>
								<option <?php echo ($get_temp==$template[$x].'/'.$css) ? "selected=\"selected\"" : ""; ?> value="<?php echo $template[$x].'/'.$css; ?>"><?php echo ucfirst($template[$x]).': '.$css; ?></option>
							<?php 
							} 
							?>
						</optgroup>
					<?php 
					$x++; 
				} 
				?>
				</select>
			</form>
		</div>
		<hr class="space"/>
		
		<?php 
		/*
		??? ALWAYS saying 'settings saved' instead of the attached message in the old code? Must've been a bug...
		
		Changed to mimic the layout in the other files...
		*/                 
		?>                              
		<div class="center <?php echo $status; ?>">
			<?php 
			if(!empty($status_message)) 
			{ 
				echo '<span class="ss_sprite '.($status == 'notice' ? 'ss_accept' : 'ss_error').'">'.$status_message.'</span>'; 
			} 
			?>
		</div>
		
		<form action="../../process.inc.php?template=<?php echo $get_temp; ?>&amp;action=save-template" method="post" accept-charset="utf-8">
		
			<textarea id="content" name="content" style="height:400px;width:100%;color:#000;"><?php echo htmlspecialchars(trim($contents)); ?></textarea>
			
			<p>
				<input type="hidden" name="template" value="<?php echo $get_temp; ?>" id="template" />
				<?php 
				if($chstatus > 0) 
				{ 
				?>
					<button type="submit" name="do" id="submit"><span class="ss_sprite ss_disk"><?php echo $ccms['lang']['editor']['savebtn']; ?></span></button>
				<?php 
				}  
				?>
				<span class="ss_sprite ss_cross"><a href="javascript:;" onClick="confirmation()" title="<?php echo $ccms['lang']['editor']['cancelbtn']; ?>"><?php echo $ccms['lang']['editor']['cancelbtn']; ?></a></span>
			</p>
			
		</form>
	
	</div>
</body>
</html>