<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\CityForm;
use App\Model\CityRepo;
use App\Service\MysqlStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CitiesManagment extends AbstractController
{
    protected CityRepo $model;

    public function __construct(MysqlStorage $storage)
    {
        $this->model = $storage->getModel(City::class);
    }

    protected function ifEntity(int $id): City
    {
        $city = $this->model->get($id);
        if ($city === null) {
            throw new NotFoundHttpException();
        }
        return $city;
    }

    #[Route(name: 'cities', path: '/cities', methods: 'GET')]
    public function list(Request $request): Response
    {
        if($request->getSession()->get('rol') != 'admin'){
            return $this->redirectToRoute('logout');
        }
        if(!$request->getSession()->has('login')){
            return $this->redirectToRoute('select');
        }
        $page = $request->query->get('page', 1);

        $entities = $this->model->all(limit: 10, page: $page - 1);
        return $this->render('cities.html.twig', [
            'cities' => $entities,
            'page' => $page,
            'rol' => $request->getSession()->get('rol')
        ]);
    }

    #[Route('/city/new', methods: [ 'GET', 'POST' ])]
    public function create(Request $request): Response
    {
        if($request->getSession()->get('rol') != 'admin'){
            return $this->redirectToRoute('logout');
        }
        if(!$request->getSession()->has('login')){
            return $this->redirectToRoute('select');
        }
        $city = new City();
        $form = $this->createForm(CityForm::class, $city);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->model->save($city);
            return $this->redirectToRoute('cities');
        }

        return $this->render('city_edit.html.twig', [ 'city' => $city, 'form' => $form,'rol' => $request->getSession()->get('rol') ]);
    }

    #[Route('/city/{id}/edit', methods: [ 'GET', 'POST' ])]
    public function edit(Request $request, int $id): Response
    {
        if($request->getSession()->get('rol') != 'admin'){
            return $this->redirectToRoute('logout');
        }
        if(!$request->getSession()->has('login')){
            return $this->redirectToRoute('select');
        }
        $city = $this->ifEntity($id);
        $form = $this->createForm(CityForm::class, $city);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var City */
            $entity = $form->getData();
            $this->model->update($entity);
            return $this->redirectToRoute('cities');
        }

        return $this->render('city_edit.html.twig', [ 'city' => $city, 'form' => $form,'rol' => $request->getSession()->get('rol') ]);
    }

    #[Route('/city/{id}/delete', methods: 'GET')]
    public function delete(Request $request, int $id): Response
    {
        if($request->getSession()->get('rol') != 'admin'){
            return $this->redirectToRoute('logout');
        }
        if(!$request->getSession()->has('login')){
            return $this->redirectToRoute('select');
        }
        $city = $this->ifEntity($id);
        if ($request->query->has('confirmation')) {
            $this->model->delete($city);
            return $this->redirectToRoute('cities');
        }
        return $this->render('city_delete.html.twig', ['city' => $city, 'rol' => $request->getSession()->get('rol')]);
    }

    #[Route('/city/{id}', methods: 'GET')]
    public function details(Request $request, int $id): Response
    {
        if($request->getSession()->get('rol') != 'admin'){
            return $this->redirectToRoute('logout');
        }
        if(!$request->getSession()->has('login')){
            return $this->redirectToRoute('select');
        }
        $entity = $this->ifEntity($id);
        return $this->render('city.html.twig', ['city' => $entity, 'rol' => $request->getSession()->get('rol')]);
    }
}
