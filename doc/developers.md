#Developers Guidelines

# Debugging
To develop, please set the $conf['debug']['show'] to '1' (or true), this will:
- displays php errors (according to the php error level set in $conf['debug']['level']
- Check any changes in Smarty templates and "compil" changes on the fly
- disallow the minify , for easier debugging
You an also temporately activate debug by adding ?debug=1 to the URL


# PHP, JavaScript Coding
Please follow the current coding style, use tabs rather than spaces.


# CSS writing
Please dont remove the following comments.
`
/* @group ----- */
(style here)
/* @end */
`
They are needed to nicely format CSS in editors like Expresso (formely CSSEdit) 
See: http://stackoverflow.com/questions/5765887/group-and-override-in-css for more information


# Pull requests
- Please follow the current code style writing 
- submit to the 'develop' branch, using git-flow features : http://nvie.com/posts/a-successful-git-branching-model/


# Roadmap
- finalize dimmers
- finalize/add new API clients
- sort devices
- better design (CSS, icons, etc...)
- move blocks / group config to a GUI, using a light file based database (listing devices groups, blocks, customs icons)
- better skins (with skin configs to override simple design choices, ie buttons sizes)
- user defined pages + smarty templates
- may be a dedicated website if many users interested
- Integrate xPl, as an api-client, to be able to send xPl messages
- DLNA/upnp controller
- Asterisk Call logs displays, and basic redial
- multiple API client support in a single phpDomo instance (if any interest)
- ....


# People needed
- Git gurus to become repo maintainers
- Involved Coders
- Documentations writers
- Designers
