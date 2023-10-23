<?php

namespace App\Entity;

use App\Storage\StorageResultParseableTrait;

class User extends Base
{
    use StorageResultParseableTrait;

    protected string $name;
    protected string $last_name;
    protected string $phone;
    protected string $email;
    protected string $password;

    /** Name functions */

    public function setName(string $name, string $last_name): static
    {
        $this->name = mb_strcut($name, 0, 100);
        $this->last_name = mb_strcut($name, 0, 100);
        return $this;
    }

    public function getFullName(string $format = '%s %s'): string
    {
        return sprintf($format, $this->name, $this->last_name);
    }

    public function getLastName(): string
    {
        return $this->last_name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /** Phone functions */

    public function setPhone(string $phone): static
    {
        $this->phone = mb_strcut($phone, 0, 15);
        return $this;
    }

    public function getPhone(?string $format = null): string
    {
        // TODO: Implement formatting for the phone number
        return $format ?: $this->phone;
    }

    /** Email */

    public function setEmail(string $email): static
    {
        $this->email = mb_strcut($email, 0, 255);
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    /** Password */

    public function setPassword(string $password): static
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $this->password = $password;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

}
