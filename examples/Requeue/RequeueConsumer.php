#!/usr/bin/php

<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/RequeueListener.php';

$queue = MicroQueue\Queue::declareQueue('sms');

$eventDispatcher = new \Armadillo\EventDispatcher;

$eventDispatcher->registerEvent('message.requeue');
$eventDispatcher->addListenerToEvent('message.requeue', new RequeueListener('sms'));

$consumer = new MicroQueue\Consumer($queue, $eventDispatcher);

while(true) {
    $consumer->consume(function($message, $eventDispatcher) {
        if ('123' == $message) {
            $eventDispatcher->dispatchEvent('message.requeue', $message);
        } else {
            file_put_contents('/tmp/microqueue_messages', $message);
        }
    });
}
