<?php

namespace App\Controller\Api;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/post", defaults={"_format": "json"})
 */
class PostController extends AbstractController
{

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, methods="GET")
     */
    public function show(Post $post): Response
    {
        $serializedPost = [
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'body' => $post->getBody(),
            'created_at' => $post->getCreatedAt()->format('c'),
            'written_by' => [
                'nickname' => $post->getWrittenBy()->getNickName()
            ]
        ];

        return $this->json($serializedPost);
    }
}