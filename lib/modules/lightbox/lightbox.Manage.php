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
require_once('../../sitemap.php');

$canarycage	= md5(session_id());
$currenthost= md5($_SERVER['HTTP_HOST']);
$do 		= (isset($_GET['do'])?$_GET['do']:null);

// Get permissions
$perm = $db->QuerySingleRowArray("SELECT * FROM ".$cfg['db_prefix']."cfgpermissions");

// Fill array with albums
$albums = array();
$count = array();
$index = 0;

if ($handle = opendir(BASE_PATH.'/media/albums/')) {
	while (false !== ($file = readdir($handle))) {
		if ($file != "." && $file != ".svn" && $file != ".." && is_dir(BASE_PATH.'/media/albums/'.$file)) {
			// Fill albums array
			$albums[] = $file;
			
			// Count files in album
			if ($countdir = opendir(BASE_PATH.'/media/albums/'.$file)) {
				$count[$index] = '0';
				while (false !== ($counthandle = readdir($countdir))) {
					$ext = strtolower(substr($counthandle, strrpos($counthandle, '.') + 1));
					if ($ext=="jpg"||$ext=="jpeg"||$ext=="png"||$ext=="gif") {
						$count[$index]++;
					}
				} closedir($countdir);
			}
			$index++;
		} 
	}
	closedir($handle);
}
?>

<?php if(checkAuth($canarycage,$currenthost) && isset($_SESSION['rc1']) && !empty($_SESSION['rc2'])) { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title>Lightbox module</title>
		<link rel="stylesheet" type="text/css" href="../../../admin/img/styles/base.css,layout.css,sprite.css,uploader.css" />
		<script type="text/javascript" src="../../includes/js/mootools.js" charset="utf-8"></script>
		<script type="text/javascript" src="../../../admin/includes/fancyupload/Source/Uploader/Swiff.Uploader.js"></script>
		<script type="text/javascript" src="../../../admin/includes/fancyupload/Source/Uploader/Fx.ProgressBar.js"></script>
		<script type="text/javascript" src="../../../admin/includes/fancyupload/FancyUpload2.js"></script>
		<script type="text/javascript" src="../../../admin/includes/fancyupload/modLightbox.js"></script>
	</head>
	
<body>
	<div class="module">
			
		<div class="center <?php echo (isset($_GET['status'])?$_GET['status']:null); ?>">
			<?
			// Show relevant statuses
			if(isset($_GET['msg'])) {
				if($_GET['msg']=="writerr") { echo $ccms['lang']['system']['error_dirwrite']; }
				elseif($_GET['msg']=="created"||$_GET['msg']=="success") { echo $ccms['lang']['backend']['success']; }
				elseif($_GET['msg']=="duperr") { echo $ccms['lang']['system']['error_exists']; }
				elseif($_GET['msg']=="invalid"||$_GET['msg']=="failed") { echo $ccms['lang']['system']['error_value']; }
			} ?>
		</div>
		
		<div class="span-12 colborder">
		<?php 
		// 
		$album_path = (isset($_GET['album'])&&!empty($_GET['album'])?BASE_PATH.'/media/albums/'.$_GET['album']:null);
		$album_path = (is_dir($album_path)?$album_path:null);
		if($album_path==null) { ?>
			<form action="lightbox.Process.php?action=del-album" method="post" accept-charset="utf-8">
			<h2>Current albums</h2>
				<table border="0" cellspacing="5" cellpadding="5">
						<?php if($_SESSION['ccms_userLevel']>=$perm['manageModLightbox']) { ?><th class="span-1">&#160;</th><?php } ?>
						<th class="span-5">Album</th>
						<th class="span-2">Files</th>
						<th class="span-4">Last modified</th>
					</tr>
					<?php 
					$i = 0;
					foreach ($albums as $value) {
						// Alternate rows
				    	if($i%2 != '1') {
							echo '<tr style="background-color: #E6F2D9;">';
						} else { 
							echo '<tr>';
						} ?>
							<?php if($_SESSION['ccms_userLevel']>=$perm['manageModLightbox']) { ?>
								<td><input type="checkbox" name="albumID[<?php echo $i+1;?>]" value="<?php echo $value; ?>" id="newsID"></td>
							<?php } ?>
							<td><span class="ss_sprite ss_folder_picture"><a href="lightbox.Manage.php?album=<?php echo $value;?>"><?php echo $value;?></a></span></td>
							<td><span class="ss_sprite ss_pictures"><?php echo ($count[$i]>0?$count[$i]:'0'); ?></span></td>
							<td><span class="ss_sprite ss_calendar"><?php echo date("Y-m-d G:i:s", filemtime(BASE_PATH.'/media/albums/'.$value)); ?></td>
						</tr>
					<?php
			  			$i++;
	  				} ?>
				</table>
				<hr />
				<?php if($_SESSION['ccms_userLevel']>=$perm['manageModLightbox']) { ?><button type="submit" name="deleteAlbum"><span class="ss_sprite ss_bin_empty">Delete</span></button><?php } ?>
			</form>
				
		<?php } elseif($album_path!=null) { 
			// Load all images
			$images = array();
			if ($handle = opendir($album_path)) {
				while (false !== ($file = readdir($handle))) {
					$ext = strtolower(substr($file, strrpos($file, '.') + 1));
					if ($ext=="jpg"||$ext=="jpeg"||$ext=="png"||$ext=="gif") {
						$images[$file] = '../../../media/albums/'.$_GET['album'].'/_thumbs/'.$file;
					}
				} closedir($handle);
			} ?>
			<h2>Album content</h2>
				<?php foreach ($images as $key => $value) { ?>
					<?php if($_SESSION['ccms_userLevel']>=$perm['manageModLightbox']) {?>
					<a href="lightbox.Process.php?album=<?php echo $_GET['album']; ?>&amp;image=<?php echo $key; ?>&amp;action=del-image">
					<?php } ?>
						<img src="<?php echo $value; ?>" class="thumbview" alt="Thumbnail of <?php echo $key; ?>" />
					<?php if($_SESSION['ccms_userLevel']>=$perm['manageModLightbox']) {?>
					</a>
					<?php } ?>
				<?php } ?>
				
				<p class="clear right"><span class="ss_sprite ss_arrow_undo"><a href="lightbox.Manage.php">All albums</a></span></p>
		<?php } ?>
		</div>
	
		<div class="span-8">
			
		<?php if(!isset($_GET['album'])&&empty($_GET['album'])) { ?>
			<h2>Create new album</h2>
			<?php if($_SESSION['ccms_userLevel']>=$perm['manageModLightbox']) {?>
			<form action="lightbox.Process.php?action=create-album" method="post" accept-charset="utf-8">
				<label for="album">Album name</label><input type="text" class="text" style="width:185px;" name="album" value="" id="album" />
				<button type="submit"><span class="ss_sprite ss_wand">Create</span></button>
			</form>
			<?php } else echo $ccms['lang']['auth']['featnotallowed']; ?>
		
		<hr class="space" />
		<?php } ?>
		
		<h2>Upload content</h2>
		<?php if($_SESSION['ccms_userLevel']>=$perm['manageModLightbox']) {?>
		<form action="./lightbox.Process.php?action=save-files" method="post" enctype="multipart/form-data" id="lightboxForm">
	
			<label for="album" style="margin-right:5px;display:inline;">Upload to existing album</label>
			<select name="album" id="album" class="span-4" size="1">
				<?php foreach ($albums as $value) { ?>
					<option <?php echo (isset($_GET['album'])&&$_GET['album']===$value?"selected":null); ?> value="<?php echo $value; ?>"><?php echo $value; ?></option>
				<?php } ?>
			</select>
			<hr class="space"/>
			<div id="lightbox-fallback">
				<form action="lightbox.Process.php?action=save-files" method="post" accept-charset="utf-8">
					<strong>Single file upload</strong><br/>
					<p>The Flash loader failed to initialize. Make sure Javascript is enabled and Flash is installed. Single file uploads are possible, but not optimized.</p>
					<label for="lightbox-photoupload">Upload a single photo</label>
					<input id="lightbox-photoupload" type="file" name="ccms_file" />
					<p><button type="submit"><span class="ss_sprite ss_add">Upload</span></button></p>
				</form>
			</div>
		
			<div id="lightbox-status" class="hide">
				<p>
					<span class="ss_sprite ss_folder_image"><a href="#" id="lightbox-browse">Browse files</a></span> |
					<span class="ss_sprite ss_cross"><a href="#" id="lightbox-clear">Clear list</a></span> |
					<span class="ss_sprite ss_picture_save"><a href="#" id="lightbox-upload">Start upload</a></span>
				</p>
				<div>
					<strong class="overall-title"></strong><br />
					<img src="../../../admin/includes/fancyupload/Assets/bar.gif" class="progress overall-progress" />
				</div>
				<div>
					<strong class="current-title"></strong><br />
					<img src="../../../admin/includes/fancyupload/Assets/bar.gif" class="progress current-progress" />
				</div>
				<div class="current-text"></div>
			</div>
		
			<ul id="lightbox-list"></ul>
		</form>	
		<?php } else echo $ccms['lang']['auth']['featnotallowed']; ?>
		</div>
		
	</div>
</body>
</html>
<?php } else die("No external access to file");?>