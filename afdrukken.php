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

// This file loads the appropriate configuration
require_once(dirname(__FILE__) . '/lib/sitemap.php'); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $ccms['language']; ?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="[PRINT FRIENDLY] <?php echo $ccms['desc']; ?>" />
	<meta name="keywords" content="<?php echo $ccms['keywords']; ?>" />
	<title>Print: <?php echo $ccms['title']; ?></title>
</head>

<body style="background: #fff none;">

<p style="text-align: center;">
	<a name="top"></a>
	<strong><a href="<?php echo $ccms['rootdir'];?><?php echo ($_GET['page']!=$cfg['homepage'])?$_GET['page'].'.html':null; ?>"><?php echo $ccms['lang']['system']['tooriginal']; ?></a></strong>
</p>
<hr />

<table width="80%" style="margin: 20px; border: none;">
  <tr>
    <td><h1><?php echo $ccms['pagetitle']; ?></h1></td>
  </tr>
  <tr>
    <td><h3><?php echo $ccms['subheader']; ?></h3></td>
  </tr>
  <tr>
    <td>
	<?php echo $ccms['content']; ?>
    </td>
  </tr>
</table>
<hr />

<p style="text-align:center;">
	<!-- Please consider keeping a link (invisible to visitors). It will help the search engine ranking. E.g.: -->
	<div style="display: none;"><a href="http://www.compactcms.nl">Maintained with CompactCMS.nl</a></div>
	<!-- That's it! Of course you're free to show off CompactCMS anywhere on your website -->
	<p>&copy; <?php echo date('Y')." ".$ccms['sitename']; ?> | Maintained with <a href="http://www.compactcms.nl">CompactCMS</a></p>
</p>

</body>
</html>
