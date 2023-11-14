<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Seller;
use App\Entity\User;
use App\Model\CityRepo;
use App\Model\SellerRepo;
use App\Model\UserRepo;
use App\Service\MysqlStorage;
use App\Trait\EntityChoices;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class StandForm extends AbstractType
{
    use EntityChoices;
    protected SellerRepo $sellerModel;
    protected UserRepo $userModel;
    protected CityRepo $cityModel;

    public function __construct(MysqlStorage $storage)
    {
        $this->sellerModel = $storage->getModel(Seller::class);
        $this->userModel = $storage->getModel(User::class);
        $this->cityModel = $storage->getModel(City::class);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $sellers = [];
        {
            /** @var array<Seller> */
            $tmp = $this->sellerModel->all();
            foreach ($tmp as $key => $value) {
                $name = $this->userModel->get($value->userId)->getFullName();
                $sellers[$name] = $value->id;
            }
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
            ->add('sellerId', ChoiceType::class, [
                'label' => 'Seller',
                'choices' => $sellers,
            ])
            ->add('tag', TextType::class, [
                'label' => 'input.tag'
            ])
            ->add('name', TextType::class, [
                'label' => 'input.stand_name'
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
