<?php
/**
 * Example that send notifications to Growl using the old UDP protocol
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
 * @since    File available since Release 0.9.0
 */

require_once 'Net/Growl/Autoload.php';

// Notification Type definitions
define('GROWL_NOTIFY_STATUS',   'STATUS');
define('GROWL_NOTIFY_PHPERROR', 'PHPERROR');

// define a PHP application that sends notifications to Growl
$appName = 'PEAR/Net_Growl ' . basename(__FILE__, '.php');

$notifications = array(
    GROWL_NOTIFY_STATUS => array(),
    GROWL_NOTIFY_PHPERROR => array()
);

try {
    $growl = Net_Growl::singleton($appName, $notifications);
    $growl->register();

    $name        = GROWL_NOTIFY_STATUS;
    $title       = 'Congratulation';
    $description = 'You have successfully installed PEAR/Net_Growl.';
    $growl->publish($name, $title, $description);

    $name        = GROWL_NOTIFY_PHPERROR;
    $title       = 'New Error';
    $description = 'You have a new PHP error in your script.';
    $options     = array(
        'sticky'   => true,
        'priority' => Net_Growl::PRIORITY_HIGH,
    );
    $growl->publish($name, $title, $description, $options);

    $name        = GROWL_NOTIFY_STATUS;
    $title       = 'Welcome';
    $description = "Welcome in PHP/Growl world ! \n"
                 . "Old UDP protocol did not support icons.";
    $growl->publish($name, $title, $description);

    var_export($growl);

} catch (Net_Growl_Exception $e) {
    echo 'Caught Growl exception: ' . $e->getMessage() . PHP_EOL;
}
