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

// Start the current session
session_start();

// Set current && additional step
$nextstep = (isset($_POST['do'])&&!empty($_POST['do'])?$_POST['do']:'ea2b2676c28c0db26d39331a336c6b92');
$additional = (isset($_GET['do'])&&!empty($_GET['do'])?$_GET['do']:null);

// Define default root folder
@define('BASE_PATH',dirname(dirname(__FILE__)));

/**
*
* Per step processing of input
*
**/

// Step two
if($nextstep == md5('2') && md5(session_id())==$_SESSION['id'] && md5($_SERVER['HTTP_HOST']) == $_SESSION['host']) { 
	
	//
	// Installation actions
	//  - Environmental variables
	//
	$rootdir	= array("rootdir" => (substr($_POST['rootdir'],-1)!=='/'?$_POST['rootdir'].'/':$_POST['rootdir']));
	$sitename	= array("sitename" => $_POST['sitename']);
	$language	= array("language" => $_POST['language']);
	
	// Add new data to variable session
	$_SESSION['variables'] = array_merge($rootdir,$sitename,$language);
?>
	<script type="text/javascript" charset="utf-8">function passwordStrength(password){var score=0;if(password.length>5)score++;if((password.match(/[a-z]/))&&(password.match(/[A-Z]/)))score++;if(password.match(/\d+/))score++;if(password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/))score++;if(password.length>12)score++;document.getElementById("passwordStrength").className="strength"+score;}</script>
	<script type="text/javascript" charset="utf-8">function randomPassword(length){chars="abcdefghijkmNPQRSTUVWXYZ123456789!@#$%";pass="";for(x=0;x<length;x++){i=Math.floor(Math.random()*38);pass+=chars.charAt(i);}passwordStrength(pass);return document.getElementById("adminpass").value=pass;}</script>
	<legend class="installMsg">Step 2 - Setting your preferences</legend>

		<label for="adminpass"><span class="ss_sprite ss_lock">Administrator password</span><br/><a href="#" class="small ss_sprite ss_arrow_refresh" onclick="randomPassword(8);">Auto generate a safe password</a></label>
		<input type="text" class="alt title" name="adminpass" maxlenght="5" onkeyup="passwordStrength(this.value)" style="width:300px;" value="" id="adminpass" />
		<div class="clear center">
			<div id="passwordStrength" class="strength0"></div>
		</div>
		<br/>&#160;<span class="ss_sprite ss_bullet_star small quiet">Remember your admin password as it cannot be retrieved</span>
		<label for="authcode"><span class="ss_sprite ss_textfield_key">Authentication PIN</span></label>
		<input type="text" class="alt title" name="authcode" maxlenght="5" style="width:300px;" value="<?php echo rand('12345','98765');?>" id="authcode" />
		<br/>&#160;<span class="ss_sprite ss_bullet_star small quiet">Adding this PIN to the URL shows previews of inactive pages</span>
		<br/>&#160;<span class="ss_sprite ss_bullet_star small quiet">This code is used to encrypt passwords (salt)</span>
		<br class="clear"/>
		<label for="protect"><input type="checkbox" name="protect" value="true" checked id="protect" /> Password protect the administration</label>
		<label for="version"><input type="checkbox" name="version" value="true" checked id="version" /> Show version information</label>
		&#160;<span class="ss_sprite ss_bullet_star small quiet">Want to see the latest CCMS version at the dashboard?</span>
		<label for="iframe"><input type="checkbox" name="iframe" value="true" id="iframe" /> Support &amp; allow iframes</label>
		&#160;<span class="ss_sprite ss_bullet_star small quiet">Can iframes be managed from within the WYSIWYG editor?</span>
		<label for="wysiwyg"><input type="checkbox" name="wysiwyg" value="true" checked id="wysiwyg" /> Enable the visual content editor</label>
		&#160;<span class="ss_sprite ss_bullet_star small quiet">Uncheck if you want to disable the visual editor all together</span>

		<p class="span-8 right">
			<button name="submit" type="submit"><span class="ss_sprite ss_lock_go">Proceed</span></button>
			<a href="index.php" title="Back to step first step">Cancel</a>
			<input type="hidden" name="do" value="<?php echo md5('3'); ?>" id="do" />
		</p>

<?php
} // Close step two

// Step three
if($nextstep == md5('3') && md5(session_id())==$_SESSION['id'] && md5($_SERVER['HTTP_HOST']) == $_SESSION['host']) { 
	//
	// Installation actions
	//  - Saving preferences
	//

	$version	= array("version" => (isset($_POST['version'])&&$_POST['version']=='true'?'true':'false'));
	$iframe		= array("iframe" => (isset($_POST['iframe'])&&$_POST['iframe']=='true'?'true':'false'));
	$wysiwyg	= array("wysiwyg" => (isset($_POST['wysiwyg'])&&$_POST['wysiwyg']=='true'?'true':'false'));
	$protect	= array("protect" => (isset($_POST['protect'])&&$_POST['protect']=='true'?'true':'false'));
	$adminpass	= array("adminpass" => $_POST['adminpass']);
	$authcode	= array("authcode" => $_POST['authcode']);
	
	// Add new data to variable session
	$_SESSION['variables'] = array_merge($_SESSION['variables'],$version,$iframe,$wysiwyg,$protect,$adminpass,$authcode);
?>
	<legend class="installMsg">Step 3 - Collecting your database details</legend>
		<label for="db_host"><span class="ss_sprite ss_server_database">Database host</span></label><input type="text" class="alt title" name="db_host" style="width:300px;" value="localhost" id="db_host" />
		<br class="clear"/>
		<label for="db_user"><span class="ss_sprite ss_drive_user">Database username</span></label><input type="text" class="alt title" name="db_user" style="width:300px;" value="" id="db_user" />
		<br class="clear"/>
		<label for="db_pass"><span class="ss_sprite ss_drive_key">Database password</span></label><input type="password" class="title" name="db_pass" style="width:300px;" value="" id="db_pass" />
		<br class="clear"/>
		<label for="db_name"><span class="ss_sprite ss_database">Database name</span></label><input type="text" class="alt title" name="db_name" style="width:300px;" value="compactcms" id="db_name" />
		<br class="clear"/>
		<label for="db_prefix"><span class="ss_sprite ss_database_table">Database table prefix</span></label><input type="text" class="alt title" name="db_prefix" style="width:300px;" value="ccms_" id="db_prefix" />
			
		<p class="span-8 right">
			<button name="submit" type="submit"><span class="ss_sprite ss_information">To confirmation</span></button>
			<a href="index.php" title="Back to step first step">Cancel</a>
			<input type="hidden" name="do" value="<?php echo md5('4'); ?>" id="do" />
		</p>

<?php
} // Close step three

// Step four
if($nextstep == md5('4') && md5(session_id())==$_SESSION['id'] && md5($_SERVER['HTTP_HOST']) == $_SESSION['host']) { 
	
	//
	// Installation actions
	//  - Process database
	//
	$db_host	= array("db_host" => $_POST['db_host']);
	$db_user	= array("db_user" => $_POST['db_user']);
	$db_pass	= array("db_pass" => $_POST['db_pass']);
	$db_name	= array("db_name" => $_POST['db_name']);
	$db_prefix	= array("db_prefix" => $_POST['db_prefix']);
	
	// Add new data to variable session
	$_SESSION['variables'] = array_merge($_SESSION['variables'],$db_host,$db_user,$db_pass,$db_name,$db_prefix);
	
	// Define alternative table row color
	$alt_row = "#CDE6B3";
	
	//
	// Check for current chmod() if server != Windows
	//
	if(!strpos($_SERVER['SERVER_SOFTWARE'], "Win")) {
		if(substr(sprintf('%o', fileperms(BASE_PATH.'/.htaccess')),-4)!='0666') { $chfile[] = '.htaccess (0666)'; }
		if(substr(sprintf('%o', fileperms(BASE_PATH.'/lib/config.inc.php')),-4)!='0666') { $chfile[] = '/lib/config.inc.php (0666)'; }
		if(substr(sprintf('%o', fileperms(BASE_PATH.'/content/home.php')),-4)!='0666') { $chfile[] = '/content/home.php (0666)'; }
		if(substr(sprintf('%o', fileperms(BASE_PATH.'/content/contact.php')),-4)!='0666') { $chfile[] = '/content/contact.php (0666)'; }
		if(substr(sprintf('%o', fileperms(BASE_PATH.'/lib/templates/ccms.tpl.html')),-4)!='0666') { $chfile[] = '/lib/templates/ccms.tpl.html (0666)'; }
		// Directories under risk due to chmod(0777)
		if(substr(sprintf('%o', fileperms(BASE_PATH.'/content/')),-4)!='0777') { $chfile[] = '/content/ (0777)'; }
		if(substr(sprintf('%o', fileperms(BASE_PATH.'/media/')),-4)!='0777') { $chfile[] = '/media/ (0777)'; }
		if(substr(sprintf('%o', fileperms(BASE_PATH.'/media/albums/')),-4)!='0777') { $chfile[] = '/media/albums/ (0777)'; }
		if(substr(sprintf('%o', fileperms(BASE_PATH.'/media/files/')),-4)!='0777') { $chfile[] = '/media/files/ (0777)'; }
		if(substr(sprintf('%o', fileperms(BASE_PATH.'/lib/includes/cache/')),-4)!='0777') { $chfile[] = '/lib/includes/cache/ (0777)'; }
	}
?>	
	<legend class="installMsg">Step 4 - Review your input</legend>
		<?php if(!isset($chfile)) { ?><p class="center"><span class="ss_sprite ss_tick"><em>All files are already correctly chmod()'ed</em></span></p><?php } ?>
		<?php if(ini_get('safe_mode') || isset($chfile)) { ?>
			<span class="ss_sprite ss_exclamation">&#160;</span><h2 style="display:inline;">Warning</h2>
			<p>It appears that it <abbr title="Based on current chmod() rights and/or safe mode restrictions">may not be possible</abbr> for the installer to chmod() various files. Please consider doing so manually <em>or</em> by using the <a href="index.php?do=ff104b2dfab9fe8c0676587292a636d3">built-in FTP chmod function</a>.</p>
			<span>&rarr; <em>Files that still require chmod():</em></span>
				<ul>
					<?php foreach ($chfile as $value) {
						if(!empty($value)&&!is_numeric($value)) { echo "<li>$value</li>"; }
					}?>
				</ul>
		<?php } ?>
		<span class="ss_sprite ss_computer">&#160;</span><h2 style="display:inline;">Environment</h2>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr style="background-color: <?php echo $alt_row; ?>;">
				<th width="55%" scope="row">Sitename</th>
				<td><?php echo $_SESSION['variables']['sitename'];?></td>
			</tr>
			<tr>
				<th scope="row">Root directory</th>
				<td><?php echo $_SESSION['variables']['rootdir'];?></td>
			</tr>
			<tr style="background-color: <?php echo $alt_row; ?>;">
				<th scope="row">Language</th>
				<td><?php echo $_SESSION['variables']['language'];?></td>
			</tr>
		</table>
		<br class="clear"/>
		<span class="ss_sprite ss_cog">&#160;</span><h2 style="display:inline;">Preferences</h2>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr style="background-color: <?php echo $alt_row; ?>;">
				<th width="55%" scope="row">Version</th>
				<td><?php echo $_SESSION['variables']['version'];?></td>
			</tr>
			<tr>
				<th scope="row">Iframe</th>
				<td><?php echo $_SESSION['variables']['iframe'];?></td>
			</tr>
			<tr style="background-color: <?php echo $alt_row; ?>;">
				<th scope="row">Visual editor</th>
				<td><?php echo $_SESSION['variables']['wysiwyg'];?></td>
			</tr>
			<tr>
				<th scope="row">User authentication</th>
				<td><?php echo $_SESSION['variables']['protect'];?></td>
			</tr>
			<tr style="background-color: <?php echo $alt_row; ?>;">
				<th scope="row">Administrator password</th>
				<td> *** </td>
			</tr>
			<tr>
				<th scope="row">Authentication PIN</th>
				<td><?php echo $_SESSION['variables']['authcode'];?></td>
			</tr>
		</table>
		<br class="clear"/>
		<span class="ss_sprite ss_database">&#160;</span><h2 style="display:inline;">Database details</h2>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr style="background-color: <?php echo $alt_row; ?>;">
				<th width="55%" scope="row">Database host</th>
				<td><?php echo $_SESSION['variables']['db_host'];?></td>
			</tr>
			<tr>
				<th scope="row">Database username</th>
				<td><?php echo $_SESSION['variables']['db_user'];?></td>
			</tr>
			<tr style="background-color: <?php echo $alt_row; ?>;">
				<th scope="row">Database password</th>
				<td> *** </td>
			</tr>
			<tr>
				<th scope="row">Database name</th>
				<td><?php echo $_SESSION['variables']['db_name'];?></td>
			</tr>
			<tr style="background-color: <?php echo $alt_row; ?>;">
				<th scope="row">Database table prefix</th>
				<td><?php echo $_SESSION['variables']['db_prefix'];?></td>
			</tr>
		</table>
		
		<hr noshade="noshade" />
		<p class="quiet">
			<strong><span class="ss_sprite ss_exclamation">Please note</span></strong><br/>
			Any data that is currently in <strong><?php echo $_SESSION['variables']['db_prefix']; ?>pages</strong> and <strong><?php echo $_SESSION['variables']['db_prefix']; ?>users</strong> might be overwritten, depending your servers' configuration.
		</p>
		
		<p class="span-8 right">
			<button name="submit" id="installbtn" type="submit"><span class="ss_sprite ss_accept">Install <strong>CompactCMS</strong></span></button>
			<a href="index.php" title="Back to step first step">Cancel</a>
			<input type="hidden" name="do" value="<?php echo md5('final'); ?>" id="do" />
		</p>

<?php
} // Close step four

/**
*
* Do the actual configuration
*
**/

// Final step
if($nextstep == md5('final') && md5(session_id())==$_SESSION['id'] && md5($_SERVER['HTTP_HOST']) == $_SESSION['host']) {
	
	//
	// Installation actions
	//  - Set collected data
	//
	
	// Let's start with a clean sheet
	$err = 0;
	
	// Include MySQL class && initiate
	require_once(BASE_PATH.'/lib/class/mysql.class.php');
	$db = new MySQL();
	
	//
	// Try database connection
	//
	if (!$db->Open($_SESSION['variables']['db_name'], $_SESSION['variables']['db_host'], $_SESSION['variables']['db_user'], $_SESSION['variables']['db_pass'])) {
    	$errors[] = 'Error: could not connect to the database';
    	$errors[] = $db->Error();
    	$err++;
	} else { 
		$log[] = "Database connection successful"; 
	}
	
	//
	// Insert database structure and sample data
	//
	if($err==0) {
		$sql = file_get_contents(BASE_PATH.'/_docs/structure.sql');
		$sql = preg_replace('/ccms_/', $_SESSION['variables']['db_prefix'], $sql);
		$sql = preg_replace('/52dcb810931e20f7aa2f49b3510d3805/', md5($_SESSION['variables']['adminpass'].$_SESSION['variables']['authcode']), $sql);
		
		// Execute per sql piece
		$tok = strtok($sql, ";");
		while ($tok !== false) {
			$results = $db->Query("$tok");
		    $tok = strtok(";");
		}	$log[] = "Database structure and data successfully imported";
	}
	
	//
	// Set chmod on config.inc.php, .htaccess, content, cache and albums
	//
	if($err==0 && !isset($_POST['ftp_host']) && empty($_POST['ftp_host']) && !strpos($_SERVER['SERVER_SOFTWARE'], "Win")) {
		// Set warning when safe mode is enabled
		if(ini_get('safe_mode')) {
			$errors[] = 'Warning: safe mode is enabled, skipping chmod()';
		}
		
		// Set default values
		$chmod = 0;
		$errfile=0;
		
		// Chmod check and set function
		function setChmod($path, $value) {
			// Check current chmod() status
			if(substr(sprintf('%o', fileperms(BASE_PATH.$path)), -4)!=$value) {
				// If not set, set
				if(@chmod(BASE_PATH.$path, $value)) { 
					return true;
				} 
			} else {
				return true;
			}
		}
		
		// Do chmod() per necessary folder and set status
		if(setChmod('/.htaccess','0666')) { $chmod++; } else $errfile[] = 'Could not chmod() /.htaccess/';
		if(setChmod('/lib/config.inc.php','0666')) { $chmod++; } else $errfile[] = 'Could not chmod() /lib/config.inc.php';
		if(setChmod('/content/home.php','0666')) { $chmod++; } else $errfile[] = 'Could not chmod() /content/home.php';
		if(setChmod('/content/contact.php','0666')) { $chmod++; } else $errfile[] = 'Could not chmod() /content/contact.php';
		if(setChmod('/lib/templates/ccms.tpl.html','0666')) { $chmod++; } else $errfile[] = 'Could not chmod() /lib/templates/ccms.tpl.html';
		
		// Directories under risk due to chmod(0777)
		if(setChmod('/content/','0777')) { $chmod++; } else $errfile[] = 'Could not chmod() /content/';
		if(setChmod('/media/','0777')) { $chmod++; } else $errfile[] = 'Could not chmod() /media/';
		if(setChmod('/media/albums/','0777')) { $chmod++; } else $errfile[] = 'Could not chmod() /media/albums/';
		if(setChmod('/media/files/','0777')) { $chmod++; } else $errfile[] = 'Could not chmod() /media/files/';
		if(setChmod('/lib/includes/cache/','0777')) { $chmod++; } else $errfile[] = 'Could not chmod() /lib/includes/cache/';
					
		if($chmod>0) { 
			$log[] = '<abbr title=".htaccess, config.inc.php, ./content/, ./lib/includes/cache/, back-up folder &amp; 2 media folders">Confirmed correct chmod() on '.$chmod.' files</abbr>';
		} 
		if(!isset($chmod)||$chmod==0||$errfile>0) {
			$errors[] = 'Warning: could not chmod() all files.';
			foreach ($errfile as $key => $value) {
				$errors[] = $value;
			}
			$errors[] = 'Either use the <a href="index.php?do=ff104b2dfab9fe8c0676587292a636d3">built-in FTP chmod function</a>, or manually perform chmod().';
		}
	}
	
	//
	// Perform optional FTP chmod command
	//
	if(isset($_POST['ftp_host']) && !empty($_POST['ftp_host']) && isset($_POST['ftp_user']) && !empty($_POST['ftp_user'])) {
	
		// Set up a connection or die
		$conn_id = ftp_connect($_POST['ftp_host']) or die("Couldn't connect to ".$_POST['ftp_host']); 
		
		// Try to login using provided details
		if(@ftp_login($conn_id, $_POST['ftp_user'], $_POST['ftp_pass'])) {
		    
			// trimPath function
			function trimPath($path,$depth) {
				$path = explode('/',$path);
				$np = '/';
				for ($i=$depth; $i<count($path); $i++) { 
					$np .= $path[$i].'/';
				}
				return $np;	
			}
			
			// Find FTP path
			$i 		= 1;
			$path 	= $_POST['ftp_path'];
			
			// Set max tries to 15
			for ($i=1; $i<15; $i++) { 
				if(@ftp_chdir($conn_id, trimPath($path,$i))) {
					$log[] = "Successfully connected to FTP server";
					$i = 15;
				}
			}
		} else {
		    $errors[] = "Fatal: couldn't connect to the FTP server. Perform chmod() manually.";
		    $err++;
		}
		// Count the ftp_chmod() successes
		$ftp_chmod = 0;
		
		// Perform the ftp_chmod command
		if(@ftp_chmod($conn_id, 0666, "./.htaccess")) { $ftp_chmod++; }
		if(@ftp_chmod($conn_id, 0666, "./lib/config.inc.php")) { $ftp_chmod++; }
		if(@ftp_chmod($conn_id, 0666, "./content/home.php")) { $ftp_chmod++; }
		if(@ftp_chmod($conn_id, 0666, "./content/contact.php")) { $ftp_chmod++; }
		if(@ftp_chmod($conn_id, 0666, "./lib/templates/ccms.tpl.html")) { $ftp_chmod++; }
		// Directories under risk due to chmod(0777)
		if(@ftp_chmod($conn_id, 0777, "./content/")) { $ftp_chmod++; }
		if(@ftp_chmod($conn_id, 0777, "./media/")) { $ftp_chmod++; }
		if(@ftp_chmod($conn_id, 0777, "./media/albums")) { $ftp_chmod++; }
		if(@ftp_chmod($conn_id, 0777, "./media/files/")) { $ftp_chmod++; }
		if(@ftp_chmod($conn_id, 0777, "./lib/includes/cache/")) { $ftp_chmod++; }
	
		if($ftp_chmod>0) { 
			$log[] = '<abbr title=".htaccess, config.inc.php, ./content/, ./lib/includes/cache/, back-up folder &amp; 2 media folders">Successful chmod() on '.$chmod.' files using FTP.</abbr>';
		} elseif($ftp_chmod==0) {
			$errors[] = 'Fatal: could not FTP chmod() various files.';
			$err++;
		}
	
		// Close the connection
		ftp_close($conn_id);  
	}
	
	//
	// Write config.inc.php file
	//
	if($err==0) {
		include(BASE_PATH.'/lib/config.inc.php');
		$config_str = "\$cfg = array();\r\n";
		$write_err	= null;
		
		// Write new variables to configuration file
		if ($fp = @fopen(BASE_PATH.'/lib/config.inc.php', 'w')) {
			// Write start line
			fwrite($fp, "<?php\r\n// Copyright (C) 2008 - ".date('Y')." by Xander Groesbeek (CompactCMS.nl)\r\n// This file is part of CompactCMS\r\n// Please refer to license.txt for information on license conditions.\r\n");
		
			// Compare old and new variables
			foreach($cfg as $key=>$val) {
				if (isset($_SESSION['variables'][$key])) {
					$new_val = $_SESSION['variables'][$key];
				} else {
					$new_val = $cfg[$key];
				}
				// Rewrite the previous loaded string
				if($new_val=="true"||$new_val=="false") {
					$config_str = "\$cfg['{$key}'] = {$new_val}; \r\n";
				} else {
					$config_str = "\$cfg['{$key}'] = '{$new_val}'; \r\n";
				}
				// Write each new variable to the config file
				if(!fwrite($fp, $config_str, strlen($config_str))) {
					$write_err = "1";
					$errors[] = "Fatal: Problem saving new configuration values";
					$err++;
				} 
			}
			// Write end lines
			fwrite($fp, "\$cfg['restrict'] = array();\r\n?>");
			
			// Check for errors
			if(empty($write_err)) {
				$log[] = "Configuration successfully saved to config.inc.php";
			}
		} else {
			$errors[] = 'Fatal: the configuration file is not writable.';
			$errors[] = 'Make sure the file is writable, or <a href="index.php?do=ff104b2dfab9fe8c0676587292a636d3">do so now</a>.';
			$err++;
		}
	}
	//
	// Modify .htaccess file
	//
	if($err==0 && $_SESSION['variables']['rootdir']!='/') {
		$htaccess 	= file_get_contents(BASE_PATH.'/.htaccess');
		$newline	= "RewriteBase ".$_SESSION['variables']['rootdir'];
	
		if(strpos($htaccess, $newline)===false) {
			$htaccess = preg_replace("/RewriteBase \//", $newline, $htaccess);

			if ($fp = fopen(BASE_PATH.'/.htaccess', 'w')) {			
				if(fwrite($fp, $htaccess, strlen($htaccess))) {
					$log[] = "Successfully rewrote the .htaccess file";
				} 
			} elseif($_SESSION['variables']['rootdir']=="/") {
				$errors[] = 'Warning: the .htaccess file is not writable.';
			} elseif($_SESSION['variables']['rootdir']!="/") {
				$errors[] = 'Fatal: the .htaccess file is not writable.';
				$errors[] = 'Make sure the file is writable, or <a href="index.php?do=ff104b2dfab9fe8c0676587292a636d3">do so now</a>.';
				$err++;
			}
		}
	}
	
?>	
	<legend class="installMsg">Final - Finishing the installation</legend>
		<?php if(isset($log)) { 
			unset($_SESSION['variables']); ?>
		<h2>Process results</h2>
		<p>
			<?php 
			while (list($key,$value) = each($log)) {
				echo '<span class="ss_sprite ss_accept">'.$value.'</span><br />';
			} ?>
		</p>
		<?php } if(isset($errors)) { ?>
		<h2>Errors &amp; warnings</h2>
		<p>
			<?php 
			while (list($key,$value) = each($errors)) {
				echo '<span class="ss_sprite ss_exclamation">'.$value.'</span><br />';
			} ?>
		</p>
		<?php } if($err==0) { ?>
		<h2>What's next?</h2>
		<p>The installation has been successful! You should now follow the steps below, to get you started.</p>
		<ol>
			<li>Delete the <em>./_install</em> directory</li>
			<li><a href="../admin/">Login</a> using username <span class="ss_sprite ss_user_red"><strong>admin</strong></span></li>
			<li>Change your password through the back-end</li>
			<li><a href="http://www.compactcms.nl/contact.html" target="_blank">Let me know</a> how you like CompactCMS!</li>
		</ol>
		<?php } else echo '<a href="index.php">Retry setting the variables</a>'; ?>
	
<?php
} // Close final processing
?>