# Installing phpMyDomo

## Requirements ############################################

- Apache WebServer
- Apache ModRewrite enabled
- php5 >= 5.3
- php5-curl
- php5-sqlite (for iPhoto)



## Installation ##############################################
If you're used to Git, the best way to install phpMyDomo is to clone the git 'master' branch, and to set an Apache virtual host pointing to the phpMydomo/www directory.
Future update will then only require a `git pull` .

If not, here is the manual way of installing phpMyDomo:


### 1) Download the archive 

Download the archive and decompress it somewhere on your server, ie in your home directory : */home/USERNAME/*

```sh
cd /home/USERNAME/
wget http://www.phpmydomo.org/download/?t=gz -O phpMyDomo_latest.tar.gz
tar xvfz phpMyDomo_latest.tar.gz
```
_Change "USERNAME" to your real username (ie "pi", "johndoe", etc..)_


### 2) Apache + php5 + php5-curl + php5-sqlite + ModRewrite must be installed

Make sure that you have a working Apache + php5 + php5-curl + php5-sqlite + ModRewrite installed and enabled (On Ubuntu, you might also need "php5-json"). If not, you can automatically install these by doing (on Debian):

```sh
cd /home/USERNAME/phpMyDomo-phpMyDomo-XXXX/www/inc/bin/
chmod 755 install_debian.sh
./install_debian.sh
```
_change "phpMyDomo-phpMyDomo-XXXX" according to your exact directory name, ie "phpMyDomo-phpMyDomo-9fa72f8"_

If some software are already installed, it wont break anything to launch this script: It will just install missing software (if any), enable modRewrite, change the cache permission and rename the conf_sample directory for you...


### 3) make the _www/inc/cache_ directory writable
If you've not already launched the install script, do :

`chmod -R 777 /home/USERNAME/phpMyDomo-phpMyDomo-XXXX/www/inc/cache`

and make sure that Apache modRewrite is enabled : `a2enmod rewrite`


### 4) Setup the virtual host for Apache 
If you use the default apache web directory at /var/www/ , make sure that this directory has the __AllowOverride All__ and __Options Indexes FollowSymLinks__ set, ie:

```
<Directory /var/www/>	
	Options Indexes FollowSymLinks
	AllowOverride All
	(other directives)
</Directory>
```

_in Debian, when using the default configuration, you just have to change its "AllowOverride None" to "AllowOverride All" ._

`nano /etc/apache2/sites-available/default`


### 5) Move the *www* directory to the WebServer root

You have to move the content of your *phpMyDomo-phpMyDomo-XXXX/www/* directory to the directory where you have configured Apache to serve files from:

_Ie on debian, if you're using the default webserver location in /var/www/, do:_
`mv -f /home/USERNAME/phpMyDomo-phpMyDomo-XXXX/www/* /var/www/`
`mv -f /home/USERNAME/phpMyDomo-phpMyDomo-XXXX/www/.htaccess /var/www/`

_Notice: Be sure that you've copied the /home/USERNAME/phpMyDomo-phpMyDomo-XXXX/www/.htaccess (hidden) file to your webserver root directory, else you will get a 404 when trying to access phpMyDomo from your web browser._


### 6) restart apache
`apache2ctl restart`


## Configuration ##########################################

in phpMyDomo/www/inc/
rename the directory *conf_sample* to __conf__ :
`mv /var/www/inc/conf_sample /var/www/inc/conf`

By default, phpMyDomo, is configured to use the OpenHab API, from the live demo API server.


## Check if it works #########################################

- Launch you browser on the url where you've installed phpMyDomo.
 Click on the "Devices" Tab. If all is well configured , you should see the device list from the demo server.

- You can now customize the www/inc/conf/config.php file: You first have to change the API server to the one you want to use, (and set its location:port when PMD  is on a different server). Then create your groups, blocks, hide menu, add cameras etc...



_(BTW anyone wanting to make a better, clear,  good-english version of this document is very welcome!)_
