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

// Include functions
include_once('functions.php');

// Get permissions
$perm = $db->QuerySingleRowArray("SELECT * FROM ".$cfg['db_prefix']."cfgpermissions");
?>
<?php if(md5(session_id())==$canarycage && isset($_SESSION['rc1']) && !empty($_SESSION['rc2']) && md5($_SERVER['HTTP_HOST']) == $currenthost) { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title>Manage users</title>
		<link rel="stylesheet" type="text/css" href="../../../img/styles/base.css,layout.css,sprite.css" />
	</head>
<body>
	<div class="module">
	
		<?php if(isset($_GET['status'])&&isset($_GET['action'])) { ?>
			<div class="<?php echo $_GET['status'];?> center"><strong><?php echo ucfirst($_GET['action']);?></strong></div>
		<?php } ?>
		
		<div class="span-15 colborder">
			<h2>Overview CMS users</h2>
			<form action="../../process.inc.php?action=delete-user" method="post" accept-charset="utf-8">
			
			<table border="0" cellspacing="5" cellpadding="5">
				<tr>
					<th>&#160;</th>
					<th>User</th>
					<th>Name</th>
					<th>E-mail</th>
					<th>Active</th>
					<th>Level</th>
					<th>Last log</th>
				</tr>
			
			<?php 
			// Get previously opened DB stream
			$i=0;
			// Open recordset for all users with levels <= to own
			$db->Query("SELECT * FROM `".$cfg['db_prefix']."users` ORDER BY userID ASC");
			$db->MoveFirst();
			
			// Loop through results
			while (!$db->EndOfSeek()) {
	    		// Fill $row with values
				$row = $db->Row();
			
				// Define $isEven for alternate table coloring
				if($i%2 != '1') {
					echo '<tr style="background-color: #E6F2D9;">';
				} else { 
					echo '<tr>';
				} ?>
					<td>
					<?php if($_SESSION['ccms_userLevel']>=$perm['manageUsers']&&$_SESSION['ccms_userLevel']>=$row->userLevel&&$_SESSION['ccms_userID']!=$row->userID) { ?>	
						<input type="checkbox" name="userID[]" value="<?php echo $row->userID; ?>" id="userID" />
					<?php } else echo "&#160;"; ?>
					</td>
					<td>
					<?php if($_SESSION['ccms_userID']==$row->userID||$_SESSION['ccms_userLevel']>=$perm['manageUsers']&&$_SESSION['ccms_userLevel']>=$row->userLevel) { ?>
						<a href="user.Edit.php?userID=<?php echo $row->userID; ?>"><?php echo $row->userName; ?></a>
					<?php } else echo $row->userName; ?>
					</td>
					<td><?php echo substr($row->userFirst,0,1); ?>. <?php echo $row->userLast; ?></td>
					<td><span class="ss_sprite ss_email"><a href="mailto:<?php echo $row->userEmail; ?>"><?php echo $row->userEmail; ?></a></span></td>
					<td><?php echo ($row->userActive==1?$ccms['lang']['backend']['yes']:$ccms['lang']['backend']['no']); ?></td>
					<td><?php echo $row->userLevel; ?></td>
					<td><?php echo date('d-m-\'y',strtotime($row->userLastlog)); ?></td>
				</tr>
				<?php $i++; } ?>
			</table>
			<hr class="space"/>
				<?php if($_SESSION['ccms_userLevel']>=$perm['manageUsers']) { ?><button type="submit" name="deleteUser"><span class="ss_sprite ss_user_delete">Delete</span></button><?php } ?>
			</form>
			
		</div>
		
		<div class="span-5">
			
			<h2>Create a user</h2>
			<?php if($_SESSION['ccms_userLevel']>=$perm['manageUsers']) { ?>
			<form action="../../process.inc.php?action=add-user" method="post" accept-charset="utf-8">
				<label for="userName">Username</label><input type="text" class="text" name="user" value="" id="userName" />
				<label for="userPass">Password</label><input type="text" class="text" name="pass" value="<?php echo createPassword(); ?>" id="userPass" />
				<label for="userFirstname">First name</label><input type="text" class="text" name="userFirstname" value="" id="userFirstname" />
				<label for="userLastname">Last name</label><input type="text" class="text" name="userLastname" value="" id="userLastname" />
				<label for="userEmail">E-mail</label><input type="text" class="text" name="userEmail" value="" id="userEmail" />
				
				<hr class="space"/>
				<label for="userLevel">User level</label>
				<select name="userLevel" id="userLevel" size="1">
					<option value="1">User (Level = 1)</option>
					<?php if($_SESSION['ccms_userLevel']>1) { ?><option value="2">Editor (Level = 2)</option>
					<?php } if($_SESSION['ccms_userLevel']>2) { ?><option value="3">Manager (Level = 3)</option>
					<?php } if($_SESSION['ccms_userLevel']>3) { ?><option value="4">Administrator (Level = 4)</option><?php } ?>
				</select>
				<label>Activated</label>
					<label for="userActive1" style="display:inline;font-weight:normal;">Yes</label><input type="radio" name="userActive" value="1" id="userActive1" />	
					<img src="../../../img/spacer.gif" height="10" width="50" alt=" "/>
					<label for="userActive0" style="display:inline;font-weight:normal;">No</label><input type="radio" name="userActive" value="0" id="userActive0" />
				<hr class="space"/>
				<p class="right"><button type="submit"><span class="ss_sprite ss_user_add"><?php echo $ccms['lang']['forms']['createbutton']; ?></span></button></p>
			</form>
			<?php } else echo $ccms['lang']['auth']['featnotallowed']; ?>
		</div>

	</div>	
</body>
</html>
<?php } else die("No external access to file");?>