<?php

namespace App\Controller;

use App\ApiControllerBase;
use Exception;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FileAPI extends ApiControllerBase
{
    public function __construct(
        protected Filesystem $filesystem,
        protected ValidatorInterface $validator
    ) {
    }

    #[Route(path: '/api/file/upload', methods: 'POST')]
    public function newFile(Request $request): Response
    {
        /** @var UploadedFile */
        $file = $avatar = $request->files->get('picture');

        if (!$file) {
            throw new BadRequestHttpException('Please upload a file');
        }

        if (!$file->isValid()) {
            if ($file->isFile()) {
                $this->filesystem->remove($file);
            }
            throw new BadRequestHttpException($file->getErrorMessage());
        }

        $uid = uniqid();
        $uniqname = "$uid.{$file->guessExtension()}";
        try {
            $moved = $file->move('avatars', $uniqname);
            if (!$moved->isFile()) {
                $this->filesystem->remove($moved);
                throw new Exception('Server filed to process file');
            }
        } catch(FileException $exception) {
            return new Response(status: 500, content: 'Filed to process file');
        }

        return new Response(content: $moved->getPathname());
    }
}
