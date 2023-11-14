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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class StandForm extends AbstractType
{
    protected SellerRepo $SellerModel;
    protected UserRepo $UserModel;
    protected CityRepo $CityModel;

    public function __construct(MysqlStorage $storage)
    {
        $this->SellerModel = $storage->getModel(Seller::class);
        $this->UserModel = $storage->getModel(User::class);
        $this->CityModel = $storage->getModel(City::class);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $sellers = [];
        {
            /** @var array<Seller> */
            $tmp = $this->SellerModel->all();
            foreach ($tmp as $key => $value) {
                $name = $this->UserModel->get($value->userId)->getFullName();
                $sellers[$name] = $value->id;
            }
        }

        $cities = [];
        {
            /** @var array<City> */
            $tmp = $this->CityModel->all();
            foreach ($tmp as $key => $value) {
                $name = $value->cityName;
                $cities[$name] = $value->id;
            }
        }

        $builder->add('sellerId', ChoiceType::class, [
            'label' => 'Seller',
            'choices' => $sellers,
        ])
        ->add('tag', TextType::class, [
            'label' => 'input.tag'
        ])
        ->add('standName', TextType::class, [
            'label' => 'input.StandName'
        ])
        ->add('info', TextareaType::class, [
            'label' => 'input.info'
        ])
        ->add('city', ChoiceType::class, [
            'label' => 'input.city',
            'choices' => $cities
        ])
        ->add('submit', SubmitType::class);
    }
}
