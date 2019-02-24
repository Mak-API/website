<?php

namespace App\Controller\Back;

use App\Form\UserType;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Security;

/**
 * Class AdminController
 * @package App\Controller\Back
 * @Route(path="/admin", name="app_admin_")
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
        return $this->render('admin/index.html.twig');
    }
}