<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class Index extends AbstractController {
	#[Route('/', methods: 'GET')]
	public function index(): Response {
		return $this->render('index.html.twig');
	}

	#[Route('/home', methods: 'POST')]
	public function home(): Response {
		return $this->render('home.html.twig');
	}

	#[Route('/usuarios', methods: 'GET')]
	public function users(): Response {
		return $this->render('users.html.twig');
	}
}