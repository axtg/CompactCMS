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

// Default albums location
$album_path	= BASE_PATH.'/media/albums';
$album_url	= $cfg['rootdir'].'media/albums';

// Read through selected album, get first and count all
function fileList($d){
	foreach(array_diff(scandir($d),array('.','..','index.html','info.txt')) as $f) {
		if(is_file($d.'/'.$f)) {
			$l[] = $f;
   		}
   	} return $l;
} 

// Get all the albums in the default media/albums location
if($handle = opendir($album_path)) {
	while (false !== ($file = readdir($handle))) {
		if ($file != "." && $file != ".." && $file != "index.html" && $file != "info.txt") {
			$albums[] = $file;
    	}
	} closedir($handle);
}

// Get specified album for current page
if(isset($albums)&&count($albums)>0) {
	foreach ($albums as $file) {
		$lines = @file($album_path.'/'.$file.'/info.txt');
		if($lines>0&&@preg_match('/'.$_GET['page'].'/',$lines[0])) {
			$spec_album = $file;
		}
	}
	// Define single show
	$singleShow = (isset($spec_album)||count($albums)=='1'||isset($_GET['id'])&&!empty($_GET['id'])?'1':'0');
}
?>

<!-- additional style and code -->
<link rel="stylesheet" href="<?php echo $cfg['rootdir']; ?>lib/modules/lightbox/resources/style.css" type="text/css" media="screen" title="lightbox" charset="utf-8" />
<script type="text/javascript" src="<?php echo $cfg['rootdir']; ?>lib/modules/lightbox/resources/script.js" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">window.addEvent("domready", function() {initImageZoom({loadImage: '<?php echo $cfg['rootdir']."lib/modules/lightbox/resources/loading.gif"; ?>'});});</script>

<!-- lay-out -->
<?php if(!isset($_GET['id'])&&empty($_GET['id'])&&isset($albums)&&count($albums)>1&&$singleShow!='1') { ?>
	<?php if(!empty($albums)) {
		foreach ($albums as $i => $file) { 
			// Get the images in an album
			$image = @fileList($album_path.'/'.$file);
			
			// If album is not empty and thumbnail is found
			if(file_exists($album_path.'/'.$file.'/_thumbs/'.$image[0])&&count($image)>0) 
			{
				echo "<div class=\"album-item\">";
				echo "<a href=\"".$cfg['rootdir'].$_GET['page']."/".$file.".html\">";
				echo "<img src=\"$album_url/$file/_thumbs/".$image[0]."\" height=\"80\" width=\"80\"/><br/>";
				echo ucfirst($file)." (".count($image).")</a></div>";	
			} 
			// If album does exist, but no contents (empty album)
			elseif(count($image)==0) {
				echo "<div class=\"album-item\">";
				echo "<img src=\"".$cfg['rootdir']."lib/modules/lightbox/resources/empty.png\" height=\"80\" width=\"80\" /><br/>";
				echo ucfirst($file)." (0)</div>";	
			} 
			// Otherwise show the first image of non-empty album and scale it to 80x80
			else {
				echo "<div class=\"album-item\">";
				echo "<a href=\"".$cfg['rootdir'].$_GET['page']."/".$file.".html\">";
				echo "<img src=\"".$album_url."/".$file."/".$image[0]."\" height=\"80\" width=\"80\"/><br/>";
				echo ucfirst($file)." (".count($image).")</a></div>";	
			}
		} 
	} else echo $ccms['lang']['album']['noalbums'];
} elseif(isset($singleShow)&&$singleShow=='1') {
	$album = (isset($_GET['id'])?htmlentities($_GET['id']):$albums[0]);
	$album = (isset($spec_album)?$spec_album:$album);
	
	echo "<h3>".$ccms['lang']['album']['album']." ".ucfirst($album)."</h3>";
	if(isset($_GET['id'])) { echo "<p style=\"text-align:right\"><a href=\"".$cfg['rootdir'].$_GET['page'].".html\"\">".$ccms['lang']['backend']['tooverview']."</a></p>"; }

	$desc = null;
	$lines = @file($album_path.'/'.$album.'/info.txt');
	for ($x=1; $x<count($lines); $x++) {
    	$desc = trim($desc.' '.htmlspecialchars($lines[$x]));
	} echo "<p>$desc</p>";

	if($handle = @opendir($album_path.'/'.$album)) {
		while (false !== ($content = readdir($handle))) {
			if ($content != "." && $content != ".." && $content != "_thumbs" && $content != "info.txt") {
				$caption = substr($content, 0, strrpos($content, '.')); 
				$caption = ucfirst(str_replace('_', ' ', $caption));
				if(file_exists($album_path.'/'.$album.'/_thumbs/'.$content)) {
					echo "<div class=\"album-item\">";
					echo "<a rel=\"imagezoom[$album]\" href=\"$album_url/$album/$content\" title=\"$caption\"><img src=\"$album_url/$album/_thumbs/$content\" height=\"80\" width=\"80\" alt=\"\" /></a>";
					echo "</div>";
				} else {
					echo "<div class=\"album-item\">";
					echo "<a rel=\"imagezoom[$album]\" href=\"$album_url/$album/$content\" title=\"$caption\"><img src=\"$album_url/$album/$content\" height=\"80\" width=\"80\" alt=\"\" /></a>";
					echo "</div>";
				}
			}
		} closedir($handle);
	} else echo "<p>&#160;</p><p>".$ccms['lang']['system']['error_value']."</p>";
	if(isset($_GET['id'])) { echo "<p style=\"text-align:right;clear:both;\"><a href=\"".$cfg['rootdir'].$_GET['page'].".html\">".$ccms['lang']['backend']['tooverview']."</a></p>"; }
} else echo $ccms['lang']['system']['noresults']; ?>