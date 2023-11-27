<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Seller;
use App\Entity\Stand;
use App\Enum\StandCategory;
use App\Model\OrderRepo;
use App\Model\ProductRepo;
use App\Model\SellerRepo;
use App\Model\StandRepo;
use App\Service\Authorization;
use App\Service\MysqlStorage;
use App\Trait\EntityViolations;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class StandAPI extends AbstractController
{
    use EntityViolations;
    protected StandRepo $standRepo;
    protected ProductRepo $productRepo;
    protected SellerRepo $sellerRepo;
    protected OrderRepo $orderRepo;

    public function __construct(
        protected MysqlStorage $storage,
        protected ValidatorInterface $validator,
    ) {
        $this->standRepo = $storage->getModel(Stand::class);
        $this->productRepo = $storage->getModel(Product::class);
        $this->sellerRepo = $storage->getModel(Seller::class);
        $this->orderRepo = $storage->getModel(Order::class);
    }

    #[Route(path: '/api/stand/create', methods: ['PUT'])]
    public function createStand(Request $request, Authorization $authorization): Response
    {
        $data = $request->getPayload()->all();
        $user = $authorization->getSession();
        $stand = new Stand();
        $stand->hydrate($data);
        $stand->setSeller($user->id);
        $violations = $this->validator->validate($stand);
        $this->processErrors($violations);

        if (!$this->standRepo->save($stand)) {
            throw new BadRequestException('Data error');
        };
        return new JsonResponse($stand);
    }

    #[Route(path: '/api/stands', methods: ['GET'])]
    public function getStands(Request $request, Authorization $authorization): Response
    {
        $authorization->getSession();
        $page = $request->query->get('page', 1);
        $entities = $this->standRepo->allTeaser(limit: 10, page: $page - 1);
        return new JsonResponse($entities);
    }

    #[Route(path: '/api/stand/{id}', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function getStand(Authorization $authorization, int $id): Response
    {
        $authorization->getSession();
        $stand = $this->standRepo->get($id);
        if (!$stand) {
            throw new NotFoundHttpException('Page was not found');
        }
        return new JsonResponse($stand);
    }

    #[Route(path: '/api/stand/{id}', methods: ['PATCH'], requirements: ['id' => '\d+'])]
    public function updateStand(Request $request, Authorization $authorization, int $id): Response
    {
        $data = $request->getPayload()->all();
        $user = $authorization->getSession();
        $seller = $this->sellerRepo->getByUser($user->id);
        if (!$seller) {
            throw new UnauthorizedHttpException('Access denied');
        }
        /** @var Stand */
        $stand = $this->standRepo->get($id);
        if (!$stand) {
            throw new NotFoundHttpException('Page was not found');
        }
        if ($stand->sellerId !== $seller->id) {
            throw new UnauthorizedHttpException('Access denied');
        }

        $stand->hydrate($data);
        $stand->setSeller($user->id);
        $violations = $this->validator->validate($stand);
        $this->processErrors($violations);

        if (!$this->standRepo->update($stand)) {
            dd($this->standRepo->getErrors());
            throw new BadRequestException('Request error');
        };
        return new JsonResponse($stand);
    }

    #[Route(path: '/api/stand/{id}', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function deleteStand(Authorization $authorization, int $id): Response
    {
        $user = $authorization->getSession();
        $seller = $this->sellerRepo->getByUser($user->id);
        if (!$seller) {
            throw new UnauthorizedHttpException('Access denied');
        }
        /** @var Stand */
        $stand = $this->standRepo->get($id);
        if (!$stand) {
            throw new NotFoundHttpException('Page was not found');
        }
        if ($stand->sellerId !== $seller->id) {
            throw new UnauthorizedHttpException('Access denied');
        }
        if (!$this->standRepo->delete($stand)) {
            throw new BadRequestException('Bad request');
        }
        return new JsonResponse($stand);
    }

    #[Route(path: '/api/stand/{sid}/products', methods: ['PUT'], requirements: ['sid' => '\d+'])]
    public function addProduct(Request $request, Authorization $authorization, int $sid): Response
    {
        $authorization->getSession();
        $data = $request->getPayload()->all();
        $stand = $this->standRepo->get($sid);
        if (!$stand) {
            throw new NotFoundHttpException('Page was not found');
        }
        $product = new Product();
        $product->hydrate($data);
        $product->setStand($stand->id);
        $violations = $this->validator->validate($product);
        $this->processErrors($violations);
        if(!$this->productRepo->save($product)) {
            dd($this->productRepo->getErrors());
            throw new BadRequestException('Bad request');
        }

        return new JsonResponse($product);
    }

    #[Route(path: '/api/stand/{sid}/products', methods: ['GET'], requirements: ['sid' => '\d+'])]
    public function getProducts(Request $request, Authorization $authorization, int $sid): Response
    {
        $authorization->getSession();
        $page = $request->query->get('page', 1);
        $entities = $this->productRepo->allByStand(sid: $sid, limit: 10, page: $page - 1);
        return new JsonResponse($entities);
    }

    #[Route(path: '/api/stand/{sid}/product/{pid}', methods: ['PATCH'], requirements: ['sid' => '\d+', 'pid' => '\d+'])]
    public function changeProduct(Request $request, Authorization $authorization, int $sid, int $pid): Response
    {
        $data = $request->getPayload()->all();
        $user = $authorization->getSession();
        /** @var Product */
        $stand = $this->productRepo->get($pid);
        $stand->hydrate($data);
        $violations = $this->validator->validate($stand);
        $this->processErrors($violations);

        if (!$this->standRepo->update($stand)) {
            dd($this->standRepo->getErrors());
            throw new BadRequestException('Request error');
        };
        return new JsonResponse($stand);
    }

    #[Route(path: '/api/stand/{sid}/orders', methods: ['GET'])]
    public function getOrders(Request $request, Authorization $authorization, int $sid): Response
    {
        $authorization->getSession();
        $page = $request->query->get('page', 1);

        $stand = $this->standRepo->get($sid);
        $entities = $this->orderRepo->getByStand($stand->id);
        return new JsonResponse($entities);
    }

    #[Route(path: '/api/stand/{sid}/orders/create', methods: ['GET', 'POST'])]
    public function newOrder(Request $request, Authorization $authorization): Response
    {
        $data = $request->getPayload();
        $order = new Order;
        $order->hydrate($data->all());

        $violations = $this->validator->validate($order);
        $this->processErrors($violations);
        $type = $data->getInt('type', 0);

        $this->orderRepo->save($order);

        error_log(json_encode($this->orderRepo->getErrors()));

        return new JsonResponse($order);
    }

    #[Route(path: '/api/stands/category/{category}', methods: ['GET'])]
    public function getByCategory(Request $request, Authorization $authorization, string $category): Response
    {
        $authorization->getSession();
        $page = $request->query->get('page', 1);

        $entities = $this->standRepo->getByCategory($category, limit: 10, page: $page - 1);
        return new JsonResponse($entities);
    }
}
