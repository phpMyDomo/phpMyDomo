# phpMyDomo
_The ultimate Domotic remote for the rest of us !_

http://www.phpmydomo.org


## About phpMyDomo

phpMyDomo is an open-source Web Application aimed to provide a clean, robust, customizable, fast and intuitive interface to many Home Automation software. With todays chinese phones and tablets starting as low as $50, it becomes a cheap and powerfull way to build remotes to control anything smartly in your house. phpMyDomo wants to be the ultimate WebApp that works as well on these devices, as on any computer browser.
phpMyDomo don't aim to replace the cool domotic's software you're currently using, but rather to add a convenient web interface to it.

Currently supported software include __Domoticz__, __Domotiga__, __OpenHab__ and __Domogik__. (+ draft Pilight)

This is a Work In Progress: Depending on the chosen server API, some features might not be fully working at this time.

## Features
- Allow to Switch on/off or Dim (experimental) any supported devices or scenes, from the Dashboard page
- View all sensors at once, or per user defined groups
- View all cameras on a single page
- Responsive design : Display fine on any Android/IOS tablet, phone and any modern (HTML5) web browser
- Multiple Skins, build your own easily
- Multiples languages supported
- Any Home Automation Software supported via Api Plugins
- Easily customizable
- Fast loading
- Minimal server requirements : a php-enabled web server
- Object oriented, MVC design : easily build your own custom pages, in minutes...
- Free and Open Source
- Highest WAF (Wife acceptation Factor) possible


## Screenshots
![alt text](doc/screenshots/01.png?raw=true "Main page (Default Skin)")
![alt text](doc/screenshots/02.png?raw=true "Main page on phone (Metal Skin)")
![alt text](doc/screenshots/03.png?raw=true "Main page (Black Skin)")
![alt text](doc/screenshots/04.png?raw=true "Devices List (Black Skin)")


## Install
Just [download the 'Master' archive](https://github.com/phpMyDomo/phpMyDomo/archive/master.zip), and follow the [Install guide](www/inc/doc/INSTALL.md), or
install from git : `git clone https://github.com/phpMyDomo/phpMyDomo.git`


## Update
Either updade from git : `git pull` (in the root phpMydomo directory) 
or 
 - keep a copy of your config file (from phpMyDomo/www/inc/conf/config.php )
 - replace the phpMyDomo directory, from the latest [Master archive](https://github.com/phpMyDomo/phpMyDomo/archive/master.zip)
 - copy your original config.php file back in the phpMyDomo/www/inc/conf/ directory
 
 then go to http://[your_web_server]/utils/update from your web browser


## Skills Wanted !
One of the goal of this app is to gather talented developers, designers and users, to build the coolest WebApp that can control any smart domotic's software.

What's needed:

- Finalize and test current Domoticz, Domotiga, OpenHab, Pilight, and Domogiz APIs.
- Language Files.
- API plugins coders to support others domotic's software.
- Talented CSS and Photoshop designers to make Skins, and make the coolest GUI ever ;-)
- Maintainers: to allow/disallow pull requests, follow issues, etc...
- Writers/Authors: to enhance documentation, fix my bad english, etc...
- Testers: to report bugs and suggestions...
- Fans: to promote phpMyDomo around the web !
- Any ideas or code to make it better.... ;-)

To resume, if you have a skill, and wish to contibute : You're Welcome !
__So Please, fork this Repo, and make Pull-Requests to the Develop branch ;-)__


## Support
The software is provided as is, and need a minimum computer expertise to be installed and configured. I won't provide any support by email , phone, tickets, etc...  but if you're offering money, I might change my mind... ;-)

If something doesn't works, look at the code. If the code is missing, well, code it and make a pull request. If you're not a coder yourself, I (or any other developers)  might implement missing devices only if we own the device. We can't blindly support a gear that we can't test ourself.

BTW: Offering me a device not currently supported is a cool and cheap way to say "Thank you", and might certainly encourage me to implement the device fastly. :-D
