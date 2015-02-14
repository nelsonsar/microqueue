<?php

namespace MicroQueue\Exception;

class MessageBufferSizeOverflowException extends \RuntimeException
{
    public function __construct()
    {
        $message = sprintf('Message is greater than the allowed size');

        parent::__construct($message, 22);
    }
}
