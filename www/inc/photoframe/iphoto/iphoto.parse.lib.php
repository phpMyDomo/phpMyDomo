<?php

/*
Alexandre Feblot, 2005-2011
http://globs.org

This software is a computer program whose purpose is to let people share
their iPhoto Library on the web, and let their users easily download a
bundle of pictures.

This software is governed by the CeCILL  license under French law and
abiding by the rules of distribution of free software.  You can  use, 
modify and/ or redistribute the software under the terms of the CeCILL
license as circulated by CEA, CNRS and INRIA at the following URL
"http://www.cecill.info". 

As a counterpart to the access to the source code and  rights to copy,
modify and redistribute granted by the license, users are provided only
with a limited warranty  and the software's author,  the holder of the
economic rights,  and the successive licensors  have only  limited
liability. 

In this respect, the user's attention is drawn to the risks associated
with loading,  using,  modifying and/or developing or reproducing the
software by the user in light of its specific status of free software,
that may mean  that it is complicated to manipulate,  and  that  also
therefore means  that it is reserved for developers  and  experienced
professionals having in-depth computer knowledge. Users are therefore
encouraged to load and test the software's suitability as regards their
requirements in conditions enabling the security of their systems and/or 
data to be ensured and,  more generally, to use and operate it in the 
same conditions as regards security. 

The fact that you are presently reading this means that you have had
knowledge of the CeCILL license and that you accept its terms.
*/

/*

--------------- Photos ---------------
Array
(
    [4297] => Array
        (
            [PhotoId] => 4297
            [MediaType] => Image
            [Caption] => DSCF0248.JPG
            [Comment] => 
            [Timestamp] => 1134752646
            [ImagePath] => 2005/12/16/DSCF0248.JPG
            [ThumbPath] => 2005/12/16/Thumbs/4297.jpg
            [Keywords] => Array         (only set if there are keywords)
                (
                    [0] => 3
                    [1] => 5
                    [2] => 7
                    ....
                )
        )
    ....
)
--------------- Keywords ---------------
Array
(
    [1] => _Favorite_
    [6] => Anniversaire
    [4] => Enfants
    ....
)
--------------- Albums ---------------
Array
(
    [999000] => Array
        (
            [AlbumId] => 999000
            [AlbumName] => Phototheque
            [PhotoIds] => Array
                (
                    [0] => 90
                    [1] => 93
                    [2] => 91
                    ....
                    [793] => 4297
                )

            [Master] => TRUE     (only set if this is the master)
            [Parent] => 255
            [AlbumType] => Special Roll, Special Month, Subscribed, Folder, Regular...
        )
    ....
*/


define('PARSER_NOWHERE', 0);
define('PARSER_IN_ALBUM_LIST', 10);
define('PARSER_IN_ALBUM', 20);
define('PARSER_IN_ALBUM_CONTENT', 30);
define('PARSER_IN_KEYWORD_LIST', 40);
define('PARSER_IN_PHOTO_LIST', 50);
define('PARSER_IN_PHOTO', 60);
define('PARSER_IN_PHOTO_KEYWORDS', 70);

define('EVENT_MASTER_ALBUM', 9991343); // Random bug number for a fake Events master album

//=============================================================================
class IphotoParser {
    var $xml_parser;
    var $state;
    var $content;
    var $key;
    var $levels;    // array of stacks for xml keywords
    
    var $photo;
    var $photos;

    var $masterAlbumId; // temp variable
    var $album;
    var $albums;
    
    var $keywords;

    var $version;

    var $archivePath;

    var $filesize;
    var $fp;
    var $nbRead;

    //---------------------------------------------------------
    function IphotoParser($file) {
        $this->filesize = filesize($file);
        $this->fp = fopen($file,"r") or die("Error reading xml file.");
        $this->nbRead = 0;

        $this->state = PARSER_NOWHERE;
        $this->photos = array();
        $this->albums = array();
        $this->keywords = array();
        $this->levels = array();
        $this->version = "";
        $this->showRolls = FALSE;

        $this->archivePath = dirname($file).'/';    // Don't rely on the value in AlbumData, it may be null

        $this->dbProperties = NULL;
        $this->AspectRatioStatement = NULL;

       // Create an XML parser
        $this->xml_parser = xml_parser_create();
        // Set the functions to handle opening and closing tags
        xml_set_element_handler($this->xml_parser, array(&$this, "startElement"), array(&$this, "endElement"));
        // Set the function to handle blocks of character data
        xml_set_character_data_handler($this->xml_parser, array(&$this, "characterData"));
    }

    //---------------------------------------------------------
    // Parses a little bif of the file and returns the percentage parsed.
    function parseLittle() {

        $data = fread($this->fp, 4096);

       // Parse each 4KB chunk with the XML parser created above
       xml_parse($this->xml_parser, $data, feof($this->fp))
           // Handle errors in parsing
           or die(sprintf("XML error: %s at line %d",  
               xml_error_string(xml_get_error_code($this->xml_parser)),  
               xml_get_current_line_number($this->xml_parser)));

        // If the version is known and is a bad one (before v4), stop parsing (return 100%)
        if (empty($data) or feof($this->fp) or (!empty($this->version) and $this->version < "4.0.0")) {
            // Close the XML file
            fclose($this->fp);
            return 1.0;
        } else {
            $this->nbRead += strlen($data);
            return $this->nbRead / $this->filesize;
        }
    }

    //---------------------------------------------------------
    function getResults(&$photos, &$albums, &$keywords) {
        // remove all albums entries which don't exist in the photo list
        // (this happens for videos which are listed in albums but rejected
        // by the parser and not handled by this software (yet?) )
        foreach($this->albums as $aid=>$album) {
            foreach($album['PhotoIds'] as $i=>$pid) {
                if ( ! isset($this->photos[$pid])) {
                    unset($this->albums[$aid]['PhotoIds'][$i]);
                }
            }
        }
        $photos = $this->photos;
        $albums = $this->albums;
        $keywords = $this->keywords;
    }

    //---------------------------------------------------------
    function getVersion() {
        return $this->version;
    }

    //---------------------------------------------------------
    function parse() {
        do {
            $percent = $this->parseLittle();
        } while ($percent<1.0);
    }

    //---------------------------------------------------------
    function startElement($parser, $name, $attribs) {
        switch ($this->state) {
            case PARSER_IN_ALBUM_LIST:
                switch ($name) {
                    case 'DICT':
                        $this->state = PARSER_IN_ALBUM;
                        $this->levels['DICT']++;
                        break;
                }
                break;
            case PARSER_IN_ALBUM:
                switch ($name) {
                    case 'DICT':
                        $this->levels['DICT']++;
                        break;
                }
                break;
            case PARSER_IN_PHOTO:
                switch ($name) {
                    case 'DICT':
                        $this->levels['DICT']++;
                        break;
                }
                break;
        }
    }

    //---------------------------------------------------------
    function endElement($parser, $name) {
        switch ($this->state) {
            case PARSER_NOWHERE:
                switch ($this->content) {
                    case 'List of Albums':
                    case 'List of Rolls':
                        $this->state = PARSER_IN_ALBUM_LIST;
                        break;
                    case 'List of Keywords':
                        $this->state = PARSER_IN_KEYWORD_LIST;
                        break;
                    case 'Master Image List':
                        $this->state = PARSER_IN_PHOTO_LIST;
                        break;
                    default:
                        switch ($name) {
                            case 'KEY':
                                $this->key = $this->content;
                                break;
                            default:
                                switch($this->key) {
                                case 'Archive Path':
                                    // If this value is set, it's more reliable than the path set in Wipha's admin page
                                    if ($this->content!='(null)') {
                                        $this->archivePath = $this->content.'/';
                                    }
                                    break;
                                    case 'Application Version':
                                        $this->version = preg_replace('/\\s+.*/', '', $this->content);
                                        if (version_compare($this->version, "7", ">=")) {
                                            $this->showRolls = TRUE;
                                            $this->albums[EVENT_MASTER_ALBUM] = array(
                                                'AlbumName' => 'Events',
                                                'AlbumId'   => EVENT_MASTER_ALBUM,
                                                'AlbumType' => 'Fake container',
                                                'PhotoIds'  => array()
                                            );
                                        }
                                        if (version_compare($this->version, "9.4", ">=")) {
                                            $dbPropertiesPath = 
                                            $this->dbProperties = new PDO("sqlite:".$this->archivePath."/Database/apdb/Properties.apdb");
                                            $this->AspectRatioStatement = $this->dbProperties->prepare("SELECT propertyKey, stringProperty FROM RKOtherProperty, RKUniqueString WHERE RKUniqueString.modelId=RKOtherProperty.stringId AND (RKOtherProperty.propertyKey='AspectRatio' OR RKOtherProperty.propertyKey='Orientation') AND RKOtherProperty.versionid=?");
                                        }
                                        break;
                                }
                                break;
                        }
                        break;
                }
                break;
            case PARSER_IN_ALBUM_LIST:
                switch ($name) {
                    case 'ARRAY':
                        $this->state = PARSER_NOWHERE;
                        break;
                }
                break;
            case PARSER_IN_ALBUM:
                switch ($name) {
                    case 'DICT':
                        $this->levels['DICT']--;
                        if ($this->levels['DICT']==0) {
                            $this->state = PARSER_IN_ALBUM_LIST;
                            // if the father of this album is the master album
                            // then, it's just a "roll" album -> don't take it in account
                            if ($this->showRolls
                               ||($this->album['Parent']!=$this->masterAlbumId
                                && ($this->album['AlbumType']!='Photocasts')
                                && ($this->album['AlbumType']!='Selected Event Album')
                                && ($this->album['AlbumType']!='Subscribed') ) ) {
                                $this->albums[$this->album['AlbumId']] = $this->album;
                            }
                            $this->album = array();
                        }
                        break;
                    case 'KEY':
                        $this->key = $this->content;
                        if ($this->key =='KeyList') {
                            $this->state = PARSER_IN_ALBUM_CONTENT;
                            $this->album['PhotoIds'] = array();
                        }
                        break;
                    default:
                        switch($this->key) {
                            case 'Master':
                                $this->album[$this->key] = $name;
                                $this->masterAlbumId = $this->album['AlbumId'];
                                break;
                            case 'AlbumId':
                            case 'AlbumName':
                            case 'Parent':
                                // The AlbumId can't easily be changed, since it may be referenced in other albums in the 'Parent' key.
                                $this->album[$this->key] = $this->content;
                                break;
                            case 'RollName':
                                $this->album['AlbumName'] = $this->content;
                                break;
                            case 'RollID':
                                $this->album['AlbumId'] = 'e'.$this->content; // Add a 'e' so that event IDs can't conflict with Album IDs
                                $this->album['AlbumType'] = 'Event';
                                $this->album['Parent'] = EVENT_MASTER_ALBUM;
                                break;
                            case 'Album Type':  // used only to reject photocast albums
                            case 'AlbumType':
                                $this->album['AlbumType'] = $this->content;
                                break;
                        }
                        break;
                }
                break;
            case PARSER_IN_ALBUM_CONTENT:
                switch ($name) {
                    case 'STRING':
                        array_push($this->album['PhotoIds'], $this->content);
                        break;
                    case 'ARRAY':
                        $this->state = PARSER_IN_ALBUM;
                        break;
                }
                break;
            case PARSER_IN_PHOTO_LIST:
                switch ($name) {
                    case 'DICT':
                        $this->state = PARSER_NOWHERE;
                        break;
                    case 'KEY';
                        $this->state = PARSER_IN_PHOTO;
                        $this->photo['PhotoId'] = $this->content;
                        break;
                }
                break;
            case PARSER_IN_KEYWORD_LIST:
                switch ($name) {
                    case 'KEY':
                        $this->key = $this->content;
                        break;
                    case 'DICT':
                        $this->state = PARSER_NOWHERE;
                        break;
                    default:
                        $this->keywords[$this->key] = $this->content;
                        break;
                }
                break;
            case PARSER_IN_PHOTO:
                switch ($name) {
                    case 'DICT':
                        $this->levels['DICT']--;
                        if ($this->levels['DICT']==0) {
                            $this->state = PARSER_IN_PHOTO_LIST;
// // //                            if (($this->photo['MediaType']=='Image')||(!isset($this->photo['MediaType']))) {
                            // As of iPhoto 9.4, get Aspect Ratio from sqlite Properties database
                            if (isset($this->AspectRatioStatement)) {
                                $this->photo['Aspect Ratio'] = $this->getAspectRatioFromDatabase($this->photo['PhotoId']);
                            }
                            $this->photos[$this->photo['PhotoId']] = $this->photo;
// // //                            }
                            $this->photo = array();
                            //echo '------------------------------<br>';
                        }
                        break;
                    case 'KEY':
                        $this->key = $this->content;
                        if ($this->key =='Keywords') {
                            $this->state = PARSER_IN_PHOTO_KEYWORDS;
                            $this->photo['Keywords'] = array();
                        }
                        break;
                    default:
                        switch($this->key) {
                            case 'ImagePath':
                            case 'ThumbPath':
                                $this->photo[$this->key] = preg_replace('/'.preg_quote($this->archivePath, '/').'/', '', $this->content);
                                break;
                            case 'DateAsTimerInterval':
                                $this->photo['Timestamp'] = $this->timerIntervalToTimestamp($this->content);
                                break;
                            case 'Caption':
                            case 'Comment':
                            case 'MediaType':
                            case 'Aspect Ratio':
                                $this->photo[$this->key] = $this->content;
                                break;
                        }
                        break;
                }
                break;
            case PARSER_IN_PHOTO_KEYWORDS:
                switch ($name) {
                    case 'ARRAY':
                        $this->state = PARSER_IN_PHOTO;
                        break;
                    default:
                        array_push($this->photo['Keywords'], $this->content);
                        break;
                }
                break;
        }
        $this->content = '';
    }

    //---------------------------------------------------------
    function characterData($parser, $data) {
        if (preg_match('/^[[:space:]]*$/', $data)) {  // string with only white spaces
            return;
        }
        $this->content .= $data;
        return;
    }

    //---------------------------------------------------------
    function free() {
        // Free up memory used by the XML parser
        xml_parser_free($this->xml_parser);
        $this->photos = NULL;
        $this->albums = NULL;
        $this->keywords = NULL;
    }

    //---------------------------------------------------------
    function timerIntervalToTimestamp($appleTimerInterval) {
        // Apple timer interval seems to be in seconds, starting on 01 January 2001
        $d = getdate(intval($appleTimerInterval));
        return mktime($d['hours'],$d['minutes'],$d['seconds'], $d['mon'],$d['mday'],$d['year']+31);
    }

    //---------------------------------------------------------
    function getAspectRatioFromDatabase($photoId) {
        $this->AspectRatioStatement->execute(array($photoId));
        $ratioData = $this->AspectRatioStatement->fetchall(PDO::FETCH_NUM);
        if ($ratioData[0][0] == 'AspectRatio') {
            $stringRatio = $ratioData[0][1];
            $orientation = strtolower($ratioData[1][1]);
        } else {
            $stringRatio = $ratioData[1][1];
            $orientation = strtolower($ratioData[0][1]);
        }
        if (preg_match('/^(\d+):(\d+)$/', $stringRatio, $matches)) {
            if (($orientation == 'landscape' && $matches[2] > $matches[1]) || ($orientation == 'portrait' && $matches[2] < $matches[1])) {
                $ratio = $matches[2]/$matches[1];
            } else {
                $ratio = $matches[1]/$matches[2];
            }
        } else {
            print "Photo $photoId: Can't compute AspectRatio for string '$stringRatio' and orientation '$orientation'. ";
            $ratio = 0;
        }
        return $ratio;
    }
    
}


?>