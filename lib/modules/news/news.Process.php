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
	$base = str_replace('\\','/',dirname(dirname(dirname(dirname(__FILE__)))));
	define('BASE_PATH', $base);
}

// Include general configuration
/*MARKER*/require_once(BASE_PATH . '/lib/sitemap.php');

// Security functions



// Get permissions
$perm = $db->SelectSingleRowArray($cfg['db_prefix'].'cfgpermissions');
if (!$perm) $db->Kill("INTERNAL ERROR: 1 permission record MUST exist!");

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
		$newsAuthor = getPOSTparam4Number('newsAuthor');
		//$pageID = getPOSTparam4IdOrNumber('pageID');
		$newsTitle = getPOSTparam4DisplayHTML('newsTitle');
		$newsTeaser = getPOSTparam4DisplayHTML('newsTeaser');
		$newsContent = getPOSTparam4DisplayHTML('newsContent');
		$newsModified = getPOSTparam4DateTime('newsModified', time());
		$newsPublished = getPOSTparam4boolean('newsPublished');
		
		// Set all the submitted variables
		$values = array(); // [i_a] make sure $values is an empty array to start with here
		$values["userID"] = MySQL::SQLValue($newsAuthor, MySQL::SQLVALUE_NUMBER);
		$values["pageID"] = MySQL::SQLValue($pageID, MySQL::SQLVALUE_TEXT);
		$values["newsTitle"] = MySQL::SQLValue($newsTitle, MySQL::SQLVALUE_TEXT);
		$values["newsTeaser"] = MySQL::SQLValue($newsTeaser, MySQL::SQLVALUE_TEXT);
		$values["newsContent"] = MySQL::SQLValue($newsContent, MySQL::SQLVALUE_TEXT);
		$values["newsModified"] = MySQL::SQLValue($newsModified, MySQL::SQLVALUE_DATETIME);
		$values["newsPublished"] = MySQL::SQLValue($newsPublished, MySQL::SQLVALUE_BOOLEAN);
	
		// Execute either INSERT or UPDATE based on $newsID
		if($db->AutoInsertUpdate($cfg['db_prefix']."modnews", $values, array("newsID" => MySQL::BuildSQLValue($newsID)))) 
		{
			header('Location: ' . makeAbsoluteURI('news.Manage.php?file='.$pageID.'&status=notice&msg='.rawurlencode($ccms['lang']['backend']['itemcreated'])));
			exit();
		} 
		else 
		{
			$db->Kill();
		}
	} 
	else 
	{
		die($ccms['lang']['auth']['featnotallowed']);
	}
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
		$total = (!empty($_POST['newsID']) ? count($_POST['newsID']) : 0);
		
		// If nothing selected, throw error
		if($total==0) 
		{
			header('Location: ' . makeAbsoluteURI('news.Manage.php?file='.$pageID.'&status=error&msg='.rawurlencode($ccms['lang']['system']['error_selection'])));
			exit();
		}
		
		// Delete details from the database
		$i=0;
		foreach ($_POST['newsID'] as $idnum) 
		{
			$idnum = filterParam4Number($idnum);
			
			$values = array(); // [i_a] make sure $values is an empty array to start with here
			$values['newsID'] = MySQL::SQLValue($idnum,MySQL::SQLVALUE_NUMBER);
			$result = $db->DeleteRows($cfg['db_prefix']."modnews", $values);
			$i++;
		}
	
		// Check for errors
		if($result && $i==$total) 
		{
			header('Location: ' . makeAbsoluteURI('news.Manage.php?file='.$pageID.'&status=notice&msg='.rawurlencode($ccms['lang']['backend']['fullremoved'])));
			exit();
		} 
		else 
		{
			$db->Kill();
		}
	} 
	else 
	{
		die($ccms['lang']['auth']['featnotallowed']);
	}
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
		$showLocale	= getPOSTparam4IdOrNumber('locale');
		$showMessage = getPOSTparam4Number('messages');
		$showAuthor = getPOSTparam4boolean('author');
		$showDate = getPOSTparam4boolean('show_modified');
		$showTeaser = getPOSTparam4boolean('show_teaser');
		
		$values = array(); // [i_a] make sure $values is an empty array to start with here
		$values["pageID"]		= MySQL::SQLValue($pageID, MySQL::SQLVALUE_TEXT);
		$values["showLocale"]	= MySQL::SQLValue($showLocale, MySQL::SQLVALUE_TEXT);
		$values["showMessage"]	= MySQL::SQLValue($showMessage, MySQL::SQLVALUE_NUMBER);
		$values["showAuthor"]	= MySQL::SQLValue($showAuthor, MySQL::SQLVALUE_BOOLEAN);
		$values["showDate"]		= MySQL::SQLValue($showDate, MySQL::SQLVALUE_BOOLEAN);
		$values["showTeaser"] 	= MySQL::SQLValue($showTeaser, MySQL::SQLVALUE_BOOLEAN);

		// Execute the insert or update for current page
		if($db->AutoInsertUpdate($cfg['db_prefix']."cfgnews", $values, array("cfgID" => MySQL::BuildSQLValue($cfgID)))) 
		{
			header('Location: ' . makeAbsoluteURI('news.Manage.php?file='.$pageID.'&status=notice&msg='.rawurlencode($ccms['lang']['backend']['settingssaved'])));
			exit();
		} 
		else 
		{
			$db->Kill();
		}
	} 
	else 
	{
		die($ccms['lang']['auth']['featnotallowed']);
	}
}
?>
