# phpMyDomo
_The ultimate Domotic remote for the rest of us !_  

[www.phpmydomo.org](http://www.phpmydomo.org)


## About phpMyDomo

phpMyDomo is an open-source Web Application aimed to provide a clean, robust, customizable, fast and intuitive interface to many Home Automation software.

With todays chinese phones and tablets starting as low as $50, it becomes a cheap and powerfull way to build remotes to control anything smartly in your house. phpMyDomo wants to be the ultimate WebApp that works as well on these devices, as on any computer browser.
phpMyDomo don't aim to replace the cool domotic's software you're currently using, but rather to add a convenient web interface to it.

Currently supported software include __Domoticz__, __Domotiga__, __OpenHab__ and __Domogik__. (+ Pilight & Freedomotic drafts)

This is a Work In Progress: Depending on the chosen server API, some features might not be fully working at this time.

## Features

- Allows to switch on/off or Dim any supported devices or scenes, from the Dashboard page
- Supports RGB(W) buttons with color picker and color presets
- View all sensors at once, or per user defined groups
- View all your IP Cameras on a single page
- Display your photo library from a PhotoFrame like page
- Display Clock and favorites sensors in a Wheater Station like page
- Controls all your SqueezeBox Players in the LAN
- Launch Actions (ie Notifications) triggered by your HomeAutomation software (a simple URL request)
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

## Demo

Please go the [phpMyDomo Demo site](http://www.phpmydomo.org/demo/www) for a live preview.


## Screenshots

![alt text](www/inc/doc/screenshots/01.png?raw=true "Main page (Default Skin)")
![alt text](www/inc/doc/screenshots/02.png?raw=true "Main page on phone (Metal Skin)")
![alt text](www/inc/doc/screenshots/03.png?raw=true "Main page (Black Skin)")
![alt text](www/inc/doc/screenshots/04.png?raw=true "Devices List (Black Skin)")
![alt text](www/inc/doc/screenshots/05.png?raw=true "Squeezebox Player (Black Skin)")


## Install

Just [download the 'Master' archive](https://github.com/phpMyDomo/phpMyDomo/archive/master.zip), and follow the [Install guide](www/inc/doc/INSTALL.md), or
install from git : `git clone https://github.com/phpMyDomo/phpMyDomo.git`


## Update

*You can browse the [ChangeLog](www/inc/doc/changelog.md), no see what's new.*

The are 2 methods to update PMD: Either from git (fastest) or manually.

- Update from git : `git pull` (in the root phpMydomo directory). Then go to `http://[your_web_server]/utils/update` from your web browser, to finish the procedure.

- Manually : you will see a link in the footer of each PMD page, indicating if there is an update available.
Click the 'available' link, and follow the instructions (be sure to keep a copy of your /PMD/www/inc/conf/ folder).


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

The software is provided as is, and needs a minimum computer expertise to be installed and configured. I won't provide any support by email , phone, tickets, etc...  but if you're offering money, I might change my mind... ;-)

If something doesn't works, look at the code. If the code is missing, well, code it and make a pull request. If you're not a coder yourself, I (or any other developers)  might implement missing devices only if we own the device. We can't blindly support a gear that we can't test ourself.

BTW: Offering me a device not currently supported is a cool and cheap way to say "Thank you", and might certainly encourage me to implement the device fastly. :-D
