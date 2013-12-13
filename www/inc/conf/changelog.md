# phpMyDomo ChangeLog

## Version : 0.134 - Dec xx, 2013
- new: Dutch translation (thanks to Pepijn Goossens)
- new: (Experimental) Dimmers button implementation (Tested on openHab, Domoticz)
- new: Now default to openHab Api, which has a convenient ONLINE api demo
- new: Domoticz now supports type "Temp + Humidity", "Motion Sensor", "YouLess Meter"
- fix: 'Cache NotWritable' Error Help text
- dev: ApiFetchCustom for specific Fetch cases
- dev: Draft Pilight API (absolutely not tested,  certainly needs some fixes)

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
