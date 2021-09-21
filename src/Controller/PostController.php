<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $newPost = new Post();

        $form = $this->createFormBuilder($newPost)
            ->add('title')
            ->add('body')
            ->add('isPublished')
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPost->setCreatedAt();
            $manager->persist($newPost);
            $manager->flush();

            return $this->redirectToRoute('app_post_show', [
                'id' => $newPost->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/new.html.twig', [
            'create_form' => $form,
        ]);
    }
}
