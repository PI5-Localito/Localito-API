<?php

namespace App\Form;

use App\Enum\UserTypes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\PasswordStrength;
use Symfony\Component\Validator\Constraints\Type;

class RegisterForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new NotNull(),
                ]
            ])
            ->add('last_name', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new NotNull(),
                ]
            ])
            ->add('phone', TelType::class, [
                'required' => false,
                'constraints' => [
                    new Length(exactly: 10),
                    new Type(type: 'digit', message: 'type.digit'),
                ]
            ])
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new NotBlank(),
                    new NotNull(),
                    new NotCompromisedPassword(),
                    new PasswordStrength(minScore: PasswordStrength::STRENGTH_MEDIUM)
                ]
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotNull(),
                    new NotBlank(),
                ]
            ])
            ->add('avatar', TextType::class, [
                'required' => false,
            ])
            ->add('type', EnumType::class, [
                'class' => UserTypes::class,
                'constraints' => [
                    new NotBlank(),
                    new NotNull(),
                ]
            ]);
    }
}
