<?php
/* ************************************************************
Copyright (C) 2008 - 2010 by Xander Groesbeek (CompactCMS.nl)
Revision:   CompactCMS - v 1.4.2
	
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


// Include general configuration
if (!defined('BASE_PATH'))
{
	$base = str_replace('\\','/',dirname(dirname(dirname(__FILE__))));
	define('BASE_PATH', $base);
}
/*MARKER*/require_once(BASE_PATH . '/lib/sitemap.php');

// If session already exists
if(!empty($_SESSION['ccms_userID']) && !empty($_SESSION['ccms_userName']) && CheckAuth()) // [i_a] session vars must exist AND NOT BE EMPTY to be deemed valid.
{
	header('Location: ' . makeAbsoluteURI('../../admin/index.php'));
	exit();
}

// Check for ./install directory
if(is_dir('../../_install/') && !defined('CCMS_DEVELOPMENT_ENVIRONMENT')) 
{
	die('<strong>Security risk: the installation directory is still present.</strong><br/>Either first <a href="../../_install/">run the installer</a>, or remove the <em>./_install</em> directory, before accessing <a href="../../admin/">the back-end</a>.');
}

$userName = strtolower(getPOSTparam4IdOrNumber('userName'));

// Do authentication
if(isset($_POST['submit']) && $_SERVER['REQUEST_METHOD']=="POST") 
{                               
	/*
	This code does NOT require that the submitted data (user+pass) originates from the
	web form below and was entered in the same session (as we don't have the CheckAuth()
	condition checked in the if(...) above).
	
	This is intentional: users may store the login credentials in any form and still log
	in. However, it also means that we must be aware that the current POST data can be
	entirely malicious, hence we MUST perform rigorous checks -- which one would require
	anyhow when logging in.
	
	To prevent SQL injection attacks against this form, we make sure the POST-ed data
	does not contain any wildcards or trickery which makes our validation query below
	produce multiple records. If for some other reason we get multiple user records
	from the database then this is clearly a security/safety violation!
	
	Only when everything check out do we set the session validation items 'id' and 'host'
	which will be used to validate basic website interaction security for the remainder 
	of this session.
	*/
	$userPass = md5($_POST['userPass'].$cfg['authcode']);

	if(empty($userName) && empty($userPass))
	{
		$_SESSION['logmsg'] = rawurlencode($ccms['lang']['login']['nodetails']);
	} 
	elseif(empty($userName))
	{
		$_SESSION['logmsg'] = rawurlencode($ccms['lang']['login']['nouser']);
	} 
	elseif(empty($userPass))
	{
		$_SESSION['logmsg'] = rawurlencode($ccms['lang']['login']['nopass']);
	} 
	else
	{
		$values = array();
		$values['userName'] = MySQL::SQLValue($userName, MySQL::SQLVALUE_TEXT);
		$values['userPass'] = MySQL::SQLValue($userPass, MySQL::SQLVALUE_TEXT);
		$values['userActive'] = MySQL::SQLValue(false, MySQL::SQLVALUE_BOOLEAN);
		$matchNumRows = $db->SelectSingleValue($cfg['db_prefix'].'users', $values, 'COUNT(userID)');
		if($matchNumRows>0)
		{
			$_SESSION['logmsg'] = rawurlencode($ccms['lang']['login']['notactive']);
		} 
		else
		{
			// Select statement: alter the previous condition set:
			$values['userActive'] = MySQL::SQLValue(true, MySQL::SQLVALUE_BOOLEAN);
			$row = $db->SelectSingleRowArray($cfg['db_prefix'].'users', $values, null, null, true, null, MYSQL_ASSOC);
			if ($db->ErrorNumber()) $db->Kill();
			
			if ($db->RowCount() > 1)
			{
				// probably corrupt db table (corrupt import?) or hack attempt
				die('<strong>Database corruption or hack attempt. Access denied.</strong>');
				
				// TODO: alert website owner about this failure/abuse. email to owner?
			}
			elseif(!$row)
			{
				// no match found in DB: user/pass combo doesn't exist!
				//
				// If no match: count attempt and show error
				$_SESSION['logmsg'] = rawurlencode($ccms['lang']['login']['nomatch']);
			}
			elseif($userName != $row['userName'] || $userPass != $row['userPass'] || $row['userActive'] <= 0)
			{
				// If no match: count attempt and show error
				//
				// NOTE: code should never enter here!
				//
				die('INTERNAL ERROR!');
			} 
			else
			{
				// If all checks are okay
				//
				// Update latest login date
				if($db->UpdateRows($cfg['db_prefix'].'users', array('userLastlog' => MySQL::SQLValue(date('Y-m-d G:i:s'), MySQL::SQLVALUE_DATETIME)), array('userID' => MySQL::BuildSQLValue($row['userID'])))) 
				{
					// Set system wide session variables
					$_SESSION['ccms_userID']	= $row['userID'];
					$_SESSION['ccms_userName']	= $row['userName'];
					$_SESSION['ccms_userFirst']	= $row['userFirst'];
					$_SESSION['ccms_userLast']	= $row['userLast'];
					$_SESSION['ccms_userLevel']	= $row['userLevel'];

					// [i_a] fix for session faking/hijack security issue:
					// Setting safety variables as well: used for checkAuth() during the session.
					SetAuthSafety();

					unset($_SESSION['logmsg']);
					// Return functions result
					header('Location: ' . makeAbsoluteURI('../../admin/index.php'));
					exit();
				}
				else
				{
					$db->Kill();
				}
			}
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $cfg['language']; ?>">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>CompactCMS Administration</title>
		<meta name="description" content="CompactCMS administration. CompactCMS is a light-weight and SEO friendly Content Management System for developers and novice programmers alike." />
		<link rel="stylesheet" type="text/css" href="../../admin/img/styles/base.css,layout.css,sprite.css" />
	</head>
<body>

<div id="login-wrapper" class="container">
	<div id="help" class="span-8 colborder">
		<div id="logo" class="sprite logo"><h1>CompactCMS administration</h1></div>
		<span class="ss_sprite ss_door_open">&#160;</span><h2 style="display:inline;"><?php echo $ccms['lang']['login']['login']; ?></h2>
		<?php echo $ccms['lang']['login']['welcome'];?>
	</div>
	
	<div id="login" class="span-9">
		<?php 
		if(!empty($_SESSION['logmsg'])) 
		{ 
		?>
			<div class="loginMsg"><?php echo rawurldecode($_SESSION['logmsg']);?></div>
		<?php 
		} 
		?>
		<p>&#160;</p>
		<form id="loginFrm" name="loginFrm" class="clear" action="./auth.inc.php" method="post">
			<label for="userName"><?php echo $ccms['lang']['login']['username']; ?></label><input type="text" class="alt title" autofocus placeholder="username" name="userName" style="width:300px;" value="<?php echo $userName;?>" id="userName" />
			<br class="clear"/>
			<label for="userPass"><?php echo $ccms['lang']['login']['password']; ?></label><input type="password" class="title" name="userPass" style="width:300px;" value="" id="userPass" />
			
			<p class="span-8 right">
				<button name="submit" type="submit"><span class="ss_sprite ss_lock_go"><?php echo $ccms['lang']['login']['login']; ?></span></button>
			</p>
		</form>
	</div>
</div>
<p class="quiet small" style="text-align:center;">&copy; 2010 <a href="http://www.compactcms.nl" title="Maintained with CompactCMS.nl">CompactCMS.nl</a></p>

</body>
</html>