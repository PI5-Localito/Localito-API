<?php

namespace App\Form;

use App\Model\Stands;
use App\Service\MysqlStorage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductForm extends AbstractType
{
    public function __construct(protected MysqlStorage $storage)
    {

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $model = $this->storage->getModel(Stands::class);
        $stands = $model->all();
        $builder->add('submit', SubmitType::class)
                ->add('standId', ChoiceType::class, [
                    'choices' => $stands,
                    'choice_label' => fn ($s) => $s?->getName(),
                    'choice_value' => fn ($s) => $s?->getId()
                ])
                ->add('name', TextType::class)
                ->add('info', TextareaType::class)
                ->add('image', FileType::class)
                ->add('price', NumberType::class);
    }
}

