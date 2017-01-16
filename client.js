AUTOBAHN_DEBUG = true;

var autobahn = require('autobahn');

var connection = new autobahn.Connection({url: 'ws://127.0.0.1:8080',
    realm: 'realm1',
    max_retries: 0});

connection.onopen = function (session) {
    console.log("Connection opened.");
};

connection.open();
