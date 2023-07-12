<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/posts')]
class PostController extends AbstractController
{
  #[Route(name: 'app_post')]
  public function index(PostRepository $postRepository): JsonResponse
  {
    return $this->json($postRepository->findAll());
  }
}
