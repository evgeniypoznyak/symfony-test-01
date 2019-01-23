<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Entity\User;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class MicroPostController
 * @package App\Controller
 * @Route("/micro-post")
 * // @ Security ("is_granted('ROLE_USER')", message="LA-LA! Access Denied") -  Protect all controller and child routes
 */
class MicroPostController extends AbstractController
{

    /**
     * @var MicroPostRepository
     */
    private $microPostRepository;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    public function __construct(
        MicroPostRepository $microPostRepository,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        RouterInterface $router,
        FlashBagInterface $flashBag
    ) {
        $this->microPostRepository = $microPostRepository;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->flashBag = $flashBag;
    }

    /**
     * @Route("/", name="micro_post_index")
     */
    public function index()
    {
        $posts = $this->microPostRepository->findBy([], ['time' => 'DESC']);

        return $this->render(
            'micro-post/index.html.twig',
            [
                'posts' => $posts,
            ]
        );
    }

    /**
     * @Route("/edit/{id}", name="micro_post_edit")
     * // microPost - variable name fetched by param converter
     * @Security("is_granted('edit', microPost)", message="LOL!!! Access Denied")
     * @param MicroPost $microPost
     * @param Request $request
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(MicroPost $microPost, Request $request)
    {

        // $this->denyAccessUnlessGranted('edit', $microPost, message='Oops! Access Denied');

        $form = $this->formFactory->create(MicroPostType::class, $microPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('micro_post_index'));
        }

        return $this->render(
            'micro-post/edit.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/delete/{id}", name="micro_post_delete")
     * @Security("is_granted('delete', microPost)", message="Oops! Access Denied")
     * @param MicroPost $microPost
     * @return RedirectResponse
     */
    public function delete(MicroPost $microPost)
    {
//        $this->denyAccessUnlessGranted('delete', $microPost);
        $this->entityManager->remove($microPost);
        $this->entityManager->flush();
        $this->flashBag->add('notice', 'Micropost was deleted');

        return new RedirectResponse($this->router->generate('micro_post_index'));
    }

    /**
     * @Route ("/add", name="micro_post_add")
     * @Security("is_granted('ROLE_USER')", message="Oops! You are not authenticated!")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function add(Request $request)
    {
        $user = $this->getUser();
        $microPost = new MicroPost();
        $microPost->setTime(new \DateTime());
        $microPost->setUser($user);
        $form = $this->formFactory->create(MicroPostType::class, $microPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($microPost);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('micro_post_index'));
        }

        return $this->render(
            'micro-post/add.html.twig',
            [
                'form' => $form->createView(),
            ]
        );

    }

    /**
     * @Route("/user/{username}", name="micro_post_user")
     * @param User $userWithPosts
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userPosts(User $userWithPosts)
    {
//        $posts = $this->microPostRepository->findBy(
//            ['user' => $userWithPosts],
//            ['time' => 'DESC']
//        );

        $posts = $userWithPosts->getPosts();

        return $this->render(
            'micro-post/index.html.twig',
            [
                'posts' => $posts,
            ]
        );
    }

    /**
     * @Route("/{id}", name="micro_post_post")
     * @param MicroPost $post
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function post(MicroPost $post)
    {
        // $post = $this->microPostRepository->find($microPost);

        return $this->render('micro-post/single-post.html.twig', ['post' => $post]);

    }


}
