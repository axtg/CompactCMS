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

/* make sure no-one can run anything here if they didn't arrive through 'proper channels' */
if(!defined("COMPACTCMS_CODE")) { define("COMPACTCMS_CODE", 1); } /*MARKER*/

/*
We're only processing form requests / actions here, no need to load the page content in sitemap.php, etc. 
*/
if (!defined('CCMS_PERFORM_MINIMAL_INIT')) { define('CCMS_PERFORM_MINIMAL_INIT', true); }


// Define default location
if (!defined('BASE_PATH'))
{
	$base = str_replace('\\','/',dirname(dirname(dirname(dirname(__FILE__)))));
	define('BASE_PATH', $base);
}

// Include general configuration
/*MARKER*/require_once(BASE_PATH . '/admin/includes/security.inc.php'); // when session expires or is overridden, the login page won't show if we don't include this one, but a cryptic error will be printed.


if (!checkAuth() || empty($_SESSION['rc1']) || empty($_SESSION['rc2'])) 
{
	die("No external access to file");
}



$do	= getGETparam4IdOrNumber('do');
$status = getGETparam4IdOrNumber('status');
$status_message = getGETparam4DisplayHTML('msg');
$pageID	= getGETparam4Filename('file');


// Get permissions
$perm = $db->SelectSingleRowArray($cfg['db_prefix'].'cfgpermissions');
if (!$perm) $db->Kill("INTERNAL ERROR: 1 permission record MUST exist!");



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>News module</title>
	<link rel="stylesheet" type="text/css" href="../../../admin/img/styles/base.css,liquid.css,layout.css,sprite.css" />
	<script type="text/javascript" charset="utf-8">
function confirmation()
{
	var answer=confirm('<?php echo $ccms['lang']['backend']['confirmdelete']; ?>');
	if(answer)
	{
		try
		{
			return true;
		}
		catch(e)
		{
		}
	}
	else
	{
		return false;
	}
}
	</script>
</head>
<body>
	<div class="module">
		<div class="center <?php echo $status; ?>">
			<?php 
			if(!empty($status_message)) 
			{ 
				echo '<span class="ss_sprite '.($status == 'notice' ? 'ss_accept' : 'ss_error').'">'.$status_message.'</span>'; 
			} 
			?>
		</div>
		
		<div class="span-16 colborder">
			<h2><?php echo $ccms['lang']['news']['manage']; ?></h2>
			<?php
			// Load recordset
			$i=0;
			if(!$db->Query("SELECT * FROM `".$cfg['db_prefix']."modnews` n LEFT JOIN `".$cfg['db_prefix']."users` u ON n.userID=u.userID WHERE pageID=".MySQL::SQLValue($pageID,MySQL::SQLVALUE_TEXT)))
				$db->Kill();

			// Start switch for news, select all the right details
			if($db->HasRecords()) 
			{ 
			?>
				<form action="news.Process.php?action=del-news" method="post" accept-charset="utf-8">
					<table border="0" cellspacing="5" cellpadding="5">
						<tr>
							<?php 
							if($perm['manageModNews']>0 && $_SESSION['ccms_userLevel']>=$perm['manageModNews']) 
							{ 
							?>
								<th class="span-1">&#160;</th>
							<?php 
							} 
							?>
							<th class="span-1">&#160;</th>
							<th class="span-7"><?php echo $ccms['lang']['news']['title']; ?></th>
							<th class="span-5"><?php echo $ccms['lang']['news']['author']; ?></th>
							<th class="span-4"><?php echo $ccms['lang']['news']['date']; ?></th>
						</tr>
						<?php
						while (!$db->EndOfSeek()) 
						{
							$rsNews = $db->Row();
							
							// Alternate rows
							if($i%2 != 1) 
							{
								echo '<tr style="background-color: #E6F2D9;">';
							} 
							else 
							{ 
								echo '<tr>';
							} 
						
								if($perm['manageModNews']>0 && $_SESSION['ccms_userLevel']>=$perm['manageModNews']) 
								{ 
								?>
									<td><input type="checkbox" name="newsID[]" value="<?php echo $rsNews->newsID; ?>" id="newsID"></td>
								<?php 
								} 
								?>
								<td><?php echo "<span class='ss_sprite ".($rsNews->newsPublished!=0?"ss_bullet_green'>":"ss_bullet_red'>")."</span>"; ?></td>
								<?php 
								if($perm['manageModNews']>0 && $_SESSION['ccms_userLevel']>=$perm['manageModNews']) 
								{ 
								?>
									<td><span class="ss_sprite ss_pencil"><a href="news.Write.php?pageID=<?php echo $pageID;?>&amp;newsID=<?php echo $rsNews->newsID; ?>"><?php echo substr($rsNews->newsTitle,0,20); echo (strlen($rsNews->newsTitle)>20?'...':null); ?></a></span></td>
								<?php 
								} 
								else 
								{ 
								?>
									<td><?php echo $rsNews->newsTitle; ?></td>
								<?php 
								} 
								?>
								<td><span class="ss_sprite ss_email"><a href="mailto:<?php echo $rsNews->userEmail; ?>"><?php echo substr(ucfirst($rsNews->userFirst),0,1).'. '.ucfirst($rsNews->userLast); ?></a></span></td>
								<td><span class="ss_sprite ss_calendar"><?php echo date('Y-m-d G:i', strtotime($rsNews->newsModified)); ?></span></td>
							</tr>
							<?php 
							$i++; 
						}
						?>
					</table>
					<hr />
					<?php 
					if($perm['manageModNews']>0 && $_SESSION['ccms_userLevel']>=$perm['manageModNews']) 
					{ 
					?>
						<input type="hidden" name="pageID" value="<?php echo $pageID; ?>" id="pageID">
						<button type="submit" onclick="return confirmation();" name="deleteNews"><span class="ss_sprite ss_newspaper_delete"><?php echo $ccms['lang']['backend']['delete']; ?></span></button>
					<?php 
					} 
					?>
				</form>
				<?php
			} 
			else 
				echo $ccms['lang']['system']['noresults'];  // [i_a] moved OUTSIDE the <form><table> : correct HTML
			?>
		</div>
		<div class="span-6">
			<h2><?php echo $ccms['lang']['news']['addnews']; ?></h2>
			<?php 
			if($perm['manageModNews']>0 && $_SESSION['ccms_userLevel']>=$perm['manageModNews']) 
			{ 
			?>
				<p><span class="ss_sprite ss_newspaper_add"><a href="news.Write.php?pageID=<?php echo $pageID; ?>"><?php echo $ccms['lang']['news']['addnewslink']; ?></a></span></p>
			
				<h2><?php echo $ccms['lang']['news']['settings']; ?></h2>
				<?php 
				if (!$rsCfg = $db->QuerySingleRow("SELECT * FROM `".$cfg['db_prefix']."cfgnews` WHERE pageID=".MySQL::SQLValue($pageID,MySQL::SQLVALUE_TEXT)))
					$db->Kill();
					
				if ($db->HasRecords())
				{
					$showmsg = max(1,intval($rsCfg->showMessage)); // always show at least 1 news item on a news page!
					$locale = $rsCfg->showLocale;
					$showauth = intval($rsCfg->showAuthor);
					$showdate = intval($rsCfg->showDate);
					$showteaser = intval($rsCfg->showTeaser);
					//$newscfgid = $rsCfg->cfgID;
				}
				else // set defaults 
				{
					// [i_a] when no cfg record, fill in the defaults as were also set in the database
					$showmsg = 3;
					$locale = $cfg['locale'];
					$showauth = 1;
					$showdate = 1;
					$showteaser = 0;
					//$newscfgid = null;
				}
				?>
				<form action="news.Process.php?action=cfg-news" method="post" accept-charset="utf-8">
					<label for="messages"><?php echo $ccms['lang']['news']['numbermess']; ?></label>
					<input type="text" class="text" name="messages" value="<?php echo $showmsg; ?>" id="messages" />
					
					<label for="locale"><?php echo $ccms['lang']['forms']['setlocale']; ?></label>
					<select name="locale" class="text" id="locale" size="1">
						<option value="eng" <?php echo ($locale=='eng'?"selected":null); ?>>English</option>
						<option value="esp" <?php echo ($locale=='esp'?"selected":null); ?>>español</option>
						<option value="fra" <?php echo ($locale=='fra'?"selected":null); ?>>français</option>
						<option value="deu" <?php echo ($locale=='deu'?"selected":null); ?>>Deutsch</option>
						<option value="nld" <?php echo ($locale=='nld'?"selected":null); ?>>Nederlands</option>
						<option value="ita" <?php echo ($locale=='ita'?"selected":null); ?>>italiano</option>
						<option value="dan" <?php echo ($locale=='dan'?"selected":null); ?>>dansk</option>
						<option value="fin" <?php echo ($locale=='fin'?"selected":null); ?>>suomi</option>
						<option value="nor" <?php echo ($locale=='nor'?"selected":null); ?>>norsk</option>
						<option value="rus" <?php echo ($locale=='rus'?"selected":null); ?>>русский</option>
						<option value="sve" <?php echo ($locale=='sve'?"selected":null); ?>>svenska</option>
						<option value="ind" <?php echo ($locale=='ind'?"selected":null); ?>>Bahasa Indonesia</option>
					</select>
					
					<label><?php echo $ccms['lang']['news']['showauthor']; ?></label>
						<img src="../../../admin/img/spacer.gif" height="10" width="20" alt=" "/>
						<label style="display:inline;" for="show_author1"><?php echo $ccms['lang']['backend']['yes']; ?></label>
						<input type="radio" name="author" <?php echo ($showauth!=0?"checked":null); ?> value="1" id="author1" />
							<img src="../../../admin/img/spacer.gif" height="10" width="50" alt=" "/>
						<label style="display:inline;" for="show_author0"><?php echo $ccms['lang']['backend']['no']; ?></label>
						<input type="radio" name="author" <?php echo ($showauth==0?"checked":null); ?> value="0" id="author0" />
					<br/><br/>
					<label><?php echo $ccms['lang']['news']['showdate']; ?></label>
						<img src="../../../admin/img/spacer.gif" height="10" width="20" alt=" "/>
						<label style="display:inline;" for="show_modified1"><?php echo $ccms['lang']['backend']['yes']; ?></label>
						<input type="radio" name="show_modified" <?php echo ($showdate!=0?"checked":null); ?> value="1" id="show_modified1" />
							<img src="../../../admin/img/spacer.gif" height="10" width="50" alt=" "/>
						<label style="display:inline;" for="show_modified0"><?php echo $ccms['lang']['backend']['no']; ?></label>
						<input type="radio" name="show_modified" <?php echo ($showdate==0?"checked":null); ?> value="0" id="show_modified0" />
					<br/><br/>
					<label><?php echo $ccms['lang']['news']['showteaser']; ?></label>
						<img src="../../../admin/img/spacer.gif" height="10" width="20" alt=" "/>
						<label style="display:inline;" for="show_teaser1"><?php echo $ccms['lang']['backend']['yes']; ?></label>
						<input type="radio" name="show_teaser" <?php echo ($showteaser!=0?"checked":null); ?> value="1" id="show_teaser1" />
							<img src="../../../admin/img/spacer.gif" height="10" width="50" alt=" "/>
						<label style="display:inline;" for="show_modified0"><?php echo $ccms['lang']['backend']['no']; ?></label>
						<input type="radio" name="show_teaser" <?php echo ($showteaser==0?"checked":null); ?> value="0" id="show_teaser0" />
					<br/><br/>			
					<p class="prepend-3">
						<?php echo ($db->HasRecords()?'<input type="hidden" name="cfgID" value="'.$rsCfg->cfgID.'" id="cfgID" />':null); ?>
						<input type="hidden" name="pageID" value="<?php echo $pageID; ?>" id="pageID" />
						<button type="submit"><span class="ss_sprite ss_disk"><?php echo $ccms['lang']['forms']['savebutton']; ?></span></button>
					</p>
				</form>
			<?php 
			} 
			else 
				echo $ccms['lang']['auth']['featnotallowed']; 
			?>
		</div>
	</div>
</body>
</html>
