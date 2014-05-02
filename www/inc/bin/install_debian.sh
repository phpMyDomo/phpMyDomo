#!/bin/bash
echo "Updating APT..."
apt-get update

echo
echo "Installing Web Server : Apache + php5 +ModRewrite...."
apt-get install apache2 php5 php5-curl php5-json 
a2enmod rewrite

echo
echo "Setting Permissions....."
chmod -R 777 ../cache/

echo
echo "Renaming conf_sample directory to conf....."
mv ../conf_sample ../conf

echo "Install completed!"
