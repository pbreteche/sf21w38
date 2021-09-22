<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
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
        $form = $this->createForm(PostType::class, $newPost);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPost->setCreatedAt();
            $manager->persist($newPost);
            $manager->flush();

            $this->addFlash('success', 'La publication a été correctement enregistrée.');

            return $this->redirectToRoute('app_post_show', [
                'id' => $newPost->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/new.html.twig', [
            'create_form' => $form,
        ]);
    }

    /**
     * @Route("/post/{id}/edit", requirements={"id": "\d+"}, methods={"GET", "POST"})
     */
    public function edit(
        Post $post,
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUpdatedAt(new \DateTimeImmutable());
            $manager->flush();

            $this->addFlash('success', 'La publication a été correctement modifiée.');

            return $this->redirectToRoute('app_post_show', [
                'id' => $post->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/edit.html.twig', [
            'edit_form' => $form,
            'post' => $post,
        ]);
    }
}
