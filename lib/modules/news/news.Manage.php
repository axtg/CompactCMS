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

// Include general configuration
require_once('../../sitemap.php');

$canarycage	= md5(session_id());
$currenthost= md5($_SERVER['HTTP_HOST']);
$do 		= (isset($_GET['do'])?$_GET['do']:null);

// Get permissions
$perm = $db->QuerySingleRowArray("SELECT * FROM ".$cfg['db_prefix']."cfgpermissions");

?>

<?php if(checkAuth($canarycage,$currenthost) && isset($_SESSION['rc1']) && !empty($_SESSION['rc2'])) { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title>News module</title>
		<link rel="stylesheet" type="text/css" href="../../../admin/img/styles/base.css,layout.css,sprite.css" />
		<?php $cfg['language'] = (file_exists('../../../admin/includes/tiny_mce/langs/'.$cfg['language'].'.js'))?$cfg['language']:'en';?>
	</head>
<body>
	<div class="module">
		
		<div class="span-15 colborder">
			<h2>Manage current news items</h2>
			<form action="news.Process.php?action=del-news" method="post" accept-charset="utf-8">
				<table border="0" cellspacing="5" cellpadding="5">
					<tr>
						<?php if($_SESSION['ccms_userLevel']>=$perm['manageModNews']) { ?><th class="span-1">&#160;</th><?php } ?>
						<th class="span-1">&#160;</th>
						<th class="span-5">Title</th>
						<th class="span-4">Author</th>
						<th class="span-4">Date</th>
					</tr>
					<?php
					// Load recordset
					$i=0;
					$db->Query("SELECT * FROM `".$cfg['db_prefix']."modnews` n LEFT JOIN `".$cfg['db_prefix']."users` u ON n.userID=u.userID");
		
					// Start switch for news, select all the right details
					if($db->HasRecords()) {
		
						while (!$db->EndOfSeek()) {
			    		$rsNews = $db->Row();
					
			    	// Alternate rows
			    	if($i%2 != '1') {
						echo '<tr style="background-color: #E6F2D9;">';
					} else { 
						echo '<tr>';
					} ?>
						<?php if($_SESSION['ccms_userLevel']>=$perm['manageModNews']) { ?>
							<td><input type="checkbox" name="newsID[]" value="<?php echo $rsNews->newsID; ?>" id="newsID"></td>
						<?php } ?>
						<td><?php echo "<span class='ss_sprite ".($rsNews->newsPublished>0?"ss_bullet_green'>":"ss_bullet_red'>")."</span>"; ?></td>
						<?php if($_SESSION['ccms_userLevel']>=$perm['manageModNews']) { ?>
							<td><span class="ss_sprite ss_pencil"><a href="news.Write.php?newsID=<?php echo $rsNews->newsID; ?>"><?php echo $rsNews->newsTitle; ?></a></span></td>
						<?php } else { ?>
							<td><?php echo $rsNews->newsTitle; ?></td>
						<?php } ?>
						<td><span class="ss_sprite ss_email"><a href="mailto:<?php echo $rsNews->userEmail; ?>"><?php echo substr(ucfirst($rsNews->userFirst),0,1).'. '.ucfirst($rsNews->userLast); ?></a></span></td>
						<td><span class="ss_sprite ss_calendar"><?php echo date('Y-m-d G:i', strtotime($rsNews->newsModified)); ?></span></td>
					</tr>
					<?php 
					$i++; }
					} ?>
				</table>
				<hr />
				<?php if($_SESSION['ccms_userLevel']>=$perm['manageModNews']) { ?><button type="submit" name="deleteNews"><span class="ss_sprite ss_newspaper_delete">Delete</span></button><?php } ?>
			</form>
		</div>
		<div class="span-6">
			<h2>Add news</h2>
			<?php if($_SESSION['ccms_userLevel']>=$perm['manageModNews']) { ?>
			<p><span class="ss_sprite ss_newspaper_add"><a href="news.Write.php">Add news article</a></span></p>
			
			<h2>Manage settings</h2>
			<?php $rsCfg = $db->QuerySingleRow("SELECT * FROM `".$cfg['db_prefix']."cfgnews`"); ?>
			<form action="news.Process.php?action=cfg-news" method="post" accept-charset="utf-8">
				<label for="show_messages">Number of messages on front-end</label>
				<input type="text" class="text" name="messages" value="<?php echo (isset($rsCfg)?$rsCfg->showMessage:5); ?>" id="messages" />
				
				<label>Show author</label>
					<img src="../../../admin/img/spacer.gif" height="10" width="20" alt=" "/>
					<label style="display:inline;" for="show_author1">Yes</label>
					<input type="radio" name="author" <?php echo (isset($rsCfg)&&$rsCfg->showAuthor==1?"checked":null); ?> value="1" id="author1" />
						<img src="../../../admin/img/spacer.gif" height="10" width="50" alt=" "/>
					<label style="display:inline;" for="show_author0">No</label>
					<input type="radio" name="author" <?php echo (isset($rsCfg)&&$rsCfg->showAuthor==0?"checked":null); ?> value="0" id="author0" />
				<br/><br/>
				<label>Show publication date</label>
					<img src="../../../admin/img/spacer.gif" height="10" width="20" alt=" "/>
					<label style="display:inline;" for="show_modified1">Yes</label>
					<input type="radio" name="show_modified" <?php echo (isset($rsCfg)&&$rsCfg->showDate==1?"checked":null); ?> value="1" id="show_modified1" />
						<img src="../../../admin/img/spacer.gif" height="10" width="50" alt=" "/>
					<label style="display:inline;" for="show_modified0">No</label>
					<input type="radio" name="show_modified" <?php echo (isset($rsCfg)&&$rsCfg->showDate==0?"checked":null); ?> value="0" id="show_modified0" />
				<br/><br/>
				<label>Only show teaser</label>
					<img src="../../../admin/img/spacer.gif" height="10" width="20" alt=" "/>
					<label style="display:inline;" for="show_teaser1">Yes</label>
					<input type="radio" name="show_teaser" <?php echo (isset($rsCfg)&&$rsCfg->showTeaser==1?"checked":null); ?> value="1" id="show_teaser1" />
						<img src="../../../admin/img/spacer.gif" height="10" width="50" alt=" "/>
					<label style="display:inline;" for="show_modified0">No</label>
					<input type="radio" name="show_teaser" <?php echo (isset($rsCfg)&&$rsCfg->showTeaser==0?"checked":null); ?> value="0" id="show_teaser0" />
				<br/><br/>			
				<p class="prepend-3"><button type="submit"><span class="ss_sprite ss_disk">Save</span></button></p>
			</form>
			<?php } else echo $ccms['lang']['auth']['featnotallowed']; ?>
		</div>
		
	</div>
</body>
</html>
<?php } else die("No external access to file");?>