<?php
/**
 * Created by IntelliJ IDEA.
 * @author Evgeniy
 * Date: 2018-10-31
 */

namespace App\Controller;


use App\Service\Greeting;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @var Greeting
     */
    private $greeting;

    public function __construct(Greeting $greeting)
    {
        $this->greeting = $greeting;
    }

    /**
     * @Route("/", name="blog_index")
     */
    public function index(Request $request)
    {
        $name = 'test';
        if ( $request->get('name')) {
            $name = $request->get('name');
        }


        return $this->render(
            'base.html.twig', [
                'message' => $this->greeting->greet($name)
            ]
        );
    }
}
