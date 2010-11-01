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
$commentID 	= getGETparam4Number('commentID');
$pageID		= getPOSTparam4Filename('pageID');
$cfgID		= getPOSTparam4Number('cfgID');
$do_action 	= getGETparam4IdOrNumber('action');

 /**
 *
 * Show comments
 *
 */
if($_SERVER['REQUEST_METHOD'] == "GET" && $do_action=="show-comments" && checkAuth()) {
		
	// Pagination variables
	$pageID	= getGETparam4Filename('page');
	$rsCfg	= $db->QuerySingleValue("SELECT showMessage FROM `".$cfg['db_prefix']."cfgcomment` WHERE pageID='$pageID'");
	$rsLoc	= $db->QuerySingleValue("SELECT showLocale FROM `".$cfg['db_prefix']."cfgcomment` WHERE pageID='$pageID'");
	$max 	= (!empty($rsCfg)?$rsCfg:'10');
	$limit 	= (isset($_GET['offset'])&&$_GET['offset']>0?($_GET['offset']*$max).','.$max:"0,$max");
	$total	= count($db->QueryArray("SELECT commentID FROM `".$cfg['db_prefix']."modcomment` WHERE pageID='$pageID'"));
	
	// Set front-end language
	SetUpLanguageAndLocale($rsLoc);

	// Load recordset
	$db->Query("SELECT * FROM `".$cfg['db_prefix']."modcomment` WHERE pageID='$pageID' ORDER BY `commentID` DESC LIMIT $limit");
	
	// Start switch for comments, select all the right details
	if($db->HasRecords()) {
		while (!$db->EndOfSeek()) {
		$rsComment = $db->Row(); ?>
	
			<div id="s-display">
				<div id="s-avatar">
					<img src="http://www.gravatar.com/avatar.php?gravatar_id=<?php echo md5($rsComment->commentEmail);?>&amp;size=80&amp;rating=G" alt="<?php echo $ccms['lang']['guestbook']['avatar'];?>" /><br/>
				</div>
				<div id="s-name">
					<?php echo (!empty($rsComment->commentUrl)?'<a href="'.$rsComment->commentUrl.'" rel="nofollow" target="_blank">'.$rsComment->commentName.'</a>':$rsComment->commentName).' '.$ccms['lang']['guestbook']['wrote']; ?>:
				</div>
				<div id="s-comment"><p><?php echo nl2br(strip_tags($rsComment->commentContent)); ?></p></div>
				<div id="s-rating">
					<p>
						<img src="<?php echo $cfg['rootdir']; ?>lib/modules/comment/resources/<?php echo $rsComment->commentRate;?>-star.gif" alt="<?php echo $ccms['lang']['guestbook']['rating']." ".$rsComment->commentRate; ?>" /><br/>
						<?php echo htmlentities(strftime('%A %d %B %Y, %H:%M',strtotime($rsComment->commentTimestamp)));?>
					</p>
				</div>
			</div>
		<?php } ?>
		
		<div class="pagination">
			<?php $current = (isset($_GET['offset'])&&$_GET['offset']>0?$_GET['offset']:'0'); ?>
			<?php for ($i=0; $i<$total; $i++) { 
				$linktext = ($i/$max>0?($i/$max)+1:1);
				if($i%$max==0&&$current==($i/$max)) {
					echo '<span class="current">'.$linktext.'</span>';
				} elseif($i%$max==0&&$current!=($i/$max)) {
					echo '<a href="?offset='.$i/$max.'">'.$linktext.'</a>';
				}
			} ?>
		</div>
		<p>&#160;</p>

	<?php } else echo $ccms['lang']['guestbook']['noposts'];
}

 /**
 *
 * Delete comment
 *
 */
if($_SERVER['REQUEST_METHOD'] == "GET" && $do_action=="del-comment" && checkAuth()) {
	
	// Only if current user has the rights
	if($_SESSION['ccms_userLevel']>=$perm['manageModComment']) {
	
		$values['commentID'] = MySQL::SQLValue($commentID,MySQL::SQLVALUE_NUMBER);
		if($db->DeleteRows($cfg['db_prefix']."modcomment", $values)) {
			header("Location: comment.Manage.php?status=notice&file=".$_GET['pageID']."&msg=".$ccms['lang']['backend']['fullremoved']);
			exit();
		} 
	} else die($ccms['lang']['auth']['featnotallowed']);
}

 /**
 *
 * Add comment
 *
 */
if($_SERVER['REQUEST_METHOD'] == "POST" && $do_action=="add-comment" && checkAuth() && $_POST['verification']==$_SESSION['ccms_captcha']) 
{
	$values['pageID']		= MySQL::SQLValue($_POST['pageID'], MySQL::SQLVALUE_TEXT);
	$values['commentName']	= MySQL::SQLValue($_POST['name'], MySQL::SQLVALUE_TEXT);
	$values['commentEmail']	= MySQL::SQLValue($_POST['email'], MySQL::SQLVALUE_TEXT);
	$values['commentUrl']	= MySQL::SQLValue($_POST['website'], MySQL::SQLVALUE_TEXT);
	$values['commentRate']	= MySQL::SQLValue($_POST['rating'], MySQL::SQLVALUE_NUMBER);
	$values['commentContent'] = MySQL::SQLValue(strip_tags($_POST['comment']), MySQL::SQLVALUE_TEXT);
	$values['commentHost']	= MySQL::SQLValue($_SERVER['REMOTE_ADDR'], MySQL::SQLVALUE_TEXT);
	
	// Insert new page into database
	$db->InsertRow($cfg['db_prefix']."modcomment", $values);
}

 /**
 *
 * Save configuration
 *
 */
if($_SERVER['REQUEST_METHOD'] == "POST" && $do_action=="save-cfg" && checkAuth()) {

	$values['pageID'] = MySQL::SQLValue($pageID, MySQL::SQLVALUE_TEXT);
	$values['showMessage'] = MySQL::SQLValue(getPOSTparam4Number('messages']), MySQL::SQLVALUE_NUMBER);
	$values['showLocale'] = MySQL::SQLValue($_POST['locale'], MySQL::SQLVALUE_TEXT);

	// Insert or update configuration
	if($db->AutoInsertUpdate($cfg['db_prefix']."cfgcomment", $values, array("cfgID" => $cfgID))) {
		header("Location: comment.Manage.php?file=$pageID&status=notice&msg=".$ccms['lang']['backend']['settingssaved']);
		exit();
	} else $db->Kill();
}
?>