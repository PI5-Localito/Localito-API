<?php

namespace App\Form;

use App\Model\Users;
use App\Model\Buyers;
use App\Model\Sellers;
use App\Model\Stands;
use App\Service\MysqlStorage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderForm extends AbstractType
{
	public function __construct(protected MysqlStorage $storage){

	}

	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$usersModel = $this->storage->getModel(Users::class);
		$users = $usersModel->all();
		$buyersModel = $this->storage->getModel(Buyers::class);
		$buyers = $buyersModel->all();
		$sellersModel = $this->storage->getModel(Sellers::class);
		$sellers = $sellersModel->all();
		$standsModel = $this->storage->getModel(Stands::class);
		$stands = $standsModel->all();
		$builder->add('submit', SubmitType::class)
				->add('buyerId', ChoiceType::class,[
					'choices' => $buyers,
					'choice_label' => fn ($b) => $usersModel->get($b?->getId())->getFullName(), //Esta madre jala en mi cabeza, pero aparentemente aquí no va a jalar
					'choice_value' => fn ($b) => $b?->getId(),
				])
				->add('sellerId', ChoiceType::class,[
					'choices' => $sellers,
					'choice_label' => fn ($s) => $usersModel->get($s?->getId())->getFullName(), //Esta madre jala en mi cabeza, pero aparentemente aquí no va a jalar
					'choice_value' => fn ($s) => $s?->getId(),
				])
				->add('standId', ChoiceType::class,[
					'choices' => $stands,
					'choice_label' => fn ($st) => $st?->getName(),
					'choice_value' => fn ($st) => $st->getId(),
				])
				->add("date", DateType::class,[
					'widget' => 'choice',
					'placeholder' => 'Selecciona una fecha'
				])
				->add('state', ChoiceType::class,[
					'choices' => [
						'Pendiente' => 'PENDING',
						'Rechazada' => 'REJECTED',
						'Aceptada' => 'ACCEPTED',
						'Finalizada' => 'FINISHED'
					],
					'multiple'=>false,
					'expanded'=>true
				]);
	}
}