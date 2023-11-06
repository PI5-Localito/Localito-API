<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CityForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('countryCode', TextType::class, [
            'required' => 'true',
            'label' => 'input.country_code'
        ])
        ->add('cityName', TextType::class, [
            'required' => 'true',
            'label' => 'input.name'
        ])
        ->add('longitude', NumberType::class, [
            'required' => 'true',
            'label' => 'input.longitude'
        ])
        ->add('latitude', NumberType::class, [
            'required' => 'true',
            'label' => 'input.latitude'
        ])
        ->add('submit', SubmitType::class);
    }
}
