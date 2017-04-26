# Logitech SqueezeBox Remote

The Music page (*pmd_ip/squeeze*) allows to control all SqueezeBox players registered at your Logitech Media Server.

## Features #########################################################################
 - Full transport controls (including *FastForward* and *Rewind*)
 - Keyboard shortcuts
 - Musician friendly In/Out cue points to precisely loop part of a song
 - Auto link each playing Artist/song to Youtube, IMDB, Google
 - Album Art display
 - Global players controls : Stop all players, Mute all player, change all players volumes, etc...
 - easy shortcut link to the IOS App, Android App and LMS server

## Configuration File #########################################################################
The sample configuration file *squeeze.php* (in the *inc/conf_sample/* directory) should be copied into the *inc/conf/* directory.

Then just change the **$prefs['url_server']** to match your own Logitech Media Server Url.

All registered players should then appear and be control-able from the Music (Squeeze) page.
