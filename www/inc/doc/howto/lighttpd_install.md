# Setup Lighttpd for phpMyDomo

_For experts only: Using Apache webserver is the prefered way to install phpMyDomo, but if you are an expert and prefer to use Lighttpd instead of Apache You might try the following method._


## Install Required packages ############################################
as root:

```
apt-get update
apt-get install lighttpd php5-fpm php5 php5-curl
```


## Edit php config ############################################
```
nano /etc/php5/fpm/php.ini
```
And change _cgi.fix_pathinfo=0__ to __cgi.fix_pathinfo=1__
Then restart php:
```
service php5-fpm restart
```

## Configure lighttpd #########################################
```
nano /etc/lighttpd/lighttpd.conf
```

### Enable necessary modules ##################################
make sure the following modules are enabled (begin of the file). 
Disabled modules should be commented out. Other modules will be present.
'rewrite' and 'fastcgi' are required for phpMyDomo.
```
server.modules = (
        "mod_fastcgi",
        "mod_rewrite",
)
```

### Enable php via fastcgi ####################################
Add at the end of the file:
```
fastcgi.server = (
    ".php" => (
      "localhost" => ( 
        "socket" => "/var/run/php5-fpm.sock",
        "broken-scriptfilename" => "enable"
      ))
)

```

### Add phpMyDomo via virtualhosting ##########################
The bellow configuration will redirect any request whose host begin by "domo" to phpMyDomo.

Add at the end of the file:
```
$HTTP["host"] =~ "domo\..*" {
    server.document-root = "/var/www/domo/"
    server.errorlog = "/var/log/lighttpd/domo.error.log"
    accesslog.filename = "/var/log/lighttpd/domo.access.log"
    index-file.names            = ( "index.php")
    url.rewrite-once = (
       "^/controller\.php.*$" => "$0",
       "^/static/.*$"         => "$0", 
       "^/.+$"                 => "/controller.php$0"
    )
}
```

### Start lighttpd ############################################
start lighttpd
```
service lighttpd start
```

## Move the phpMydomo "www" directory as the web root directory ###############################
Now you have to move the content of the phpMyDomo/www/ directory to the directory that you defined as "root" in the VirtualHost configuration, ie:
```
mv /tmp/phpMyDomo/www/* /var/www/domo/
```
