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
define('CCMS_PERFORM_MINIMAL_INIT', true);


// Define default location
if (!defined('BASE_PATH'))
{
	$base = str_replace('\\','/',dirname(dirname(dirname(dirname(__FILE__)))));
	define('BASE_PATH', $base);
}

// Include general configuration
/*MARKER*/require_once(BASE_PATH . '/lib/sitemap.php');


if (!checkAuth() || empty($_SESSION['rc1']) || empty($_SESSION['rc2'])) 
{
	die("No external access to file");
}



$do	= getGETparam4IdOrNumber('do');
$pageID	= getGETparam4Filename('file');

// Get permissions
$perm = $db->QuerySingleRowArray("SELECT * FROM ".$cfg['db_prefix']."cfgpermissions");



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
		
		<div class="center <?php echo (isset($_GET['status'])?htmlspecialchars($_GET['status']):null); ?>">
			<?php if(isset($_GET['msg'])&&strlen($_GET['msg'])>2) { echo htmlspecialchars(rawurldecode($_GET['msg'])); } ?>
		</div>
			
		<div class="span-16 colborder">
		<h2><?php echo $ccms['lang']['guestbook']['manage']; ?></h2>
		<?php // Load recordset
		if (!$db->Query("SELECT * FROM `".$cfg['db_prefix']."modcomment` WHERE pageID=" . MySQL::SQLValue($pageID, MySQL::SQLVALUE_TEXT) . " ORDER BY `commentID` DESC"))
			$db->Kill();
	
		// Start switch for news, select all the right details
		if($db->HasRecords()) 
		{
			while (!$db->EndOfSeek()) 
			{
				$rsComment = $db->Row(); 
				?>
				<div class="span-5">
					<?php 
					if ($cfg['enable_gravatar']) 
					{ 
						?><img src="http://www.gravatar.com/avatar.php?gravatar_id=<?php echo md5($rsComment->commentEmail); ?>&amp;size=80&amp;rating=G" style="margin:4px;border:2px solid #000;" alt="<?php echo $ccms['lang']['guestbook']['avatar'];?>"/><br/><?php
					}
					?><img src="./resources/<?php echo $rsComment->commentRate;?>-star.gif" alt="<?php echo $ccms['lang']['guestbook']['rating']." ".$rsComment->commentRate;?>"/>
				</div>
				<div class="span-17">
					<p><strong><?php echo (!empty($rsComment->commentUrl)?'<a href="'.$rsComment->commentUrl.'" target="_blank">'.$rsComment->commentName.'</a>':$rsComment->commentName); echo ' '.$ccms['lang']['guestbook']['wrote']; ?>:</strong></p>
					<p><?php echo nl2br(strip_tags($rsComment->commentContent));?></p>
					<p>
					<?php 
					if($perm['manageModComment']>0 && $_SESSION['ccms_userLevel']>=$perm['manageModComment']) 
					{ 
					?>
						<span class="ss_sprite ss_cross small"><a onclick="return confirmation()" href="comment.Process.php?pageID=<?php echo $pageID;?>&amp;commentID=<?php echo $rsComment->commentID;?>&amp;action=del-comment"><?php echo $ccms['lang']['guestbook']['delentry'];?></a></span>
					<?php 
					} 
					?>
					<span class="ss_sprite ss_email small"><a href="mailto:<?php echo $rsComment->commentEmail;?>"><?php echo $ccms['lang']['guestbook']['sendmail'];?></a></span>
					<span class="ss_sprite ss_world quiet small"><?php echo $rsComment->commentHost; ?></span>
					<span class="ss_sprite ss_time quiet small"><?php echo date('Y-m-d G:i:s',strtotime($rsComment->commentTimestamp))?></span>
					</p>
				</div>
				<hr class="space" />
				<?php 
				$i++; 
			}
		} else echo $ccms['lang']['guestbook']['noposts']; ?>
		</div>
	
		<div class="span-6">
			<h2>Configuration</h2>
			<?php 
			if($perm['manageModComment']>0 && $_SESSION['ccms_userLevel']>=$perm['manageModComment']) 
			{ 
				$rsCfg = $db->QuerySingleRow("SELECT * FROM `".$cfg['db_prefix']."cfgcomment` WHERE pageID='$pageID'"); 
				if ($db->HasRecords())
				{
					$showmsg = max(1,intval($rsCfg->showMessage)); // always show at least 1 news item on a comment page!
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
				<form action="comment.Process.php?action=save-cfg" method="post" accept-charset="utf-8">
					
					<label for="messages"><?php echo $ccms['lang']['news']['numbermess']; ?></label>
					<input type="input" style="width:200px;" class="text" name="messages" value="<?php echo $showmsg; ?>" id="messages" />
					
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
					
					<p>
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
