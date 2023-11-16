<?php

namespace App\Controller;

use App\Model\UserRepo;
use App\Service\MysqlStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthAPI extends AbstractController
{
    protected UserRepo $repo;

    public function __construct(
        protected MysqlStorage $storage,
        protected ValidatorInterface $validator
    ) {
    }

    #[Route(path: '/api/token', methods: 'GET')]
    public function token(Request $request): Response
    {
        $data = $request->getPayload();

    }
}
