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
define('CCMS_PERFORM_MINIMAL_INIT', true);


// Compress all output and coding
header('Content-type: text/html; charset=UTF-8');

// Define default location
if (!defined('BASE_PATH'))
{
	$base = str_replace('\\','/',dirname(dirname(dirname(dirname(__FILE__)))));
	define('BASE_PATH', $base);
}

// Include general configuration
/*MARKER*/require_once(BASE_PATH . '/lib/sitemap.php');

// Security functions



// Get permissions
$perm = $db->QuerySingleRowArray("SELECT * FROM ".$cfg['db_prefix']."cfgpermissions");

// Set default variables
$newsID 	= getPOSTparam4Number('newsID');
$pageID		= getPOSTparam4IdOrNumber('pageID');
$cfgID		= getPOSTparam4Number('cfgID');
$do_action 	= getGETparam4IdOrNumber('action');

/**
 *
 * Either INSERT or UPDATE news article
 *
 */
if($_SERVER['REQUEST_METHOD'] == "POST" && $do_action=="add-edit-news" && checkAuth()) 
{
	// Only if current user has the rights
	if($perm['manageModNews']>0 && $_SESSION['ccms_userLevel']>=$perm['manageModNews']) 
	{
		// Published
		$newsPublished = getPOSTparam4boolean('newsPublished');
		
		// Set all the submitted variables
		$values = array(); // [i_a] make sure $values is an empty array to start with here
		$values["userID"] = MySQL::SQLValue(getPOSTparam4IdOrNumber('newsAuthor'), MySQL::SQLVALUE_NUMBER);
		$values["pageID"] = MySQL::SQLValue(getPOSTparam4IdOrNumber('pageID'), MySQL::SQLVALUE_TEXT);
		$values["newsTitle"]  = MySQL::SQLValue($_POST['newsTitle'], MySQL::SQLVALUE_TEXT);
		$values["newsTeaser"]  = MySQL::SQLValue($_POST['newsTeaser'], MySQL::SQLVALUE_TEXT);
		$values["newsContent"]  = MySQL::SQLValue($_POST['newsContent'], MySQL::SQLVALUE_TEXT);
		$values["newsModified"]  = MySQL::SQLValue($_POST['newsModified'], MySQL::SQLVALUE_DATETIME);
		$values["newsPublished"]  = MySQL::SQLValue($newsPublished, MySQL::SQLVALUE_BOOLEAN);
	
		// Execute either INSERT or UPDATE based on $newsID
		if($db->AutoInsertUpdate($cfg['db_prefix']."modnews", $values, array("newsID" => MySQL::BuildSQLValue($newsID)))) 
		{
			header("Location: news.Manage.php?file=$pageID&status=notice&msg=".rawurlencode($ccms['lang']['backend']['itemcreated']));
			exit();
		} 
		else 
			$db->Kill();
	} 
	else 
		die($ccms['lang']['auth']['featnotallowed']);
}

/**
 *
 * Delete current news item
 *
 */
if($_SERVER['REQUEST_METHOD'] == "POST" && $do_action=="del-news" && checkAuth()) 
{
	// Only if current user has the rights
	if($perm['manageModNews']>0 && $_SESSION['ccms_userLevel']>=$perm['manageModNews']) 
	{
		// Number of selected items
		$total = count($_POST['newsID']);
		
		// If nothing selected, throw error
		if($total==0) 
		{
			header("Location: news.Manage.php?file=$pageID&status=error&msg=".rawurlencode($ccms['lang']['system']['error_selection']));
			exit();
		}
		
		// Delete details from the database
		$i=0;
		foreach ($_POST['newsID'] as $value) 
		{
			$values = array(); // [i_a] make sure $values is an empty array to start with here
			$values['newsID'] = MySQL::SQLValue($value,MySQL::SQLVALUE_NUMBER);
			$result = $db->DeleteRows($cfg['db_prefix']."modnews", $values);
			$i++;
		}
	
		// Check for errors
		if($result&&$i==$total) 
		{
			header("Location: news.Manage.php?file=$pageID&status=notice&msg=".rawurlencode($ccms['lang']['backend']['fullremoved']));
			exit();
		} 
		else 
			$db->Kill();
	} 
	else 
		die($ccms['lang']['auth']['featnotallowed']);
}

/**
 *
 * Save configuration preferences
 *
 */
if($_SERVER['REQUEST_METHOD'] == "POST" && $do_action=="cfg-news" && checkAuth()) 
{
	// Only if current user has the rights
	if($perm['manageModNews']>0 && $_SESSION['ccms_userLevel']>=$perm['manageModNews']) 
	{
		$values = array(); // [i_a] make sure $values is an empty array to start with here
		$values["pageID"]		= MySQL::SQLValue($pageID, MySQL::SQLVALUE_TEXT);
		$values["showLocale"]	= MySQL::SQLValue(getPOSTparam4IdOrNumber('locale'), MySQL::SQLVALUE_TEXT);
		$values["showMessage"]	= MySQL::SQLValue(htmlspecialchars($_POST['messages']), MySQL::SQLVALUE_NUMBER);
		$values["showAuthor"]	= MySQL::SQLValue(htmlspecialchars($_POST['author']), MySQL::SQLVALUE_BOOLEAN);
		$values["showDate"]		= MySQL::SQLValue(htmlspecialchars($_POST['show_modified']), MySQL::SQLVALUE_BOOLEAN);
		$values["showTeaser"] 	= MySQL::SQLValue(htmlspecialchars($_POST['show_teaser']), MySQL::SQLVALUE_BOOLEAN);

		// Execute the insert or update for current page
		if($db->AutoInsertUpdate($cfg['db_prefix']."cfgnews", $values, array("cfgID" => MySQL::BuildSQLValue($cfgID)))) 
		{
			header("Location: news.Manage.php?file=$pageID&status=notice&msg=".rawurlencode($ccms['lang']['backend']['settingssaved']));
			exit();
		} 
		else 
			$db->Kill();
	} 
	else 
		die($ccms['lang']['auth']['featnotallowed']);
}
?>
