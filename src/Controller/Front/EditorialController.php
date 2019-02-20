<?php

namespace App\Controller\Front;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class EditorialController
 * @package App\Controller\Front
 * @Route(name="app_editorial_")
 */
class EditorialController extends AbstractController
{
    /**
     * @Route(path="/about-us", name="about_us")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function aboutUs()
    {
        return $this->render('components/editorial_pages/about/about.html.twig', []);
    }
}