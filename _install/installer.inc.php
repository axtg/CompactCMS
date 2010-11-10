<?php
/* ************************************************************
Copyright (C) 2008 - 2010 by Xander Groesbeek (CompactCMS.nl)
Revision:   CompactCMS - v 1.4.1

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


// Start the current session
session_start();

// Load basic configuration
/*MARKER*/require_once(BASE_PATH . '/lib/config.inc.php');

// Load generic functions
/*MARKER*/require_once(BASE_PATH . '/lib/includes/common.inc.php');


// Set current && additional step
$nextstep = getPOSTparam4IdOrNumber('do', 'ea2b2676c28c0db26d39331a336c6b92');

if (empty($_SESSION['variables']))
{
	$_SESSION['variables'] = array();
}


/*
preload the session variables
*/
if (empty($_SESSION['variables']['sitename']) && !empty($cfg['sitename']))
{
	$_SESSION['variables']['sitename'] = $cfg['sitename'];
}
if (empty($_SESSION['variables']['rootdir']) && !empty($cfg['rootdir']))
{
	$_SESSION['variables']['rootdir'] = $cfg['rootdir'];
}
if (empty($_SESSION['variables']['language']) && !empty($cfg['language']))
{
	$_SESSION['variables']['language'] = $cfg['language'];
}
if (empty($_SESSION['variables']['version']) && !empty($cfg['version']))
{
	$_SESSION['variables']['version'] = ($cfg['version'] ? 'true' : 'false');
}
if (empty($_SESSION['variables']['iframe']) && !empty($cfg['iframe']))
{
	$_SESSION['variables']['iframe'] = ($cfg['iframe'] ? 'true' : 'false');
}
if (empty($_SESSION['variables']['wysiwyg']) && !empty($cfg['wysiwyg']))
{
	$_SESSION['variables']['wysiwyg'] = ($cfg['wysiwyg'] ? 'true' : 'false');
}
if (empty($_SESSION['variables']['protect']) && !empty($cfg['protect']))
{
	$_SESSION['variables']['protect'] = ($cfg['protect'] ? 'true' : 'false');
}
if (empty($_SESSION['variables']['authcode']) && !empty($cfg['authcode']))
{
	$_SESSION['variables']['authcode'] = $cfg['authcode'];
}
if (empty($_SESSION['variables']['db_host']) && !empty($cfg['db_host']))
{
	$_SESSION['variables']['db_host'] = $cfg['db_host'];
}
if (empty($_SESSION['variables']['db_user']) && !empty($cfg['db_user']))
{
	$_SESSION['variables']['db_user'] = $cfg['db_user'];
}
if (empty($_SESSION['variables']['db_pass']) && !empty($cfg['db_pass']))
{
	$_SESSION['variables']['db_pass'] = $cfg['db_pass'];
}
if (empty($_SESSION['variables']['db_name']) && !empty($cfg['db_name']))
{
	$_SESSION['variables']['db_name'] = $cfg['db_name'];
}
if (empty($_SESSION['variables']['db_prefix']) && !empty($cfg['db_prefix']))
{
	$_SESSION['variables']['db_prefix'] = $cfg['db_prefix'];
}





/**
*
* Per step processing of input
*
**/

// Step two
if($nextstep == md5('2') && CheckAuth())
{
	//
	// Installation actions
	//  - Environmental variables
	//
	$dir = getPOSTparam4FullFilePath('rootdir');
	$rootdir    = array('rootdir' => (substr($dir,-1)!=='/'?$dir.'/':$dir));
	$sitename   = array('sitename' => getPOSTparam4HumanName('sitename'));
	$language   = array('language' => getPOSTparam4IdOrNumber('language'));

	// Add new data to variable session
	$_SESSION['variables'] = array_merge($_SESSION['variables'],$rootdir,$sitename,$language);
?>
	<legend class="installMsg">Step 2 - Setting your preferences</legend>

		<label for="userPass"><span class="ss_sprite ss_lock">Administrator password</span><br/><a href="#" class="small ss_sprite ss_arrow_refresh" onclick="randomPassword(8);">Auto generate a safe password</a></label>
		<input type="text" class="alt title" name="userPass" maxlenght="5" onkeyup="passwordStrength(this.value)" style="width:300px;" value="" id="userPass" />
		<div class="clear center">
			<div id="passwordStrength" class="strength0"></div>
		</div>
		<br/>&#160;<span class="ss_sprite ss_bullet_star small quiet">Remember your admin password as it cannot be retrieved</span>
		<label for="authcode"><span class="ss_sprite ss_textfield_key">Authentication PIN</span></label>
		<input type="text" class="alt title" name="authcode" maxlenght="5" style="width:300px;" value="<?php
			echo (empty($_SESSION['variables']['authcode']) ? $_SESSION['variables']['authcode'] . '::' . mt_rand('12345','98765') : $_SESSION['variables']['authcode']); ?>" id="authcode" />
		<br/>&#160;<span class="ss_sprite ss_bullet_star small quiet">Adding this PIN to the URL shows previews of inactive pages</span>
		<br/>&#160;<span class="ss_sprite ss_bullet_star small quiet">This code is used to encrypt passwords (salt)</span>
		<br class="clear"/>
		<label for="protect"><input type="checkbox" name="protect" value="true" <?php
			echo (!empty($_SESSION['variables']['protect']) && $_SESSION['variables']['protect'] == 'true' ? 'checked' : ''); ?> id="protect" /> Password protect the administration</label>
		<label for="version"><input type="checkbox" name="version" value="true"  <?php
			echo (!empty($_SESSION['variables']['version']) && $_SESSION['variables']['version'] == 'true' ? 'checked' : ''); ?>  id="version" /> Show version information</label>
		&#160;<span class="ss_sprite ss_bullet_star small quiet">Want to see the latest CCMS version at the dashboard?</span>
		<label for="iframe"><input type="checkbox" name="iframe" value="true"  <?php
			echo (!empty($_SESSION['variables']['iframe']) && $_SESSION['variables']['iframe'] == 'true' ? 'checked' : ''); ?> id="iframe" /> Support &amp; allow iframes</label>
		&#160;<span class="ss_sprite ss_bullet_star small quiet">Can iframes be managed from within the WYSIWYG editor?</span>
		<label for="wysiwyg"><input type="checkbox" name="wysiwyg" value="true"  <?php
			echo (!empty($_SESSION['variables']['wysiwyg']) && $_SESSION['variables']['wysiwyg'] == 'true' ? 'checked' : ''); ?>  id="wysiwyg" /> Enable the visual content editor</label>
		&#160;<span class="ss_sprite ss_bullet_star small quiet">Uncheck if you want to disable the visual editor all together</span>

		<p class="span-8 right">
			<button name="submit" type="submit"><span class="ss_sprite ss_lock_go">Proceed</span></button>
			<a href="index.php" title="Back to step first step">Cancel</a>
			<input type="hidden" name="do" value="<?php echo md5('3'); ?>" id="do" />
		</p>

<?php
} // Close step two

// Step three
if($nextstep == md5('3') && CheckAuth()) 
{
	//
	// Installation actions
	//  - Saving preferences
	//

	$version    = array('version' => getPOSTparam4boolean('version'));
	$iframe     = array('iframe' => getPOSTparam4boolean('iframe'));
	$wysiwyg    = array('wysiwyg' => getPOSTparam4boolean('wysiwyg'));
	$protect    = array('protect' => getPOSTparam4boolean('protect'));
	$userPass   = array('userPass' => $_POST['userPass']); // must store this in RAW form - will not be displayed anywhere, only fed to MD5()
	$authcode   = array('authcode' => getPOSTparam4IdOrNumber('authcode'));

	// Add new data to variable session
	$_SESSION['variables'] = array_merge($_SESSION['variables'],$version,$iframe,$wysiwyg,$protect,$userPass,$authcode);
?>
	<legend class="installMsg">Step 3 - Collecting your database details</legend>
		<label for="db_host"><span class="ss_sprite ss_server_database">Database host</span></label><input type="text" class="alt title" name="db_host" style="width:300px;" value="<?php
			echo (empty($_SESSION['variables']['db_host']) ? 'localhost' : $_SESSION['variables']['db_host']); ?>" id="db_host" />
		<br class="clear"/>
		<label for="db_user"><span class="ss_sprite ss_drive_user">Database username</span></label><input type="text" class="alt title" name="db_user" style="width:300px;" value="<?php
			echo (empty($_SESSION['variables']['db_user']) ? '' : $_SESSION['variables']['db_user']); ?>" id="db_user" />
		<br class="clear"/>
		<label for="db_pass"><span class="ss_sprite ss_drive_key">Database password</span></label><input type="password" class="title" name="db_pass" style="width:300px;" value="<?php
			echo (empty($_SESSION['variables']['db_pass']) ? '' : $_SESSION['variables']['db_pass']); ?>" id="db_pass" />
		<br class="clear"/>
		<label for="db_name"><span class="ss_sprite ss_database">Database name</span></label><input type="text" class="alt title" name="db_name" style="width:300px;" value="<?php
			echo (empty($_SESSION['variables']['db_name']) ? 'compactcms' : $_SESSION['variables']['db_name']); ?>" id="db_name" />
		<br class="clear"/>
		<label for="db_prefix"><span class="ss_sprite ss_database_table">Database table prefix</span></label><input type="text" class="alt title" name="db_prefix" style="width:300px;" value="<?php
			echo (empty($_SESSION['variables']['db_prefix']) ? 'ccms_' : $_SESSION['variables']['db_prefix']); ?>" id="db_prefix" />

		<p class="span-8 right">
			<button name="submit" type="submit"><span class="ss_sprite ss_information">To confirmation</span></button>
			<a href="index.php" title="Back to step first step">Cancel</a>
			<input type="hidden" name="do" value="<?php echo md5('4'); ?>" id="do" />
		</p>

<?php
} // Close step three

// Step four
if($nextstep == md5('4') && CheckAuth())
{
	//
	// Installation actions
	//  - Process database
	//
	$db_host    = array("db_host" => getPOSTparam4IdOrNumber('db_host'));
	$db_user    = array("db_user" => getPOSTparam4IdOrNumber('db_user'));
	$db_pass    = array("db_pass" => $_POST['db_pass']); // must be RAW
	$db_name    = array("db_name" => getPOSTparam4IdOrNumber('db_name'));
	$db_prefix  = array("db_prefix" => getPOSTparam4IdOrNumber('db_prefix'));

	// Add new data to variable session
	$_SESSION['variables'] = array_merge($_SESSION['variables'],$db_host,$db_user,$db_pass,$db_name,$db_prefix);

	// Define alternative table row color
	$alt_row = "#CDE6B3";

	//
	// Check for current chmod() if server != Windows
	//
	$chfile = array();
	if(!strpos($_SERVER['SERVER_SOFTWARE'], "Win"))
	{
		/*
		Note that the 'required' 0666/0777 access rights are, in reality, overdoing it. To be more precise:
		these files and directories should have [W]rite access enabled for the user the php binary is running
		under. Generally that user would be the user under which the webserver, e.g. apache, is running
		(CGI may be a different story!)

		Next to that, the directories tested here need e[X]ecutable access for that same user as well.

		This is /less/ than the 0666/0777 splattergun, but the latter is easier to grok and do for novices.
		So the message can remain 0666/0777 but in here we're performing the stricter check, as 'is_writable()'
		is the one which really counts after all: that's the very same check performed by the PHP engine on
		open-for-writing any file/directory.
		*/
		if(!is_writable(BASE_PATH.'/.htaccess')) { $chfile[] = '.htaccess (0666)'; }
		if(!is_writable(BASE_PATH.'/lib/config.inc.php')) { $chfile[] = '/lib/config.inc.php (0666)'; }
		if(!is_writable(BASE_PATH.'/content/home.php')) { $chfile[] = '/content/home.php (0666)'; }
		if(!is_writable(BASE_PATH.'/content/contact.php')) { $chfile[] = '/content/contact.php (0666)'; }
		if(!is_writable(BASE_PATH.'/lib/templates/ccms.tpl.html')) { $chfile[] = '/lib/templates/ccms.tpl.html (0666)'; }
		// Directories under risk due to chmod(0777)
		if(!is_writable(BASE_PATH.'/content/')) { $chfile[] = '/content/ (0777)'; }
		if(!is_writable(BASE_PATH.'/media/')) { $chfile[] = '/media/ (0777)'; }
		if(!is_writable(BASE_PATH.'/media/albums/')) { $chfile[] = '/media/albums/ (0777)'; }
		if(!is_writable(BASE_PATH.'/media/files/')) { $chfile[] = '/media/files/ (0777)'; }
		if(!is_writable(BASE_PATH.'/lib/includes/cache/')) { $chfile[] = '/lib/includes/cache/ (0777)'; }
	}
?>
	<legend class="installMsg">Step 4 - Review your input</legend>
		<?php 
		if(count($chfile) == 0) 
		{ 
		?>
			<p class="center"><span class="ss_sprite ss_tick"><em>All files are already correctly chmod()'ed</em></span></p>
		<?php 
		} 
		
		if(ini_get('safe_mode') || count($chfile) > 0)
		{ 
		?>
			<span class="ss_sprite ss_exclamation">&#160;</span><h2 style="display:inline;">Warning</h2>
			<p>It appears that it <abbr title="Based on current chmod() rights and/or safe mode restrictions">may not be possible</abbr> for the installer to chmod() various files. Please consider doing so manually <em>or</em> by using the <a href="index.php?do=<?php echo md5('ftp'); ?>">built-in FTP chmod function</a>.</p>
			<span>&rarr; <em>Files that still require chmod():</em></span>
			<ul>
				<?php 
				foreach ($chfile as $value) 
				{
					echo "<li>$value</li>";
				}
				?>
			</ul>
		<?php 
		} 
		?>
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
				<th width="55%" scope="row">Version Check</th>
				<td><?php echo ($_SESSION['variables']['version'] ? 'yes' : '---');?></td>
			</tr>
			<tr>
				<th scope="row">Iframes Allowed</th>
				<td><?php echo ($_SESSION['variables']['iframe'] ? 'yes' : '---');?></td>
			</tr>
			<tr style="background-color: <?php echo $alt_row; ?>;">
				<th scope="row">Visual editor</th>
				<td><?php echo ($_SESSION['variables']['wysiwyg'] ? 'yes' : '---');?></td>
			</tr>
			<tr>
				<th scope="row">User authentication</th>
				<td><?php echo ($_SESSION['variables']['protect'] ? 'yes' : '---');?></td>
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
			Any data that is currently in <strong><?php echo $_SESSION['variables']['db_prefix']; ?>pages</strong> and <strong><?php echo $_SESSION['variables']['db_prefix']; ?>users</strong> might be overwritten, depending your server configuration.
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
if($nextstep == md5('final') && CheckAuth())
{
	//
	// Installation actions
	//  - Set collected data
	//

	// Let's start with a clean sheet
	$err = 0;

	// Include MySQL class && initiate
	/*MARKER*/require_once(BASE_PATH.'/lib/class/mysql.class.php');
	$db = new MySQL();

	//
	// Try database connection
	//
	if (!$db->Open($_SESSION['variables']['db_name'], $_SESSION['variables']['db_host'], $_SESSION['variables']['db_user'], $_SESSION['variables']['db_pass']))
	{
		$errors[] = 'Error: could not connect to the database';
		$errors[] = $db->Error();
		$err++;
	}
	else
	{
		$log[] = "Database connection successful";
	}

	//
	// Insert database structure and sample data
	//
	if($err==0)
	{
		$currently_in_sqltextdata = false;

		function is_a_sql_query_piece($line)
		{
			global $currently_in_sqltextdata;

			$line = trim($line);

			if (!$currently_in_sqltextdata)
			{
				// not in a quoted text section? --> ignore empty lines and commented lines
				if (empty($line))
					return false;
				if (substr($line, 0, 2) == '--')
					return false;
				if ($line[0] == '#')
					return false;
			}

			/*
			* Check whether we're right smack in the middle of a multiline text being inserted, e.g.:
			*
			*     -- This should be recognized as a comment line!
			*     INSERT INTO ccms_modnews VALUES ('5', '1', '2nd-news', 'newz #2', 'wut?', 'And you call this news?
			*
			*     Good gracious me!
			*
			*     -- This would definitely break our array_filter if we\'re not careful...
			*     # and so would this line
			*     --and this
			*     --#and this!', '2010-10-31 06:20:00', '1');
			*/
			$line = str_replace("\\'", '', $line);
			$line = str_replace("''", '', $line);
			$quotedchunks = explode("'", $line);
			$idx = ($currently_in_sqltextdata ? 1 : 0) + count($quotedchunks);
			$currently_in_sqltextdata = ($idx % 2 == 0);

			// anything in a textblock is valid!

			return true;
		}

		$sqldump = array();

		$sql = file_get_contents(BASE_PATH.'/_docs/structure.sql');
		$sql = preg_replace('/ccms_/', $_SESSION['variables']['db_prefix'], $sql);
		$sql = preg_replace("/'[0-9a-f]{32}'/", "'".md5($_SESSION['variables']['userPass'].$_SESSION['variables']['authcode'])."'", $sql);

		// Execute per sql piece
		$queries = explode(";\n", $sql);
		foreach($queries as $tok)
		{
			// filter query: remove comment lines, then see if there's anything left to BE a query...
			$currently_in_sqltextdata = false;
			$lines = array_filter(explode("\n", $tok), "is_a_sql_query_piece");
			if ($currently_in_sqltextdata)
			{
				echo "<pre>B0rked on query:\n".$tok."\n---------------------------------\n",implode("\n",$lines);
				die();
			}
			$tok = trim(implode("\n", $lines));
			if (empty($tok))
				continue;


			if (!defined('CCMS_DEVELOPMENT_ENVIRONMENT'))
			{
				$results = $db->Query($tok);
				if ($results == false)
				{
					$errors[] = 'Error: executing query: ' . $tok;
					$errors[] = $db->Error();
					$err++;
				}
			}
			else
			{
				$sqldump[] = "Execute query:\n---------------------------------------\n" . $tok . "\n---------------------------------------\n";
			}
		}
		if ($err = 0)
		{
			$log[] = "Database structure and data successfully imported";
		}
		if (defined('CCMS_DEVELOPMENT_ENVIRONMENT'))
		{
?>
			<div id="configinc_display" >
				<h2>Database Initialization</h2>
				<pre class="small"><?php
					foreach($sqldump as $line)
					{
						echo htmlspecialchars($line);
					}
				?></pre>
			</div>
<?php
		}
	}

	//
	// Set chmod on config.inc.php, .htaccess, content, cache and albums
	//
	if($err==0 && !isset($_POST['ftp_host']) && empty($_POST['ftp_host']) && !strpos($_SERVER['SERVER_SOFTWARE'], "Win"))
	{
		// Set warning when safe mode is enabled
		if(ini_get('safe_mode')) 
		{
			$errors[] = 'Warning: safe mode is enabled, skipping chmod()';
		}

		// Set default values
		$chmod = 0;
		$errfile=0;

		// Chmod check and set function
		function setChmod($path, $value) 
		{
			// Check current chmod() status
			if(substr(sprintf('%o', fileperms(BASE_PATH.$path)), -4)!=$value) 
			{
				// If not set, set
				if(@chmod(BASE_PATH.$path, $value)) 
				{
					return true;
				}
			} 
			else 
			{
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

		if($chmod>0) 
		{
			$log[] = '<abbr title=".htaccess, config.inc.php, ./content/, ./lib/includes/cache/, back-up folder &amp; 2 media folders">Confirmed correct chmod() on '.$chmod.' files</abbr>';
		}
		if(!isset($chmod)||$chmod==0||$errfile>0) 
		{
			$errors[] = 'Warning: could not chmod() all files.';
			foreach ($errfile as $key => $value) 
			{
				$errors[] = $value;
			}
			$errors[] = 'Either use the <a href="index.php?do=' . md5('ftp') . '">built-in FTP chmod function</a>, or manually perform chmod().';
		}
	}

	//
	// Perform optional FTP chmod command
	//
	if(isset($_POST['ftp_host']) && !empty($_POST['ftp_host']) && isset($_POST['ftp_user']) && !empty($_POST['ftp_user'])) 
	{
		// Set up a connection or die
		$conn_id = ftp_connect($_POST['ftp_host']) or die("Couldn't connect to ".$_POST['ftp_host']);

		// Try to login using provided details
		if(@ftp_login($conn_id, $_POST['ftp_user'], $_POST['ftp_pass'])) {

			// trimPath function
			function trimPath($path,$depth) 
			{
				$path = explode('/',$path);
				$np = '/';
				for ($i=$depth; $i<count($path); $i++) 
				{
					$np .= $path[$i].'/';
				}
				return $np;
			}

			// Find FTP path
			$i      = 1;
			$path   = $_POST['ftp_path'];

			// Set max tries to 15
			for ($i=1; $i<15; $i++) 
			{ 
				if(@ftp_chdir($conn_id, trimPath($path,$i))) 
				{
					$log[] = "Successfully connected to FTP server";
					$i = 15;
				}
			}
		} 
		else 
		{
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

		if($ftp_chmod>0) 
		{
			$log[] = '<abbr title=".htaccess, config.inc.php, ./content/, ./lib/includes/cache/, back-up folder &amp; 2 media folders">Successful chmod() on '.$chmod.' files using FTP.</abbr>';
		} 
		elseif($ftp_chmod==0) 
		{
			$errors[] = 'Fatal: could not FTP chmod() various files.';
			$err++;
		}

		// Close the connection
		ftp_close($conn_id);
	}

	//
	// Write config.inc.php file
	//
	if($err==0)
	{
		/*
		Keep comments, etc. intact in the file; they help when manually modifying
		or upgrading the site.
		*/
		$configinc  = file_get_contents(BASE_PATH . '/lib/config.inc.php');

		// Write new variables to configuration file

		// Edit start line
		$newline = "Copyright (C) 2008 - ".date('Y')." ";
		$configinc = preg_replace('/Copyright \(C\) 2008 - [0-9]+ /', $newline, $configinc);

		// Compare old and new variables - the old set is already loaded at the top!
		foreach($cfg as $key=>$val)
		{
			if (isset($_SESSION['variables'][$key]))
			{
				$new_val = $_SESSION['variables'][$key];
			}
			else
			{
				$new_val = $cfg[$key];
			}
			// Rewrite the previous loaded string
			if($new_val=="true"||$new_val=="false")
			{
				$config_str = "\$cfg['{$key}'] = {$new_val};";
				$re_str = '/\$cfg\[\''.$key.'\'\]\s+=\s+[^;]+;/';
			}
			else
			{
				$config_str = "\$cfg['{$key}'] = '{$new_val}';";
				$re_str = '/\$cfg\[\''.$key.'\'\]\s+=\s+[\'"].*[\'"]\s*;/';
			}
			$configinc = preg_replace($re_str, $config_str, $configinc);
		}

		// Write the new setup to the config file
		if (!defined('CCMS_DEVELOPMENT_ENVIRONMENT'))
		{
			if ($fp = fopen(BASE_PATH . '/lib/config.inc.php', 'w'))
			{
				if(fwrite($fp, $configinc, strlen($configinc)))
				{
					$log[] = "Successfully wrote the new configuration values in the config.inc.php file";
				}
				else
				{
					$err++;
					$errors[] = "Fatal: Problem saving new configuration values";
				}
				fclose($fp);
			}
			else
			{
				$errors[] = 'Fatal: the configuration file is not writable.';
				$errors[] = 'Make sure the file is writable, or <a href="index.php?do=ff104b2dfab9fe8c0676587292a636d3">do so now</a>.';
				$err++;
			}
		}
		else
		{
?>
			<div id="configinc_display" >
				<h2>config.inc.php Configuration Values - after modification</h2>
				<pre class="small"><?php echo htmlspecialchars($configinc); ?></pre>
			</div>
<?php
			$log[] = "Successfully wrote the new configuration values in the config.inc.php file";
		}
	}
	//
	// Modify .htaccess file
	//
	if($err==0)
	{
		$htaccess   = file_get_contents(BASE_PATH.'/.htaccess');
		$newline    = "RewriteBase ".$_SESSION['variables']['rootdir']." "; // 'superfluous' space at the end there to simplify the match, even when moving the setup from /dir/ to /

		if(strpos($htaccess, $newline)===false)
		{
			$htaccess = preg_replace('/RewriteBase\s+\/.*/', $newline, $htaccess);
			if (!$htaccess)
			{
				$errors[] = 'Fatal: could not set the RewriteBase in the .htaccess file.';
				$err++;
			}
			else
			{
				if (!defined('CCMS_DEVELOPMENT_ENVIRONMENT'))
				{
					if ($fp = fopen(BASE_PATH.'/.htaccess', 'w'))
					{
						if(fwrite($fp, $htaccess, strlen($htaccess)))
						{
							$log[] = "Successfully rewrote the .htaccess file";
						}
						else
						{
							$errors[] = "Fatal: Problem saving new .htaccess file.";
							$errors[] = 'Make sure the file is writable, or <a href="index.php?do=ff104b2dfab9fe8c0676587292a636d3">do so now</a>.';
							$err++;
						}
						fclose($fp);
					}
					elseif($_SESSION['variables']['rootdir']=="/")
					{
						$errors[] = 'Warning: the .htaccess file is not writable.';
					}
					elseif($_SESSION['variables']['rootdir']!="/")
					{
						$errors[] = 'Fatal: the .htaccess file is not writable.';
						$errors[] = 'Make sure the file is writable, or <a href="index.php?do=ff104b2dfab9fe8c0676587292a636d3">do so now</a>.';
						$err++;
					}
				}
				else
				{
?>
					<div id="htaccess_display" >
						<h2>.htaccess Rewrite Rules - after modification</h2>
						<pre class="small"><?php echo htmlspecialchars($htaccess); ?></pre>
					</div>
<?php
					$log[] = "Successfully rewrote the .htaccess file";
				}
			}
		}
	}

?>
	<legend class="installMsg">Final - Finishing the installation</legend>
	<?php 
	if(isset($log)) 
	{
		unset($_SESSION['variables']); 
		?>
		<h2>Process results</h2>
		<p>
			<?php
			while (list($key,$value) = each($log)) 
			{
				echo '<span class="ss_sprite ss_accept">'.$value.'</span><br />';
			} 
			?>
		</p>
	<?php 
	} 
	if(isset($errors)) 
	{ 
	?>
		<h2>Errors &amp; warnings</h2>
		<p>
			<?php
			while (list($key,$value) = each($errors)) 
			{
				echo '<span class="ss_sprite ss_exclamation">'.$value.'</span><br />';
			} 
			?>
		</p>
	<?php 
	} 
	
	if($err==0) 
	{ 
	?>
		<h2>What's next?</h2>
		<p>The installation has been successful! You should now follow the steps below, to get you started.</p>
		<ol>
			<li>Delete the <em>./_install</em> directory</li>
			<li><a href="../admin/">Login</a> using username <span class="ss_sprite ss_user_red"><strong>admin</strong></span></li>
			<li>Change your password through the back-end</li>
			<li><a href="http://www.compactcms.nl/contact.html" target="_blank">Let me know</a> how you like CompactCMS!</li>
		</ol>
	<?php 
	} 
	else 
	{
		echo '<a href="index.php">Retry setting the variables</a>'; 
	}
} // Close final processing

?>