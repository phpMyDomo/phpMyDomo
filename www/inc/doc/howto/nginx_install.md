# Setup Nginx for phpMyDomo

_For experts only: Using the Apache webserver is the recommended way to install phpMyDomo, but if you prefer to use Nginx instead of Apache, you might try the following **unsupported** method._


## Install Required packages ############################################
As root:

```
apt-get update
apt-get install nginx php5-fpm php5 php5-curl
```


## Edit php config ############################################
```
nano /etc/php5/fpm/php.ini
```
And change *cgi.fix_pathinfo=0* to **cgi.fix_pathinfo=1**

Then restart php:
```
service php5-fpm restart
```


## Make a Virtual Host for phpMyDomo ##############################################
```
nano /etc/nginx/sites-available/phpMyDomo
```

Add something like this (change according to your own needs)
```
# You should look at the following URL's in order to grasp a solid understanding
# of Nginx configuration files in order to fully unleash the power of Nginx.
# http://wiki.nginx.org/Pitfalls
# http://wiki.nginx.org/QuickStart
# http://wiki.nginx.org/Configuration
#
# Please see /usr/share/doc/nginx-doc/examples/ for more detailed examples.
##

server {
	listen   8090; ## listen for ipv4; this line is default and implied
	#listen   [::]:80 default_server ipv6only=on; ## listen for ipv6

	root /var/www/html;
	index index.php index.html index.htm;

	# Make site accessible from http://whatever/
	server_name _ default;

	location / {
		# RewriteRule ^(.*)controller\.php$ - [L]
		# RewriteRule ^(.*)static/ - [L]
		# RewriteCond %{REQUEST_FILENAME} !-f
		# RewriteCond %{REQUEST_FILENAME} !-d
		# RewriteRule . controller.php [L]
                if ($request_filename ~* ^.*controller.php$ ) { 
                    break;
                }
                if ($request_filename ~* ^.*static/ ) { 
                    break;
                }
                if (!-e $request_filename) { 
                    rewrite ^.*$ /controller.php last; 
                    break;
                }
		# First attempt to serve request as file, then
		# as directory, then fall back to displaying a 404.
		try_files $uri $uri/ /index.html;
		# Uncomment to enable naxsi on this location
		# include /etc/nginx/naxsi.rules
	}

	location ~ \.php$ {
            fastcgi_pass unix:/var/run/php5-fpm.sock;
            fastcgi_index index.php;
            include fastcgi_params;
        }
}

```

Enable it and restart Nginx:
```
ln -s /etc/nginx/sites-available/phpMyDomo /etc/nginx/sites-enabled/phpMyDomo
service nginx stop
service nginx start
```

## Move the phpMydomo "www" directory as the web root directory ###############################
Now you have to move the content of the phpMyDomo/www/ directory to the directory that you defined as "root" in the VirtualHost configuration, ie:
```
mv /tmp/phpMyDomo/www/* /var/www/html/
```


