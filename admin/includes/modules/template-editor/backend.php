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

// Include general configuration
require_once('../../../../lib/sitemap.php');

$canarycage	= md5(session_id());
$currenthost= md5($_SERVER['HTTP_HOST']);
$do 		= (isset($_GET['do'])?$_GET['do']:null);

if(!empty($do) && $_GET['do']=="backup" && $_POST['btn_backup']=="dobackup" && md5(session_id())==$canarycage && isset($_SESSION['rc1']) && md5($_SERVER['HTTP_HOST'])==$currenthost) {
	
	// Include back-up functions
	include_once('functions.php');
}

// Set the default template
$dir_temp = "../../../../lib/templates/";
$get_temp = (isset($_GET['template'])?htmlentities($_GET['template']):$template[0].'.tpl.html');
$chstatus = (substr(sprintf('%o', fileperms($dir_temp.$get_temp)), -4)>='0666'?1:0);
	
// Check for filename	
if(!empty($get_temp)) {
	if(@fopen($dir_temp.$get_temp, "r")) {
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
?>
<?php if(checkAuth($canarycage,$currenthost) && $_SESSION['ccms_userLevel']>=$perm['manageTemplate']) { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title>Back-up &amp; Restore module</title>
		<link rel="stylesheet" type="text/css" href="../../../img/styles/base.css,liquid.css,layout.css,sprite.css" />
		
		<script type="text/javascript" src="../../edit_area/edit_area_compressor.php"></script>
		<script type="text/javascript">editAreaLoader.init({id:"content",allow_resize:'both',allow_toggle:false,word_wrap:true,start_highlight:true,<?php echo 'language:"'.$cfg['language'].'",'; ?>syntax:"html"});</script>
		<script type="text/javascript">function confirmation(){var answer=confirm(<?php echo"'".$ccms['lang']['editor']['confirmclose']."'";?>);if(answer){try{parent.MochaUI.closeWindow(parent.$('sys-tmp_ccms'));}catch(e){}}else{return false;}}</script>
	</head>
<body>
	<div class="module">

		<?php if(!strpos($_SERVER['SERVER_SOFTWARE'], "Win") && $chstatus==0) { ?>
			<p class="error center"><?php echo $ccms['lang']['template']['nowrite']; ?></p>
		<?php } ?>	
		<div class="span-13">
			<h1 class="editor"><?php echo $ccms['lang']['template']['manage']; ?></h1>
		</div>
		
		<div class="span-8 right">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" id="changeTmp" method="get" class="right" accept-charset="utf-8">
				<label for="template" style="display:inline;"><?php echo $ccms['lang']['backend']['template'];?></label>
				<select class="text" onChange="document.getElementById('changeTmp').submit();" id="template" name="template">
					<?php
					$x = 0; 
					while($x<count($template)) { ?>
						<optgroup label="<?php echo ucfirst($template[$x]); ?>">
							<option <?php echo ($get_temp==$template[$x].".tpl.html") ? "selected=\"selected\"" : ""; ?> value="<?php echo $template[$x]; ?>.tpl.html"><?php echo ucfirst($template[$x]).': '.strtolower($ccms['lang']['backend']['template']); ?></option>
							<?php 
							
							// Get CSS files
							if ($handle = opendir($dir_temp.$template[$x].'/')) {
								while (false !== ($file = readdir($handle))) {
							        if ($file != "." && $file != ".." && strtolower(substr($file, strrpos($file, '.') + 1))=='css') {
							            $cssfiles[$x][] = $file;
							        }
							    }
							    closedir($handle);
							}
							foreach ($cssfiles[$x] as $css) { ?>
								<option <?php echo ($get_temp==$template[$x].'/'.$css) ? "selected=\"selected\"" : ""; ?> value="<?php echo $template[$x].'/'.$css; ?>"><?php echo ucfirst($template[$x]).': '.$css; ?></option>
							<?php } ?>
						</optgroup>
					<?php $x++; } ?>
				</select>
			</form>
		</div>
		<hr class="space"/>
		
		<?php if(isset($_GET['status'])) { ?>
			<div class="notice center"><span class="ss_sprite ss_confirm"><?php echo $ccms['lang']['backend']['settingssaved'];?></span></div>
		<?php } ?>
		
		<form action="../../process.inc.php?template=<?php echo $get_temp; ?>&amp;action=save-template" method="post" accept-charset="utf-8">
		
			<textarea id="content" name="content" style="height:400px;width:100%;color:#000;">
				<?php echo htmlspecialchars($contents); ?>
			</textarea>
			
			<p>
				<input type="hidden" name="template" value="<?php echo $get_temp; ?>" id="template" />
				<?php if(strpos($_SERVER['SERVER_SOFTWARE'], "Win") || $chstatus>0) { ?>
					<button type="submit" name="do" id="submit"><span class="ss_sprite ss_disk"><?php echo $ccms['lang']['editor']['savebtn']; ?></span></button>
				<?php }  ?>
				<span class="ss_sprite ss_cross"><a href="javascript:;" onClick="confirmation()" title="<?php echo $ccms['lang']['editor']['cancelbtn']; ?>"><?php echo $ccms['lang']['editor']['cancelbtn']; ?></a></span>
			</p>
			
		</form>
	
	</div>
</body>
</html>
<?php } else die("No external access to file");?>