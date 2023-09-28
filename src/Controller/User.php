<?php

namespace App\Controller;

use App\Service\Mariadb;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class User extends AbstractController {

	#[Route('/user', methods: 'GET')]
	public function get(Mariadb $db): Response {
		return new Response();
	}
}