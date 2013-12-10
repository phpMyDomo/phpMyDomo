
## Requirements ############################################

- Apache WebServer
- Apache ModRewrite enabled
- php5 >= 5.3
- php5-curl



## Installation ##############################################
If you're used to Git, the best way to install phpMyDomo is to clone the git 'master' branch, and to set an Apache virtual host pointing to the phpMydomo/www directory.
Future update will then only require a "git pull" .
If not, here is the manual way of installing phpMyDomo:

__1) Download the archive and decompress it__ somewhere on your server, ie in your home directory : /home/USERNAME/.
```sh
cd /home/USERNAME/
wget http://www.phpmydomo.org/download/?t=gz -O phpMyDomo_latest.tar.gz
tar xvfz phpMyDomo_latest.tar.gz
```
_Change "USERNAME" to your real username (ie "pi", "johndoe", etc..)_


__2) Make sure that you have a working Apache + php5 + php5-curl + ModRewrite installed__. If not, you can automatically install these by doing (on Debian):
```sh
cd /home/USERNAME/phpMyDomo-phpMyDomo-XXXX/www/inc/bin/
chmod 755 install_debian.sh
./install_debian.sh
```
_change "phpMyDomo-phpMyDomo-XXXX" according to your exact directory name, ie "phpMyDomo-phpMyDomo-9fa72f8"_


__3)__ If you've not already launched the install script, __make the _www/inc/cache_ directory writable__:
`chmod -R 777 /home/USERNAME/phpMyDomo-phpMyDomo-XXXX/www/inc/cache`


__4) Set the virtual host for apache__: 
If you use the default apache web directory at /var/www/ , make sure that this directory has the __AllowOverride All__ and __Options Indexes__ set, ie:

```
<Directory /var/www/>	
	Options Indexes
	AllowOverride All
	(other directives)
</Directory>
```

_in Debian, you just have to change "AllowOverride None" to "AllowOverride All" ._
`nano /etc/apache2/sites-available/default`

__5) move the content of your phpMyDomo-phpMyDomo-XXXX/www/ directory to the directory where you have configured Apache to serve files from__.
_Ie on debian, if you're using the default webserver location in /var/www/, do:_
`mv -f /home/USERNAME/phpMyDomo-phpMyDomo-XXXX/www/* /var/www/`
_Notice: Be sure that you've copied the /home/USERNAME/phpMyDomo-phpMyDomo-XXXX/www/.htaccess (hidden) file to your webserver root directory, else you will get a 404 when trying to access phpMyDomo from your web browser._


__6) restart apache__
`apache2ctl restart`


## Configuration ##########################################

in phpMyDomo/www/inc/conf/
rename _config-sample.php_ to __config.php__

By default, phpMyDomo, is configured to use the Domoticz API, on the same server, port 8080. If your Domoticz/Domogik is on another server or port, please edit it to match your own environment.


## Check if it works #########################################

- Launch you browser on the url where you've installed phpMyDomo.
 Click on the "Devices" Tab. If all is well configured , you should see your device list.

- You can now customize the config.php file to create your groups, blocks, hide menu, add cameras etc...

