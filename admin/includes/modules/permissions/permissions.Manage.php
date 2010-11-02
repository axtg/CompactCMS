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
	$base = str_replace('\\','/',dirname(dirname(dirname(dirname(dirname(__FILE__))))));
	define('BASE_PATH', $base);
}

// Include general configuration
/*MARKER*/require_once(BASE_PATH . '/lib/sitemap.php');



$do	= getGETparam4IdOrNumber('do');
$status = getGETparam4IdOrNumber('status');

// Get permissions
$perm = $db->QuerySingleRowArray("SELECT * FROM ".$cfg['db_prefix']."cfgpermissions");



if(checkAuth() && isset($_SESSION['rc1']) && !empty($_SESSION['rc2'])) 
{ 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>Permissions module</title>
	<link rel="stylesheet" type="text/css" href="../../../img/styles/base.css,liquid.css,layout.css,sprite.css" />

	<!-- Confirm close -->
	<script type="text/javascript">
function confirmation()
{
	var answer=confirm('<?php echo $ccms['lang']['editor']['confirmclose']; ?>');
	if(answer)
	{
		try
		{
			parent.MochaUI.closeWindow(parent.$('sys-perm_ccms'));
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
		if(isset($_GET['msg'])) 
		{ 
			echo '<span class="ss_sprite ss_confirm">'.htmlspecialchars(rawurldecode($_GET['msg'])).'</span><br/><span class="ss_sprite ss_exclamation">'.$ccms['lang']['backend']['must_refresh'].'</span>'; 
		} 
		?>
	</div>

	<h2><?php echo $ccms['lang']['permission']['header']; ?></h2>
	<?php 

	// (!) Only administrators can change these values
	if($_SESSION['ccms_userLevel']>=4) 
	{
	?>
		<p><?php echo $ccms['lang']['permission']['explain']; ?></p>
		<form action="permissions.Process.php" method="post" accept-charset="utf-8">
			<table border="0" cellspacing="5" cellpadding="5">
				<tr>
					<th class="span-4"><em><?php echo $ccms['lang']['permission']['target']; ?></em></th>
					<th class="span-4 center"><?php echo $ccms['lang']['backend']['disabled']; ?></th>
					<th class="span-4 center"><?php echo $ccms['lang']['permission']['level1']; ?></th>
					<th class="span-4 center"><?php echo $ccms['lang']['permission']['level2']; ?></th>
					<th class="span-4 center"><?php echo $ccms['lang']['permission']['level3']; ?></th>
					<th class="span-4 center"><?php echo $ccms['lang']['permission']['level4']; ?></th>
				</tr>
				<?php
				$i = 0;
				$rsCfg = $db->QuerySingleRow("SELECT * FROM `".$cfg['db_prefix']."cfgpermissions`");

				// Get column names and their comments from database
				$columns = $db->GetColumnComments($cfg['db_prefix']."cfgpermissions");
				foreach ($columns as $columnName => $comments) 
				{
					if($i%2 != 1) 
					{
						echo '<tr style="background-color: #E6F2D9;">';
					} 
					else 
					{ 
						echo '<tr>';
					}  
					?>
						<th><?php echo (!empty($comments) ? "<abbr title=\"$comments\">$columnName</abbr>" : $columnName); ?></th>
						<td class="center">
							<input type="radio" name="<?php echo $columnName; ?>" <?php echo ($rsCfg->$columnName==0?"checked":null); ?> value="0" id="<?php echo $columnName; ?>">
						</td>
						<td class="center">
							<input type="radio" name="<?php echo $columnName; ?>" <?php echo ($rsCfg->$columnName==1?"checked":null); ?> value="1" id="<?php echo $columnName; ?>">
						</td>
						<td class="center">
							<input type="radio" name="<?php echo $columnName; ?>" <?php echo ($rsCfg->$columnName==2?"checked":null); ?> value="2" id="<?php echo $columnName; ?>">
						</td>
						<td class="center">
							<input type="radio" name="<?php echo $columnName; ?>" <?php echo ($rsCfg->$columnName==3?"checked":null); ?> value="3" id="<?php echo $columnName; ?>">
						</td>
						<td class="center">
							<input type="radio" name="<?php echo $columnName; ?>" <?php echo ($rsCfg->$columnName==4?"checked":null); ?> value="4" id="<?php echo $columnName; ?>">
						</td>
					</tr>
					<?php 
					$i++;
				} 
				?>
			</table>
			<hr />
			<p class="right"><button type="submit"><span class="ss_sprite ss_disk"><?php echo $ccms['lang']['forms']['savebutton'];?></span></button> <span class="ss_sprite ss_cross"><a href="#" onClick="confirmation();" title="<?php echo $ccms['lang']['editor']['cancelbtn']; ?>"><?php echo $ccms['lang']['editor']['cancelbtn']; ?></a></span></p>
		</form>
	<?php
	} 
	else 
		die($ccms['lang']['auth']['featnotallowed']);
	?>
</div>
</body>
</html>

<?php
} 
else 
	die("No external access to file"); 
?>
