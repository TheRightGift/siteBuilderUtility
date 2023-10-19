#!/bin/bash

echo 'App starting' 

#grant permission to files inside cicidTest folder
sudo chmod -R 777 /var/www/siteBuilderUtility

#Grant permission to files in required folder
sudo chown -R www-data.www-data /var/www/siteBuilderUtility/public
sudo chown -R www-data.www-data /var/www/siteBuilderUtility/src/API
sudo chown -R www-data.www-data /var/www/siteBuilderUtility/src/Controller
sudo chown -R www-data.www-data /var/www/siteBuilderUtility/src/storeTemplates

cd /var/www/siteBuilderUtility/src
sudo chmod -R 777 /var/www/siteBuilderUtility/public && sudo chmod -R 777 API && sudo chmod -R 777 Controller && sudo chmod -R 777 storeTemplates

