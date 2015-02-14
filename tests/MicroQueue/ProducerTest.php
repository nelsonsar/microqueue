<?php

namespace MicroQueue;

class ProducerTest extends \PHPUnit_Framework_TestCase
{
    private $queue = null;
    private $producer = null;

    protected function setUp()
    {
        $this->queue = Queue::declareQueue('nls');
        $this->producer = new Producer($this->queue);
    }

    protected function tearDown()
    {
        msg_remove_queue($this->queue->getResource());
        $this->queue = null;
    }

    /**
     * @expectedException MicroQueue\Exception\MessageBufferSizeOverflowException
     * @expectedExceptionMessage Message is greater than the allowed size
     */
    public function testPublishShouldThrowExceptionWhenMessageSizeIsLargerThanPermitted()
    {
        $bigMessage = str_repeat('123', 3000);

        $this->producer->publish($bigMessage);
    }

    /**
     * @expectedException MicroQueue\Exception\EmptyMessageException
     * @expectedExceptionMessage Message cannot be empty
     * @dataProvider emptyMessageProvider
     */
    public function testPublishShouldThrowAnExceptionWhenMessageIsEmpty($message)
    {
        $this->producer->publish($message);
    }

    public function emptyMessageProvider()
    {
        return array(
            array(''),
            array(null),
            array([]),
            array(0),
        );
    }

    /**
     * @dataProvider messageProvider
     */
    public function testPublish($message)
    {
        $this->assertTrue($this->producer->publish($message));
    }

    public function messageProvider()
    {
        return array(
            array('This is a test'),
            array([1, 2, 3, 4]),
            array(new \ArrayObject([4, 5, 6]))
        );
    }
}
