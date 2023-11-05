<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FileAPI
{
    #[Route(path: '/file/upload', methods: 'PUT')]
    public function newFile(): Response
    {
        return new JsonResponse([]);
    }
}
