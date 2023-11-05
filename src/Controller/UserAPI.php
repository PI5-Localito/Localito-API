<?php

namespace App\Controller;

use App\Entity\User;
use App\Model\UserRepo;
use App\Service\MysqlStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class UserAPI extends AbstractController
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

    #[Route(path: '/api/users', methods: 'GET')]
    public function list(Request $request): Response
    {
        $page = $request->query->get('page', 1);

        $entities = $this->model->all(limit: 10, page: $page - 1);
        return new JsonResponse($entities);
    }
}
