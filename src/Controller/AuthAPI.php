<?php

namespace App\Controller;

use App\Entity\User;
use App\Model\UserRepo;
use App\Service\JWT;
use App\Service\MysqlStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthAPI extends AbstractController
{
    protected UserRepo $userModel;

    public function __construct(
        protected MysqlStorage $storage,
        protected ValidatorInterface $validator,
        protected JWT $tokenizer,
    ) {
        $this->userModel = $storage->getModel(User::class);
    }

    #[Route(path: '/api/token', methods: 'GET')]
    public function token(Request $request): Response
    {
        $data = $request->getPayload();
        if (!$data->has('email') || !$data->has('password')) {
            throw new BadRequestHttpException();
        }

        $user = $this->userModel->getByEmail($data->getString('email'));
        if (!password_verify($data->getString('password'), $user->password)) {
            throw new BadRequestHttpException();
        }

        $jwt = $this->tokenizer->encode($user);

        return new Response(
            content: $jwt,
            status: 200,
            headers: ['Content-Type' => 'text']
        );
    }
}
