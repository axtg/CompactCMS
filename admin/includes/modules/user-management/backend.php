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
define("COMPACTCMS_CODE", 1);


// Define default location
if (!defined('BASE_PATH'))
{
	$base = str_replace('\\','/',dirname(dirname(dirname(dirname(dirname(__FILE__))))));
	define('BASE_PATH', $base);
}

// Include general configuration
require_once(BASE_PATH . '/lib/sitemap.php');

// Set default variables
$canarycage	= md5(session_id());
$currenthost= md5($_SERVER['HTTP_HOST']);
$do 		= (isset($_GET['do'])?$_GET['do']:null);

// Get permissions
$perm = $db->QuerySingleRowArray("SELECT * FROM ".$cfg['db_prefix']."cfgpermissions");
?>
<?php if(md5(session_id())==$canarycage && isset($_SESSION['rc1']) && !empty($_SESSION['rc2']) && md5($_SERVER['HTTP_HOST']) == $currenthost) { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title>Manage users</title>
		<link rel="stylesheet" type="text/css" href="../../../img/styles/base.css,liquid.css,layout.css,sprite.css" />
		<script type="text/javascript" src="../../../../lib/includes/js/mootools.js" charset="utf-8"></script>
		<script type="text/javascript" charset="utf-8">function confirmation(){var answer=confirm('<?php echo $ccms['lang']['backend']['confirmdelete']; ?>');if(answer){try{return true;}catch(e){}}else{return false;}}</script>
		<script type="text/javascript" charset="utf-8">window.addEvent('domready',function(){new FormValidator($('addUser'),{onFormValidate:function(passed,form,event){if(passed)form.submit();}});});</script>
		<script type="text/javascript" charset="utf-8">
		function passwordStrength(password){var score=0;if(password.length>5)score++;if((password.match(/[a-z]/))&&(password.match(/[A-Z]/)))score++;if(password.match(/\d+/))score++;if(password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/))score++;if(password.length>12)score++;document.getElementById("passwordStrength").className="strength"+score;}</script>
		<script type="text/javascript" charset="utf-8">function randomPassword(length){chars="abcdefghijkmNPQRSTUVWXYZ123456789!@#$%";pass="";for(x=0;x<length;x++){i=Math.floor(Math.random()*38);pass+=chars.charAt(i);}passwordStrength(pass);return document.getElementById("userPass").value=pass;}</script>
	</head>
<body>
	<div class="module">
	
		<?php if(isset($_GET['status'])&&isset($_GET['action'])) { ?>
			<div class="<?php echo $_GET['status'];?> center"><strong><?php echo ucfirst($_GET['action']);?></strong></div>
		<?php } ?>
		
		<div class="span-16 colborder">
			<h2><?php echo $ccms['lang']['users']['overviewusers']; ?></h2>
			<form action="../../process.inc.php?action=delete-user" method="post" accept-charset="utf-8">
			
			<table border="0" cellspacing="5" cellpadding="5">
				<tr>
					<th>&#160;</th>
					<th><?php echo $ccms['lang']['users']['user']; ?></th>
					<th><?php echo $ccms['lang']['users']['name']; ?></th>
					<th><?php echo $ccms['lang']['users']['email']; ?></th>
					<th><?php echo $ccms['lang']['users']['active']; ?></th>
					<th><?php echo $ccms['lang']['users']['level']; ?></th>
					<th><?php echo $ccms['lang']['users']['lastlog']; ?></th>
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
						<span class="ss_sprite ss_user_edit"><a href="user.Edit.php?userID=<?php echo $row->userID; ?>"><?php echo $row->userName; ?></a></span>
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
				<?php if($_SESSION['ccms_userLevel']>=$perm['manageUsers']) { ?><button type="submit" onclick="return confirmation();" name="deleteUser"><span class="ss_sprite ss_user_delete"><?php echo $ccms['lang']['backend']['delete']; ?></span></button><?php } ?>
			</form>
			
		</div>
		
		<div class="span-6">
			
			<h2><?php echo $ccms['lang']['users']['createuser']; ?></h2>
			<?php if($_SESSION['ccms_userLevel']>=$perm['manageUsers']) { ?>
			<form action="../../process.inc.php?action=add-user" method="post" id="addUser" accept-charset="utf-8">
				<label for="userName"><?php echo $ccms['lang']['users']['username']; ?></label><input type="text" class="minLength:3 text" name="user" value="" id="userName" />
				<label for="userPass"><?php echo $ccms['lang']['users']['password']; ?><br/><a href="#" class="small ss_sprite ss_bullet_key" onclick="randomPassword(8);"><?php echo $ccms['lang']['auth']['generatepass']; ?></a></label><input type="text" onkeyup="passwordStrength(this.value)" class="minLength:6 text" name="pass" value="" id="userPass" />
				<div class="clear center">
					<div id="passwordStrength" class="strength0"></div><br/>
				</div>
				<label for="userFirstname"><?php echo $ccms['lang']['users']['firstname']; ?></label><input type="text" class="required text" name="userFirstname" value="" id="userFirstname" />
				<label for="userLastname"><?php echo $ccms['lang']['users']['lastname']; ?></label><input type="text" class="required text" name="userLastname" value="" id="userLastname" />
				<label for="userEmail"><?php echo $ccms['lang']['users']['email']; ?></label><input type="text" class="required validate-email text" name="userEmail" value="" id="userEmail" />
				
				<hr class="space"/>
				<label for="userLevel"><?php echo $ccms['lang']['users']['userlevel']; ?></label>
				<select name="userLevel" class="required text" id="userLevel" size="1">
					<option value="1"><?php echo $ccms['lang']['permission']['level1']; ?></option>
					<?php if($_SESSION['ccms_userLevel']>1) { ?><option value="2"><?php echo $ccms['lang']['permission']['level2']; ?></option>
					<?php } if($_SESSION['ccms_userLevel']>2) { ?><option value="3"><?php echo $ccms['lang']['permission']['level3']; ?></option>
					<?php } if($_SESSION['ccms_userLevel']>3) { ?><option value="4"><?php echo $ccms['lang']['permission']['level4']; ?></option><?php } ?>
				</select>
				<div>
				<label><?php echo $ccms['lang']['users']['active']; ?></label>
					<label for="userActive1" style="display:inline;font-weight:normal;"><?php echo $ccms['lang']['backend']['yes']; ?></label><input type="radio" class="validate-one-required" name="userActive" value="1" id="userActive1" />	
					<img src="../../../img/spacer.gif" height="10" width="50" alt=" "/>
					<label for="userActive0" style="display:inline;font-weight:normal;"><?php echo $ccms['lang']['backend']['no']; ?></label><input type="radio" name="userActive" value="0" id="userActive0" />
				</div>
				<hr class="space"/>
				<p class="right"><button type="submit"><span class="ss_sprite ss_user_add"><?php echo $ccms['lang']['forms']['createbutton']; ?></span></button></p>
			</form>
			<?php } else echo $ccms['lang']['auth']['featnotallowed']; ?>
		</div>

	</div>	
</body>
</html>
<?php } else die("No external access to file");?>