<?php
/*

PLEASE READ the /phpMyDomo/doc/howto/squeeze.md for more informations

## Description  ----------------------------------------------------------------------------------
Display a remote for all SqueezeBox players in your LAN

## Requirements  ----------------------------------------------------------------------------------
A local Logitech Server
*/

// ##############################################################################
// Squeeze Preferences  ##########################################################
// ##############################################################################

// Server URL #######################
// Format : http://IP:PORT, ie : http://192.168.1.222:9000
$prefs['url_server']			='http://192.168.1.222:9000';

// Refresh States time ##############
// the display is updated every [refresh_states] milliseconds, to correctly display the ciurrent title and buttons states.
// If you set it to low, you might overload your server
$prefs['refresh_states']		=5000;

// Refresh Counters time #############
// the real-time counter is updated every [refresh_counters] milliseconds. This also set the minimum steps of the cue points and loop.
$prefs['refresh_counters']		=5;

// Scroll times ######################
// how much time (in second ) to jump when pressing << / >> buttons
$prefs['scroll_time1']			=5;
$prefs['scroll_time2']			=10;

// Cue Offset #######################
// Musicians? Wanting perfect jump on beat  when using the Cue or Loop? Try to compensate the network latency by setting a small offset (in ms)
$prefs['cue_offset']			=300;

// Cue Nudeges values (in ms) #######################
$prefs['cue_nudge1']			=10;
$prefs['cue_nudge2']			=2;

// Keyboard Shortcuts #######################
$prefs['key_cue_in_set']		='e';
$prefs['key_cue_in_nudge2down']	='S';
$prefs['key_cue_in_nudge1down']	='s';
$prefs['key_cue_in_nudge1up']	='d';
$prefs['key_cue_in_nudge2up']	='D';
$prefs['key_cue_in_jump']		='x';

$prefs['key_cue_out_set']		='t';
$prefs['key_cue_out_nudge2down']='F';
$prefs['key_cue_out_nudge1down']='f';
$prefs['key_cue_out_nudge1up']	='g';
$prefs['key_cue_out_nudge2up']	='G';
$prefs['key_cue_out_jump']		='v';
$prefs['key_cue_loop']			='c';

$prefs['key_play']				='p';
$prefs['key_pause']				=' ';
$prefs['key_prev']				='u';
$prefs['key_next']				='i';
$prefs['key_rw2']				='J';
$prefs['key_rw1']				='j';
$prefs['key_ff1']				='k';
$prefs['key_ff2']				='K';

?>