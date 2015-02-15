# microqueue
A tiny (and extremely opinionated) framework to use [mqueue](http://linux.die.net/man/7/mq_overview) and stop to use tables as queues.

## Why?

When companies need to make fast solutions for notification or communication with customer a lot of them use database tables as queues which are hard to maintain and use a lot of database resources. Besides all, it always involves nasty things (and you can see some of them [here](https://blog.engineyard.com/2011/5-subtle-ways-youre-using-mysql-as-a-queue-and-why-itll-bite-you/)) and side effects.

## How it works?

It's really, really simple:

1. Declare a queue with a **string** name;
2. Create a consumer for that queue;
3. Create a producer for the same queue;
4. Publish messages!

```MicroQueue``` does not have any configuration and use events to give you more flexibility! (Of course it was the only thing that I could think to avoid my consumer to know the producer... So, sorry).

The script above is an example of how simple it is:

```php

// Consumer.php
$queue = MicroQueue\Queue::declareQueue('sms');

$eventDispatcher = new \Armadillo\EventDispatcher;

$consumer = new MicroQueue\Consumer($queue, $eventDispatcher);

$consumer->consume(function($message, $eventDispatcher) {
    // your code for consuming messages
}

// Producer.php
$queue = MicroQueue\Queue::declareQueue('sms');

$producer = new MicroQueue\Producer($queue);

$producer->publish('This is a test' . PHP_EOL);

```

This should be enough to start publishing messages. The *examples* folder has one example on how to use events to extend consumer functionality.

## MicroQueue is a replacement for other services?

**NO**. Because of the following reasons:

- ```MicroQueue``` have a blocking nature (and most of the other services are not) and this is not nice for big jobs;
- ```MicroQueue``` is limited by session (consumer and producer need to exist in same session of Linux, for example) and by your OS (OS controls number of queues, number of messages and message size);
- ```MicroQueue``` tests will start soon (in a production environment) so I cannot ensure its reliability.

## Tests

```Producer``` and ```Consumer``` test will use ```mqueue``` installed in your machine. If you cannot run them for some hardware limitation, please use the [vagrant](https://www.vagrantup.com/) machine to do it. And sorry for have no idea on how I could have done unit tests.

## Further work

- Use another event lib like ```evenement``` (that is also tiny);
- Create a console app to help to create consumers as daemons;
