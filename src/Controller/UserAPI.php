<?php

namespace App\Controller;

use App\Model\Users;
use App\Service\MysqlStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserAPI extends AbstractController
{
    #[Route('/users', methods: 'GET')]
    public function get(MysqlStorage $storage): Response
    {
        $model = $storage->getModel(Users::class);
        $entities = $model->all();
        dd($entities);
    }

    public function create()
    {

    }
}
