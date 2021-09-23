<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Factory\PostInitializer;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\EqualTo;

/**
 * @method User getUser()
 *
 * @Route("/post", methods="GET")
 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
 */
class PostController extends AbstractController
{
    /**
     * @Route("")
     */
    public function index(Request $request, PostRepository $repository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if (!$this->isGranted('ROLE_USER')) {
            throw $this->createAccessDeniedException();
        }

        $page = (int)$request->query->get('p', 1);

        $posts = $repository->findByMonth2(new \DateTimeImmutable(), $page);
        //$posts = $repository->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"})
     */
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/new", methods="POST")
     */
    public function create(Request $request, PostInitializer $factory, EntityManagerInterface $manager): Response
    {
        $newPost = $factory->create();
        $form = $this->createForm(PostType::class, $newPost);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
     * @Route("/{id}/edit", requirements={"id": "\d+"}, methods="PUT")
     * @IsGranted("POST_EDIT", subject="post")
     */
    public function edit(
        Post $post,
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $post->setUpdatedAt();
        $form = $this->createForm(PostType::class, $post, [
            'method' => 'put',
            'warn_seo' => true,
            'validation_groups' => ['Post', 'Edit'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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

    /**
     * @Route("/{id}/delete", requirements={"id": "\d+"}, methods="DELETE")
     */
    public function delete(
        Post $post,
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $form = $this->createFormBuilder(null, ['method' => 'delete'])
            ->add('confirm', TextType::class, [
                'help' => 'Recopiez ici le titre de la publication',
                'constraints' => [new EqualTo(['value' => $post->getTitle()])]
            ])->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->remove($post);
            $manager->flush();

            $this->addFlash('success', 'La publication a été définitivement supprimée.');

            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/delete.html.twig', [
            'delete_form' => $form,
            'post' => $post,
        ]);
    }
}
