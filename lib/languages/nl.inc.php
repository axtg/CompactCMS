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

// System wide error messages
$ccms['lang']['system']['error_database'] 	= "Kon geen verbinding maken met de database. Controleer login- en databasegegevens.";
$ccms['lang']['system']['error_openfile'] 	= "Kon het aangegeven bestand niet openen";
$ccms['lang']['system']['error_notemplate']	= "Er zijn momenteel geen templates beschikbaar. Voeg minstens 1 template toe in de ./lib/templates/ map.";
$ccms['lang']['system']['error_templatedir'] = "Kon de template map niet vinden! Controleer of de map bestaat en voeg tenminste 1 template toe.";
$ccms['lang']['system']['error_write'] 		= "Geen schrijfrechten tot het bestand";
$ccms['lang']['system']['error_dirwrite']	= "Directory has no write access";
$ccms['lang']['system']['error_chmod'] 		= "Het huidige bestand kon niet aangepast worden. Controleer de schrijfrechten op de bestanden in de /content map (666).";
$ccms['lang']['system']['error_value'] 		= "Fout: waarde onjuist";
$ccms['lang']['system']['error_default'] 	= "De standaardpagina kan niet verwijderd worden.";
$ccms['lang']['system']['error_forged'] 	= "Waarde is verstoord";
$ccms['lang']['system']['error_filedots'] 	= "De bestandsnaam kan geen punten bevatten, bijv. '.html'.";
$ccms['lang']['system']['error_filesize'] 	= "De bestandsnaam moet minimaal 3 karakters lang zijn.";
$ccms['lang']['system']['error_pagetitle'] 	= "Geef een titel van meer dan 3 karakters op.";
$ccms['lang']['system']['error_subtitle'] 	= "Specificeer een korte sub-koptekst.";
$ccms['lang']['system']['error_description'] = "De huidige beschrijving is te kort";
$ccms['lang']['system']['error_reserved'] 	= "De gekozen bestandsnaam is gereserveerd voor intern gebruik.";
$ccms['lang']['system']['error_general'] 	= "Er deed zich een fout voor";
$ccms['lang']['system']['error_correct'] 	= "Corrigeer het volgende:";
$ccms['lang']['system']['error_create'] 	= "Fout tijdens het aanmaken van het bestand";
$ccms['lang']['system']['error_exists'] 	= "De opgegeven bestandsnaam bestaat al.";
$ccms['lang']['system']['error_delete'] 	= "Fout tijdens het verwijderen van de opgegeven pagina";
$ccms['lang']['system']['error_selection'] 	= "Geen enkel bestand geselecteerd.";
$ccms['lang']['system']['error_versioninfo'] = "Geen versie informatie beschikbaar.";
$ccms['lang']['system']['error_misconfig'] 	= "<strong>Onjuiste configuratie aangetroffen.</strong><br/>Controleer of het .htaccess bestand correct is geconfigureerd aan de huidige mapstructuur. Als CompactCMS in<br/>een submap is geinstalleerd, dan dient het .htaccess bestand daarop aangepast te worden.";
$ccms['lang']['system']['error_deleted']	= "<h1>Het geselecteerd bestand lijkt al verwijderd</h1><p>Vernieuw de pagina lijst om het meest recente overzicht te zien en zo deze foutmelding te voorkomen. Als dit het probleem niet oplost, controleer dan handmating of het desbetreffende bestand &uuml;berhaupt in de content map bestaat.</p>";
$ccms['lang']['system']['error_404title'] 	= "Bestand niet gevonden";
$ccms['lang']['system']['error_404header'] 	= "Er deed zich een 404 fout voor, de pagina werd niet gevonden.";
$ccms['lang']['system']['error_sitemap'] 	= "Een overzicht van alle pagina's";
$ccms['lang']['system']['error_tooshort']	= "Een of meerdere waardes waren te kort of onjuist";
$ccms['lang']['system']['error_passshort']	= "Een wachtwoord dient uit minstens 6 karakters te bestaan";
$ccms['lang']['system']['error_passnequal']	= "De opgegeven wachtwoorden kwamen niet overeen";
$ccms['lang']['system']['noresults']		= "Geen resultaten";
$ccms['lang']['system']['tooriginal']		= "Terug naar origineel";
$ccms['lang']['system']['message_rights'] 	= "Alle rechten voorbehouden";
$ccms['lang']['system']['message_compatible'] = "Succesvol getest op";

// Administration general messages
$ccms['lang']['backend']['gethelp'] 		= "Heb je suggesties, feedback of problemen? Bezoek dan <a href=\"http://community.compactcms.nl/forum/\" title=\"Bezoek het offici&euml;le forum\" class=\"external\" rel=\"external\">het forum</a>!";
$ccms['lang']['backend']['ordertip'] 		= "Gebruik de 'drop-downs' hieronder om de structuur van de pagina's in het menu te verwerken. Let op dat het systeem geen rekening houdt met gelijke top- en subniveau's combinaties.";
$ccms['lang']['backend']['createtip'] 		= "Vul onderstaand formulier in om direct een nieuwe pagina aan te maken. Nadat het bestand is aangemaakt, kan deze direct eenvoudig bijgewerkt worden.";
$ccms['lang']['backend']['currentfiles'] 	= "In de lijst hieronder staan alle huidige pagina's weergegeven. De standaard pagina kan niet worden verwijderd omdat dit de startpagina van de website is. Andere pagina's kunnen afgeschermde content hebben omdat alleen de beheerder het recht heeft deze bewerken.";
$ccms['lang']['backend']['confirmdelete'] 	= "Bevestig het verwijderen van de aangegeven item(s).";
$ccms['lang']['backend']['settingssaved']	= "De gemaakte aanpassingen zijn opgeslagen.";
$ccms['lang']['backend']['itemcreated']		= "Succesvolle verwerking van item(s).";
$ccms['lang']['backend']['fullremoved']		= "Succesvolle verwijdering van geselecteerde item(s).";
$ccms['lang']['backend']['tooverview']		= "Terug naar overzicht";
$ccms['lang']['backend']['permissions']		= "Definieer CCMS rechten";
$ccms['lang']['backend']['contentowners']	= "Bepaal content eigenaren";
$ccms['lang']['backend']['templateeditor']	= "Bewerk templates";
$ccms['lang']['backend']['usermanagement']	= "Beheer gebruikers";
$ccms['lang']['backend']['changevalue'] 	= "Klik om aan te passen";
$ccms['lang']['backend']['previewpage'] 	= "Bekijk";
$ccms['lang']['backend']['editpage'] 		= "Beheer";
$ccms['lang']['backend']['restrictpage'] 	= "Afgeschermd";
$ccms['lang']['backend']['newfiledone'] 	= "Dit bestand is in afwachting om opgevuld te worden!";
$ccms['lang']['backend']['newfilecreated'] 	= "Het bestand is aangemaakt";
$ccms['lang']['backend']['startedittitle'] 	= "Pas direct aan!";
$ccms['lang']['backend']['starteditbody'] 	= "Het nieuwe bestand is aangemaakt. Pas het direct aan of voeg meer nieuwe pagina's toe, &ouml;f beheer de huidige.";
$ccms['lang']['backend']['success'] 		= "Gelukt!";
$ccms['lang']['backend']['fileexists'] 		= "Bestand bestaat";
$ccms['lang']['backend']['statusdelete'] 	= "Voortgang van verwijdering:";
$ccms['lang']['backend']['statusremoved'] 	= "verwijderd";
$ccms['lang']['backend']['uptodate'] 		= "up to date.";
$ccms['lang']['backend']['outofdate'] 		= "verouderd.";
$ccms['lang']['backend']['considerupdate'] 	= "Overweeg bij te werken";
$ccms['lang']['backend']['orderprefsaved'] 	= "Je voorkeur voor de volgorde van de menu items is opgeslagen.";
$ccms['lang']['backend']['inmenu'] 			= "In menu";
$ccms['lang']['backend']['updatelist'] 		= "Ververs paginalijst";
$ccms['lang']['backend']['administration'] 	= "Administratie";
$ccms['lang']['backend']['currentversion'] 	= "Je gebruikt momenteel versie";
$ccms['lang']['backend']['mostrecent'] 		= "De meest recente stabiele versie van CompactCMS is";
$ccms['lang']['backend']['versionstatus'] 	= "De installatie is";
$ccms['lang']['backend']['createpage'] 		= "Maak nieuwe pagina aan";
$ccms['lang']['backend']['managemenu'] 		= "Menu beheren";
$ccms['lang']['backend']['managefiles'] 	= "Beheer huidige pagina's";
$ccms['lang']['backend']['delete'] 			= "Verwijder";
$ccms['lang']['backend']['toplevel'] 		= "1ste level";
$ccms['lang']['backend']['sublevel'] 		= "2e level";
$ccms['lang']['backend']['active'] 			= "Actief";
$ccms['lang']['backend']['disabled'] 		= "Inactief";
$ccms['lang']['backend']['template'] 		= "Template";
$ccms['lang']['backend']['notinmenu'] 		= "Item niet in een menu";
$ccms['lang']['backend']['menutitle'] 		= "Menu";
$ccms['lang']['backend']['linktitle'] 		= "Link";
$ccms['lang']['backend']['item'] 			= "Item";
$ccms['lang']['backend']['none']			= "Geen";
$ccms['lang']['backend']['yes'] 			= "Ja";
$ccms['lang']['backend']['no'] 				= "Nee";
$ccms['lang']['backend']['confirmthumbregen'] 	= "Bevestig a.u.b. dat u alle mini-afbeeldingen wilt regeneren. Dit kan even duren.";
$ccms['lang']['backend']['fullregenerated']	= "De mini-afbeeldingen zijn volledig geregenereerd.";
$ccms['lang']['backend']['must_refresh']	= "Merk op dat het verstandig is om de admin pagina te herladen (functietoets F5) om <em>alle</em> wijzigingen in de rechten direct terug te zien!";

// Texts for authentication screen
$ccms['lang']['login']['welcome']			= "<p>Gebruik een geldige gebruikersnaam en wachtwoord om in te loggen op CompactCMS. Indien u hier per abuis belandde, keer dan terug naar de <a href='../../'>start pagina</a>.</p><p>E-mail de webmaster voor uw gegevens.</p>";
$ccms['lang']['login']['username']			= "Gebruikersnaam";
$ccms['lang']['login']['password']			= "Wachtwoord";
$ccms['lang']['login']['login']				= "Login";
$ccms['lang']['login']['provide']			= "Geef uw gebruikers gegevens op";
$ccms['lang']['login']['nodetails']			= "Vul zowel gebruikersnaam als wachtwoord in";
$ccms['lang']['login']['nouser']			= "Geef uw gebruikersnaam op";
$ccms['lang']['login']['nopass']			= "Geef uw wachtwoord op";
$ccms['lang']['login']['notactive']			= "Dit account is ge-deactiveerd";
$ccms['lang']['login']['falsetries']		= "Let op: u deed al meerdere onjuiste pogingen";
$ccms['lang']['login']['nomatch']			= "Onjuiste gebruikersnaam of wachtwoord";

// Menu titles for administration back-end
$ccms['lang']['menu']['1']				= "Main";
$ccms['lang']['menu']['2']				= "Links";
$ccms['lang']['menu']['3']				= "Rechts";
$ccms['lang']['menu']['4']				= "Footer";
$ccms['lang']['menu']['5']				= "Extra";

// Administration form related texts
$ccms['lang']['forms']['filename'] 		= "Bestand";
$ccms['lang']['forms']['pagetitle'] 	= "Pagina titel";
$ccms['lang']['forms']['subheader'] 	= "Sub-koptekst";
$ccms['lang']['forms']['description'] 	= "Beschrijving";
$ccms['lang']['forms']['module'] 		= "Module";
$ccms['lang']['forms']['contentitem']	= "Content item (standaard)";
$ccms['lang']['forms']['additions']		= "Extra modules";
$ccms['lang']['forms']['printable'] 	= "Printbaar";
$ccms['lang']['forms']['published'] 	= "Actief";
$ccms['lang']['forms']['iscoding'] 		= "Code";
$ccms['lang']['forms']['createbutton'] 	= "Aanmaken!";
$ccms['lang']['forms']['modifybutton'] 	= "Aanpassen";
$ccms['lang']['forms']['savebutton'] 	= "Opslaan";
$ccms['lang']['forms']['setlocale']		= "Front-end taalvoorkeur";

// Administration hints for form fields
$ccms['lang']['hints']['filename'] 		= "De bestandsnaam (home.html) :: De bestandsnaam waaronder deze pagina aan te roepen is (zonder .html)";
$ccms['lang']['hints']['pagetitle'] 	= "Titel voor deze pagina (Home) :: Een beschrijvende titel voor deze pagina.";
$ccms['lang']['hints']['subheader'] 	= "Koptekst (Welkom op de startpagina) :: Een korte beschrijving die gebruikt wordt als header en verschijnt in de titel tag van iedere pagina.";
$ccms['lang']['hints']['description'] 	= "Meta beschrijving :: Een unieke beschrijving voor deze pagina die gebruikt wordt als de pagina's 'meta description'.";
$ccms['lang']['hints']['module']		= "Module :: Selecteer welke module de inhoud van deze pagina moet beheren. Kies bij twijfel het standaard item.";
$ccms['lang']['hints']['printable'] 	= "Printbaarheid pagina :: Selecteer 'JA' om automatisch een printbare versie te genereren. Kies 'NEE' als deze pagina's afbeeldingen of andere media bevat. Een printbare versie hiervoor is vaak onbruikbaar.";
$ccms['lang']['hints']['published'] 	= "Publicatie status :: Kies of deze pagina gepubliceerd moet worden, of de pagina in de sitemap meegenomen wordt en of deze toegankelijk is voor bezoekers.";
$ccms['lang']['hints']['toplevel'] 		= "Top niveau :: Specificeer het gewenste top niveau voor dit menu item.";
$ccms['lang']['hints']['sublevel'] 		= "Sub niveau :: Selecteer 0 wanneer dit item zelf een top item is. Als dit item als sub item toebehoort aan een bestaand top item, kies dan het gewenste sub niveau.";
$ccms['lang']['hints']['template'] 		= "Template :: Indien de website meerdere templates gebruikt, dan kunnen de pagina's ieder een eigen template toegewezen krijgen met behulp van onderstaande drop-downs.";
$ccms['lang']['hints']['activelink'] 	= "Actieve link in menu? :: Niet alle items hoeven altijd een link te zijn in het menu. Om de verwijzing (link) voor dit item uit te schakelen, dient de checkbox ge-deselecteerd te worden.";
$ccms['lang']['hints']['menuid'] 		= "Menu categorie :: Kies in welk menu dit item opgenomen en getoond wordt. Standaard is dit main (1) waar ook de start pagina link onder hoort.";
$ccms['lang']['hints']['iscoding'] 		= "Bevat programmeertaal :: Bevat dit bestand handmatig toegevoegde code zoals PHP of Javascript? Door 'Ja' te selecteren wordt toegang tot het bestand via de WYSIWYG editor stopgezet en schakelt de code editor in.";

// Editor messages
$ccms['lang']['editor']['closeeditor'] 	= "Sluit de editor";
$ccms['lang']['editor']['editorfor'] 	= "Tekst editor voor";
$ccms['lang']['editor']['instruction'] 	= "Gebruik de editor hieronder om de huidige inhoud te wijzigen. Klaar? Klik dan op de button \"Aanpassingen opslaan\" om direct de aanpassingen door te voeren op het world wide web. Voeg ook tot tien relevante keywords toe voor zoekmachine-optimalisatie.";
$ccms['lang']['editor']['savebtn'] 		= "Aanpassingen opslaan";
$ccms['lang']['editor']['cancelbtn'] 	= "Annuleer";
$ccms['lang']['editor']['confirmclose'] = "Het venster sluiten en wijzigingen annuleren?";
$ccms['lang']['editor']['preview'] 		= "Bekijk het resultaat";
$ccms['lang']['editor']['savesuccess'] 	= "<strong>Gelukt!</strong> De onderstaande inhoud is opgeslagen in ";
$ccms['lang']['editor']['backeditor'] 	= "Terug naar de editor";
$ccms['lang']['editor']['closewindow'] 	= "Sluit venster";
$ccms['lang']['editor']['keywords'] 	= "Keywords - <em>gescheiden door komma's, maximaal 250 karakters</em>";

// Authorization messages
$ccms['lang']['auth']['generatepass'] 	= "Genereer een veilig wachtwoord";
$ccms['lang']['auth']['featnotallowed']	= "Het huidige accountlevel heeft geen toegang tot dit onderdeel.";

################### MODULES ###################

// Back-up messages
$ccms['lang']['backup']['createhd']		= "Cre&euml;er nieuwe back-up";
$ccms['lang']['backup']['explain']		= "Om mogelijk verlies van data door externe omstandigheden te voorkomen, is het verstandig om regelmatig back-ups te maken.";
$ccms['lang']['backup']['currenthd']	= "Beschikbare back-ups";
$ccms['lang']['backup']['timestamp']	= "Back-up bestandsnaam";
$ccms['lang']['backup']['download']		= "Download archief";

// User management messages
$ccms['lang']['users']['createuser']	= "Gebruiker aanmaken";
$ccms['lang']['users']['overviewusers']	= "Overzicht CCMS gebruikers";
$ccms['lang']['users']['editdetails']	= "Persoonlijke gegevens aanpassen";
$ccms['lang']['users']['editpassword']	= "Wachtwoord aanpassen";
$ccms['lang']['users']['accountcfg']	= "Account instellingen";
$ccms['lang']['users']['user']			= "Gebruiker";
$ccms['lang']['users']['username']		= "Gebruikersnaam";
$ccms['lang']['users']['name']			= "Naam";
$ccms['lang']['users']['firstname']		= "Voornaam";
$ccms['lang']['users']['lastname']		= "Achternaam";
$ccms['lang']['users']['password']		= "Wachtwoord";
$ccms['lang']['users']['cpassword']		= "Bevestig wachtwoord";
$ccms['lang']['users']['email']			= "E-mail";
$ccms['lang']['users']['active']		= "Actief";
$ccms['lang']['users']['level']			= "Niveau";
$ccms['lang']['users']['userlevel']		= "Gebruikersniveau";
$ccms['lang']['users']['lastlog']		= "Laatste log";

// Template editor
$ccms['lang']['template']['manage']		= "Beheer templates";
$ccms['lang']['template']['nowrite']	= "Het huidige template is <strong>niet</strong> beschrijfbaar";

// Permissions
$ccms['lang']['permission']['header']	= "Toegangsvoorkeuren";
$ccms['lang']['permission']['explain']	= "Gebruik onderstaande tabel om het minimale gebruikersniveau voor een bepaalde functie te bepalen. Een gebruiker onder het gespecificeerde niveau heeft geen toegang tot de desbetreffende functie.";
$ccms['lang']['permission']['target']	= "Doel";
$ccms['lang']['permission']['level1']	= "Level 1 - User";
$ccms['lang']['permission']['level2']	= "Level 2 - Editor";
$ccms['lang']['permission']['level3']	= "Level 3 - Manager";
$ccms['lang']['permission']['level4']	= "Level 4 - Admin";

// Content owners
$ccms['lang']['owners']['header']		= "Content eigenaren";
$ccms['lang']['owners']['explain']		= "Hier kunnen content eigenaren gespecificeerd worden. Als voor &eacute;&eacute;n pagina geen gebruikers zijn geselecteerd, dan hebben alle gebruikers toegang tot de pagina. Anders enkel de geselecteerde gebruiker. Beheerders hebben altijd toegang tot alle pagina's";
$ccms['lang']['owners']['pages']		= "Pagina's";
$ccms['lang']['owners']['users']		= "Gebruikers";

// Album messages
$ccms['lang']['album']['album']			= "Album";
$ccms['lang']['album']['currentalbums']	= "Huidige albums";
$ccms['lang']['album']['uploadcontent']	= "Upload content";
$ccms['lang']['album']['toexisting']	= "Upload naar huidig album";
$ccms['lang']['album']['upload']		= "Start upload";
$ccms['lang']['album']['browse']		= "Kies foto's";
$ccms['lang']['album']['clear']			= "Leeg lijst";
$ccms['lang']['album']['singlefile']	= "<strong>Enkele file upload</strong><br/><p>De Flash lader kon niet laden. Zorg dat Javascript toegestaan is en Flash is geinstalleerd. Enkele uploads zijn mogelijk, maar niet geoptimaliseerd.</p>";
$ccms['lang']['album']['manage']		= "Beheer album";
$ccms['lang']['album']['albumlist']		= "Albumlijst";
$ccms['lang']['album']['newalbum']		= "Cre&euml;er nieuw album";
$ccms['lang']['album']['noalbums']		= "Er zijn nog geen albums aangemaakt!";
$ccms['lang']['album']['files']			= "Bestanden";
$ccms['lang']['album']['nodir']			= "Bevestig dat de map <strong>albums</strong> bestaat in de ./media/ map";
$ccms['lang']['album']['lastmod']		= "Laatste update";
$ccms['lang']['album']['apply_to']		= "Hang dit album aan de volgende index pagina:";
$ccms['lang']['album']['description']	= "Album beschrijving";
$ccms['lang']['album']['please_wait'] 	= "Even geduld, a.u.b. ...";
$ccms['lang']['album']['regenalbumthumbs']	= "Regenereer alle mini-afbeeldingen";
$ccms['lang']['album']['settings']		= "Album instellingen";

// News messages
$ccms['lang']['news']['manage']			= "Beheer huidige nieuwsitems";
$ccms['lang']['news']['addnews']		= "Voeg nieuws toe";
$ccms['lang']['news']['addnewslink']	= "Schrijf nieuw artikel";
$ccms['lang']['news']['settings']		= "Beheer instellingen";
$ccms['lang']['news']['writenews']		= "Schrijf nieuws";
$ccms['lang']['news']['numbermess']		= "# berichten op front-end";
$ccms['lang']['news']['showauthor']		= "Toon auteur";
$ccms['lang']['news']['showdate']		= "Toon publicatiedatum";
$ccms['lang']['news']['showteaser']		= "Toon enkel ankeiler";
$ccms['lang']['news']['title']			= "Titel";
$ccms['lang']['news']['author']			= "Auteur";
$ccms['lang']['news']['date']			= "Datum";
$ccms['lang']['news']['published']		= "Publiceren?";
$ccms['lang']['news']['teaser']			= "Ankeiler";
$ccms['lang']['news']['contents']		= "Inhoud artikel";
$ccms['lang']['news']['viewarchive']	= "Bekijk archief";

// Guestbook message
$ccms['lang']['guestbook']['noposts']	= "Er zijn nog geen reacties geplaatst!";
$ccms['lang']['guestbook']['verinstr']	= "Herhaal de volgende getallen ter controle van automatische reacties";
$ccms['lang']['guestbook']['reaction']	= "Reactie";
$ccms['lang']['guestbook']['rating']	= "Beoordeling";
$ccms['lang']['guestbook']['avatar']	= "Gravatar.com gebruikersavatar";
$ccms['lang']['guestbook']['wrote']		= "schreef";
$ccms['lang']['guestbook']['manage']	= "Beheer reacties";
$ccms['lang']['guestbook']['delentry']	= "Verwijder deze post";
$ccms['lang']['guestbook']['sendmail']	= "E-mail auteur";
$ccms['lang']['guestbook']['removed'] 	= "is verwijderd uit de database.";
$ccms['lang']['guestbook']['name'] 		= "Jouw naam";
$ccms['lang']['guestbook']['email']		= "Jouw e-mail";
$ccms['lang']['guestbook']['website']	= "Jouw website";
$ccms['lang']['guestbook']['comments']	= "Commentaar";
$ccms['lang']['guestbook']['verify']	= "Verificatie";
$ccms['lang']['guestbook']['preview']	= "Preview reactie";
$ccms['lang']['guestbook']['add']		= "Voeg jouw reactie toe";
$ccms['lang']['guestbook']['posted']	= "Je reactie is geplaatst!";
$ccms['lang']['guestbook']['error']		= "Fouten &amp; afwijzingen";
$ccms['lang']['guestbook']['rejected']	= "Jouw bijdrage is helaas afgewezen.";
$ccms['lang']['guestbook']['success']	= "Dank je wel";


      /* ### OBSOLETED ENTRIES ### */
      /*
         Please check the CompactCMS code to:

         a) make sure whether these entries are indeed obsoleted.
            When yes, then the corresponding entry above should be
            removed as well!

         b) When no, i.e. the entry exists in the code, this merits
            a bug report regarding the ./collect_lang_items.sh script.
       
         ----------------------------------------------------------
	
	$ccms['lang']['album']['nodir']			
	$ccms['lang']['backend']['fileexists'] 		
	$ccms['lang']['backend']['startedittitle'] 	
	$ccms['lang']['backend']['updatelist'] 		
	$ccms['lang']['editor']['closeeditor'] 	
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
	  
$ccms['lang']['forms']['add']           = "Add filter for";
$ccms['lang']['forms']['edit_remove']   = "Edit or remove filter for";
$ccms['lang']['forms']['filter_showing']	= "right now we're only showing pages which have at least this text in here";
$ccms['lang']['hints']['filter']        = "<br>You can click on the <span class='sprite livefilter livefilter_active'>&#160;filter icon</span> at left of the title to add, edit or remove a text to filter the page list on, e.g. when you type 'home' in the edit field which appears when you click the icon, then press the Enter/Return key, only pages which have the text 'home' in this column will be shown. <br>Clicking the icon again and deleting the text in the edit field, then pressing the Enter/Return key, will remove the filter.<br>Hover over the filter icon to see whether the column is currently being filtered, and if so, using which filter text.";
       
      /*
         ----------------------------------------------------------
      */
	  
?>
