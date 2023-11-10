<?php

namespace App\Controller;

use App\Entity\Stand;
use App\Form\StandForm;
use App\Model\StandRepo;
use App\Service\MysqlStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class StandManagement extends AbstractController
{
    protected StandRepo $model;

    public function __construct(MysqlStorage $storage)
    {
        $this->model = $storage->getModel(Stand::class);
    }

    protected function ifEntity(int $id): Stand
    {
        $stand = $this->model->get($id);
        if ($stand === null) {
            throw new NotFoundHttpException();
        }
        return $stand;
    }

    #[Route(name: 'stands', path: '/stands', methods: 'GET')]
    public function list(Request $request): Response
    {
        if(!$request->getSession()->has('login')){
            return $this->redirectToRoute('login');
        }
        $page = $request->query->get('page', 1);

        $entities = $this->model->all(limit: 10, page: $page - 1);
        return $this->render('stands.html.twig', [
            'stands' => $entities,
            'page' => $page,
        ]);
    }

    #[Route('/stand/new', methods: [ 'GET', 'POST' ])]
    public function create(Request $request): Response
    {
        if(!$request->getSession()->has('login')){
            return $this->redirectToRoute('login');
        }
        $stand = new Stand();
        $form = $this->createForm(StandForm::class, $stand);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->model->save($stand);
            return $this->redirectToRoute('stands');
        }

        return $this->render('stand_edit.html.twig', [ 'stand' => $stand, 'form' => $form ]);
    }

    #[Route('/stand/{id}/edit', methods: [ 'GET', 'POST' ])]
    public function edit(Request $request, int $id): Response
    {
        if(!$request->getSession()->has('login')){
            return $this->redirectToRoute('login');
        }
        $stand = $this->ifEntity($id);
        $form = $this->createForm(StandForm::class, $stand);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Stand */
            $entity = $form->getData();
            $this->model->update($entity);
            return $this->redirectToRoute('stands');
        }

        return $this->render('stand_edit.html.twig', [ 'stand' => $stand, 'form' => $form ]);
    }

    #[Route('/stand/{id}/delete', methods: 'GET')]
    public function delete(Request $request, int $id): Response
    {
        if(!$request->getSession()->has('login')){
            return $this->redirectToRoute('login');
        }
        $stand = $this->ifEntity($id);
        if ($request->query->has('confirmation')) {
            $this->model->delete($stand);
            return $this->redirectToRoute('stands');
        }
        return $this->render('stand_delete.html.twig', ['stand' => $stand]);
    }

    #[Route('/stand/{id}', methods: 'GET')]
    public function details(Request $request, int $id): Response
    {
        if(!$request->getSession()->has('login')){
            return $this->redirectToRoute('login');
        }
        $entity = $this->ifEntity($id);
        return $this->render('stand.html.twig', ['stand' => $entity]);
    }
}
