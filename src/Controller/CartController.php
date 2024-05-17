<?php

namespace App\Controller;

use App\Service\CartService;
use Exception;
use http\Client\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

#[Route('/cart', name: 'app_cart_')]
class CartController extends AbstractController
{
    private CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }


    #[Route('/', name: 'show')]
    public function index(): Response
    {
        $cart = $this->cartService->getCart();

        list($total, $datas) =$this->cartService->showCart($cart);

        return $this->render('cart/index.html.twig', [
            'datas' => $datas,
            'total' => $total,
        ]);
    }



    /**
     * @throws Exception
     */
    #[Route('/add/{id}', name: 'add')]
    public function addToCart(SymfonyRequest $request, int $id): JsonResponse
    {
        // Récupération de la quantité à partir de la requête
        $data = json_decode($request->getContent(), true);
        $quantity = $data['quantity'];

        $cart = $this->cartService->addToCart($id, $quantity);

        return new JsonResponse(['cart' => $cart]);
    }

    #[Route('/remove/{id}', name: 'remove')]
    public function removeFromCart(int $id): Response
    {
        $this->cartService->removeFromCart($id);

        return $this->redirectToRoute('app_home');
    }



    #[Route('/clear', name: 'clear')]
    public function clearCart(): Response
    {
        $this->cartService->clearCart();

        return $this->redirectToRoute('app_home');
    }



    #[Route('/increment/{id}', name: 'increment')]
    public function incrementItem(int $id): Response
    {
        $this->cartService->incrementItem($id);

        return $this->redirectToRoute('app_cart_show');
    }



    #[Route('/decrement/{id}', name: 'decrement')]
    public function decrementItem(int $id): Response
    {
        $this->cartService->decrementItem($id);

        return $this->redirectToRoute('app_cart_show');
    }

}
