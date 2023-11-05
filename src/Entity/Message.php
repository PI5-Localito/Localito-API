<?php

namespace App\Entity;

use Lib\Storage\Entity;
use Lib\Storage\Traits\MethodHydrator;


class Message implements Entity
{
    use MethodHydrator;

    protected ?int $id;
    protected ?int $userFrom;
    protected ?int $userTo;
    protected ?int $orderId;
    protected ?string $body;
    protected string $messageTimestamp;
    /**
     * @return array<string,array>
     */
    public function mappings(): array{
        return [
            'id' => [$this->getId, $this->setId],
            'user_from' => [$this->getFrom, $this->setFrom],
            'user_to' => [$this->getTo, $this->setTo],
            'order_id' => [$this->getOrder, $this->setOrder],
            'body' => [$this->getBody, $this->setBody],
            'message_timestamp' => [$this->getTimestamp, $this->setTimestamp]
        ];
    }

    public function setId(?int $id = null): static
    {
        $this->id = $id;
        return $this;
    }
    public function getId(): ?int
    {
        return $this->id;
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
        $this->body = $body;
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

