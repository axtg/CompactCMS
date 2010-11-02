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
if(!defined("COMPACTCMS_CODE")) { die('Illegal entry point!'); } /*MARKER*/

// Define default location
if (!defined('BASE_PATH')) die('BASE_PATH not defined!');

// Load basic configuration
/*MARKER*/require_once(BASE_PATH . '/lib/config.inc.php');

function check_session_sidpatch()
{
	global $cfg;
	
	$getid = 'SID'.md5($cfg['authcode'].'x');
	$sesid = session_id();
	// bloody hack for FancyUpload FLASH component which doesn't pass along cookies:
	if (!empty($_GET[$getid]) && empty($sesid))
	{
		$sesid = preg_replace('/[^A-Za-z0-9]/', 'X', $_GET[$getid]);
		session_id($sesid);
	}
	return true;
}

// Start session
check_session_sidpatch();
session_start();

// Load MySQL Class and initiate connection
/*MARKER*/require_once(BASE_PATH . '/lib/class/mysql.class.php');
$db = new MySQL();

// Load generic functions
/*MARKER*/require_once(BASE_PATH . '/lib/includes/common.inc.php');


// LANGUAGE ==

/*
You may specify a 2-char language code OR a 3-char 'locale' code
to set up the proper language files and settings.
*/
function SetUpLanguageAndLocale($language)
{
	global $cfg;
	global $ccms; // <-- this one will be augmented by the (probably) loaded language file(s).
	
	// Translate 2 character code to setlocale compliant code
	//
	// ALSO fix the 2-char language code if it is unknown (security + consistancy measure)!
	switch ($language) 
	{
	default:
	case 'en':
	case 'eng':
		$language = 'en'; $locale = 'eng';break;
	case 'de':
	case 'deu':
		$language = 'de'; $locale = 'deu';break;
	case 'it':
	case 'ita':
		$language = 'it'; $locale = 'ita';break;
	case 'nl':
	case 'nld':
		$language = 'nl'; $locale = 'nld';break;
	case 'ru':
	case 'rus':
		$language = 'ru'; $locale = 'rus';break;
	case 'sv':
	case 'sve':
		$language = 'sv'; $locale = 'sve';break;
	case 'fr':
	case 'fra':
		$language = 'fr'; $locale = 'fra';break;
	case 'es':
	case 'esp':
		$language = 'es'; $locale = 'esp';break;
	case 'pr':
	case 'por':
		$language = 'pr'; $locale = 'por';break;
	case 'tr':
	case 'tur':
		$language = 'tr'; $locale = 'tur';break;
	case 'ch':
	case 'chs':
		$language = 'ch'; $locale = 'chs';break;
	}

	// Either select the specified language file or fall back to English
	$langfile = BASE_PATH . '/lib/languages/'.$language.'.inc.php';
	if(is_file($langfile))
	{
		// only load language files when the current language has not been loaded just before.
		if ($language !== $cfg['language'])
		{
			/*MARKER*/require($langfile);
		}
	} 
	else 
	{
		$language = 'en';
		$locale = 'eng';
		// only load language files when the current language has not been loaded just before.
		if ($language !== $cfg['language'])
		{
			/*MARKER*/require(BASE_PATH . '/lib/languages/en.inc.php');
		}
	}

	// Set local for time, currency, etc
	setlocale(LC_ALL, $locale);

	
	$mce_langfile = BASE_PATH . '/admin/includes/tiny_mce/langs/'.$language.'.js';
	if (is_file($mce_langfile))
	{
		$cfg['tinymce_language'] = $language;
	}
	else
	{
		$cfg['tinymce_language'] = 'en';
	}

	$editarea_langfile = BASE_PATH . '/admin/includes/edit_area/langs/'.$language.'.js';
	if (is_file($editarea_langfile))
	{
		$cfg['editarea_language'] = $language;
	}
	else
	{
		$cfg['editarea_language'] = 'en';
	}
	
	$cfg['language'] = $language;
	$cfg['locale'] = $locale;
	
	return $language;
}

// multilingual support per page through language cfg override:
$language = getGETparam4IdOrNumber('lang');
if (empty($language))
{
	$language = $cfg['language'];
}
// blow away $cfg['language'] to ensure the language file(s) are loaded this time - it's our first anyhow.
unset($cfg['language']);
$language = SetUpLanguageAndLocale($language);



// SECURITY ==
// Include security file only for administration directory
$location = explode("/", $_SERVER['PHP_SELF']);
if(in_array("admin",$location)) 
{
	/*MARKER*/require_once(BASE_PATH . '/admin/includes/security.inc.php');
}


// CheckAuth() has been moved to common.inc.php



// DATABASE ==
// All set! Now this statement will connect to the database
if(!$db->Open($cfg['db_name'], $cfg['db_host'], $cfg['db_user'], $cfg['db_pass'])) 
{
	$db->Kill($ccms['lang']['system']['error_database']);
}

// ENVIRONMENT ==
// Some variables to help this file orientate on its environment
$current = basename(filterParam4FullFilePath($_SERVER['REQUEST_URI']));


// [i_a] $curr_page was identical (enough) to $pagereq before
$pagereq = getGETparam4Filename('page');
$ccms['pagereq'] = $pagereq;

$ccms['printing'] = filterParam4IdOrNumber($_GET['printing']);
if ($ccms['printing'] != 'Y')
{
	$ccms['printing'] = 'N';
}

// This files' current version
$v = "1.4.1";

// TEMPLATES ==
// Read and list the available templates
if ($handle = @opendir(BASE_PATH . '/lib/templates/')) 
{
	$template = array();

	while (false !== ($file = readdir($handle))) 
	{
		if ($file != "." && $file != ".." && strpos($file, ".tpl.html")) 
		{
			// Add the templates to an array for use through-out CCMS, while removing the extension .tpl.html (=9)
			$template_name = substr($file,0,-9);
			if ($template_name != $cfg['default_template'])
			{
				$template[] = substr($file,0,-9);
			}
		}
	}
	closedir($handle);

	// sort the order of the templates; also make sure that the 'default' template is placed at index [0] so that 404 pages and others pick that one.
	sort($template, SORT_LOCALE_STRING);
	if (!empty($cfg['default_template']))
	{
		array_unshift($template, $cfg['default_template']);
	}
	$ccms['template_collection'] = $template;
	
} 
else 
{
	die($ccms['lang']['system']['error_templatedir']);
}

// GENERAL FUNCTIONS ==
// [i_a] moved to common.inc.php


// Fill active module array and load the plugin code
$modules = $db->QueryArray("SELECT * FROM `".$cfg['db_prefix']."modules` WHERE modActive='1'");
foreach($modules as $index => $module)
{
	/*
	Next, we determine where and if to load the module 'augmentation' functionality: this is our new way of doing extensible 'plugins'
	as allowing those to provide a class instance with various methods, we can call into those at various spots where module/plugin
	specific augmentation/alteration is desirable, for example in the case of the 'lightbox' module when constructing the 'breadcrumb' below:
	
	there we would benefit greatly from having access to the module/plugin and allowing it to extend/augment the breadcrumb as it
	desires, so that we can offer a complete breadcrumb, striaght into the album page (when multiple album pages were assigned to a single
	CCMS page) plus printer-friendly versions of those (as 'print' is part of the breadcrumb generation down below.
	
	Of course, simply loading another module PHP file is not enough; we must consider where to place the hooks/callbacks and decide on
	what can be altered/augmented and when/where.
	
	NB: since we require_once each module PHP, we MUST require_once those bits of code BEFORE this moment AND ONLY when we're loading the
		 list of CCMS modules. Right here we should then be able to invoke the first hook: instantiation of the plugin class object for the
		 current page.
	*/
	$modfilename = strtolower($module['modName']);
	$module_path = BASE_PATH . '/lib/modules/'.$modfilename.'/'.$modfilename.'.Augment.php';
	if (@file_exists($module_path))
	{
		$modules[$index]['module_path'] = $module_path;
		
		/*MARKER*/require_once($module_path);
		
		if (!is_object($modules[$modfilename]))
		{
			die('FATAL: module ' . $module['modName'] . ' failed to initialize.');
		}
	}
}
// 'editor' is a special module as it is built-in and doesn't come with a plugin class instance, so is_object($modules['editor'])===false



// only execute the remainder of this file's code if we aren't running a 'minimal' run...
if (!defined('CCMS_PERFORM_MINIMAL_INIT'))
{


// OPERATION MODE ==
// 1) Start normal operation mode (if sitemap.php is not requested directly).
// This will fill all variables based on the requested page, or throw a 403/404 error when applicable.
//
// [i_a] $pagereq = (isset($_GET['page'])&&!empty($_GET['page']))?htmlspecialchars($_GET['page']):null;
if($current != "sitemap.php" && $current != "sitemap.xml" && $pagereq != "sitemap") 
{
	// Parse contents function
	function ccmsContent($page,$published) 
	{
		/*
		Add every item which we have around here and want present in the module page being loaded in here.
		-->
		We want the db connection and the config ($cfg) and content ($ccms) arrays available anywhere inside the require_once()'d content.
		*/
		global $ccms, $cfg, $db, $modules, $v;
		
		$msg = explode(' ::', $ccms['lang']['hints']['published']);
		ob_start();
			// Check for preview variable
			$preview = getGETparam4IdOrNumber('preview');
			// Warning message when page is disabled and authcode is correct
			if ($preview==$cfg['authcode'] && $published=='N') 
			{ 
				echo "<p style=\"clear:both;padding:.8em;margin-bottom:1em;background:#FBE3E4;color:#8a1f11;border:2px solid #FBC2C4;\">".$msg[0].": <strong>".strtolower($ccms['lang']['backend']['disabled'])."</strong></p>";
			}

			// Parse content for active or preview mode
			if($published=='Y' || $preview==$cfg['authcode']) 
			{
				/*MARKER*/require_once(BASE_PATH. "/content/".$page.".php");
			}
			// Parse 403 contents (disabled and no preview token)
			else  // [i_a] superfluous check removed
			{ 
				// [i_a] prevent errors in non-published-marked previews (which would trigger the 403): preview a non-published item, then click on the breadcrumb link to same page and boom!
				/*MARKER*/require_once(BASE_PATH. "/content/403.php");
				//echo file_get_contents(BASE_PATH. "/content/403.php");
			}
			// All parsed function contents to $content variable
			$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}

	// Select the appropriate statement (home page versus specified page)
	if(!empty($pagereq)) {
		if (!$db->Query("SELECT * FROM `".$cfg['db_prefix']."pages` WHERE `urlpage` = " . MySQL::SQLValue($pagereq, MySQL::SQLVALUE_TEXT))) $db->Kill();
	} else {
		if (!$db->Query("SELECT * FROM `".$cfg['db_prefix']."pages` WHERE `urlpage` = 'home'")) $db->Kill();
	}

	// Start switch for pages, select all the right details
	if($db->HasRecords()) 
	{
		$db->MoveFirst();
		$row = $db->Row();

		// Internal reference
		$ccms['published']  = $row->published;
		$ccms['iscoding']   = $row->iscoding;

		// Content variables
		$ccms['language']   = $cfg['language'];
		$ccms['tinymce_language']   = $cfg['language'];
		$ccms['editarea_language']   = $cfg['language'];
		$ccms['sitename']   = $cfg['sitename'];
		$ccms['rootdir']    = (substr($cfg['rootdir'],-1)!=='/'?$cfg['rootdir'].'/':$cfg['rootdir']);
		$ccms['urlpage']    = $row->urlpage;
		$ccms['pagetitle']  = $db->SQLUnfix($row->pagetitle);
		$ccms['subheader']  = $db->SQLUnfix($row->subheader);
		$ccms['desc']       = $db->SQLUnfix($row->description);
		$ccms['keywords']   = $db->SQLUnfix($row->keywords);
		$ccms['title']      = ucfirst($ccms['pagetitle'])." - ".$ccms['sitename']." | ".$ccms['subheader'];
		$ccms['printable']  = $row->printable;
		$ccms['content']    = ccmsContent($ccms['urlpage'],$ccms['published']);

		// TEMPLATING ==
		// Check whether template exists, specify default or throw "no templates" error.
		$ccms['template'] = DetermineTemplateName($row->variant, $ccms['printing']);

		$ccms['module']      = $row->module;
		// create a plugin/module instance tailored to this particular page
		if($row->module != "editor" && is_object($modules[$row->module]) && method_exists($modules[$row->module], 'getInstance')) 
		{
			$ccms['module_instance'] = $modules[$row->module]->getInstance($ccms);
			if (!is_object($ccms['module_instance']))
			{
				die('FATAL: module ' . $row->module . ' failed to initialize for page ' . $row->urlpage);
			}
		}
		
		// BREADCRUMB ==
		// Create breadcrumb for the current page
		if($row->urlpage=="home") 
		{
			$ccms['breadcrumb'] = "<span class=\"breadcrumb\">&raquo; <a href=\"".$cfg['rootdir']."\" title=\"".ucfirst($cfg['sitename'])." Home\">Home</a></span>";
		}
		else {
			// [i_a] these branches didn't include the span which was included by the 'home' if(...) above.
			if($row->sublevel=='0') 
			{
				$ccms['breadcrumb'] = "<span class=\"breadcrumb\">&raquo; <a href=\"".$cfg['rootdir'].$row->urlpage.".html\" title=\"".$db->SQLUnfix($row->subheader)."\">".$db->SQLUnfix($row->pagetitle)."</a></span>";
			}
			else 
			{ 
				// sublevel page
				$subpath = $db->QuerySingleRow("SELECT * FROM `".$cfg['db_prefix']."pages` WHERE `toplevel` = '".$row->toplevel."' AND `sublevel`='0'");
				if (!subpath) $db->Kill();
				$ccms['breadcrumb'] = "<span class=\"breadcrumb\">&raquo; <a href=\"".$cfg['rootdir'].$subpath->urlpage.".html\" title=\"".$db->SQLUnfix($subpath->subheader)."\">".$db->SQLUnfix($subpath->pagetitle)."</a> &raquo; <a href=\"".$cfg['rootdir'].$row->urlpage.".html\" title=\"".$db->SQLUnfix($row->subheader)."\">".$db->SQLUnfix($row->pagetitle)."</a></span>";
			}
		}
	} 
	else 
	{
		// ERROR 404
		// Or if DB query returns zero, show error 404: file does not exist

		$ccms['module']      = 'error';
		$ccms['module_path'] = null;

		$ccms['language']   = $cfg['language'];
		$ccms['tinymce_language']   = $cfg['language'];
		$ccms['editarea_language']   = $cfg['language'];
		$ccms['sitename']   = $cfg['sitename'];
		$ccms['pagetitle']  = $ccms['lang']['system']['error_404title'];
		$ccms['subheader']  = $ccms['lang']['system']['error_404header'];
		$ccms['title']      = ucfirst($ccms['pagetitle'])." - ".$ccms['sitename']." | ".$ccms['subheader'];
		ob_start();
			// [i_a] 
			/*MARKER*/require_once(BASE_PATH. "/content/404.php");
			$ccms['content'] = ob_get_contents();
		ob_end_clean();
		//$ccms['content']    = file_get_contents(BASE_PATH. "/content/404.php");
		$ccms['printable']  = "N";
		$ccms['published']  = "Y";
		// [i_a] fix: close <span> here as well
		$ccms['breadcrumb'] = "<span class=\"breadcrumb\">&raquo; <a href=\"./\" title=\"".ucfirst($cfg['sitename'])." Home\">Home</a> &raquo ".$ccms['lang']['system']['error_404title']."</span>";
		$ccms['iscoding']   = "N";
		$ccms['rootdir']    = (substr($cfg['rootdir'],-1)!=='/'?$cfg['rootdir'].'/':$cfg['rootdir']);
		$ccms['urlpage']    = "404";
		$ccms['desc']       = $ccms['lang']['system']['error_404title'];
		$ccms['keywords']   = "";

		$ccms['template'] = DetermineTemplateName(null, $ccms['printing']);
	}


	// OPERATION MODE ==
	// 2) Start site structure generation to a default maximum of MENU_TARGET_COUNT menus
	// Use the various menu item variables to get a dynamic structured list (ul). Current item marked with class="current".

	// more flexible approach than before; fewer queries (1 instead of 6..10) to boot.
	// flexibility in the sense that when user has assigned same top/sub numbers to multiple entries, this version will not b0rk
	// but dump the items in alphabetic order instead.
	// Also, when sub menu items with a top# that has no entry itself, is found, such an item will be assigned a 'dummy' top node.
	$menu_in_set = '1';
	for($i = 2; $i <= MENU_TARGET_COUNT; $i++) 
	{
		$menu_in_set .= ',' . $i;
	}
	$db->Query("SELECT * FROM `".$cfg['db_prefix']."pages` WHERE `published`='Y' AND `menu_id` IN ($menu_in_set) ORDER BY `menu_id` ASC, `toplevel` ASC, `sublevel` ASC, `page_id` ASC");

	if($db->HasRecords()) 
	{
		$current_menuID = 0;
		$current_top = 0;
		$current_structure = null;
		$top_idx = 0;
		$sub_idx = 0;
		$sub_done = false;
		$dummy_top_written = false;
		
		$db->MoveFirst();

		/*
		When a submenu item is located which doesn't have a proper topmenu item set up as well, a dummy top is written.
		
		To simplify the flow within the loop, the loop is executed /twice/ for such elements: the first time through,
		the top item will be written (as if it existed in the database), the next time through the subitem itself is
		generated.
		
		The same re-cycling mode is used to switch from one menu to the next (note the 'continue;' in there).
		*/
		while ($dummy_top_written || !$db->EndOfSeek()) 
		{
			if (!$dummy_top_written)
			{
				$row = $db->Row();
			}
			$dummy_top_written = false;

			// whether we have found the (expectedly) accompanying toplevel menu item.
			$top_done = ($row->sublevel != 0 && $row->toplevel == $current_top && $row->menu_id == $current_menuID);
			
			if ($row->menu_id != $current_menuID)
			{
				if ($current_top > 0)
				{
					// terminate generation of previous menu
					if ($sub_done)
					{
						$ccms[$current_structure] .= "</li></ul>\n";
					}
					$ccms[$current_structure] .= "</li></ul>\n";
				}
				
				// forward to next menu...
				$current_menuID = $row->menu_id;
				$current_top = 0;
				$current_structure = 'structure' . $current_menuID;
				$top_idx = 0;
				$sub_idx = 0;
				$sub_done = false;
				
				// Start this menu root item: UL
				$ccms[$current_structure] = '<ul>';
			
				// prevent loading the next record on the next round through the loop:
				$dummy_top_written = true;
				continue;
			}
			else if ($row->toplevel != $current_top || $row->sublevel == 0)
			{
				// terminate generation of previous submenu
				if ($current_top > 0)
				{
					if ($sub_done)
					{
						$ccms[$current_structure] .= "</li></ul>\n";
					}
					$ccms[$current_structure] .= "</li>\n";
				}
				
				$current_top = $row->toplevel;
				$top_idx++;
				$sub_idx = 0;
				$sub_done = false;
				
				if (!$top_done && $row->sublevel != 0)
				{
					// write a dummy top
					$dummy_top_written = true;
				}
			}
			else if ($row->sublevel != 0)
			{
				if ($sub_done)
				{
					$ccms[$current_structure] .= "</li>\n";
				}
				else
				{
					$ccms[$current_structure] .= "\n<ul class=\"sublevel\">\n";
				}
				$sub_idx++;
				$sub_done = true;
			}
			
			// Specify special link attributes if applicable
			$current_class = '';
			$current_extra = '';
			$current_link = '';
			if ($row->urlpage == $pagereq || (empty($pagereq) && $row->urlpage == "home"))
			{
				// 'home' has a pagereq=='', but we still want to see the 'current' class for that one.
				// (The original code didn't do this, BTW!)
				$current_class = 'current';
			}
			
			$menu_item_class = '';
			if ($dummy_top_written)
			{
				$current_class = '';
				$menu_item_class = 'menu_item_dummy';
			}
			else if ($row->islink != "Y")
			{
				$current_link = '#';
				$menu_item_class = 'menu_item_nolink';
			}
			else if (regexUrl($db->SQLUnfix($row->description)))
			{
				$msg = explode(' ::', $db->SQLUnfix($row->description));
				$current_link = $msg[0];
				$current_extra = $msg[1];
				$current_class = 'to_external_url';
				$menu_item_class = 'menu_item_extref';
			}
			else if ($row->urlpage == "home")
			{
				$current_link = $cfg['rootdir'];
				$menu_item_class = 'menu_item_home';
			}
			else 
			{
				$current_link = $cfg['rootdir'] . $row->urlpage . '.html';
			}
			
			if (!empty($current_extra))
			{
				$current_extra = "\n<br/>\n" . $current_extra;
			}
			

			// What text to show for the links
			$link_text = ucfirst($db->SQLUnfix($row->pagetitle));
			$link_title = ucfirst($db->SQLUnfix($row->subheader));

			$current_link_classes = trim($current_class . ' ' . $menu_item_class);
			if (!empty($current_link_classes))
			{
				$current_link_classes = 'class="' . $current_link_classes . '"';
			}
			$menu_item_text = '<a '.$current_link_classes.' href="'.$current_link.'" title="'.$link_title.'">'.$link_text.'</a>'.$current_extra;
			
			$menu_top_class = 'menu_item' . ($top_idx % 2);
			$menu_sub_class = 'menu_item' . ($sub_idx % 2);
			
			if ($dummy_top_written)
			{
				$menu_item_text = '<span ' . $current_link_classes . '>-</span>';
				$ccms[$current_structure] .= '<li class="' . /* $current_class . ' ' . */ $menu_top_class . ' ' . $menu_item_class . '">' . $menu_item_text;
			}
			else if ($row->sublevel != 0)
			{
				$ccms[$current_structure] .= '<li class="' . $current_class . ' ' . $menu_sub_class . ' ' . $menu_item_class . '">' . $menu_item_text;
			}
			else
			{
				$ccms[$current_structure] .= '<li class="' . $current_class . ' ' . $menu_top_class . ' ' . $menu_item_class . '">' . $menu_item_text;
			}
		}
		
		// now that we're done in the loop, terminate the last menu:
		if ($current_top > 0)
		{
			// terminate generation of previous menu
			if ($sub_done)
			{
				$ccms[$current_structure] .= '</li></ul>';
			}
			$ccms[$current_structure] .= '</li></ul>';
		}
	}
}

// OPERATION MODE ==
// 3) Start dynamic sitemap creation used by spiders and various webmaster tools.
// e.g. You can use this function to submit a dynamic sitemap to Google Webmaster Tools.
else /* if($current == "sitemap.php" || $current == "sitemap.xml") */   // [i_a] if() removed so the GET URL index.php?page=sitemap doesn't slip through the cracks.
{
	$dir = $cfg['rootdir'];   // [i_a] the original substr($_SERVER[]) var would fail when called with this req URL: index.php?page=sitemap

	// Start generating sitemap
	header ("content-type: text/xml");

	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
	?>
	<urlset
		xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
			http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
	<?php
	// Select all published pages
	if (!$db->Query("SELECT `urlpage`,`description`,`islink` FROM `".$cfg['db_prefix']."pages` WHERE `published` = 'Y'")) $db->Kill();

	$db->MoveFirst();
	while (!$db->EndOfSeek()) {
		$row = $db->Row();

		// Do not include external links in sitemap
		if(!regexUrl($db->SQLUnfix($row->description))) {
			echo "<url>\n";
				if($row->urlpage == "home") {
					echo "<loc>http://".$_SERVER['SERVER_NAME']."".$dir."</loc>\n";
					echo "<priority>0.80</priority>\n";
				} else if($row->islink == 'N') {
					// [i_a] put pages which are not accessible through the menus (and thus the home/index page, at a higher scan priority.
					echo "<loc>http://".$_SERVER['SERVER_NAME']."".$dir."".$row->urlpage.".html</loc>\n";
					echo "<priority>0.70</priority>\n";
				} else {
					echo "<loc>http://".$_SERVER['SERVER_NAME']."".$dir."".$row->urlpage.".html</loc>\n";
					echo "<priority>0.50</priority>\n";
				}
			echo "<changefreq>weekly</changefreq>\n";
			echo "</url>\n";
		}
	}
	echo "</urlset>";
	exit(); // [i_a] exit now; no need nor want to run the XML through the template engine
}


} // if (!defined('CCMS_PERFORM_MINIMAL_INIT'))


?>