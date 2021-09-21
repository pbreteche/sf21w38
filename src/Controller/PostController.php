<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/post", methods="GET")
     */
    public function index(PostRepository $repository): Response
    {
        $posts = $repository->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/post/{id}", requirements={"id": "\d+"}, methods="GET")
     */
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/post/new", methods={"GET", "POST"})
     */
    public function create(): Response
    {
        $newPost = new Post();

        $form = $this->createFormBuilder($newPost)
            ->add('title')
            ->add('body')
            ->add('isPublished')
            ->getForm()
        ;

        return $this->render('post/new.html.twig', [
            'create_form' => $form->createView(),
        ]);
    }
}
