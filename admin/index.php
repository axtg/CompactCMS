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
We're rendering the admin page here, no need to load the user/viewer page content in sitemap.php, etc. 
*/
define('CCMS_PERFORM_MINIMAL_INIT', true);


// Compress all output and coding
header('Content-type: text/html; charset=UTF-8');

// Define default location
if (!defined('BASE_PATH'))
{
	$base = str_replace('\\','/',dirname(dirname(__FILE__)));
	define('BASE_PATH', $base);
}

// Include general configuration
// /*MARKER*/require_once(BASE_PATH . '/lib/sitemap.php');   [i_a] loaded by process.inc.php anyway
/*MARKER*/require_once(BASE_PATH . '/admin/includes/process.inc.php');


/* make darn sure only authenticated users can get past this point in the code */
if(empty($_SESSION['ccms_userID']) || empty($_SESSION['ccms_userName']) || !CheckAuth()) 
{
	// this situation should've caught inside process.inc.php above! This is just a safety measure here.
	die($ccms['lang']['auth']['featnotallowed']); 
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $cfg['language']; ?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=8" />
	<title>CompactCMS Administration</title>
	<meta name="description" content="CompactCMS administration. CompactCMS is a light-weight and SEO friendly Content Management System for developers and novice programmers alike." />
	<link rel="icon" type="image/ico" href="../media/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="img/styles/base.css,layout.css,editor.css,sprite.css" />
	<!--[if IE]>
		<link rel="stylesheet" type="text/css" href="img/styles/ie.css" />
		<script type="text/javascript" src="../lib/includes/js/excanvas.js" charset="utf-8"></script>
	<![endif]-->
	<script type="text/javascript" src="../lib/includes/js/mootools.js,common.js,mocha.js" charset="utf-8"></script>
	<script type="text/javascript" charset="utf-8">
window.addEvent('domready',function()
	{
		if ($('addForm')) /* [i_a] extra check due to permissions cutting out certain parts of the page */
		{
			new FormValidator($('addForm')); 
		}
	});
</script>
</head>

<body id="desktop">	
<div class="container">

	<?php // Top bar including status block ?>
	<div id="logo" class="sprite logo span-5 colborder">
		<h1>CompactCMS <?php echo $ccms['lang']['backend']['administration']; ?></h1>
		<p><span class="ss_sprite ss_world"><?php echo $cfg['sitename']; ?></span></p>
	</div>
	<div id="notify" class="sprite notify span-12">
		<p><?php 
		if($cfg['protect'])
		{
			?><span class="ss_sprite ss_door_open right"><a href="./includes/security.inc.php?do=logout">Log-out</a></span><?php 
		} 
		?><a id="clockLink" style="text-decoration:none;" class="clock"><span class="ss_sprite ss_clock">&#160;</span></a></p>
		<div id="notify_res">&#160;
			<?php 
			if(!empty($version_recent) && !empty($v) && $cfg['version']===true) 
			{ 
			?>
				<br/><?php echo $ccms['lang']['backend']['currentversion']." ".$v; ?>. <?php echo $ccms['lang']['backend']['mostrecent']." ".$version_recent; ?>.<br/>
				<br/>
				<div style="font-weight: bold; text-align: center;"> <?php echo $ccms['lang']['backend']['versionstatus']." ".$version; ?></div>
			<?php 
			} 
			else 
				echo "<br/>".$ccms['lang']['system']['error_versioninfo']; 
			?>
		</div>
	</div>
	<div id="advanced" class="prepend-1 span-5 last">
		<h2><span class="ss_sprite ss_user_red">Hi</span> <?php echo $_SESSION['ccms_userFirst']; ?></h2>
		<div id="advanced_res">
			<ul>
				<?php 
				if($_SESSION['ccms_userLevel']>=4) 
				{ 
				?>
					<li><span class="ss_sprite ss_group_key"><a id="sys-perm" href="./includes/modules/permissions/permissions.Manage.php" rel="<?php echo $ccms['lang']['backend']['permissions']; ?>" class="tabs"><?php echo $ccms['lang']['backend']['permissions']; ?></a></span></li>
				<?php 
				} 
				if($perm['manageOwners']>0 && $_SESSION['ccms_userLevel']>=$perm['manageOwners']) 
				{ 
				?>
					<li><span class="ss_sprite ss_folder_user "><a id="sys-pow" href="./includes/modules/content-owners/content-owners.Manage.php" rel="<?php echo $ccms['lang']['backend']['contentowners']; ?>" class="tabs"><?php echo $ccms['lang']['backend']['contentowners']; ?></a></span></li>
				<?php 
				} 
				if($perm['manageTemplate']>0 && $_SESSION['ccms_userLevel']>=$perm['manageTemplate'])  // [i_a] template dialog would still appear when turned off in permissions --> error message in that window anyway.
				{ 
				?>
					<li><span class="ss_sprite ss_color_swatch"><a id="sys-tmp" href="./includes/modules/template-editor/backend.php" rel="<?php echo $ccms['lang']['backend']['templateeditor']; ?>" class="tabs"><?php echo $ccms['lang']['backend']['templateeditor']; ?></a></span></li>
				<?php 
				} 
				// if($perm['manageUsers']>0)    -- [i_a] we'll always be able to 'manage' ourselves; at least the users.manage page can cope with that scenario - plus it's in line with the rest of the admin behaviour IMHO
				{ 
				?>
					<li><span class="ss_sprite ss_group"><a id="sys-usr" href="./includes/modules/user-management/backend.php" rel="<?php echo $ccms['lang']['backend']['usermanagement']; ?>" class="tabs"><?php echo $ccms['lang']['backend']['usermanagement']; ?></a></span></li>
				<?php 
				} 
				if($perm['manageModBackup']>0) 
				{ 
				?>
					<li><span class="ss_sprite ss_drive_disk"><a id="sys-bck" href="./includes/modules/backup-restore/backend.php" rel="<?php echo $ccms['lang']['backup']['createhd'];?>" class="tabs"><?php echo $ccms['lang']['backup']['createhd'];?></a></span></li>
				<?php 
				} 
				?>
			</ul>
		</div>
	</div>

	<?php 
	
	// Start main management section 
	
	// Create new page 
	if($_SESSION['ccms_userLevel']>=$perm['managePages']) 
	{ 
	?>
	<div id="createnew" class="span-9">
	<fieldset>
		<legend><span class="ss_sprite ss_add">&#160;</span><a class="toggle" rel="form_wrapper" href="#"><?php echo $ccms['lang']['backend']['createpage']; ?></a></legend>
		<div id="form_wrapper">
		<form method="post" id="addForm" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<p><?php echo $ccms['lang']['backend']['createtip']; ?></p>
			<div id="fields">
				<label for="urlpage"><?php echo $ccms['lang']['forms']['filename']; ?></label> 
				<input class="required minLength:3 text" type="text" id="urlpage" name="urlpage" />
				<span class="ss_sprite ss_help" title="<?php echo $ccms['lang']['hints']['filename']; ?>">&#160;</span><br/>
				
				<label for="f_pt"><?php echo $ccms['lang']['forms']['pagetitle']; ?></label>
				<input class="required minLength:3 text" type="text" id="f_pt" name="pagetitle" />
				<span class="ss_sprite ss_help" title="<?php echo $ccms['lang']['hints']['pagetitle']; ?>">&#160;</span><br/>
				
				<label for="f_sh"><?php echo $ccms['lang']['forms']['subheader']; ?></label> 
				<input class="required minLength:3 text" type="text" id="f_sh" name="subheader" />
				<span class="ss_sprite ss_help" title="<?php echo $ccms['lang']['hints']['subheader']; ?>">&#160;</span><br/>
				
				<label for="f_de"><?php echo $ccms['lang']['forms']['description']; ?></label>
				<textarea class="required minLength:3" id="f_de" name="description" rows="4" cols="30"></textarea>
				<span class="ss_sprite ss_help" title="<?php echo $ccms['lang']['hints']['description']; ?>">&#160;</span><br/>
				
				<?php 
				// Modules
				if(count($modules)>0) 
				{ 
				?>
				<label for="f_mod"><?php echo $ccms['lang']['forms']['module']; ?></label>
				<select class="text" name="module" id="f_mod" size="1">
					<option value="editor"><?php echo $ccms['lang']['forms']['contentitem']; ?></option>
					<optgroup label="<?php echo $ccms['lang']['forms']['additions']; ?>">
						<?php //
						for ($i=0; $i<count($modules); $i++) 
						{ 
						?>
							<option value="<?php echo $modules[$i]['modName'];?>"><?php echo $modules[$i]['modTitle']; ?></option>
						<?php 
						} 
						?>
					</optgroup>
				</select>&#160;<span class="ss_sprite ss_help" title="<?php echo $ccms['lang']['hints']['module']; ?>">&#160;</span><br/>
				<?php 
				} 
				?>
				
				<div id="editor-options">
					<label><?php echo $ccms['lang']['forms']['printable']; ?>?</label> 
					<?php echo $ccms['lang']['backend']['yes']; ?>: <input type="radio" id="f_pr1" checked="checked" name="printable" value="Y" />  
					<?php echo $ccms['lang']['backend']['no']; ?>: <input type="radio" id="f_pr2" name="printable" value="N" />
					<span class="ss_sprite ss_help" title="<?php echo $ccms['lang']['hints']['printable']; ?>">&#160;</span><br/>
					<label style="clear:both; margin-top: 6px;"><?php echo $ccms['lang']['forms']['published']; ?>?</label> 
					<?php echo $ccms['lang']['backend']['yes']; ?>: <input type="radio" id="f_pu1" checked="checked" name="published" value="Y" />  
					<?php echo $ccms['lang']['backend']['no']; ?>: <input type="radio" style="margin-top: 10px;" id="f_pu2" name="published" value="N" />
					<span class="ss_sprite ss_help" title="<?php echo $ccms['lang']['hints']['published']; ?>">&#160;</span><br/>
					<label style="clear:both; margin-top: 3px;"><?php echo $ccms['lang']['forms']['iscoding']; ?>?</label> 
					<?php echo $ccms['lang']['backend']['yes']; ?>: <input type="radio" id="f_cod" name="iscoding" value="Y" />  
					<?php echo $ccms['lang']['backend']['no']; ?>: <input type="radio" style="margin-top: 10px;" id="f_co2" checked="checked" name="iscoding" value="N" />
					<span class="ss_sprite ss_help" title="<?php echo $ccms['lang']['hints']['iscoding']; ?>">&#160;</span><br/>
				</div>
				<input type="hidden" name="form" value="create" />
				<div class="right"><button type="submit" id="addbtn" name="submit"><span class="ss_sprite ss_wand"><?php echo $ccms['lang']['forms']['createbutton']; ?></span></button></div>
			</div>
		</form>	
		</div>
	</fieldset>
	</div>
	<?php 
	} 
	else 
	{
	?>
<!--
	<div id="createnew" class="span-9">
	<fieldset>
		<legend><span class="ss_sprite ss_add">&#160;</span><a class="toggle" rel="form_wrapper" href="#"><?php echo $ccms['lang']['backend']['createpage']; ?></a></legend>
		<div id="form_wrapper">
		<p><?php echo $ccms['lang']['auth']['featnotallowed']; ?></p>
		<form method="post" id="addForm" action="#">
			<div id="fields">
				<div id="editor-options">
				</div>
			</div>
		</form>	
		</div>
	</fieldset>
	</div>
-->
	<?php
	}

		
	// Manage menu depths & languages 
	
	if($_SESSION['ccms_userLevel']>=$perm['manageMenu']) 
	{ 
	?>
	<div id="menudepth" class="span-16">
	<fieldset>
		<legend><span class="ss_sprite ss_text_list_bullets">&#160;</span> <a class="toggle" rel="menu_wrapper" href="#"><?php echo $ccms['lang']['backend']['managemenu']; ?></a></legend>
		<div id="menu_wrapper">
		<p><?php echo $ccms['lang']['backend']['ordertip']; ?></p>
		<form method="post" id="menuForm" action="<?php echo $_SERVER['PHP_SELF'] ?>">
			<table class="span-15" id="table_menu">
			<tr>
				<th class="span-2"><?php echo $ccms['lang']['backend']['menutitle']; ?> <span class="ss_sprite ss_help" title="<?php echo $ccms['lang']['hints']['menuid']; ?>"></span></th>
				<th class="span-2"><?php echo $ccms['lang']['backend']['template']; ?> <span class="ss_sprite ss_help" title="<?php echo $ccms['lang']['hints']['template']; ?>"></span></th>
				<th class="span-2"><?php echo $ccms['lang']['backend']['toplevel']; ?> <span class="ss_sprite ss_help" title="<?php echo $ccms['lang']['hints']['toplevel']; ?>"></span></th>
				<th class="span-2"><?php echo $ccms['lang']['backend']['sublevel']; ?> <span class="ss_sprite ss_help" title="<?php echo $ccms['lang']['hints']['sublevel']; ?>"></span></th>
				<th class="span-1-1"><?php echo $ccms['lang']['backend']['linktitle']; ?> <span class="ss_sprite ss_help" title="<?php echo $ccms['lang']['hints']['activelink']; ?>"></span></th>
				<th class="span-4"><?php echo $ccms['lang']['forms']['pagetitle']; ?></th>
			</tr>
			</table>
			<div id="menuFields">
				<!--spinner-->
			</div>
			<hr class="space"/>

			<div class="right">
				<input type="hidden" name="form" value="menuorder" />
				<button type="submit" name="submit"><span class="ss_sprite ss_disk"><?php echo $ccms['lang']['editor']['savebtn']; ?></span></button>
			</div>

		</form>	
		</div>
	</fieldset>
	</div>
	<?php 
	} 
	else 
	{
	?>
<!--
	<div id="menudepth" class="span-16">
	<fieldset>
		<legend><span class="ss_sprite ss_text_list_bullets">&#160;</span> <a class="toggle" rel="menu_wrapper" href="#"><?php echo $ccms['lang']['backend']['managemenu']; ?></a></legend>
		<div id="menu_wrapper">
		<p><?php echo $ccms['lang']['auth']['featnotallowed']; ?></p>
		<form method="post" id="menuForm" action="#">
		</form>	
		</div>
	</fieldset>
	</div>
-->
	<?php
	}
	

		
	// Manage current files 

	function gen_span4pagelist_filterheader($name, $title)
	{
		global $ccms;
		
		if (!empty($name) && !empty($_SESSION[$name]))
		{
			echo '<span class="sprite livefilter livefilter_active" rel="' . $name . '" title="' . ucfirst($ccms['lang']['forms']['add_remove']) . ' ' . $title . ' -- ' . $ccms['lang']['forms']['filter_showing'] . ': \'' . htmlspecialchars($_SESSION[$name]) . '\'">&#160;</span>';
		}
		else
		{
			echo '<span class="sprite livefilter livefilter_add" rel="' . $name . '" title="' . ucfirst($ccms['lang']['forms']['add_remove']) . ' ' . $title . '">&#160;</span>';
		}
	}

	?>
	<div id="manage" class="span-25">
	<fieldset>
		<legend><span class="ss_sprite ss_folder_database">&#160;</span><a class="toggle" rel="filelist_wrapper" href="#"><?php echo $ccms['lang']['backend']['managefiles']; ?></a></legend>
		<div id="filelist_wrapper">
		<p><?php echo $ccms['lang']['backend']['currentfiles']; ?></p>
		<form action="index.php" id="delete">
		<table id="table_manage">
			<tr>
				<th style="padding-left: 5px;" class="span-1"></th>
				<th class="span-3"><?php gen_span4pagelist_filterheader('filter_pages_name', 'name filter'); echo $ccms['lang']['forms']['filename']; ?> <span class="ss_sprite ss_help" title="<?php echo $ccms['lang']['hints']['filename']; ?>">&#160;</span></th>
				<th class="span-4"><?php gen_span4pagelist_filterheader('filter_pages_title', 'title filter');  echo $ccms['lang']['forms']['pagetitle']; ?> <span class="ss_sprite ss_help" title="<?php echo $ccms['lang']['hints']['pagetitle']; ?>">&#160;</span></th>
				<th class="span-6"><?php gen_span4pagelist_filterheader('filter_pages_subheader', 'subheader filter'); echo $ccms['lang']['forms']['subheader']; ?> <span class="ss_sprite ss_help" title="<?php echo $ccms['lang']['hints']['subheader']; ?>">&#160;</span></th>
				<th class="center span-2-1"><?php echo $ccms['lang']['forms']['printable']; ?> <span class="ss_sprite ss_help" title="<?php echo $ccms['lang']['hints']['printable']; ?>">&#160;</span></th>
				<th class="center span-2">
					<?php 
					if($_SESSION['ccms_userLevel']>=$perm['manageActivity']) 
					{ 
						echo $ccms['lang']['forms']['published']; 
						?> 
						<span class="ss_sprite ss_help" title="<?php echo $ccms['lang']['hints']['published']; ?>">&#160;</span>
					<?php 
					} 
					?>
				</th>
				<th class="center span-2">
					<?php
					if($_SESSION['ccms_userLevel']>=$perm['manageVarCoding']) 
					{ 
						echo $ccms['lang']['forms']['iscoding']; 
						?> 
						<span class="ss_sprite ss_help" title="<?php echo $ccms['lang']['hints']['iscoding']; ?>">&#160;</span>
					<?php 
					} 
					?>
				</th>
				<th class="span-5" style="text-align: right;">&#160;</th>
			</tr>
		</table>
		<div id="dyn_list">
			<?php echo $ccms['lang']['system']['error_misconfig']; ?> <a href="http://community.compactcms.nl/forum/"><strong>See forum</strong></a>.
			<!--spinner-->
		</div>
		<table width="100%">
			<tr>
				<?php 
				if($_SESSION['ccms_userLevel']>=$perm['managePages']) 
				{ 
				?>
					<th class="span-11" style="text-align: left;">
						<input type="hidden" name="form" value="delete" />
						<input type="hidden" id="ad_msg01" value="<?php echo $ccms['lang']['backend']['confirmdelete']; ?>" />
						<button type="submit" name="btn_del"><span class="ss_sprite ss_bin_empty"><?php echo $ccms['lang']['backend']['delete']; ?></span></button>
					</th>
				<?php 
				} 
				else 
					echo '<th class="span-11">&#160;</th>'; 
				?>
				<th class="span-2"><div style="background-color: #CDE6B3; text-align: center;"><span class="ss_sprite ss_accept"><?php echo $ccms['lang']['backend']['active']; ?></span></div></th>
				<th class="span-2"><div style="background-color: #F2D9DE; text-align: center;"><span class="ss_sprite ss_stop"><?php echo $ccms['lang']['backend']['disabled']; ?></span></div></th>
			</tr>
		</table>
		</form>
		</div>
	</fieldset>
	</div>

	<?php // Footer block ?>
	<div id="footer" class="span-25">
		<div class="prepend-11 span-11 colborder">&copy; 2008 - <?php echo date('Y'); ?> <a href="http://www.compactcms.nl">CompactCMS.nl</a>. <?php echo $ccms['lang']['system']['message_rights']; ?>.<br/><em><?php echo $ccms['lang']['backend']['gethelp']; ?></em></div>
		<div class="span-1 last"><a href="http://twitter.com/compactcms" class="sprite twittlogo" title="Follow at Twitter"></a></div>
		<div style="margin-top: 10px;" class="prepend-13 span-12">
			<span class="sprite ff" title="<?php echo $ccms['lang']['system']['message_compatible']; ?> Firefox"></span>
			<span class="sprite ie" title="<?php echo $ccms['lang']['system']['message_compatible']; ?> Internet Explorer 7+"></span>
			<span class="sprite opera" title="<?php echo $ccms['lang']['system']['message_compatible']; ?> Opera"></span>
			<span class="sprite chrome" title="<?php echo $ccms['lang']['system']['message_compatible']; ?> Chrome"></span>
			<span class="sprite safari" title="<?php echo $ccms['lang']['system']['message_compatible']; ?> Safari"></span>
		</div>
	</div>
</div>
	
	<?php // Dock block ?>
	<div id="dockWrapper">
		<div id="dock">
			<div id="dockPlacement"></div>
			<div id="dockAutoHide"></div>
			<div id="dockSort"><div id="dockClear" class="clear"></div></div>
		</div>
	</div>
	
</body>
</html>