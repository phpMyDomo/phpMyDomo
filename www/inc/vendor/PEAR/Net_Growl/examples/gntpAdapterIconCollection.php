<?php
/**
 * Example that send notifications to Growl using the new GNTP/1.0 protocol.
 * Icon streams organized as collection is available since API 2.7
 *
 * PHP version 5
 *
 * @category Networking
 * @package  Net_Growl
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @license  http://www.opensource.org/licenses/bsd-license.php  BSD
 * @version  SVN: Release: 2.7.0
 * @link     http://growl.laurent-laville.org/
 * @since    File available since Release 2.7.0
 */

require_once 'Net/Growl/Autoload.php';

// Notification Type definitions
define('GROWL_NOTIFY_PHPUC',   'PHPUC');
define('GROWL_NOTIFY_PHPUNIT', 'PHPUNIT');
define('GROWL_NOTIFY_PHING',   'PHING');

// define a PHP application that sends notifications to Growl
$appName = 'PEAR/Net_Growl ' . basename(__FILE__, '.php');

// CAUTION: normalized archive real path name (required on windows platform)
$archive = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'growlDefaultCollection';
if (DIRECTORY_SEPARATOR == '\\') {
    $archive = preg_replace('/^([a-zA-Z]:)(.*)/', '$2', $archive);
    $archive = str_replace('\\', '/', $archive);
}
if (version_compare(PHP_VERSION, '5.3.0') < 0) {
    $archive = "zip://$archive.zip#";
} else {
    $archive = "phar://$archive.phar/";
}

$notifications = array(
    GROWL_NOTIFY_PHPUC => array(
        'icon'    => $archive . '80/growl_phpuc.png',
        'display' => 'phpUnderControl',
    ),

    GROWL_NOTIFY_PHPUNIT => array(
        'icon'    => $archive . '80/growl_phpunit.png',
        'display' => 'PHPUnit'
    ),

    GROWL_NOTIFY_PHING => array(
        'icon'    => $archive . '80/growl_phing.png',
        'display' => 'Phing'
    ),
);

$ID1      = uniqid();
$password = '';
$options  = array(
    'resourceDir' => dirname(__FILE__),
    'defaultIcon' => 'Help.ico',
    'AppIcon'     => $archive . '128/growl-starkicon.png',
    'protocol'    => 'gntp', 'timeout' => 15,
    'debug'       => dirname(__FILE__) . DIRECTORY_SEPARATOR .
        basename(__FILE__, '.php') . '.log'
);

try {
    $growl = Net_Growl::singleton($appName, $notifications, $password, $options);
    $growl->register();

    $name        = GROWL_NOTIFY_PHPUC;
    $title       = 'Congratulation';
    $description = 'CC-Job #15 DONE';
    $options     = array(
        'ID'           => uniqid(),
        'CoalescingID' => $ID1,
    );
    $growl->publish($name, $title, $description, $options);
    // for demo purpose only; just to let see each notification between two processes
    sleep(2);

    $name        = GROWL_NOTIFY_PHPUNIT;
    $title       = 'Failure';
    $description = 'Test Suite #15 FAILED';
    $options     = array(
        'priority'     => Net_Growl::PRIORITY_HIGH,
        'ID'           => uniqid(),
        'CoalescingID' => $ID1,
    );
    $growl->publish($name, $title, $description, $options);
    // for demo purpose only; just to let see each notification between two processes
    sleep(2);

    $name        = GROWL_NOTIFY_PHING;
    $title       = 'Proposal';
    $description = "Project #15 BUILDED";
    $options     = array(
        'ID'           => uniqid(),
        'CoalescingID' => $ID1,
    );
    $growl->publish($name, $title, $description, $options);
    // for demo purpose only; just to let see each notification between two processes
    sleep(2);

    $name        = GROWL_NOTIFY_PHING;
    $title       = 'FirePHP';
    $description = "Project #16 BUILDED";
    $options     = array(
        'icon'         => $archive . 'firephp.png',
        'ID'           => uniqid(),
        'CoalescingID' => $ID1,
    );
    $growl->publish($name, $title, $description, $options);

} catch (Net_Growl_Exception $e) {
    echo 'Caught Growl exception: ' . $e->getMessage() . PHP_EOL;
}
