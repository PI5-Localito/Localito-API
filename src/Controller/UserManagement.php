<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserForm;
use App\Model\UserRepo;
use App\Service\MysqlStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class UserManagement extends AbstractController
{
    protected UserRepo $model;

    public function __construct(MysqlStorage $storage)
    {
        $this->model = $storage->getModel(User::class);
    }

    protected function ifEntity(int $id): User
    {
        $user = $this->model->get($id);
        if ($user === null) {
            throw new NotFoundHttpException();
        }
        return $user;
    }

    #[Route(name: 'users', path: '/users', methods: 'GET')]
    public function list(Request $request): Response
    {
        $page = $request->query->get('page', 1);

        $entities = $this->model->all(limit: 20, page: $page - 1);
        return $this->render('users.html.twig', [
            'users' => $entities,
            'page' => $page,
        ]);
    }

    #[Route('/user/{id}/edit', methods: [ 'GET', 'POST' ])]
    public function edit(Request $request, int $id): Response
    {
        $user = $this->ifEntity($id);
        $form = $this->createForm(UserForm::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->model->update($form->getData());
            return $this->redirectToRoute('users');
        }

        return $this->render('user_edit.html.twig', [ 'user' => $user, 'form' => $form ]);
    }

    #[Route('/user/{id}/delete', methods: [ 'GET' ])]
    public function delete(Request $request, int $id): Response
    {
        $user = $this->ifEntity($id);
        if ($request->query->has('confirmation')) {
            $this->model->delete($user);
            return $this->redirectToRoute('users');
        }
        return $this->render('user_delete.html.twig', ['user' => $user]);
    }

    #[Route('/user/new', methods: [ 'GET', 'POST' ])]
    public function create(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserForm::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User */
            $entity = $form->getData();
            /** @var UploadedFile */
            $file = $form->get('avatar')->getData();

            $file->move('public/avatars', uniqid() . $file->guessExtension());
            $entity->setAvatar($file->getPathname());
            $this->model->save($entity);
            return $this->redirectToRoute('users');
        }

        return $this->render('user_edit.html.twig', [ 'user' => $user, 'form' => $form ]);
    }
}
