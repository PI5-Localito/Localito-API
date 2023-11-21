<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Product;
use App\Entity\Seller;
use App\Entity\Stand;
use App\Entity\User;
use App\Form\ProductForm;
use App\Form\StandForm;
use App\Model\CityRepo;
use App\Model\ProductRepo;
use App\Model\SellerRepo;
use App\Model\StandRepo;
use App\Model\UserRepo;
use App\Service\MysqlStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class StandManagement extends AbstractController
{
    protected StandRepo $model;
    protected ProductRepo $productModel;
    protected SellerRepo $sellerModel;
    protected UserRepo $userModel;
    protected CityRepo $cityModel;

    public function __construct(MysqlStorage $storage)
    {
        $this->model = $storage->getModel(Stand::class);
        $this->productModel = $storage->getModel(Product::class);
        $this->sellerModel = $storage->getModel(Seller::class);
        $this->userModel = $storage->getModel(User::class);
        $this->cityModel = $storage->getModel(City::class);
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
        if(!$request->getSession()->has('login')) {
            return $this->redirectToRoute('login');
        }
        $page = $request->query->get('page', 1);

        $users = $this->userModel->all();
        $cities = $this->cityModel->all();
        $sellers = $this->sellerModel->all();
        $entities = $this->model->all(limit: 10, page: $page - 1);
        return $this->render('stands.html.twig', [
            'stands' => $entities,
            'page' => $page,
            'cities' => $cities,
            'users' => $users,
            'sellers' => $sellers,
        ]);
    }

    #[Route('/stand/new', methods: [ 'GET', 'POST' ])]
    public function create(Request $request): Response
    {
        if(!$request->getSession()->has('login')) {
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
        if(!$request->getSession()->has('login')) {
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
        if(!$request->getSession()->has('login')) {
            return $this->redirectToRoute('login');
        }
        $stand = $this->ifEntity($id);
        if ($request->query->has('confirmation')) {
            $this->model->delete($stand);
            return $this->redirectToRoute('stands');
        }
        return $this->render('stand_delete.html.twig', ['stand' => $stand]);
    }

    #[Route(name: 'stand', path: '/stand/{id}', methods: 'GET')]
    public function details(Request $request, int $id): Response
    {
        if(!$request->getSession()->has('login')) {
            return $this->redirectToRoute('login');
        }
        $entity = $this->ifEntity($id);
        $seller = $this->sellerModel->get($entity->sellerId);
        $sellerName = $this->userModel->get($seller->userId)->getFullName();
        $city = $this->cityModel->get($entity->city);
        $products = $this->productModel->allByStand($id);
        return $this->render('stand.html.twig', ['stand' => $entity, 'products' => $products, 'seller' => $sellerName, 'city' => $city]);
    }

    #[Route('/stand/{id}/newproduct', methods: [ 'GET', 'POST' ])]
    public function newProduct(Request $request, int $id): Response
    {
        if(!$request->getSession()->has('login')) {
            return $this->redirectToRoute('login');
        }

        $product = new Product();
        $entity = $this->ifEntity($id);
        $form = $this->createForm(ProductForm::class, $product, ['sid' => $id]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->productModel->save($product);
            return $this->redirectToRoute('stand', ['id' => $id]);
        }

        return $this->render('product_edit.html.twig', [ 'product' => $product, 'form' => $form ]);
    }

    #[Route('/stand/{id}/product/{pid}', methods: 'GET')]
    public function product(Request $request, int $id, int $pid): Response
    {
        if(!$request->getSession()->has('login')) {
            return $this->redirectToRoute('login');
        }
        $product = $this->productModel->get($pid);
        $stand = $this->ifEntity($id);
        return $this->render('product.html.twig', ['stand' => $stand, 'product' => $product]);
    }

    #[Route('/stand/{id}/product/{pid}/edit', methods: [ 'GET', 'POST' ])]
    public function editPredirectToRoute(Request $request, int $id, int $pid): Response
    {
        if(!$request->getSession()->has('login')) {
            return $this->redirectToRoute('login');
        }
        $product = $this->productModel->get($pid);
        $stand = $this->ifEntity($id);
        $seller = $this->sellerModel->get($stand->sellerId);
        $form = $this->createForm(ProductForm::class, $product, ['sid' => $seller->id]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Product */
            $product = $form->getData();
            $this->productModel->update($product);
            return $this->redirectToRoute('stand', ['id' => $id]);
        }

        return $this->render('product_edit.html.twig', [ 'product' => $product, 'form' => $form ]);
    }

    #[Route(name: 'deleteproduct', path: '/stand/{id}/product/{pid}/delete', methods: 'GET')]
    public function deleteProduct(Request $request, int $id, int $pid): Response
    {
        if(!$request->getSession()->has('login')) {
            return $this->redirectToRoute('login');
        }
        $product = $this->productModel->get($pid);
        $stand = $this->ifEntity($id);
        if ($request->query->has('confirmation')) {
            $this->productModel->delete($product);
            return $this->redirect('/stand/'.$id);
        }
        return $this->render('product_delete.html.twig', ['stand' => $stand, 'product' => $product]);
    }
}
