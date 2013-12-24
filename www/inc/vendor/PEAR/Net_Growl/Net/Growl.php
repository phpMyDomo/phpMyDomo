<?php
/**
 * Copyright (c) 2009-2013, Laurent Laville <pear@laurent-laville.org>
 *                          Bertrand Mansion <bmansion@mamasam.com>
 *
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the authors nor the names of its contributors
 *       may be used to endorse or promote products derived from this software
 *       without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS
 * BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * PHP version 5
 *
 * @category Networking
 * @package  Net_Growl
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @author   Bertrand Mansion <bmansion@mamasam.com>
 * @license  http://www.opensource.org/licenses/bsd-license.php  BSD
 * @version  SVN: $Id: Growl.php 329346 2013-01-29 17:07:45Z farell $
 * @link     http://growl.laurent-laville.org/
 * @link     http://pear.php.net/package/Net_Growl
 * @since    File available since Release 0.9.0
 */

/**
 * Sends notifications to {@link http://growl.info Growl}
 *
 * This package makes it possible to easily send a notification from
 * your PHP script to {@link http://growl.info Growl}.
 *
 * Growl is a global notification system for Mac OS X.
 * Any application can send a notification to Growl, which will display
 * an attractive message on your screen. Growl currently works with a
 * growing number of applications.
 *
 * The class provides the following capabilities:
 * - Register your PHP application in Growl.
 * - Let Growl know what kind of notifications to expect.
 * - Notify Growl.
 * - Set a maximum number of notifications to be displayed (beware the loops !).
 *
 * @category Networking
 * @package  Net_Growl
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @author   Bertrand Mansion <bmansion@mamasam.com>
 * @license  http://www.opensource.org/licenses/bsd-license.php  BSD
 * @version  Release: 2.7.0
 * @link     http://growl.laurent-laville.org/
 * @link     http://pear.php.net/package/Net_Growl
 * @link     http://growl.info Growl Homepage
 * @since    Class available since Release 0.9.0
 */
class Net_Growl
{
    /**
     * Growl version
     */
    const VERSION = '2.7.0';

    /**
     * Growl default UDP port
     */
    const UDP_PORT = 9887;

    /**
     * Growl default GNTP port
     */
    const GNTP_PORT = 23053;

    /**
     * Growl priorities
     */
    const PRIORITY_LOW = -2;
    const PRIORITY_MODERATE = -1;
    const PRIORITY_NORMAL = 0;
    const PRIORITY_HIGH = 1;
    const PRIORITY_EMERGENCY = 2;

    /**
     * PHP application object
     *
     * This is usually a Net_Growl_Application object but can really be
     * any other object as long as Net_Growl_Application methods are
     * implemented.
     *
     * @var object
     */
    private $_application;

    /**
     * Application is registered
     * @var bool
     */
    protected $isRegistered = false;

    /**
     * Net_Growl connection options
     * @var array
     */
    protected $options = array(
        'host' => '127.0.0.1',
        'port' => self::UDP_PORT,
        'protocol' => 'udp',
        'timeout' => 30,
        'context' => array(),
        'passwordHashAlgorithm' => 'MD5',
        'encryptionAlgorithm' => 'NONE',
        'debug' => false,
        'resourceDir' => false,
        'defaultIcon' => 'growl.png'
    );

    /**
     * Current number of notification being displayed on user desktop
     * @var int
     */
    protected $growlNotificationCount = 0;

    /**
     * Maximum number of notification to be displayed on user desktop
     * @var int
     */
    private $_growlNotificationLimit = 0;

    /**
     * Handle to the log file.
     * @var resource
     * @since 2.0.0b2
     */
    private $_fp = false;

    /**
     * Notification callback results
     *
     * @var array
     * @since 2.0.0b2
     */
    protected $growlNotificationCallback = array();

    /**
     * Notification unique instance
     * @var   object
     * @since 2.1.0
     * @see   singleton, reset
     */
    protected static $instance = null;

    /**
     * Singleton
     *
     * Makes sure there is only one Growl connection open.
     *
     * @param mixed  &$application  Can be either a Net_Growl_Application object
     *                              or the application name string
     * @param array  $notifications List of notification types
     * @param string $password      (optional) Password for Growl
     * @param array  $options       (optional) List of options : 'host', 'port',
     *                              'protocol', 'timeout' for Growl socket server.
     *                              'passwordHashAlgorithm', 'encryptionAlgorithm'
     *                              to secure communications.
     *                              'debug' to know what data are sent and received.
     *
     * @return object Net_Growl
     * @throws Net_Growl_Exception if class handler does not exists
     */
    public static final function singleton(&$application, $notifications,
        $password = '', $options = array()
    ) {
        if (isset($options['errorHandler']) && $options['errorHandler'] === true) {
            // Converts standard error into exception
            set_error_handler(array('Net_Growl', 'errorHandler'));
        }

        if (self::$instance === null) {
            if (isset($options['protocol'])) {
                if ($options['protocol'] == 'tcp') {
                    $protocol = 'gntp';
                } else {
                    $protocol = $options['protocol'];
                }
            } else {
                $protocol = 'udp';
            }
            if ($protocol == 'udp') {
                $options['port'] = self::UDP_PORT;
            } else {
                $options['port'] = self::GNTP_PORT;
            }
            $class = 'Net_Growl_' . ucfirst($protocol);

            if (class_exists($class, true)) {
                self::$instance = new $class(
                    $application, $notifications, $password, $options
                );
            } else {
                $message = 'Cannot find class "'.$class.'"';
                throw new Net_Growl_Exception($message);
            }
        }
        return self::$instance;
    }

    /**
     * Resettable Singleton Solution
     *
     * @return void
     * @link http://sebastian-bergmann.de/archives/882-guid.html
     *       Testing Code That Uses Singletons
     * @since 2.1.0
     */
    public static final function reset()
    {
        self::$instance = null;
    }

    /**
     * Constructor
     *
     * This method instantiate a new Net_Growl object and opens a socket connection
     * to the specified Growl socket server.
     * Currently, only UDP is supported by Growl.
     * The constructor registers a shutdown function {@link Net_Growl::_Net_Growl()}
     * that closes the socket if it is open.
     *
     * Example 1.
     * <code>
     * require_once 'Net/Growl.php';
     *
     * $notifications = array('Errors', 'Messages');
     * $growl = Net_Growl::singleton('My application', $notification);
     * $growl->notify( 'Messages',
     *                 'My notification title',
     *                 'My notification description');
     * </code>
     *
     * @param mixed  &$application  Can be either a Net_Growl_Application object
     *                              or the application name string
     * @param array  $notifications (optional) List of notification types
     * @param string $password      (optional) Password for Growl
     * @param array  $options       (optional) List of options : 'host', 'port',
     *                              'protocol', 'timeout' for Growl socket server.
     *                              'passwordHashAlgorithm', 'encryptionAlgorithm'
     *                              to secure communications.
     *                              'debug' to know what data are sent and received.
     *
     * @return void
     */
    protected function __construct(&$application, $notifications = array(),
        $password = '', $options = array()
    ) {
        foreach ($options as $k => $v) {
            if (isset($this->options[$k])) {
                $this->options[$k] = $v;
            }
        }
        $timeout = $this->options['timeout'];
        if (!is_int($timeout)) {
            // get default timeout (in seconds) for socket based streams.
            $timeout = ini_get('default_socket_timeout');
        }
        if (!is_int($timeout)) {
            // if default timeout not available on php.ini, then use this one
            $timeout = 30;
        }
        $this->options['timeout'] = $timeout;

        if (is_string($application)) {
            if (isset($options['AppIcon'])) {
                $icon = $options['AppIcon'];
            } else {
                $icon = '';
            }
            $this->_application = new Net_Growl_Application(
                $application, $notifications, $password, $icon
            );
        } elseif (is_object($application)) {
            $this->_application = $application;
        }

        if (is_string($this->options['debug'])) {
            $this->_fp = fopen($this->options['debug'], 'a');
        }
    }

    /**
     * Destructor
     *
     * @since 2.0.0b2
     */
    public function __destruct()
    {
        if (is_resource($this->_fp)) {
            fclose($this->_fp);
        }
    }

    /**
     * Gets options used with current Growl object
     *
     * @return array
     * @since  2.6.0
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Limit the number of notifications
     *
     * This method limits the number of notifications to be displayed on
     * the Growl user desktop. By default, there is no limit. It is used
     * mostly to prevent problem with notifications within loops.
     *
     * @param int $max Maximum number of notifications
     *
     * @return void
     */
    public function setNotificationLimit($max)
    {
        $this->_growlNotificationLimit = $max;
    }

    /**
     * Returns the registered application object
     *
     * @return object Application
     * @see Net_Growl_Application
     */
    public function getApplication()
    {
        return $this->_application;
    }

    /**
     * Sends a application register to Growl
     *
     * @return Net_Growl_Response
     * @throws Net_Growl_Exception if REGISTER failed
     */
    public function register()
    {
        return $this->sendRegister();
    }

    /**
     * Sends a notification to Growl
     *
     * Growl notifications have a name, a title, a description and
     * a few options, depending on the kind of display plugin you use.
     * The bubble plugin is recommended, until there is a plugin more
     * appropriate for these kind of notifications.
     *
     * The current options supported by most Growl plugins are:
     * <pre>
     * array('priority' => 0, 'sticky' => false)
     * </pre>
     * - sticky: whether the bubble stays on screen until the user clicks on it.
     * - priority: a number from -2 (low) to 2 (high), default is 0 (normal).
     *
     * @param string $name        Notification name
     * @param string $title       Notification title
     * @param string $description (optional) Notification description
     * @param string $options     (optional) few Notification options
     *
     * @return Net_Growl_Response | FALSE
     * @throws Net_Growl_Exception if NOTIFY failed
     */
    public function notify($name, $title, $description = '', $options = array())
    {
        if ($this->_growlNotificationLimit > 0
            && $this->growlNotificationCount >= $this->_growlNotificationLimit
        ) {
            // limit reached: no more notification displayed on user desktop
            return false;
        }

        if (!$this->isRegistered) {
            $this->sendRegister();
        }
        return $this->sendNotify($name, $title, $description, $options);
    }

    /**
     * Alias of notify() method.
     *
     * @param string $name        Notification name
     * @param string $title       Notification title
     * @param string $description (optional) Notification description
     * @param string $options     (optional) few Notification options
     *
     * @return Net_Growl_Response | FALSE
     * @throws Net_Growl_Exception if NOTIFY failed
     */
    public function publish($name, $title, $description = '', $options = array())
    {
        return $this->notify($name, $title, $description, $options);
    }

    /**
     * Send request to remote server
     *
     * @param string $method   Either REGISTER, NOTIFY
     * @param mixed  $data     Data block to send
     * @param bool   $callback (optional) Socket callback request
     *
     * @return Net_Growl_Response | TRUE
     * @throws Net_Growl_Exception if remote server communication failure
     */
    protected function sendRequest($method, $data, $callback = false)
    {
        // @codeCoverageIgnoreStart
        $protocol = $this->options['protocol'] == 'udp' ? 'udp' : 'tcp';

        $addr = $protocol . '://' . $this->options['host'];

        $this->debug(
            $addr . ':' .
            $this->options['port'] . ' ' .
            $this->options['timeout']
        );

        // open connection
        if (is_array($this->options['context'])
            && function_exists('stream_context_create')
        ) {
            $context = stream_context_create($this->options['context']);

            if (function_exists('stream_socket_client')) {
                $flags = STREAM_CLIENT_CONNECT;
                $addr  = $addr . ':' . $this->options['port'];
                $sh = @stream_socket_client(
                    $addr, $errno, $errstr,
                    $this->options['timeout'], $flags, $context
                );
            } else {
                $sh = @fsockopen(
                    $addr, $this->options['port'],
                    $errno, $errstr, $$this->options['timeout'], $context
                );
            }
        } else {
            $sh = @fsockopen(
                $addr, $this->options['port'],
                $errno, $errstr, $$this->options['timeout']
            );
        }

        if ($sh === false) {
            $this->debug($errstr, 'error');
            $error = 'Could not connect to Growl Server.';
            throw new Net_Growl_Exception($error);
        }
        stream_set_timeout($sh, $this->options['timeout'], 0);

        $this->debug($data);
        $res = fwrite($sh, $data, $this->strByteLen($data));

        if ($res === false) {
            $error = 'Could not send data to Growl Server.';
            throw new Net_Growl_Exception($error);
        }

        switch ($protocol) {
        case 'tcp':
            // read GNTP response
            $line = $this->_readLine($sh);
            $this->debug($line);
            $response = new Net_Growl_Response($line);
            $statusOK = ($response->getStatus() == 'OK');
            while ($this->strByteLen($line) > 0) {
                $line = $this->_readLine($sh);
                $response->appendBody($line."\r\n");
                if (is_resource($this->_fp)) {
                    $this->debug($line);
                }
            }

            if ($statusOK
                && $callback === true
                && $method == 'NOTIFY'
            ) {
                // read GNTP socket Callback response
                $line = $this->_readLine($sh);
                $this->debug($line);
                if (preg_match('/^GNTP\/1.0 -(\w+).*$/', $line, $resp)) {
                    $res = ($resp[1] == 'CALLBACK');
                    if ($res) {
                        while ($this->strByteLen($line) > 0) {
                            $line = $this->_readLine($sh);
                            $this->debug($line);
                            $eon = true;

                            $nid = preg_match(
                                '/^Notification-ID: (.*)$/',
                                $line, $resp
                            );
                            if ($nid) {
                                $eon = false;
                            }

                            $ncr = preg_match(
                                '/^Notification-Callback-Result: (.*)$/',
                                $line, $resp
                            );
                            if ($ncr) {
                                $this->growlNotificationCallback[] = $resp[1];
                                $eon = false;
                            }

                            $ncc = preg_match(
                                '/^Notification-Callback-Context: (.*)$/',
                                $line, $resp
                            );
                            if ($ncc) {
                                $this->growlNotificationCallback[] = $resp[1];
                                $eon = false;
                            }

                            $ncct = preg_match(
                                '/^Notification-Callback-Context-Type: (.*)$/',
                                $line, $resp
                            );
                            if ($ncct) {
                                $this->growlNotificationCallback[] = $resp[1];
                                $eon = false;
                            }

                            $nct = preg_match(
                                '/^Notification-Callback-Timestamp: (.*)$/',
                                $line, $resp
                            );
                            if ($nct) {
                                $this->growlNotificationCallback[] = $resp[1];
                                $eon = false;
                            }

                            if ($eon) {
                                break;
                            }
                        }
                    }
                }

                if (is_resource($this->_fp)) {
                    while ($this->strByteLen($line) > 0) {
                        $line = $this->_readLine($sh);
                        $this->debug($line);
                    }
                }
            }
            break;
        case 'udp':
            $statusOK = $response = true;
            break;
        }

        switch (strtoupper($method)) {
        case 'REGISTER':
            if ($statusOK) {
                $this->isRegistered = true;
            }
            break;
        case 'NOTIFY':
            if ($statusOK) {
                $this->growlNotificationCount++;
            }
            break;
        }

        // close connection
        fclose($sh);

        return $response;
        // @codeCoverageIgnoreEnd
    }

    /**
     * Returns Growl default icon logo binary data
     *
     * @return string
     * @throws Net_Growl_Exception if cannot get icon resource contents
     */
    public function getDefaultGrowlIcon()
    {
        if (isset($this->options['resourceDir'])
            && is_dir($this->options['resourceDir'])
        ) {
            $resourceDir = $this->options['resourceDir'];
        } else {
            $resourceDir = '@data_dir@' . DIRECTORY_SEPARATOR .
                'Net_Growl' . DIRECTORY_SEPARATOR . 'data';
        }

        $icon = new Net_Growl_Icon(
            $resourceDir . DIRECTORY_SEPARATOR . $this->options['defaultIcon']
        );
        $data = $icon->getContents();
        if (empty($data)) {
            throw new Net_Growl_Exception('Invalid Icon Resource');
        }
        return $data;
    }

    /**
     * Logs GNTP IN/OUT messages
     *
     * @param string $message  String containing the message to log
     * @param string $priority (optional) String containing a priority name
     *
     * @return void
     */
    protected function debug($message, $priority = 'debug')
    {
        if (is_resource($this->_fp)
            && $this->strByteLen($message) > 0
        ) {
            fwrite(
                $this->_fp,
                date("Y-m-d H:i:s") . " [$priority] - " . $message . "\n"
            );
        }
    }

    /**
     * Converts standard error into exception
     *
     * @param int    $errno   contains the level of the error raised
     * @param string $errstr  contains the error message
     * @param string $errfile contains the filename that the error was raised in
     * @param int    $errline contains the line number the error was raised at
     *
     * @return void
     * @throws ErrorException when a standard error occured with severity level
     *                        we are asking for (uses error_reporting)
     * @since 2.1.0
     */
    public static function errorHandler($errno, $errstr, $errfile, $errline)
    {
        // Only catch errors we are asking for
        if ((error_reporting() & $errno) == 0) {
            return;
        }
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }

    /**
     * Read until either the end of the socket or a newline, whichever
     * comes first. Strips the trailing newline from the returned data.
     *
     * @param mixed $fp a file pointer resource
     *
     * @return All available data up to a newline, without that
     *         newline, or until the end of the socket,
     * @throws Net_Growl_Exception if not connected
     */
    private function _readLine($fp)
    {
        // @codeCoverageIgnoreStart
        if (!is_resource($fp)) {
            throw new Net_Growl_Exception('not connected');
        }

        $line = '';
        $timeout = time() + $this->options['timeout'];
        while (!feof($fp) && (time() < $timeout)) {
            $line .= @fgets($fp);
            if (mb_substr($line, -1) == "\n" && $this->strByteLen($line) > 0) {
                break;
            }
        }
        return rtrim($line, "\r\n");
        // @codeCoverageIgnoreEnd
    }

    /**
     * Encodes a detect_order string to UTF-8
     *
     * @param string $data an intended string.
     *
     * @return Returns of the UTF-8 translation of $data.
     *
     * @see http://www.php.net/manual/en/function.mb-detect-encoding.php
     * @see http://www.php.net/manual/en/function.mb-convert-encoding.php
     */
    protected function utf8Encode($data)
    {
        if (extension_loaded('mbstring')) {
            return mb_convert_encoding($data, 'UTF-8', 'auto');
        } else {
            return utf8_encode($data);
        }
    }

    /**
     * Get string byte length
     *
     * @param string $string The string being measured for byte length.
     *
     * @return The byte length of the $string.
     */
    protected function strByteLen($string)
    {
        return strlen(bin2hex($string)) / 2;
    }

}
