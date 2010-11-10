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
	$base = str_replace('\\','/',dirname(dirname(dirname(dirname(dirname(__FILE__))))));
	define('BASE_PATH', $base);
}

// Include general configuration
/*MARKER*/require_once(BASE_PATH . '/lib/sitemap.php');



// security check done ASAP
if(!checkAuth() || empty($_SESSION['rc1']) || empty($_SESSION['rc2'])) 
{ 
	die("No external access to file");
}



$do = getGETparam4IdOrNumber('do');
$status = getGETparam4IdOrNumber('status');
$status_message = getGETparam4DisplayHTML('msg');

// Get permissions
$perm = $db->SelectSingleRowArray($cfg['db_prefix'].'cfgpermissions');
if (!$perm) $db->Kill("INTERNAL ERROR: 1 permission record MUST exist!");

// Get all pages
$pages = $db->QueryArray("SELECT page_id,urlpage,user_ids FROM ".$cfg['db_prefix']."pages", MYSQL_ASSOC);

// Get all users
$users = $db->QueryArray("SELECT userID,userName,userFirst,userLast,userEmail,userLevel FROM ".$cfg['db_prefix']."users", MYSQL_ASSOC);


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title>Page-owners</title>
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
			parent.MochaUI.closeWindow(parent.$('sys-pow_ccms'));
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
	
	<div>
		<h2><span class="ss_sprite ss_group_gear"><?php echo $ccms['lang']['owners']['header']; ?></span></h2>
		<p><?php echo $ccms['lang']['owners']['explain']; ?></p>
		<form action="content-owners.Process.php" method="post" accept-charset="utf-8">
		<table border="0" cellspacing="5" cellpadding="5">
		<tr>
			<th><span class="ss_sprite ss_arrow_down"><?php echo $ccms['lang']['owners']['pages']; ?></span> \ <span class="ss_sprite ss_arrow_right"><?php echo $ccms['lang']['owners']['users']; ?></span></th>
			<?php
			for ($ar1=0; $ar1<count($users); $ar1++) 
			{ 
			?>
				<th class="center span-2" style="border-bottom:solid #AD8CCF 2px;">
					<span class="ss_sprite ss_user_<?php echo ($users[$ar1]['userLevel']>=4?'suit':'green'); ?>"><?php echo $users[$ar1]['userFirst'].' '.substr($users[$ar1]['userLast'],0,1); ?>.</span>
				</th>
			<?php 
			} 
			?>
		</tr>
		<?php 
		for ($i = 0; $i < count($pages); $i++) 
		{ 
			$users_owning_page = explode('||', $pages[$i]['user_ids']);
			
		?>
			<tr>			
			<td class="span-4" style="padding-left:2px;background-color:<?php echo ($i%2!=1?'#EAF3E2;':'#fff;'); ?>border-right:solid #AD8CCF 2px;">
				<span class="ss_sprite ss_page_white_world"><?php echo $pages[$i]['urlpage']; ?>.html</span>
			</td>
				<?php
				for ($ar2=0; $ar2<count($users); $ar2++) 
				{ 
				?>
					<td class="hover center">
						<label for="<?php echo $i.'_'.$ar2;?>"><span>
						<input type="checkbox" name="owner[]" 
						<?php 
						/*
						This code is a security issue of another kind: user ownership settings will OVERLAP for certain users when their IDs are substrings, e.g. user #1 will have everything user #11 has as well.
						
						if(strstr($pages[$i]['user_ids'], $users[$ar2]['userID'])!==false)
						
						Hence the code is replaced with an explode plus array scan. Another way to solve would be padding the rights string with leading and trailing '||' and then
						regex matching against "/||$userid||/".
						
						*/
						if (in_array($users[$ar2]['userID'], $users_owning_page))
						{
							echo 'checked';
						} 
						?> value="<?php echo $users[$ar2]['userID'].'||'.$pages[$i]['page_id'];?>" id="<?php echo $i.'_'.$ar2;?>" />
						</span></label>
					</td>
				<?php 
				} 
				?>
			</tr>
		<?php 
		} 
		?>
		</tr>
		</table>
		<hr/>
		<p class="right"><button type="submit"><span class="ss_sprite ss_disk">Save</span></button><span class="ss_sprite ss_cross"><a href="javascript:;" onClick="confirmation()" title="<?php echo $ccms['lang']['editor']['cancelbtn']; ?>"><?php echo $ccms['lang']['editor']['cancelbtn']; ?></a></span></p>
		</form>
	</div>
