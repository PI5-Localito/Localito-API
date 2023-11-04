<?php

namespace App\Entity;

use Lib\Storage\Entity;
use Lib\Storage\Traits\MethodHydrator;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\PasswordStrength;

class User implements Entity
{
    use MethodHydrator;

    protected ?int $id;
    #
    #[Assert\NotBlank(message: 'not.blank')]
    #[Assert\NotNull(message: 'not.null')]
    protected string $name;

    #[Assert\NotBlank(message: 'not.blank')]
    #[Assert\NotNull(message: 'not.null')]
    protected string $lastName;

    #[Assert\Length(exactly: 10, exactMessage: 'string.length')]
    #[Assert\Type(type: 'digit', message: 'type.digit')]
    protected ?string $phone;

    #[Assert\NotBlank(message: 'not.blank')]
    #[Assert\NotNull(message: 'not.null')]
    protected ?string $email;
    protected ?string $avatar;

    #[Assert\NotBlank(message: 'not.blank')]
    #[Assert\NotNull]
    #[Assert\NotCompromisedPassword(message: 'password.compromised')]
    #[Assert\PasswordStrength([ 'minScore' => PasswordStrength::STRENGTH_MEDIUM ], message: 'password.weak')]
    protected string $password;

    /**
     * @return array<string,array>
     */
    public function mappings(): array
    {
        return [
            'id' => ['getId', 'setId'],
            'name' => ['getName', 'setName'],
            'last_name' => ['getLastName', 'setLastName'],
            'phone' => ['getPhone', 'setPhone'],
            'password' => ['getPassword', 'setPassword'],
            'email' => ['getEmail', 'setEmail'],
            'avatar' => ['getAvatar', 'setAvatar'],
        ];
    }

    public function setId(?int $id = null): static
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id ?? null;
    }

    public function setName(string $name): static
    {
        $this->name = mb_strcut($name, 0, 100);
        return $this;
    }

    public function setLastName(string $last_name): static
    {
        $this->lastName = mb_strcut($last_name, 0, 100);
        return $this;
    }

    public function getFullName(string $format = '%s %s'): string
    {
        return sprintf($format, $this->name, $this->lastName);
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = mb_strcut($phone, 0, 15);
        return $this;
    }

    public function getPhone(?string $format = null): ?string
    {
        // TODO: Implement formatting for the phone number
        return $format ?: $this->phone ?? null;
    }

    public function setEmail(string $email): static
    {
        $this->email = mb_strcut($email, 0, 255);
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email ?? null;
    }

    public function setPassword(string $password, bool $hash = false): static
    {
        $this->password = !$hash ? $password : password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getAvatar(): string
    {
        return $this->avatar ?? null;
    }

    public function setAvatar(?string $avatar): static
    {
        $this->avatar = $avatar;
        return $this;
    }
}
