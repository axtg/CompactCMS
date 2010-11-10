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
if(!defined("COMPACTCMS_CODE")) { die('Illegal entry point!'); } /*MARKER*/



// Load news preferences
$pageID	= getGETparam4Filename('page');
$do	= getGETparam4IdOrNumber('do');
$id = getGETparam4IdOrNumber('id');

$numCfg = 0;
if(!empty($pageID)) 
{
	$rsCfg = $db->SelectSingleRow($cfg['db_prefix']."cfgnews", array('pageID' => MySQL::SQLValue($pageID, MySQL::SQLVALUE_TEXT)));
	$numCfg	= $db->RowCount();
}
$locale 	= ($numCfg>0?$rsCfg->showLocale:$cfg['locale']);

// we only need to check if the given page is a valid news page...
$news_in_page = $db->SelectSingleValue($cfg['db_prefix']."pages", array('module' => "'news'", 'urlpage' => MySQL::SQLValue($pageID, MySQL::SQLVALUE_TEXT)), array('urlpage'));
if ($db->Error()) $db->Kill();

// Set front-end language
SetUpLanguageAndLocale($locale);

// Limited characters
$special_chars = array("#","$","%","@","^","&","*","!","~","‘","\"","’","'","=","?","/","[","]","(",")","|","<",">",";","\\",",");

// Do actions for overview
if(empty($id)) 
{
	if(!empty($news_in_page)) 
	{
		// Load recordset for all news on specific news page
		$db->Query("SELECT * FROM `".$cfg['db_prefix']."modnews` n LEFT JOIN `".$cfg['db_prefix']."users` u ON n.userID=u.userID WHERE newsPublished<>'0' AND pageID=" . MySQL::SQLValue($pageID, MySQL::SQLVALUE_TEXT) . " ORDER BY newsModified DESC");
	} 
	else 
	{
		// Load recordset for all news on any page
		$db->Query("SELECT * FROM `".$cfg['db_prefix']."modnews` n LEFT JOIN `".$cfg['db_prefix']."users` u ON n.userID=u.userID WHERE newsPublished<>'0' ORDER BY newsModified DESC");
	}
} 
else 
{
	// Do actions for specific news
	
	// Define requested news item
	$newsID = explode("-", $id, 2);
	
	// Load recordset for newsID
	$db->Query("SELECT * FROM `".$cfg['db_prefix']."modnews` n LEFT JOIN `".$cfg['db_prefix']."users` u ON n.userID=u.userID WHERE newsID=".MySQL::SQLValue($newsID[0], MySQL::SQLVALUE_NUMBER)." AND newsPublished<>'0' AND pageID=".MySQL::SQLValue($pageID, MySQL::SQLVALUE_TEXT));
}

?>
<!-- additional style and code -->
<link rel="stylesheet" href="<?php echo $cfg['rootdir'];?>lib/modules/news/resources/style.css" type="text/css" media="screen" title="lightbox" charset="utf-8" />

<!-- lay-out -->

<?php 
// Start switch for news, select all the right details
if($db->HasRecords()) 
{
	if(empty($do)) 
	{
		if($numCfg>0) 
		{
			$listMax 	= ($rsCfg->showMessage > $db->RowCount() ? $db->RowCount() : $rsCfg->showMessage);
			$showTeaser	= intval($rsCfg->showTeaser);
			$showAuthor	= intval($rsCfg->showAuthor);
			$showDate	= intval($rsCfg->showDate);
		} 
		else 
		{
			$listMax = $db->RowCount();
			$showTeaser	= 1;
			$showAuthor	= 1;
			$showDate	= 1;
		}
		for ($i=0; $i<$listMax; $i++) 
		{ 
			$rsNews = $db->Row();
			?>
			<div>
				<?php 
				if($showDate==1) 
				{ 
				?>
					<strong class="date"><?php echo htmlentities(strftime('%B',strtotime($rsNews->newsModified))); ?><span><?php echo date('j',strtotime($rsNews->newsModified)); ?></span></strong>
				<?php 
				} 

				if(empty($id)) 
				{ 
					// Filter spaces, non-file characters and account for UTF-8
					$newsTitle = htmlentities(strtolower($rsNews->newsTitle),ENT_COMPAT,'UTF-8');
					$newsTitle = str_replace($special_chars, "", $newsTitle); 
					$newsTitle = str_replace(' ','-',$newsTitle);
					
					?>
					<h2><a href="<?php echo $cfg['rootdir'].$rsNews->pageID.'/'.$rsNews->newsID.'-'.$newsTitle; ?>.html"><?php echo $rsNews->newsTitle; ?></a></h2>
					<p><strong><?php echo $rsNews->newsTeaser; ?></strong></p>
					<?php 
					if($showTeaser==0) 
					{ 
					?>
						<p><?php echo $rsNews->newsContent; ?></p>
					<?php 
					} 

					if($showAuthor==1||$showDate==1) 
					{ 
					?>
						<p style="text-align:right;">
							<?php 
							if($showAuthor==1) 
							{ 
								echo '<strong>&ndash; '.$rsNews->userFirst.' '.$rsNews->userLast.'</strong>'; 
							} 
							?>
						</p>
					<?php 
					} 
					?>
				<?php 
				} 
				else
				{ 
				?>
					<h1><?php echo $rsNews->newsTitle; ?></h1>
					<p><strong><?php echo $rsNews->newsTeaser; ?></strong></p>
					<p><?php echo $rsNews->newsContent; ?></p>
					
					<?php 
					if($showAuthor==1||$showDate==1) 
					{ 
					?>
						<p style="text-align:right;">
							<?php 
							if($showAuthor==1) 
							{ 
								echo '<strong>&ndash; '.$rsNews->userFirst.' '.$rsNews->userLast.'</strong>'; 
							} 
							?>
						</p>
					<?php 
					} 
					?>
					<p>&laquo; <a href="<?php echo $cfg['rootdir'].$rsNews->pageID; ?>.html?do=all"><?php echo $ccms['lang']['news']['viewarchive']; ?></a> | <a href="<?php echo $cfg['rootdir'].$rsNews->pageID; ?>.html"><?php 
						echo $db->QuerySingleValue("SELECT `pagetitle` FROM `".$cfg['db_prefix']."pages` WHERE `urlpage` = ".MySQL::SQLValue($rsNews->pageID, MySQL::SQLVALUE_TEXT)); 
					?></a></p>
				<?php 
				} 
				?>
			</div>
			<hr style="clear:both;"/>
		<?php
		}
		if(empty($id) && $db->RowCount() > $rsCfg->showMessage) 
		{ 
		?>
			<hr/><p style="text-align:center;"><a href="<?php echo $cfg['rootdir'].$rsNews->pageID; ?>.html?do=all"><?php echo $ccms['lang']['news']['viewarchive']; ?></a></p>
		<?php 
		}
	}
	
	if($do == "all") 
	{
		for ($i=0; $i<$db->RowCount(); $i++) 
		{ 
			$rsNews = $db->Row();
	    	
			// Filter spaces, non-file characters and account for UTF-8
			$newsTitle = htmlentities(strtolower($rsNews->newsTitle),ENT_COMPAT,'UTF-8');
  			$newsTitle = str_replace($special_chars, "", $newsTitle); 
			$newsTitle = str_replace(' ','-',$newsTitle); 
			?>
	    	
			<h3>&#8594; <a href="<?php echo $cfg['rootdir'].$rsNews->pageID.'/'.$rsNews->newsID.'-'.$newsTitle; ?>.html"><?php echo $rsNews->newsTitle; ?></a></h3>
			<span style="font-size:0.8em;font-style:italic;"><?php echo strftime('%Y-%m-%d',strtotime($rsNews->newsModified));?> &ndash; <?php echo $rsNews->userFirst.' '.$rsNews->userLast; ?></span>
	    	<p><?php echo $rsNews->newsTeaser; ?></p>
		<?php
		}
	}
} 
else 
	echo $ccms['lang']['system']['noresults'];
?>
