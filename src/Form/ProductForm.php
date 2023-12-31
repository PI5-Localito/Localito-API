<?php

namespace App\Form;

use App\Entity\Stand;
use App\Model\StandRepo;
use App\Service\MysqlStorage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductForm extends AbstractType
{
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
        $builder
            ->add('standId', HiddenType::class, [
                'data' => $options['sid'],
            ])
            ->add('name', TextType::class, [
                'label' => 'input.name'
            ])
            ->add('info', TextareaType::class, [
                'label' => 'input.info'
            ])
            ->add('image', FileType::class, [
                'required' => false,
                'constraints' => [
                    new File(
                        maxSize: '2M',
                        extensions: ['jpg', 'jpeg', 'png', 'gif'],
                    ),
                ],
            ])
            ->add('price', NumberType::class, [
                'label' => 'input.price',
            ])
            ->add('submit', SubmitType::class);
    }
}
