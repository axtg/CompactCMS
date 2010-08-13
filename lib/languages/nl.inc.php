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

// System wide error messages
$ccms['lang']['system']['error_database'] 	= "Kon geen verbinding maken met de database. Controleer login- en databasegegevens.";
$ccms['lang']['system']['error_openfile'] 	= "Kon het aangegeven bestand niet openen";
$ccms['lang']['system']['error_notemplate']	= "Er zijn momenteel geen templates beschikbaar. Voeg minstens 1 template toe in de ./lib/templates/ map.";
$ccms['lang']['system']['error_templatedir'] = "Kon de template map niet vinden! Controleer of de map bestaat en voeg tenminste 1 template toe.";
$ccms['lang']['system']['error_write'] 		= "Geen schrijfrechten tot het bestand";
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
$ccms['lang']['system']['tooriginal']		= "Terug naar origineel";
$ccms['lang']['system']['message_rights'] 	= "Alle rechten voorbehouden";
$ccms['lang']['system']['message_compatible'] = "Succesvol getest op";

// Administration general messages
$ccms['lang']['backend']['gethelp'] 		= "Heb je suggesties, feedback of problemen? Bezoek dan <a href=\"http://community.compactcms.nl/forum/\" title=\"Bezoek het offici&euml;le forum\" class=\"external\" rel=\"external\">het forum</a>!";
$ccms['lang']['backend']['ordertip'] 		= "Gebruik de 'drop-downs' hieronder om de structuur van de pagina's in het menu te verwerken. Let op dat het systeem geen rekening houdt met gelijke top- en subniveau's combinaties.";
$ccms['lang']['backend']['createtip'] 		= "Vul onderstaand formulier in om direct een nieuwe pagina aan te maken. Nadat het bestand is aangemaakt, kan deze direct eenvoudig bijgewerkt worden.";
$ccms['lang']['backend']['currentfiles'] 	= "In de lijst hieronder staan alle huidige pagina's weergegeven. De standaard pagina kan niet worden verwijderd omdat dit de startpagina van de website is. Andere pagina's kunnen afgeschermde content hebben omdat alleen de beheerder het recht heeft deze bewerken.";
$ccms['lang']['backend']['confirmdelete'] 	= "Bevestig het verwijderen van de aangegeven item(s) en de betreffende inhoud.";
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
$ccms['lang']['backend']['yes'] 			= "Ja";
$ccms['lang']['backend']['no'] 				= "Nee";

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
$ccms['lang']['forms']['filename'] 		= "Bestandsnaam";
$ccms['lang']['forms']['pagetitle'] 	= "Pagina titel";
$ccms['lang']['forms']['subheader'] 	= "Sub-koptekst";
$ccms['lang']['forms']['description'] 	= "Beschrijving";
$ccms['lang']['forms']['module'] 		= "Module";
$ccms['lang']['forms']['contentitem']	= "Content item (standaard)";
$ccms['lang']['forms']['additions']		= "Extra modules";
$ccms['lang']['forms']['printable'] 	= "Printbaar";
$ccms['lang']['forms']['published'] 	= "Actief";
$ccms['lang']['forms']['createbutton'] 	= "Aanmaken!";
$ccms['lang']['forms']['savebutton'] 	= "Opslaan";
$ccms['lang']['forms']['iscoding'] 		= "Coding";

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

################### MODULES ###################

// Back-up messages
$ccms['lang']['backup']['createhd']		= "Cre&euml;r nieuwe back-up";
$ccms['lang']['backup']['explain']		= "Om mogelijk verlies van data door externe omstandigheden te voorkomen, is het verstandig om regelmatig back-ups te maken.";
$ccms['lang']['backup']['currenthd']	= "Beschikbare back-ups";
$ccms['lang']['backup']['timestamp']	= "Back-up datum";
$ccms['lang']['backup']['download']		= "Download archief";

// Album messages
$ccms['lang']['album']['album']			= "Album";
$ccms['lang']['album']['errordir']		= "De opgegeven albumnaam is te kort (min. 4).";
$ccms['lang']['album']['newdircreated']	= "albummap is aangemaakt.";
$ccms['lang']['album']['renamed']		= "is hernoemd naar";
$ccms['lang']['album']['removed']		= "en alle bijhorende inhoud is verwijderd.";
$ccms['lang']['album']['refresh']		= "Vernieuwen";
$ccms['lang']['album']['manage']		= "Beheer albums";
$ccms['lang']['album']['albumlist']		= "Album lijst";
$ccms['lang']['album']['newalbum']		= "Nieuwe albumnaam";
$ccms['lang']['album']['noalbums']		= "Er zijn nog geen albums aangemaakt!";
$ccms['lang']['album']['directory']		= "Album (#)";
$ccms['lang']['album']['rename']		= "Hernoem";
$ccms['lang']['album']['tooverview']	= "Terug naar het overzicht";
$ccms['lang']['album']['nodir']			= "Controleer of de map <strong>albums</strong> bestaat in de opgegeven afbeeldingen map";

// Guestbook message
$ccms['lang']['guestbook']['guestbook']	= "Gastenboek";
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
?>