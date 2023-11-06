<?php

namespace App\Entity;

use App\Model\UserRepo;
use Lib\Storage\AbstractEntity;
use Lib\Storage\Annotations\Column;
use Lib\Storage\Annotations\Table;
use Lib\Storage\Traits\AnnotationMappings;
use Lib\Storage\Traits\ColumnHydrate;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\PasswordStrength;

#[Table('users', UserRepo::class)]
class User extends AbstractEntity
{
    use ColumnHydrate;
    use AnnotationMappings;

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
    public string $email;

    #[Column('avatar', 'getAvatarUri', 'setAvatarFromUri')]
    public ?File $avatar = null;

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

    public function getPhoneFormat(string $format = null): ?string
    {
        // TODO: Implement formatting for the phone number
        return $format ?: $this->phone ?? null;
    }

    public function setPasswordHash(string $password): static
    {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return $this;
    }

    public function getAvatarUri(): ?string
    {
        return $this->avatar?->getPathname();
    }

    public function setAvatarFromUri(?string $path): static
    {
        if (!empty($path)) {
            $this->avatar = new File($path, false);
        }
        return $this;
    }

    public function jsonSerialize(): mixed
    {
        $data = $this->extract();
        unset($data['password']);
        return $data;
    }
}
