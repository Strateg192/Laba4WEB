<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Form\PostType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;
/**
 * @Route("/post")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="post_index", methods={"GET"})
     */
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
			'posts' => $postRepository->findBy([], ['date_added' => 'DESC'])
        ]);
    }

    /**
     * @Route("/new", name="post_new", methods={"GET","POST"})
     */
    public function new(Request $request, PostRepository $postRepository): Response
    {
        $post = new Post();
		$post->setCountViews(0);
		// устанавливем время
        $dateNow = new \DateTime();
        $post->setDateAdded($dateNow);
        // устанавливаем  пользователя
        $userNow = $this->getUser();
        $post->setUserFk($userNow);
		
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
        }

        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_show", methods={"GET","POST"})
     */
    public function show(Request $request, Post $post, CommentRepository $commentRepository): Response
    {
		$post->setCountViews(($post->getCountViews())+1);
		$entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($post);
        $entityManager->flush();
			
		if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->render('post/show.html.twig', [
                'post' => $post,
                'comments' => $commentRepository->findBy(['post_fk' => $post->getId()],
                    ['date_added' => 'ASC'])
            ]);
        }
		
		$comment = new Comment();
        // устанавливем время
        $dateNow = new \DateTime();
        $comment->setDateAdded($dateNow);
        // устанавливаем  пользователя
        $userNow = $this->getUser();
        $comment->setUserFk($userNow);
        // устанавливаем  post_fk
        $comment->setPostFk($post);

        //созданем форму
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            $request->request->remove('comment');
            // редирект на эту же страницу (для очистки формы)
            return $this->redirect($request->getUri());
        }
		
        return $this->render('post/show.html.twig', [
            'post' => $post,
            'comments' => $commentRepository->findBy(['post_fk' => $post->getId()],
                ['date_added' => 'ASC']),
            'form' => $form->createView()
        ]);
    }
}
