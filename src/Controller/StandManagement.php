<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Message;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\ProductInOrder;
use App\Entity\Seller;
use App\Entity\Stand;
use App\Entity\User;
use App\Form\MessageForm;
use App\Form\ProductForm;
use App\Form\StandForm;
use App\Model\CityRepo;
use App\Model\MessageRepo;
use App\Model\OrderRepo;
use App\Model\ProductInOrderRepo;
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
    protected OrderRepo $orderModel;
    protected ProductInOrderRepo $pioModel;
    protected MessageRepo $messageModel;

    public function __construct(MysqlStorage $storage)
    {
        $this->model = $storage->getModel(Stand::class);
        $this->productModel = $storage->getModel(Product::class);
        $this->sellerModel = $storage->getModel(Seller::class);
        $this->userModel = $storage->getModel(User::class);
        $this->cityModel = $storage->getModel(City::class);
        $this->orderModel = $storage->getModel(Order::class);
        $this->pioModel = $storage->getModel(ProductInOrder::class);
        $this->messageModel = $storage->getModel(Message::class);
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
            return $this->redirectToRoute('select');
        }
        $page = $request->query->get('page', 1);

        $users = $this->userModel->all();
        $cities = $this->cityModel->all();
        $sellers = $this->sellerModel->all();

        $entities = $request->getSession()->get('rol') == 'admin' ?
            $this->model->all(limit: 10, page: $page - 1) :
            $this->model->getBySeller($request->getSession()->get('login'), limit: 10, page: $page - 1);

        return $this->render('stands.html.twig', [
            'stands' => $entities,
            'page' => $page,
            'cities' => $cities,
            'users' => $users,
            'sellers' => $sellers,
            'rol' => $request->getSession()->get('rol')
        ]);
    }

    #[Route('/stand/new', methods: [ 'GET', 'POST' ])]
    public function create(Request $request): Response
    {
        if(!$request->getSession()->has('login')) {
            return $this->redirectToRoute('select');
        }
        $rol = $request->getSession()->get('rol');
        $sessionId = $request->getSession()->get('login');
        $stand = new Stand();
        $form = $this->createForm(StandForm::class, $stand, ['rol' => $rol, 'sid' => $sessionId]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            if (!empty($stand->image)) {
                $new_file = $stand->image->move('stands', uniqid() . ".{$stand->image->guessExtension()}");
                $stand->image = $new_file;
            }
            $this->model->save($stand);
            return $this->redirectToRoute('stands');
        }

        return $this->render('stand_edit.html.twig', [ 
            'stand' => $stand, 
            'form' => $form, 
            'rol' => $request->getSession()->get('rol') ]);
    }

    #[Route('/stand/{id}/edit', methods: [ 'GET', 'POST' ])]
    public function edit(Request $request, int $id): Response
    {
        if(!$request->getSession()->has('login')) {
            return $this->redirectToRoute('select');
        }
        $rol = $request->getSession()->get('rol');
        $sessionId = $request->getSession()->get('login');
        $stand = $this->ifEntity($id);
        $currentPath = $stand->getImageUri();
        $form = $this->createForm(StandForm::class, $stand,['rol' => $rol, 'sid' => $sessionId]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Stand */
            $entity = $form->getData();
            if (!empty($entity->image)) {
                $new_file = $entity->image->move('stands', uniqid() . ".{$entity->image->guessExtension()}");
                $entity->image = $new_file;
            }else{
                $entity->setImageFromUri($currentPath);
            }
            $this->model->update($entity);
            return $this->redirectToRoute('stands');
        }

        return $this->render('stand_edit.html.twig', [ 
            'stand' => $stand, 
            'form' => $form,
            'rol' => $request->getSession()->get('rol') ]);
    }

    #[Route('/stand/{id}/delete', methods: 'GET')]
    public function delete(Request $request, int $id): Response
    {
        if(!$request->getSession()->has('login')) {
            return $this->redirectToRoute('select');
        }
        $stand = $this->ifEntity($id);
        if ($request->query->has('confirmation')) {
            $this->model->delete($stand);
            return $this->redirectToRoute('stands');
        }
        return $this->render('stand_delete.html.twig', [
            'stand' => $stand, 
            'rol' => $request->getSession()->get('rol')]);
    }

    #[Route(name: 'stand', path: '/stand/{id}', methods: 'GET')]
    public function details(Request $request, int $id): Response
    {
        if(!$request->getSession()->has('login')) {
            return $this->redirectToRoute('select');
        }
        $entity = $this->ifEntity($id);
        $seller = $this->sellerModel->get($entity->sellerId);
        $sellerName = $this->userModel->get($seller->userId)->getFullName();
        $city = $this->cityModel->get($entity->city);
        $users = $this->userModel->all();
        $orders = $this->orderModel->getByStand($id);
        $products = $this->productModel->allByStand($id);
        return $this->render('stand.html.twig', ['stand' => $entity, 'products' => $products, 'seller' => $sellerName, 'city' => $city, 'orders' => $orders, 'users' => $users, 'rol' => $request->getSession()->get('rol')]);
    }

    #[Route('/stand/{id}/newproduct', methods: [ 'GET', 'POST' ])]
    public function newProduct(Request $request, int $id): Response
    {
        if(!$request->getSession()->has('login')) {
            return $this->redirectToRoute('select');
        }

        $product = new Product();
        $entity = $this->ifEntity($id);
        $form = $this->createForm(ProductForm::class, $product, ['sid' => $id]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!empty($product->image)) {
                $new_file = $product->image->move('products', uniqid() . ".{$product->image->guessExtension()}");
                $product->image = $new_file;
            }
            $this->productModel->save($product);
            return $this->redirectToRoute('stand', ['id' => $id]);
        }

        return $this->render('product_edit.html.twig', [ 'product' => $product, 'form' => $form, 'rol' => $request->getSession()->get('rol') ]);
    }

    #[Route('/stand/{id}/product/{pid}', methods: 'GET')]
    public function product(Request $request, int $id, int $pid): Response
    {
        if(!$request->getSession()->has('login')) {
            return $this->redirectToRoute('select');
        }
        $product = $this->productModel->get($pid);
        $stand = $this->ifEntity($id);
        return $this->render('product.html.twig', ['stand' => $stand, 'product' => $product, 'rol' => $request->getSession()->get('rol')]);
    }

    #[Route('/stand/{id}/product/{pid}/edit', methods: [ 'GET', 'POST' ])]
    public function editPredirectToRoute(Request $request, int $id, int $pid): Response
    {
        if(!$request->getSession()->has('login')) {
            return $this->redirectToRoute('select');
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

        return $this->render('product_edit.html.twig', [ 'product' => $product, 'form' => $form, 'rol' => $request->getSession()->get('rol') ]);
    }

    #[Route(name: 'deleteproduct', path: '/stand/{id}/product/{pid}/delete', methods: 'GET')]
    public function deleteProduct(Request $request, int $id, int $pid): Response
    {
        if(!$request->getSession()->has('login')) {
            return $this->redirectToRoute('select');
        }
        $product = $this->productModel->get($pid);
        $stand = $this->ifEntity($id);
        if ($request->query->has('confirmation')) {
            $this->productModel->delete($product);
            return $this->redirect('/stand/'.$id);
        }
        return $this->render('product_delete.html.twig', ['stand' => $stand, 'product' => $product, 'rol' => $request->getSession()->get('rol')]);
    }

    #[Route('/stand/{id}/order/{oid}', methods: 'GET')]
    public function viewOrder(Request $request, int $id, int $oid): Response
    {
        if(!$request->getSession()->has('login')) {
            return $this->redirectToRoute('select');
        }
        $order = $this->orderModel->get($oid);
        $buyer = $this->userModel->get($order->buyerId)->getFullName();
        $products = $this->productModel->allByStand($id);
        $productsInOrder = $this->pioModel->getByOrder($oid);

        return $this->render('order.html.twig', ['order' => $order, 'buyer' => $buyer, 'products' => $products, 'productsInOrder' => $productsInOrder, 'rol' => $request->getSession()->get('rol')]);
    }

    #[Route('/stand/{id}/order/{oid}/messages')]
    public function messages(Request $request, int $id, int $oid): Response
    {
        if(!$request->getSession()->has('login')) {
            return $this->redirectToRoute('select');
        }
        $order = $this->orderModel->get($oid);
        $buyer = $this->userModel->get($order->buyerId);
        $messages = $this->messageModel->getByOrder($oid);
        $message = new Message();
        $form = $this->createForm(MessageForm::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Message */
            $message = $form->getData();
            $toId = $this->orderModel->get($oid)->buyerId;
            $message->userFrom = $request->getSession()->get('login');
            $message->userTo = $toId;
            $this->messageModel->save($message);
            return $this->redirect('/stand/'.$id.'/order/'.$sid.'/messages');
        }

        return $this->render('messages.html.twig', ['form' => $form, 'order' => $order, 'buyer' => $buyer, 'messages' => $messages, 'rol' => $request->getSession()->get('rol')]);
    }

    #[Route('/stand/{id}/order/{oid}/messages/send')]
    public function sendMessage(Request $request, int $oid): Response
    {
        if(!$request->getSession()->has('login')) {
            return $this->redirectToRoute('select');
        }
        $toId = $this->orderModel->get($oid)->buyerId;
        $message->userFrom = $request->getSession()->get('login');
        $message->userTo = $toId;
        $message->body = 'a';
    }

    #[Route('/stand/{id}/order/{oid}/update/{state}')]
    public function updateState(Request $request, int $id, int $oid, string $state): Response
    {
        if(!$request->getSession()->has('login')) {
            return $this->redirectToRoute('select');
        }

        $this->orderModel->updateState($oid, $state);

        return $this->redirect('/stand/'.$id.'/order/'.$oid);
    }
}
