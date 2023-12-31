<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Seller;
use App\Entity\User;
use App\Model\CityRepo;
use App\Model\SellerRepo;
use App\Model\UserRepo;
use App\Service\MysqlStorage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormBuilderInterface;

class StandForm extends AbstractType
{
    protected SellerRepo $sellerModel;
    protected UserRepo $userModel;
    protected CityRepo $cityModel;

    public function __construct(MysqlStorage $storage)
    {
        $this->sellerModel = $storage->getModel(Seller::class);
        $this->userModel = $storage->getModel(User::class);
        $this->cityModel = $storage->getModel(City::class);
    }

    public function configureOptions(OptionsResolver $options){
        $options->setRequired('sid');
        $options->setAllowedTypes('sid', ['int']);
        $options->setRequired('rol');
        $options->setAllowedTypes('rol', ['string']);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if($options['rol'] == 'admin'){
            $sellers = [];
            {
                /** @var array<Seller> */
                $tmp = $this->sellerModel->all();
                foreach ($tmp as $key => $value) {
                    $name = $this->userModel->get($value->userId)->getFullName();
                    $sellers[$name] = $value->id;
                }
            }
            $builder
            ->add('sellerId', ChoiceType::class, [
                'label' => 'Seller',
                'choices' => $sellers,
            ]);
        }else if($options['rol'] == 'seller'){
            $builder
            ->add('sellerId', HiddenType::class, [
                'data' => $options['sid'],
            ]);
        }

        $cities = [];
        {
            /** @var array<City> */
            $tmp = $this->cityModel->all();
            foreach ($tmp as $key => $value) {
                $name = $value->cityName;
                $cities[$name] = $value->id;
            }
        }

        $builder
            ->add('tag', TextType::class, [
                'label' => 'input.tag'
            ])
            ->add('name', TextType::class, [
                'label' => 'input.StandName'
            ])
            ->add('info', TextareaType::class, [
                'label' => 'input.info'
            ])
            ->add('city', ChoiceType::class, [
                'label' => 'input.city',
                'choices' => $cities
            ])
            ->add('category', ChoiceType::class, [
                'label' => 'Category',
                'choices' => [
                    'Comida' => 'Comida',
                    'Herramientas' => 'Herramientas',
                    'Moda' => 'Moda',
                    'Servicios' => 'Servicios',
                    'Mascotas' => 'Mascotas'
                ]
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
            ->add('submit', SubmitType::class);
    }
}
