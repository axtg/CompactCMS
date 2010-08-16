<?php 
// Default albums location
$album_path	= BASE_PATH.'/media/albums';
$album_url	= $cfg['rootdir'].'media/albums';

// Read through selected album, get first and count all
function fileList($d){
	foreach(array_diff(scandir($d),array('.','..','index.html')) as $f) {
		if(is_file($d.'/'.$f)) {
			$l[] = $f;
   		}
   	} return $l;
} 

// Get all the albums in the default media/albums location
if($handle = opendir($album_path)) {
	while (false !== ($file = readdir($handle))) {
		if ($file != "." && $file != ".." && $file != "index.html") {
			$albums[] = $file;
    	}
	} closedir($handle);
}
?>

<!-- additional style and code -->
<link rel="stylesheet" href="<?php echo $cfg['rootdir']; ?>lib/modules/lightbox/resources/style.css" type="text/css" media="screen" title="lightbox" charset="utf-8" />
<script type="text/javascript" src="<?php echo $cfg['rootdir']; ?>lib/modules/lightbox/resources/script.js" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">window.addEvent("domready", function() {initImageZoom({loadImage: '<?php echo $cfg['rootdir']."lib/modules/lightbox/resources/loading.gif"; ?>'});});</script>

<!-- lay-out -->
<?php if(!isset($_GET['id'])&&empty($_GET['id'])) { ?>
	<?php if(!empty($albums)) {
		foreach ($albums as $i => $file) { 
			// Get the images in an album
			$image = fileList($album_path.'/'.$file);
			
			// If album is not empty and thumbnail is found
			if(file_exists($album_path.'/'.$file.'/_thumbs/'.$image['0'])) {
				echo "<div class=\"album-item\">";
				echo "<a href=\"".$cfg['rootdir'].$_GET['page']."/".$file.".html\">";
				echo "<img src=\"$album_url/$file/_thumbs/".$image['0']."\" height=\"80\" width=\"80\"/><br/>";
				echo ucfirst($file)." (".count($image).")</a></div>";	
			} 
			// If album does exist, but no contents (empty album)
			elseif(!is_file($album_path."/".$file."/".$image['0'])) {
				echo "<div class=\"album-item\">";
				echo "<img src=\"".$cfg['rootdir']."lib/modules/lightbox/empty.png\" height=\"80\" width=\"80\" /><br/>";
				echo ucfirst($file)." (0)</div>";	
			} 
			// Otherwise show the first image of non-empty album and scale it to 80x80
			else {
				echo "<div class=\"album-item\">";
				echo "<a href=\"".$cfg['rootdir'].$_GET['page']."/".$file.".html\">";
				echo "<img src=\"".$album_url."/".$file."/".$image['0']."\" height=\"80\" width=\"80\"/><br/>";
				echo ucfirst($file)." (".count($image).")</a></div>";	
			}
		} 
	} else echo $ccms['lang']['album']['noalbums'];
} elseif(isset($_GET['id'])&&!empty($_GET['id'])) {
	$album = htmlentities($_GET['id']);
	echo "<h3>".$ccms['lang']['album']['album']." ".ucfirst($album)."</h3>";
	echo "<a href=\"javascript:history.go(-1);\">".$ccms['lang']['album']['tooverview']."</a><br/>";

	if($handle = @opendir($album_path.'/'.$album)) {
		while (false !== ($content = readdir($handle))) {
			if ($content != "." && $content != ".." && $content != "_thumbs" && $content != ".svn") {
				if(file_exists($album_path.'/'.$album.'/_thumbs/'.$content)) {
					echo "<div class=\"album-item\">";
					echo "<a rel=\"imagezoom[$album]\" href=\"$album_url/$album/$content\" title=\"$content\"><img src=\"$album_url/$album/_thumbs/$content\" height=\"80\" width=\"80\" alt=\"\" /></a>";
					echo "</div>";
				} else {
					echo "<div class=\"album-item\">";
					echo "<a rel=\"imagezoom[$album]\" href=\"$album_url/$album/$content\" title=\"$content\"><img src=\"$album_url/$album/$content\" height=\"80\" width=\"80\" alt=\"\" /></a>";
					echo "</div>";
				}
			}
		} closedir($handle);
	} else echo "<p>&#160;</p><p>".$ccms['lang']['system']['error_value']."</p>";
	echo "<p style=\"clear:both;\"><a href=\"".$cfg['rootdir'].$_GET['page'].".html\">".$ccms['lang']['album']['tooverview']."</a></p>";
}?>