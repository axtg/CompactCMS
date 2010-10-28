#! /bin/bash
#
# See also:
#   http://community.compactcms.nl/forum/index.php/topic,170.0.html
#
# For the folks who have SSH/telnet access to their web server (like me: no TP, 
# but SSH+SCP instead): run the script and all the files and directories get 
# their modes set right, so the install script doesn't yak about this.
#
# NOTE: this version makes only ./lib/templates/ccms.tpl.html writable, not all the 
# template files, so those will show up as 'cannot edit'able items when you go 
# to the admin template editor.
# Another 'find ... -exec ...' in there will remedy that if you want that. I wanted 
# the 'standard' templates non-writable but shut up the CCMS installer at the 
# same time.
#

pushd .
cd ../

rm -rf ./lib/includes/cache

mkdir ./content ./media ./media/albums ./media/files ./lib/includes/cache 

find ./ -type f -exec chmod 0644 "{}" \;
find ./ -type d -exec chmod 0755 "{}" \;

find ./content -type f -exec chmod 0666 "{}" \;
find ./media -type f -exec chmod 0666 "{}" \;
#    uncomment the next line when you want all templates to be editable...
#find ./lib/templates -type f -exec chmod 0666 "{}" \;

chmod 0666 ./.htaccess ./lib/config.inc.php ./content/home.php ./content/contact.php ./lib/templates/ccms.tpl.html 
chmod 0777 ./content ./media ./media/albums ./media/files ./lib/includes/cache 

popd

chmod 0777 *.sh


