<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserForm;
use App\Model\Users;
use App\Service\MysqlStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserManagement extends AbstractController
{
    #[Route('/api/users', methods: 'GET')]
    public function get(MysqlStorage $storage): Response
    {
        $model = $storage->getModel(Users::class);
        $entities = $model->all();
        dd($entities);
    }

    #[Route('/user/new', methods: [ 'GET', 'POST' ])]
    public function create(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserForm::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dd("Negros");
        }

        return $this->render('edit_user.html.twig', [ 'form' => $form ]);
    }
}
