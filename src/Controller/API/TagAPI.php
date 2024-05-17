<?php

namespace App\Controller\API;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

class TagAPI extends AbstractController
{
    #[Route("/api/tagsget", name: "app_api_tag_index", methods: ["GET"])]
    public function index(TagRepository $tagRepository, Request $request): JsonResponse
    {
        try {
            $name = $request->get('name', null);
            $id = $request->get('id', null);

            if (is_null($name) && is_null($id)) {
                $tags = $tagRepository->findAll();
            } else {
                $tags = $tagRepository->findByFilters($name, $id);
            }

            return $this->json($tags, 200, [], ['groups' => 'tag:read']);
        } catch (Throwable $e) {
            return $this->json([
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTrace(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    #[Route("/api/tags", name: "app_api_tag_create", methods: ["POST"])]
    public function createTag(TagRepository $tagRepository, Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        try {
            /** @var Tag $tag */
            $tag = $serializer->deserialize($request->getContent(), Tag::class, 'json');

            $errors = $validator->validate($tag);
            if (count($errors) > 0) {
                $violations = [];
                foreach ($errors as $error) {
                    $violations[] = [
                        'field' => $error->getPropertyPath(),
                        'message' => $error->getMessage(),
                    ];
                }

                return $this->json($violations, Response::HTTP_BAD_REQUEST);
            }

            $tagRepository->save($tag);

            return $this->json($tag);
        } catch (Throwable $e) {
            return $this->json([
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTrace(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


}