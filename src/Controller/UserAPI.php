<?php

namespace App\Controller;

use App\Entity\Buyer;
use App\Entity\Seller;
use App\Entity\User;
use App\Model\BuyerRepo;
use App\Model\SellerRepo;
use App\Model\UserRepo;
use App\Service\Authorization;
use App\Service\JWT;
use App\Service\MysqlStorage;
use App\Trait\EntityViolations;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserAPI extends AbstractController
{
    use EntityViolations;
    protected UserRepo $userModel;
    protected BuyerRepo $buyerModel;
    protected SellerRepo $sellerModel;

    public function __construct(
        MysqlStorage $storage,
        protected ValidatorInterface $validator,
        protected Filesystem $filesystem,
    ) {
        $this->userModel = $storage->getModel(User::class);
        $this->buyerModel = $storage->getModel(Buyer::class);
        $this->sellerModel = $storage->getModel(Seller::class);
    }

    #[Route(path: '/api/user/create', methods: ['POST'])]
    public function register(Request $request): Response
    {
        $data = $request->getPayload();
        $user = new User();
        $user->hydrate($data->all());
        $violations = $this->validator->validate($user);
        $this->processErrors($violations);

        $type = $data->getInt('type', 0);
        $user->setPasswordHash($user->password);
        if ($user->avatar && !$user->avatar->isFile()) {
            return new JsonResponse([
                ['message' => 'File not found', 'cause' => 'avatar']
            ], status: 400);
        }

        if (!$this->userModel->save($user)) {
            $errors = $this->userModel->getErrors();
            if ($errors[23000]) {
                return new JsonResponse([
                    ['message' => 'Duplicated entry', 'cause' => 'user']
                ], status: 500);
            }
            return new JsonResponse([
                ['message' => 'Oops! something failed', 'cause' => 'user']
            ], status: 500);
        }

        $buyer = new Buyer();
        $buyer->userId = $user->id;
        if (!$this->buyerModel->save($buyer)) {
            return new JsonResponse([
                ['message' => 'Oops! something failed', 'cause' => 'buyer']
            ], status: 500);
        }

        return new JsonResponse([
            'user' => $user,
            'buyer' => $buyer
        ]);
    }

    #[Route(path: '/api/user/{id}', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function user(int $id): Response
    {
        $user = $this->userModel->get($id);
        if (!$user) {
            throw new JsonResponse([
                ['message' => 'Entity not found', 'cause' => 'id']
            ], status: 404);
        }

        return new JsonResponse($user);
    }

    #[Route(path: '/api/user/{id}/{type}', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function userWith(int $id, string $type): Response
    {
        $user = $this->userModel->get($id);
        if (!$user) {
            return new JsonResponse([
                ['message' => 'Entity not found', 'cause' => 'id']
            ], status: 404);
        }

        $role = match($type) {
            default => $this->buyerModel->getByUser($user->id),
            'buyer' => $this->buyerModel->getByUser($user->id),
            'seller' => $this->sellerModel->getByUser($user->id),
        };

        if(!$role) {
            return new JsonResponse([
                ['message' => 'Entity was not found', 'cause' => 'role']
            ], status: 404);
        }

        return new JsonResponse([ 'entity' => $user, 'role' => $role ]);
    }

    #[Route(path: '/api/make-seller', methods: 'PUT')]
    public function createSeller(Authorization $authorization, JWT $jwt): Response
    {
        $data = $authorization->getSession();
        $user = $this->userModel->get($data['id']);
        $seller = new Seller();
        $seller->userId = $user->id;
        $this->sellerModel->save($seller);
        if (!$user) {
            return new JsonResponse([
                ['message' => 'Entity not found', 'cause' => 'id']
            ], status: 404);
        }
        return new JsonResponse([ 'user' => $user, 'seller' => $seller]);
    }
}
