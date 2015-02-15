#!/usr/bin/php

<?php

require_once __DIR__ . '/../../vendor/autoload.php';

$queue = MicroQueue\Queue::declareQueue('sms');

$producer = new MicroQueue\Producer($queue);

$producer->publish('This is a test' . PHP_EOL);
