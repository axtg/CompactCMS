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
require_once(dirname(dirname(dirname(__FILE__))).'/lib/sitemap.php');

// Disable common hacking functions
ini_set('base64_decode', 'Off');
ini_set('exec', 'Off');
ini_set('allow_url_fopen', 'Off');
ini_set('allow_url_include', 'Off');

// Set appropriate auth.inc.php file location
$loc = "";
if(is_file('../lib/includes/auth.inc.php')) 			{ $loc = "../lib/includes/auth.inc.php"; } 
elseif(is_file('../../lib/includes/auth.inc.php'))		{ $loc = "../../lib/includes/auth.inc.php"; }
elseif(is_file('../../../lib/includes/auth.inc.php'))	{ $loc = "../../../lib/includes/auth.inc.php"; }

	// Check whether current user has running session
	if(empty($_SESSION['ccms_userID']) && $cfg['protect']==true){
		header('Location: '.$loc);
		exit();
	}
	
	// Do log-out (kill sessions) and redirect
	if(isset($_GET['do'])&&$_GET['do']=="logout") {
		// Unset all of the session variables.
		$_SESSION = array();
		
		// Destroy session
		if (ini_get("session.use_cookies")) {
		    $params = session_get_cookie_params();
		    setcookie(session_name(), '', time() - 42000,
		        $params["ccms_userID"], $params["domain"],
		        $params["secure"], $params["httponly"]
		    );
		}
		
		// Generate a new session_id
		session_regenerate_id();
		
		// Finally, destroy the session.
		if(session_destroy()) {
			header('Location: '.$loc);
			exit();
		}
		
		if(empty($_SESSION['ccms_userID'])) {
			header('Location: '.$loc);
			exit();
		}
	}
	
?>