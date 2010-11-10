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




$do	= getGETparam4IdOrNumber('do');

// Open recordset for specified user
$newsID = getGETparam4Number('newsID');
$pageID = getGETparam4IdOrNumber('pageID');

if($newsID != null) 
{
	$news = $db->QuerySingleRow("SELECT * FROM `".$cfg['db_prefix']."modnews` m LEFT JOIN `".$cfg['db_prefix']."users` u ON m.userID=u.userID WHERE newsID = " . MySQL::SQLValue($newsID, MySQL::SQLVALUE_NUMBER) . " AND pageID=" . MySQL::SQLValue($pageID, MySQL::SQLVALUE_TEXT));
	if (!$news) $db->Kill();
}

// Get permissions
$perm = $db->SelectSingleRowArray($cfg['db_prefix'].'cfgpermissions');
if (!$perm) $db->Kill("INTERNAL ERROR: 1 permission record MUST exist!");


if (!(checkAuth() && $perm['manageModNews']>0 && $_SESSION['ccms_userLevel'] >= $perm['manageModNews'])) 
{
	die("No external access to file");
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>News module</title>
	
	<!-- File uploader styles -->
	<link rel="stylesheet" media="all" type="text/css" href="../../../admin/includes/fancyupload/Assets/manager.css" />

	<!-- TinyMCE JS -->
	<script type="text/javascript" src="../../../admin/includes/tiny_mce/tiny_mce_gzip.js"></script>	
	
	<!-- Mootools library -->
	<script type="text/javascript" src="../../includes/js/mootools.js" charset="utf-8"></script>
	
	<!-- File uploader JS -->
	<script type="text/javascript" src="../../../admin/includes/fancyupload/Source/FileManager.js"></script>
	<script type="text/javascript" src="../../../admin/includes/fancyupload/Language/Language.en.js"></script>
	<script type="text/javascript" src="../../../admin/includes/fancyupload/Source/Additions.js"></script>
	<script type="text/javascript" src="../../../admin/includes/fancyupload/Source/Uploader/Fx.ProgressBar.js"></script>
	<script type="text/javascript" src="../../../admin/includes/fancyupload/Source/Uploader/Swiff.Uploader.js"></script>
	<script type="text/javascript" src="../../../admin/includes/fancyupload/Source/Uploader.js"></script>
	<script type="text/javascript">
FileManager.TinyMCE=function(options)
	{
		return function(field,url,type,win)
			{
				var manager=new FileManager($extend(
					{
						onComplete:function(path)
						{
							if(!win.document)
								return;
							win.document.getElementById(field).value='<?php echo $cfg['rootdir']; ?>'+path;
							if(win.ImageDialog)
								win.ImageDialog.showPreviewImage('<?php echo $cfg['rootdir']; ?>'+path,1);
							this.container.destroy();
						}
					},
					options(type)));
				manager.dragZIndex=400002;
				manager.SwiffZIndex=400003;
				manager.el.setStyle('zIndex',400001);
				manager.overlay.el.setStyle('zIndex',400000);
				document.id(manager.tips).setStyle('zIndex',400010);
				manager.show();
				return manager;
			};
	};
FileManager.implement('SwiffZIndex',400003);
var Dialog=new Class(
	{
		Extends:Dialog,
		initialize:function(text,options)
		{
			this.parent(text,options);
			this.el.setStyle('zIndex',400010);
			this.overlay.el.setStyle('zIndex',400009);
		}
	});
	</script>
		
	<link rel="stylesheet" type="text/css" href="../../../admin/img/styles/base.css,liquid.css,layout.css,sprite.css" />
	
	<!-- TinyMCE -->
	<script type="text/javascript" src="../../../admin/includes/tiny_mce/tiny_mce_gzip.js"></script>	
	
	<script type="text/javascript">
tinyMCE_GZ.init(
	{
		plugins:'safari,table,advlink,advimage,media,inlinepopups,print,fullscreen,paste,searchreplace,visualchars,spellchecker,tinyautosave',
		themes:'advanced',
		<?php echo "languages: '".$cfg['tinymce_language']."',"; ?>
		disk_cache:true,
		debug:false
	});
	</script>
		
	<script type="text/javascript">
tinyMCE.init(
	{
		mode:"exact",
		elements:"newsContent",
		theme:"advanced",
		<?php echo 'language:"'.$cfg['tinymce_language'].'",'; ?>
		skin:"o2k7",
		skin_variant:"silver",
		plugins:"safari,table,advlink,advimage,media,inlinepopups,print,fullscreen,paste,searchreplace,visualchars,spellchecker,tinyautosave",
		theme_advanced_buttons1:"fullscreen,tinyautosave,print,formatselect,fontselect,fontsizeselect,|,justifyleft,justifycenter,justifyright,justifyfull,|,sub,sup,|,spellchecker,link,unlink,anchor,hr,image,media,|,charmap,code",
		theme_advanced_buttons2:"undo,redo,cleanup,|,bold,italic,underline,strikethrough,|,forecolor,backcolor,removeformat,|,cut,copy,paste,replace,|,bullist,numlist,outdent,indent,|,tablecontrols",
		theme_advanced_buttons3:"",
		theme_advanced_toolbar_location:"top",
		theme_advanced_toolbar_align:"left",
		theme_advanced_statusbar_location:"bottom",
		dialog_type:"modal",
		paste_auto_cleanup_on_paste:true,
		theme_advanced_resizing:true,
		relative_urls:true,
		convert_urls:false,
		remove_script_host:true,
		document_base_url:"../../",
		<?php 
		if($cfg['iframe'] === true) 
		{ 
		?> 
		extended_valid_elements:"iframe[align<bottom?left?middle?right?top|class|frameborder|height|id|longdesc|marginheight|marginwidth|name|scrolling<auto?no?yes|src|style|title|width]",
		<?php 
		} 
		?>
		spellchecker_languages: "+English=en,Dutch=nl,German=de,Spanish=es,French=fr,Italian=it,Russian=ru",
		file_browser_callback:FileManager.TinyMCE(function(type)
			{
				return {
						url:type=='image'?'../../../admin/includes/fancyupload/selectImage.php':'../../../admin/includes/fancyupload/manager.php',
						assetBasePath:'../../../admin/includes/fancyupload/Assets',
						language:'en',
						selectable:true,
						uploadAuthData:{session:'ccms_userLevel'}
					};
			})
	});
	</script>
	
	<!-- Check form and post -->
	<script type="text/javascript" charset="utf-8">
window.addEvent('domready',function()
	{
		new FormValidator($('newsForm'),
			{
				onFormValidate:function(passed,form,event)
				{
					if(passed)
						form.submit();
				}
			});
	});
	</script>
	
	<!-- Confirm close -->
	<script type="text/javascript">
function confirmation()
{
	var answer=confirm('<?php echo $ccms['lang']['editor']['confirmclose']; ?>');
	if(answer)
	{
		try
		{
			parent.window.history.go(-1);
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
		<div id="status">
			<!-- spinner -->
		</div>
			
		<h2><?php echo $ccms['lang']['news']['writenews']; ?></h2>
		<div class="span-24">
			<form action="./news.Process.php?action=add-edit-news" id="newsForm" method="post" accept-charset="utf-8">
				<div class="span-7">
					<label for="newsTitle"><?php echo $ccms['lang']['news']['title']; ?></label><input type="text" class="minLength:3 text" name="newsTitle" value="<?php echo (isset($news)?$news->newsTitle:null);?>" id="newsTitle"/>
				</div>
				<div class="span-7">
					<label for="newsAuthor"><?php echo $ccms['lang']['news']['author']; ?></label>
					<select name="newsAuthor" class="required text" id="newsAuthor" size="1">
						<?php 
							$db->Query("SELECT * FROM `".$cfg['db_prefix']."users`");
							while (! $db->EndOfSeek()) 
							{
		    						$user = $db->Row(); 
								?>
								<option value="<?php echo $user->userID;?>" <?php echo (isset($news)&&$user->userID==$news->userID?'selected="selected"':null); ?>><?php echo $user->userFirst.' '.$user->userLast; ?></option>
							<?php 
							} 
							?>
					</select>
				</div>
				<div class="span-4">
					<label for="newsModified"><?php echo $ccms['lang']['news']['date']; ?></label><input type="text" class="required text" style="width:120px;" name="newsModified" value="<?php echo (isset($news)?date('Y-m-d G:i',strtotime($news->newsModified)):date('Y-m-d G:i'));?>" id="newsModified">
				</div>
				<div class="span-2">
					<label for="newsPublished"><?php echo $ccms['lang']['news']['published']; ?></label><input type="checkbox" name="newsPublished" <?php echo (isset($news)&&$news->newsPublished?"checked":null); ?>  value="1" id="newsPublished" />
				</div>
				<label class="clear" for="newsTeaser"><?php echo $ccms['lang']['news']['teaser']; ?></label>
				<textarea name="newsTeaser" id="newsTeaser" style="height:50px;width:98%;" class="minLength:3 text" rows="4" cols="40"><?php echo (isset($news)?$news->newsTeaser:null);?></textarea>
				
				<label for="newsContent"><?php echo $ccms['lang']['news']['contents']; ?></label>
				<textarea name="newsContent" id="newsContent" style="height:290px;width:100%;color:#000;" class="text" rows="8" cols="40"><?php 
					echo (isset($news) ? $news->newsContent : null);
				?></textarea>
				<hr class="space"/>
				<p>
					<input type="hidden" name="newsID" value="<?php echo $newsID; ?>" id="newsID" />
					<input type="hidden" name="pageID" value="<?php echo $pageID; ?>" id="pageID" />
					<button type="submit" name="submitNews" value="<?php echo $newsID; ?>">
						<?php 
						if(empty($newsID)) 
						{ 
						?>
							<span class="ss_sprite ss_newspaper_add"><?php echo $ccms['lang']['forms']['createbutton']; ?></span></button>
						<?php 
						} 
						else 
						{ 
						?>
							<span class="ss_sprite ss_newspaper_go"><?php echo $ccms['lang']['forms']['modifybutton']; ?></span></button>
						<?php 
						} 
						?>
					<span class="ss_sprite ss_cross"><a href="javascript:;" onClick="confirmation()" title="<?php echo $ccms['lang']['editor']['cancelbtn']; ?>"><?php echo $ccms['lang']['editor']['cancelbtn']; ?></a></span>
				</p>
			</form>
		</div>
	</div>
</body>
</html>
