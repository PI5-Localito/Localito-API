<?php

namespace App\Form;

use App\Entity\Stand;
use App\Model\StandRepo;
use App\Service\MysqlStorage;
use App\Trait\EntityChoices;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductForm extends AbstractType
{
    use EntityChoices;

    protected StandRepo $standModel;

    public function __construct(MysqlStorage $storage)
    {
        $this->standModel = $storage->getModel(Stand::class);
    }

    public function configureOptions(OptionsResolver $options): void
    {
        $options->setRequired('sid');
        $options->setAllowedTypes('sid', ['int']);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $stands = [];
        {
            /** @var array<Stand> */
            $tmp = $this->standModel->getBySeller($options['sid']);
            foreach ($tmp as $key => $value) {
                $name = $value->name;
                $stands[$name] = $value->id;
            }
        }

        $builder
            ->add('standId', ChoiceType::class, [
                'label' => 'Stand',
                'choices' => $stands,
            ])
            ->add('name', TextType::class, [
                'label' => 'input.name'
            ])
            ->add('info', TextareaType::class, [
                'label' => 'input.info'
            ])
            ->add('image', HiddenType::class, [
                'data' => 'placeholder'
            ])
            ->add('price', NumberType::class, [
                'label' => 'input.price',
            ])
            ->add('submit', SubmitType::class);
    }
}
