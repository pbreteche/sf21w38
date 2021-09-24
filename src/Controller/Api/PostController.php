<?php

namespace App\Controller\Api;

use App\Entity\Post;
use App\Serializer\PostSerializer;
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
    public function show(Post $post, PostSerializer $serializer): Response
    {
        $serializedPost = $serializer->serialize($post);

        return $this->json($serializedPost);
    }
}