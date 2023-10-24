<?php

namespace App\Entity;

use App\Storage\StorageResultParseableTrait;

class Message extends Base{
	use StorageResultParseableTrait;

	protected int $id;
	protected ?int $user_from;
	protected ?int $user_to;
	protected ?int $order_id;
	protected ?string $message;
	protected string $message_timestamp;

	public function setMessage(Message $message): static{
		$this->id = $message->id;
		return $this;
	}

	public function getMessage(bool $raw = false): int|Message{
		return $raw ? $this->id : new Message($this->id);
	}

	public function setFrom(User $user): static{
		$this->user_from = $user->id();
		return $this;
	}

	public function getFrom(bool $raw = false): int|User{
		return $raw ? $this->user_from : new User($this->user_from);
	}

	public function setTo(User $user): static{
		$this->user_to = $user->id();
		return $this;
	}

	public function getTo(bool $raw = false): int|User{
		return $raw ? $this->user_to : new User($this->user_to);
	}

	public function setOrder(Order $order): static{
		$this->order_id = $order->id();
		return $this;
	}

	public function getOrder(bool $raw = false): int|Order{
		return $raw ? $this->order_id : new Order($this->order_id);
	}

	public  function setMessageContent(string $message): static{
		$this->message = $message;
		return $this;
	}

	public function getMessageContent(): ?string{
		return $this->message;
	}

	public function setTimestamp(string $message_timestamp): static{
		$this->message_timestamp = $message_timestamp;
		return $this;
	}

	public function getTimestamp(): string{
		return $this->message_timestamp;
	}
}