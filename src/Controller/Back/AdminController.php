<?php

namespace App\Controller\Back;

use App\Form\UserType;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class AdminController
 * @package App\Controller\Back
 * @Route(name="app_admin_")
 * @IsGranted("ROLE_ADMIN", statusCode="404")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/",  name="index")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('back/admin/index.html.twig');
    }

    /**
     * @param UserRepository $userRepository
     * @Route("/users",  name="showUser", methods={"GET"})
     * @IsGranted("ROLE_ADMIN", statusCode="404")
     * @return Response
     */
    public function users(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
}