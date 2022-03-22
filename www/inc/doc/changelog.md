# phpMyDomo ChangeLog

## Version : 22.01xx - Jan xx , 2022
- new: OpenWrt also List stations
- fix: smaller Ajax responses (/5)
- new: Domoticz - Implements Thermostats
- dev: Add 'Value' buttons, used by new 'therm' type



## Version : 21.1230 - Dec 30 , 2021
- new: Allows to spread 'groups' and 'blocks' into multiple Dashboard pages
- new: Domoticz - Add new 'volume_sub' types  and handle volume sensors (ie Water)


## Version : 21.0605 - June 5 , 2021
- new: Dashboard is now fully REALTIME (no longer refreshes the page)
- new: Dashboard sensors values highlighted for a few seconds, when updated
- fix: Dashboard - Also links group's sensors to (Domoticz only) log pages
- fix: Domoticz - Dimmer level scale
- fix: Domoticz - Add new types : Percentage, Power, Current, Smoke
- fix: Squeeze - Playlist target correctly memorized by cookie
- fix: Squeeze - No longers bugs when some player are using undefined data (ie ShairTunes)
- new: Admin Pages reorganized
- new: OpenWrt Tool page to monitor connections in realtime on multiple AccessPoint (running OWRT) - EXPERIMENTAL
- fix: typo in some HTML titles
- fix: Slightly lighter sensors colors (in the Black Skin)
- new: Version naming follows release's date

## Version : 0.600 - December 22 , 2019
- new: Domoticz - Handles RGB, RGBW buttons
- new: RGB buttons: Full color picker
- new: RGB buttons: Colors Presets
- new: Brand new 'gorgeous' Squeeze Player
- new: Squeeze - LCD like display
- new: Squeeze - Show playlist next songs
- new: Squeeze - Add currently playing song to a playlist
- new: Squeeze - Clickable Progress bar
- new: Squeeze - Links to Sheet music, Lyrics
- new: Squeeze - Song Information (Tempo, year, reaminig time, file type)
- fix: Don't display "ghost" players remaining registered at LMS
- fix: Various bugs fixes
- dev: Load Api client ONLY when needed
- dev: FontAwesome v4.7

## Version : 0.500 - March 16, 2019
- new: Support for OpenHab v2 API (v1 still available as 'openhab1')
- new: Domoticz - Clicking on Sensor values links to their graphs (log) page.
- new: Domoticz - New sensors types supported: custom, text, alert, mediaplayer
- fix: Fixes selectors for the latest Domoticz versions (names are now base64 encoded)
- dev: New sensors Types : 'mediaplayer', 'text', 'date', 'time', 'custom'
- new: XBMC Action - renamed to 'kodi'. Update your calls ("type=xbmc" becomes "type=kodi" in the url)
- fix: XBMC Action - parameter 'image' renamed to 'icon'. . Update your calls ("image=" becomes "icon=" in the url)
- new: Growl Action - handles Notifications Groups.
- new: Growl Action - accepts unfiltered 'title' and 'message' parameters
- new: Localize Numbers formats
- fix: Devices Pages - smallers rows
- fix: Black Theme - lighter text color in right block & devices pages
- fix: Better Debug Popover positioning in devices page
- new: Always show Debug Popover (on icon click) in devices page
- fix: Squeezebox display in Medium viewport
- fix: Timer audio files enhancements
- fix: 'HTTP_PORT' constant in some funky php installation
- dev: freedomotic draft implementation
- dev: MySensors documentation fix (mtype)

## Version : 0.300 - April 27, 2017
- new: Implements Logitech SqueezeBox Remote controller
- new: Allows (multiple) warnings (red highlight) whenever sensor values change
- new: Allows custom blocks
- new: Domoticz Push Buttons
- new: Domoticz supports Door Contacts, Luminosity and Distance sensors
- new: Italian Language (Thanks to Aldodemi)
- fix: MySensors 'type' field renamed to 'mtype'
- fix: Time display in calendar block
- dev: Debug Device Popover no longer under NavBar
- dev: 'utils_calc' page allows conversion between AB400 jumpers / House,Unit Code
- dev: Minify updated to v2.3
- dev: FontAwesome updated to v4.6.3
- dev: latest MySensors_php_API library
- dev: allow to force language in the URL using 'lang='

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
