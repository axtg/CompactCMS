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

// Some security functions
$canarycage		= md5(session_id());
$currenthost	= md5($_SERVER['HTTP_HOST']);

// Get permissions
$perm = $db->QuerySingleRowArray("SELECT * FROM ".$cfg['db_prefix']."cfgpermissions");

// Set default variables
$album_name	= (isset($_POST['album'])&&!empty($_POST['album'])?$_POST['album']:null);
$do_action	= (isset($_GET['action'])&&!empty($_GET['action'])?$_GET['action']:null);

 /**
 *
 * Create a new album
 *
 */
if($_SERVER['REQUEST_METHOD'] == "POST" && $do_action == "create-album" && checkAuth($canarycage,$currenthost)) {

	// Only if current user has the rights
	if($_SESSION['ccms_userLevel']>=$perm['manageModLightbox']) {
	
		if($album_name!=null) {
			$dest = BASE_PATH.'/media/albums/'.$album_name;
			if(!is_dir($dest)) {
				if(mkdir($dest)&&mkdir($dest.'/_thumbs')) {
					header("Location: lightbox.Manage.php?status=success&msg=created&album=$album_name");
					exit();
				} else {
					header("Location: lightbox.Manage.php?status=error&msg=writeerr");
					exit();
				}
			} else {
				header("Location: lightbox.Manage.php?status=error&msg=duperr");
				exit();
			}
		} else {
			header("Location: lightbox.Manage.php?status=error&msg=invalid");
			exit();
		}
	} else die($ccms['lang']['auth']['featnotallowed']);
}

 /**
 *
 * Delete a current album (including all of its files)
 *
 */
if($_SERVER['REQUEST_METHOD'] == "POST" && $do_action == "del-album" && checkAuth($canarycage,$currenthost)) {

	// Only if current user has the rights
	if($_SESSION['ccms_userLevel']>=$perm['manageModLightbox']) {

		function rrmdir($dir) {
			if (is_dir($dir)) {
				$objects = scandir($dir);
				
				foreach ($objects as $object) {
					if ($object != "." && $object != "..") {
						if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
					}
				}
				reset($objects);
				rmdir($dir);
			} return true;
	 	}
	
		$total 	= count($_POST['albumID']);
		$i		= 0;
		foreach ($_POST['albumID'] as $key => $value) {
			if(!empty($key)&&!empty($value)) {
				$dest = BASE_PATH.'/media/albums/'.$value;
				if(is_dir($dest)) {
					if(rrmdir($dest)) {
						$i++;
					}
				}
			}
		}
		if($total==$i) {
			header("Location: lightbox.Manage.php?status=success&msg=X");
			exit();
		}
	} else die($ccms['lang']['auth']['featnotallowed']);
}

 /**
 *
 * Delete a single image
 *
 */
if($_SERVER['REQUEST_METHOD'] == "GET" && $do_action == "del-image" && checkAuth($canarycage,$currenthost)) {
	
	// Only if current user has the rights
	if($_SESSION['ccms_userLevel']>=$perm['manageModLightbox']) {

		$album = (isset($_GET['album'])&&!empty($_GET['album'])?$_GET['album']:null);
		$image = (isset($_GET['image'])&&!empty($_GET['image'])?$_GET['image']:null);
		
		if(!empty($album)&&!empty($image)) {
			$file	= BASE_PATH.'/media/albums/'.$album.'/'.$image;
			$thumb	= BASE_PATH.'/media/albums/'.$album.'/_thumbs/'.$image;
			if(is_file($file)) {
				if(unlink($file)&&unlink($thumb)) {
					header("Location:lightbox.Manage.php?status=success&msg=success&album=$album");
					exit();
				} else {
					header("Location:lightbox.Manage.php?status=error&msg=failed&album=$album");
					exit();
				}
			}
		}
	} else die($ccms['lang']['auth']['featnotallowed']);
}

 /**
 *
 * Process and save image plus thumbnail
 *
 */
if($_SERVER['REQUEST_METHOD'] == "POST" && $do_action == "save-files" && checkAuth($canarycage,$currenthost)) {
	
	$dest = BASE_PATH.'/media/albums/'.$album_name;
	if(!is_dir($dest)) {
		header("Location: lightbox.Manage.php?status=error&msg=writeerr");
		exit();
	} else
	
	// Validation
	$error 		= false;

	if (!isset($_FILES['Filedata']) || !is_uploaded_file($_FILES['Filedata']['tmp_name'])) {
		$error = 'Invalid Upload';
	}
	
	if (!$error && !($size = @getimagesize($_FILES['Filedata']['tmp_name']) ) ) {
		$error = 'Please upload only images, no other files are supported.';
	}
	
	if (!$error && !in_array($size[2], array(1, 2, 3, 7, 8) ) ) {
		$error = 'Please upload only images of type JPEG, GIF or PNG.';
	}
	
	if (!$error && ($size[0] < 50) || ($size[1] < 50)) {
		$error = 'Please upload an image bigger than 50px.';
	}
	
	// Get file extension
	$extension	= strtolower(substr($_FILES['Filedata']['name'], strrpos($_FILES['Filedata']['name'], '.') + 1));
	
	// Do resize
	if($extension=="jpg" || $extension=="jpeg" ) {
		$uploadedfile = $_FILES['Filedata']['tmp_name'];
		$src = imagecreatefromjpeg($uploadedfile);
	} else if($extension=="png") {
		$uploadedfile = $_FILES['Filedata']['tmp_name'];
		$src = imagecreatefrompng($uploadedfile);
	}
	else {
		$src = imagecreatefromgif($_FILES['Filedata']['tmp_name']);
	}
		 
	list($width,$height)=getimagesize($uploadedfile);
	
	// Resize original file to max 640 x 480
	$newwidth	= '640';
	$newheight	= ($height/$width)*$newwidth;
	$tmp		= imagecreatetruecolor($newwidth,$newheight);
	imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
	
	// Resize thumbnail to approx 80 x 80
	$newwidth_t	= '80';
	$newheight_t= ($height/$width)*$newwidth_t;
	$tmp_t		= imagecreatetruecolor($newwidth_t,$newheight_t);
	imagecopyresampled($tmp_t,$src,0,0,0,0,$newwidth_t,$newheight_t,$width,$height);
	
	// Save newly generated versions
	$thumbnail	= $dest.'/_thumbs/'. $_FILES['Filedata']['name'];
	$original	= $dest.'/'.$_FILES['Filedata']['name'];
	
	imagejpeg($tmp, $original, 100);
	imagejpeg($tmp_t, $thumbnail, 100);

	// Check for errors
	if ($error) {
		$return = array(
			'status' => '0',
			'error' => $error
		);
	} else {
		$return = array(
			'status' => '1',
			'name' => $_FILES['Filedata']['name'],
			'src' => $dest.'/'.$_FILES['Filedata']['name']
		);
		// Our processing, we get a hash value from the file
		$return['hash'] = md5_file($return['src']);
		$info = @getimagesize($return['src']);
		
		if ($info) {
			$return['width'] = $info[0];
			$return['height'] = $info[1];
			$return['mime'] = $info['mime'];
		}
	}

	if (isset($_REQUEST['response']) && $_REQUEST['response'] == 'xml') {
		/* do nothing */
	} else {
		// header('Content-type: application/json');
		echo json_encode($return);
	}
}
?>