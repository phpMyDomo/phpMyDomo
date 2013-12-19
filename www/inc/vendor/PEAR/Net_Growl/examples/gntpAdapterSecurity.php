<?php
/**
 * Security example
 * that send notifications to Growl using the new GNTP/1.0 protocol
 * with encrypted messages
 *
 * PHP version 5
 *
 * @category Networking
 * @package  Net_Growl
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @author   Bertrand Mansion <bmansion@mamasam.com>
 * @license  http://www.opensource.org/licenses/bsd-license.php  BSD
 * @version  SVN: Release: 2.7.0
 * @link     http://growl.laurent-laville.org/
 * @since    File available since Release 2.0.0RC1
 */

require_once 'Net/Growl/Autoload.php';

// Notification Type definitions
define('GROWL_NOTIFY_STATUS', 'STATUS');

// define a PHP application that sends notifications to Growl
$appName = 'PEAR/Net_Growl ' . basename(__FILE__, '.php');

$notifications = array(
    GROWL_NOTIFY_STATUS => array(
        'display' => 'Status',
    ),
);

$password = '';
$options  = array(
    'protocol' => 'gntp', 'timeout' => 10,
    'AppIcon'  => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Help.ico',
    'encryptionAlgorithm'   => 'AES',
    'passwordHashAlgorithm' => 'SHA256',
    'debug'    => dirname(__FILE__) . DIRECTORY_SEPARATOR .
        basename(__FILE__, '.php') . '.log'
);

try {
    $growl = Net_Growl::singleton($appName, $notifications, $password, $options);
    $growl->register();

    $name        = GROWL_NOTIFY_STATUS;
    $title       = 'Congratulation';
    $description = 'You have successfully installed PEAR/Net_Growl.';
    $growl->publish($name, $title, $description);

    $name        = GROWL_NOTIFY_STATUS;
    $title       = 'Welcome in PHP/GNTP world';
    $description = "New GNTP protocol support 3 encryption algorithms ! \n"
                 . "DES, 3DES, AES with 4 hash algorithm \n"
                 . "MD5, SHA1, SHA256, SHA512.";
    $options     = array(
        'sticky' => true,
    );
    $growl->publish($name, $title, $description, $options);

} catch (Net_Growl_Exception $e) {
    echo 'Caught Growl exception: ' . $e->getMessage() . PHP_EOL;
}
