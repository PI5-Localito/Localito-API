<?php

namespace App\Form;

use App\Model\Orders;
use App\Model\Users;
use App\Service\MysqlStorage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class MessageForm extends AbstractType
{
    public function __construct(protected MysqlStorage $storage)
    {

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $userModel = $this->storage->getModel(Users::class);
        $users = $userModel->all();
        $orderModel = $this->storage->getModel(Orders::class);
        $orders = $orderModel->all();
        $builder->add('submit', SubmitType::class)
                ->add('userFrom', ChoiceType::class, [
                    'choices' => $users,
                    'choice_label' => fn ($u) => $u?->getFullName(),
                    'choice_value' => fn ($u) => $u?->getId(),
                ])
                ->add('userTo', ChoiceType::class, [
                    'choices' => $users,
                    'choice_label' => fn ($u) => $u?->getFullName(),
                    'choice_value' => fn ($u) => $u?->getId(),
                ])
                ->add('orderId', ChoiceType::class, [
                    'choices' => $orders,
                    'choice_label' => "Orden #" . fn ($o) => $o?->getId(),
                    'choice_value' => fn ($o) => $o?->getId(),
                ])
                ->add('body', TextareaType::class)
                ->add('messageTimestamp', DateType::class, [
                    'widget' => 'choice',
                    'placeholder' => 'Selecciona una fecha',
                ]);
    }
}

