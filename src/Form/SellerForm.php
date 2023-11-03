<?php

namespace App\Form;

use App\Model\Users;
use App\Service\MysqlStorage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class SellerForm extends AbstractType
{
    public function __construct(protected MysqlStorage $storage)
    {

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $model  = $this->storage->getModel(Users::class);
        $users = $model->all();
        $builder->add('submit', SubmitType::class)
            ->add('userId', ChoiceType::class, [
                'choices' => $users,
                'choice_label' => fn ($u) => $u?->getFullName(),
                'choice_value' => fn ($u) => $u?->getId(),
            ])
            ->add('state', CheckboxType::class, [
                'label' => 'Activo'
            ]);
    }
}
