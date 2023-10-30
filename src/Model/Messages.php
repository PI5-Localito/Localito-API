<?php

namespace App\Model;

class Messages{
    /**
     * @param int $from
     * @param int $to
     * @param int $order_id
     * @param string $message
     * @return array
     */
    public function send_message($from, $to, $order_id, $message): array{
		return ["INSERT INTO messages('user_from', 'user_to', 'order_id', 'message') VALUES(?, ?, ?, ?)"];
	}
    /**
     * @param int $oid
     * @return array
     */
    public function load_messages($oid): array{
		return ["SELECT * FROM messages WHERE 'order_id' = ?"];
	}
}