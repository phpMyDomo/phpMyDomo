<?php
/**
 * URL callbacks example
 * that send notifications to Growl using the new GNTP/1.0 protocol
 *
 * The callback url will be opened in the user's default browser.
 * Unlike socket callbacks, URL callbacks are only triggered if the notification
 * is clicked (CLICK|CLICKED), not for CLOSE|CLOSED or TIMEOUT|TIMEDOUT actions.
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
 * @since    File available since Release 2.0.0b2
 */

require_once 'Net/Growl/Autoload.php';

// Notification Type definitions
define('GROWL_NOTIFY_STATUS',   'STATUS');
define('GROWL_NOTIFY_PHPERROR', 'PHPERROR');

// define a PHP application that sends notifications to Growl

$app = new Net_Growl_Application(
    'PEAR/Net_Growl ' . basename(__FILE__, '.php'),
    array(
        GROWL_NOTIFY_STATUS => array(
            'display' => 'Status',
        ),

        GROWL_NOTIFY_PHPERROR => array(
            'icon' => 'http://www.laurent-laville.org/growl/images/firephp.png',
            'display' => 'Error-Log'
        )
    ),
    'mamasam'
);


try {
    $growl = Net_Growl::singleton(
        $app,
        null, null,
        array(
            'protocol' => 'gntp',
            'AppIcon'  => 'http://www.laurent-laville.org/growl/images/Help.png',
            'encryptionAlgorithm'   => 'AES',
            'passwordHashAlgorithm' => 'SHA256',
            'debug'    => dirname(__FILE__) . DIRECTORY_SEPARATOR .
                basename(__FILE__, '.php') . '.log'
        )
    );
    $growl->register();

    $name        = GROWL_NOTIFY_STATUS;
    $title       = 'Congratulation';
    $description = "You have successfully installed PEAR/Net_Growl.";
    $options     = array(
        'ID'                  => 123456,
        'CallbackContext'     => 'this is my context',
        'CallbackContextType' => 'STRING',
        'CallbackTarget'      => 'http://growl.laurent-laville.org/parseUrl.php'
                               . '?hello=world',
        'sticky'              => true,
    );
    $growl->publish($name, $title, $description, $options);

    $name        = GROWL_NOTIFY_PHPERROR;
    $title       = 'New Error';
    $description = 'You have a new PHP error in your script.';
    $options     = array(
        'priority' => Net_Growl::PRIORITY_HIGH,
    );
    $growl->publish($name, $title, $description, $options);

    $name        = GROWL_NOTIFY_STATUS;
    $title       = 'Welcome';
    $description = "Welcome in PHP/GNTP world ! \n"
                 . "New GNTP protocol add icon support.";
    $options     = array(
        'icon'   => 'http://www.laurent-laville.org/growl/images/unknown.png',
        'sticky' => false,
    );
    $growl->publish($name, $title, $description, $options);

} catch (Net_Growl_Exception $e) {
    echo 'Caught Growl exception: ' . $e->getMessage() . PHP_EOL;
}
