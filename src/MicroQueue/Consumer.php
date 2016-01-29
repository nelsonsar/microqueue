<?php

namespace MicroQueue;

final class Consumer
{
    const CONSUMER_DEFAULT_MESSAGE_TYPE = 1;

    private $eventDispatcher = null;
    private $queue = null;

    public function __construct(Queue $queue, \Armadillo\EventDispatcher $eventDispatcher)
    {
        $this->queue = $queue;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function consume(callable $callback)
    {
        $queueResource = $this->queue->getResource();
        $receivedMessageType = null;
        $messageMaxSize = $this->queue->getMessageAllowedSize();
        $receivedMessage = null;
        $unserializeMessage = true;
        $flags = 0;
        $errorCode = 0;

        $result = @msg_receive(
            $queueResource,
            self::CONSUMER_DEFAULT_MESSAGE_TYPE,
            $receivedMessageType,
            $messageMaxSize,
            $receivedMessage,
            $unserializeMessage,
            $flags,
            $errorCode
        );

        if (false === $result) throw new Exception\MessageBufferSizeOverflowException;

        if (self::CONSUMER_DEFAULT_MESSAGE_TYPE != $receivedMessageType) return;

        call_user_func_array($callback, array($receivedMessage, $this->eventDispatcher));
    }
}
