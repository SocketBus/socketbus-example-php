<?php
error_reporting(E_ERROR | E_PARSE);
require __DIR__ . '/vendor/autoload.php';

function return_json() {
    header('Content-Type: application/json');
}

use SocketBus\SocketBus;


const APP_ID = 'my-app-ip';
const APP_SECRET = 'my-sercret';

function getSocketBus() {
    return new SocketBus([
        'app_id' => APP_ID,
        'secret' => APP_SECRET,
        'custom_encryption_key' => 'My-test'
    ]);
}

function auth() {
    $socketId = $_POST['socket_id'];
    $channelName = $_POST['channel_name'];
    $response = getSocketBus()->auth($socketId, $channelName);
    die(json_encode($response));
}

function broadcast() {
    $payload = [
        'type' => 'cupcake',
        'flavor' => 'sweet'
    ];

    getSocketBus()->broadcast(['foods'], 'new-food', $payload);
}

function authPresence() {
    $socketId = $_GET['socket_id'];
    $channelName = $_GET['channel_name'];
    $userId = 'user-'.rand(100,999);

    return getSocketBus()->authPresence($socketId, $channelName, $userId, true);
}

function welcome() {
    echo file_get_contents("./public/index.html");
}

if (isset($_GET['method'])) {
    $fn = $_GET['method'];
} else {
    $fn = 'welcome';
}

if ($fn !== 'welcome') {
    return_json();
}

call_user_func($fn);
