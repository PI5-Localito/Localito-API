<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('submit', SubmitType::class)
            ->add('name', TextType::class, [
                'label' => 'input.name',
            ])
            ->add('lastName', TextType::class, [
                'label' => 'input.last_name'
            ])
            ->add('phone', TelType::class, [
                'required' => false,
                'label' => 'input.phone'
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'password.not_match',
                'required' => true,
                'first_options'  => ['label' => 'input.password'],
                'second_options' => ['label' => 'input.repeated_password'],

            ])
            ->add('email', EmailType::class, [
                'label' => 'input.email'
            ])
            ->add('avatar', FileType::class, [
                'required' => false,
                'constraints' => [
                    new File(
                        maxSize: '2M',
                        extensions: ['jpg', 'jpeg', 'png', 'gif'],
                    ),
                ],
            ]);
    }
}
