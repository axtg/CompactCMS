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

//
// Below you'll find all the configuration variables. Please refer to the
// documentation (http://www.compactcms.nl/docs.html) for descriptions and details.
//

// Standard configuration
$cfg['sitename']	= "CompactCMS";	// Your site name - this will show in the front-end.
$cfg['language']	= "en"; 		// Select the language of your (front-end) website - en, nl, de, es.
$cfg['rootdir']		= "/";			// The root directory where CCMS is installed under, must include trailing slash ('/').
$cfg['authcode']	= "12345";		// Add ?preview=X (where X is your authcode) to your address bar to preview unpublished items.

// Detailed configuration. By default shouldn't need adjusting.
$cfg['version']		= true;			// Check for the latest CompactCMS version [true/false]
$cfg['iframe']		= false;		// Support iframes within the editor [true/false] (risky(!), see documentation.)
$cfg['wysiwyg']		= true;			// Enable the WYSIWYG editor [true/false]

// Security configuration
$cfg['protect']		= true;			// Password protect your administration [true/false]

// Database settings (case sensitive)
$cfg['db_host']		= "localhost"; 	// MySQL setting - your database host.
$cfg['db_user']		= "root";		// MySQL setting - your database username.
$cfg['db_pass']		= ""; 			// MySQL setting - your database password.
$cfg['db_name']		= "compactcms";	// MySQL setting - your database name.
$cfg['db_prefix']	= "ccms_";		// MySQL setting - the table prefix.

// Restrict for editing with the editor. Use the filenames without extension.
$cfg['restrict']	= array("foo","bar");
$cfg['default_template'] = 'ccms'; 
$cfg['enable_gravatar'] = true;  // set to 'false' if you don't want to show 'gravatars' in your comment pages for each commenter.


define('CCMS_DEVELOPMENT_ENVIRONMENT', true); // comment out or remove this line for any release install (where you are not developing on a local & very safe machine)
?>