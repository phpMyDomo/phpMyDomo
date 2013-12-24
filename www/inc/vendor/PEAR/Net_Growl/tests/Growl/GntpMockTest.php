<?php
/**
 * Unit tests for Net_Growl package GNTP Mock adapter
 *
 * PHP version 5
 *
 * @category Networking
 * @package  Net_Growl
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @license  http://www.opensource.org/licenses/bsd-license.php  BSD
 * @version  SVN: $Id: GntpMockTest.php 324848 2012-04-05 10:02:09Z farell $
 * @link     http://pear.php.net/package/Net_Growl
 * @since    File available since Release 2.1.0
 */

require_once 'Net/Growl/Autoload.php';

/**
 * Unit test for Net_Growl_Gntp class
 */
class Net_Growl_GntpMockTest extends PHPUnit_Framework_TestCase
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
     * test a response got from local file
     */
    public function testResponseFromFile()
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
     * test a response got from string
     */
    public function testResponseFromString()
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
            "GNTP/1.0 -ERROR NONE\r\n" .
            "Error-Code: 300\r\n" .
            "Error-Description: No notifications registered\r\n" .
            "Origin-Machine-Name: OURAGAN\r\n" .
            "Origin-Software-Name: Growl/Win\r\n" .
            "Origin-Software-Version: 2.0.0.28\r\n" .
            "Origin-Platform-Name: Microsoft Windows NT 5.1.2600 Service Pack 3\r\n" .
            "Origin-Platform-Version: 5.1.2600.196608\r\n" .
            "X-Message-Daemon: Growl/Win\r\n" .
            "X-Timestamp: 14/02/2010 15:52:42\r\n" .
            ""
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
     * test a response got from exception
     */
    public function testResponseException()
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
     * test invalid response
     */
    public function testResponseInvalid()
    {
        $appName  = 'PHP GNTP Test';
        $password = 'mamasam';
        $options  = array(
            'protocol' => 'gntpMock',
        );

        $notifications = array();

        $mock = Net_Growl::singleton($appName, $notifications, $password, $options);

        try {
            $mock->addResponse(false);
            $mock->register();
        }
        catch (Net_Growl_Exception $e) {
            $this->assertEquals('Parameter is not a valid response', $e->getMessage());
            return;
        }
        $this->fail('Expected Net_Growl_Exception was not thrown');
    }
}
 