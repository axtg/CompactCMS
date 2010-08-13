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

// Set default variables
$canarycage	= md5(session_id());
$currenthost= md5($_SERVER['HTTP_HOST']);
$do 		= (isset($_GET['do'])?$_GET['do']:null);

// Include back-up functions
include_once('functions.php');

// Open recordset for specified user
$userID = (isset($_GET['userID']) && is_numeric($_GET['userID'])?$_GET['userID']:null);

if($userID!=null) {
	$row = $db->QuerySingleRow("SELECT * FROM `".$cfg['db_prefix']."users` WHERE userID = $userID");
} else die($ccms['lang']['system']['error_general']);

?>
<?php if(md5(session_id())==$canarycage && isset($_SESSION['rc1']) && !empty($_SESSION['rc2']) && md5($_SERVER['HTTP_HOST']) == $currenthost) { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>Edit users</title>
	<link rel="stylesheet" type="text/css" href="../../../img/styles/base.css,layout.css,sprite.css" />
</head>

<body>
	<div class="module">
		<p class="clear right"><span class="ss_sprite ss_arrow_undo"><a href="backend.php">Back to overview</a></span></p>
		
		<div class="span-10 colborder">
			<?php
			// Check authority
			if($row->userLevel>$_SESSION['ccms_userLevel']) {
				die("[ERR802] ".$ccms['lang']['system']['error_general']);
			}
			?>
			<h2>Edit user's personal details</h2>
			<form action="user_submit" method="post" accept-charset="utf-8">
				<label>Username</label><span style="display:block;height:30px;"><?php echo $row->userName; ?></span>
				<label for="first">First name</label><input type="text" class="text" name="first" value="<?php echo $row->userFirst; ?>" id="first" />
				<label for="last">Last name</label><input type="text" class="text" name="last" value="<?php echo $row->userLast; ?>" id="last" />
				<label for="email">E-mail</label><input type="text" class="text" name="email" value="<?php echo $row->userEmail; ?>" id="email" />
				<p class="span-6 right"><button type="submit"><span class="ss_sprite ss_user_edit"><?php echo $ccms['lang']['forms']['savebutton'];?></span></button></p>
			</form>
			
		</div>
		
		<div class="span-8">
			<h2>Edit user's password</h2>
			<form action="user_submit" method="post" accept-charset="utf-8">
				<label for="pass">Password</label><input type="text" class="text" name="pass" value="" id="pass" />
				<p class="span-6 right"><button type="submit"><span class="ss_sprite ss_key"><?php echo $ccms['lang']['forms']['savebutton'];?></span></button></p>
			</form>
			
			<hr/>
			
			<h2>Account settings</h2>
			<form action="user_submit" method="get" accept-charset="utf-8">
				<label for="userLevel">User level</label>
				<select name="userLevel" id="userLevel" size="1">
					<option value="1" <?php echo ($row->userLevel==1?"selected='SELECTED'":null); ?>>User (Level = 1)</option>
					<?php if($_SESSION['ccms_userLevel']>1) { ?>
						<option value="2" <?php echo ($row->userLevel==2?"selected='SELECTED'":null); ?>>Editor (Level = 2)</option>
					<?php } if($_SESSION['ccms_userLevel']>2) { ?>
						<option value="3" <?php echo ($row->userLevel==3?"selected='SELECTED'":null); ?>>Manager (Level = 3)</option>
					<?php } if($_SESSION['ccms_userLevel']>3) { ?>
					<option value="5" <?php echo ($row->userLevel==5?"selected='SELECTED'":null); ?>>Administrator (Level = 5)</option>
					<?php } ?>
				</select>
				<hr class="space"/>
				<label>Active</label>
					<label for="userActive1" style="display:inline;font-weight:normal;">Yes</label>
					<input type="radio" name="userActive" <?php echo ($row->userActive==1?"checked='CHECKED'":null); ?> value="1" id="userActive1" />	
					
					<img src="../../../img/spacer.gif" height="10" width="50" alt=" "/>
					
					<label for="userActive0" style="display:inline;font-weight:normal;">No</label>
					<input type="radio" name="userActive" <?php echo ($row->userActive==0?"checked='CHECKED'":null); ?> value="0" id="userActive0" />
				<hr class="space"/>		
			
				<p class="span-6 right"><button type="submit"><span class="ss_sprite ss_disk"><?php echo $ccms['lang']['forms']['savebutton'];?></span></button></p>
			</form>
		</div>
		
		<p class="clear right"><span class="ss_sprite ss_arrow_undo"><a href="backend.php">Back to overview</a></span></p>
	
	</div>
</body>
</html>
<?php } else die("No external access to file");?>