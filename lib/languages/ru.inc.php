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

/* Russian translation by Ilya <icherevkov@gmail.com> */

// System wide messages
$ccms['lang']['system']['error_database'] 	= "Невозможно подключиться к базе данных. Пожалуйста, проверьте ваш логин и название базы данных в настройках.";
$ccms['lang']['system']['error_openfile'] 	= "Невозможно открыть указанный файл.";
$ccms['lang']['system']['error_notemplate']	= "Не найдено ни одного шаблона, который мог бы быть использован для вашего сайта. Пожалуйста, добавьте как минимум один шаблон в ./lib/templates/.";
$ccms['lang']['system']['error_templatedir'] = "Не найдена директория с шаблонами! Пожалуйста, убедитесь, что она существует и содержит как минимум один шаблон.";
$ccms['lang']['system']['error_write'] 		= "Файл не имеет разрешения на запись.";
$ccms['lang']['system']['error_chmod'] 		= "Данный файл не может быть изменен. Проверьте разрешения в /content directory (666).";
$ccms['lang']['system']['error_value'] 		= "Ошибка: недопустимое значение.";
$ccms['lang']['system']['error_default']	= "Страница, заданная по умолчанию, не может быть удалена.";
$ccms['lang']['system']['error_forged']		= "Значение было привязано к";
$ccms['lang']['system']['error_filedots']	= "Имя файла не должно включать точки, например '.html'.";
$ccms['lang']['system']['error_filesize']	= "Название файла должно состоять как минимум из трех символов.";
$ccms['lang']['system']['error_pagetitle']	= "Введите название страницы, состоящее из как минимум 3 символов или более.";
$ccms['lang']['system']['error_subtitle']	= "Дайте краткий подзаголовок вашей странице.";
$ccms['lang']['system']['error_description'] = "Введите описание из более чем трех символов";
$ccms['lang']['system']['error_reserved'] 	= "Файл с заданным названием уже существует";
$ccms['lang']['system']['error_general']	= "Произошла ошибка";
$ccms['lang']['system']['error_correct'] 	= "Пожалуйста исправьте следующее:";
$ccms['lang']['system']['error_create'] 	= "Произошла ошибка во время создания нового файла";
$ccms['lang']['system']['error_exists'] 	= "Заданное имя уже существует.";
$ccms['lang']['system']['error_delete']		= "Произошла ошибка во время удаления выбранного файла";
$ccms['lang']['system']['error_selection'] 	= "Ни один файл не был выбран.";
$ccms['lang']['system']['error_versioninfo'] = "Информация о версии программы недоступна.";
$ccms['lang']['system']['error_misconfig']	= "<strong>Похоже, вы недонастроили систему до конца.</strong><br/>Пожалуйста, убедитесь, что файл .htaccess настроен правильно. Если вы <br/>установили CompactCMS в какую-либо поддиректрию, то настройте файл .htaccess соответственно.";
$ccms['lang']['system']['error_deleted']	= "<h1>Файл, который вы выбрали, похоже удален.</h1><p>Обновите ваш список файлов для того, чтобы избежать этой ошибки. Если это не помогает, вручную проверьте каталог с файлом, который вы хотите открыть.</p>";
$ccms['lang']['system']['error_404title'] 	= "Файл не найден";
$ccms['lang']['system']['error_404header']	= "Ошибка 404, запрашиваемый файл не найден.";
$ccms['lang']['system']['error_sitemap'] 	= "Просмотр всех страниц";
$ccms['lang']['system']['tooriginal']		= "Вернуться к оригиналу";
$ccms['lang']['system']['message_rights'] 	= "Все права защищены";
$ccms['lang']['system']['message_compatible'] = "Успешно протестировано с";

// Administration general messages
$ccms['lang']['backend']['gethelp'] 		= "Есть предложения, комментарии или вы столкнулись с проблемой? Посетите <a href=\"http://community.compactcms.nl/forum/\" title=\"Посетите официальный форум\" class=\"external\" rel=\"external\">форум</a>!";
$ccms['lang']['backend']['ordertip'] 		= "Используйте эту панель для отображения структуры страниц в меню вашего сайта. Помните, что система не предполагает повторения комбинаций одного и того же уровня и подуровня.";
$ccms['lang']['backend']['createtip'] 		= "Для создания новой страницы заполните форму ниже, и новая страница будет создана для вас без обновления панели. После того, как страница будет создана, вы сможете отредактировать ее как обычно.";
$ccms['lang']['backend']['currentfiles'] 	= "В списке ниже вы найдете все уже когда-либо опубликованные страницы. Учтите, что вы не можете удалить файл, заданный как главная страница вашего сайта по умолчанию. Некоторые файлы могут иметь ограниченный доступ, так как администратор установил исключительные права на эти файлы.";
$ccms['lang']['backend']['confirmdelete'] 	= "Пожалуйста, подтвердите, что вы хотите удалить отмеченное со всем содержимым.";
$ccms['lang']['backend']['changevalue']		= "Кликните для изменения";
$ccms['lang']['backend']['previewpage']		= "Просмотр";
$ccms['lang']['backend']['editpage']		= "Изменить";
$ccms['lang']['backend']['restrictpage'] 	= "Доступ запрещен";
$ccms['lang']['backend']['newfiledone'] 	= "Файл готов к наполнению!";
$ccms['lang']['backend']['newfilecreated']	= "Файл был создан";
$ccms['lang']['backend']['startedittitle'] 	= "Начинайте редактирование!";
$ccms['lang']['backend']['starteditbody']	= "Новый файл был создан. Начинайте редактирование прямо сейчас, либо добавьте больше страниц или измените существующие. ";
$ccms['lang']['backend']['success'] 		= "Успех!";
$ccms['lang']['backend']['fileexists'] 		= "Файл существует";
$ccms['lang']['backend']['statusdelete'] 	= "Статус файлов, отмеченных на удаление:";
$ccms['lang']['backend']['statusremoved']	= "удалено";
$ccms['lang']['backend']['uptodate']		= "не нуждается в обновлении.";
$ccms['lang']['backend']['outofdate']		= "устарела.";
$ccms['lang']['backend']['considerupdate'] 	= "Подтвердите обновление";
$ccms['lang']['backend']['orderprefsaved'] 	= "Порядок элементов в меню был сохранен.";
$ccms['lang']['backend']['inmenu']			= "В меню";
$ccms['lang']['backend']['updatelist']		= "Обновить список файлов";
$ccms['lang']['backend']['administration']	= "Администрация";
$ccms['lang']['backend']['currentversion']	= "Сейчас вы используете версию";
$ccms['lang']['backend']['mostrecent']		= "Наиболее стабильная и актуальная версия - ";
$ccms['lang']['backend']['versionstatus'] 	= "Ваша система";
$ccms['lang']['backend']['createpage']		= "Создать новую страницу";
$ccms['lang']['backend']['managemenu']		= "Управление меню";
$ccms['lang']['backend']['managefiles'] 	= "Управление файлами";
$ccms['lang']['backend']['delete'] 			= "Удалить";
$ccms['lang']['backend']['toplevel']		= "Уровень";
$ccms['lang']['backend']['sublevel'] 		= "Подуровень";
$ccms['lang']['backend']['active']			= "Активно";
$ccms['lang']['backend']['disabled']		= "Отключено";
$ccms['lang']['backend']['template']		= "Шаблон";
$ccms['lang']['backend']['notinmenu']		= "Элемент не в меню";
$ccms['lang']['backend']['menutitle']		= "Меню";
$ccms['lang']['backend']['linktitle']		= "Ссылка";
$ccms['lang']['backend']['item']			= "Элемент";
$ccms['lang']['backend']['yes']				= "Да";
$ccms['lang']['backend']['no']				= "Нет";

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
$ccms['lang']['forms']['filename']		= "Название"; 
$ccms['lang']['forms']['pagetitle']		= "Заголовок";
$ccms['lang']['forms']['subheader'] 	= "Подзаголовок";
$ccms['lang']['forms']['description'] 	= "Описание";
$ccms['lang']['forms']['module'] 		= "Module";
$ccms['lang']['forms']['contentitem']	= "Информационная страница (по умолчанию)";
$ccms['lang']['forms']['additions']		= "Дополнения";
$ccms['lang']['forms']['printable'] 	= "Печать";
$ccms['lang']['forms']['published'] 	= "Активно";
$ccms['lang']['forms']['iscoding'] 		= "Код";
$ccms['lang']['forms']['createbutton'] 	= "Создать!";
$ccms['lang']['forms']['savebutton'] 	= "Сохранить";

// Administration hints for form fields
$ccms['lang']['hints']['filename']		= "URL страницы (home.html):: Имя файла, в котором хранится страница (без .html).";
$ccms['lang']['hints']['pagetitle'] 	= "Название страницы (Home) :: Краткий заголовок страницы.";
$ccms['lang']['hints']['subheader']		= "Краткий подзаголовок (Приветствуем вас на нашем сайте) :: Краткое описание, которое используется в начале каждой страницы так же, как и заголовок.";
$ccms['lang']['hints']['description']	= "Meta-описание :: Meta-описание вашей страницы.";
$ccms['lang']['hints']['module']		= "Модуль :: Выберите, какой модуль будет использоваться для отображения данной страницы. Если вы не уверены, выберите модуль по умолчанию.";
$ccms['lang']['hints']['printable']		= "Возможность печати страницы :: Выберите 'ДА', если хотите, чтобы на странице отображалась кнопка печати. Выберите 'НЕТ', если на странице присутствуют изображения или другое медиа-содержание. Страницы с подобным содержанием могут печататься некорректно.";
$ccms['lang']['hints']['published']		= "Статус публикации :: Выберите, должна ли данная страница быть опубликованна, отображена на карте сайта, и может ли она быть доступна для посетителей.";
$ccms['lang']['hints']['toplevel']		= "Уровень :: Выберите уровень для данного меню. Выберите --- для того, чтобы не включать страницу в меню.";
$ccms['lang']['hints']['sublevel']		= "Подуровень :: Выберите 0, если этот элемент должен быть основным. Если это подэлемент конкретного элемента меню, пожалуйста, выберите соответствующий подуровень.";
$ccms['lang']['hints']['template']		= "Шаблон :: Если вы используете несколько шаблонов на вашем сайте, то вы можете назначить каждой странице сайта соответствующий шаблон.";
$ccms['lang']['hints']['activelink']	= "Активность ссылки в меню :: Не все элементы всегда нуждаются в ссылке. Для деактивации ссылки с этого элемента снимите галочку с чекбокса ниже.";
$ccms['lang']['hints']['menuid']		= "Меню :: Выберите в каком меню должен содержаться данный элемент. Основное меню задано по умолчанию (1), это то меню где отображается ссылка на страницу home.";
$ccms['lang']['hints']['iscoding']		= "Разрешить коды :: Будет ли данная страница содержать какой-либо вручную добавленный код, такой как PHP или Javascript? При выборе 'ДА', система запретит доступ к визуальному редактору WYSIWYG и активирует редактор кода.";

// Editor messages
$ccms['lang']['editor']['closeeditor']	= "Закрыть редактор";
$ccms['lang']['editor']['editorfor']	= "Текстовый редактор для";
$ccms['lang']['editor']['instruction']	= "Используйте редактор ниже для изменения текущего файла. Окончив редактирование, нажмите кнопку \"Сохранить изменения\" для публикации вашей странице на сайте. Не забудьте добавить до десяти ключевых слов для поисковой оптимизации.";
$ccms['lang']['editor']['savebtn']		= "Сохранить изменения";
$ccms['lang']['editor']['cancelbtn'] 	= "Отмена";
$ccms['lang']['editor']['confirmclose'] = "Закрыть это окно без сохранения изменений?";
$ccms['lang']['editor']['preview']		= "Просмотр конечной страницы";
$ccms['lang']['editor']['savesuccess'] 	= "<strong>Успех! </strong>Информация, как показано ниже, была сохранена в ";
$ccms['lang']['editor']['backeditor'] 	= "Вернуться в редактор";
$ccms['lang']['editor']['closewindow'] 	= "Закрыть окно";
$ccms['lang']['editor']['keywords']		= "Ключевые слова - <em>разделенные запятыми, макс. 250 символов</em>";

################### MODULES ###################

// Back-up messages
$ccms['lang']['backup']['createhd']		= "Создать новый бэк-ап";
$ccms['lang']['backup']['explain']		= "Для предотвращения потери данных в результате какой-либо ошибки рекомендуется регулярно делать резервную копию ваших файлов.";
$ccms['lang']['backup']['currenthd']	= "Доступные бэк-апы";
$ccms['lang']['backup']['timestamp']	= "Back-up file name";
$ccms['lang']['backup']['download']		= "Загрузить архив";

// Album messages
$ccms['lang']['album']['album']			= "Альбом";
$ccms['lang']['album']['errordir']		= "Заданное название альбома слишком коротко (мин. 4).";
$ccms['lang']['album']['newdircreated']	= "Директория альбома была создана.";
$ccms['lang']['album']['renamed']		= "был переименован в";
$ccms['lang']['album']['removed']		= "и все содержимое было удалено.";
$ccms['lang']['album']['refresh']		= "Обновить";
$ccms['lang']['album']['manage']		= "Управление альбомами";
$ccms['lang']['album']['albumlist']		= "Список альбомов";
$ccms['lang']['album']['newalbum']		= "Название нового альбома";
$ccms['lang']['album']['noalbums']		= "Ни одного альбома пока не создано!";
$ccms['lang']['album']['directory']		= "Директория (#)";
$ccms['lang']['album']['tooverview']	= "Вернуться к обзору";
$ccms['lang']['album']['rename']		= "Переименовать";
$ccms['lang']['album']['nodir']			= "Пожалуйста, проверьте, что папка <strong>albums</strong> существует в вашем каталоге с изображениями";

// Guestbook message
$ccms['lang']['guestbook']['guestbook']	= "Гостевая книга";
$ccms['lang']['guestbook']['noposts']	= "Ни одного комментария еще не было оставлено";
$ccms['lang']['guestbook']['verinstr']	= "Для проверки, что сообщение не автоматическое, пожалуйста введите";
$ccms['lang']['guestbook']['reaction']	= "Комментарий";
$ccms['lang']['guestbook']['rating']	= "Рэйтинг";
$ccms['lang']['guestbook']['avatar']	= "Аватар Gravatar.com";
$ccms['lang']['guestbook']['wrote']		= "написал";
$ccms['lang']['guestbook']['manage']	= "Управлять комментариями";
$ccms['lang']['guestbook']['delentry']	= "Удалить эту запись";
$ccms['lang']['guestbook']['sendmail']	= "Сообщение по e-mail автору";
$ccms['lang']['guestbook']['removed'] 	= "был удален из базы данных.";
$ccms['lang']['guestbook']['name'] 		= "Ваше имя";
$ccms['lang']['guestbook']['email']		= "Ваш e-mail";
$ccms['lang']['guestbook']['website']	= "Ваш веб-сайт";
$ccms['lang']['guestbook']['comments']	= "Комментарии";
$ccms['lang']['guestbook']['verify']	= "Подтверждение";
$ccms['lang']['guestbook']['preview']	= "Предпросмотр комментария";
$ccms['lang']['guestbook']['add']		= "Добавить комментарий";
$ccms['lang']['guestbook']['posted']	= "Комментарий опубликован!";
?>