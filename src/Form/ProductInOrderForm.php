<?php

namespace App\Form;

use App\Model\Orders;
use App\Model\Products;
use App\Service\MysqlStorage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductInOrderForm extends AbstractType
{
	public function __construct(protected MysqlStorage $storage){

	}

	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$productModel = $this->storage->getModel(Products::class);
		$products = $productModel->all();
		$ordersModel = $this->storage->getModel(Orders::class);
		$orders = $ordersModel->all();
		$builder->add('submit', SubmitType::class)
				->add('orderId', ChoiceType::class,[
					'choices' => $orders,
					'choice_label' => "Orden #" . fn ($o) => $o?->getId(),
					'choice_value' => fn ($o) => $o?->getId()
				])
				->add('productId', ChoiceType::class,[
					'choices' => $products,
					'choice_label' => fn ($p) => $p?->getName(),
					'choice_value' => fn ($p) => $p?->getId()
				])
				->add('productQuantity', IntegerType::class);
	}
}