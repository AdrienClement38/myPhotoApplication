<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagSearchType;
use App\Form\TagType;
use App\Repository\TagRepository;
use App\Service\TagService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tag')]
class TagController extends AbstractController
{
    public function __construct( Private readonly TagRepository $tagRepository, Private readonly EntityManagerInterface $entityManager, Private readonly TagService $tagService)
    {

    }

    #[Route('/', name: 'app_tag_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('tag/index.html.twig', [
            'tags' => $this->tagRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_tag_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($tag);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_tag_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tag/new.html.twig', [
            'tag' => $tag,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tag_show', methods: ['GET'])]
    public function show(Tag $tag): Response
    {
        return $this->render('tag/show.html.twig', [
            'tag' => $tag,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tag_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tag $tag): Response
    {
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_tag_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tag/edit.html.twig', [
            'tag' => $tag,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tag_delete', methods: ['POST'])]
    public function delete(Request $request, Tag $tag): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tag->getId(), $request->getPayload()->get('_token'))) {
            $this->entityManager->remove($tag);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_tag_index', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request): Response
    {
        $form = $this->createForm(TagSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($form->get('searchField')->getData() as $entity) {
                   $tag = $entity;
            }
        }

        return $this->render('search/index.html.twig', [
            'form' => $form,
        ]);
    }


    /**
     * @Route("/autocomplete", name="autocomplete")
     */
    public function autocomplete(Request $request): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $search = $request->query->get('search');
        $tags = $this->tagRepository->findBy(['name' => $search]);
        $response = [];
        foreach ($tags as $tag) {
            $response[] = ['id' => $tag->getId(), 'name' => $tag->getName()];
        }
        return $this->json($response);
    }
}
