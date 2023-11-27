<?php

namespace App\Entity;

use App\Model\MessageRepo;
use Lib\Storage\AbstractEntity;
use Lib\Storage\Annotations\Column;
use Lib\Storage\Annotations\Table;
use Lib\Storage\Traits\AnnotationMappings;
use Lib\Storage\Traits\ColumnHydrate;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;

#[Table('messages', MessageRepo::class)]
class Message extends AbstractEntity
{
    use AnnotationMappings;
    use ColumnHydrate;

    #[Column('user_from')]
    public ?int $userFrom;

    #[Column('user_to')]
    public ?int $userTo;

    #[Column('order_id')]
    public ?int $orderId;

    #[Column('body')]
    public ?string $body;

    #[Column('message_timestamp')]
    public string $messageTimestamp;

    public function __construct(){
        $this->messageTimestamp = (new DateTime('now'))->format('Y-m-d h:m:s');
    }
    public function setFrom(int $uid): static
    {
        $this->userFrom = $uid;
        return $this;
    }

    public function getFrom(): int
    {
        return $this->userFrom;
    }

    public function setTo(int $uid): static
    {
        $this->userTo = $uid;
        return $this;
    }

    public function getTo(): int
    {
        return $this->userTo;
    }

    public function setOrder(int $oid): static
    {
        $this->orderId = $oid;
        return $this;
    }

    public function getOrder(): int
    {
        return $this->orderId;
    }

    public function setBody(string $body): static
    {
        $this->body = mb_strcut($body, 0, 255);
        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setTimestamp(string $messageTimestamp): static
    {
        $this->messageTimestamp = $messageTimestamp;
        return $this;
    }

    public function getTimestamp(): string
    {
        return $this->messageTimestamp;
    }
}
