<?php

namespace App\Service;

use App\Repository\PhotoRepository;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class CartService
{
    private PhotoRepository $photoRepository;
    private SessionInterface $session;

    public function __construct(RequestStack $requestStack, PhotoRepository $photoRepository)
    {
        $this->session = $requestStack->getSession();
        $this->photoRepository = $photoRepository;
    }

    public function getCart(): array
    {
        return $this->session->get('cart', []);
    }



    /**
     * @param array $cart
     * @return array
     */
    public function showCart(array $cart): array
    {
        $total = 0;
        $datas = [];
        foreach ($cart as $id => $item) {
            $photo = $this->photoRepository->find($id);
            $quantity = $item['quantity'];
            $datas[] = [
                'photo' => $photo,
                'quantity' => $quantity
            ];

            $total += $photo->getPrice() * $quantity;
        }
        return array($total, $datas);
    }


    /**
     * @throws Exception
     */
    public function addToCart(int $id, int $quantity): array
    {
        $cart = $this->getCart();

        $photo = $this->photoRepository->find($id);

        if (!$photo) {
            throw new Exception('Photo not found');
        }

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                'quantity' => $quantity
            ];
        }

        $this->session->set('cart', $cart);

        return $cart;
    }



    public function removeFromCart(int $id): void
    {
        $cart = $this->getCart();

        foreach ($cart as $key => $item) {
            if ($key === $id) {
                unset($cart[$key]);
                break;
            }
        }

        $this->session->set('cart', $cart);
    }

    public function clearCart(): void
    {
        $this->session->remove('cart');
    }

    public function incrementItem(int $id): void
    {
        $cart = $this->getCart();

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += 1;
        }

        $this->session->set('cart', $cart);
    }

    public function decrementItem(int $id): void
    {
        $cart = $this->getCart();

        if (isset($cart[$id])) {
            if ($cart[$id]['quantity'] === 1) {
                unset($cart[$id]);
            } else {
                $cart[$id]['quantity'] -= 1;
            }
        }

        $this->session->set('cart', $cart);
    }
}
