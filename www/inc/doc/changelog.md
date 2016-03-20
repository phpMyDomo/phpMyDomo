# phpMyDomo ChangeLog

## Version : 0.200 - March 20, 2016
- new: Clock Page
- new: MySensors action
- new: Domoticz : implements Selector Button types
- new: Screensaver mode : clock or photoframe album
- new: Now support Nabaztag voices
- new: in Nabaztag Action : implements stream mode, voice selection, force action when sleeping, and various utility modes
- new: Lighttpd install documentation (thanks to cblomart)
- new: IOS fullscreen web app
- fix: No php warning on some servers without HTTPS
- fix: Minimum HTTP Timeout for faster actions
- fix: photo album count badge in Firefox
- fix: buttons height for Firefox
- fix: Domogik : state for dimmers
- dev: Domogik API cleanup
- dev: allow per devices dim min/max level
- dev: recursive merge of language

## Version : 0.144 - April 23, 2014
- new: Custom devices icons
- new: Better Domogik 0.3 support (Thanks to Thefrip), ... Work In Progess!
- new: SunSet & Sunrise for servers missing it (using a new [app][location] config. parameter )
- new: Actions Debounce : prevents identicals actions to be triggered before the debounce period
- new: Photoframe click back to home when launched in ScreenSaver Mode
- new: Domoticz Blinds Inverted, Contacts
- new: Blinds & Shutter Buttons
- new: OpenHab shows Server's Time
- new: Sensors now show states
- fix: Better HTTPS detection (Thanks to JobPilot, cblomart)
- fix: Domoticz Set Blinds
- fix: OpenHab smartest Names
- fix: Hide Calendar information, if not set
- fix: Dimmer commands in Domogik
- fix: Apache Setup Documentation

## Version : 0.143 - Feb 08, 2014
- new : PhotoFrame ScreenSaver mode : automatically switch to photoframe when idle
- new : Links Block : Custom links to various website
- new : Hide browser address bar on mobiles
- new : Show/hide sensors names in groups titles
- new : UK language (same as EN language, but using the 24h format)
- fix : Show missing sensors units in groups titles
- fix : Fix Domotiga DIM commands

## Version : 0.142 - Jan 03, 2014
- new : PhotoFrame display your photo library (Directory based or from iPhoto)
- fix : Remove (online) Google Font dependencies
- new : Cameras FullScreen Mode
- fix : Cameras list scaled
- fix : Domotiga v1.0.013 compatibility (breaks v1.0.012 compatibility)
- fix : Devices sorted according to the user language

## Version : 0.141 - Dec 24, 2013
- new : Add (unmerged) actions Email, XBMC, Growl
- new : Action Nabaztag (using OJN)
- new : Action Prowl (IOS devices)
- new : Action NMA (Android devices)

## Version : 0.140 - Dec 23, 2013
- new: Introduces Actions (notifications)
- fix: New Domoticz Types : UV, Radiation, Visibility
- fix: IOS icon
- dev: fix PageError in api clients
- dev: add Demo mode
- fix: auto-truncate long names in blocks
- fix: footer no longer triggers scroll arrows

## Version : 0.134 - Dec 19, 2013
- new: (Experimental) Dimmers button implementation (Tested on openHab, Domoticz)
- new: Dutch translation (thanks to Pepijn Goossens)
- new: German translation (thanks to Juergen Kimmel)
- new: Now default to openHab Api, which has a convenient ONLINE api demo
- new: Domoticz now supports type "Temp + Humidity", "Motion Sensor", "YouLess Meter", "P1 Smart Meter"
- new: CSS enhancements
- new: Additionnal Metal skin
- new: Nginx Install HowTo (Thanks to Hans Rune)
- new: Directly check update NOW from the home page , with ?update  in the url (/home?update)
- fix: Devices, Command and Sensors Page sorted by Type & Name
- fix: better type icons
- fix: 'Cache NotWritable' Error Help text
- dev: ApiFetchCustom for specific Fetch cases
- dev: Draft Pilight API (absolutely not tested,  certainly needs some fixes)
- dev: "current" type renamed to "consum"
- dev: Devices page shows a full Dump when adding ?dump to the url
- dev: Starting to support Blinds type in Domoticz
- dev: move doc folder to www/inc/doc
- dev: move conf/config-sample.php to to conf_sample/config.php
- dev: move changelog.md to www/inc/doc/

## Version : 0.133 - Dec 11, 2013
- new: Implement openHab API
- new: Custom server port can be defined in the config file (if you're updating see config-sample for examples)
- new: Cameras sizes can be defined
- dev: Language files: date information + use only double quotes
- dev: Dimmers slider view (dont currently send data)
- new: Devices Page shows commands values (ie for dimmers)
- new: RGB icons, + nicer Groups icons 
- new: Nicer Error Page 
- dev: Nicer Debug Page 
- new: Allow Installation of PMD in a sub-directory
- fix: Index page now checks if .htaccess if already present

## Version : 0.132 - Dec 10, 2013
- fix: Domogik API values
- new: Update show ChangeLog from remote new Version
- fix: CSS for the Update page
- fix: Better Installation instructions
- dev: VersionCheck reports version and API used

## Version : 0.131 - Dec 9, 2013
- fix: show correct sensors names in dashboard
- dev: hide commands page buttons not implemented
- new: phpMyDomo.org web site
- new: Check latest version available, and help for updating

## Version : 0.13 - Dec 6, 2013
- new: Domotiga API : support List Device, and maybe Switch command (not tested)
- dev: start implementing Dimmers
- dev: Rewrite ApiClient_root
- dev: Show debug in device list
- new: Implement ChangeLog

## Version : 0.12 - Dec 4, 2013
- new: Added Basic Update procedure in /utils/update
- dev: ?compil=1 now rebuild the minify files
- dev: Move Home Bocks to separate template
- dev: InstallBootstrap Slider
- fix: Update Dependencies to php >= 5.3 + php-curl
- dev: Install json_rpc v2 LIB

## Version : 0.11 - Dec 2, 2013
- fix: Documentation
- fix: installer correctly set Version
- dev: New devide Types

## Version : 0.1 - Dec 1, 2013
- new: Initial Release
