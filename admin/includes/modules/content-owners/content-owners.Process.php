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

// Compress all output and coding
header('Content-type: text/html; charset=UTF-8');

// Include general configuration
require_once('../../../../lib/sitemap.php');

// Security functions
$canarycage		= md5(session_id());
$currenthost	= md5($_SERVER['HTTP_HOST']);

// Get permissions
$perm = $db->QuerySingleRowArray("SELECT * FROM ".$cfg['db_prefix']."cfgpermissions");

 /**
 *
 * Either INSERT or UPDATE preferences
 *
 */
if($_SERVER['REQUEST_METHOD'] == "POST" && checkAuth($canarycage,$currenthost)) {
	
	// Only if current user has the rights
	if($_SESSION['ccms_userLevel']>=$perm['manageOwners']) {
		
		// Set all values back to zero
		$values["user_ids"] = 0;
		if($db->UpdateRows($cfg['db_prefix']."pages", $values)) {
			
			// If all empty, we're done here
			if(empty($_POST['owner'])) {
				header("Location: ./content-owners.Manage.php?status=notice&action=".$ccms['lang']['backend']['settingssaved']);
				exit();
			}
		
			// Otherwise, set the page owners
			$i=0;
			foreach ($_POST['owner'] as $value) {
				// Split posted variable
				$explode = explode("||",$value);
			
				// Set variables
				$pageID = (isset($explode['1'])&&is_numeric($explode['1'])?$explode['1']:null);
				$current = $db->QuerySingleValue("SELECT user_ids FROM ".$cfg['db_prefix']."pages WHERE page_id='".$pageID."'");
				$users = $current.$explode['0'].'||';
				$values["user_ids"] = MySQL::SQLValue($users,MySQL::SQLVALUE_TEXT);
			
				if($db->UpdateRows($cfg['db_prefix']."pages", $values, array("page_id" => "\"$pageID\""))) {
					$i++;
				}
				
				if($i==count($_POST['owner'])) {
					header("Location: ./content-owners.Manage.php?status=notice&action=".$ccms['lang']['backend']['success']);
					exit();
				} 
			}
		}
	} else die($ccms['lang']['auth']['featnotallowed']);
} else die("No external access to file");
?>