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
	$base = str_replace('\\','/',dirname(__FILE__));
	define('BASE_PATH', $base);
}

// This file loads the appropriate configuration
/*MARKER*/require_once(BASE_PATH . '/lib/sitemap.php');


// Check first whether installation directory exists
if(is_dir('./_install/')&&is_file('./_install/index.php') && !defined('CCMS_DEVELOPMENT_ENVIRONMENT')) {
	header('Location: ' . makeAbsoluteURI('./_install/index.php'));
	exit();
}


// This file parses the template and coding
/*MARKER*/require_once(BASE_PATH . '/lib/class/engine.class.php');

// Initialize ccmsParser class
$STP = new ccmsParser;

// Set friendly menu names
$ccms['mainmenu']	= (isset($ccms['structure1'])?$ccms['structure1']:null);
$ccms['leftmenu']	= (isset($ccms['structure2'])?$ccms['structure2']:null);
$ccms['rightmenu']	= (isset($ccms['structure3'])?$ccms['structure3']:null);
$ccms['footermenu']	= (isset($ccms['structure4'])?$ccms['structure4']:null);
$ccms['extramenu']	= (isset($ccms['structure5'])?$ccms['structure5']:null);

// Set the appropriate template
$STP->setTemplate('./lib/templates/'.$ccms['template'].'.tpl.html', '<?php global $db, $cfg, $ccms; ?>');

// Execute code
$STP->setParams($ccms);
$STP->parseAndEchoPHP();

/*
echo "<p>\$ccms = <pre>";
var_dump($ccms);
*/
?>