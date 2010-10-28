#! /bin/bash

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


