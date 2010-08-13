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

// Check first whether installation directory exists
if(is_dir('./_install/')&&is_file('./_install/index.php')) {
	header('Location: ./_install/index.php');
	exit();
}

// This file loads the appropriate configuration
require_once(dirname(__FILE__) . '/lib/sitemap.php'); 

// This file parses the template and coding
require_once(dirname(__FILE__) . '/lib/class/engine.class.php');

// Initialize ccmsParser class
$STP = new ccmsParser;

// Set friendly menu names
$ccms['mainmenu']	= (isset($ccms['structure1'])?$ccms['structure1']:null);
$ccms['leftmenu']	= (isset($ccms['structure2'])?$ccms['structure2']:null);
$ccms['rightmenu']	= (isset($ccms['structure3'])?$ccms['structure3']:null);
$ccms['footermenu']	= (isset($ccms['structure4'])?$ccms['structure4']:null);
$ccms['extramenu']	= (isset($ccms['structure5'])?$ccms['structure5']:null);

// Set the appropriate template
$STP->setTemplate('./lib/templates/'.$ccms['template'].'.tpl.html');

// Execute code
$STP->setParams($ccms);
$STP->parseAndEchoPHP();
?>