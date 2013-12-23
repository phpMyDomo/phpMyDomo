# Developers Guidelines

## Debugging #############################################################################
To develop, please set the `$conf['debug']['force']` to '1' (or true), this will:
- displays php errors (according to the php error level set in `$conf['debug']['level']` )
- Check any changes in Smarty templates and "compil" changes on the fly
- disallow the minify , for easier debugging
You an also temporately activate debug by adding `?debug=1` to the URL.


## PHP, JavaScript Coding ################################################################
Please follow the current coding style, and use tabs rather than spaces.

## API Plugins Development ###############################################################
To develop new API plugin you basically have to:
- Fetch all devices and their value from the API server.
- Define Set and Dim command.  
To quickly understand how it work, I suggest you to try the OpenHab API as, because it has an online accessible API.

If you need to use a different protocol than json , json_rpc2, GET or POST, you would either implement it in the API_client_root if it's a standart (ie XML),  either you build your own methods by extending the ApiFetchCustom method.

When in the devices page, you can simply add `?dump` to the URL (http://[server]/devices?dump) to display the raw dump of all devices.


## CSS writing & Skins ###################################################################

#### Skins 
- At any time you can change the skin on the fly by using ?skin=xxx in the url, ie:
`/home?skin=black`, `/home?skin=default` or `/home?skin=your_skin`

#### StyleSheets 
- If you're Not in debug mode, all CSS are minified, and cached, thus viewing change on the fly is not possible.
To prevent this, active the debug mode either in the config file ( `$conf['debug']['force']` ) or add `?debug=1` to your url.

- Please dont remove the following comments. in stylesheets
```
/* @group ----- */
(style here)
/* @end */
```
They are needed to nicely format CSS in editors like Expresso (formely CSSEdit) 
See: http://stackoverflow.com/questions/5765887/group-and-override-in-css for more information


## Git ###################################################################################
- Please follow the current code style writing 
- submit to the 'develop' branch, using git-flow features : http://nvie.com/posts/a-successful-git-branching-model/
- the gh-pages branch is only for phpMyDomo.org web site: dont merge it in develop
- If you don't already know the amazing free SourceTree git(+mercurial) client (mac & Pc), get it from http://www.sourcetreeapp.com/
You will certainly LOVE it!


## Roadmap ###############################################################################
- finalize dimmers
- finalize/add new API clients
- actions triggered by http Get requests : ie email, growl notifications, XBMC notifications, xPl message....
- better design (CSS, icons, etc...)
- move blocks / group config to a GUI, using a light file based database (listing devices groups, blocks, customs icons)
- better skins (with skin configs to override simple design choices, ie buttons sizes)
- user defined pages + smarty templates
- PhotoFrame like page
- Integrate xPl, as an api-client, to be able to send xPl messages
- DLNA/upnp controller
- Asterisk Call logs displays, and basic redial
- HTML5 sensor Graphs , using highcharts (www.highcharts.com)
- external links
- multiple API client support in a single phpDomo instance (if any interest)
- ....


## People needed #########################################################################
- Git gurus to become repo maintainers
- Involved Coders
- Documentations writers
- Designers

