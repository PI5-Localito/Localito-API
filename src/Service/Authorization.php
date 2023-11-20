<?php

namespace App\Service;

use App\Entity\User;
use App\Model\UserRepo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Authorization
{
    protected Request $request;
    protected UserRepo $userRepo;

    public function __construct(
        RequestStack $requestStack,
        protected JWT $tokenizer,
        MysqlStorage $storage,
    ) {
        $this->request = $requestStack->getCurrentRequest();
        $this->userRepo = $storage->getModel(User::class);
    }

    public function getToken(): string
    {
        $auth = $this->request->headers->get('Authorization');
        $token = substr($auth, 7);
        return $token;
    }

    public function getSession(): array
    {
        $token = $this->getToken();
        $access = $this->tokenizer->decode($token);
        if ($access === false) {
            throw new UnauthorizedHttpException('Permission denied');
        }
        $user = $this->userRepo->get($access['id'] ?? 0);
        if ($user === null) {
            throw new UnauthorizedHttpException('Permission denied');
        }
        return $user;
    }
}
