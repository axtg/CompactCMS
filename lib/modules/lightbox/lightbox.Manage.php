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
	$base = str_replace('\\','/',dirname(dirname(dirname(dirname(__FILE__)))));
	define('BASE_PATH', $base);
}

// Include general configuration
/*MARKER*/require_once(BASE_PATH . '/admin/includes/security.inc.php'); // when session expires or is overridden, the login page won't show if we don't include this one, but a cryptic error will be printed.


// security check done ASAP
if(!checkAuth() || empty($_SESSION['rc1']) || empty($_SESSION['rc2'])) 
{ 
	die("No external access to file");
}

$do = getGETparam4IdOrNumber('do');
$status = getGETparam4IdOrNumber('status');
$status_message = getGETparam4DisplayHTML('msg');

// Get permissions
$perm = $db->SelectSingleRowArray($cfg['db_prefix'].'cfgpermissions');
if (!$perm) $db->Kill("INTERNAL ERROR: 1 permission record MUST exist!");



// Read through selected album, get first and count all
function fileList($d)
{
	$l = array();
	foreach(array_diff(scandir($d),array('.','..','index.html','info.txt','_thumbs')) as $f) 
	{
		if(is_file($d.'/'.$f)) 
		{
			$ext = strtolower(substr($f, strrpos($f, '.') + 1));
			if ($ext=="jpg"||$ext=="jpeg"||$ext=="png"||$ext=="gif") 
			{
				$l[] = $f;
			}
   		}
   	} 
	sort($l, SORT_STRING);
	return $l;
} 


function calc_thumb_padding($img_path, $thumb_path = null, $max_height = 80, $max_width = 80)
{
	$show_thumb = 0;
	$height = null;
	$width = null;
	$aspect_ratio = null;
	if(!empty($thumb_path) && file_exists($thumb_path))
	{
		$imginfo = @getimagesize($thumb_path);
		if (!empty($imginfo[0]))
		{
			$height = floatval($imginfo[1]);
			$width = floatval($imginfo[0]);
			$aspect_ratio = (floatval($height)/floatval($width));
		
			$show_thumb = 1;
		}
	}
	if ($show_thumb != 1)
	{
		$thumb_path = $img_path;
		if(file_exists($thumb_path)) 
		{
			$imginfo = @getimagesize($thumb_path);
			if (!empty($imginfo[0]))
			{
				$height = floatval($imginfo[1]);
				$width = floatval($imginfo[0]);
				$aspect_ratio = (floatval($height)/floatval($width));
				
				$show_thumb = 2;
			}
		}
	}
	
	if ($show_thumb == 0)
	{
		return null;
	}
	
	// Resize thumbnail to approx 80 x 80
	$newheight = $height;
	$newwidth = $width;
	if ($newwidth > $max_width)
	{
		$newwidth = $max_width;
		$newheight = intval($aspect_ratio * $newwidth);
	}
	if ($newheight > $max_height)
	{
		$newheight = $max_height;
		$newwidth = intval($newheight / $aspect_ratio);
	}
	
	// calc padding to fill box up to max_h x max_w
	$pad_height = $max_height - $newheight;
	$pad_width = $max_width - $newwidth;
	
	$rv = array();
	$rv['h'] = $newheight;
	$rv['w'] = $newwidth;
	$rv['show'] = $show_thumb;
	$rv['ph1'] = intval($pad_height / 2);
	$pad_height -= $rv['ph1'];
	$rv['ph2'] = $pad_height;
	$rv['pw1'] = intval($pad_width / 2);
	$pad_width -= $rv['pw1'];
	$rv['pw2'] = $pad_width;
	
	$rv['style'] = 'style="padding:' . $rv['ph1'] . 'px ' . $rv['pw2'] . 'px ' . $rv['ph2'] . 'px ' . $rv['pw1'] . 'px; width:' . $rv['w'] . 'px; height:' . $rv['h'] . 'px;"';
	
	return $rv;
}


// Fill array with albums
$albums = array();
$count = array();

if ($handle = opendir(BASE_PATH.'/media/albums/')) 
{
	while (false !== ($file = readdir($handle))) 
	{
		if ($file != "." && $file != ".." && is_dir(BASE_PATH.'/media/albums/'.$file)) 
		{
			// Fill albums array
			$albums[] = $file;
		} 
	}
	closedir($handle);
	sort($albums, SORT_STRING);
	
	// to make sure $count[] array is in sync with the $albums[] array, we need to perform this extra round AFTER the sort() operation.
	foreach($albums as $key => $file) 
	{
		// Count files in album
		$images = fileList(BASE_PATH.'/media/albums/'.$file);
		$count[$key] = count($images);
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>Lightbox module</title>
	<link rel="stylesheet" type="text/css" href="../../../admin/img/styles/base.css,liquid.css,layout.css,sprite.css,uploader.css" />
	<script type="text/javascript" src="../../includes/js/mootools.js" charset="utf-8"></script>
	<?php 
	// prevent JS errors when permissions don't allow uploading (and all the rest)
	if($perm['manageModLightbox']>0 && $_SESSION['ccms_userLevel']>=$perm['manageModLightbox']) 
	{
	?>
		<script type="text/javascript" src="../../../admin/includes/fancyupload/Source/Uploader/Swiff.Uploader.js"></script>
		<script type="text/javascript" src="../../../admin/includes/fancyupload/Source/Uploader/Fx.ProgressBar.js"></script>
		<script type="text/javascript" src="../../../admin/includes/fancyupload/FancyUpload2.js"></script>
		<script type="text/javascript" src="../../../admin/includes/fancyupload/modLightbox.js"></script>
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

function confirm_regen()
{
	var answer=confirm('<?php echo $ccms['lang']['backend']['confirmthumbregen']; ?>');
	if(answer)
	{
		try
		{
			$('lightbox-pending').setStyle('visibility', 'visible');
			return true;
		}
		catch(e)
		{
			$('lightbox-pending').setStyle('visibility', 'hidden');
			return false;
		}
	}
	else
	{
		$('lightbox-pending').setStyle('visibility', 'hidden');
		return false;
	}
}
		</script>
	<?php
	}
	?>
</head>
<body>
	<div class="module">
		<div class="center <?php echo $status; ?>">
			<?php 
			if(!empty($status_message)) 
			{ 
				echo '<span class="ss_sprite '.($status == 'notice' ? 'ss_accept' : 'ss_error').'">'.$status_message.'</span>'; 
			} 
			?>
		</div>
		
		<div class="span-14 colborder">
		<?php 
		// more secure: only allow showing specific albums if they are in the known list; if we change that set any time later, this code will not let undesirable items slip through
		$album = getGETparam4Filename('album');
		$album_path = (in_array($album, $albums) ? BASE_PATH.'/media/albums/'.$album : null);
		if($album==null) 
		{ 
		?>
			<form action="lightbox.Process.php?action=del-album" method="post" accept-charset="utf-8">
				<h2><?php echo $ccms['lang']['album']['currentalbums']; ?></h2>
				<table border="0" cellspacing="5" cellpadding="5">
					<?php 
					if(count($albums) > 0) 
					{ 
					?>
					<tr>
						<?php 
						if($perm['manageModLightbox']>0 && $_SESSION['ccms_userLevel'] >= $perm['manageModLightbox']) 
						{ 
						?>
							<th class="span-1">&#160;</th>
						<?php 
						} 
						?>
						<th class="span-5"><?php echo $ccms['lang']['album']['album']; ?></th>
						<th class="span-2"><?php echo $ccms['lang']['album']['files']; ?></th>
						<th class="span-4"><?php echo $ccms['lang']['album']['lastmod']; ?></th>
						</tr>
						<?php 
						foreach ($albums as $key => $value) 
						{
							// Alternate rows
					    	if($key % 2 != 1) 
							{
								echo '<tr style="background-color: #E6F2D9;">';
							} 
							else 
							{ 
								echo '<tr>';
							} 
							
							if($perm['manageModLightbox']>0 && $_SESSION['ccms_userLevel'] >= $perm['manageModLightbox']) 
							{ 
							?>
								<td><input type="checkbox" name="albumID[<?php echo $key+1;?>]" value="<?php echo $value; ?>" id="newsID"></td>
							<?php 
							} 
							?>
							<td><span class="ss_sprite ss_folder_picture"><a href="lightbox.Manage.php?album=<?php echo $value;?>"><?php echo $value;?></a></span></td>
							<td><span class="ss_sprite ss_pictures"><?php echo $count[$key]; ?></span></td>
							<td><span class="ss_sprite ss_calendar"><?php echo date("Y-m-d G:i:s", filemtime(BASE_PATH.'/media/albums/'.$value)); ?></td>
						</tr>
						<?php
		  				}
	  				} 
					else 
						echo $ccms['lang']['system']['noresults']; 
					?>
				</table>
				<hr />
				<?php 
				if($perm['manageModLightbox']>0 && $_SESSION['ccms_userLevel']>=$perm['manageModLightbox']&&count($albums)>0) 
				{ 
				?>
					<button type="submit" onclick="return confirmation();" name="deleteAlbum"><span class="ss_sprite ss_bin_empty"><?php echo $ccms['lang']['backend']['delete']; ?></span></button>
				<?php 
				} 
				?>
			</form>
		<?php 
		} 
		else
		{ 
			// Load all images
			$images = fileList($album_path);
			$imagethumbs = array();
			$imginfo = array();
			if (count($images) > 0)
			{
				foreach($images as $index => $file) 
				{
					$imagethumbs[$index] = '../../../media/albums/'.$album.'/_thumbs/'.$file;
					$thumb_path = $album_path.'/_thumbs/'.$file;
					$img_path = $album_path.'/'.$file;
					$imginfo[$index] = calc_thumb_padding($img_path, $thumb_path);
				} 
			} 
			?>
			<h2><?php echo $ccms['lang']['album']['manage']; ?></h2>
			<?php 
			foreach ($images as $key => $value) 
			{ 
				if($perm['manageModLightbox']>0 && $_SESSION['ccms_userLevel']>=$perm['manageModLightbox'])
				{
					echo '<a onclick="return confirmation();" href="lightbox.Process.php?album=' . $album . '&amp;image=' . $value . '&amp;action=del-image" title="' . $ccms['lang']['backend']['delete'] . ': ' . $value . '">';
				} 

				echo '<img src="' . $imagethumbs[$key] . '" class="thumbview" alt="Thumbnail of ' . $value . '" ' . $imginfo[$key]['style'] . ' />';

				if($perm['manageModLightbox']>0 && $_SESSION['ccms_userLevel']>=$perm['manageModLightbox']) 
				{
					echo '</a>';
				} 
				echo "\n";
			} 
			?>
			<p class="clear right">
			<?php
			if (count($images) > 0 && $perm['manageModLightbox']>0 && $_SESSION['ccms_userLevel'] >= $perm['manageModLightbox']) 
			{
			?>
				<span class="ss_sprite ss_arrow_in"><a onclick="return confirm_regen();" href="lightbox.Process.php?album=<?php echo $album; ?>&amp;action=confirm_regen">
				<?php echo $ccms['lang']['album']['regenalbumthumbs']; ?>
				</a></span>
			<?php
			}
			?>
			<span class="ss_sprite ss_arrow_undo"><a href="lightbox.Manage.php"><?php echo $ccms['lang']['album']['albumlist']; ?></a></span>
			</p>
		<?php 
		} 
		?>
		</div>
	
		<div class="span-8">
			
		<?php 
		if(empty($album)) 
		{ 
		?>
			<h2><?php echo $ccms['lang']['album']['newalbum']; ?></h2>
			<?php 
			if($perm['manageModLightbox']>0 && $_SESSION['ccms_userLevel']>=$perm['manageModLightbox']) 
			{
			?>
				<form action="lightbox.Process.php?action=create-album" method="post" accept-charset="utf-8">
					<label for="album"><?php echo $ccms['lang']['album']['album']; ?></label><input type="text" class="text" style="width:160px;" name="album" value="" id="album" />
					<button type="submit"><span class="ss_sprite ss_wand"><?php echo $ccms['lang']['forms']['createbutton']; ?></span></button>
				</form>
			<?php 
			} 
			else 
				echo $ccms['lang']['auth']['featnotallowed']; 
			?>
		
			<hr class="space" />
		<?php 
		} 
		else 
		{
			$lines = @file($album_path.'/info.txt'); 
			?>
			<h2><?php echo $ccms['lang']['album']['settings']; ?></h2>
			<?php 
			if($perm['manageModLightbox']>0 && $_SESSION['ccms_userLevel']>=$perm['manageModLightbox']) 
			{
			?>
				<form action="lightbox.Process.php?action=apply-album" method="post" accept-charset="utf-8">
					<label for="albumtopage"><?php echo $ccms['lang']['album']['apply_to']; ?></label>
					<select class="text" name="albumtopage" id="albumtopage" size="1">
						<option value=""><?php echo $ccms['lang']['backend']['none']; ?></option>
						<?php 
						$lightboxes = $db->QueryArray("SELECT * FROM ".$cfg['db_prefix']."pages WHERE module='lightbox'", MYSQL_ASSOC); 
						for ($i=0; $i < count($lightboxes); $i++) 
						{ 
						?>
							<option <?php echo (!empty($lines[0])&&trim($lines[0])==$lightboxes[$i]['urlpage']?'selected':null); ?> value="<?php echo $lightboxes[$i]['urlpage'];?>"><?php echo $lightboxes[$i]['urlpage'];?>.html</option>
						<?php 
						} 
						?>
					</select>
					<?php
					$desc = '';
					for ($x=1; $x<count($lines); $x++) 
					{
						$desc = trim($desc.' '.$lines[$x]); // [i_a] double invocation of htmlspecialchars, together with the form input (lightbox.Process.php)
					}
					?>
					<label for="description"><?php echo $ccms['lang']['album']['description']; ?></label>
					<textarea name="description" rows="3" cols="40" style="height:50px;width:290px;" id="description"><?php echo $desc; ?></textarea>
					<input type="hidden" name="album" value="<?php echo $album; ?>" id="album" />
					<p class="prepend-5"><button type="submit"><span class="ss_sprite ss_disk"><?php echo $ccms['lang']['forms']['savebutton']; ?></span></button></p>
				</form>
			<?php 
			} 
			else 
				echo $ccms['lang']['auth']['featnotallowed']; 
		} 
		
		if(count($albums)>0) 
		{ 
		?>
			<h2><?php echo $ccms['lang']['album']['uploadcontent']; ?></h2>
			<?php 
			if($perm['manageModLightbox']>0 && $_SESSION['ccms_userLevel']>=$perm['manageModLightbox']) 
			{
			?>
			<form action="./lightbox.Process.php?<?php 
				/* 
				FancyUpload 3.0 uses a Flash object, which doesn't pass the session ID cookie, hence it BREAKS the session.
				Given that we now finally DO check the session variables, FancyUpload suddenly b0rks with timeout errors as
				lightbox.Process.php didn't produce ANY output in such circumstances.
				
				We need to make sure the Flash component forwards the session ID anyway. Use SID for that. See also:
				
					http://www.php.net/manual/en/function.session-id.php
					http://devzone.zend.com/article/1312
					http://www.php.net/manual/en/session.idpassing.php
				*/
				$_SESSION['fup1'] = md5(mt_rand().time().mt_rand());

				$sesid = null;
				if (defined('SID'))
				{
					$sesid = SID;
				}
				
				if (!empty($sesid))
				{
					echo $sesid;
				}
				else
				{
					echo 'SID' . md5($cfg['authcode'].'x') . '=' . session_id();
				}
				
				/*
				Because sessions are long-lived, we need to add an extra check as well, which will ensure that the current
				form display will only produce a single permitted upload request; we can do this using a few random values
				which may be stored in the session, but we MUST DESTROY those values once we've handled the corresponding
				'save-files' action resulting from a form submit.
				*/
				$_SESSION['fup1'] = md5(mt_rand().time().mt_rand());
				echo '&SIDCHK=' . $_SESSION['fup1'];

				/* whitespace is important here... */ ?>&action=save-files" method="post" enctype="multipart/form-data" id="lightboxForm">
		
				<label for="album" style="margin-right:5px;display:inline;"><?php echo $ccms['lang']['album']['toexisting']; ?></label>
				<select name="album" id="album" class="text" style="width:130px;" size="1">
					<?php 
					foreach ($albums as $value) 
					{ 
					?>
						<option <?php echo ($album===$value?"selected":null); ?> value="<?php echo $value; ?>"><?php echo $value; ?></option>
					<?php 
					} 
					?>
				</select>
				<hr class="space"/>
				<div id="lightbox-fallback">
					<form action="lightbox.Process.php?action=save-files" method="post" accept-charset="utf-8">
						<?php echo $ccms['lang']['album']['singlefile']; ?>
						<input id="lightbox-photoupload" type="file" name="Filedata" />
						<p><button type="submit"><span class="ss_sprite ss_add"><?php echo $ccms['lang']['album']['upload']; ?></span></button></p>
					</form>
				</div>
			
				<div id="lightbox-status" class="hide">
					<p>
						<span class="ss_sprite ss_folder_image"><a href="#" id="lightbox-browse"><?php echo $ccms['lang']['album']['browse']; ?></a></span> |
						<span class="ss_sprite ss_cross"><a href="#" id="lightbox-clear"><?php echo $ccms['lang']['album']['clear']; ?></a></span> |
						<span class="ss_sprite ss_picture_save"><a href="#" id="lightbox-upload"><?php echo $ccms['lang']['album']['upload']; ?></a></span>
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
			<?php 
			} 
			else 
				echo $ccms['lang']['auth']['featnotallowed']; 
			?>
			</div>
		<?php 
		} 
		?>
			
		<div id="lightbox-pending" class="lightbox-spinner-bg">
			<p class="loading-img" ><?php echo $ccms['lang']['album']['please_wait']; ?></p>
		</div>
	</div>
</body>
</html>
