<?php

namespace App\Controller\Generator;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FormController
 * @package App\Controller\Generator
 * @Route(path="/generator", name="app_generator_form_")
 */
class FormController extends AbstractController
{
    /**
     * @Route("/form", name="index")
     */
    public function index()
    {
        return $this->render('generator/index_form.html.twig', [
            'controller_name' => 'FormController',
        ]);
    }
}
