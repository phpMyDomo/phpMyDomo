<?php

require_once 'src/JsonRPC/Server.php';

use JsonRPC\Server;

class ServerTest extends PHPUnit_Framework_TestCase
{
    public function testPositionalParameters()
    {
        $subtract = function($minuend, $subtrahend) {

            return $minuend - $subtrahend;
        };


        $server = new Server('{"jsonrpc": "2.0", "method": "subtract", "params": [42, 23], "id": 1}');
        $server->register('subtract', $subtract);

        $this->assertEquals(
            json_decode('{"jsonrpc": "2.0", "result": 19, "id": 1}', true),
            json_decode($server->execute(), true)
        );


        $server = new Server('{"jsonrpc": "2.0", "method": "subtract", "params": [23, 42], "id": 1}');
        $server->register('subtract', $subtract);

        $this->assertEquals(
            json_decode('{"jsonrpc": "2.0", "result": -19, "id": 1}', true),
            json_decode($server->execute(), true)
        );
    }


    public function testNamedParameters()
    {
        $subtract = function($minuend, $subtrahend) {

            return $minuend - $subtrahend;
        };


        $server = new Server('{"jsonrpc": "2.0", "method": "subtract", "params": {"subtrahend": 23, "minuend": 42}, "id": 3}');
        $server->unregisterAll();
        $server->register('subtract', $subtract);

        $this->assertEquals(
            json_decode('{"jsonrpc": "2.0", "result": 19, "id": 3}', true),
            json_decode($server->execute(), true)
        );


        $server = new Server('{"jsonrpc": "2.0", "method": "subtract", "params": {"minuend": 42, "subtrahend": 23}, "id": 4}');
        $server->unregisterAll();
        $server->register('subtract', $subtract);

        $this->assertEquals(
            json_decode('{"jsonrpc": "2.0", "result": 19, "id": 4}', true),
            json_decode($server->execute(), true)
        );
    }


    public function testNotification()
    {
        $update = function($p1, $p2, $p3, $p4, $p5) {};
        $foobar = function() {};


        $server = new Server('{"jsonrpc": "2.0", "method": "update", "params": [1,2,3,4,5]}');
        $server->unregisterAll();
        $server->register('update', $update);
        $server->register('foobar', $foobar);

        $this->assertEquals('', $server->execute());


        $server = new Server('{"jsonrpc": "2.0", "method": "foobar"}');
        $server->unregisterAll();
        $server->register('update', $update);
        $server->register('foobar', $foobar);

        $this->assertEquals('', $server->execute());
    }


    public function testNoMethod()
    {
        $server = new Server('{"jsonrpc": "2.0", "method": "foobar", "id": "1"}');
        $server->unregisterAll();

        $this->assertEquals(
            json_decode('{"jsonrpc": "2.0", "error": {"code": -32601, "message": "Method not found"}, "id": "1"}', true),
            json_decode($server->execute(), true)
        );
    }


    public function testInvalidJson()
    {
        $server = new Server('{"jsonrpc": "2.0", "method": "foobar, "params": "bar", "baz]');
        $server->unregisterAll();

        $this->assertEquals(
            json_decode('{"jsonrpc": "2.0", "error": {"code": -32700, "message": "Parse error"}, "id": null}', true),
            json_decode($server->execute(), true)
        );
    }


    public function testInvalidRequest()
    {
        $server = new Server('{"jsonrpc": "2.0", "method": 1, "params": "bar"}');
        $server->unregisterAll();

        $this->assertEquals(
            json_decode('{"jsonrpc": "2.0", "error": {"code": -32600, "message": "Invalid Request"}, "id": null}', true),
            json_decode($server->execute(), true)
        );
    }


    public function testBatchInvalidJson()
    {
        $server = new Server('[
          {"jsonrpc": "2.0", "method": "sum", "params": [1,2,4], "id": "1"},
          {"jsonrpc": "2.0", "method"
        ]');

        $server->unregisterAll();

        $this->assertEquals(
            json_decode('{"jsonrpc": "2.0", "error": {"code": -32700, "message": "Parse error"}, "id": null}', true),
            json_decode($server->execute(), true)
        );
    }


    public function testBatchEmptyArray()
    {
        $server = new Server('[]');
        $server->unregisterAll();

        $this->assertEquals(
            json_decode('{"jsonrpc": "2.0", "error": {"code": -32600, "message": "Invalid Request"}, "id": null}', true),
            json_decode($server->execute(), true)
        );
    }


    public function testBatchNotEmptyButInvalid()
    {
        $server = new Server('[1]');
        $server->unregisterAll();

        $this->assertEquals(
            json_decode('[{"jsonrpc": "2.0", "error": {"code": -32600, "message": "Invalid Request"}, "id": null}]', true),
            json_decode($server->execute(), true)
        );
    }


    public function testBatchInvalid()
    {
        $server = new Server('[1,2,3]');
        $server->unregisterAll();

        $this->assertEquals(
            json_decode('[
                {"jsonrpc": "2.0", "error": {"code": -32600, "message": "Invalid Request"}, "id": null},
                {"jsonrpc": "2.0", "error": {"code": -32600, "message": "Invalid Request"}, "id": null},
                {"jsonrpc": "2.0", "error": {"code": -32600, "message": "Invalid Request"}, "id": null}
            ]', true),
            json_decode($server->execute(), true)
        );
    }


    public function testBatchOk()
    {
        $server = new Server('[
            {"jsonrpc": "2.0", "method": "sum", "params": [1,2,4], "id": "1"},
            {"jsonrpc": "2.0", "method": "notify_hello", "params": [7]},
            {"jsonrpc": "2.0", "method": "subtract", "params": [42,23], "id": "2"},
            {"foo": "boo"},
            {"jsonrpc": "2.0", "method": "foo.get", "params": {"name": "myself"}, "id": "5"},
            {"jsonrpc": "2.0", "method": "get_data", "id": "9"}
        ]');

        $server->unregisterAll();

        $server->register('sum', function($a, $b, $c) {

            return $a + $b + $c;
        });

        $server->register('subtract', function($minuend, $subtrahend) {

            return $minuend - $subtrahend;
        });

        $server->register('get_data', function() {

            return array('hello', 5);
        });

        $response = $server->execute();

        //var_dump($response);
        //print_r(json_decode($response, true));

        $this->assertEquals(
            json_decode('[
                {"jsonrpc": "2.0", "result": 7, "id": "1"},
                {"jsonrpc": "2.0", "result": 19, "id": "2"},
                {"jsonrpc": "2.0", "error": {"code": -32600, "message": "Invalid Request"}, "id": null},
                {"jsonrpc": "2.0", "error": {"code": -32601, "message": "Method not found"}, "id": "5"},
                {"jsonrpc": "2.0", "result": ["hello", 5], "id": "9"}
            ]', true),
            json_decode($response, true)
        );
    }


    public function testBatchNotifications()
    {
        $server = new Server('[
            {"jsonrpc": "2.0", "method": "notify_sum", "params": [1,2,4]},
            {"jsonrpc": "2.0", "method": "notify_hello", "params": [7]}
        ]');

        $server->unregisterAll();

        $server->register('notify_sum', function($a, $b, $c) {

        });

        $server->register('notify_hello', function($id) {

        });

        $this->assertEquals('', $server->execute());
    }
}