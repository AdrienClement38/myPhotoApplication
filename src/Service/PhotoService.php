<?php

namespace App\Service;

use App\Repository\PhotoRepository;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class PhotoService
{
    private PhotoRepository $photoRepository;
    private SessionInterface $session;

    public function __construct(RequestStack $requestStack, PhotoRepository $photoRepository)
    {
        $this->session = $requestStack->getSession();
        $this->photoRepository = $photoRepository;
    }


    public function getPhoto(int $id): object
    {
        return $this->photoRepository->find($id);
    }

}
