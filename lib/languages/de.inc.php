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

/* Translation by Dierk Bandow <dierk@dbb-web.de> */

// System wide messages
$ccms['lang']['system']['error_database'] 	= "Die Verbindung zur Datenbank konnte nicht hergestellt werden. Bitte die Login-Details und das Datenbank-Passwort überprüfen.";
$ccms['lang']['system']['error_openfile'] 	= "Kann die angegebene Datei nicht öffnen";
$ccms['lang']['system']['error_write'] 		= "Datei hat keine Schreibberechtigung";
$ccms['lang']['system']['error_chmod'] 		= "Die Datei kann nicht geändert werden. Überprüfen Sie die Dateiberechtigung (666).";
$ccms['lang']['system']['error_value'] 		= "Fehler: falsche Eingabe";
$ccms['lang']['system']['error_default']	= "Homepage kann nicht gelöscht werden.";
$ccms['lang']['system']['error_forged']		= "Wert nicht vorhanden";
$ccms['lang']['system']['error_filedots']	= "Dateinamen dürfen keine Punkte enthalten, z.B. '.html'.";
$ccms['lang']['system']['error_filesize']	= "Dateiname muss mind. 3 Zeichen lang sein.";
$ccms['lang']['system']['error_pagetitle']	= "Bitte einen Seitentitel von mind. 3 Zeichen eingeben.";
$ccms['lang']['system']['error_subtitle']	= "Bitte einen kurzen Untertitel für die Seite erstellen.";
$ccms['lang']['system']['error_description'] = "Die Beschreibung der Seite muss ist zu kurz";
$ccms['lang']['system']['error_reserved'] 	= "Der eingegebene Dateiname ist für den internen Gebrauch reserviert.";
$ccms['lang']['system']['error_general']	= "Ein Fehler ist aufgetreten";
$ccms['lang']['system']['error_correct'] 	= "Bitte folgendes berichtigen:";
$ccms['lang']['system']['error_create'] 	= "Fehler bei der Erstellung einer neuen Datei";
$ccms['lang']['system']['error_exists'] 	= "Der Dateiname besteht bereits.";
$ccms['lang']['system']['error_delete']		= "Fehler beim Löschen der ausgewählten Datei";
$ccms['lang']['system']['error_selection'] 	= "Es wurde keine Datei ausgewählt.";
$ccms['lang']['system']['error_versioninfo'] = "Keine Versionsinformationen verfügbar.";
$ccms['lang']['system']['error_misconfig']	= "<strong>Es scheint ein Fehler bei der Konfiguration vorzuliegen.</strong><br/>Die .htaccess Datei muss korrekt konfiguriert sein um die Dateistruktur wiederzugeben. Wenn<br/>CompactCMS in ein Unterverzeichnis installiert wurde, muss 
die .htaccess Datei dementsprechend angepasst werden.";
$ccms['lang']['system']['error_deleted']	= "<h1>Die ausgew&auml;hlte Datei schein gel&oouml;scht worden zu sein</h1><p>Um diesen Fehler zu vermeiden, 

Dateiliste erneut aufrufen um die Liste der letzten verf&uuml;gbaren Dateien anzuzeigen. Falls dieses Vorgehen das Problem nicht löst, den Ordner per Hand öffnen und nachsehen, ob die Datei überhaubt existiert.</p>";
$ccms['lang']['system']['error_404title'] 	= "Datei nicht gefunden";
$ccms['lang']['system']['error_404header']	= "Ein 404 Fehler ist aufgetreten, die angeforderte Datei konnte nicht gefunden werden.";
$ccms['lang']['system']['error_sitemap'] 	= "Seitenübersicht";
$ccms['lang']['system']['tooriginal']		= "zurück zum Original";
$ccms['lang']['system']['message_rights'] 	= "Alle Rechte vorbehalten";
$ccms['lang']['system']['message_compatible'] = "Erfolgreich getestet mit";
$ccms['lang']['system']['error_notemplate']	= "Es stehen noch keine Templates zur Verfügung.  mindestens 1 Template nach ./lib/templates/ hinzufügen.";

// Administration general messages
$ccms['lang']['backend']['gethelp'] 		= "Bei Vorschl&auml;gen, Feedback oder Schwierigkeiten  <a href=\"http://community.compactcms.nl/forum/\" title=\"Das offizielle CCMS-forum\" class=\"external\" rel=\"external\">gehe zum Forum</a>!";
$ccms['lang']['backend']['ordertip'] 		= "Drop-downs benutzen, um die Struktur der Seite im Menü wiederzugeben.";
$ccms['lang']['backend']['createtip'] 		= "Um eine neue Seite zu erstellen, sind die Felder auszufüllen. Nach der Erstellung der Seite kann diese wie üblich bearbeitet werden.";
$ccms['lang']['backend']['currentfiles'] 	= "In der Auflistung unten sind alle erstellten Seiten zu finden. Die Startseite kann grundsätzlich nicht gelöscht werden. Einige Seiten können nur vom Administrator verändert werden, da dieser allein die Berechtigungen vergibt.";
$ccms['lang']['backend']['confirmdelete'] 	= "Bitte bestätigen, dass alle Seiten und der gesamte Inhalt gelöscht werden sollen.";
$ccms['lang']['backend']['changevalue']		= "Änderung bestätigen";
$ccms['lang']['backend']['previewpage']		= "Ansicht";
$ccms['lang']['backend']['editpage']		= "Bearbeiten";
$ccms['lang']['backend']['restrictpage'] 	= "Eingeschränkt";
$ccms['lang']['backend']['newfiledone'] 	= "Diese Datei ist bereit mit Inhalt gefüllt zu werden!";
$ccms['lang']['backend']['newfilecreated']	= "Die Datei wurde angelegt!";
$ccms['lang']['backend']['startedittitle'] 	= "Mit der Eingabe beginnen!";
$ccms['lang']['backend']['starteditbody']	= "Die neue Datei wurde angelegt. Entweder sofort mit der Bearbeitung beginnen oder weitere Seiten hinzufügen.";
$ccms['lang']['backend']['success'] 		= "Erfolg!";
$ccms['lang']['backend']['fileexists'] 		= "Datei besteht bereits";
$ccms['lang']['backend']['statusdelete'] 	= "Status des Löschvorgangs:";
$ccms['lang']['backend']['statusremoved']	= "entfernt";
$ccms['lang']['backend']['uptodate']		= "aktuell.";
$ccms['lang']['backend']['outofdate']		= "veraltet.";
$ccms['lang']['backend']['considerupdate'] 	= "Update erwägen";
$ccms['lang']['backend']['orderprefsaved'] 	= "Die Reihenfolge der Menüpunkte wurde gespeichert.";
$ccms['lang']['backend']['inmenu']			= "Im Menü";
$ccms['lang']['backend']['updatelist']		= "Item";
$ccms['lang']['backend']['administration']	= "Administraton";
$ccms['lang']['backend']['currentversion']	= "Die verwendete Version is";
$ccms['lang']['backend']['mostrecent']		= "Die letzte stabile CompactCMS Version ist";
$ccms['lang']['backend']['versionstatus'] 	= "Diese Installation ist";
$ccms['lang']['backend']['createpage']		= "Seite hinzufügen";
$ccms['lang']['backend']['managemenu']		= "Menü bearbeiten";
$ccms['lang']['backend']['managefiles'] 	= "Seiten verwalten";
$ccms['lang']['backend']['delete'] 			= "Entfernen";
$ccms['lang']['backend']['toplevel']		= "Top Level";
$ccms['lang']['backend']['sublevel'] 		= "Sub Level";
$ccms['lang']['backend']['active']			= "Aktiv";
$ccms['lang']['backend']['disabled']		= "Ausgeschaltet";
$ccms['lang']['backend']['template']		= "Template";
$ccms['lang']['backend']['notinmenu']		= "Punkt erscheint nicht im Menü";
$ccms['lang']['backend']['menutitle']		= "Menü";
$ccms['lang']['backend']['linktitle']		= "Link";
$ccms['lang']['backend']['item']			= "Item";
$ccms['lang']['backend']['yes']				= "Ja";
$ccms['lang']['backend']['no']				= "Nein";

// Texts for authentication screen
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
$ccms['lang']['forms']['filename']		= "Dateiname"; 
$ccms['lang']['forms']['pagetitle']		= "Seitentitel";
$ccms['lang']['forms']['subheader'] 	= "Subheader";
$ccms['lang']['forms']['description'] 	= "Beschreibung";
$ccms['lang']['forms']['module'] 		= "Module";
$ccms['lang']['forms']['contentitem']	= "Content item (default)";
$ccms['lang']['forms']['additions']		= "Additions";
$ccms['lang']['forms']['printable'] 	= "Druckbar";
$ccms['lang']['forms']['published'] 	= "Aktiv";
$ccms['lang']['forms']['iscoding'] 		= "Code";
$ccms['lang']['forms']['createbutton'] 	= "Erstellen!";
$ccms['lang']['forms']['savebutton'] 	= "Speichern";

// Administration hints for form fields
$ccms['lang']['hints']['filename']		= "Dateiname (home.html) :: Dateiname, mit dem die Datei aufgerufen wird (ohne .html)";
$ccms['lang']['hints']['pagetitle'] 	= "Seitentitel (Home) :: Titel dieser Seite.";
$ccms['lang']['hints']['subheader']		= "Kurzer Headertext (Willkommen auf unserer Homepage) :: Kurzer beschreibender Text, der sowohl im Header als auch im Titel jeder Seite erscheint.";
$ccms['lang']['hints']['description']	= "Meta description :: Meta description f&uuml;r diese Seite. Wird in der Seitenspezifischen 'meta description' benutzt.";
$ccms['lang']['hints']['module']		= "Module :: Select what module should handle the content of this file. If you are unsure, select the default.";
$ccms['lang']['hints']['printable']		= "Druckfreundliche Seite :: Bei 'Ja' wird eine druckfreundliche Seite generiert. 'Nein' sollte bei Seiten mit Fotos und/oder anderen Medien ausgewählt werden.";
$ccms['lang']['hints']['published']		= "Veröffentlicht :: Diesen Punkt auswählen, wenn die Seite veröffentlicht werden soll. Sie ist dann für die Öffentlichkeit sichtbar.";
$ccms['lang']['hints']['toplevel']		= "Top Level :: Setzt die Seite im Menü an die erste Stelle. N.I.M. auswählen, wenn die Seite nicht im Menü erscheinen soll.";
$ccms['lang']['hints']['sublevel']		= "Sub Level :: 0 auswählen, wenn die Seite als 'Top Level' Seite erscheinen soll. Sonst den entsprechenden Untermenüpunkt auswählen.";
$ccms['lang']['hints']['template']		= "Kategorie :: Bei der Verwendung mehrerer Templates können hier die Templates den einzelnen Seiten zugeordnet werden.";
$ccms['lang']['hints']['activelink']	= "Aktiver Link im menu? :: Nicht alle Menüpunkte benötigen einen aktiven Link (versteckte Seiten). Um den Link zu deaktivieren, muss der Haken aus der Checkbox entfernt werden.";
$ccms['lang']['hints']['menuid']		= "Menü Kategorie :: Hier auswählen, in welchem Menü der Menüpunkt erscheinen soll. Standard ist das Hauptmenü (1), in dem auch die Startseite erscheint.";
$ccms['lang']['hints']['iscoding']		= "Code :: Soll die Datei Code (PHP, Javascript) enthalten, 'Ja' ausählen. Der WISYWIG-Editor wird ausgeschaltet.";

// Editor messages
$ccms['lang']['editor']['closeeditor']	= "Editor schließen";
$ccms['lang']['editor']['editorfor']	= "Texteditor für";
$ccms['lang']['editor']['instruction']	= "Editor zum ändern der Seite. Nach der Änderung auf 'Ändern' klicken um die Seite sofort im www zu publizieren.";
$ccms['lang']['editor']['savebtn']		= "Änderung übernehmen";
$ccms['lang']['editor']['cancelbtn'] 	= "Abbrechen";
$ccms['lang']['editor']['confirmclose'] = "Fenster schließen und Änderungen verwerfen?";
$ccms['lang']['editor']['preview']		= "Voransicht";
$ccms['lang']['editor']['savesuccess'] 	= "<strong>Erfolg!</strong> Der Inhalt wurde Gespeichert nach";
$ccms['lang']['editor']['backeditor'] 	= "Zurück zum Editor";
$ccms['lang']['editor']['closewindow'] 	= "Fenster schließen";
$ccms['lang']['editor']['keywords']		= "Keywords - <em>getrennt durch Kommata, maximaal 250 Zeichen</em>";

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
$ccms['lang']['login']['welcome']			= "<p>Use a valid username and password to gain access to the CompactCMS back-end. If you arrived here by mistake, return to the <a href='../../'>home page</a>.</p><p>Contact your webmaster for your details.</p>";
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
$ccms['lang']['system']['error_templatedir'] = "Couldn't find the templates directory! Make sure it exists and contains at least one template.";
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
