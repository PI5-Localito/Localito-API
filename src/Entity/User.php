<?php

namespace App\Entity;

use App\Model\UserRepo;
use Lib\Storage\AbstractEntity;
use Lib\Storage\Annotations\Column;
use Lib\Storage\Annotations\Table;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Mime\Email;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\PasswordStrength;

#[Table('users', UserRepo::class)]
class User extends AbstractEntity
{
    #[Column('name')]
    #[Assert\NotBlank(message: 'not.blank')]
    #[Assert\NotNull(message: 'not.null')]
    public string $name;

    #[Column('last_name')]
    #[Assert\NotBlank(message: 'not.blank')]
    #[Assert\NotNull(message: 'not.null')]
    public string $lastName;

    #[Column('phone')]
    #[Assert\Length(exactly: 10, exactMessage: 'string.length')]
    #[Assert\Type(type: 'digit', message: 'type.digit')]
    public ?string $phone;

    #[Column('email')]
    #[Assert\NotBlank(message: 'not.blank')]
    #[Assert\NotNull(message: 'not.null')]
    public Email $email;

    #[Column('avatar', 'getAvatarUri', 'setAvatarFromUri')]
    #[Assert\NotBlank(message: 'not.blank')]
    #[Assert\NotNull(message: 'not.null')]
    public ?File $avatar;

    #[Column('password')]
    #[Assert\NotBlank(message: 'not.blank')]
    #[Assert\NotNull]
    #[Assert\NotCompromisedPassword(message: 'password.compromised')]
    #[Assert\PasswordStrength([ 'minScore' => PasswordStrength::STRENGTH_MEDIUM ], message: 'password.weak')]
    public string $password;

    public function getFullName(string $format = '%s %s'): string
    {
        return sprintf($format, $this->name, $this->lastName);
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

    public function hashPassword(string $password): string
    {
        return password_hash($this->password, PASSWORD_DEFAULT);
    }

    public function getAvatarUri(): string
    {
        return $this->avatar->getPathname();
    }

    public function setAvatarFromUri(string $path): static
    {
        $this->avatar = new File(path: $path);
        return $this;
    }
}
