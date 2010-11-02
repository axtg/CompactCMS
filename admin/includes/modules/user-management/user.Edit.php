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
define('CCMS_PERFORM_MINIMAL_INIT', true);


// Define default location
if (!defined('BASE_PATH'))
{
	$base = str_replace('\\','/',dirname(dirname(dirname(dirname(dirname(__FILE__))))));
	define('BASE_PATH', $base);
}

// Include general configuration
/*MARKER*/require_once(BASE_PATH . '/lib/sitemap.php');

// Set default variables


$do	= getGETparam4IdOrNumber('do');

// Open recordset for specified user
$userID = getGETparam4Number('userID');

if($userID!=null) {
	$row = $db->QuerySingleRow("SELECT * FROM `".$cfg['db_prefix']."users` WHERE userID = $userID");
} else die($ccms['lang']['system']['error_general']);

// Get permissions
$perm = $db->QuerySingleRowArray("SELECT * FROM ".$cfg['db_prefix']."cfgpermissions");


if(isset($_SESSION['rc1']) && !empty($_SESSION['rc2']) && checkAuth()) 
{ 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>Edit users</title>
	<link rel="stylesheet" type="text/css" href="../../../img/styles/base.css,liquid.css,layout.css,sprite.css" />
	<script type="text/javascript" src="../../../../lib/includes/js/mootools.js" charset="utf-8"></script>
	<!-- Check form and post -->
	<script type="text/javascript" charset="utf-8">
	window.addEvent('domready', function()
	{
		new FormValidator($('userDetailForm'), {onFormValidate: function(passed, form, event){if (passed) form.submit();}});
		new FormValidator($('userPassForm'), {onFormValidate: function(passed, form, event){if (passed) form.submit();}});
		new FormValidator($('userLevelForm'), {onFormValidate: function(passed, form, event){if (passed) form.submit();}});
	});
	</script>
	<script type="text/javascript" src="passwordcheck.js" charset="utf-8"></script>
</head>

<body>
	<div class="module">
		<?php if(isset($_GET['status'])&&isset($_GET['action'])) 
		{ 
		?>
			<div class="<?php echo htmlspecialchars($_GET['status']);?> center"><strong><?php echo ucfirst(htmlspecialchars($_GET['action']));?></strong></div>
		<?php 
		} 
		?>
		
		<p class="clear right"><span class="ss_sprite ss_arrow_undo"><a href="backend.php"><?php echo $ccms['lang']['backend']['tooverview']; ?></a></span></p>
		
		<?php // Check authority 
		if($perm['manageUsers']==0 || $_SESSION['ccms_userLevel']<$perm['manageUsers'] || $row->userLevel>$_SESSION['ccms_userLevel']) 
		{
			if($_SESSION['ccms_userID']!=$userID) 
			{
				die("[ERR802] ".$ccms['lang']['auth']['featnotallowed']);
			}
		} 
		?>
		
		<div class="span-13 colborder">
			<h2><?php echo $ccms['lang']['users']['editdetails']; ?></h2>
			<form action="../../process.inc.php?action=edit-user-details" id="userDetailForm" method="post" accept-charset="utf-8">
				<label><?php echo $ccms['lang']['users']['username']; ?></label><span style="display:block;height:30px;"><?php echo $row->userName; ?></span>
				<label for="first"><?php echo $ccms['lang']['users']['firstname']; ?></label><input type="text" class="required text" name="first" value="<?php echo $row->userFirst; ?>" id="first" />
				<label for="last"><?php echo $ccms['lang']['users']['lastname']; ?></label><input type="text" class="required text" name="last" value="<?php echo $row->userLast; ?>" id="last" />
				<label for="email"><?php echo $ccms['lang']['users']['email']; ?></label><input type="text" class="required validate-email text" name="email" value="<?php echo $row->userEmail; ?>" id="email" />
				
				<input type="hidden" name="userID" value="<?php echo $row->userID; ?>" id="userID" />
				<p class="span-6 right"><button type="submit"><span class="ss_sprite ss_user_edit"><?php echo $ccms['lang']['forms']['savebutton'];?></span></button></p>
			</form>
			
		</div>
		
		<div class="span-9">
			<?php 
			if($_SESSION['ccms_userID']==$row->userID||($perm['manageUsers']>0 && $_SESSION['ccms_userLevel']>=$perm['manageUsers']&&$_SESSION['ccms_userLevel']>=$row->userLevel)) 
			{ 
			?>
			<h2><?php echo $ccms['lang']['users']['editpassword']; ?></h2>
			<div class="prepend-1">
				<form action="../../process.inc.php?action=edit-user-password" id="userPassForm" method="post" accept-charset="utf-8">
					<label for="userPass"><?php echo $ccms['lang']['users']['password']; ?><br/><a href="#" class="small ss_sprite ss_bullet_key" onclick="randomPassword(8);"><?php echo $ccms['lang']['auth']['generatepass']; ?></a></label><input type="text" onkeyup="passwordStrength(this.value)" style="width:200px;" class="required minLength:6 text" name="userPass" value="" id="userPass" />
					<div class="clear center">
						<div id="passwordStrength" class="strength0"></div><br/>
					</div>
					<label for="cpass"><?php echo $ccms['lang']['users']['cpassword']; ?></label><input type="password" style="width:200px;" class="validate-match matchInput:'userPass' matchName:'Password' required minLength:6 text" name="cpass" value="" id="cpass" />
					
					<input type="hidden" name="userID" value="<?php echo $row->userID; ?>" id="userID" />
					<p class="span-6 right"><button type="submit"><span class="ss_sprite ss_key"><?php echo $ccms['lang']['forms']['savebutton'];?></span></button></p>
				</form>
			</div>
			
			<hr/>
			
			<h2><?php echo $ccms['lang']['users']['accountcfg']; ?></h2>
			<?php 
			} 
			if($perm['manageUsers']>0 && $_SESSION['ccms_userLevel']>=$perm['manageUsers']&&$_SESSION['ccms_userLevel']>=$row->userLevel) 
			{ 
			?>
			<div class="prepend-1">
				<form action="../../process.inc.php?action=edit-user-level" id="userLevelForm" method="post" accept-charset="utf-8">
					<label for="userLevel"><?php echo $ccms['lang']['users']['userlevel']; ?></label>
					<select name="userLevel" class="required" id="userLevel" size="1">
						<option value="1" <?php echo ($row->userLevel==1?"selected='SELECTED'":null); ?>><?php echo $ccms['lang']['permission']['level1']; ?></option>
						<?php 
						if($_SESSION['ccms_userLevel'] > 1) 
						{ 
						?>
							<option value="2" <?php echo ($row->userLevel==2?"selected='SELECTED'":null); ?>><?php echo $ccms['lang']['permission']['level2']; ?></option>
						<?php 
						} 
						if($_SESSION['ccms_userLevel'] > 2) 
						{ 
						?>
							<option value="3" <?php echo ($row->userLevel==3?"selected='SELECTED'":null); ?>><?php echo $ccms['lang']['permission']['level3']; ?></option>
						<?php 
						} 
						if($_SESSION['ccms_userLevel'] > 3) 
						{ 
						?>
							<option value="4" <?php echo ($row->userLevel==4?"selected='SELECTED'":null); ?>><?php echo $ccms['lang']['permission']['level4']; ?></option>
						<?php 
						} 
						?>
					</select>
					<hr class="space"/>
					<div>
					<label><?php echo $ccms['lang']['users']['active']; ?></label>
						<label for="userActive1" style="display:inline;font-weight:normal;"><?php echo $ccms['lang']['backend']['yes']; ?></label>
						<input type="radio" name="userActive" <?php echo ($row->userActive==1?"checked='CHECKED'":null); ?> value="1" id="userActive1" />	
						
						<img src="../../../img/spacer.gif" height="10" width="50" alt=" "/>
						
						<label for="userActive0" style="display:inline;font-weight:normal;"><?php echo $ccms['lang']['backend']['no']; ?></label>
						<input type="radio" name="userActive" class="validate-one-required" <?php echo ($row->userActive==0?"checked='CHECKED'":null); ?> value="0" id="userActive0" />
					</div>
					<hr class="space"/>		
				
					<input type="hidden" name="userID" value="<?php echo $row->userID; ?>" id="userID" />
					<p class="span-6 right"><button type="submit"><span class="ss_sprite ss_disk"><?php echo $ccms['lang']['forms']['savebutton'];?></span></button></p>
				</form>
			</div>
			<?php } else echo $ccms['lang']['auth']['featnotallowed']; ?>
		</div>
		
		<p class="clear right"><span class="ss_sprite ss_arrow_undo"><a href="backend.php"><?php echo $ccms['lang']['backend']['tooverview']; ?></a></span></p>
	
	</div>
</body>
</html>
<?php 
} 
else 
	die("No external access to file");
?>