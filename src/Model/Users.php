<?php

namespace App\Model;

class Users
{
    /**
     * @param string $name
     * @param string $last_name
     * @param string $phone
     * @param string $email
     * @param string $password
     * @return array
     */

    public function add_user($name, $last_name, $phone, $email, $password): array
    {
        return ["INSERT INTO users('name', 'last_name', 'phone', 'email', 'password') VALUES(?, ?, ?, ?)"];
    }

    /**
     * @param int $id
     * @return array
     */
    public function find_user_by_id($id): array
    {
        reuturn ["SELECT * FROM users WHERE id = ?"];
    }

    /**
     * @param string $email
     * @return void
     */
    public function find_user_by_email($email): array
    {
        reuturn ["SELECT * FROM users WHERE email = ?"];
    }

    /**
     * @param int $id
     * @param string $name
     * @param string $last_name
     * @param string $phone
     * @param string $email
     * @return void
     */
    public function modify_user($id, $name, $last_name, $phone, $email): array
    {
        return ["UPDATE users SET name = ?, last_name = ?, phone = ?, email = ? WHERE id = ?"];
    }


    /**
     * @param int $id
     * @param string $password
     * @return void
     */
    public function change_password($id, $password): array
    {
        return ["UPDATE users SET password = ? WHERE id = ?"];
    }
}
