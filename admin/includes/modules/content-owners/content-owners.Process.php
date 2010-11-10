<?php
 /**
 * Copyright (C) 2008 - 2010 by Xander Groesbeek (CompactCMS.nl)
 * 
 * Last changed: $LastChangedDate$
 * @author $Author$
 * @version $Revision$
 * @package CompactCMS.nl
 * @license GNU General Public License v3
 * 
 * This file is part of CompactCMS.
 * 
 * CompactCMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * CompactCMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * A reference to the original author of CompactCMS and its copyright
 * should be clearly visible AT ALL TIMES for the user of the back-
 * end. You are NOT allowed to remove any references to the original
 * author, communicating the product to be your own, without written
 * permission of the original copyright owner.
 * 
 * You should have received a copy of the GNU General Public License
 * along with CompactCMS. If not, see <http://www.gnu.org/licenses/>.
 * 
 * > Contact me for any inquiries.
 * > E: Xander@CompactCMS.nl
 * > W: http://community.CompactCMS.nl/forum
**/

/* make sure no-one can run anything here if they didn't arrive through 'proper channels' */
if(!defined("COMPACTCMS_CODE")) { define("COMPACTCMS_CODE", 1); } /*MARKER*/

/*
We're only processing form requests / actions here, no need to load the page content in sitemap.php, etc. 
*/
if (!defined('CCMS_PERFORM_MINIMAL_INIT')) { define('CCMS_PERFORM_MINIMAL_INIT', true); }


// Compress all output and coding
header('Content-type: text/html; charset=UTF-8');

// Define default location
if (!defined('BASE_PATH'))
{
	$base = str_replace('\\','/',dirname(dirname(dirname(dirname(dirname(__FILE__))))));
	define('BASE_PATH', $base);
}

// Include general configuration
/*MARKER*/require_once(BASE_PATH . '/lib/sitemap.php');

// Security functions



// Get permissions
$perm = $db->SelectSingleRowArray($cfg['db_prefix'].'cfgpermissions');
if (!$perm) $db->Kill("INTERNAL ERROR: 1 permission record MUST exist!");

 /**
 *
 * Either INSERT or UPDATE preferences
 *
 */
if($_SERVER['REQUEST_METHOD'] == "POST" && checkAuth()) 
{
	// Only if current user has the rights
	if($_SESSION['ccms_userLevel']>=$perm['manageOwners']) 
	{
		/*
		Since the number of items to process is PAGES x USERS, this number can become rather large, even for moderately small sites.
		
		Hence we do this in two phases: 
		
		1) first we collect the user=owner set per page in an associative array.
		
		2) next, we update the database for each page collected in phase 1.
		
		This is different from the original approach in that:
		
		a) it cuts down the number of queries by a factor of USERS
		
		b) it does NOT reset the ownership of ALL pages at the start with another query --> unmentioned pages don't change.
		
		Particularly (b) plays well into our hands when we expand the notion of 'filtered page sets' in the admin section, i.e.
		an admin section which currently only shows a SUBSET of all the pages available on the site.
		*/
		
		// If all empty, we're done here
		if(empty($_POST['owner'])) 
		{
			header('Location: ' . makeAbsoluteURI('./content-owners.Manage.php?status=notice&msg='.rawurlencode($ccms['lang']['backend']['settingssaved'])));
			exit();
		}
	
		// Otherwise, set the page owners (phase #1)
		$ownership = array();
		foreach ($_POST['owner'] as $value) 
		{
			// Split posted variable
			$explode = explode("||",$value);
		
			// Set variables
			$userID = filterParam4Number($explode[0]);
			$pageID = filterParam4Number($explode[1]);
			if (empty($userID) || empty($pageID))
			{
				die($ccms['lang']['system']['error_forged']);
			}
			if (empty($ownership[$pageID])) $ownership[$pageID] = '';
			$ownership[$pageID] .= '||' . $userID; // add user; we'll trim leading '||' in phase 2
		}
		
		// now update page ownership in the database (phase #2); order doesn't matter
		foreach($ownership as $page_id => $users)
		{
			$users = ltrim($users, '|');
			
			$values = array();
			$values["user_ids"] = MySQL::SQLValue($users,MySQL::SQLVALUE_TEXT);
		
			if(!$db->UpdateRows($cfg['db_prefix']."pages", $values, array("page_id" => MySQL::SQLValue($page_id,MySQL::SQLVALUE_NUMBER)))) 
			{
				$db->Kill();
			}
		}	
		
		header('Location: ' . makeAbsoluteURI('./content-owners.Manage.php?status=notice&msg='.rawurlencode($ccms['lang']['backend']['success'])));
		exit();
	} 
	else 
		die($ccms['lang']['auth']['featnotallowed']);
} 
else 
	die("No external access to file");
?>