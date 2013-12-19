# Actions

Actions are usefull to trigger an action (usually a notification), depending on the state of a command or a switch.  
Ie: You might want to receive an Email when someone ring at the door, or when a Motion sensor is triggered.

Actions are triggered from an URL request like this:  
http://pmd_host_or_ip/action?type=ACTION

phpMyDomo is not an Home Automation server himself, so you will have to define this automation on your own server, with something like:
``WHEN switch_XXX IS ON THEN TRIGGER HTTP_REQUEST "http://pmd_host_or_ip/action?type=ACTION&preset=door_ring"```
The syntax, or GUI to define this automation, will obviously depend on the server you're using, and if it is able to send  HTTP requests.


## Configuration File #########################################################################
Each possible actions are defined in the PMD/www/inc/action/ directory.  
Each one need to have its configuration file in the PMD/www/inc/conf/ directory.

Example if you want to use the "email" action, there must be an "action_email.php" file present (and configured) in the *PMD/www/inc/conf/* directory.

Sample configurations can be found in the *PMD/www/inc/conf_sample/ directory* (if they are not already moved in the the *PMD/www/inc/conf directory*).


## Settings #########################################################################
Each action require some fields to be set in the configuration file. They are all detailed in the sample configuration file.

### Fields definitions ############################

Example : "email" action: 
```
## Fields  ----------------------------------------------------------------------------------
- 'type'	=> (mandatory) 'email'
- 'from' 	=> (required) The "From" email,	formated as : "Name <email@server.com>" or "email@server.com".
- 'to'   	=> (required) The "To " email,	formated as : "Name <email@server.com>" or "email@server.com".
- 'subject'	=> (required) Email subject
- 'content'	=> (required) Email Content
- 'custom'	=> (optionnal) replaces "{custom}" in the email content
```

All these fields need to be defined either:
- From the $action['globals']['FIELD'], ie `$action['globals']['to']="myemail@address.com";`
- From a Preset $action['presets']['PRESET_NAME']['FIELD'], ie `$action['presets']['ring_door']['subject']="Someone rings at the door";`
- Directly from the URL, ie : http://pmd_host_or_ip/action?type=email&to=another@email.com

### Globals Field ######################
Fields defined as global, are used when they are not defined elsewhere ie in the URL or in a preset.  
You should mostly want to fill them all.

### Fields Presets ######################
Preset are just groups of fields , that define their values. You can define as many preset as you want, with the following syntax:
```
$action['presets']['PRESET_NAME']['FIELD1']="VALUE1";
$action['presets']['PRESET_NAME']['FIELD2']="VALUE2";
```
You can define all fields in a preset, or simply some of them that will override those defined in the globals fields.

They will be simply called from an url like
http://pmd_host_or_ip/action?type=email&preset=PRESET_NAME


### Fields order Precedence: ######################
Most fields are generally required, but they will be initialized in this way: 
- First from the url, if it exists (ie: /action?FIELD=xxx)
- else from the $action['presets']['PRESET_NAME']['FIELD'] if a preset is passed to the url (ie: /action?preset=PRESET_NAME) and if the field is defined in the preset
- else from the  $action['globals']['FIELD'] if it is defined
- else you will get an error if a required field is missing

__So all you have to do is to fill all the "globals" fields, and define some presets that will override the fields defined as globals__

Then, you will just call a preset with an url like this:  
http://pmd_host_or_ip/action?type=email&preset=ring_door

If you may want to override some preset or global fields, you just add them to the URL, ie:  
http://pmd_host_or_ip/action?type=email&preset=ring_door&to=alternate@email.com



## Usage #################################################################################

Once you have setupped the configuration file of your action, you might make tests by going to:  
http://pmd_host_or_ip/action?type=ACTION&preset=YOUR_FIRST_PRESET

You will see a JSON encoded response, indicating if it worked or not.

_BTW: You can add "&debug" at the end of the URL, to show a HTML view of the response)_


### Url Syntax ######################
Fields are passed as field=value, starting with '?', and then separated by '&.   
ie: *http://phpmydomo_host_or_ip/action?type=email&field1=value1&field2=value2&field3=values3*
- type=ACTION is **always** required
- Field order in the URL doesn't matter!
- If you need a value with non ASCII characters (including space) you have to URL_ENCODE them. ie replace " "(space) by "%20" (or "+")  
	-> More at: http://en.wikipedia.org/wiki/Percent-encoding  
	-> Live Encoder at http://meyerweb.com/eric/tools/dencoder/


### Some URLS examples ##################
Call preset 'door_ring' (using to/from defined either in the preset or as globals)  
http://pmd_host_or_ip/action?type=email&preset=door_ring

Call preset 'door_ring', but with a custom text inserted in the content  
http://pmd_host_or_ip/action?type=email&preset=door_ring&custom=portal

Call preset 'door_ring', but send it To anotheremail@server.com
http://pmd_host_or_ip/action?type=email&preset=door_ring&to=anotheremail@server.com  

Send a mail to/from emails defined as global, using a custom subject and email  
http://pmd_host_or_ip/action?type=email&subject=This+is_an+example&content=testing+a+custom+email


You get the idea? ;-)


