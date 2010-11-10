=== COMPACTCMS 1.4.2 ===
Thank you for downloading this latest release of CompactCMS. This project is open
source and released under the GNU GENERAL PUBLIC LICENSE v3 (see license.txt).

=== INSTALL ===

== QUICK ==
- Extract the complete archive to your working folder (e.g. desktop)
- Either upload the files to a remote location or copy to a local server directory
- Call the root of the installation (mysite.com/ or mysite.com/test/ccms/)
- Carefully follow the installer instructions
- Delete the ./_install directory once finished
- Login at 'yoursite.com/admin/' using username "admin" and your selected password

---
If you're running into chmod() trouble, you'll need to at least chmod() both 
./.htaccess and ./lib/config.inc.php. 

== FULL DOCUMENTATION ==
Please refer to http://www.compactcms.nl/docs.html for the latest documentation on
installing this CCMS release. This page also includes a defintion of the variables
for manually configuring CompactCMS as well as a template variables descriptions.

After installation call your administration by adding '/admin' to the url of your
installation.

=== 1.4.2 CHANGES ===
The changes for 1.4.2 are completely driven by the hard work of GerHobbelt, who optimized
most of the code both security and functionality wise. This latest release includes many
(if not all) of the proposed improvements as made by GerHobbelt.

* Sanitizing of all input variables through added functions (common.inc.php)
* Optimized installer support
* Improved overall structuring within virtually all files
* Updates to external included libraries
* Removal of my 'single minded developer rookie and biased' mistakes
* Much more...

Thank you GerHobbelt, your inputs and efforts are much appreciated!

=== 1.4.1 CHANGES ===
The list below includes a list of changes for version 1.4.1.

* New user management (database driven)
* User levels allow for restrictions to features
* Inclusion of database class
* Enhanced security
* Added a database table prefix variable
* Included an installer
* Optimized variable defining
* Open multiple windows from dashboard at once
* Optimized news, comment and lightbox modules
* Edit templates from within the back-end
* Scores 99 on Yahoo's YSlow 2.0 guidelines when using CDN

=== 1.4.0 CHANGES ===
The list below includes a list of changes for version 1.4.0.

* Custom module support
* New simple template engine
* Simplified support for multi-lingual sites
* Back-up feature for contents and database
* Adoption of code editor for delicate programming
* Optimized for speed and usability
* Scores 96 on Yahoo's YSlow 2.0 guidelines
* Improved administration protection (Digest)
* Default lightbox and guestbook module

=== 1.x CHANGES ===
Previous version information has been archived.