<?php
/**
 * Unit tests for Net_Growl package GNTP adapter
 *
 * PHP version 5
 *
 * @category Networking
 * @package  Net_Growl
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @license  http://www.opensource.org/licenses/bsd-license.php  BSD
 * @version  SVN: $Id: GntpTest.php 329339 2013-01-29 14:57:48Z farell $
 * @link     http://pear.php.net/package/Net_Growl
 * @since    File available since Release 2.1.0
 */

require_once 'Net/Growl/Autoload.php';

/**
 * Unit test for Net_Growl_Gntp class
 */
class Net_Growl_GntpTest extends PHPUnit_Framework_TestCase
{
    /**
     * Sets up the fixture.
     * This method is called before a test is executed.
     *
     * @return void
     */
    protected function setUp()
    {
        // @link http://sebastian-bergmann.de/archives/882-guid.html
        //       Testing Code That Uses Singletons
        Net_Growl::reset();
    }

    /**
     * test a well formed REGISTER request that return a OK response
     */
    public function testRegisterOK()
    {
        $appName  = 'PHP GNTP Test';
        $password = 'mamasam';
        $options  = array(
            'protocol' => 'gntpMock',
        );

        $notifications = array(
            'GROWL_NOTIFY_STATUS'   => array(),
            'GROWL_NOTIFY_PHPERROR' => array(),
        );

        $mock = Net_Growl::singleton($appName, $notifications, $password, $options);
        $mock->addResponse(
            fopen(dirname(dirname(__FILE__))
                . '/_files/response_gntp_register_ok', 'rb')
        );

        try {
            $response = $mock->register();
        }
        catch (Exception $e) {
            $this->fail('Not Expected Net_Growl_Exception was thrown: '.$e->getMessage());
            return;
        }

        // @link http://www.growlforwindows.com/gfw/help/gntp.aspx#ok
        $this->assertEquals('1.0', $response->getVersion());
        $this->assertEquals('OK', $response->getStatus());
        $this->assertEquals('REGISTER', $response->getResponseAction());

        $this->assertEquals('Growl/Win', $response->getOriginSoftwareName());
        $this->assertEquals('2.0.0.28', $response->getOriginSoftwareVersion());
        // @link http://www.php.net/manual/en/function.php-uname.php
        // see php_uname('n')
        $this->assertEquals('OURAGAN', $response->getOriginMachineName());
        // see php_uname('s')
        $this->assertTrue(
            strpos($response->getOriginPlatformName(), 'Windows NT') >= 0,
            'Operating system name not found'
        );
        // see php_uname('r')
        $this->assertTrue(
            strpos($response->getOriginPlatformVersion(), '5.1') >= 0,
            'Release name not found'
        );
    }

    /**
     * test a bad formed REGISTER request that return an ERROR response
     */
    public function testRegisterERROR()
    {
        $appName  = 'PHP GNTP Test';
        $password = 'mamasam';
        $options  = array(
            'protocol' => 'gntpMock',
        );

        $notifications = array(
            'GROWL_NOTIFY_STATUS'   => array(),
            'GROWL_NOTIFY_PHPERROR' => array(),
        );

        $mock = Net_Growl::singleton($appName, $notifications, $password, $options);

        $mock->addResponse(
            fopen(dirname(dirname(__FILE__))
                . '/_files/response_gntp_no_notifications_registered', 'rb')
        );

        try {
            $response = $mock->register();
        }
        catch (Exception $e) {
            $this->fail('Not Expected Net_Growl_Exception was thrown: '.$e->getMessage());
            return;
        }

        // @link http://www.growlforwindows.com/gfw/help/gntp.aspx#error
        $this->assertEquals('ERROR', $response->getStatus());
        $this->assertNull($response->getResponseAction());
        $this->assertEquals(300, $response->getErrorCode());
        $this->assertEquals('No notifications registered', $response->getErrorDescription());

        $this->assertEquals('Growl/Win', $response->getOriginSoftwareName());
        $this->assertEquals('2.0.0.28', $response->getOriginSoftwareVersion());
        // @link http://www.php.net/manual/en/function.php-uname.php
        // see php_uname('n')
        $this->assertEquals('OURAGAN', $response->getOriginMachineName());
        // see php_uname('s')
        $this->assertTrue(
            strpos($response->getOriginPlatformName(), 'Windows NT') >= 0,
            'Operating system name not found'
        );
        // see php_uname('r')
        $this->assertTrue(
            strpos($response->getOriginPlatformVersion(), '5.1') >= 0,
            'Release name not found'
        );
    }

    /**
     * test a bad formed REGISTER request that raise an exception
     */
    public function testRegisterWithException()
    {
        $appName  = 'PHP GNTP Test';
        $password = 'mamasam';
        $options  = array(
            'protocol' => 'gntpMock',
        );

        $notifications = array();

        $registerException = 'Could not send registration to Growl Server. '
            . 'No notifications registered';

        $mock = Net_Growl::singleton($appName, $notifications, $password, $options);
        $mock->addResponse(
            new Net_Growl_Exception($registerException)
        );

        try {
            $mock->register();
        }
        catch (Exception $e) {
            $this->assertEquals($registerException, $e->getMessage());
            return;
        }
        $this->fail('Expected Net_Growl_Exception was not thrown');
    }

    /**
     * test a communication problem between php application and Growl client
     * that could not understand the request sent
     */
    public function testBadRegisterRequest()
    {
        $appName  = 'PHP GNTP Test';
        $password = 'mamasam';
        $options  = array(
            'protocol' => 'gntpMock',
        );

        $notifications = array(
            'GROWL_NOTIFY_STATUS'   => array(),
        );

        $registerException = 'Malformed response: Bad Request';

        $mock = Net_Growl::singleton($appName, $notifications, $password, $options);

        try {
            $response = $mock->register();
        }
        catch (Exception $e) {
            $this->assertEquals($registerException, $e->getMessage());
            return;
        }
        $this->fail('Expected Net_Growl_Exception was not thrown');
    }

    /**
     * test a well formed NOTIFY request that return a OK response
     */
    public function testNotifyOK()
    {
        $appName  = 'PHP GNTP Test';
        $password = 'mamasam';
        $options  = array(
            'protocol' => 'gntpMock',
        );

        $notifications = array(
            'GROWL_NOTIFY_STATUS'   => array(),
            'GROWL_NOTIFY_PHPERROR' => array(),
        );

        $mock = Net_Growl::singleton($appName, $notifications, $password, $options);
        $mock->addResponse(
            "GNTP/1.0 -OK NONE\r\n" .
            "Response-Action: REGISTER\r\n" .
            "Origin-Machine-Name: OURAGAN\r\n" .
            "Origin-Software-Name: Growl/Win\r\n" .
            "Origin-Software-Version: 2.0.0.28\r\n" .
            "Origin-Platform-Name: Microsoft Windows NT 5.1.2600 Service Pack 3\r\n" .
            "Origin-Platform-Version: 5.1.2600.196608\r\n" .
            "X-Message-Daemon: Growl/Win\r\n" .
            "X-Timestamp: 22/02/2010 19:40:25\r\n" .
            ""
        );
        $mock->addResponse(
            "GNTP/1.0 -OK NONE\r\n" .
            "Response-Action: NOTIFY\r\n" .
            "Origin-Machine-Name: OURAGAN\r\n" .
            "Origin-Software-Name: Growl/Win\r\n" .
            "Origin-Software-Version: 2.0.0.28\r\n" .
            "Origin-Platform-Name: Microsoft Windows NT 5.1.2600 Service Pack 3\r\n" .
            "Origin-Platform-Version: 5.1.2600.196608\r\n" .
            "X-Message-Daemon: Growl/Win\r\n" .
            "X-Timestamp: 22/02/2010 19:40:25\r\n" .
            ""
        );

        try {
            $name        = 'GROWL_NOTIFY_STATUS';
            $title       = 'Congratulation';
            $description = 'Congratulation! You are successfull install PHP/NetGrowl.';
            $options     = array();
            $response    = $mock->notify($name, $title, $description, $options);
        }
        catch (Exception $e) {
            $this->fail('Not Expected Net_Growl_Exception was thrown: '.$e->getMessage());
            return;
        }

        // @link http://www.growlforwindows.com/gfw/help/gntp.aspx#ok
        $this->assertEquals('1.0', $response->getVersion());
        $this->assertEquals('OK', $response->getStatus());
        $this->assertEquals('NOTIFY', $response->getResponseAction());

        $this->assertEquals('Growl/Win', $response->getOriginSoftwareName());
        $this->assertEquals('2.0.0.28', $response->getOriginSoftwareVersion());
        // @link http://www.php.net/manual/en/function.php-uname.php
        // see php_uname('n')
        $this->assertEquals('OURAGAN', $response->getOriginMachineName());
        // see php_uname('s')
        $this->assertTrue(
            strpos($response->getOriginPlatformName(), 'Windows NT') >= 0,
            'Operating system name not found'
        );
        // see php_uname('r')
        $this->assertTrue(
            strpos($response->getOriginPlatformVersion(), '5.1') >= 0,
            'Release name not found'
        );
    }

    /**
     * test a well formed NOTIFY request with notification count limit
     */
    public function testNotifyWithLimit()
    {
        $appName  = 'PHP GNTP Test';
        $password = 'mamasam';
        $options  = array(
            'protocol' => 'gntpMock',
        );

        $notifications = array(
            'GROWL_NOTIFY_STATUS'   => array(),
            'GROWL_NOTIFY_PHPERROR' => array(),
        );

        $mock = Net_Growl::singleton($appName, $notifications, $password, $options);
        $mock->setNotificationLimit(1);
        $mock->addResponse(
            "GNTP/1.0 -OK NONE\r\n" .
            "Response-Action: REGISTER\r\n" .
            "Origin-Machine-Name: OURAGAN\r\n" .
            "Origin-Software-Name: Growl/Win\r\n" .
            "Origin-Software-Version: 2.0.0.28\r\n" .
            "Origin-Platform-Name: Microsoft Windows NT 5.1.2600 Service Pack 3\r\n" .
            "Origin-Platform-Version: 5.1.2600.196608\r\n" .
            "X-Message-Daemon: Growl/Win\r\n" .
            "X-Timestamp: 22/02/2010 19:40:25\r\n" .
            ""
        );
        $mock->addResponse(
            "GNTP/1.0 -OK NONE\r\n" .
            "Response-Action: NOTIFY\r\n" .
            "Origin-Machine-Name: OURAGAN\r\n" .
            "Origin-Software-Name: Growl/Win\r\n" .
            "Origin-Software-Version: 2.0.0.28\r\n" .
            "Origin-Platform-Name: Microsoft Windows NT 5.1.2600 Service Pack 3\r\n" .
            "Origin-Platform-Version: 5.1.2600.196608\r\n" .
            "X-Message-Daemon: Growl/Win\r\n" .
            "X-Timestamp: 22/02/2010 19:40:25\r\n" .
            ""
        );

        try {
            $name        = 'GROWL_NOTIFY_STATUS';
            $title       = 'Congratulation';
            $description = 'Congratulation! You are successfull install PHP/NetGrowl.';
            $options     = array();
            $response    = $mock->notify($name, $title, $description, $options);
        }
        catch (Exception $e) {
            $this->fail('Not Expected Net_Growl_Exception was thrown: '.$e->getMessage());
            return;
        }

        // @link http://www.growlforwindows.com/gfw/help/gntp.aspx#ok
        $this->assertEquals('1.0', $response->getVersion());
        $this->assertEquals('OK', $response->getStatus());
        $this->assertEquals('NOTIFY', $response->getResponseAction());

        $this->assertEquals('Growl/Win', $response->getOriginSoftwareName());
        $this->assertEquals('2.0.0.28', $response->getOriginSoftwareVersion());
        // @link http://www.php.net/manual/en/function.php-uname.php
        // see php_uname('n')
        $this->assertEquals('OURAGAN', $response->getOriginMachineName());
        // see php_uname('s')
        $this->assertTrue(
            strpos($response->getOriginPlatformName(), 'Windows NT') >= 0,
            'Operating system name not found'
        );
        // see php_uname('r')
        $this->assertTrue(
            strpos($response->getOriginPlatformVersion(), '5.1') >= 0,
            'Release name not found'
        );

        try {
            $name        = 'GROWL_NOTIFY_PHPERROR';
            $title       = 'PHP Error';
            $description = 'You have a new PHP error in your script P at line N';
            $options     = array(
                'priority' => Net_Growl::PRIORITY_HIGH,
            );
            $response    = $mock->notify($name, $title, $description, $options);
        }
        catch (Exception $e) {
            $this->fail('Not Expected Net_Growl_Exception was thrown: '.$e->getMessage());
            return;
        }

        // notification limit is reached
        $this->assertFalse($response);
    }

    /**
     * test PNG image that display a 48x48 pixels official Growl icon
     */
    public function testDefaultIcon()
    {
        $png = fread(
            fopen(dirname(dirname(__FILE__)) . '/_files/growl_icon_48x48.png', 'rb'),
            8192
        );

        $appName  = 'PHP GNTP Test';
        $password = '';
        $options  = array(
            'protocol'    => 'gntpMock',
            'resourceDir' => dirname(dirname(__FILE__)) . '/_files',
            'defaultIcon' => 'growl_icon_48x48.png'
        );

        $notifications = array(
            'GROWL_NOTIFY_STATUS'   => array(),
        );

        $mock = Net_Growl::singleton($appName, $notifications, $password, $options);
        $icon = $mock->getDefaultGrowlIcon();

        $this->assertSame($icon, $png);
    }

    /**
     * test a well formed REGISTER request,
     * data came from a Net_Growl_Appplication object
     */
    public function testRegisterWithApplicationSettings()
    {
        $appName  = 'PHP GNTP Test';
        $appIcon  = '';
        $password = 'mamasam';
        $options  = array(
            'protocol' => 'gntpMock',
        );

        $notifications = array(
            'GROWL_NOTIFY_STATUS'
        );

        $app = new Net_Growl_Application(
            $appName,
            $notifications,
            $password,
            $appIcon
        );
        // add another notification disabled by default
        $app->addGrowlNotifications(
            array('GROWL_NOTIFY_PHPERROR' => array('enabled' => false))
        );

        $mock = Net_Growl::singleton($app, null, null, $options);
        $mock->addResponse(
            fopen(dirname(dirname(__FILE__))
                . '/_files/response_gntp_register_ok', 'rb')
        );

        try {
            $response = $mock->register();
        }
        catch (Exception $e) {
            $this->fail('Not Expected Net_Growl_Exception was thrown: '.$e->getMessage());
            return;
        }

        // @link http://www.growlforwindows.com/gfw/help/gntp.aspx#ok
        $this->assertEquals('1.0', $response->getVersion());
        $this->assertEquals('OK', $response->getStatus());
        $this->assertEquals('REGISTER', $response->getResponseAction());

        $this->assertEquals('Growl/Win', $response->getOriginSoftwareName());
        $this->assertEquals('2.0.0.28', $response->getOriginSoftwareVersion());
        // @link http://www.php.net/manual/en/function.php-uname.php
        // see php_uname('n')
        $this->assertEquals('OURAGAN', $response->getOriginMachineName());
        // see php_uname('s')
        $this->assertTrue(
            strpos($response->getOriginPlatformName(), 'Windows NT') >= 0,
            'Operating system name not found'
        );
        // see php_uname('r')
        $this->assertTrue(
            strpos($response->getOriginPlatformVersion(), '5.1') >= 0,
            'Release name not found'
        );

        // each notification is auto enabled by Net_Growl
        $enabledNotifications = array(
            'GROWL_NOTIFY_STATUS'   => array('enabled' => true),
            'GROWL_NOTIFY_PHPERROR' => array('enabled' => false)
        );
        $this->assertEquals(
            $enabledNotifications, $mock->getApplication()->getGrowlNotifications()
        );
        $this->assertEquals(
            $appName, $mock->getApplication()->getGrowlName()
        );
        $this->assertEquals(
            $password, $mock->getApplication()->getGrowlPassword()
        );
        $this->assertEquals(
            '', $mock->getApplication()->getGrowlIcon()
        );
    }

    /**
     * test debug option / writing on a file
     */
    public function testDebugOption()
    {
        $appName  = 'PHP GNTP Test';
        $password = 'mamasam';
        $debug    = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'gntp.log';
        $options  = array(
            'protocol' => 'gntpMock',
            'debug'    => $debug
        );

        $notifications = array(
            'GROWL_NOTIFY_STATUS'   => array(),
            'GROWL_NOTIFY_PHPERROR' => array(),
        );

        $mock = Net_Growl::singleton($appName, $notifications, $password, $options);
        $mock->addResponse(
            fopen(dirname(dirname(__FILE__))
                . '/_files/response_gntp_register_ok', 'rb')
        );

        try {
            $response = $mock->register();
        }
        catch (Exception $e) {
            $this->fail('Not Expected Net_Growl_Exception was thrown: '.$e->getMessage());
            return;
        }

        // @link http://www.growlforwindows.com/gfw/help/gntp.aspx#ok
        $this->assertEquals('OK', $response->getStatus());
        $this->assertEquals('REGISTER', $response->getResponseAction());

        $this->assertFileExists($debug);
    }

    /**
     * test timeout option
     */
    public function testTimeoutOption()
    {
        $appName  = 'PHP GNTP Test';
        $password = 'mamasam';
        $options  = array(
            'protocol' => 'gntpMock',
            'timeout'  => 'invalid value'
        );

        $notifications = array(
            'GROWL_NOTIFY_STATUS'   => array(),
            'GROWL_NOTIFY_PHPERROR' => array(),
        );

        $mock = Net_Growl::singleton($appName, $notifications, $password, $options);
        $mock->addResponse(
            fopen(dirname(dirname(__FILE__))
                . '/_files/response_gntp_register_ok', 'rb')
        );

        try {
            $response = $mock->register();
        }
        catch (Exception $e) {
            $this->fail('Not Expected Net_Growl_Exception was thrown: '.$e->getMessage());
            return;
        }

        // @link http://www.growlforwindows.com/gfw/help/gntp.aspx#ok
        $this->assertEquals('OK', $response->getStatus());
        $this->assertEquals('REGISTER', $response->getResponseAction());
    }

    /**
     * test AppIcon option
     */
    public function testAppIconOption()
    {
        $appName  = 'PHP GNTP Test';
        $password = 'mamasam';
        $appIcon  = dirname(dirname(__FILE__)) . '/_files/korganizer-128x128.png';
        $options  = array(
            'protocol' => 'gntpMock',
            'AppIcon'  => $appIcon
        );

        $notifications = array(
            'GROWL_NOTIFY_STATUS'   => array(),
            'GROWL_NOTIFY_PHPERROR' => array(),
        );

        $mock = Net_Growl::singleton($appName, $notifications, $password, $options);
        $mock->addResponse(
            fopen(dirname(dirname(__FILE__))
                . '/_files/response_gntp_register_ok', 'rb')
        );

        try {
            $response = $mock->register();
        }
        catch (Exception $e) {
            $this->fail('Not Expected Net_Growl_Exception was thrown: '.$e->getMessage());
            return;
        }

        // @link http://www.growlforwindows.com/gfw/help/gntp.aspx#ok
        $this->assertEquals('OK', $response->getStatus());
        $this->assertEquals('REGISTER', $response->getResponseAction());

        $this->assertEquals(
            fread(
                fopen(dirname(dirname(__FILE__)) . '/_files/korganizer-128x128.png', 'rb'),
                30720
            ),
            $mock->getApplication()->getGrowlIcon()
        );
    }

    /**
     * test Protocol option on loading external class resource
     */
    public function testProtocolOptionCannotLoadClass()
    {
        $appName  = 'PHP GNTP Test';
        $password = 'mamasam';
        $options  = array(
            'protocol' => 'foo',
        );

        $notifications = array(
            'GROWL_NOTIFY_STATUS'   => array(),
            'GROWL_NOTIFY_PHPERROR' => array(),
        );

        $registerException = 'Cannot find class "Net_Growl_Foo"';

        try {
            $mock = Net_Growl::singleton($appName, $notifications, $password, $options);
        }
        catch (Net_Growl_Exception $e) {
            $this->assertEquals($registerException, $e->getMessage());
            return;
        }
        catch (Exception $e) {
            $this->fail('Not Expected Exception was thrown: '. $e->getMessage());
            return;
        }
        $this->fail('Expected Net_Growl_Exception was not thrown');
    }

    /**
     * test Protocol option expected external class resource loaded and defined
     */
    public function testProtocolOptionCannotFindClass()
    {
        $appName  = 'PHP GNTP Test';
        $password = 'mamasam';
        $options  = array(
            'protocol' => 'foo',
        );

        $notifications = array(
            'GROWL_NOTIFY_STATUS'   => array(),
            'GROWL_NOTIFY_PHPERROR' => array(),
        );

        $registerException = 'Cannot find class "Net_Growl_Foo"';

        ini_set(
            'include_path',
            dirname(dirname(__FILE__)) . '/_files'
            . PATH_SEPARATOR . ini_get('include_path')
        );

        try {
            $mock = Net_Growl::singleton($appName, $notifications, $password, $options);
        }
        catch (Net_Growl_Exception $e) {
            $this->assertEquals($registerException, $e->getMessage());
            return;
        }
        catch (Exception $e) {
            $this->fail('Not Expected Exception was thrown: '. $e->getMessage());
            return;
        }
        $this->fail('Expected Net_Growl_Exception was not thrown');
    }
}
