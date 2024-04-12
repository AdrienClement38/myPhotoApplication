<?php

namespace App\Controller;

use App\Repository\PhotoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/cart', name: 'app_cart_')]
class CartController extends AbstractController
{
    #[Route('/', name: 'show')]
    public function index(SessionInterface $session, PhotoRepository $photoRepository): Response
    {
        $cart = $session->get('cart', []);

        $total = 0;
        $datas = []; // Initialisation de $datas comme un tableau vide
        foreach ($cart as $id => $item) {
            $photo = $photoRepository->find($id);
            $quantity = $item['quantity'];
            $datas[] = [
                'photo' => $photo,
                'quantity' => $quantity
            ];

            $total += $photo->getPrice() * $quantity;
        }

        return $this->render('cart/index.html.twig', [
            'datas' => $datas,
            'total' => $total,
        ]);
    }

    #[Route('/add/{id}', name: 'add')]
    public function addToCart(SessionInterface $session, PhotoRepository $photoRepository, int $id): JsonResponse
    {
        // Je recupère mon panier si il existe, sinon je le crée
        $cart = $session->get('cart', []);

        // Je récupère la photo que je souhaite ajouter au panier
        $photo = $photoRepository->find($id);

        if (!$photo) {
            throw $this->createNotFoundException('Photo not found');
        }

        // Je regarde si la photo est dans le panier
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += 1;
        } else {
            $cart[$id] = [
                'quantity' => 1
            ];
        }

        // Je sauvegarde le panier mis à jour dans la session
        $session->set('cart', $cart);

        return new JsonResponse(['cart' => $cart]);
    }

    #[Route('/remove/{id}', name: 'remove')]
    public function removeFromCart(SessionInterface $session, int $id): Response
    {
        $cart = $session->get('cart', []);

        // Je supprime la photo du panier
        foreach ($cart as $key => $item) {
            if ($key === $id) {
                unset($cart[$key]);
                break;
            }
        }

        // Je sauvegarde le panier mis à jour dans la session
        $session->set('cart', $cart);

        // Redirection vers la page du panier ou autre page spécifiee
        return $this->redirectToRoute('app_home');
    }

    #[Route('/clear', name: 'clear')]
    public function clearCart(SessionInterface $session): Response
    {
        $session->remove('cart');
        return $this->redirectToRoute('app_home');
    }

    // Méthodes incrémenter l'article du panier

    #[Route('/increment/{id}', name: 'increment')]
    public function incrementItem(SessionInterface $session, int $id): Response
    {
        // Je recupère mon panier si il existe, sinon je le crée
        $cart = $session->get('cart', []);

        // Je regarde si la photo est dans le panier
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += 1;
        }

        // Je sauvegarde le panier mis à jour dans la session
        $session->set('cart', $cart);

        // Redirection vers la page du panier ou autre page spécifiee
        return $this->redirectToRoute('app_cart_show');
    }

    // Methode decrémenter l'article du panier

    #[Route('/decrement/{id}', name: 'decrement')]
    public function decrementItem(SessionInterface $session, int $id): Response
    {

        $cart = $session->get('cart', []);

        // Je regarde si la photo est dans le panier
        if (isset($cart[$id])) {

            if ($cart[$id]['quantity'] === 1) {
                unset($cart[$id]);
            } else {
                $cart[$id]['quantity'] -= 1;
            }
        }

        // Je sauvegarde le panier mis à jour dans la session
        $session->set('cart', $cart);

        // Redirection vers la page du panier ou autre page spécifiee
        return $this->redirectToRoute('app_cart_show');
    }
}