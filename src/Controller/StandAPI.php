<?php

namespace App\Controller;

use App\Entity\Stand;
use App\Model\StandRepo;
use App\Model\UserRepo;
use App\Service\Authorization;
use App\Service\JWT;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;

class StandAPI
{
    public function __construct(
        protected StandRepo $standRepo,
    ) {
    }

    #[Route(path: '/api/stand/create', methods: ['PUT'])]
    public function createStand(Request $request, Authorization $authorization)
    {
        $data = $request->getPayload()->all();
        $user = $authorization->getSession();
        $stand = new Stand();
        $stand->hydrate($data);
    }
}
