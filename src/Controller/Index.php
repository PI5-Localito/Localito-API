<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\LoginForm;
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

    public function __construct(MysqlStorage $storage)
    {
        $this->model = $storage->getModel(Admin::class);
    }

    #[Route(name: 'home', path: '/', methods: 'GET')]
    public function home(Request $r): Response
    {
        if($r->getSession()->has('login')){
            return $this->render('home.html.twig');
        }
        return $this->redirectToRoute('login');
    }

    #[Route(name: 'login', path: '/login', methods: ['GET', 'POST'])]
    public function login(Request $r): Response
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
                //dd($admin);
                if($verify){
                    $sess->set('login', $admin->id);
                    //dd($session);
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

    #[Route(name: 'logout', path: '/logout', methods: 'GET')]
    public function logout(Request $r): Response
    {
        $sess = $r->getSession()->remove('login');
        return $this->redirectToRoute('login');
    }
}
