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
$ccms['lang']['system']['error_database'] 	= "Impossibile connettersi al database. Verificare username, password ed il nome del database.";
$ccms['lang']['system']['error_openfile'] 	= "Impossibile aprireilfile specificato.";
$ccms['lang']['system']['error_notemplate']	= "No templates could be found to be applied to your site. Please add at least one template to ./lib/templates/.";
$ccms['lang']['system']['error_templatedir'] = "Couldn't find the templates directory! Make sure it exists and contains at least one template.";
$ccms['lang']['system']['error_write'] 		= "File senza accesso in scrittura";
$ccms['lang']['system']['error_chmod'] 		= "Il file non pu&ograve; essere modificato. Controllare i permessi (CHMOD).";
$ccms['lang']['system']['error_value'] 		= "Errore: valore incorretto";
$ccms['lang']['system']['error_default'] 	= "La pagine principale non pu&ograve; essere cancellata.";
$ccms['lang']['system']['error_forged'] 	= "Il valore &egrave; stato impostato con";
$ccms['lang']['system']['error_filedots'] 	= "Il nome del file non pu&ograve; contenere punti, es. '.html'.";
$ccms['lang']['system']['error_filesize'] 	= "Il nome del file deve essere almeno di tre caratteri.";
$ccms['lang']['system']['error_pagetitle'] 	= "Inserire il titolo della pagina di almeno tre caratteri.";
$ccms['lang']['system']['error_subtitle'] 	= "Inserire il sottotitolo della pagina.";
$ccms['lang']['system']['error_description'] = "Inserire una descrizione di almeno tre caratteri.";
$ccms['lang']['system']['error_reserved'] 	= "E' stato specificato il nome di un file riservato.";
$ccms['lang']['system']['error_general'] 	= "Errore";
$ccms['lang']['system']['error_correct'] 	= "Corregere il/i seguente/i:";
$ccms['lang']['system']['error_create'] 	= "Errore nel completamento del nuovo file";
$ccms['lang']['system']['error_exists'] 	= "Nome di file esistente.";
$ccms['lang']['system']['error_delete'] 	= "Errore durante l'eliminazione del file.";
$ccms['lang']['system']['error_selection'] 	= "Nessun file selezionato.";
$ccms['lang']['system']['error_versioninfo'] = "Informazione non disponibile.";
$ccms['lang']['system']['error_misconfig'] 	= "<strong>Errore di configurazione.</strong><br/>Verificare che il file .htaccess sia correttamente configurato. Se<br/>CompactCMS &egrave; stato installato in una sottodirectory, modificare correttamente il file .htaccess.";
$ccms['lang']['system']['error_deleted']	= "<h1>Il file selezionato non &egrave; stato trovato</h1><p>Aggiornare la lista dei file per mostrare i file attualmente disponibili e prevenire il suddetto errore. Se ci&ograve; non risolve il problema, controllare manualmente l'esistenza del file nella cartella dei contenuti.</p>";
$ccms['lang']['system']['error_404title'] 	= "File non trovato";
$ccms['lang']['system']['error_404header'] 	= "Errore 404, il file richiesto &egrave; inesistente.";
$ccms['lang']['system']['error_sitemap'] 	= "Anteprima di tutte le pagine";
$ccms['lang']['system']['tooriginal']		= "Torna all'originale";
$ccms['lang']['system']['message_rights'] 	= "Tutti i diritti sono riservati.";
$ccms['lang']['system']['message_compatible'] = "Testato su";
$ccms['lang']['system']['error_notemplate']	= "Non ci sono template disponibili. Aggiungere almeno un template nella directory ./lib/templates/ map.";

// Administration general messages
$ccms['lang']['backend']['gethelp'] 		= "Suggerimenti o problemi? Visita <a href=\"http://www.compactcms.nl/forum/\" title=\"Visit the official forum\" class=\"external\" rel=\"external\">il forum</a>!";
$ccms['lang']['backend']['ordertip'] 		= "Utilizzare la lista sotto per strutturare il menu del sito. Il sistema non gestisce i duplicati.";
$ccms['lang']['backend']['createtip'] 		= "Per creare una nuova pagina, riempire il modulo sotto ad una nuova pagina sar&agrave; creata. Appena il file sar&agrave; creato sar&agrave; possibile modificarlo.";
$ccms['lang']['backend']['currentfiles'] 	= "Nella lista sotto son presenti i file pubblicati. La pagina di default non pu&ograve; essere cancellata essendo la primapagina del sito. Gli altri file potrebbero essere soggette a restrizioni dell'amministratore.";
$ccms['lang']['backend']['confirmdelete'] 	= "Cancellare le pagine selezionate ed il loro contenuto?";
$ccms['lang']['backend']['changevalue'] 	= "Premi per cambiare";
$ccms['lang']['backend']['previewpage'] 	= "Anteprima";
$ccms['lang']['backend']['editpage'] 		= "Modifica";
$ccms['lang']['backend']['restrictpage'] 	= "Riservato";
$ccms['lang']['backend']['newfiledone'] 	= "Inserire il contenuto...";
$ccms['lang']['backend']['newfilecreated'] 	= "Il file &egrave; stato creato con successo";
$ccms['lang']['backend']['startedittitle'] 	= "Inizio modifica!";
$ccms['lang']['backend']['starteditbody'] 	= "Nuovo file creato. Modificare il suo contenuto o aggiungere/gestire nuove pagine.";
$ccms['lang']['backend']['success'] 		= "Riuscito!";
$ccms['lang']['backend']['fileexists'] 		= "File esistente";
$ccms['lang']['backend']['statusdelete'] 	= "Stato dell'eliminazione della selezione:";
$ccms['lang']['backend']['statusremoved'] 	= "rimosso";
$ccms['lang']['backend']['uptodate'] 		= "aggiornato.";
$ccms['lang']['backend']['outofdate'] 		= "obsoleto.";
$ccms['lang']['backend']['considerupdate'] 	= "Aggiornamento consigliato";
$ccms['lang']['backend']['orderprefsaved'] 	= "Ordine dei menu salvato.";
$ccms['lang']['backend']['inmenu'] 			= "Nel menu";
$ccms['lang']['backend']['updatelist'] 		= "Aggiorna lista file";
$ccms['lang']['backend']['administration'] 	= "Amministrazione";
$ccms['lang']['backend']['currentversion'] 	= "Versione corrente";
$ccms['lang']['backend']['mostrecent'] 		= "L'ultima versione di CompactCMS stabile &egrave;";
$ccms['lang']['backend']['versionstatus'] 	= "La tua installazione &egrave;";
$ccms['lang']['backend']['createpage'] 		= "Crea nuova pagina";
$ccms['lang']['backend']['managemenu'] 		= "Gestisci menu";
$ccms['lang']['backend']['managefiles'] 	= "Gestisci i file correnti";
$ccms['lang']['backend']['delete'] 			= "Elimina";
$ccms['lang']['backend']['toplevel'] 		= "Livello top";
$ccms['lang']['backend']['sublevel'] 		= "Sottolivello";
$ccms['lang']['backend']['active'] 			= "Attivo";
$ccms['lang']['backend']['disabled'] 		= "Disabilitato";
$ccms['lang']['backend']['template'] 		= "Categoria";
$ccms['lang']['backend']['notinmenu'] 		= "L'oggetto non &egrave; nel men&ugrave;";
$ccms['lang']['backend']['menutitle'] 		= "Menu";
$ccms['lang']['backend']['linktitle'] 		= "Link";
$ccms['lang']['backend']['item'] 			= "Oggetto";
$ccms['lang']['backend']['yes'] 			= "S&igrave;";
$ccms['lang']['backend']['no'] 				= "No";

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
$ccms['lang']['menu']['1']				= "Principale";
$ccms['lang']['menu']['2']				= "Sinistra";
$ccms['lang']['menu']['3']				= "Destra";
$ccms['lang']['menu']['4']				= "Fondo";
$ccms['lang']['menu']['5']				= "Extra";

// Administration form related texts
$ccms['lang']['forms']['filename'] 		= "Nome file";
$ccms['lang']['forms']['pagetitle'] 	= "Titolo pagina";
$ccms['lang']['forms']['subheader'] 	= "Sottotitolo";
$ccms['lang']['forms']['description'] 	= "Descrizione";
$ccms['lang']['forms']['module'] 		= "Module";
$ccms['lang']['forms']['contentitem']	= "Content item (default)";
$ccms['lang']['forms']['additions']		= "Additions";
$ccms['lang']['forms']['printable'] 	= "Stampabile";
$ccms['lang']['forms']['published'] 	= "Attiva";
$ccms['lang']['forms']['createbutton'] 	= "Crea!";
$ccms['lang']['forms']['savebutton'] 	= "Salva";
$ccms['lang']['forms']['iscoding'] 		= "Codice";

// Administration hints for form fields
$ccms['lang']['hints']['filename'] 		= "Indirizzo URL (home.html) :: Nome del file (senza estensione .html)";
$ccms['lang']['hints']['pagetitle'] 	= "Titolo della pagina (Home) :: Breve descrizione della pagina.";
$ccms['lang']['hints']['subheader'] 	= "Intestazione della pagina (Benvenuto nel nostro sito) :: Una breve descrizione utilizzata sia in ogni pagina sia come titolo nella barra del broswer.";
$ccms['lang']['hints']['description'] 	= "Codici Meta :: Descrizione unica di questa pagina (utilizzata anche nei dati meta).";
$ccms['lang']['hints']['module']		= "Module :: Select what module should handle the content of this file. If you are unsure, select the default.";
$ccms['lang']['hints']['printable'] 	= "Stampabile :: In caso positivo viene creata una pagina stampabile. 'NO' dovrebbe essere selezionato per le pagine contenenti immagini ed elementi multimediali.";
$ccms['lang']['hints']['published'] 	= "Pubblicato? :: Selezionare la casella se si vuole pubblicare la pagina per renderla accessibile al pubblico ed inclusa nella sitemap.";
$ccms['lang']['hints']['toplevel'] 		= "Livello Top :: Specifica il livello pi&ugrave;alto per questo menu. Selezionare N.I.M. per non includerla nei menu.";
$ccms['lang']['hints']['sublevel'] 		= "Sottolivello :: Selezionando 0 l'oggetto del menu &egrave; nel livello pi&ugrave; alto. Se invece &egrave; un sottolivello per un certo menu, selezionare ilsottolivello appropriato.";
$ccms['lang']['hints']['template'] 		= "Categoria :: Se si usano template multipli, &egrave; possibile categorizzare i menu in base al template.";
$ccms['lang']['hints']['activelink'] 	= "Menu attivo? :: Non tutte le pagine necessitano di un link attivo. Per disattivare una pagina premere la checkbox corrispondente.";
$ccms['lang']['hints']['menuid'] 		= "Categoria Menu :: Selezione in quale menu questo oggetto deve essere inserito. Il default &egrave; il main menu (1), in cui &egrave; mostrato il link alla homepage.";
$ccms['lang']['hints']['iscoding'] 		= "Contiene codice :: Il file contiene codice (es. PHP o Javascript)? Selezionado 'S&igrave;' &egrave; possibile inserire il proprio codice a mano nell'editor della pagina.";

// Editor messages
$ccms['lang']['editor']['closeeditor'] 	= "Chiudi l'editor";
$ccms['lang']['editor']['editorfor'] 	= "Editor di testo per";
$ccms['lang']['editor']['instruction'] 	= "Utilizzare l'editor sotto per modificare il file corrente. Premere poi il pulsante \"Salva cambiamenti\" per pubblicare le modifiche ed aggiornare automaticamente il sito.";
$ccms['lang']['editor']['savebtn'] 		= "Salva cambiamenti";
$ccms['lang']['editor']['cancelbtn'] 	= "Annulla";
$ccms['lang']['editor']['confirmclose'] = "Chiudere la finestra e scartare le modifiche?";
$ccms['lang']['editor']['preview'] 		= "Anteprima pagina";
$ccms['lang']['editor']['savesuccess'] 	= "<strong>Successo!</strong> Il contenuto sotto &egrave; stato salvato in ";
$ccms['lang']['editor']['backeditor'] 	= "Torna all'editor";
$ccms['lang']['editor']['closewindow'] 	= "Chiudi finestra";
$ccms['lang']['editor']['keywords'] 	= "Parole chiave - <em> separate da virgole, massimo 250 caratteri </ em>";

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
	$ccms['lang']['backend']['fileexists'] 		
	$ccms['lang']['backend']['startedittitle'] 	
	$ccms['lang']['backend']['updatelist'] 		
	$ccms['lang']['editor']['closeeditor'] 	
	$ccms['lang']['guestbook']['guestbook']	
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
$ccms['lang']['forms']['add']           = "Add filter for";
$ccms['lang']['forms']['edit_remove']   = "Edit or remove filter for";
$ccms['lang']['forms']['filter_showing']	= "right now we're only showing pages which have at least this text in here";
$ccms['lang']['forms']['modifybutton'] 	= "Modify";
$ccms['lang']['forms']['setlocale']		= "Front-end language";
$ccms['lang']['guestbook']['error']		= "Failures &amp; Rejections";
$ccms['lang']['guestbook']['rejected']	= "Your comment has been rejected.";
$ccms['lang']['guestbook']['success']	= "Thank you";
$ccms['lang']['hints']['filter']        = "<br>You can click on the <span class='sprite livefilter livefilter_active'>&#160;filter icon</span> at left of the title to add, edit or remove a text to filter the page list on, e.g. when you type 'home' in the edit field which appears when you click the icon, then press the Enter/Return key, only pages which have the text 'home' in this column will be shown. <br>Clicking the icon again and deleting the text in the edit field, then pressing the Enter/Return key, will remove the filter.<br>Hover over the filter icon to see whether the column is currently being filtered, and if so, using which filter text.";
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
