<?php

namespace MicroQueue;

class Producer
{
    private $queue = null;

    const ERROR_PRODUCER_SEND_UNKNOWN_ERROR = 'Unknown error. Please report it.';
    const PRODUCER_DEFAULT_MESSAGE_TYPE = 1;

    public function __construct(Queue $queue)
    {
        $this->queue = $queue;
    }

    public function publish($message)
    {
        if (empty($message)) throw new Exception\EmptyMessageException;

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

        if (false === $result) $this->throwExceptionForErrorCode($errorCode);

        return true;
    }

    private function throwExceptionForErrorCode($errorCode)
    {
        if (PosixErrorCode::EINVAL == $errorCode) throw new Exception\MessageBufferSizeOverflowException;

        throw new \RuntimeException(self::ERROR_PRODUCER_SEND_UNKNOWN_ERROR);
    }
}
