<?php

namespace MicroQueue;

class ConsumerTest extends \PHPUnit_Framework_TestCase
{
    private $queue = null;

    protected function setUp()
    {
        $this->queue = Queue::declareQueue('nls');
        $producer = new Producer($this->queue);
        $producer->publish('This is a test');
    }

    protected function tearDown()
    {
        msg_remove_queue($this->queue->getResource());
        $this->queue = null;
    }

    public function testConsumeShouldExecuteGivenCallback()
    {
        $eventDispatcher = new \Armadillo\EventDispatcher;
        $consumer = new Consumer($eventDispatcher);

        $callbackWasExecuted = false;
        $eventDispatcherClass = "";
        $consumeCallback = function($message, $eventDispatcher) use (&$callbackWasExecuted, &$eventDispatcherClass) {
            $callbackWasExecuted = true;
            $eventDispatcherClass = get_class($eventDispatcher);
        };

        $consumer->consume($this->queue, $consumeCallback);

        $this->assertTrue($callbackWasExecuted);
        $this->assertEquals("Armadillo\EventDispatcher", $eventDispatcherClass);
    }
}
