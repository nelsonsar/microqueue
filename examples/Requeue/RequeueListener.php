<?php

class RequeueListener implements \SplObserver
{
    private $queueName = '';

    public function __construct($queueName)
    {
        $this->queueName = $queueName;
    }

    public function update(\SplSubject $subject)
    {
        $queue = Queue::declareQueue($this->queueName);
        $producer = new \MicroQueue\Producer($queue);
        $message = $subject->getData();

        $producer->publish($message);
    }
}
