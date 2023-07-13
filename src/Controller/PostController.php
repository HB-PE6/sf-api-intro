<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/posts')]
class PostController extends AbstractController
{
  #[Route(name: 'api_posts_index', methods: ['GET'])]
  public function index(PostRepository $postRepository): JsonResponse
  {
    return $this->json($postRepository->findAll());
  }

  #[Route(name: 'api_post_create', methods: ['POST'])]
  public function create(
    Request $request,
    EntityManagerInterface $em,
    SerializerInterface $serializer
  ): JsonResponse {
    $body = $request->getContent(); // string, JSON Format
    $post = $serializer->deserialize($body, Post::class, 'json');
    $em->persist($post);
    $em->flush();

    return $this->json([
      'uri' => '/posts/' . $post->getId(),
      'post' => $post
    ], Response::HTTP_CREATED);
  }

  #[Route('/{id}', name: 'api_posts_item')]
  public function item(PostRepository $postRepository, int $id): JsonResponse
  {
    $post = $postRepository->find($id);

    return $this->json($post);
  }
}
