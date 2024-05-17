<?php

namespace App\Controller\API;

use App\Entity\Photo;
use App\Repository\PhotoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

class PhotoApi extends AbstractController
{

    #[Route("/api/photosget", name: "app_api_photo_index", methods: ["GET"])]
    public function index(PhotoRepository $photoRepository, Request $request): JsonResponse
    {
        try {
            $name = $request->get('name', null);
            $id = $request->get('id', null);

            if (is_null($name) && is_null($id)) {
                $photos = $photoRepository->findAll();
            } else {
                $photos = $photoRepository->findByFilters($name, $id);
            }

            return $this->json($photos, 200 , [], ['groups' => 'photo:read']);
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


    #[Route("/api/photoscreate", name: "app_api_photo_create", methods: ["POST"])]
    public function createPhoto(PhotoRepository $photoRepository, Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        try {
            /** @var Photo $photo */
            $photo = $serializer->deserialize($request->getContent(), Photo::class, 'json');

            $errors = $validator->validate($photo);
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

            $photoRepository->save($photo);

            return $this->json($photo);
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
