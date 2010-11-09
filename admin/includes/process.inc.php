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
We're only processing form requests / actions here, no need to load the page content in sitemap.php, etc. 
*/
define('CCMS_PERFORM_MINIMAL_INIT', true);


// Compress all output and coding
header('Content-type: text/html; charset=UTF-8');

// Define default location
if (!defined('BASE_PATH'))
{
	$base = str_replace('\\','/',dirname(dirname(dirname(__FILE__))));
	define('BASE_PATH', $base);
}

// Include general configuration
/*MARKER*/require_once(BASE_PATH . '/lib/sitemap.php');

// Some security functions


/* make darn sure only authenticated users can get past this point in the code */
if(empty($_SESSION['ccms_userID']) || empty($_SESSION['ccms_userName']) || !CheckAuth()) 
{
	// this situation should've caught inside sitemap.php-->security.inc.php above! This is just a safety measure here.
	die($ccms['lang']['auth']['featnotallowed']); 
}


if(!isset($_SESSION['rc1']) || !isset($_SESSION['rc2'])) 
{
	$_SESSION['rc1'] = mt_rand('12345', '98765'); 
	$_SESSION['rc2'] = mt_rand('1234', '9876');
}

// Prevent PHP warning by setting default (null) values
$do_action = getGETparam4IdOrNumber('action');

// Get permissions
$perm = $db->QuerySingleRowArray("SELECT * FROM ".$cfg['db_prefix']."cfgpermissions");

// Fill active module array
// $modules = $db->QueryArray("SELECT * FROM `".$cfg['db_prefix']."modules` WHERE modActive='1'");    // [i_a] already collected in sitemap.php 



$do_update_or_livefilter = ($do_action == "update" && $_SERVER['REQUEST_METHOD'] != "POST");


$page_selectquery_restriction = '';

// Open recordset for sites' pages
$db->Query("SELECT * FROM `".$cfg['db_prefix']."pages` " . $page_selectquery_restriction . " ORDER BY `published`, `menu_id`, `toplevel`, `sublevel` ASC");

// Check whether the recordset is not empty
if($db->HasRecords()) 
{
	// Set the target for PHP processing
	$target_form = getPOSTparam4IdOrNumber('form');

	/**
	 *
	 * Render the dynamic list with files
	 *
	 */
	if ($do_update_or_livefilter && checkAuth()) 
	{
		$i = 0;
		
		echo '<table cellpadding="0" cellspacing="0">';
		
		// Get previously opened DB stream
		$db->MoveFirst();
		while (!$db->EndOfSeek()) 
		{
			// Fill $row with values
			$row = $db->Row();
				
			// Check whether current user is owner, or no owners at all
			$owner = @explode('||', $row->user_ids);
			if($row->user_ids==0||$perm['manageOwners']==0||$_SESSION['ccms_userLevel']>=4||in_array($_SESSION['ccms_userID'], $owner)) 
			{
				// Determine file specific variables
				if($row->module=="editor") 
				{
					$module = './includes/process.inc.php';
				} 
				else 
				{
					$module = '../lib/modules/'.$row->module.'/'.$row->module.'.Manage.php';
				}
				
				// Define $isEven for alternate table coloring
				if($i%2 != 1) 
				{
					if($row->published === "N") 
					{
						echo '<tr style="background-color: #F2D9DE;">';
					} 
					else 
					{
						echo '<tr style="background-color: #E6F2D9;">';
					}
				} 
				else 
				{ 
					if($row->published === "N") 
					{
						echo '<tr style="background-color: #EBC6CD;">';
					} 
					else 
					{
						echo '<tr>'; 
					}
				} 
				?>
				<td style="padding-left:2px;" class="span-1">
				<?php 
				if($_SESSION['ccms_userLevel']<$perm['managePages'] || $row->urlpage == "home" || in_array($row->urlpage, $cfg['restrict'])) 
				{ 
				?>
					<span class="ss_sprite ss_bullet_red" title="<?php echo $ccms['lang']['auth']['featnotallowed']; ?>"></span>
				<?php 
				} 
				else 
				{ 
				?>
					<input type="checkbox" id="page_id_<?php echo $i;?>" name="page_id[]" value="<?php echo $_SESSION['rc1'].$_SESSION['rc2'].$row->page_id; ?>" />
				<?php 
				} 
				?>
				</td>
				<td class="span-3">
					<label for="page_id_<?php echo $i;?>"><em><abbr title="<?php echo $row->urlpage; ?>.html"><?php echo substr($row->urlpage,0,25); ?></abbr></em></label>
				</td>
				<td class="span-4">
					<span id="<?php echo $row->page_id; ?>" class="sprite-hover liveedit" rel="pagetitle"><?php echo $row->pagetitle; ?></span>
				</td>
				<td class="span-6">
					<span id="<?php echo $row->page_id; ?>" class="sprite-hover liveedit" rel="subheader"><?php echo $row->subheader; ?></span>
				</td>
				<td class="span-2" style="text-align: center;">
					<a href="#" id="printable-<?php echo $row->page_id; ?>" rel="<?php echo $row->printable; ?>" class="sprite editinplace" title="<?php echo $ccms['lang']['backend']['changevalue']; ?>"><?php 
						if($row->printable == "Y")
						{ 
							echo $ccms['lang']['backend']['yes']; 
						} 
						else 
						{
							echo $ccms['lang']['backend']['no']; 
						}
						?></a>
				</td>
				<td class="span-2" style="text-align: center;">
					<?php 
					if($_SESSION['ccms_userLevel']>=$perm['manageActivity']) 
					{ 
					?>
						<a href="#" id="published-<?php echo $row->page_id; ?>" rel="<?php echo $row->published; ?>" class="sprite editinplace" title="<?php echo $ccms['lang']['backend']['changevalue']; ?>"><?php 
							if($row->published == "Y") 
							{ 
								echo $ccms['lang']['backend']['yes']; 
							} 
							else 
							{
								echo $ccms['lang']['backend']['no']; 
							}
							?></a>
					<?php 
					} 
					?>
				</td>
				<td class="span-2" style="text-align: center;">
					<?php 
					if($_SESSION['ccms_userLevel']>=$perm['manageVarCoding']) 
					{ 
					?>
						<?php 
						if($row->module=="editor") 
						{ 
						?>
							<a href="#" id="iscoding-<?php echo $row->page_id; ?>" rel="<?php echo $row->iscoding; ?>" class="sprite editinplace" title="<?php echo $ccms['lang']['backend']['changevalue']; ?>"><?php if($row->iscoding == "Y") { echo "<span style=\"color:#8F0000;font-weight:bold;\">".$ccms['lang']['backend']['yes']."</span>"; } else echo $ccms['lang']['backend']['no']; ?></a>
						<?php 
						} 
						else 
							echo "&ndash;"; 
						?>
					<?php 
					} 
					?>
				</td>
				<?php 
				// Check for restrictions
				if(!in_array($row->urlpage, $cfg['restrict'])||!in_array($row->page_id, $owners)) 
				{ 
				?>
					<td class="span-5" style="text-align: right;">
						<a id="<?php echo $row->urlpage;?>" href="<?php echo $module; ?>?file=<?php echo $row->urlpage; ?>&amp;action=edit&amp;restrict=<?php echo $row->iscoding; ?>&amp;active=<?php echo $row->published;?>" rel="Edit <?php echo $row->urlpage.'.html';?>" class="tabs sprite edit"><?php echo $ccms['lang']['backend']['editpage']; ?></a> | <a href="../<?php echo ($row->urlpage!="home")?$row->urlpage.'.html?preview='.$cfg['authcode']:'?preview='.$cfg['authcode']; ?>" class="external"><?php echo $ccms['lang']['backend']['previewpage']; ?></a>&#160;
					</td>
				<?php 
				} 
				else 
				{ 
				?>
					<td class=\"span-3\" style=\"text-align: right;\">
						<div style="margin: 2px;"><span class="ss_sprite ss_exclamation" title="<?php echo $ccms['lang']['backend']['restrictpage']; ?>"></span> <?php echo $ccms['lang']['backend']['restrictpage'];?></div>
					</td>
				<?php 
				} 
				?>
				</tr>
				
				<?php 
				if($i%2 != 1) 
				{
					if($row->published === "N") 
					{
						echo "<tr style=\"background-color: #F2D9DE;\">";
					} 
					else     
					{
						echo "<tr style=\"background-color: #E6F2D9;\">";
					}
				} 
				else 
				{ 
					if($row->published === "N") 
					{
						echo "<tr style=\"background-color: #EBC6CD;\">";
					} 
					else 
					{
						echo "<tr>"; 
					}
				} 
				?>
				<td>&#160;</td>
				<td colspan="5"><strong><?php echo $ccms['lang']['forms']['description']; ?></strong>: <span id="<?php echo $row->page_id; ?>" class="sprite-hover liveedit" rel="description"><?php echo ucfirst($row->description) ;?></span></td>
				<td colspan="2" style="text-align: right; padding-right:5px;">
					<?php 
					if($row->module=="editor" && !empty($row->toplevel)) 
					{ 
					?>
						<em><?php echo $ccms['lang']['backend']['inmenu']; ?>:</em> <?php echo "<strong>".strtolower($ccms['lang']['menu'][$row->menu_id])."</strong> | <em>".$ccms['lang']['backend']['item']." </em> <strong>".$row->toplevel.":".$row->sublevel."</strong>"; ?></td></tr>
					<?php 
					} 
					elseif($row->module!="editor") 
					{ 
						echo "<span class=\"ss_sprite ss_information\"><strong>".ucfirst($row->module)."</strong></span> ".strtolower($ccms['lang']['forms']['module']);
					} 
					else 
					{
						echo "<em>".$ccms['lang']['backend']['notinmenu']."</em>"; 
					}
					?>
				</td>
				</tr>
			<?php
			} 
			// If user is not a page owner 
			else 
			{
				$i++;
			} 
			
			// Regular move on	
			$i++;
		} 
		?>
		</table>
	<?php 
	}

		
	/**
	 *
	 * Render the entire menu list
	 *
	 */
	if($do_action == "renderlist" && $_SERVER['REQUEST_METHOD'] != "POST" && checkAuth()) 
	{
		if(isset($_SESSION['ccms_userLevel']) && $_SESSION['ccms_userLevel'] >= $perm['manageMenu']) 
		{
			echo '<table class="span-15" cellpadding="0" cellspacing="0">';
			
			$i = 0;
			// Get previously opened DB stream
			$db->MoveFirst();
			while (!$db->EndOfSeek()) 
			{
				// Fill $row with values
				$row = $db->Row();
				
				// Define $isEven for alternate table coloring
				$isEven = !($i % 2);
				if($isEven != '1') 
				{
					echo "<tr style=\"background-color: #CDE6B3;\">";
				} 
				else 
				{ 
					echo "<tr>"; 
				} 
				?>
					<td class="span-2">
						<select class="span-2" name="menuid[<?php echo $row->page_id; ?>]">
							<optgroup label="Menu">
								<?php 
								$y = 1; 
								while($y<=MENU_TARGET_COUNT) 
								{ 
								?>
									<option <?php echo ($row->menu_id==$y) ? "selected=\"selected\"" : ""; ?> value="<?php echo $y; ?>"><?php echo $ccms['lang']['menu'][$y]; ?></option>
									<?php 
									$y++; 
								} 
								?>
							</optgroup>
						</select>
					</td>
					<td class="span-2">
						<select class="span-2" name="template[<?php echo $row->page_id; ?>]">
							<optgroup label="<?php echo $ccms['lang']['backend']['template'];?>">
								<?php 
								$x = 0; 
								while($x<count($template)) 
								{ 
								?>
									<option <?php echo ($row->variant==$template[$x]) ? "selected=\"selected\"" : ""; ?> value="<?php echo $template[$x]; ?>"><?php echo ucfirst($template[$x]); ?></option>
									<?php 
									$x++; 
								} 
								?>
							</optgroup>
						</select>
					</td>
					<td class="span-2">&#160;
						<select class="span-2" name="toplevel[<?php echo $row->page_id; ?>]">
							<optgroup label="Toplevel">
								<?php 
								$z = 1; 
								while($z <= $db->RowCount()) 
								{ 
								?>
									<option <?php echo ($row->toplevel==$z) ? "selected=\"selected\"" : ""; ?> value="<?php echo $z; ?>"><?php echo $z; ?></option>
									<?php 
									$z++; 
								} 
								?>
							</optgroup>
						</select>
					</td>
					<td class="span-2">
						<select class="span-2" name="sublevel[<?php echo $row->page_id; ?>]">
							<optgroup label="Sublevel">
								<?php 
								$y = 0; 
								while($y+1 < $db->RowCount()) 
								{ 
								?>
									<option <?php echo ($row->sublevel==$y) ? "selected=\"selected\"" : ""; ?> value="<?php echo $y; ?>"><?php echo $y; ?></option>
									<?php 
									$y++; 
								} 
								?>
							</optgroup>
						</select>
					</td>
					<td class="span-1-1" id="td-islink-<?php echo $row->page_id; ?>">
						<?php 
						if($row->urlpage == "home") 
						{ 
						?>
							<input type="checkbox" checked="checked" disabled="disabled" />
						<?php 
						} 
						else 
						{ 
						?>
							<input type="checkbox" name="islink" id="<?php echo $row->page_id; ?>" class="islink" <?php echo($row->islink==="Y")?'checked="checked"':null;?> />
						<?php 
						} 
						?>
					</td>
					<td class="span-4">
						<?php echo $row->urlpage; ?><em>(.html)</em>
						<input type="hidden" name="pageid[]" value="<?php echo $row->page_id; ?>" id="pageid"/>
					</td>
				</tr>
				<?php 
				$i++; 
			} 
			?>
			</table>
		<?php 
		} 
		else 
		{
			echo '<p class="center" style="padding-top:5px;"><span class="ss_sprite ss_delete">'.$ccms['lang']['auth']['featnotallowed'].'</span></p>';
		} 
	}
}


/**
 *
 * Process the request for new page creation
 *
 */
if($target_form == "create" && $_SERVER['REQUEST_METHOD'] == "POST" && checkAuth()) 
{
	// Remove all none system friendly characters
	$post_urlpage = filterParam4Filename($_POST['urlpage']); 
	$post_urlpage = strtolower(str_replace(' ','-',$post_urlpage));
	
	// Check for non-empty module variable
	$post_module = strtolower(filterParam4Filename($_POST['module'], "editor"));
	
	// Start with a clean sheet
	$errors=null;
	
	if(strstr($_POST['urlpage'], '.') !== FALSE) 
		{ $errors[] = "- ".$ccms['lang']['system']['error_filedots']; }
	if ($post_urlpage=='' || strlen($post_urlpage)<3)
		{ $errors[] = "- ".$ccms['lang']['system']['error_filesize']; }
	if ($_POST['pagetitle']=='' || strlen($_POST['pagetitle'])<3)
		{ $errors[] = "- ".$ccms['lang']['system']['error_pagetitle']; }
	if ($_POST['subheader']=='' || strlen($_POST['subheader'])<3)
		{ $errors[] = "- ".$ccms['lang']['system']['error_subtitle']; }
	if ($_POST['description']=='' || strlen($_POST['description'])<3)
		{ $errors[] = "- ".$ccms['lang']['system']['error_description']; }
	if ($post_urlpage=='403' || $post_urlpage=='404' || $post_urlpage=='sitemap' /* || $post_urlpage=='home' */)
		{ $errors[] = "- ".$ccms['lang']['system']['error_reserved']; }
	
	if(is_array($errors))
	{
		echo '<p class="h1"><span class="ss_sprite ss_exclamation" title="'.$ccms['lang']['system']['error_general'].'"></span> '.$ccms['lang']['system']['error_correct'].'</p>';
		while (list($key,$value) = each($errors))
		{
			echo '<span class="fault">'.$value.'</span><br />';
		}
		exit(); // Prevent AJAX from continuing
	}
	else 
	{
		// Set variables
		if (!get_magic_quotes_gpc()) 
		{
			$pagetitle	= htmlspecialchars($_POST['pagetitle']);
			$subheader	= htmlspecialchars($_POST['subheader']);
			$description = htmlspecialchars($_POST['description']);
		} 
		else 
		{
			// obsoleted?
			$pagetitle = htmlspecialchars($_POST['pagetitle']);
			$subheader = htmlspecialchars($_POST['subheader']);
			$description = htmlspecialchars($_POST['description']);
		}
		
		// Check radio button values
		$printable_pref = getPOSTparam4boolYN('printable', 'Y');
		$published_pref = getPOSTparam4boolYN('published', 'Y');
		$iscoding_pref	= getPOSTparam4boolYN('iscoding', 'N');
		
		// Insert new page into database
		// $arrayVariable["column name"] = formatted SQL value
		$values['urlpage']		= MySQL::SQLValue($post_urlpage,MySQL::SQLVALUE_TEXT);
		$values['module']		= MySQL::SQLValue($post_module,MySQL::SQLVALUE_TEXT);
		$values['toplevel']		= MySQL::SQLValue('1',MySQL::SQLVALUE_NUMBER);
		$values['sublevel']		= MySQL::SQLValue('0',MySQL::SQLVALUE_NUMBER);
		$values['menu_id']		= MySQL::SQLValue('5',MySQL::SQLVALUE_NUMBER);
		$values['pagetitle']	= MySQL::SQLValue($pagetitle,MySQL::SQLVALUE_TEXT);
		$values['subheader']	= MySQL::SQLValue($subheader,MySQL::SQLVALUE_TEXT);
		$values['description']	= MySQL::SQLValue($description,MySQL::SQLVALUE_TEXT);
		$values['srcfile']		= MySQL::SQLValue($post_urlpage.".php",MySQL::SQLVALUE_TEXT);
		$values['printable']	= MySQL::SQLValue($printable_pref,MySQL::SQLVALUE_Y_N);
		$values['published']	= MySQL::SQLValue($published_pref,MySQL::SQLVALUE_Y_N);
		$values['iscoding']		= MySQL::SQLValue($iscoding_pref,MySQL::SQLVALUE_Y_N);
		
		// Execute the insert
		$result = $db->InsertRow($cfg['db_prefix']."pages", $values);
		
		// Check for errors
		if($result) 
		{
			// Create the actual file
			$filehandle = fopen("../../content/".$post_urlpage.".php", 'w');
			if(!$filehandle) 
			{
				$db->TransactionRollback();
				$errors[] = $ccms['lang']['system']['error_write'];
			} 
			else 
			{
				// Write default contents to newly created file
				if($post_module==="editor") 
				{
					fwrite($filehandle, "<p>".$ccms['lang']['backend']['newfiledone']."</p>");
				} 
				// Write include_once tag to file (modname.Show.php)
				else 
				{
					fwrite($filehandle, "<?php include_once('./lib/modules/$post_module/$post_module.Show.php'); ?>");
				}
			}
			// Report success in notify area
			if(fclose($filehandle)) 
			{
				echo "<p class=\"h1\"><span class=\"ss_sprite ss_accept\" title=\"".$ccms['lang']['backend']['success']."\"></span> ".$ccms['lang']['backend']['newfilecreated']."</p>".$ccms['lang']['backend']['starteditbody'];
			} 
			else 
				die($ccms['lang']['system']['error_create']);
		} 
		elseif($db->ErrorNumber() == 1062) 
		{
			die("<p class=\"h1\"><span class=\"ss_sprite ss_exclamation\" title=\"".$ccms['lang']['system']['error_general']."\"></span> ".$ccms['lang']['backend']['fileexists']."</p>- ".$ccms['lang']['system']['error_exists']); 
		} 
		else 
			die($db->Error($ccms['lang']['system']['error_general'])); // Some error that has not been antipicated.
	}
}

/**
 *
 * Process the request for page deletion
 *
 */
if($target_form == "delete" && $_SERVER['REQUEST_METHOD'] == "POST" && checkAuth()) 
{
	if(!empty($_POST['page_id'])) 
	{
		echo '<p class="h1"><span class="ss_sprite ss_accept" title="'.$ccms['lang']['backend']['success'].'"></span> '.$ccms['lang']['backend']['statusdelete'].'</p>';
	
		// Loop through all submitted page ids
		foreach ($_POST['page_id'] as $index) 
		{
			$value = explode($_SESSION['rc2'], $index);
			if(is_numeric($value[1])) 
			{	
				// Select file name and module with given page_id
				$correct_filename = $db->QuerySingleValue("SELECT `urlpage` FROM `".$cfg['db_prefix']."pages` WHERE `page_id` = ".$value[1]);
				$module = $db->QuerySingleValue("SELECT `module` FROM `".$cfg['db_prefix']."pages` WHERE `page_id` = ".$value[1]);
				
				// Delete details from the database
				$values = array(); // [i_a] make sure $values is an empty array to start with here
				$values["page_id"] = MySQL::SQLValue($value[1],MySQL::SQLVALUE_NUMBER);
				$result = $db->DeleteRows($cfg['db_prefix']."pages", $values);
				
				// Delete linked rows from module tables
				if($module!="editor") 
				{
					$filter = array(); // [i_a] make sure $filter is an empty array to start with here
					$filter["pageID"] = MySQL::SQLValue($correct_filename,MySQL::SQLVALUE_TEXT);
					$delmod = $db->DeleteRows($cfg['db_prefix']."mod".$module, $filter);
					$delcfg = $db->DeleteRows($cfg['db_prefix']."cfg".$module, $filter);
				}
				
				if ($result) 
				{
					// Delete the actual file
					if(@unlink("../../content/".$correct_filename.".php")) 
					{
						echo '- '.ucfirst($correct_filename).' '.$ccms['lang']['backend']['statusremoved'].'<br/>';
					} 
					else 
						die($ccms['lang']['system']['error_delete']);
				} 
				else 
					die($db->Error($ccms['lang']['system']['error_general']));
			} 
			else 
				die($ccms['lang']['system']['error_forged']);
		}
	} 
	else 
		echo '<p class="h1"><span class="ss_sprite ss_exclamation" title="'.$ccms['lang']['system']['error_general'].'"></span> '.$ccms['lang']['system']['error_correct'].'</p><span class="fault">- '.$ccms['lang']['system']['error_selection'].'</span>';
}

/**
 *
 * Save the menu order, individual templating & menu allocation preferences
 *
 */
if($target_form == "menuorder" && $_SERVER['REQUEST_METHOD'] == "POST" && checkAuth()) 
{
	$error = null;
	
	foreach ($_POST['pageid'] as $key => $page_id) 
	{
		$page_id = filterParam4Number($page_id);
		if (!$page_id)
		{
			$error = true;
			break;
		}
		$values = array(); // [i_a] make sure $values is an empty array to start with here
		$values["toplevel"]	= MySQL::SQLValue($_POST['toplevel'][$page_id], MySQL::SQLVALUE_NUMBER);
		$values["sublevel"]	= MySQL::SQLValue($_POST['sublevel'][$page_id], MySQL::SQLVALUE_NUMBER);
		$values["variant"]	= MySQL::SQLValue(filterParam4Filename($_POST['template'][$page_id]), MySQL::SQLVALUE_TEXT);
		$values["menu_id"]	= MySQL::SQLValue($_POST['menuid'][$page_id], MySQL::SQLVALUE_NUMBER);
		
		// Execute the update
		if(!$db->UpdateRows($cfg['db_prefix']."pages", $values, array("page_id" => MySQL::SQLValue($page_id,MySQL::SQLVALUE_NUMBER)))) 
		{
			$error = true; // alas, we exit here and now anyway
			$db->Kill();
		}
	}
	
	if(empty($error)) 
	{
		echo '<p class="h1"><span class="ss_sprite ss_accept" title="'.$ccms['lang']['backend']['success'].'"></span> '.$ccms['lang']['backend']['success'].'</p>'.$ccms['lang']['backend']['orderprefsaved'];
	} 
	else 
		$db->Kill($ccms['lang']['system']['error_general']);
}

 /**
 *
 * Set actual hyperlink behind menu item to true/false
 *
 */
if($do_action == "islink" && $_SERVER['REQUEST_METHOD'] == "POST" && checkAuth()) 
{
	$page_id = getPOSTparam4Number('id');
	$values = array(); // [i_a] make sure $values is an empty array to start with here
	$values["islink"] = MySQL::SQLValue($_POST['cvalue'], MySQL::SQLVALUE_Y_N);
	
	if ($db->UpdateRows($cfg['db_prefix']."pages", $values, array("page_id" => MySQL::SQLValue($page_id,MySQL::SQLVALUE_NUMBER)))) 
	{
		if($values["islink"] == "Y") { echo $ccms['lang']['backend']['yes']; } else echo $ccms['lang']['backend']['no'];
	} 
	else 
		$db->Kill();
}

/**
 *
 * Edit print, publish or iscoding preference
 *
 */
if($do_action == "editinplace" && $_SERVER['REQUEST_METHOD'] != "POST" && checkAuth()) 
{
	// Explode variable with all necessary information
	$page_id = explode("-", $_GET['id']);
	
	// Set the action for this call
	if($page_id[0] == "printable" || $page_id[0] == "published" || $page_id[0] == "iscoding") {
		$action	 = $page_id[0];
	} else die($ccms['lang']['system']['error_forged']);
	if($_GET['s'] == "Y") { $new = "N"; } elseif($_GET['s'] == "N") { $new = "Y"; }
	$values = array(); // [i_a] make sure $values is an empty array to start with here
	$values["$action"] = MySQL::SQLValue($new,MySQL::SQLVALUE_Y_N);
	
	if ($db->UpdateRows($cfg['db_prefix']."pages", $values, array("page_id" => MySQL::SQLValue($page_id[1],MySQL::SQLVALUE_NUMBER)))) 
	{
		if($new == "Y") 
		{ 
			echo $ccms['lang']['backend']['yes']; 
		} 
		else 
		{
			echo $ccms['lang']['backend']['no'];
		}
	} 
	else 
		$db->Kill($ccms['lang']['system']['error_general']);
}

/**
 *
 * Check latest version
 *
 */
$version_recent = @file_get_contents("http://www.compactcms.nl/version/".$v.".txt");
if(version_compare($version_recent, $v) != '1') 
{ 
	$version = $ccms['lang']['backend']['uptodate']; 
} 
else 
{
	$version = $ccms['lang']['backend']['outofdate']." <a href=\"http://www.compactcms.nl/changes.html\" class=\"external\" rel=\"external\">".$ccms['lang']['backend']['considerupdate']."</a>.";
}

/**
 *
 * Edit-in-place update action
 *
 */
if($do_action == "liveedit" && $_SERVER['REQUEST_METHOD'] == "POST" && checkAuth()) 
{
	if(!empty($_POST['content']) && strlen($_POST['content'])>=3 && strlen($_POST['content'])<=240) 
	{
		if (!get_magic_quotes_gpc()) {
    		$content = htmlspecialchars(addslashes($_POST['content']), ENT_COMPAT, 'UTF-8');
    		$content = str_replace("'", "&#039;", $content); 
		} else {
    		$content = htmlspecialchars($_POST['content'], ENT_COMPAT, 'UTF-8');
    	}
	} 
	else 
		die($ccms['lang']['system']['error_value']);
	
	// Continue with content update
	$page_id		= getPOSTparam4Number('id');
	$dest			= getGETparam4IdOrNumber('part');
	$values = array(); // [i_a] make sure $values is an empty array to start with here
	$values["$dest"]= MySQL::SQLValue($content,MySQL::SQLVALUE_TEXT);
	
	if (!$db->UpdateRows($cfg['db_prefix']."pages", $values, array("page_id" => MySQL::SQLValue($page_id,MySQL::SQLVALUE_NUMBER))))
		$db->Kill();
	if (!get_magic_quotes_gpc()) 
	{
    		echo stripslashes($content);
	} 
	else 
	{
		echo $content;
	}
    }
}

/**
 *
 * Save the edited template and check for authority
 *
 */
if($do_action == "save-template" && $_SERVER['REQUEST_METHOD'] == "POST" && checkAuth()) 
{
	// Only if current user has the rights
	if($_SESSION['ccms_userLevel']>=$perm['manageTemplate']) 
	{
		$filename	= "../../lib/templates/".htmlentities($_POST['template']);
		$filenoext	= $_POST['template'];
		$content	= $_POST['content'];
		
		if (is_writable($filename)) 
		{
			if (!$handle = fopen($filename, 'w')) 
			{
				die("[ERR105] ".$ccms['lang']['system']['error_openfile']." (".$filename.").");
			}
			if (fwrite($handle, $content) === FALSE) 
			{
				die("[ERR106] ".$ccms['lang']['system']['error_write']." (".$filename.").");
			}
			// Do on success
			fclose($handle);
			header("Location: ./modules/template-editor/backend.php?status=notice&template=$filenoext");
			exit();
		} 
		else 
		{
			// Else throw relevant error(s)
			die($ccms['lang']['system']['error_chmod']);
		} 
	} 
	else 
		die($ccms['lang']['auth']['featnotallowed']);
}

/**
 *
 * Create a new user as posted by an authorized user
 *
 */
if($do_action == "add-user" && $_SERVER['REQUEST_METHOD'] == "POST" && checkAuth()) 
{
	// Only if current user has the rights
	if($perm['manageUsers']>0 && $_SESSION['ccms_userLevel']>=$perm['manageUsers']) 
	{
		$i=0;
		foreach ($_POST as $key => $value) 
		{
			$count[] = (strlen($value) > 0 ? $i++ : null);
		}
		if($i <= 6) 
		{
			header("Location: ./modules/user-management/backend.php?status=error&action=".$ccms['lang']['system']['error_tooshort']);
			exit();
		}
			
		// Set variables
		$values = array(); // [i_a] make sure $values is an empty array to start with here
		$values['userName']		= MySQL::SQLValue(strtolower($_POST['user']),MySQL::SQLVALUE_TEXT);
		$values['userPass']		= MySQL::SQLValue(md5($_POST['userPass'].$cfg['authcode']),MySQL::SQLVALUE_TEXT);
		$values['userFirst']	= MySQL::SQLValue($_POST['userFirstname'],MySQL::SQLVALUE_TEXT);
		$values['userLast']		= MySQL::SQLValue($_POST['userLastname'],MySQL::SQLVALUE_TEXT);
		$values['userEmail']	= MySQL::SQLValue($_POST['userEmail'],MySQL::SQLVALUE_TEXT);
		$values['userActive']	= MySQL::SQLValue($_POST['userActive'],MySQL::SQLVALUE_BOOLEAN);
		$values['userLevel']	= MySQL::SQLValue($_POST['userLevel'],MySQL::SQLVALUE_NUMBER);
		$values['userToken']	= MySQL::SQLValue(mt_rand('123456789','987654321'),MySQL::SQLVALUE_NUMBER);
		
		// Execute the insert
		$result = $db->InsertRow($cfg['db_prefix']."users", $values);
		
		// Check for errors
		if($result) 
		{
			header("Location: ./modules/user-management/backend.php?status=notice&action=".$ccms['lang']['backend']['settingssaved']);
			exit();
		} 
		else 
			$db->Kill();
	} 
	else 
		die($ccms['lang']['auth']['featnotallowed']);
}

/**
 *
 * Edit user details as posted by an authorized user
 *
 */
if($do_action == "edit-user-details" && $_SERVER['REQUEST_METHOD'] == "POST" && checkAuth()) 
{
	// Only if current user has the rights
	if(($perm['manageUsers']>0 && $_SESSION['ccms_userLevel']>=$perm['manageUsers']) || $_SESSION['ccms_userID']==$_POST['userID']) 
	{
		// Check length of values
		if(strlen($_POST['first'])>2&&strlen($_POST['last'])>2&&strlen($_POST['email'])>6) 
		{
			$userID = getPOSTparam4Number('userID');
			$values = array(); // [i_a] make sure $values is an empty array to start with here
			$values["userFirst"]= MySQL::SQLValue($_POST['first'],MySQL::SQLVALUE_TEXT);
			$values["userLast"]	= MySQL::SQLValue($_POST['last'],MySQL::SQLVALUE_TEXT);
			$values["userEmail"]= MySQL::SQLValue($_POST['email'],MySQL::SQLVALUE_TEXT);
			
			if ($db->UpdateRows($cfg['db_prefix']."users", $values, array("userID" => MySQL::SQLValue($userID,MySQL::SQLVALUE_NUMBER)))) 
			{
				if($userID==$_SESSION['ccms_userID']) 
				{
					$_SESSION['ccms_userFirst']	= htmlspecialchars($_POST['first']);
					$_SESSION['ccms_userLast']	= htmlspecialchars($_POST['last']);
				}
				
				header("Location: ./modules/user-management/backend.php?status=notice&action=".$ccms['lang']['backend']['settingssaved']);
				exit();
			}
			else
				$db->Kill();
		} 
		else 
		{
			header("Location: ./modules/user-management/backend.php?status=error&action=".$ccms['lang']['system']['error_tooshort']);
			exit();
		}
	} 
	else 
		die($ccms['lang']['auth']['featnotallowed']);
}
 
/**
 *
 * Edit users' password as posted by an authorized user
 *
 */
 
if($do_action == "edit-user-password" && $_SERVER['REQUEST_METHOD'] == "POST" && checkAuth()) 
{
	// Only if current user has the rights
	if(($perm['manageUsers']>0 && $_SESSION['ccms_userLevel']>=$perm['manageUsers']) || $_SESSION['ccms_userID']==$_POST['userID']) 
	{
		if(strlen($_POST['userPass'])>6&&md5($_POST['userPass'])===md5($_POST['cpass'])) 
		{
			$userID = getPOSTparam4Number('userID');
			$values = array(); // [i_a] make sure $values is an empty array to start with here
			$values["userPass"] = MySQL::SQLValue(md5($_POST['userPass'].$cfg['authcode']),MySQL::SQLVALUE_TEXT);
			
			if ($db->UpdateRows($cfg['db_prefix']."users", $values, array("userID" => MySQL::SQLValue($userID,MySQL::SQLVALUE_NUMBER)))) 
			{
				header("Location: ./modules/user-management/backend.php?status=notice&action=".$ccms['lang']['backend']['settingssaved']);
				exit();
			}
			else
				$db->Kill();
		} 
		elseif(strlen($_POST['userPass'])<=6) 
		{
			header("Location: ./modules/user-management/user.Edit.php?userID=".$_POST['userID']."&status=error&action=".$ccms['lang']['system']['error_passshort']);
			exit();
		} 
		else 
		{
			header("Location: ./modules/user-management/user.Edit.php?userID=".$_POST['userID']."&status=error&action=".$ccms['lang']['system']['error_passnequal']);
			exit();
		}
	} 
	else 
		die($ccms['lang']['auth']['featnotallowed']);
}

/**
 *
 * Edit user level as posted by an authorized user
 *
 */
 
if($do_action == "edit-user-level" && $_SERVER['REQUEST_METHOD'] == "POST" && checkAuth()) 
{
	// Only if current user has the rights
	if($perm['manageUsers']>0 && $_SESSION['ccms_userLevel']>=$perm['manageUsers']) 
	{
		$userID = getPOSTparam4Number('userID');
		$userLevel = getPOSTparam4Number('userLevel');
		if (is_integer($userLevel))
		{
			$values = array(); // [i_a] make sure $values is an empty array to start with here
			$values["userLevel"] = MySQL::SQLValue($userLevel,MySQL::SQLVALUE_NUMBER);
			$values["userActive"] = MySQL::SQLValue($_POST['userActive'],MySQL::SQLVALUE_BOOLEAN);
				
			if ($db->UpdateRows($cfg['db_prefix']."users", $values, array("userID" => MySQL::SQLValue($userID,MySQL::SQLVALUE_NUMBER)))) 
			{
				if($userID==$_SESSION['ccms_userID']) 
				{
					$_SESSION['ccms_userLevel'] = $userLevel;
				}
				
				header("Location: ./modules/user-management/backend.php?status=notice&action=".$ccms['lang']['backend']['settingssaved']);
				exit();
			}
			else
				$db->Kill();
		}
		else 
			die($ccms['lang']['system']['error_forged']);
	} 
	else 
		die($ccms['lang']['auth']['featnotallowed']);
}

/**
 *
 * Delete a user as posted by an authorized user
 *
 */
if($do_action == "delete-user" && $_SERVER['REQUEST_METHOD'] == "POST" && checkAuth()) 
{
	// Only if current user has the rights
	if($perm['manageUsers']>0 && $_SESSION['ccms_userLevel']>=$perm['manageUsers']) 
	{
		$total = count($_POST['userID']);
		
		if($total==0) 
		{
			header("Location: ./modules/user-management/backend.php?status=error&action=".$ccms['lang']['system']['error_selection']);
			exit();
		}
		
		// Delete details from the database
		$i=0;
		foreach ($_POST['userID'] as $value) 
		{
			$values = array(); // [i_a] make sure $values is an empty array to start with here
			$values['userID'] = MySQL::SQLValue($value,MySQL::SQLVALUE_NUMBER);
			$result = $db->DeleteRows($cfg['db_prefix']."users", $values);
			$i++;
		}
		// Check for errors
		if($result && $i == $total) 
		{
			header("Location: ./modules/user-management/backend.php?status=notice&action=".$ccms['lang']['backend']['fullremoved']);
			exit();
		} 
		else 
			$db->Kill();
	} 
	else 
		die($ccms['lang']['auth']['featnotallowed']);
}

/**
 *
 * Generate the WYSIWYG or code editor for editing purposes (prev. editor.php)
 *
 */
if($do_action == "edit" && $_SERVER['REQUEST_METHOD'] != "POST" && checkAuth()) 
{
	// Set the necessary variables
	$name 		= getGETparam4Filename('file');
	$iscoding	= getGETparam4boolYN('restrict', 'N');
	$active		= getGETparam4boolYN('active', 'N');
	$filename	= "../../content/".$name.".php";
	
	// Check for editor.css in template directory
	$template	= $db->QuerySingleValue("SELECT `variant` FROM `".$cfg['db_prefix']."pages` WHERE `urlpage` = ".MySQL::SQLValue($name, MySQL::SQLVALUE_TEXT));
	$css = "";
	if (is_file('../../lib/templates/'.$template.'/editor.css')) 
	{
		$css = '../../lib/templates/'.$template.'/editor.css';
	}
	
	// Check for filename	
	if(!empty($filename)) 
	{
		$handle = @fopen("$filename", "r");
		if ($handle) 
		{
			// PHP5+ Feature
			// $contents = stream_get_contents($handle);
			// PHP4 Compatibility
			$contents = fread($handle, filesize($filename));
			$contents = str_replace("<br />", "<br>", $contents);
			fclose($handle);
		} 
		else 
		{ 
			die($ccms['lang']['system']['error_deleted']);
		}
	} 
	
	// Get keywords for current file
	$keywords = $db->QuerySingleValue("SELECT `keywords` FROM `".$cfg['db_prefix']."pages` WHERE `urlpage` = '$name'");
	
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $cfg['language']; ?>">
	<head>
		<title>CompactCMS - <?php echo $ccms['lang']['editor']['editorfor']." ".$name; ?></title>
		<script type="text/javascript">
function confirmation()
{
	var answer=confirm('<?php echo $ccms['lang']['editor']['confirmclose']; ?>');
	if(answer)
	{
		try
		{
			parent.MochaUI.closeWindow(parent.$('<?php echo htmlspecialchars($_GET['file']); ?>_ccms'));
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
		
	<?php 
	// Load TinyMCE (compressed for faster loading) 
	if($cfg['wysiwyg']===true && $iscoding=="N") 
	{ 
		$cfg['language'] = (file_exists('./tiny_mce/langs/'.$cfg['language'].'.js'))?$cfg['language']:'en';?>
		
		<!-- File uploader styles -->
		<link rel="stylesheet" media="all" type="text/css" href="./fancyupload/Assets/manager.css" />
	
		<!-- TinyMCE JS -->
		<script type="text/javascript" src="./tiny_mce/tiny_mce_gzip.js"></script>	
		
		<!-- Mootools library -->
		<script type="text/javascript" src="../../lib/includes/js/mootools.js" charset="utf-8"></script>
		
		<!-- File uploader JS -->
		<script type="text/javascript" src="./fancyupload/Source/FileManager.js"></script>
		<script type="text/javascript" src="./fancyupload/Language/Language.en.js"></script>
		<script type="text/javascript" src="./fancyupload/Source/Additions.js"></script>
		<script type="text/javascript" src="./fancyupload/Source/Uploader/Fx.ProgressBar.js"></script>
		<script type="text/javascript" src="./fancyupload/Source/Uploader/Swiff.Uploader.js"></script>
		<script type="text/javascript" src="./fancyupload/Source/Uploader.js"></script>
		<script type="text/javascript">
FileManager.TinyMCE=function(options)
{
	return function(field,url,type,win)
	{
		var manager=new FileManager(
			$extend(
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
				options(type)
			)
		);
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
		
		<!-- GZ version of TinyMCE -->
		<script type="text/javascript">	
tinyMCE_GZ.init(
	{
		plugins:'safari,table,advlink,advimage,media,inlinepopups,print,fullscreen,paste,searchreplace,visualchars,spellchecker,tinyautosave',
		themes:'advanced',
		<?php echo "languages: '".$cfg['language']."',"; ?>
		disk_cache:true,
		debug:false
	});
		</script>
		
		<script type="text/javascript">
tinyMCE.init(
	{
		mode:"textareas",
		theme:"advanced",
		<?php echo 'language:"'.$cfg['language'].'",'; ?>
		skin:"o2k7",
		skin_variant:"silver",
		<?php echo (!empty($css)?'content_css:"'.$css.'",':null);?>
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
		<?php if($cfg['iframe'] === true) { ?> 
			extended_valid_elements:"iframe[align<bottom?left?middle?right?top|class|frameborder|height|id|longdesc|marginheight|marginwidth|name|scrolling<auto?no?yes|src|style|title|width]",
		<?php } ?>
		spellchecker_languages:"+English=en,Dutch=nl,German=de,Spanish=es,French=fr,Italian=it,Russian=ru",
		file_browser_callback:FileManager.TinyMCE(
			function(type)
			{
				return { /* ! '{" MUST be on same line as 'return' otherwise JS will see the newline as end-of-statement! */
					url:type=='image'?'./fancyupload/selectImage.php':'./fancyupload/manager.php',
					assetBasePath:'./fancyupload/Assets',
					language:'en',
					selectable:true,
					uploadAuthData:
					{
						session:'ccms_userLevel'
					}
				};
			})
	});
		</script>

	<?php 
	} // End TinyMCE. Start load Editarea for code editing
	else 
	{ 
		$cfg['language'] = (file_exists('./edit_area/langs/'.$cfg['language'].'.js'))?$cfg['language']:'en'; 
		?>
		<script type="text/javascript" src="./edit_area/edit_area_compressor.php"></script>
		<script type="text/javascript">
editAreaLoader.init(
	{
		id:"content",
		is_multi_files:false,
		allow_toggle:false,
		word_wrap:true,
		start_highlight:true,
		<?php echo 'language:"'.$cfg['language'].'",'; ?>
		syntax:"html"
	});
		</script>
	<?php 
	} 
	?>
	<link rel="stylesheet" type="text/css" href="../img/styles/base.css,layout.css,sprite.css" />
	</head>
	
	<body>
	<div class="module">
		
		<h2><?php echo $ccms['lang']['backend']['editpage']." $name<em>.html</em>"; ?></h2>
		<p><?php echo $ccms['lang']['editor']['instruction']; ?></p>
		
		<form action="./process.inc.php?page=<?php echo $name; ?>&amp;restrict=<?php echo $iscoding; ?>&amp;active=<?php echo $active; ?>" method="post" name="save">
			<textarea id="content" name="content" style="height:345px;width:100%;color:#000;"><?php echo htmlspecialchars(trim($contents)); ?></textarea>
			<br/>
				<label for="keywords"><?php echo $ccms['lang']['editor']['keywords']; ?></label>
				<input type="input" class="text" style="height:30px; width:98%;" maxlength="250" name="keywords" value="<?php echo $keywords; ?>" id="keywords">
			<p>
				<input type="hidden" name="action" value="Save changes" />
				<input type="hidden" name="code" value="<?php echo (isset($_GET['restrict'])&&$_GET['restrict']=="Y"?1:null);?>" id="code" />
				<button type="submit" name="do" id="submit"><span class="ss_sprite ss_disk"><?php echo $ccms['lang']['editor']['savebtn']; ?></span></button>
				<span class="ss_sprite ss_cross"><a href="javascript:;" onClick="confirmation()" title="<?php echo $ccms['lang']['editor']['cancelbtn']; ?>"><?php echo $ccms['lang']['editor']['cancelbtn']; ?></a></span>
			</p>
		</form>
		
	</div>
	</body>
	</html>
<?php 
}

 /**
 *
 * Processing save page (prev. handler.inc.php)
 *
 */
if(isset($_POST['action']) && $_POST['action'] == "Save changes" && checkAuth()) 
{
	// Strip slashes for certain servers (DEPRECIATED for PHP6)
	if (get_magic_quotes_gpc()) 
	{
		function stripslashes_deep($value) 
		{
			if(is_array($value)) {
				$value = array_map('stripslashes_deep',$value);
			} else {
				$value = stripslashes($value);
			}
			return $value;
		}
	
		$_POST = array_map('stripslashes_deep', $_POST);
		$_GET = array_map('stripslashes_deep', $_GET);
	}

	$name 		= getGETparam4Filename('page');
	$active		= getGETparam4boolYN('active', 'N');
	$type		= (isset($_POST['code'])&&$_POST['code']>0?"code":"text");
	$content	= $_POST['content'];
	$filename	= "../../content/$name.php";
	$keywords	= htmlentities($_POST['keywords']);

	if (is_writable($filename)) 
	{
		if (!$handle = fopen($filename, 'w')) {
			die("[ERR105] ".$ccms['lang']['system']['error_openfile']." (".$filename.").");
		}
		if (fwrite($handle, $content) === FALSE) {
			die("[ERR106] ".$ccms['lang']['system']['error_write']." (".$filename.").");
		}
		fclose($handle);
	} else {
		die($ccms['lang']['system']['error_chmod']);
	}
		
	// Save keywords to database
	$values = array(); // [i_a] make sure $values is an empty array to start with here
	$values["keywords"]= MySQL::SQLValue($keywords,MySQL::SQLVALUE_TEXT);
	
	if ($db->UpdateRows($cfg['db_prefix']."pages", $values, array("urlpage" => MySQL::SQLValue($name,MySQL::SQLVALUE_TEXT)))) 
	{
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $cfg['language']; ?>">
	<head>
		<title>CompactCMS <?php echo $ccms['lang']['backend']['administration']; ?></title>
		<link rel="stylesheet" type="text/css" href="../img/styles/base.css,layout.css,sprite.css" />
	</head>

	<body>
		<div id="handler-wrapper" class="module">
			
			<?php 
			if($active=="N") 
			{
			?>
				<p class="notice"><?php $msg = explode('::', $ccms['lang']['hints']['published']); echo $msg[0].": <strong>".strtolower($ccms['lang']['backend']['disabled'])."</strong>"; ?></p>
			<?php 
			} 
			?>
			<p class="success"><?php echo $ccms['lang']['editor']['savesuccess']; ?><em><?php echo $name; ?>.html</em>.</p>
			<hr/>
			<?php 
			if($type=="code") 
			{ 
			?>
				<p><pre><?php echo htmlentities(file_get_contents($filename)); ?></pre></p>
			<?php 
			} 
			else /* if($type=="text") */
			{ 
			?>
				<p><?php echo file_get_contents($filename); ?></p>
			<?php 
			} 
			?>
			<hr/>
			<p>
				<a href="../../<?php echo $name; ?>.html?preview=<?php echo $cfg['authcode'];?>" class="external" target="_blank"><?php echo $ccms['lang']['editor']['preview']; ?></a>		
			</p>
			<p>
				<span class="ss_sprite ss_arrow_undo"><a href="process.inc.php?file=<?php echo $name; ?>&amp;action=edit&amp;restrict=<?php echo htmlspecialchars($_GET['restrict']); ?>&amp;active=<?php echo $active; ?>"><?php echo $ccms['lang']['editor']['backeditor']; ?></a></span>&nbsp;&nbsp;&nbsp;
				<span class="ss_sprite ss_cross"><a href="#" onClick="parent.MochaUI.closeWindow(parent.$('<?php echo $name; ?>_ccms'));" title="<?php echo $ccms['lang']['editor']['closewindow']; ?>"><?php echo $ccms['lang']['editor']['closewindow']; ?></a></span>
			</p>
			
		</div>
	</body>
	</html>
	<?php 	
	} 
	else 
		$db->Kill();
} 
?>