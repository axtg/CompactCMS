<?php
/* ************************************************************
Copyright (C) 2008 - 2009 by Xander Groesbeek (CompactCMS.nl)
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
?>
<?php if(md5(session_id())==$canarycage && isset($_SESSION['rc1']) && !empty($_SESSION['rc2']) && md5($_SERVER['HTTP_HOST']) == $currenthost) { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title>Back-up &amp; Restore module</title>
		<link rel="stylesheet" type="text/css" href="../../../img/styles/base.css,liquid.css,layout.css,sprite.css" />
	</head>
<body class="module">
		
</body>
</html>
<?php } else die("No external access to file");?>