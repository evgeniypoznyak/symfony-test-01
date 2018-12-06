<?php
/**
 * Created by IntelliJ IDEA.
 * @author Evgeniy
 * Date: 2018-10-31
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BlogController
 * @package App\Controller
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("/", name="blog_index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(): Response
    {
        return $this->render(
            'blog/index.html.twig',
            [
                'posts' => $this->session->get('posts'),
            ]
        );
    }

    /**
     * @Route("/add", name="blog_add")
     */
    public function add()
    {
        $posts = $this->session->get('posts');
        $posts[uniqid()] = [
            'title' => 'Random title ' . rand(1, 500),
            'text' => 'Random text ' . rand(1, 500),
        ];
        $this->session->set('posts', $posts);

        return $this->render(
            'blog/index.html.twig',
            [
                'posts' => $this->session->get('posts'),
            ]
        );
    }

    /**
     * @Route("/show/{id}", name="blog_show")
     */
    public function show($id)
    {
        $posts = $this->session->get('posts');
        if (!$posts || !isset($posts)) {
            throw new NotFoundHttpException('Post not found');
        }

        return $this->render(
            'blog/post.html.twig',
            [
                'id' => $id,
                'post' => $posts[$id],
            ]
        );
    }

    /**
     * @Route("/with-name/{name}", name="blog_with_name")
     * @param string $name
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function withName($name = 'test'): Response
    {
        return $this->render(
            'base.html.twig',
            [
                'message' => $name,
            ]
        );
    }

}
