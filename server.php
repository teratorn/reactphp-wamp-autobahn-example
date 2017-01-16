<?php

require __DIR__ . '/vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Wamp\WampServer;

use Ratchet\ConnectionInterface as Conn;


class MyAPI implements Ratchet\Wamp\WampServerInterface {
    public function onPublish(Conn $conn, $topic, $event, array $exclude, array $eligible) {
        $topic->broadcast($event);
    }

    public function onCall(Conn $conn, $id, $topic, array $params) {
        $conn->callError($id, $topic, 'RPC not supported on this demo');
    }

    // No need to anything, since WampServer adds and removes subscribers to Topics automatically
    public function onSubscribe(Conn $conn, $topic) {}
    public function onUnSubscribe(Conn $conn, $topic) {}

    public function onOpen(Conn $conn) {
        echo "onOpen\n";
    }

    public function onClose(Conn $conn) {
        echo "onClose\n";
    }

    public function onError(Conn $conn, \Exception $e) {
        echo "onError" . $e;
    }
}

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new WampServer(
                new MyAPI()
            )
        )
    ),
    8080
);

$server->loop->addTimer(0.001, function($timer) {
    echo "React event-loop running.\n";
});

$server->run();
