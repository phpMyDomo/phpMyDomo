
## Requirements ############################################

- Apache WebServer
- Apache ModRewrite enabled
- php5

Please make sure that you have a working Apache + php5 + ModRewrite installed. If not, you can automatically install these by doing (on Debian):

```sh
cd phpMyDomo/www/inc/bin/
chmod 755 install_debian.sh
./install_debian.sh
```


## Installation ##############################################

1) If you've not already launched the install script, make the __www/inc/cache__ directory writable:
`chmod -R 777 phpMyDomo/www/inc/cache`

2) Set the virtual host for apache: 

If you use the default apache web directory at /var/www/ , make sure that this directory has the __AllowOverride All__ and __Options Indexes__ set, ie:

(debian) `nano /etc/apache2/sites-available/default`
```
<Directory /var/www/>	
	Options Indexes
	AllowOverride All
	(other directives)
</Directory>
```
_in Debian, you just have to change "AllowOverride None" to "__AllowOverride All__"_

3) restart apache
`apache2ctl restart`


## Configuration ##########################################

in phpMyDomo/www/inc/conf/
rename _config-sample.php_ to __config.php__

By default, phpMyDomo, is configured to use the Domoticz API, on the same server, port 8080. If your Domoticz/Domogik is on another server or port, please edit it to match your own environment.


# Check if it works #########################################

- Launch you browser on the url where you've installed phpMyDomo.
 Click on the "Devices" Tab. If all is well configured , you should see your device list.

- You can now customize the config.php file to create your group, hide menu, add cameras etc...


