#!/usr/bin/php

<?php

require_once __DIR__ . '/../../vendor/autoload.php';

$queue = MicroQueue\Queue::declareQueue('sms');

$eventDispatcher = new \Armadillo\EventDispatcher;

$consumer = new MicroQueue\Consumer($queue, $eventDispatcher);

$consumer->consume(function($message, $eventDispatcher) {
    file_put_contents('/tmp/microqueue_messages', $message);
});
