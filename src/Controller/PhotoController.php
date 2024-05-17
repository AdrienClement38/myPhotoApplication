<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Form\PhotoSearchType;
use App\Form\PhotoType;
use App\Repository\PhotoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/photo')]
class PhotoController extends AbstractController
{

    public function __construct(Private readonly PhotoRepository $photoRepository)
    {

    }


    #[Route("/search", name: 'photo_search')]
    public function search(Request $request): Response
    {
        $form = $this->createForm(PhotoSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('title')->getData();

            return $this->redirectToRoute('app_photo_show', ['slug' => $photo->getSlug()]);
        }

        return $this->render('search/photo_search_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route("/autocomplete", name: 'photo_autocomplete')]
    public function autocomplete(Request $request): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $search = $request->query->get('search');
        $photos = $this->photoRepository->findBy(['title' => $search]);
        $response = [];
        foreach ($photos as $photo) {
            $response[] = ['id' => $photo->getId(), 'title' => $photo->getTitle()];
        }
        return $this->json($response);
    }

    #[Route('/', name: 'app_photo_index', methods: ['GET'])]
    public function index(PhotoRepository $photoRepository): Response
    {
        return $this->render('photo/index.html.twig', [
            'photos' => $photoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_photo_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $photo = new Photo();
        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($photo);
            $entityManager->flush();

            return $this->redirectToRoute('app_photo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('photo/new.html.twig', [
            'photo' => $photo,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'app_photo_show', methods: ['GET'])]
    public function show(Photo $photo): Response
    {
        return $this->render('photo/show.html.twig', [
            'photo' => $photo,
        ]);
    }


    #[Route('/{slug}/edit', name: 'app_photo_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Photo $photo, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_photo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('photo/edit.html.twig', [
            'photo' => $photo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_photo_delete', methods: ['POST'])]
    public function delete(Request $request, Photo $photo, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $photo->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($photo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_photo_index', [], Response::HTTP_SEE_OTHER);
    }


}
