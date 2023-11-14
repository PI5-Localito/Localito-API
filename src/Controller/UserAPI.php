<?php

namespace App\Controller;

use App\Entity\Buyer;
use App\Entity\Seller;
use App\Entity\User;
use App\Model\BuyerRepo;
use App\Model\SellerRepo;
use App\Model\UserRepo;
use App\Service\MysqlStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserAPI extends AbstractController
{
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

    protected function processErrors(ConstraintViolationListInterface $list): false|Response
    {
        if ($list->count() == 0) {
            return false;
        }

        $violations = [];
        foreach($list as $violation) {
            $violations[] = [
                'message' => $violation->getMessage(),
                'cause' => $violation->getPropertyPath(),
            ];
        }

        return new JsonResponse($violations, status: 400);
    }

    #[Route(path: '/api/user/create', methods: ['POST'])]
    public function register(Request $request): Response
    {
        $data = $request->getPayload();
        $user = new User();
        $user->hydrate($data->all());
        $violations = $this->validator->validate($user);
        $type = $data->getInt('type', 0);

        if ($badreq = $this->processErrors($violations)) {
            return $badreq;
        }

        $user->setPassword($user->password);
        if ($user->avatar && !$user->avatar->isFile()) {
            return new JsonResponse([
                ['message' => 'File not found', 'cause' => 'avatar']
            ], status: 400);
        }

        if (!$this->userModel->save($user)) {
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

    public function createSeller(Request $request): Response
    {
        $data = $request->getPayload();

    }
}
