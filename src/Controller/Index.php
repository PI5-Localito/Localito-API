<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Seller;
use App\Entity\User;
use App\Form\LoginForm;
use App\Model\SellerRepo;
use App\Model\UserRepo;
use App\Service\MysqlStorage;
use App\Model\AdminRepo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Index extends AbstractController
{
    protected AdminRepo $model;
    protected UserRepo $userModel;
    protected SellerRepo $sellerModel;

    public function __construct(MysqlStorage $storage)
    {
        $this->model = $storage->getModel(Admin::class);
        $this->userModel = $storage->getModel(User::class);
        $this->sellerModel = $storage->getModel(Seller::class);
    }

    #[Route(name: 'home', path: '/', methods: 'GET')]
    public function home(Request $r): Response
    {
        if($r->getSession()->get('rol') == 'seller'){
            return $this->redirectToRoute('stands');
        }
        if($r->getSession()->has('login')){
            return $this->render('home.html.twig', ['rol' => $r->getSession()->get('rol')]);
        }
        return $this->redirectToRoute('select');
    }

    #[Route(name: 'select', path: '/select', methods: 'GET')]
    public function selectLogin(Request $r): Response
    {
        return $this->render('select.html.twig', ['login' => true]);
    }

    #[Route(name: 'loginAdmin', path: '/loginadmin', methods: ['GET', 'POST'])]
    public function loginAdmin(Request $r): Response
    {
        $sess = $r->getSession();
        if($sess->has('login'))
            return $this->redirectToRoute('home');
        $form = $this->createForm(LoginForm::class);
        $form->handleRequest($r);
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $password = $form->get('password')->getData();
            $admin = $this->model->getByEmail($email);
            if(!$admin){
                $form->addError(new FormError('Credenciales incorrectas :)'));
            }else{
                $dbPass = $admin->password;
                $verify = password_verify($password, $dbPass);
                if($verify){
                    $sess->set('login', $admin->id);
                    $sess->set('rol', 'admin');
                    return $this->redirectToRoute('home');
                }
                $form->addError(new FormError('Credenciales incorrectas :)'));
            }
        }
        return $this->render('login.html.twig', [
            'form' => $form,
            'login' => true
        ]);
    }

    #[Route(name: 'loginSeller', path: '/loginseller', methods: ['GET', 'POST'])]
    public function loginSeller(Request $r): Response
    {
        $sess = $r->getSession();
        if($sess->has('login'))
            return $this->redirectToRoute('home');
        $form = $this->createForm(LoginForm::class);
        $form->handleRequest($r);
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $password = $form->get('password')->getData();
            $user = $this->userModel->getByEmail($email);
            if(!$user){
                $form->addError(new FormError('Credenciales incorrectas :)'));
            }else{
                $dbPass = $user->password;
                $verify = password_verify($password, $dbPass);
                if($verify){
                    $seller = $this->sellerModel->getByUser($user->id);
                    if(!$seller){
                        $form->addError(new FormError('Este usuario no es vendedor'));        
                    }else{
                        $sess->set('login', $seller->id);
                        $sess->set('rol', 'seller');
                        return $this->redirectToRoute('stands'); 
                    }
                }
                $form->addError(new FormError('Credenciales incorrectas :)'));
            }
        }
        return $this->render('login.html.twig', [
            'form' => $form,
            'login' => true
        ]);
    }

    #[Route(name: 'logout', path: '/logout', methods: 'GET')]
    public function logout(Request $r): Response
    {
        $sess = $r->getSession()->remove('login');
        return $this->redirectToRoute('select');
    }
}
