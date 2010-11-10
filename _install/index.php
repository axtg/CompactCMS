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

if (!defined('BASE_PATH'))
{
	$base = str_replace('\\','/',dirname(dirname(__FILE__)));
	define('BASE_PATH', $base);
}

if(empty($_GET['do'])) 
{ 
	// destroy the session if it existed before: start a new session
	session_start();
	session_unset();
	if (ini_get("session.use_cookies")) 
	{
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
			(!empty($params["ccms_userID"]) ? $params["ccms_userID"] : ''), 
			(!empty($params["domain"]) ? $params["domain"] : ''),
			(!empty($params["secure"]) ? $params["secure"] : ''),
			(!empty($params["httponly"]) ? $params["httponly"] : '')
		);
	}
	session_destroy();
	session_regenerate_id();
}
// Start the current session
session_start();

// Load basic configuration
/*MARKER*/require_once(BASE_PATH . '/lib/config.inc.php');

// Load generic functions
/*MARKER*/require_once(BASE_PATH . '/lib/includes/common.inc.php');


$do	= getGETparam4IdOrNumber('do');

// If no step, set session hash
if(empty($do) && empty($_SESSION['id']) && empty($_SESSION['host'])) 
{
	// Setting safety variables
	SetAuthSafety();
} 

$do_ftp_chmod = ($do == md5('ftp') && CheckAuth());



// Set root directory
$rootdir = str_replace('\\','/',dirname(dirname($_SERVER['PHP_SELF'])));
if($rootdir != '/')
{
	$rootdir = $rootdir.'/';
}


// Set friendly local names languages
function setLanguage($lang) 
{
	switch ($lang) 
	{
		case 'en':
			return "English";
			break;
		case 'nl':
			return "Nederlands";
			break;
		case 'de':
			return "Deutsch";
			break;
		case 'it':
			return "italiano";
			break;
		case 'ru':
			return "русский";
			break;
		case 'sv':
			return "svenska";
			break;
		case 'fr':
			return "français";
			break;
		case 'es':
			return "español (castellano)";
			break;
		case 'pr':
			return "Português";
			break;
		case 'tr':
			return "Türk";
			break;
		case 'ch':
			return "中文";
			break;

		default:
			return $lang;
			break;
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>CompactCMS Installation</title>
		<meta name="description" content="CompactCMS administration. CompactCMS is a light-weight and SEO friendly Content Management System for developers and novice programmers alike." />
		<link rel="stylesheet" type="text/css" href="./install.css" />
		<script type="text/javascript" src="../lib/includes/js/mootools.js" charset="utf-8"></script>
		<script type="text/javascript" src="../admin/includes/modules/user-management/passwordcheck.js" charset="utf-8"></script>
		<script type="text/javascript" charset="utf-8">
			window.addEvent('domready', function(){
				// Process steps
				$('installFrm').addEvent('submit', function(install) {
					new Event(install).stop();
						
					var install_div = $('install');
					var scroll = new Fx.Scroll(window, {wait: false, duration: 500, transition: Fx.Transitions.Quad.easeInOut});
					
					new Request.HTML({
						method: 'post',
						url: './installer.inc.php',
						update: install_div,
						onRequest:  function() { 
							install_div.empty().addClass('loading');
						}, 
						onComplete: function() {
							install_div.removeClass('loading');
							scroll.toElement('install-wrapper');
						}
					}).send($('installFrm'));
				});
			});			
		</script>
	</head>
<body>

<p>&#160;</p>

<div id="install-wrapper" class="container">
	<div id="help" class="span-8 colborder">
		<div id="logo" class="sprite logo"><h1>CompactCMS installation</h1></div>
		<span class="ss_sprite ss_package_green">&#160;</span><h2 style="display:inline;">Install CompactCMS</h2>
		<p>Welcome to the installation wizard of CompactCMS. This wizard will guide you through the five steps required to get CCMS to work on your server. Installing CompactCMS will not take more than five minutes of your time.</p>
		<span class="ss_sprite ss_tick"></span><h2 style="display:inline;">What steps to expect</h2>
		<ol>
			<li><strong>Environment</strong><br/><em>Tell CCMS where your installation is located and what language to speak</em></li>
			<li><strong>Preferences</strong><br/><em>Indicate how you prefer your CCMS</em></li>
			<li><strong>Database</strong><br/><em>Fill-out your credentials to help CCMS save its data</em></li>
			<li><strong>Confirm</strong><br/><em>Go through all of your settings one last time</em></li>
			<li><strong>Done!</strong><br/><em>CCMS saves all of your settings and preferences</em></li>
		</ol>
		<p>If you have any questions, suggestions or perhaps even praise; be sure to <a href="http://www.compactcms.nl/contact.html?subject=My installation feedback" target="_blank" title="Send me an e-mail">let me know</a>!</p>
		<p>Cheers!<br/><em>Xander</em>.</p>
	</div>
	<div class="span-9">
		<form action="./installer.inc.php" method="post" id="installFrm">
			<fieldset id="install" style="border:none;" class="none">
				<legend class="installMsg"><?php echo (!$do_ftp_chmod ? 'Step 1 - Knowing the environment' : 'FTP - Setting permissions right');?></legend>
				<?php 
				if(!$do_ftp_chmod) 
				{ 
				?>
					<p>The details below have been filled-out based on information readily available. Please confirm these settings, select your language and click proceed.</p>
					
					<label for="sitename"><span class="ss_sprite ss_pencil">Site name</span></label><input type="text" class="alt title" name="sitename" style="width:300px;" value="<?php echo (!isset($_SESSION['variables']['sitename'])?ucfirst(preg_replace("/^www\./", "", $_SERVER['HTTP_HOST'])):$_SESSION['variables']['sitename']);?>" id="sitename" />
					
					<label for="rootdir"><span class="ss_sprite ss_application_osx_terminal">Web root directory</span></label>
					<input type="text" class="alt title" name="rootdir" style="width:300px;" autofocus value="<?php echo $rootdir;?>" id="rootdir" />
					<br/>&#160;<span class="ss_sprite ss_bullet_star small quiet">When www.domain.ext/ccms/, <strong>/ccms/</strong> is your web root</span>
					<br/>&#160;<span class="ss_sprite ss_bullet_star small quiet">Must include trailing slash!</span>
					
					<label for="language"><span class="ss_sprite ss_comments">CCMS backend language</span></label>
					<select name="language" class="title" style="padding:5px 10px;width:300px;" id="language" size="1">
						<?php // Get current languages
						if ($handle = opendir('../lib/languages')) 
						{
							while (false !== ($file = readdir($handle))) 
							{
								// Filter out irrelevant files && dirs
								if ($file != "." && $file != ".." && $file != "index.html") 
								{
									$f = substr($file,0,2);
									$s = (isset($_SESSION['variables']['language'])?$_SESSION['variables']['language']:'en');
									$c = ($f==$s?'selected="selected"':null);
									echo '<option value="'.$f.'" '.$c.'>'.setLanguage($f).'</option>';
								}
							}
						}
						?>   	
					</select>
					<input type="hidden" name="do" value="<?php echo md5('2'); ?>" id="do" />
				<?php 
				} 
				// Populate optional FTP form
				else
				{ 
				?>
					<p>Whenever a chmod() command failes through standard procedures, the installer can try to execute the chmod() command over FTP. This requires you to submit your FTP details and full path of your CCMS installation. Any of the data entered below will <strong>never</strong> be saved by the installer.</p>
					
					<label for="ftp_host">FTP host</label>
					<input type="text" class="alt title" name="ftp_host" style="width:300px;" value="" id="ftp_host"/>
					<br/>&#160;<span class="ss_sprite ss_bullet_star small quiet">Often www.domain.ext or ftp.domain.ext</span>
					
					<label for="ftp_user">FTP username</label>
					<input type="text" class="alt title" name="ftp_user" style="width:300px;" value="" id="ftp_user"/>
					
					<label for="ftp_pass">FTP password</label>
					<input type="password" class="title" name="ftp_pass" style="width:300px;" value="" id="ftp_pass"/>
					
					<label for="ftp_path">Installation path</label>
					<input type="text" class="alt title" name="ftp_path" style="width:300px;" value="<?php echo dirname(getcwd()); ?>" id="ftp_path"/>
					<br/>&#160;<span class="ss_sprite ss_bullet_star small quiet">CCMS will try to auto-find this using the default value above</span>
					
					<input type="hidden" name="do" value="<?php echo md5('final'); ?>" id="do" />
				<?php 
				} 
				?>
				
				<p class="span-8 right">
					<button name="submit" type="submit"><span class="ss_sprite ss_lock_go">Proceed</span></button>
					<a href="<?php echo (empty($do) ? 'http://www.compactcms.nl/contact.html?subject=My installation feedback' : 'index.php');?>">Cancel</a>
				</p>
			</fieldset>
		</form>
	</div>
</div>
<p class="quiet small" style="text-align:center;">&copy; 2008 - <?php echo date('Y'); ?> <a href="http://www.compactcms.nl" title="Maintained with CompactCMS.nl">CompactCMS.nl</a>. All rights reserved.</p>

</body>
</html>