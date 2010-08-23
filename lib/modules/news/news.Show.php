<?php 
// Load previously defined variables
global $db,$cfg,$ccms;

// Load news preferences
$rsCfg = $db->QuerySingleRow("SELECT * FROM `".$cfg['db_prefix']."cfgnews`");

// Limited characters
$special_chars = array("#","$","%","@","^","&","*","!","~","‘","\"","’","'","=","?","/","[","]","(",")","|","<",">",";","\\",",");

// Do actions for overview
if(!isset($_GET['id'])||empty($_GET['id'])) {
	// Load recordset for all news
	$db->Query("SELECT * FROM `".$cfg['db_prefix']."modnews` n LEFT JOIN `".$cfg['db_prefix']."users` u ON n.userID=u.userID WHERE newsPublished>'0' ORDER BY newsModified DESC");
} 
// Do actions for specific news
elseif(isset($_GET['id'])&&!empty($_GET['id'])) {
	// Define requested news item
	$newsID = explode("-",$_GET['id']);
	
	// Load recordset for newsID
	$db->Query("SELECT * FROM `".$cfg['db_prefix']."modnews` n LEFT JOIN `".$cfg['db_prefix']."users` u ON n.userID=u.userID WHERE newsID=".$newsID[0]." AND newsPublished>'0'");
}

?>
<!-- additional style and code -->
<link rel="stylesheet" href="<?php echo $cfg['rootdir'];?>lib/modules/news/resources/style.css" type="text/css" media="screen" title="lightbox" charset="utf-8" />

<!-- lay-out -->

<?php 
// Start switch for news, select all the right details
if($db->HasRecords()) {

	if(!isset($_GET['do'])) {
		$max = ($rsCfg->showMessage>$db->RowCount()?$db->RowCount():$rsCfg->showMessage);
		for ($i=0; $i<$max; $i++) { 
		    $rsNews = $db->Row();
?>
<div>
	<?php if($rsCfg->showDate==1) { ?>
		<strong class="date"><?php echo strftime('%B',strtotime($rsNews->newsModified)); ?><span><?php echo date('j',strtotime($rsNews->newsModified)); ?></span></strong>
	<?php } ?>
	
	<?php if(!isset($_GET['id'])||empty($_GET['id'])) { 
		// Filter spaces, non-file characters and account for UTF-8
		$newsTitle = @htmlentities(strtolower($rsNews->newsTitle),ENT_COMPAT,'UTF-8');
  		$newsTitle = str_replace($special_chars, "", $newsTitle); 
		$newsTitle = str_replace(' ','-',$newsTitle);
		
		?>
		<h2><a href="<?php echo $cfg['rootdir'].$_GET['page'].'/'.$rsNews->newsID.'-'.$newsTitle; ?>.html"><?php echo $rsNews->newsTitle; ?></a></h2>
		<p><strong><?php echo $rsNews->newsTeaser; ?></strong></p>
		<?php if($rsCfg->showTeaser==0) { ?><p><?php echo $rsNews->newsContent; ?></p><?php } ?>
		
		<?php if($rsCfg->showAuthor==1||$rsCfg->showDate==1) { ?>
			<p style="text-align:right;">
				<?php if($rsCfg->showAuthor==1) { echo '<strong>&ndash; '.$rsNews->userFirst.' '.$rsNews->userLast.'</strong>'; } ?>
			</p>
		<?php } ?>
	<?php } elseif(isset($_GET['id'])&&!empty($_GET['id'])) { ?>
		<h1><?php echo $rsNews->newsTitle; ?></h1>
		<p><strong><?php echo $rsNews->newsTeaser; ?></strong></p>
		<p><?php echo $rsNews->newsContent; ?></p>
		
		<?php if($rsCfg->showAuthor==1||$rsCfg->showDate==1) { ?>
		<p style="text-align:right;">
			<?php if($rsCfg->showAuthor==1) { echo '<strong>&ndash; '.$rsNews->userFirst.' '.$rsNews->userLast.'</strong>'; } ?>
		</p>
		<?php } ?>
		<p>&laquo; <a href="<?php echo $cfg['rootdir'].$_GET['page']; ?>.html?do=all"><?php echo $ccms['lang']['news']['viewarchive']; ?></a> | <a href="<?php echo $cfg['rootdir'].$_GET['page']; ?>.html"><?php echo $db->QuerySingleValue("SELECT `pagetitle` FROM `".$cfg['db_prefix']."pages` WHERE `urlpage` = '".$_GET['page']."'"); ?></a></p>
	<?php } ?>
	
</div>
<hr style="clear:both;"/>
<?php
		}
		if(!isset($_GET['id'])||empty($_GET['id'])&&$db->RowCount()>$rsCfg->showMessage) { ?>
			<hr/><p style="text-align:center;"><a href="?do=all"><?php echo $ccms['lang']['news']['viewarchive']; ?></a></p>
		<?php 
		}
	}
	if(isset($_GET['do'])&&$_GET['do']=="all") {
		for ($i=0; $i<$db->RowCount(); $i++) { 
	    	$rsNews = $db->Row();
	    	
	    	// Filter spaces, non-file characters and account for UTF-8
			$newsTitle = @htmlentities(strtolower($rsNews->newsTitle),ENT_COMPAT,'UTF-8');
  			$newsTitle = str_replace($special_chars, "", $newsTitle); 
			$newsTitle = str_replace(' ','-',$newsTitle); ?>
	    	
			<h3>&#8594; <a href="<?php echo $cfg['rootdir'].$_GET['page'].'/'.$rsNews->newsID.'-'.$newsTitle; ?>.html"><?php echo $rsNews->newsTitle; ?></a></h3>
			<span style="font-size:0.8em;font-style:italic;"><?php echo strftime('%Y-%m-%d',strtotime($rsNews->newsModified));?> &ndash; <?php echo $rsNews->userFirst.' '.$rsNews->userLast; ?></span>
	    	<p><?php echo $rsNews->newsTeaser; ?></p>
		<?php
		}
	}
}
?>