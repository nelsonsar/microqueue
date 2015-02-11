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
        $consumer = new Consumer;

        $callbackWasExecuted = false;

        $consumer->consume($this->queue, function($message, $consumer) use (&$callbackWasExecuted) {
            $callbackWasExecuted = true;
        });

        $this->assertTrue($callbackWasExecuted);
    }

    public function testConsumeShouldNotExecuteCallbackWhenMessageTypeIsNotTheExpectedOne()
    {
        $this->markTestIncomplete('Need to create a configurable producer to make tests');

        $consumer = new Consumer;

        $callbackWasExecuted = false;

        $consumer->consume($this->queue, function($message, $consumer) use (&$callbackWasExecuted) {
            $callbackWasExecuted = true;
        });

        $this->assertFalse($callbackWasExecuted);
    }
}
