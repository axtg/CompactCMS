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
	$base = str_replace('\\','/',dirname(dirname(dirname(dirname(dirname(__FILE__))))));
	define('BASE_PATH', $base);
}

// Include general configuration
/*MARKER*/require_once(BASE_PATH . '/lib/sitemap.php');

// security check done ASAP
if(!checkAuth() || empty($_SESSION['rc1']) || empty($_SESSION['rc2'])) 
{ 
	die("No external access to file");
}


// Set default variables


$do	= getGETparam4IdOrNumber('do');
$status = getGETparam4IdOrNumber('status');
$status_message = getGETparam4DisplayHTML('msg');

// Get permissions
$perm = $db->SelectSingleRowArray($cfg['db_prefix'].'cfgpermissions');
if (!$perm) $db->Kill("INTERNAL ERROR: 1 permission record MUST exist!");


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>Manage users</title>
	<link rel="stylesheet" type="text/css" href="../../../img/styles/base.css,liquid.css,layout.css,sprite.css" />
	<script type="text/javascript" src="../../../../lib/includes/js/mootools.js" charset="utf-8"></script>
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
	</script>
	<script type="text/javascript" charset="utf-8">
window.addEvent('domready',function()
	{
		new FormValidator($('addUser'),
		{
			onFormValidate:function(passed,form,event)
			{
				if(passed)
					form.submit();
			}
		});
	});
	</script>
	<script type="text/javascript" src="passwordcheck.js" charset="utf-8"></script>
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
					if (!$db->Query("SELECT * FROM `".$cfg['db_prefix']."users` ORDER BY userID ASC"))
						$db->Kill();
					$db->MoveFirst();
					
					// Loop through results
					while (!$db->EndOfSeek()) 
					{
						// Fill $row with values
						$row = $db->Row();
					
						// Define $isEven for alternate table coloring
						if($i%2 != 1) 
						{
							echo '<tr style="background-color: #E6F2D9;">';
						} 
						else 
						{ 
							echo '<tr>';
						} 
						?>
							<td>
							<?php 
							if($perm['manageUsers']>0 && $_SESSION['ccms_userLevel']>=$perm['manageUsers']&&$_SESSION['ccms_userLevel']>=$row->userLevel&&$_SESSION['ccms_userID']!=$row->userID) 
							{ 
							?>	
								<input type="checkbox" name="userID[]" value="<?php echo $row->userID; ?>" id="userID" />
							<?php 
							} 
							else 
							{
								echo "&#160;"; 
							}
							?>
							</td>
							<td>
							<?php 
							if($_SESSION['ccms_userID']==$row->userID||($perm['manageUsers']>0 && $_SESSION['ccms_userLevel']>=$perm['manageUsers']&&$_SESSION['ccms_userLevel']>=$row->userLevel)) 
							{ 
							?>
								<span class="ss_sprite ss_user_edit"><a href="user.Edit.php?userID=<?php echo $row->userID; ?>"><?php echo $row->userName; ?></a></span>
							<?php 
							} 
							else 
							{
								echo $row->userName; 
							}
							?>
							</td>
							<td><?php echo substr($row->userFirst,0,1); ?>. <?php echo $row->userLast; ?></td>
							<td><span class="ss_sprite ss_email"><a href="mailto:<?php echo $row->userEmail; ?>"><?php echo $row->userEmail; ?></a></span></td>
							<td><?php echo ($row->userActive==1?$ccms['lang']['backend']['yes']:$ccms['lang']['backend']['no']); ?></td>
							<td><?php echo $row->userLevel; ?></td>
							<td><?php echo date('d-m-\'y',strtotime($row->userLastlog)); ?></td>
						</tr>
						<?php 
						$i++; 
					} 
					?>
				</table>
				<hr class="space"/>
				<?php 
				if($perm['manageUsers']>0 && $_SESSION['ccms_userLevel']>=$perm['manageUsers']) 
				{ 
				?>
					<button type="submit" onclick="return confirmation();" name="deleteUser"><span class="ss_sprite ss_user_delete"><?php echo $ccms['lang']['backend']['delete']; ?></span></button>
				<?php 
				} 
				?>
			</form>
		</div>
		
		<div class="span-6">
			<h2><?php echo $ccms['lang']['users']['createuser']; ?></h2>
			<?php 
			if($perm['manageUsers']>0 && $_SESSION['ccms_userLevel']>=$perm['manageUsers']) 
			{ 
			?>
				<form action="../../process.inc.php?action=add-user" method="post" id="addUser" accept-charset="utf-8">
					<label for="userName"><?php echo $ccms['lang']['users']['username']; ?></label><input type="text" class="minLength:3 text" name="user" value="" id="userName" />
					<label for="userPass"><?php echo $ccms['lang']['users']['password']; ?><br/><a href="#" class="small ss_sprite ss_bullet_key" onclick="randomPassword(8);"><?php echo $ccms['lang']['auth']['generatepass']; ?></a></label><input type="text" onkeyup="passwordStrength(this.value)" class="minLength:6 text" name="userPass" value="" id="userPass" />
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
						<?php 
						if($_SESSION['ccms_userLevel']>1) 
						{ 
						?>
							<option value="2"><?php echo $ccms['lang']['permission']['level2']; ?></option>
						<?php 
						} 
						if($_SESSION['ccms_userLevel']>2) 
						{ 
						?>
							<option value="3"><?php echo $ccms['lang']['permission']['level3']; ?></option>
						<?php 
						} 
						if($_SESSION['ccms_userLevel']>3) 
						{ 
						?>
							<option value="4"><?php echo $ccms['lang']['permission']['level4']; ?></option>
						<?php 
						} 
						?>
					</select>
					<div>
					<label><?php echo $ccms['lang']['users']['active']; /* [i_a] and make sure either yes or no are selected to begin with; pick 'no' as the default here */ ?></label>
						<label for="userActive1" style="display:inline;font-weight:normal;"><?php echo $ccms['lang']['backend']['yes']; ?></label><input type="radio" class="validate-one-required" name="userActive" value="1" id="userActive1" />	
						<img src="../../../img/spacer.gif" height="10" width="50" alt=" "/>
						<label for="userActive0" style="display:inline;font-weight:normal;"><?php echo $ccms['lang']['backend']['no']; ?></label><input type="radio" name="userActive" value="0" id="userActive0" "checked" />
					</div>
					<hr class="space"/>
					<p class="right"><button type="submit"><span class="ss_sprite ss_user_add"><?php echo $ccms['lang']['forms']['createbutton']; ?></span></button></p>
				</form>
			<?php 
			} 
			else 
				echo $ccms['lang']['auth']['featnotallowed']; 
			?>
		</div>
	</div>	
</body>
</html>
