<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Index extends AbstractController
{
    #[Route('/', methods: 'GET')]
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }

    #[Route('/home', methods: 'POST')]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }


    #[Route('/usuario', methods: 'GET')]
    public function view_user(): Response
    {
        return $this->render('view_user.html.twig');
    }

    #[Route('/editarusuario', methods: 'GET')]
    public function edit_user(): Response
    {
        return $this->render('edit_user.html.twig');
    }

    #[Route('/stands', methods: 'GET')]
    public function stands(): Response
    {
        return $this->render('stands.html.twig');
    }

    #[Route('/stand', methods: 'GET')]
    public function view_stand(): Response
    {
        return $this->render('view_stand.html.twig');
    }

    #[Route('/editarstand', methods: 'GET')]
    public function edit_stand(): Response
    {
        return $this->render('edit_stand.html.twig');
    }
}

