<?php

namespace App\Entity;

use App\Model\AdminRepo;
use Lib\Storage\AbstractEntity;
use Lib\Storage\Annotations\Column;
use Lib\Storage\Annotations\Table;
use Lib\Storage\Traits\AnnotationMappings;
use Lib\Storage\Traits\ColumnHydrate;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\PasswordStrength;

#[Table('admins', AdminRepo::class)]
class Admin extends AbstractEntity
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

    #[Column('email')]
    #[Assert\NotBlank(message: 'not.blank')]
    #[Assert\NotNull(message: 'not.null')]
    public string $email;

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

    public function setPasswordHash(string $password): static
    {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return $this;
    }

    public function jsonSerialize(): mixed
    {
        $data = $this->extract();
        unset($data['password']);
        return $data;
    }
}
