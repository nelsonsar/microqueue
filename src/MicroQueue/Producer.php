<?php

namespace MicroQueue;

class Producer
{
    private $queue = null;

    const ERROR_PRODUCER_QUEUE_IS_FULL = 'Queue reached max number of messages';
    const ERROR_PRODUCER_SEND_UNKNOWN_ERROR = 'Unknown error';
    const PRODUCER_DEFAULT_MESSAGE_TYPE = 1;
    const ERROR_PRODUCER_MESSAGE_BUFFER_OVERFLOW = 'Message size is larger than the allowed value';

    public function __construct(Queue $queue)
    {
        $this->queue = $queue;
    }

    public function publish($message)
    {
        if (empty($message)) throw new \RuntimeException('Message cannot be empty');

        $queueResource = $this->queue->getResource();
        $serializeMessage = true;
        $isBlocking = true;
        $errorCode = 0;

        $result = @msg_send(
            $queueResource,
            self::PRODUCER_DEFAULT_MESSAGE_TYPE,
            $message,
            $serializeMessage,
            $isBlocking,
            $errorCode
        );

        if (false === $result) throw new \RuntimeException($this->parseErrorCode($errorCode));

        return true;
    }

    private function parseErrorCode($errorCode)
    {
        if (MSG_EAGAIN == $errorCode) {
            return self::ERROR_PRODUCER_QUEUE_IS_FULL;
        } elseif (PosixErrorCode::EINVAL == $errorCode) {
            return self::ERROR_PRODUCER_MESSAGE_BUFFER_OVERFLOW;
        }
    }
}
