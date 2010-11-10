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

class FbX extends CcmsAjaxFbException {}; // nasty way to do 'shorthand in PHP -- I do miss my #define macros! :'-|

// Security functions



// Get permissions
$perm = $db->SelectSingleRowArray($cfg['db_prefix'].'cfgpermissions');
if (!$perm) $db->Kill("INTERNAL ERROR: 1 permission record MUST exist!");

/**
 *
 * Either INSERT or UPDATE preferences
 *
 */
if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST) && checkAuth()) 
{
	FbX::SetFeedbackLocation("permissions.Manage.php");
	try
	{
		// (!) Only administrators can change these values
		if($_SESSION['ccms_userLevel']>=4) 
		{
			// Execute UPDATE
			$values = array(); // [i_a] make sure $values is an empty array to start with here
			foreach ($_POST as $key => $value)
			{
				$key = filterParam4IdOrNumber($key);
				$setting = filterParam4Number($value);
				if (empty($key) || (empty($setting) && $value !== "0"))
					throw new FbX($ccms['lang']['system']['error_forged']); 
				$values[$key] = MySQL::SQLValue($setting, MySQL::SQLVALUE_NUMBER);
			}
			if($db->UpdateRows($cfg['db_prefix']."cfgpermissions", $values)) 
			{
				header('Location: ' . makeAbsoluteURI('permissions.Manage.php?status=notice&msg='.rawurlencode($ccms['lang']['backend']['settingssaved'])));
				exit();
			} 
			else 
			{
				throw new FbX($db->MyDyingMessage());
			}
		} 
		else 
		{
			throw new FbX($ccms['lang']['auth']['featnotallowed']);
		}
	}
	catch (CcmsAjaxFbException $e)
	{
		$e->croak();
	}
} 
else 
	die("No external access to file");
?>