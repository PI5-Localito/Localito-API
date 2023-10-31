<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserForm;
use App\Model\Users;
use App\Service\MysqlStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class UserManagement extends AbstractController
{
    #[Route('/user/new', methods: [ 'GET', 'POST' ])]
    public function create(Request $request, MysqlStorage $storage): Response
    {
        $user = new User();
        $form = $this->createForm(UserForm::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $model = $storage->getModel(Users::class);
            $model->save($form->getData());
            return $this->redirect('/users');
        }

        return $this->render('edit_user.html.twig', [ 'form' => $form ]);
    }

    #[Route('/user/{id}/edit', methods: [ 'GET', 'POST' ])]
    public function edit(Request $request, int $id, MysqlStorage $storage): Response
    {
        $model = $storage->getModel(Users::class);
        $user = $model->get($id);
        if ($user === null) {
            throw new NotFoundHttpException();
        }
        $form = $this->createForm(UserForm::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $model->update($form->getData());
            return $this->redirect('/users');
        }

        return $this->render('edit_user.html.twig', [ 'form' => $form ]);
    }

    #[Route('/users', methods: 'GET')]
    public function usersView(MysqlStorage $storage): Response
    {
        $model = $storage->getModel(Users::class);
        $entities = $model->all();
        return $this->render('users.html.twig', [
            'users' => $entities,
        ]);
    }
}
