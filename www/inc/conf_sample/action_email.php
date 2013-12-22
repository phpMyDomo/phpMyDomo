<?php
/*

PLEASE READ the /phpMyDomo/doc/howto/actions.md for more informations

## Description  ----------------------------------------------------------------------------------
This action send an email

## Requirements ---------------------------------------------------------------------------------------
The email Action requires that you have an email server setuped on the phpMyDomo host, and that it works
You can chek this from the terminal, by trying to send you an email using:
mail my_email@address.com
(then enter a subject, then enter a content, then type a DOT then enter to send the mail). 
If you receive the mail, it will work, else you have to insatll a mail server, ie Postfix or Exim.

## Fields  ----------------------------------------------------------------------------------
- 'type'	=> (mandatory) 'email'
- 'from' 	=> (required) The "From" email,	formated as : "Name <email@server.com>" or "email@server.com".
- 'to'   	=> (required) The "To " email,	formated as : "Name <email@server.com>" or "email@server.com".
- 'subject'	=> (required) Email subject
- 'content'	=> (required) Email Content
- 'custom'	=> (optionnal) replaces "{custom}" in the email content

## URLS examples ----------------------------------------------------------------------------------
/action?type=email&preset=door_ring  
/action?type=email&preset=door_ring&custom=portal  
/action?type=email&preset=door_ring&to=anotheremail@server.com  
/action?type=email&subject=This+is_an+example&content=testing+a+custom+email  

*/

// ##############################################################################
// Global Configuration  #######################################################
// ##############################################################################

$action['globals']['from']		="phpMyDomo <phpmydomo@server.local>"; // use a working email, ie yours
$action['globals']['to']		="myemail@server.com";
$action['globals']['subject']	="phpMyDomo Notification";
$action['globals']['content']	="Empty Content";


// ##############################################################################
// Presets  #####################################################################
// ##############################################################################

// Make your own preset like this:
//$action['presets']['PRESET_NAME']['FIELD']="your value here";

//examples ---------------------
$action['presets']['door_ring']['subject']="Someone is ringing at the door";
$action['presets']['door_ring']['content']="I've just realized that someone is ringins at door {custom}";

$action['presets']['snail_mail']['subject']="The postman has dropped a mail";
$action['presets']['snail_mail']['content']="I've just realized that you've got a smail mail in your mailbox";

$action['presets']['snail_mail_mobile']['subject']="The postman has dropped a mail";
$action['presets']['snail_mail_mobile']['content']="I've just realized that you've got a smail mail in your mailbox";
$action['presets']['snail_mail_mobile']['to']	  ="alternative_email@server.com";

?>