<?php

namespace App\Controller;

use App\Service\MysqlStorage;
use App\Entity\User;
use App\Model\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserApi extends AbstractController
{

    protected Users $model;

    public function __construct(MysqlStorage $storage)
    {
        $this->model = $storage->getModel(Users::class);
    }

    protected function ifEntity(int $id): ?User
    {
        $user = $this->model->get($id);
        if ($user === null) {
            return null;
        }
        return $user;
    }

    #[Route(name: 'usersList', path: '/api/users', methods:'GET')]
    public function usersList(Request $r): Response
    {
        $entidad = [];
        $page = $r->query->get('page',1);

        $entities = $this->model->all(limit:20, page: $page - 1);
        /** @var User */
        foreach($entities as $e){
            $entidad[] = [
                'id' => $e->getId(),
                'name' => $e->getName(),
                'lastName' => $e->getLastName()
            ];
        }
        return new JsonResponse($entidad);
    }

    #[Route(name: 'userEdit', path:'/api/user/{id}/edit', methods:['GET', 'POST'])]
    public function editUser(Request $r, int $id): Response
    {
        $user = $this->ifEntity($id);
        if($this->model->update($user)){
            $response = [
                'response' => 'ok'
            ];
        }else{
            $response = [
                'response' => 'fail'
            ];
        }

        return new JsonResponse($response);
    }

    #[Route(name: 'userDelete', path:'/api/user/{id}/delete', methods: ['GET'])]
    public function deleteUser(Request $r, int $id): Response
    {
        $user = $this->ifEntity($id);
    }
}
