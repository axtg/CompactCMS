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

// System wide messages
$ccms['lang']['system']['error_database'] 	= "Could not connect to the database. Please verify your login details and database name.";
$ccms['lang']['system']['error_openfile'] 	= "Can not open the specified file";
$ccms['lang']['system']['error_notemplate']	= "No templates could be found to be applied to your site. Please add at least one template to ./lib/templates/.";
$ccms['lang']['system']['error_templatedir'] = "Couldn't find the templates directory! Make sure it exists and contains at least one template.";
$ccms['lang']['system']['error_write'] 		= "File has no write access";
$ccms['lang']['system']['error_dirwrite']	= "Directory has no write access";
$ccms['lang']['system']['error_chmod'] 		= "The current file could not be modified. Check permissions on the files in the /content directory (666).";
$ccms['lang']['system']['error_value'] 		= "Error: incorrect value";
$ccms['lang']['system']['error_default']	= "Default page cannot be deleted.";
$ccms['lang']['system']['error_forged']		= "Value has been tempered with";
$ccms['lang']['system']['error_filedots']	= "File name should not contain dots, e.g. '.html'.";
$ccms['lang']['system']['error_filesize']	= "File name should be at least 3 characters long.";
$ccms['lang']['system']['error_pagetitle']	= "Enter a page title of 3 characters or more.";
$ccms['lang']['system']['error_subtitle']	= "Give a short sub-title for your page.";
$ccms['lang']['system']['error_description'] = "Enter a description of more than 3 characters";
$ccms['lang']['system']['error_reserved'] 	= "You've specified a file name reserved for internal use.";
$ccms['lang']['system']['error_general']	= "Error occurred";
$ccms['lang']['system']['error_correct'] 	= "Please correct the following:";
$ccms['lang']['system']['error_create'] 	= "Error while completing creation of the new file";
$ccms['lang']['system']['error_exists'] 	= "The file name you specified already exists.";
$ccms['lang']['system']['error_delete']		= "Error while completing deletion of the selected file";
$ccms['lang']['system']['error_selection'] 	= "There were no items selected.";
$ccms['lang']['system']['error_versioninfo'] = "No version information is available.";
$ccms['lang']['system']['error_misconfig']	= "<strong>There seems to be a misconfiguration.</strong><br/>Please make sure the .htaccess file is correctly configured to reflect your file structure. If you have<br/>installed CompactCMS in a subdirectory, then adjust the .htaccess file accordingly.";
$ccms['lang']['system']['error_deleted']	= "<h1>The file you selected seems to be deleted</h1><p>Refresh the filelist to see the most recent list of available files to prevent this error from happening. If this doesn't solve this error, manually check the content folder for the file you're trying to open.</p>";
$ccms['lang']['system']['error_404title'] 	= "File not found";
$ccms['lang']['system']['error_404header']	= "A 404 error occurred, the requested file could not be found.";
$ccms['lang']['system']['error_sitemap'] 	= "An overview of all pages";
$ccms['lang']['system']['error_tooshort']	= "One or multiple submitted values were either too short or incorrect";
$ccms['lang']['system']['error_passshort']	= "A password should contain more than 6 characters";
$ccms['lang']['system']['error_passnequal']	= "The entered passwords did not match";
$ccms['lang']['system']['noresults']		= "No results";
$ccms['lang']['system']['tooriginal']		= "Back to original";
$ccms['lang']['system']['message_rights'] 	= "All rights reserved";
$ccms['lang']['system']['message_compatible'] = "Successfully tested on";

// Administration general messages
$ccms['lang']['backend']['gethelp'] 		= "Got suggestions, feedback or having trouble? Visit <a href=\"http://community.compactcms.nl/forum/\" title=\"Visit the official forum\" class=\"external\" rel=\"external\">the forum</a>!";
$ccms['lang']['backend']['ordertip'] 		= "Use the drop-downs below to reflect the structure of your pages in your sites' menu. Be aware that the system doesn't take into account duplicate top or sub level combinations.";
$ccms['lang']['backend']['createtip'] 		= "To create a new page, fill out the form below and a new page will be created for you on the fly. After the file has been created, you'll be able to edit the page as usual.";
$ccms['lang']['backend']['currentfiles'] 	= "In the listing below you'll find all pages currently published. You'll notice that the file default page can not be deleted, because it is the current homepage of your website. Other files may have restricted access because the administrator has sole ownership over these files.";
$ccms['lang']['backend']['confirmdelete'] 	= "Please confirm that you want to delete the selected item(s).";
$ccms['lang']['backend']['settingssaved']	= "Your changes have been successfully saved.";
$ccms['lang']['backend']['itemcreated']		= "Successfully processed the submitted item(s).";
$ccms['lang']['backend']['fullremoved']		= "Successfully deleted the selected item(s).";
$ccms['lang']['backend']['tooverview']		= "Back to overview";
$ccms['lang']['backend']['permissions']		= "Set CCMS permissions";
$ccms['lang']['backend']['contentowners']	= "Define content owners";
$ccms['lang']['backend']['templateeditor']	= "Template editor";
$ccms['lang']['backend']['usermanagement']	= "User management";
$ccms['lang']['backend']['changevalue']		= "Click to change";
$ccms['lang']['backend']['previewpage']		= "Preview";
$ccms['lang']['backend']['editpage']		= "Edit";
$ccms['lang']['backend']['restrictpage'] 	= "Restricted";
$ccms['lang']['backend']['newfiledone'] 	= "This file is fresh and clean for you to fill-up!";
$ccms['lang']['backend']['newfilecreated']	= "The file has been created";
$ccms['lang']['backend']['startedittitle'] 	= "Start editing!";
$ccms['lang']['backend']['starteditbody']	= "The new file has been created. Start editing right away or either add more pages or manage the current ones.";
$ccms['lang']['backend']['success'] 		= "Success!";
$ccms['lang']['backend']['fileexists'] 		= "File exists";
$ccms['lang']['backend']['statusdelete'] 	= "Status of selected deletion:";
$ccms['lang']['backend']['statusremoved']	= "removed";
$ccms['lang']['backend']['uptodate']		= "up to date.";
$ccms['lang']['backend']['outofdate']		= "outdated.";
$ccms['lang']['backend']['considerupdate'] 	= "Consider updating";
$ccms['lang']['backend']['orderprefsaved'] 	= "Your preferences for the order of your menu items have been saved.";
$ccms['lang']['backend']['inmenu']			= "In menu";
$ccms['lang']['backend']['updatelist']		= "Update file list";
$ccms['lang']['backend']['administration']	= "Administration";
$ccms['lang']['backend']['currentversion']	= "You're currently running version";
$ccms['lang']['backend']['mostrecent']		= "The most recent stable CompactCMS version is";
$ccms['lang']['backend']['versionstatus'] 	= "Your installation is";
$ccms['lang']['backend']['createpage']		= "Create a new page";
$ccms['lang']['backend']['managemenu']		= "Manage menu";
$ccms['lang']['backend']['managefiles'] 	= "Manage current pages";
$ccms['lang']['backend']['delete'] 			= "Delete";
$ccms['lang']['backend']['toplevel']		= "Top level";
$ccms['lang']['backend']['sublevel'] 		= "Sub level";
$ccms['lang']['backend']['active']			= "Active";
$ccms['lang']['backend']['disabled']		= "Disabled";
$ccms['lang']['backend']['template']		= "Template";
$ccms['lang']['backend']['notinmenu']		= "Item not in a menu";
$ccms['lang']['backend']['menutitle']		= "Menu";
$ccms['lang']['backend']['linktitle']		= "Link";
$ccms['lang']['backend']['item']			= "Item";
$ccms['lang']['backend']['none']			= "None";
$ccms['lang']['backend']['yes']				= "Yes";
$ccms['lang']['backend']['no']				= "No";

// Texts for authentication screen
$ccms['lang']['login']['welcome']			= "<p>Use a valid username and password to gain access to the CompactCMS back-end. If you arrived here by mistake, return to the <a href='../../'>home page</a>.</p><p>Contact your webmaster for your details.</p>";
$ccms['lang']['login']['username']			= "Username";
$ccms['lang']['login']['password']			= "Password";
$ccms['lang']['login']['login']				= "Login";
$ccms['lang']['login']['provide']			= "Please provide your user credentials";
$ccms['lang']['login']['nodetails']			= "Enter both your username and password";
$ccms['lang']['login']['nouser']			= "Enter your username";
$ccms['lang']['login']['nopass']			= "Enter your password";
$ccms['lang']['login']['notactive']			= "This account has been deactivated";
$ccms['lang']['login']['falsetries']		= "Note that you already made multiple attempts";
$ccms['lang']['login']['nomatch']			= "Incorrect username or password";

// Menu titles for administration back-end
$ccms['lang']['menu']['1']				= "Main";
$ccms['lang']['menu']['2']				= "Left";
$ccms['lang']['menu']['3']				= "Right";
$ccms['lang']['menu']['4']				= "Footer";
$ccms['lang']['menu']['5']				= "Extra";

// Administration form related texts
$ccms['lang']['forms']['filename']		= "File name"; 
$ccms['lang']['forms']['pagetitle']		= "Page title";
$ccms['lang']['forms']['subheader'] 	= "Subheader";
$ccms['lang']['forms']['description'] 	= "Description";
$ccms['lang']['forms']['module'] 		= "Module";
$ccms['lang']['forms']['contentitem']	= "Content item (default)";
$ccms['lang']['forms']['additions']		= "Additions";
$ccms['lang']['forms']['printable'] 	= "Printable";
$ccms['lang']['forms']['published'] 	= "Active";
$ccms['lang']['forms']['iscoding'] 		= "Coding";
$ccms['lang']['forms']['createbutton'] 	= "Create!";
$ccms['lang']['forms']['modifybutton'] 	= "Modify";
$ccms['lang']['forms']['savebutton'] 	= "Save";
$ccms['lang']['forms']['setlocale']		= "Front-end language";

// Administration hints for form fields
$ccms['lang']['hints']['filename']		= "The page url (home.html) :: The file name which this page is called upon (without .html)";
$ccms['lang']['hints']['pagetitle'] 	= "Title for this page (Home) :: A short descriptive title for this page.";
$ccms['lang']['hints']['subheader']		= "Short header text (Welcome to our site) :: A short descriptive text used in the header of each page as well as in the title of each page.";
$ccms['lang']['hints']['description']	= "Meta description :: A unique description for this page which will be used as the pages' meta description.";
$ccms['lang']['hints']['module']		= "Module :: Select what module should handle the content of this file. If you are unsure, select the default.";
$ccms['lang']['hints']['printable']		= "Page printability :: When selected 'YES' a printable page is generated. 'NO' should be selected for pages with pictures or other media. These are better off without a printable page.";
$ccms['lang']['hints']['published']		= "Published status :: Select if this page should be published, if it'll be listed in the sitemap and whether it will be accessible to the public.";
$ccms['lang']['hints']['toplevel']		= "Top level :: Specify the top level for this menu item.";
$ccms['lang']['hints']['sublevel']		= "Sub level :: Select 0 when this item should be a top level item. If it is a sub item for a certain top level, please select the appropriate sub level.";
$ccms['lang']['hints']['template']		= "Template :: If you use multiple templates for your installation, you can appoint separate templates to each individual page using this drop-down.";
$ccms['lang']['hints']['activelink']	= "Active link in menu? :: Not all items always need an actual link. To deactivate the link behind this item in the front-end menu, uncheck its checkbox below.";
$ccms['lang']['hints']['menuid']		= "Menu :: Choose in which menu this item should be listed in. The default is the main menu (1), where also the home page link should be shown.";
$ccms['lang']['hints']['iscoding']		= "Contains coding :: Does this file contain manual added code such as PHP or Javascript? Selecting 'Yes' will restrict access to the file from the back-end's WYSIWYG editor and enables the code editor.";

// Editor messages
$ccms['lang']['editor']['closeeditor']	= "Close the editor";
$ccms['lang']['editor']['editorfor']	= "Text editor for";
$ccms['lang']['editor']['instruction']	= "Use the editor below to modify the current file. Once you're done, hit the \"Save changes\" button below to directly publish your modifications to the world wide web. Also add up to ten relevant keywords for search engine optimalization.";
$ccms['lang']['editor']['savebtn']		= "Save changes";
$ccms['lang']['editor']['cancelbtn'] 	= "Cancel";
$ccms['lang']['editor']['confirmclose'] = "Close this window and discard any changes?";
$ccms['lang']['editor']['preview']		= "Preview the result page";
$ccms['lang']['editor']['savesuccess'] 	= "<strong>Success!</strong> The content as shown below has been saved to ";
$ccms['lang']['editor']['backeditor'] 	= "Back to editor";
$ccms['lang']['editor']['closewindow'] 	= "Close window";
$ccms['lang']['editor']['keywords']		= "Keywords - <em>separated by commas, max 250 characters</em>";

// Authorization messages
$ccms['lang']['auth']['generatepass'] 	= "Auto generate a safe password";
$ccms['lang']['auth']['featnotallowed']	= "Your current account level does not allow you to use this feature.";

################### MODULES ###################

// Back-up messages
$ccms['lang']['backup']['createhd']		= "Create new back-up";
$ccms['lang']['backup']['explain']		= "To prevent possible loss of data due to whatever external event, it's wise to create back-ups of your files reguraly.";
$ccms['lang']['backup']['currenthd']	= "Available back-ups";
$ccms['lang']['backup']['timestamp']	= "Back-up file name";
$ccms['lang']['backup']['download']		= "Download archive";

// User management messages
$ccms['lang']['users']['createuser']	= "Create a user";
$ccms['lang']['users']['overviewusers']	= "Overview CCMS users";
$ccms['lang']['users']['editdetails']	= "Edit user's personal details";
$ccms['lang']['users']['editpassword']	= "Edit user's password";
$ccms['lang']['users']['accountcfg']	= "Account settings";
$ccms['lang']['users']['user']			= "User";
$ccms['lang']['users']['username']		= "Username";
$ccms['lang']['users']['name']			= "Name";
$ccms['lang']['users']['firstname']		= "First name";
$ccms['lang']['users']['lastname']		= "Last name";
$ccms['lang']['users']['password']		= "Password";
$ccms['lang']['users']['cpassword']		= "Confirm password";
$ccms['lang']['users']['email']			= "E-mail";
$ccms['lang']['users']['active']		= "Active";
$ccms['lang']['users']['level']			= "Level";
$ccms['lang']['users']['userlevel']		= "User level";
$ccms['lang']['users']['lastlog']		= "Last log";

// Template editor
$ccms['lang']['template']['manage']		= "Manage templates";
$ccms['lang']['template']['nowrite']	= "The current template is <strong>not</strong> writable";

// Permissions
$ccms['lang']['permission']['header']	= "Permission preferences";
$ccms['lang']['permission']['explain']	= "Use the table below to specify what minimum user level can use certain features. Any user below the specified minimum required user level, will not see nor have access to the feature.";
$ccms['lang']['permission']['target']	= "Target";
$ccms['lang']['permission']['level1']	= "Level 1 - User";
$ccms['lang']['permission']['level2']	= "Level 2 - Editor";
$ccms['lang']['permission']['level3']	= "Level 3 - Manager";
$ccms['lang']['permission']['level4']	= "Level 4 - Admin";

// Content owners
$ccms['lang']['owners']['header']		= "Content owners";
$ccms['lang']['owners']['explain']		= "Here you can appoint specific page ownership to individual users. If for a cartain page no users are selected, everyone can modify the page. Otherwise only the specified user had modification rights to the file. Administrators always have access to all files.";
$ccms['lang']['owners']['pages']		= "Pages";
$ccms['lang']['owners']['users']		= "Users";

// Album messages
$ccms['lang']['album']['album']			= "Album";
$ccms['lang']['album']['currentalbums']	= "Current albums";
$ccms['lang']['album']['uploadcontent']	= "Upload content";
$ccms['lang']['album']['toexisting']	= "Upload to existing album";
$ccms['lang']['album']['upload']		= "Start upload";
$ccms['lang']['album']['browse']		= "Browse files";
$ccms['lang']['album']['clear']			= "Clear list";
$ccms['lang']['album']['singlefile']	= "<strong>Single file upload</strong><br/><p>The Flash loader failed to initialize. Make sure Javascript is enabled and Flash is installed. Single file uploads are possible, but not optimized.</p>";
$ccms['lang']['album']['manage']		= "Manage album";
$ccms['lang']['album']['albumlist']		= "Album list";
$ccms['lang']['album']['newalbum']		= "Create new album";
$ccms['lang']['album']['noalbums']		= "No albums have been created yet!";
$ccms['lang']['album']['files']			= "Files";
$ccms['lang']['album']['nodir']			= "Please make sure the directory <strong>albums</strong> exists in the ./media/ directory";
$ccms['lang']['album']['lastmod']		= "Last modified";

// News messages
$ccms['lang']['news']['manage']			= "Manage current news items";
$ccms['lang']['news']['addnews']		= "Add news";
$ccms['lang']['news']['addnewslink']	= "Write new article";
$ccms['lang']['news']['settings']		= "Manage settings";
$ccms['lang']['news']['writenews']		= "Write news";
$ccms['lang']['news']['numbermess']		= "# messages on front-end";
$ccms['lang']['news']['showauthor']		= "Show author";
$ccms['lang']['news']['showdate']		= "Show publication date";
$ccms['lang']['news']['showteaser']		= "Only show teaser";
$ccms['lang']['news']['title']			= "News title";
$ccms['lang']['news']['author']			= "News author";
$ccms['lang']['news']['date']			= "Date";
$ccms['lang']['news']['published']		= "Published?";
$ccms['lang']['news']['teaser']			= "Teaser";
$ccms['lang']['news']['contents']		= "Article contents";
$ccms['lang']['news']['viewarchive']	= "View archive";

// Guestbook message
$ccms['lang']['guestbook']['noposts']	= "No reactions have been posted yet!";
$ccms['lang']['guestbook']['verinstr']	= "To check that this message isn't automated, please re-enter";
$ccms['lang']['guestbook']['reaction']	= "Reaction";
$ccms['lang']['guestbook']['rating']	= "Rating";
$ccms['lang']['guestbook']['avatar']	= "Gravatar.com user avatar";
$ccms['lang']['guestbook']['wrote']		= "wrote";
$ccms['lang']['guestbook']['manage']	= "Manage reactions";
$ccms['lang']['guestbook']['delentry']	= "Delete this entry";
$ccms['lang']['guestbook']['sendmail']	= "E-mail author";
$ccms['lang']['guestbook']['removed'] 	= "has been removed from the database.";
$ccms['lang']['guestbook']['name'] 		= "Your name";
$ccms['lang']['guestbook']['email']		= "Your e-mail";
$ccms['lang']['guestbook']['website']	= "Your website";
$ccms['lang']['guestbook']['comments']	= "Comments";
$ccms['lang']['guestbook']['verify']	= "Verification";
$ccms['lang']['guestbook']['preview']	= "Preview comment";
$ccms['lang']['guestbook']['add']		= "Add your comments";
$ccms['lang']['guestbook']['posted']	= "Comment has been posted!";
?>