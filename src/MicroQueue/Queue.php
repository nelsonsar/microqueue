<?php

namespace MicroQueue;

class Queue
{
    private $resource = null;
    private $ownerId = null;
    private $ownerGroupId = null;
    private $numberOfUnreadMessages = 0;
    private $messageAllowedSize = 0;

    const QUEUE_ERROR_NAME_IS_NOT_STRING = 'Queue name must a name (string) not an id (numeric sequence)';
    const QUEUE_ERROR_NAME_HAS_INVALID_CHAR = 'Queue name must contain only letters';

    public static function declareQueue($queueName)
    {
        try {
            $resource = msg_get_queue(\Nash\Numerology::coverMessage($queueName));
        } catch (\Nash\InvalidMessageFormatException $exception) {
            throw new \InvalidArgumentException(self::QUEUE_ERROR_NAME_IS_NOT_STRING);
        } catch (\Nash\UnsupportedCharacterException $exception) {
            throw new \InvalidArgumentException(self::QUEUE_ERROR_NAME_HAS_INVALID_CHAR);
        }

        if (false === is_resource($resource)) {
            throw new \RuntimeException(sprintf('Unable to create queue with name %s', $queueName));
        }

        return new self($resource);

    }

    private function __construct($queueResource)
    {
        $this->resource = $queueResource;
        $this->fillQueueGeneralInformation();
    }

    public function getOwnerId()
    {
        return $this->ownerId;
    }

    public function getOwnerGroupId()
    {
        return $this->ownerGroupId;
    }

    public function getNumberUnreadMessages()
    {
        $this->updateQueueStatus();

        return $this->numberOfUnreadMessages;
    }

    public function getResource()
    {
        return $this->resource;
    }

    public function getMessageAllowedSize()
    {
        return $this->messageAllowedSize;
    }

    private function updateQueueStatus()
    {
        $status = msg_stat_queue($this->resource);

        $this->numberOfUnreadMessages = $status['msg_qnum'];
    }

    private function fillQueueGeneralInformation()
    {
        $status = msg_stat_queue($this->resource);

        $this->ownerId = $status['msg_perm.uid'];
        $this->ownerGroupId = $status['msg_perm.gid'];
        $this->messageAllowedSize = $status['msg_qbytes'];
    }
}
