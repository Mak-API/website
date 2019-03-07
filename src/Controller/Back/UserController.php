<?php

namespace App\Controller\Back;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class UserController
 * @package App\Controller\Back
 * @Route("/users", name="app_admin_users_")
 * @IsGranted("ROLE_ADMIN", statusCode="404")
 */
class UserController extends AbstractController
{


    /**
     * @param UserRepository $userRepository
     * @Route("/",  name="index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN", statusCode="404")
     * @return Response
     */
    public function users(UserRepository $userRepository): Response
    {
        return $this->render('back/users/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @param User $user
     * @return Response
     * @Route("/delete/{id}", name="delete")
     */
    public function deleteUsers(User $user): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('app_admin_users_index');
    }
}