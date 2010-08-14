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
	$rootdir	= array("rootdir" => $_POST['rootdir']);
	$homepage	= array("homepage" => $_POST['homepage']);
	$language	= array("language" => $_POST['language']);
	
	// Add new data to variable session
	$_SESSION['variables'] = array_merge($rootdir,$homepage,$language);
?>
	<legend class="installMsg">Step 2 - Setting your preferences</legend>
		<label for="sitename"><span class="ss_sprite ss_pencil">Site name</span></label><input type="text" class="alt title" name="sitename" style="width:300px;" value="<?php echo (!isset($_SESSION['variables']['sitename'])?ucfirst(preg_replace("/^www\./", "", $_SERVER['HTTP_HOST'])):$_SESSION['variables']['sitename']);?>" id="sitename" />
		<br class="clear"/>
		<label for="version"><input type="checkbox" name="version" value="true" checked id="version" /> Show version information</label>
		<label for="iframe"><input type="checkbox" name="iframe" value="true" id="iframe" /> Support &amp; allow iframes</label>
		<label for="wysiwyg"><input type="checkbox" name="wysiwyg" value="true" checked id="wysiwyg" /> Enable the visual content editor</label>
		<label for="protect"><input type="checkbox" name="protect" value="true" checked id="protect" /> Password protect the administration</label>
		<br class="clear"/>
		<label for="authcode"><span class="ss_sprite ss_textfield_key">Authentication PIN</span></label>
		<input type="text" class="alt title" name="authcode" maxlenght="5" style="width:300px;" value="<?php echo rand('12345','98765');?>" id="authcode" />
		
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
	$sitename	= array("sitename" => $_POST['sitename']);
	$version	= array("version" => (isset($_POST['version'])&&$_POST['version']=='true'?'true':'false'));
	$iframe		= array("iframe" => (isset($_POST['iframe'])&&$_POST['iframe']=='true'?'true':'false'));
	$wysiwyg	= array("wysiwyg" => (isset($_POST['wysiwyg'])&&$_POST['wysiwyg']=='true'?'true':'false'));
	$protect	= array("protect" => (isset($_POST['protect'])&&$_POST['protect']=='true'?'true':'false'));
	$authcode	= array("authcode" => $_POST['authcode']);
	
	// Add new data to variable session
	$_SESSION['variables'] = array_merge($_SESSION['variables'],$sitename,$version,$iframe,$wysiwyg,$protect,$authcode);
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
	$chmod = 0;
	if(!strpos($_SERVER['SERVER_SOFTWARE'], "Win")) {
		(substr(decoct(fileperms('../.htaccess')),1)!='0666'?$chmod++:null);
		(substr(decoct(fileperms('../lib/config.inc.php')),1)!='0666'?$chmod++:null);
		(substr(decoct(fileperms('../content/')),1)!='0755'?$chmod++:null);
		(substr(decoct(fileperms('../lib/includes/cache/')),1)!='0777'?$chmod++:null);
		(substr(decoct(fileperms('../lib/modules/backup-restore/files/')),1)!='0777'?$chmod++:null);
		(substr(decoct(fileperms('../media/')),1)!='0777'?$chmod++:null);
		(substr(decoct(fileperms('../media/albums/')),1)!='0777'?$chmod++:null);
	}
?>	
	<legend class="installMsg">Step 4 - Review your input</legend>
		<?php if(ini_get('safe_mode') || $chmod>0) {?>
			<h2>Warning</h2>
			<p>It appears that it <abbr title="Based on current chmod() rights and/or safe mode restrictions">might not be possible</abbr> for the installer to chmod() various files. Please consider doing so manually <em>or</em> by using the <a href="index.php?do=ff104b2dfab9fe8c0676587292a636d3">built-in FTP chmod function</a>.</p>
			<span>&rarr; <em>Files that require chmod():</em></span>
				<ul>
					<li>./.htaccess (0666)</li>
					<li>./lib/config.inc.php (0666)</li>
					<li>./content/ (0777) <a href="http://community.compactcms.nl/forum/" target="_blank"><span class="small quiet">more info</span></a></li>
					<li>./lib/includes/cache/ (0777)</li>
					<li>../lib/modules/backup-restore/files/ (0777)</li>
					<li>./media/ (0777)</li>
					<li>./media/albums/ (0777)</li>
				</ul>
		<?php } ?>
		<span class="ss_sprite ss_computer">&#160;</span><h2 style="display:inline;">Environment</h2>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<th width="45%" scope="row">Root directory</th>
				<td><?php echo $_SESSION['variables']['rootdir'];?></td>
			</tr>
			<tr>
				<th scope="row">Homepage</th>
				<td><?php echo $_SESSION['variables']['homepage'];?></td>
			</tr>
			<tr style="background-color: <?php echo $alt_row; ?>;">
				<th scope="row">Language</th>
				<td><?php echo $_SESSION['variables']['language'];?></td>
			</tr>
		</table>
		<br class="clear"/>
		<span class="ss_sprite ss_cog">&#160;</span><h2 style="display:inline;">Preferences</h2>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<th width="45%" scope="row">Sitename</th>
				<td><?php echo $_SESSION['variables']['sitename'];?></td>
			</tr>
			<tr style="background-color: <?php echo $alt_row; ?>;">
				<th scope="row">Version</th>
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
				<th scope="row">Authentication PIN</th>
				<td><?php echo $_SESSION['variables']['authcode'];?></td>
			</tr>
		</table>
		<br class="clear"/>
		<span class="ss_sprite ss_database">&#160;</span><h2 style="display:inline;">Database details</h2>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<th width="45%" scope="row">Database host</th>
				<td><?php echo $_SESSION['variables']['db_host'];?></td>
			</tr>
			<tr style="background-color: <?php echo $alt_row; ?>;">
				<th scope="row">Database username</th>
				<td><?php echo $_SESSION['variables']['db_user'];?></td>
			</tr>
			<tr>
				<th scope="row">Database password</th>
				<td> *** </td>
			</tr>
			<tr style="background-color: <?php echo $alt_row; ?>;">
				<th scope="row">Database name</th>
				<td><?php echo $_SESSION['variables']['db_name'];?></td>
			</tr>
			<tr>
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
	if($err==0 && !isset($_POST['ftp_host']) && empty($_POST['ftp_host'])) {
		// Set warning when safe mode is enabled
		if(ini_get('safe_mode')) {
			$errors[] = 'Warning: safe mode is enabled, skipping chmod()';
		}
		// Count chmod() successes
		$chmod = 0;
		
		// Do chmod() per necessary folder and set status
		if(@chmod(BASE_PATH."/.htaccess", 0666)) { $chmod++; }
		if(@chmod(BASE_PATH."/lib/config.inc.php", 0666)) { $chmod++; }
		if(@chmod(BASE_PATH."/content/", 0777)) { $chmod++; }
		if(@chmod(BASE_PATH."/content/home.php", 0666)) { $chmod++; }
		if(@chmod(BASE_PATH."/content/installation.php", 0666)) { $chmod++; }
		if(@chmod(BASE_PATH."/content/contact.php", 0666)) { $chmod++; }
		if(@chmod(BASE_PATH."/lib/includes/cache/", 0777)) { $chmod++; }
		if(@chmod(BASE_PATH."/lib/templates/ccms.tpl.html", 0666)) { $chmod++; }
		if(@chmod(BASE_PATH."/admin/includes/modules/backup-restore/files/", 0777)) { $chmod++; }
		if(@chmod(BASE_PATH."/media/", 0777)) { $chmod++; }
		if(@chmod(BASE_PATH."/media/albums/", 0777)) { $chmod++; }
		
		if($chmod>0) { 
			$log[] = '<abbr title=".htaccess, config.inc.php, ./content/, ./lib/includes/cache/, back-up folder &amp; 2 media folders">Successful chmod() on '.$chmod.' files</abbr>';
		} elseif($chmod==0) {
			$errors[] = 'Warning: could not chmod() all files.';
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
		if (@ftp_login($conn_id, $_POST['ftp_user'], $_POST['ftp_pass'])) {
		    if (ftp_chdir($conn_id, $_POST['ftp_path'])) {
				$log[] = "Successfully connected to FTP server";
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
		if(@ftp_chmod($conn_id, 0777, "./content/")) { $ftp_chmod++; }
		if(@ftp_chmod($conn_id, 0666, "./content/home.php")) { $ftp_chmod++; }
		if(@ftp_chmod($conn_id, 0666, "./content/installation.php")) { $ftp_chmod++; }
		if(@ftp_chmod($conn_id, 0666, "./content/contact.php")) { $ftp_chmod++; }
		if(@ftp_chmod($conn_id, 0777, "./lib/includes/cache/")) { $ftp_chmod++; }
		if(@ftp_chmod($conn_id, 0666, "./lib/templates/ccms.tpl.html")) { $ftp_chmod++; }
		if(@ftp_chmod($conn_id, 0777, "./admin/includes/modules/backup-restore/files/")) { $ftp_chmod++; }
		if(@ftp_chmod($conn_id, 0777, "./media/")) { $ftp_chmod++; }
		if(@ftp_chmod($conn_id, 0777, "./media/albums")) { $ftp_chmod++; }
	
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
		$htaccess = file_get_contents(BASE_PATH.'/.htaccess');
		$htaccess = preg_replace("/RewriteBase \//", "RewriteBase ".$_SESSION['variables']['rootdir'], $htaccess);

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
	
?>	
	<legend class="installMsg">Final - Finishing the installation</legend>
		<?php if(isset($log)) { ?>
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
			<li><a href="../admin/">Login</a> using details <strong>admin</strong> and <strong>pass</strong></li>
			<li>Change your password through the back-end</li>
			<li><a href="http://www.compactcms.nl/contact.html" target="_blank">Let me know</a> how you like CompactCMS!</li>
		</ol>
		<?php } else echo '<a href="index.php">Retry setting the necessary variables</a>'; ?>
	
<?php
} // Close final processing
?>