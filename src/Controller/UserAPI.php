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
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserAPI extends AbstractController
{
    protected UserRepo $model;

    public function __construct(
        MysqlStorage $storage,
        protected ValidatorInterface $validator
    ) {
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

    #[Route(path: '/api/user/{id}', methods: 'GET', requirements: ['id' => '\d+'])]
    public function user(int $id): Response
    {
        $entity = $this->ifEntity($id);
        return new JsonResponse($entity);
    }

    #[Route(path: '/api/user/{id}', methods: 'PUT', requirements: ['id' => '\d+'])]
    public function editUser(Request $request, int $id): Response
    {
        $entity = $this->ifEntity($id);
        return new JsonResponse($entity);
    }

    #[Route(path: '/api/user/{id}', methods: 'DELETE', requirements: ['id' => '\d+'])]
    public function deleteUser(int $id): Response
    {
        $entity = $this->ifEntity($id);
        $this->model->delete($entity);
        return new JsonResponse($entity);
    }

    #[Route(path: '/api/user', methods: 'POST')]
    public function create(Request $request): Response
    {
        $user = new User();
        $data = $request->getPayload()->all();
        $user->hydrate($data);
        $validations = $this->validator->validate($user);
        if ($validations->count() > 0) {
            $last_violation = $validations->get(0);
            return new JsonResponse(
                data: [
                    'errors' => $validations->count(),
                    'messages' => $last_violation->getMessage(),
                    'parameter' => $last_violation->getPropertyPath(),
                ],
                status: 400,
            );
        }

        $this->model->save($user);
        return new JsonResponse($user);
    }
}
