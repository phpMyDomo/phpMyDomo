<?php
/**
 * Example that send notifications to Growl about PEAR Errors
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
 * @since    File available since Release 2.0.0RC2
 */

require_once 'Net/Growl/Autoload.php';
require_once 'PEAR.php';

// Notification Type definitions
define('GROWL_NOTIFY_PEARERROR', 'PEAR_Error');

/**
 * PEAR_Error callback function
 *
 * @param object $error PEAR_Error instance
 *
 * @return void
 */
function growlErrors($error)
{
    static $app;

    if (!isset($app)) {
        $app = new Net_Growl_Application(
            'Net_Growl', array(GROWL_NOTIFY_PEARERROR)
        );
    }

    $growl = Net_Growl::singleton(
        $app, null
    );
    $growl->publish(
        GROWL_NOTIFY_PEARERROR,
        get_class($error),
        $error->message.' in '.$_SERVER['SCRIPT_NAME'],
        array('sticky' => true)
    );
}

if (version_compare(PHP_VERSION, '5.3.0', 'lt')) {
    PEAR::setErrorHandling(PEAR_ERROR_CALLBACK, 'growlErrors');
    PEAR::raiseError("The expected error you submitted does not exist");
} else {
    $pear = new PEAR;
    $pear->setErrorHandling(PEAR_ERROR_CALLBACK, 'growlErrors');
    $pear->raiseError("The expected error you submitted does not exist");
}
