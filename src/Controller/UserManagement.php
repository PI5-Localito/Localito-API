<?php

namespace App\Controller;

use App\Entity\Seller;
use App\Entity\User;
use App\Form\UserForm;
use App\Model\SellerRepo;
use App\Model\UserRepo;
use App\Service\MysqlStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class UserManagement extends AbstractController
{
    protected UserRepo $model;
    protected SellerRepo $sellerModel;

    public function __construct(MysqlStorage $storage)
    {
        $this->model = $storage->getModel(User::class);
        $this->sellerModel = $storage->getModel(Seller::class);
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
        if(!$request->getSession()->has('login')) {
            return $this->redirectToRoute('login');
        }
        $page = $request->query->get('page', 1);

        $entities = $this->model->all(limit: 10, page: $page - 1);
        return $this->render('users.html.twig', [
            'users' => $entities,
            'page' => $page,
        ]);
    }

    #[Route('/user/new', methods: [ 'GET', 'POST' ])]
    public function create(Request $request): Response
    {
        if(!$request->getSession()->has('login')) {
            return $this->redirectToRoute('login');
        }
        $user = new User();
        $form = $this->createForm(UserForm::class, $user, ['new' => true]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!empty($user->avatar)) {
                $new_file = $user->avatar->move('avatars', uniqid() . ".{$user->avatar->guessExtension()}");
                $user->avatar = $new_file;
            }
            $user->setPasswordHash($user->password);
            $this->model->save($user);
            return $this->redirectToRoute('users');
        }

        return $this->render('user_edit.html.twig', [ 'user' => $user, 'form' => $form, 'new' => true ]);
    }

    #[Route('/user/{id}/edit', methods: [ 'GET', 'POST' ])]
    public function edit(Request $request, int $id): Response
    {
        if(!$request->getSession()->has('login')) {
            return $this->redirectToRoute('login');
        }
        $user = $this->ifEntity($id);
        $prevPass = $user->password;
        $currentPath = $user->getAvatarUri();
        $form = $this->createForm(UserForm::class, $user, ['new' => false]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User */
            $entity = $form->getData();
                //dd($entity);
            if (!empty($entity->avatar)) {
                $new_file = $entity->avatar->move('avatars', uniqid() . " . {$entity->avatar->guessExtension()}");
                $entity->avatar = $new_file;
            }else{
                $entity->setAvatarFromUri($currentPath);
            }
            if(empty($entity->password)){
                $entity->setPassword($prevPass);
            }
            $entity->setPasswordHash($entity->password);
            $this->model->update($entity);
            return $this->redirectToRoute('users');
        }

        return $this->render('user_edit.html.twig', [ 'user' => $user, 'form' => $form, 'new' => false  ]);
    }

    #[Route('/user/{id}/delete', methods: 'GET')]
    public function delete(Request $request, int $id): Response
    {
        if(!$request->getSession()->has('login')) {
            return $this->redirectToRoute('login');
        }
        $user = $this->ifEntity($id);
        if ($request->query->has('confirmation')) {
            $this->model->delete($user);
            return $this->redirectToRoute('users');
        }
        return $this->render('user_delete.html.twig', ['user' => $user]);
    }

    #[Route('/user/{id}', methods: 'GET')]
    public function details(Request $request, int $id): Response
    {
        if(!$request->getSession()->has('login')) {
            return $this->redirectToRoute('login');
        }
        $seller = $this->sellerModel->getByUser($id);
        $entity = $this->ifEntity($id);
        return $this->render('user.html.twig', ['user' => $entity, 'seller' => $seller]);
    }

    #[Route('/user/{id}/makeseller', methods: 'GET')]
    public function makeSeller(Request $request, int $id): Response
    {
        if(!$request->getSession()->has('login')) {
            return $this->redirectToRoute('login');
        }
        $user = $this->ifEntity($id);
        if ($request->query->has('confirmation')) {
            $seller = new Seller();
            $seller->userId = $id;
            $this->sellerModel->save($seller);
            //dd($this->sellerModel->getErrors());
            return $this->redirectToRoute('users');
        }
        return $this->render('seller_create.html.twig', ['user' => $user]);
    }
}
