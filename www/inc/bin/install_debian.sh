#!/bin/bash
echo "Updating APT..."
apt-get update

echo
echo "Installing Web Server : Apache + php5 +ModRewrite...."
apt-get install apache2 php5
a2enmod rewrite

echo
echo "Setting Permissions....."
chmod -R 777 ../cache/

echo "Install completed!"
