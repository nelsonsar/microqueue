<?php

namespace MicroQueue;

class QueueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Queue name must contain only letters
     */
    public function testCreateQueueShouldThrowAnExceptionWhenQueueNameIsInvalid()
    {
        $queueName = 'a_b';

        Queue::declareQueue($queueName);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Queue name must a name (string) not an id (numeric sequence)
     */
    public function testCreateQueueShouldThrowAnExceptionWhenQueueNameIsNotAString()
    {
        $queueName = 123;

        Queue::declareQueue($queueName);
    }
}
