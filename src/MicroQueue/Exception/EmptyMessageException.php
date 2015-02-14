<?php

namespace MicroQueue\Exception;

class EmptyMessageException extends \InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Message cannot be empty');
    }
}
