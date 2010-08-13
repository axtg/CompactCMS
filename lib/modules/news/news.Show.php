<?php 

// Load previously defined variables
global $db,$cfg,$ccms;

// Load news preferences
$rsCfg = $db->QuerySingleRow("SELECT * FROM `".$cfg['db_prefix']."cfgnews`");

// Load recordset
$db->Query("SELECT * FROM `".$cfg['db_prefix']."modnews` n LEFT JOIN `".$cfg['db_prefix']."users` u ON n.userID=u.userID WHERE newsPublished>'0' LIMIT ".$rsCfg->showMessage);

// Start switch for news, select all the right details
if($db->HasRecords()) {

	for ($i=0; $i<$db->RowCount(); $i++) { 
	    $rsNews = $db->Row();

?>
<div style="float:right;width:150px;">
	<img src="<?php echo $rsNews->newsIcon; ?>" alt="Story icon" />
</div>

<div style="float:left;width:450px;">
	
	<h2><a href="?newsID=<?php echo $rsNews->newsID; ?>"><?php echo $rsNews->newsTitle; ?></a></h2>
	<p style="line-height:1.6em;"><strong><?php echo $rsNews->newsTeaser; ?></strong></p>
	<?php if($rsCfg->showTeaser==0) { ?><p><?php echo $rsNews->newsContent; ?></p><?php } ?>
	
	<?php if($rsCfg->showAuthor==1||$rsCfg->showDate==1) { ?>
		<p style="text-align:right;">
			<?php if($rsCfg->showAuthor==1) { echo $rsNews->userFirst.' '.$rsNews->userLast; } ?>
			<?php if($rsCfg->showDate==1) { echo ' on '.date('d-m-\'y',strtotime($rsNews->newsModified)); } ?>
		</p>
	<?php } ?>
	
</div>
<hr style="clear:both;"/>
<?php
	}
}


?>