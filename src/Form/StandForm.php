<?php

namespace App\Form;

use App\Model\Cities;
use App\Model\Sellers;
use App\Service\MysqlStorage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class StandForm extends AbstractType
{
    public function __construct(protected MysqlStorage $storage)
    {

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $sellersModel = $this->storage->getModel(Sellers::class);
        $sellers = $sellersModel->all();
        $citiesModel = $this->storage->getModel(Cities::class);
        $cities = $citiesModel->all();

        $builder->add('submit', SubmitType::class)
                ->add('sellerId', ChoiceType::class, [
                    'choices' => $sellers,
                    'choice_label' => fn ($s) => $s?->getId(),
                    'choice_value' => fn ($s) => $s?->getId(),
                ])
                ->add('tag', TextType::class)
                ->add('standName', TextType::class)
                ->add('info', TextareaType::class)
                ->add('city', ChoiceType::class.[
                    'choices' => $cities,
                    'choice_label' => fn ($c) => $c?->getName(),
                    'choice_value' => fn ($c) => $c?->getId()
                ]);
    }
}

