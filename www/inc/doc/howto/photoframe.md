# PhotoFrame

## Description #############################################################################
If you're using a tablet as your dedicated PMD Controller, you might like to have a nice PhotoFrame displayed, when you're not using the tablet to control or monitor your HomeAutomation Software.
Here comes the "PhotoFrame" page, that will list all your Photos Albums, and display it like a real PhotoFrame, in fullscreen mode.


## Main Configuration #############################################################################
You first have to choose a photoframe mode in the main config_file. ie `$conf['app']['photoframe']		="directory";`

There are currently two modes available:
- **directory**: Lists photos from a directory of albums directories.
- **iphoto**: Parses an existing iPhoto Library, and display all albums and photos listed in the iPhoto database.


## Demo #################################################################################################
To understand how it works, you should just use the default `$conf['app']['photoframe']		="directory";` setting and be sure to have the default "photoframe_directory.php" file present in your *conf* directory. Then with your web-browser goes to the /photos page.  

You can also try the online demo at:
http://www.phpmydomo.org/demo/www/photos


## Photos location ###############################################################################
In order to display your photos from a web-browser, they have to be located in a sub-directory of the PMD 'www' folder!  

I strongly advice you to put them in a `www/inc/conf/xxxx/` directory, because this folder will never be changed by Git updates (or if you do update manually, you already know that you have to move the *conf* folder to a safe place before erasing the whole PMD content).

### Tips ######
A simple way to keep your current photos library at a convenient place, while making them accessible by PMD, is to use a symbolic link.  
Example: You have your photo library on a NAS that can be shared via NFS. You configure an NFS Export on the NAS, ie for the `/volume1/photos` directory.  

Then on your PMD server you mount the NAS's NFS export:
```
mkdir /mnt/my_photos
mount -t nfs -o defaults,user,auto,noatime,intr,ro 192.168.0.10:/volume1/photos /mnt/my_photos
```

You now make a symbolic link from the mount point `/mnt/my_photos` to the `PMD/www/inc/conf/my_photos` directory: 
```
ln -s /mnt/my_photos PMD/www/inc/conf/my_photos
``

*You have to ensure that your Apache VirtualHost allows to follow Symbolic links, by using the "Options FollowSymLinks" directive.*



## PhotoFrame Configuration #############################################################################

For each mode available you will find a configuration file in `PMD/inc/conf_sample`, named "photoframe_MODE.php" (where MODE is the photoframe mode you have chosen in the main configuration file). You have to move this file in the `PMD/inc/conf` directory and change its setting according to your own preferences.

The main setting required is the `$photo['path']` that is used to tell PMD where your photo directory is located.

Then you might want to change some preferences of the PhotoFrame player: Most are either self explaining or documented inside the configuration file.

