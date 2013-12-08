#Developers Guidelines

# Debugging
To develop, please set the $conf['debug']['show'] to '1' (or true), this will:
- displays php errors (according to the php error level set in $conf['debug']['level']
- Check any changes in Smarty templates and "compil" changes on the fly
- disallow the minify , for easier debugging
You an also temporately activate debug by adding ?debug=1 to the URL


# Pull requests
- Please follow the current code style writing 
- submit to the 'develop' branch, using git-flow features : http://nvie.com/posts/a-successful-git-branching-model/


# Roadmap
- finalize dimmers
- finalize/add new API clients
- better design (CSS, icons, etc...)
- better skins (with skin configs to override simple design choice, ie buttons sizes)
- user defined smarty templates
- may be a dedicated website if many users interested
- Integrate xPl ,as an api-client, to be able to send xPl messages
- DLNA/upnp controller
- multiple API client support in a single phpDomo instance (if any interest)
- ....


#needed
- Git gurus to become maintainers
- Documentations writers
- Coders
- Designers
