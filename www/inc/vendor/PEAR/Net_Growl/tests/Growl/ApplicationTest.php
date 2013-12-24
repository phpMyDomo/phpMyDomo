<?php
/**
 * Unit tests for Net_Growl package using Application object
 *
 * PHP version 5
 *
 * @category Networking
 * @package  Net_Growl
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @license  http://www.opensource.org/licenses/bsd-license.php  BSD
 * @version  SVN: $Id:$
 * @link     http://pear.php.net/package/Net_Growl
 * @since    File available since Release 2.4.0
 */

require_once 'Net/Growl/Autoload.php';

/**
 * Unit test for Net_Growl_Gntp class
 */
class Net_Growl_ApplicationTest extends PHPUnit_Framework_TestCase
{
    /**
     * test a bad Application object name settings that raise an exception
     */
    public function testWrongApplicationNameSettings()
    {
        $appName  = 123;
        $appIcon  = '';
        $password = 'mamasam';
        $options  = array(
            'protocol' => 'gntpMock',
        );

        $notifications = array();

        try {
            $app = new Net_Growl_Application(
                $appName,
                $notifications,
                $password,
                $appIcon
            );
        }
        catch (Exception $e) {
            $this->assertEmpty($e->getMessage());
            return;
        }
        $this->fail('Expected InvalidArgumentException was not thrown');
    }

    /**
     * test a bad Application object notifications settings that raise an exception
     */
    public function testWrongApplicationNotificationsSettings()
    {
        $appName  = 'PHP GNTP Test';
        $appIcon  = '';
        $password = 'mamasam';
        $options  = array(
            'protocol' => 'gntpMock',
        );

        $notifications = '1-2-3';

        try {
            $app = new Net_Growl_Application(
                $appName,
                $notifications,
                $password,
                $appIcon
            );
        }
        catch (Exception $e) {
            $this->assertEmpty($e->getMessage());
            return;
        }
        $this->fail('Expected InvalidArgumentException was not thrown');
    }

    /**
     * test a bad Application object password settings that raise an exception
     */
    public function testWrongApplicationPasswordSettings()
    {
        $appName  = 'PHP GNTP Test';
        $appIcon  = '';
        $password = array('secret');  // accept only string or empty() contents
        $options  = array(
            'protocol' => 'gntpMock',
        );

        $notifications = null;

        try {
            $app = new Net_Growl_Application(
                $appName,
                $notifications,
                $password,
                $appIcon
            );
        }
        catch (Exception $e) {
            $this->assertEmpty($e->getMessage());
            return;
        }
        $this->fail('Expected InvalidArgumentException was not thrown');
    }

    /**
     * test a bad Application object icon settings that raise an exception
     */
    public function testWrongApplicationIconSettings()
    {
        $appName  = 'PHP GNTP Test';
        $appIcon  = new stdClass;  // accept only string or empty() contents
        $password = 'mamasam';
        $options  = array(
            'protocol' => 'gntpMock',
        );

        $notifications = null;

        try {
            $app = new Net_Growl_Application(
                $appName,
                $notifications,
                $password,
                $appIcon
            );
        }
        catch (Exception $e) {
            $this->assertEquals(
                'Expect to be either a valid Url or a Net_Growl_Icon instance',
                $e->getMessage()
            );
            return;
        }
        $this->fail('Expected InvalidArgumentException was not thrown');
    }
}
