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

// Start session
session_start();

// Define default location
if (!defined('BASE_PATH')) die('BASE_PATH not defined!');

// Load basic configuration
/*MARKER*/require_once(BASE_PATH . '/lib/config.inc.php');

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
if(!$db->Open($cfg['db_name'], $cfg['db_host'], $cfg['db_user'], $cfg['db_pass'])) {
	$db->Kill($ccms['lang']['system']['error_database']);
}

// ENVIRONMENT ==
// Some variables to help this file orientate on its environment
$current	= basename(htmlspecialchars($_SERVER['REQUEST_URI']));
$curr_page	= isset($_GET['page'])?mysql_real_escape_string($_GET['page']):null;

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




// only execute the remainder of this file's code if we aren't running a 'minimal' run...
if (!defined('CCMS_PERFORM_MINIMAL_INIT'))
{


// OPERATION MODE ==
// 1) Start normal operation mode (if sitemap.php is not requested directly).
// This will fill all variables based on the requested page, or throw a 403/404 error when applicable.
$pagereq = (isset($_GET['page'])&&!empty($_GET['page']))?htmlspecialchars($_GET['page']):null;
if($current != "sitemap.php" && $current != "sitemap.xml" && $pagereq != "sitemap") {
	
	// Parse contents function
	function ccmsContent($page,$published) {
		global $ccms, $cfg;
		$msg = explode(' ::', $ccms['lang']['hints']['published']); 
		ob_start();
			// Check for preview variable
			$preview = (isset($_GET['preview'])?$_GET['preview']:null);
			// Warning message when page is disabled and authcode is correct
			echo ($preview==$cfg['authcode']&&$ccms['published']=='N')?"<p style=\"clear:both;padding:.8em;margin-bottom:1em;background:#FBE3E4;color:#8a1f11;border:2px solid #FBC2C4;\">".$msg['0'].": <strong>".strtolower($ccms['lang']['backend']['disabled'])."</strong></p>":null;
			
			// Parse content for active or preview mode
			if($published=='Y' || $preview==$cfg['authcode']) {
				/*MARKER*/require_once(BASE_PATH. "/content/".$page.".php");
			}
			// Parse 403 contents (disabled and no preview token)
			elseif($published=='N') {
				echo file_get_contents(BASE_PATH. "/content/403.php");
			}
			// All parsed function contents to $content variable
			$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
	
	// Select the appropriate statement (home page versus specified page)
	if(!empty($pagereq)) {
		if (!$db->Query("SELECT * FROM `".$cfg['db_prefix']."pages` WHERE `urlpage` = '$curr_page'")) $db->Kill();
	} else {
		if (!$db->Query("SELECT * FROM `".$cfg['db_prefix']."pages` WHERE `urlpage` = 'home'")) $db->Kill();
	}

	// Start switch for pages, select all the right details
	if($db->HasRecords()) {
		
	$db->MoveFirst();
    $row = $db->Row();
	
    // Internal reference
	$ccms['published']	= $row->published;
	$ccms['iscoding']	= $row->iscoding;
    
	// Content variables
    $ccms['language']	= $cfg['language'];
	$ccms['sitename'] 	= $cfg['sitename'];
	$ccms['rootdir']	= (substr($cfg['rootdir'],-1)!=='/'?$cfg['rootdir'].'/':$cfg['rootdir']);
	$ccms['urlpage']	= $row->urlpage;
	$ccms['pagetitle'] 	= $row->pagetitle;
	$ccms['subheader'] 	= $row->subheader;
	$ccms['desc']		= $row->description;
	$ccms['keywords']	= $row->keywords;
	$ccms['title'] 		= ucfirst($ccms['pagetitle'])." - ".$ccms['sitename']." | ".$ccms['subheader'];
	$ccms['printable']	= $row->printable;
	$ccms['content']	= ccmsContent($ccms['urlpage'],$ccms['published']);
	
	// TEMPLATING ==
	// Set the template variable for current page
	$templatefile = BASE_PATH . '/lib/templates/'.$row->variant.'.tpl.html';
	
	// Check whether template exists, specify default or throw "no templates" error.
	if(file_exists($templatefile)) {
		$ccms['template'] = $row->variant;
	} elseif(count($template)>"0") {
		$ccms['template'] = $template['0'];
	} elseif(count($template)=="0") {
		die($ccms['lang']['system']['error_notemplate']);
	}
	
	// BREADCRUMB ==
	// Set default breadcrumb
	$ccms['breadcrumb'] = null;
	
	// Create breadcrumb for the current page
	if($row->urlpage=="home") {
		$ccms['breadcrumb'] = "<span class=\"breadcrumb\">&raquo; <a href=\"".$cfg['rootdir']."\" title=\"".ucfirst($cfg['sitename'])." Home\">Home</a>";
	}
	if($row->urlpage!="home" && $row->sublevel=='0') {
		$ccms['breadcrumb'] .= " &raquo; <a href=\"".$cfg['rootdir'].$row->urlpage.".html\" title=\"".$row->subheader."\">".$row->pagetitle."</a>";
	}
	if($row->sublevel>'0') {
		if (!$db->Query("SELECT * FROM `".$cfg['db_prefix']."pages` WHERE `toplevel` = '".$row->toplevel."' AND `sublevel`='0'")) $db->Kill();
		$subpath = $db->Row();
		$ccms['breadcrumb'] .= " &raquo; <a href=\"".$cfg['rootdir'].$subpath->urlpage.".html\" title=\"".$subpath->subheader."\">".$subpath->pagetitle."</a> &raquo; <a href=\"".$cfg['rootdir'].$row->urlpage.".html\" title=\"".$row->subheader."\">".$row->pagetitle."</a>";
	}
	$ccms['breadcrumb']	.= "</span>";
	
	// ERROR 404
	// Or if DB query returns zero, show error 404: file does not exist
	} else {
		$ccms['sitename'] 	= $cfg['sitename'];
		$ccms['pagetitle']	= $ccms['lang']['system']['error_404title'];
		$ccms['subheader']	= $ccms['lang']['system']['error_404header'];
		$ccms['title']		= ucfirst($ccms['pagetitle'])." - ".$ccms['sitename']." | ".$ccms['subheader'];
		$ccms['content']	= file_get_contents(BASE_PATH. "/content/404.php");
		$ccms['printable']	= "N";
		$ccms['published']	= "Y";
		$ccms['breadcrumb']	= "<span class=\"breadcrumb\">&raquo; <a href=\"./\" title=\"".ucfirst($cfg['sitename'])." Home\">Home</a> &raquo ".$ccms['lang']['system']['error_404title'];
		
		if(count($template)>"0") {
			$ccms['template'] = $template['0'];
		} elseif(count($template)=="0") {
			die($ccms['lang']['system']['error_notemplate']);
		}
	}
	// OPERATION MODE ==
	// 2) Start site structure generation to a default maximum of five ($i <= '5').
	// Use the various menu item variables to get a dynamic structured list (ul). Current item marked with class="current".
	
	// Start menu generation
	for($i=1; $i<=5; $i++) { 
		
		// Count total active menu items in database
		$ct = $db->QuerySingleRow("SELECT COUNT(`page_id`) AS num, MIN(`toplevel`) AS mtl FROM `".$cfg['db_prefix']."pages` WHERE `published`='Y' AND `menu_id`='$i' GROUP BY `menu_id`");
		
		// Loop through menu generator for all items
		if(!empty($ct->num)) {
			// Start menu parent item
			$ccms['structure'.$i] = "<ul>";
			
			for ($index=1; $index<=$ct->num; $index++) { 
				// Select all items for given menu and process hierarchy
				$db->Query("SELECT * FROM `".$cfg['db_prefix']."pages` WHERE `published`='Y' AND `menu_id`='$i' AND `toplevel`='".$ct->mtl."' ORDER BY `toplevel` ASC, `sublevel` ASC");
				
				// Next toplevel
				$ct->mtl++;
				
				// Check whether the recordset is not empty
				if($db->HasRecords()) {
					
					// Set the pointer to the first row
					$db->MoveFirst();
					
					// Go through all rows found for the current toplevel
					while (!$db->EndOfSeek()) {
		    			$row = $db->Row();
		    			
		    			// Specify special link attributes if applicable
		    			$current_class 	= ($row->urlpage==$curr_page)?'class="current"':null;
		    			$current_link	= ($row->islink=="N"?'#':null);
		    			$current_link 	= (empty($current_link)&&regexUrl($row->description)?$row->description:$current_link);
		    			$current_link	= (empty($current_link)&&$row->urlpage=="home"?$cfg['rootdir']:$current_link);
		    			$current_link	= (empty($current_link)?$cfg['rootdir'].$row->urlpage.'.html':$current_link);
		    			
		    			// What text to show for the links
		    			$link_text		= ucfirst($row->pagetitle);
						
		    			// Specifying the position of the current item in the menu
		    			if($row->sublevel==0 && $db->RowCount()==1) {
		    				$ccms['structure'.$i] .= '<li><a '.$current_class.' href="'.$current_link.'" title="'.ucfirst($row->subheader).'">'.$link_text.'</a></li>';
		    			}
		    			if($row->sublevel==0 && $db->RowCount()>1) {
				    		$ccms['structure'.$i] .= '<li><a '.$current_class.' href="'.$current_link.'" title="'.ucfirst($row->subheader).'">'.$link_text.'</a>';
				    		$ccms['structure'.$i] .= '<ul class="sublevel">';
				    	}
				    	if($row->sublevel>0 && $db->SeekPosition()!=$db->RowCount()) {
				    		$ccms['structure'.$i] .= '<li><a '.$current_class.' href="'.$current_link.'" title="'.ucfirst($row->subheader).'">'.$link_text.'</a></li>';
				    	}
				    	if($row->sublevel>0 && $db->SeekPosition()==$db->RowCount()) {
				    		$ccms['structure'.$i] .= '<li><a '.$current_class.' href="'.$current_link.'" title="'.ucfirst($row->subheader).'">'.$link_text.'</a></li>';
				    		$ccms['structure'.$i] .= '</ul></li>';
				    	}
					}
				}
			}
			$ccms['structure'.$i] .= "</ul>";
		}
	} 	
}

// OPERATION MODE ==
// 3) Start dynamic sitemap creation used by spiders and various webmaster tools.
// e.g. You can use this function to submit a dynamic sitemap to Google Webmaster Tools.
elseif($current == "sitemap.php" || $current == "sitemap.xml") {
	$dir = substr($_SERVER['SCRIPT_NAME'],0,-15);
	
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
	if (!$db->Query("SELECT `urlpage`,`description` FROM `".$cfg['db_prefix']."pages` WHERE `published` = 'Y'")) $db->Kill();

	$db->MoveFirst();
	while (!$db->EndOfSeek()) {
		$row = $db->Row();
		
		// Do not include external links in sitemap
		if(!regexUrl($row->description)) {
			echo "<url>\n";
				if($row->urlpage == "home") { 
					echo "<loc>http://".$_SERVER['SERVER_NAME']."".$dir."</loc>\n";
					echo "<priority>0.80</priority>\n";
				} else {
					echo "<loc>http://".$_SERVER['SERVER_NAME']."".$dir."".$row->urlpage.".html</loc>\n";
					echo "<priority>0.50</priority>\n";
				}
			echo "<changefreq>weekly</changefreq>\n";
			echo "</url>\n";
		}
	}
	echo "</urlset>";
}


} // if (!defined('CCMS_PERFORM_MINIMAL_INIT'))


?>