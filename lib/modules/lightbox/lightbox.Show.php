<?php 
// Default albums location
$album_location	= './media/albums';

// Read through selected album, get first and count all
function fileList($d){
	foreach(array_diff(scandir($d),array('.','..','index.html')) as $f) {
		if(is_file($d.'/'.$f)) {
			$l[] = $f;
   		}
   	} return $l;
} 

// Get all the albums in the default media/albums location
if($handle = opendir($album_location)) {
	while (false !== ($file = readdir($handle))) {
		if ($file != "." && $file != ".." && $file != "index.html") {
			$albums[] = $file;
    	}
	} closedir($handle);
}
?>

<!-- additional style and code -->
<link rel="stylesheet" href="./lib/modules/lightbox/resources/style.css" type="text/css" media="screen" title="lightbox" charset="utf-8" />
<script type="text/javascript" src="./lib/modules/lightbox/resources/script.js" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">window.addEvent("domready", function() {initImageZoom();});</script>

<!-- lay-out -->
<?php if(!isset($_GET['album'])&&empty($_GET['album'])) { ?>
	<?php if(!empty($albums)) {
		foreach ($albums as $i => $file) { 
			// Get the images in an album
			$image = fileList($album_location.'/'.$file);
			
			// If album is not empty and thumbnail is found
			if(file_exists($album_location.'/'.$file.'/_thumbs/'.$image['0'])) {
				echo "<div class=\"album-item\">";
				echo "<a href=\"?album=$file\">";
				echo "<img src=\"$album_location/$file/_thumbs/".$image['0']."\" height=\"80\" width=\"80\"/><br/>";
				echo ucfirst($file)." (".count($image).")</a></div>";	
			} 
			// If album does exist, but no contents (empty album)
			elseif(!is_file($album_location."/".$file."/".$image['0'])) {
				echo "<div class=\"album-item\">";
				echo "<img src=\"./lib/modules/lightbox/empty.png\" height=\"80\" width=\"80\" /><br/>";
				echo ucfirst($file)." (0)</div>";	
			} 
			// Otherwise show the first image of non-empty album and scale it to 80x80
			else {
				echo "<div class=\"album-item\">";
				echo "<a href=\"?album=$file\">";
				echo "<img src=\"".$album_location."/".$file."/".$image['0']."\" height=\"80\" width=\"80\"/><br/>";
				echo ucfirst($file)." (".count($image).")</a></div>";	
			}
		} 
	} else echo $ccms['lang']['album']['noalbums'];
} elseif(isset($_GET['album'])&&!empty($_GET['album'])) {
	$album = htmlentities($_GET['album']);
	echo "<h3>".$ccms['lang']['album']['album']." ".ucfirst($album)."</h3>";
	echo "<a href=\"javascript:history.go(-1);\">".$ccms['lang']['album']['tooverview']."</a><br/>";

	if($handle = @opendir($album_location.'/'.$album)) {
		while (false !== ($content = readdir($handle))) {
			if ($content != "." && $content != ".." && $content != "_thumbs" && $content != ".svn") {
				if(file_exists($album_location.'/'.$album.'/_thumbs/'.$content)) {
					echo "<div class=\"album-item\">";
					echo "<a rel=\"imagezoom[$album]\" href=\"$album_location/$album/$content\"><img src=\"$album_location/$album/_thumbs/$content\" height=\"80\" width=\"80\" alt=\"\" /></a>";
					echo "</div>";
				} else {
					echo "<div class=\"album-item\">";
					echo "<a rel=\"imagezoom[$album]\" href=\"$album_location/$album/$content\"><img src=\"$album_location/$album/$content\" height=\"80\" width=\"80\" alt=\"\" /></a>";
					echo "</div>";
				}
			}
		} closedir($handle);
	} else echo "<p>&#160;</p><p>".$ccms['lang']['system']['error_value']."</p>";
	echo "<p style=\"clear:both;\"><a href=\"javascript:history.go(-1);\">".$ccms['lang']['album']['tooverview']."</a></p>";
}?>