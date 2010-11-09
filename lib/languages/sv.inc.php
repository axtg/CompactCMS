<?php
/* ************************************************************
Copyright (C) 2008 - 2009 by Xander Groesbeek (CompactCMS.nl)
Revision:	CompactCMS - v 1.4.0
	
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

/* Translation by Bob Axell <cyberdyze@gmail.com> */

// System wide messages
$ccms['lang']['system']['error_database'] 	= "Kunde ej koppla till databasen. Var god verifiera databasinformation nedan.";
$ccms['lang']['system']['error_openfile'] 	= "Kunde ej öppna den specifierade filen";
$ccms['lang']['system']['error_notemplate']	= "No templates could be found to be applied to your site. Please add at least one template to ./lib/templates/.";
$ccms['lang']['system']['error_templatedir'] = "Couldn't find the templates directory! Make sure it exists and contains at least one template.";
$ccms['lang']['system']['error_write'] 		= "Filen är ej skrivbar";
$ccms['lang']['system']['error_chmod'] 		= "Den nuvarande filen kunde ej modifieras. Var vänlig kontrollera skrivbarhetsinställningar på filen och dess mapp (666).";
$ccms['lang']['system']['error_value'] 		= "Fel: felaktigt värde";
$ccms['lang']['system']['error_default']	= "Huvudsida kan ej raderas.";
$ccms['lang']['system']['error_forged']		= "Värde har manipulerats";
$ccms['lang']['system']['error_filedots']	= "Filnamn bör ej innehålla punkter, t.e.x. '.html'.";
$ccms['lang']['system']['error_filesize']	= "Filnamn måste innehålla minst 3 tecken.";
$ccms['lang']['system']['error_pagetitle']	= "Skriv in en sidotitel med minst 3 tecken.";
$ccms['lang']['system']['error_subtitle']	= "Skriv in en kort undertitel till din sida";
$ccms['lang']['system']['error_description'] = "Skriv in en beskrivning med mer än 3 tecken";
$ccms['lang']['system']['error_reserved'] 	= "Du har specifierat ett filnamn som reserverats för intern användning.";
$ccms['lang']['system']['error_general']	= "Fel inträffade";
$ccms['lang']['system']['error_correct'] 	= "Var god rätta till följande:";
$ccms['lang']['system']['error_create'] 	= "Fel vid skapandet av den nya filen";
$ccms['lang']['system']['error_exists'] 	= "Det filnamn du valt existerar redan.";
$ccms['lang']['system']['error_delete']		= "Fel vid raderandet av den nya filen";
$ccms['lang']['system']['error_selection'] 	= "Ingen fil har valts.";
$ccms['lang']['system']['error_versioninfo'] = "Det finns ingen versioninformation tillgänglig.";
$ccms['lang']['system']['error_misconfig']	= "<strong>Det verkar finnas en felkonfigurering.</strong><br/>Vad vänlig se till att .htaccess filens inställningar motsvarar nuvarande filstruktur. Om du har<br/>installerat CompactCMS i en underkatalog, justera då .htaccess filen i enlighet med detta.";
$ccms['lang']['system']['error_deleted']	= "<h1>Filen du har valt verkar ha raderats</h1><p>Uppdatera listan av filer för att undvika att detta händer. Om detta inte hjälper måste du manuellt öppna sökvägen och leta reda på filen du vill ha.</p>";
$ccms['lang']['system']['error_404title'] 	= "Flen hittades ej";
$ccms['lang']['system']['error_404header']	= "Ett 404 fel inträffade, den begärda filen kunde ej hittas.";
$ccms['lang']['system']['error_sitemap'] 	= "En översikt över alla sidor";
$ccms['lang']['system']['tooriginal']		= "Tillbaka till originalet";
$ccms['lang']['system']['message_rights'] 	= "Alla rättigheter reserverade";
$ccms['lang']['system']['message_compatible'] = "Testkörd på ";
$ccms['lang']['system']['error_notemplate']	= "Inga stilmallar hittades som kunde appliceras på din sida. Var god lägg in din stilmall i mappen ./lib/templates/.";

// Administration general messages
$ccms['lang']['backend']['gethelp'] 		= "Har du förslag, feedback eller problem? Besök <a href=\"http://community.compactcms.nl/forum/\" title=\"Besök det officiella forumet\" class=\"external\" rel=\"external\">forumet</a>!";
$ccms['lang']['backend']['ordertip'] 		= "Använd drop-down menyn nedan för att justera menyn på din sida. Notera att systemet inte lägger märka till dubletter.";
$ccms['lang']['backend']['createtip'] 		= "För att skapa en ny sida, fyll i formuläret nedan och sidan kommer att skapas direkt. När sidan har skapats kan du redigera den som vanligt";
$ccms['lang']['backend']['currentfiles'] 	= "I listan nedan hittar du alla hittills publicerade sidor. Huvudsisdan kan ej raderas, eftersom den är förstasidan på din webbplats. Andra filer kan ha begränsad åtkomlighet eftersom endast administratören äger tillträde till dem.";
$ccms['lang']['backend']['confirmdelete'] 	= "Var vänlig bekräfta att du verkligen vill ta bort alla dessa sidor och dess innehåll.";
$ccms['lang']['backend']['changevalue']		= "Klicka för att ändra";
$ccms['lang']['backend']['previewpage']		= "Förhandsgranska";
$ccms['lang']['backend']['editpage']		= "Redigera";
$ccms['lang']['backend']['restrictpage'] 	= "Begränsad";
$ccms['lang']['backend']['newfiledone'] 	= "Denna fil är fräsh och klar att fyllas i!";
$ccms['lang']['backend']['newfilecreated']	= "Filen har skapats";
$ccms['lang']['backend']['startedittitle'] 	= "Börja redigera!";
$ccms['lang']['backend']['starteditbody']	= "Den nya filen har skapats. Börja redigera genast eller skapa nya sidor, eller alternativt redigera gammla.";
$ccms['lang']['backend']['success'] 		= "Framgång!";
$ccms['lang']['backend']['fileexists'] 		= "Filen existerar";
$ccms['lang']['backend']['statusdelete'] 	= "Status av utvald radering:";
$ccms['lang']['backend']['statusremoved']	= "raderad";
$ccms['lang']['backend']['uptodate']		= "aktuell.";
$ccms['lang']['backend']['outofdate']		= "inaktuell.";
$ccms['lang']['backend']['considerupdate'] 	= "Överväg uppdatering";
$ccms['lang']['backend']['orderprefsaved'] 	= "Dina menyinställningar har sparats.";
$ccms['lang']['backend']['inmenu']			= "I meny";
$ccms['lang']['backend']['updatelist']		= "Uppdatera fil-lista";
$ccms['lang']['backend']['administration']	= "Administration";
$ccms['lang']['backend']['currentversion']	= "Du kör för tillfället version";
$ccms['lang']['backend']['mostrecent']		= "Den senaste stabila CompactCMS versionen är";
$ccms['lang']['backend']['versionstatus'] 	= "Din installation är";
$ccms['lang']['backend']['createpage']		= "Skapa en ny sida";
$ccms['lang']['backend']['managemenu']		= "Hantera meny";
$ccms['lang']['backend']['managefiles'] 	= "Hantera nuvarande filer";
$ccms['lang']['backend']['delete'] 			= "Radera";
$ccms['lang']['backend']['toplevel']		= "Huvudgrupper";
$ccms['lang']['backend']['sublevel'] 		= "Undergrupper";
$ccms['lang']['backend']['active']			= "Aktiv";
$ccms['lang']['backend']['disabled']		= "Avaktiverad";
$ccms['lang']['backend']['template']		= "Aktiverad";
$ccms['lang']['backend']['notinmenu']		= "Föremål ej i meny";
$ccms['lang']['backend']['menutitle']		= "Meny";
$ccms['lang']['backend']['linktitle']		= "Länk";
$ccms['lang']['backend']['item']			= "Föremål";
$ccms['lang']['backend']['yes']				= "Ja";
$ccms['lang']['backend']['no']				= "Nej";

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
$ccms['lang']['forms']['filename']		= "Filnamn"; 
$ccms['lang']['forms']['pagetitle']		= "Sidotitel";
$ccms['lang']['forms']['subheader'] 	= "Undertitel";
$ccms['lang']['forms']['description'] 	= "Beskrivning";
$ccms['lang']['forms']['module'] 		= "Module";
$ccms['lang']['forms']['contentitem']	= "Content item (default)";
$ccms['lang']['forms']['additions']		= "Additions";
$ccms['lang']['forms']['printable'] 	= "Utskrivbar";
$ccms['lang']['forms']['published'] 	= "Aktiv";
$ccms['lang']['forms']['iscoding'] 		= "Kodning";
$ccms['lang']['forms']['createbutton'] 	= "Skapa!";
$ccms['lang']['forms']['savebutton'] 	= "Spara";

// Administration hints for form fields
$ccms['lang']['hints']['filename']		= "Sido-URL:et (home.html) :: Filnamnet med vilket denna sida framtas (utan .html)";
$ccms['lang']['hints']['pagetitle'] 	= "Titel för denna sida (Home) :: AEn kort förmedlande titel för denna sida.";
$ccms['lang']['hints']['subheader']		= "Kort rubriktext (Välkommen till vår webbplats): En kort beskrivande text som används i sidhuvudet på varje sida samt i rubriken på varje sida.";
$ccms['lang']['hints']['description']	= "Meta Beskrivning :: En unik beskrivning för denna sida som kommer att användas som sidorna 'meta beskrivning'.";
$ccms['lang']['hints']['module']		= "Module :: Select what module should handle the content of this file. If you are unsure, select the default.";
$ccms['lang']['hints']['printable']		= "ida tryckbarhet:: När alternativet 'JA' väljs skall en utskriftsvänlig sida genereras. 'NEJ' bör väljas för sidor med bilder eller andra medier. Dessa är bättre utan en utskrivbar sida.";
$ccms['lang']['hints']['published']		= "Publicerad status :: Välj om sidan bör offentliggöras, om det blir i webbplatskartan och om den kommer att vara tillgänglig för allmänheten.";
$ccms['lang']['hints']['toplevel']		= "Huvudgrupper :: Ange den högsta nivån för det här menyalternativet. Välj --- att inte inkludera sidan i menyn.";
$ccms['lang']['hints']['sublevel']		= "Undergrupper :: Välj 0 när denna punkt bör ha högsta nivå. Om det är av lägre rang för en viss huvudgrupp, vänligen välj lämplig undergrupp.";
$ccms['lang']['hints']['template']		= "Stilmall:: Om du använder flera mallar för din installation kan du tillsätta olika mallar för varje enskild sida genom denna drop-down meny.";
$ccms['lang']['hints']['activelink']	= "	
Aktiv länk i menyn? :: Punkter måste inte alltid ha en faktisk koppling. Du kan avaktivera länken bakom denna punkt i front-end-menyn genom att avmarkera kryssrutan nedan.";
$ccms['lang']['hints']['menuid']		= "Meny kategori: Välj i vilken meny denna punkt bör förtecknas i. Standardvärdet är huvudmenyn (1), där även hemsideslänken visas.";
$ccms['lang']['hints']['iscoding']		= "Innehåller kodning :: Inehåller denna fil exempelvis PHP eller Javascript? Om du väljer 'Ja' begränsas tillgången till filen från back-end WYSIWYG redigeraren och redaktören.";

// Editor messages
$ccms['lang']['editor']['closeeditor']	= "Stäng redigeraren";
$ccms['lang']['editor']['editorfor']	= "Text redigerare för";
$ccms['lang']['editor']['instruction']	= "Use the editor below to modify the current file. Once you're done, hit the 'Save changes' button below to directly publish your modifications to the world wide web. Also add up to ten relevant keywords for search engine optimalization.";
$ccms['lang']['editor']['savebtn']		= "Spara ändringar";
$ccms['lang']['editor']['cancelbtn'] 	= "Ångra";
$ccms['lang']['editor']['confirmclose'] = "Stäng detta fönster och kassera förändringar?";
$ccms['lang']['editor']['preview']		= "Förhandsgranska resultatsidan";
$ccms['lang']['editor']['savesuccess'] 	= "<strong>Framgång!</strong> Innehållet nedan har sparats till";
$ccms['lang']['editor']['backeditor'] 	= "Tillbaka till redigerare";
$ccms['lang']['editor']['closewindow'] 	= "Stäng fönstret";
$ccms['lang']['editor']['keywords']		= "Nyckelord - <em>åtskillda med kommatecken, max 250 tecken totalt</em>";

################### MODULES ###################

// Back-up messages
$ccms['lang']['backup']['createhd']		= "Create new back-up";
$ccms['lang']['backup']['explain']		= "To prevent possible loss of data due to whatever external event, it's wise to create back-ups of your files reguraly.";
$ccms['lang']['backup']['currenthd']	= "Available back-ups";
$ccms['lang']['backup']['timestamp']	= "Back-up file name";
$ccms['lang']['backup']['download']		= "Download archive";

// Album messages
$ccms['lang']['album']['album']			= "Album";
$ccms['lang']['album']['errordir']		= "The specified album name is too short (min. 4).";
$ccms['lang']['album']['newdircreated']	= "album directory has been created.";
$ccms['lang']['album']['renamed']		= "has been renamed to";
$ccms['lang']['album']['removed']		= "and all of its contents have been removed.";
$ccms['lang']['album']['refresh']		= "Refresh";
$ccms['lang']['album']['manage']		= "Manage albums";
$ccms['lang']['album']['albumlist']		= "Album list";
$ccms['lang']['album']['newalbum']		= "New album name";
$ccms['lang']['album']['noalbums']		= "No albums have been created yet!";
$ccms['lang']['album']['directory']		= "Directory (#)";
$ccms['lang']['album']['tooverview']	= "Return to overview";
$ccms['lang']['album']['nodir']			= "Please make sure the directory <strong>albums</strong> exists in your image directory";

// Guestbook message
$ccms['lang']['guestbook']['guestbook']	= "Guestbook";
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


      /* ### OBSOLETED ENTRIES ### */
      /*
         Please check the CompactCMS code to:

         a) make sure whether these entries are indeed obsoleted.
            When yes, then the corresponding entry above should be
            removed as well!

         b) When no, i.e. the entry exists in the code, this merits
            a bug report regarding the ./collect_lang_items.sh script.
       
         ----------------------------------------------------------
	
	$ccms['lang']['album']['directory']		
	$ccms['lang']['album']['errordir']		
	$ccms['lang']['album']['newdircreated']	
	$ccms['lang']['album']['nodir']			
	$ccms['lang']['album']['refresh']		
	$ccms['lang']['album']['removed']		
	$ccms['lang']['album']['renamed']		
	$ccms['lang']['album']['tooverview']	
	$ccms['lang']['backend']['startedittitle'] 	
	$ccms['lang']['backend']['updatelist']		
	$ccms['lang']['editor']['closeeditor']	
	$ccms['lang']['guestbook']['guestbook']	
	$ccms['lang']['guestbook']['posted']	
	$ccms['lang']['guestbook']['reaction']	
	$ccms['lang']['guestbook']['removed'] 	
	$ccms['lang']['login']['falsetries']		
	$ccms['lang']['login']['provide']			
	$ccms['lang']['system']['error_default']	
	$ccms['lang']['system']['error_sitemap'] 	
	$ccms['lang']['system']['tooriginal']		
       
         ----------------------------------------------------------
	
         ### MISSING ENTRIES ###

         The entries below have been found to be missing from this 
         translation file; move them from this comment section to the
         PHP code above and assign them a suitable text.

         When done so, you can of course remove them from the list 
         below.
       
         ----------------------------------------------------------
      */
	  
$ccms['lang']['album']['apply_to']		= "Specifically apply this album to";
$ccms['lang']['album']['browse']		= "Browse files";
$ccms['lang']['album']['clear']			= "Clear list";
$ccms['lang']['album']['currentalbums']	= "Current albums";
$ccms['lang']['album']['description']	= "Album description";
$ccms['lang']['album']['files']			= "Files";
$ccms['lang']['album']['lastmod']		= "Last modified";
$ccms['lang']['album']['please_wait'] 	= "Please wait ...";
$ccms['lang']['album']['regenalbumthumbs']	= "Regenerate all thumbnails";
$ccms['lang']['album']['settings']		= "Album settings";
$ccms['lang']['album']['singlefile']	= "<strong>Single file upload</strong><br/><p>The Flash loader failed to initialize. Make sure Javascript is enabled and Flash is installed. Single file uploads are possible, but not optimized.</p>";
$ccms['lang']['album']['toexisting']	= "Upload to existing album";
$ccms['lang']['album']['upload']		= "Start upload";
$ccms['lang']['album']['uploadcontent']	= "Upload content";
$ccms['lang']['auth']['featnotallowed']	= "Your current account level does not allow you to use this feature.";
$ccms['lang']['auth']['generatepass'] 	= "Auto generate a safe password";
$ccms['lang']['backend']['confirmthumbregen'] 	= "Please confirm that you want to regenerate all thumbnails.";
$ccms['lang']['backend']['contentowners']	= "Define content owners";
$ccms['lang']['backend']['fullregenerated']	= "Successfully regenerated the thumbnails.";
$ccms['lang']['backend']['fullremoved']		= "Successfully deleted the selected item(s).";
$ccms['lang']['backend']['itemcreated']		= "Successfully processed the submitted item(s).";
$ccms['lang']['backend']['must_refresh']	= "Please make sure to reload the main page to see <strong>all</strong> your changes";
$ccms['lang']['backend']['none']			= "None";
$ccms['lang']['backend']['permissions']		= "Set CCMS permissions";
$ccms['lang']['backend']['settingssaved']	= "Your changes have been successfully saved.";
$ccms['lang']['backend']['templateeditor']	= "Template editor";
$ccms['lang']['backend']['tooverview']		= "Back to overview";
$ccms['lang']['backend']['usermanagement']	= "User management";
$ccms['lang']['forms']['modifybutton'] 	= "Modify";
$ccms['lang']['forms']['setlocale']		= "Front-end language";
$ccms['lang']['news']['addnews']		= "Add news";
$ccms['lang']['news']['addnewslink']	= "Write new article";
$ccms['lang']['news']['author']			= "News author";
$ccms['lang']['news']['contents']		= "Article contents";
$ccms['lang']['news']['date']			= "Date";
$ccms['lang']['news']['manage']			= "Manage current news items";
$ccms['lang']['news']['numbermess']		= "# messages on front-end";
$ccms['lang']['news']['published']		= "Published?";
$ccms['lang']['news']['settings']		= "Manage settings";
$ccms['lang']['news']['showauthor']		= "Show author";
$ccms['lang']['news']['showdate']		= "Show publication date";
$ccms['lang']['news']['showteaser']		= "Only show teaser";
$ccms['lang']['news']['teaser']			= "Teaser";
$ccms['lang']['news']['title']			= "News title";
$ccms['lang']['news']['viewarchive']	= "View archive";
$ccms['lang']['news']['writenews']		= "Write news";
$ccms['lang']['owners']['explain']		= "Here you can appoint specific page ownership to individual users. If for a cartain page no users are selected, everyone can modify the page. Otherwise only the specified user had modification rights to the file. Administrators always have access to all files.";
$ccms['lang']['owners']['header']		= "Content owners";
$ccms['lang']['owners']['pages']		= "Pages";
$ccms['lang']['owners']['users']		= "Users";
$ccms['lang']['permission']['explain']	= "Use the table below to specify what minimum user level can use certain features. Any user below the specified minimum required user level, will not see nor have access to the feature.";
$ccms['lang']['permission']['header']	= "Permission preferences";
$ccms['lang']['permission']['level1']	= "Level 1 - User";
$ccms['lang']['permission']['level2']	= "Level 2 - Editor";
$ccms['lang']['permission']['level3']	= "Level 3 - Manager";
$ccms['lang']['permission']['level4']	= "Level 4 - Admin";
$ccms['lang']['permission']['target']	= "Target";
$ccms['lang']['system']['error_dirwrite']	= "Directory has no write access";
$ccms['lang']['system']['error_passnequal']	= "The entered passwords did not match";
$ccms['lang']['system']['error_passshort']	= "A password should contain more than 6 characters";
$ccms['lang']['system']['error_tooshort']	= "One or multiple submitted values were either too short or incorrect";
$ccms['lang']['system']['noresults']		= "No results";
$ccms['lang']['template']['manage']		= "Manage templates";
$ccms['lang']['template']['nowrite']	= "The current template is <strong>not</strong> writable";
$ccms['lang']['users']['accountcfg']	= "Account settings";
$ccms['lang']['users']['active']		= "Active";
$ccms['lang']['users']['cpassword']		= "Confirm password";
$ccms['lang']['users']['createuser']	= "Create a user";
$ccms['lang']['users']['editdetails']	= "Edit user's personal details";
$ccms['lang']['users']['editpassword']	= "Edit user's password";
$ccms['lang']['users']['email']			= "E-mail";
$ccms['lang']['users']['firstname']		= "First name";
$ccms['lang']['users']['lastlog']		= "Last log";
$ccms['lang']['users']['lastname']		= "Last name";
$ccms['lang']['users']['level']			= "Level";
$ccms['lang']['users']['name']			= "Name";
$ccms['lang']['users']['overviewusers']	= "Overview CCMS users";
$ccms['lang']['users']['password']		= "Password";
$ccms['lang']['users']['user']			= "User";
$ccms['lang']['users']['userlevel']		= "User level";
$ccms['lang']['users']['username']		= "Username";
       
      /*
         ----------------------------------------------------------
      */
	  
?>
