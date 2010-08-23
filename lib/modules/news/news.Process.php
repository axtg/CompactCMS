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
require_once('../../sitemap.php');

// Security functions
$canarycage		= md5(session_id());
$currenthost	= md5($_SERVER['HTTP_HOST']);

// Get permissions
$perm = $db->QuerySingleRowArray("SELECT * FROM ".$cfg['db_prefix']."cfgpermissions");

// Set default variables
$newsID 	= (isset($_POST['newsID'])&&!empty($_POST['newsID'])&&is_numeric($_POST['newsID'])?$_POST['newsID']:'0');
$do_action 	= (isset($_GET['action'])&&!empty($_GET['action'])?$_GET['action']:null);

 /**
 *
 * Either INSERT or UPDATE news article
 *
 */
if($_SERVER['REQUEST_METHOD'] == "POST" && $do_action=="add-edit-news" && checkAuth($canarycage,$currenthost)) {
	
	// Only if current user has the rights
	if($_SESSION['ccms_userLevel']>=$perm['manageModNews']) {
	
		// Published
		$newsPublished = (!isset($_POST['newsPublished'])&&empty($_POST['newsPublished'])?"0":"1");
		
		// Set all the submitted variables
		$values["userID"] = MySQL::SQLValue($_POST['newsAuthor'], MySQL::SQLVALUE_NUMBER);
		$values["newsTitle"]  = MySQL::SQLValue($_POST['newsTitle'], MySQL::SQLVALUE_TEXT);
		$values["newsTeaser"]  = MySQL::SQLValue($_POST['newsTeaser'], MySQL::SQLVALUE_TEXT);
		$values["newsContent"]  = MySQL::SQLValue($_POST['newsContent'], MySQL::SQLVALUE_TEXT);
		$values["newsModified"]  = MySQL::SQLValue($_POST['newsModified'], MySQL::SQLVALUE_TEXT);
		$values["newsPublished"]  = MySQL::SQLValue($newsPublished);
	
		// Execute either INSERT or UPDATE based on $newsID
		if($db->AutoInsertUpdate($cfg['db_prefix']."modnews", $values, array("newsID" => $newsID))) {
			header("Location: news.Manage.php?status=notice&msg=".$ccms['lang']['backend']['itemcreated']);
			exit();
		} else $db->Kill();
	} else die($ccms['lang']['auth']['featnotallowed']);
}

 /**
 *
 * Delete current news item
 *
 */
if($_SERVER['REQUEST_METHOD'] == "POST" && $do_action=="del-news" && checkAuth($canarycage,$currenthost)) {
	
	// Only if current user has the rights
	if($_SESSION['ccms_userLevel']>=$perm['manageModNews']) {
		
		// Number of selected items
		$total = count($_POST['newsID']);
		
		// If nothing selected, throw error
		if($total==0) {
			header("Location: news.Manage.php?status=error&msg=".$ccms['lang']['system']['error_selection']);
			exit();
		}
		
		// Delete details from the database
		$i=0;
		foreach ($_POST['newsID'] as $value) {
			$values['newsID'] = MySQL::SQLValue($value,MySQL::SQLVALUE_NUMBER);
			$result = $db->DeleteRows($cfg['db_prefix']."modnews", $values);
			$i++;
		}
	
		// Check for errors
		if($result&&$i==$total) {
			header("Location: news.Manage.php?status=notice&msg=".$ccms['lang']['backend']['statusremoved']);
			exit();
		} else $db->Kill();
	} else die($ccms['lang']['auth']['featnotallowed']);
}

 /**
 *
 * Save configuration preferences
 *
 */
if($_SERVER['REQUEST_METHOD'] == "POST" && $do_action=="cfg-news" && checkAuth($canarycage,$currenthost)) {
	
	// Only if current user has the rights
	if($_SESSION['ccms_userLevel']>=$perm['manageModNews']) {
		
		$values["showMessage"]	= MySQL::SQLValue($_POST['messages']);
		$values["showAuthor"]	= MySQL::SQLValue($_POST['author']);
		$values["showDate"]		= MySQL::SQLValue($_POST['show_modified']);
		$values["showTeaser"] 	= MySQL::SQLValue($_POST['show_teaser']);

		// Execute the update where the ID is 1
		if($db->UpdateRows($cfg['db_prefix']."cfgnews", $values)) {
			header("Location: news.Manage.php?status=notice&msg=".$ccms['lang']['backend']['settingssaved']);
			exit();
		} else $db->Kill();
	} else die($ccms['lang']['auth']['featnotallowed']);
}
?>