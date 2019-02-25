<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\UserService;
use App\Service\EmailService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController
 * @Route(path="/profile", name="app_user_")
 * @package App\Controller
 * @var EmailService
 */
class UserController extends AbstractController
{
    /**
     * @var EmailService
     */
    private $emailService;
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(EmailService $emailService, UserService $userService)
    {
        $this->emailService = $emailService;
        $this->userService = $userService;
    }

    /**
     * @param UserRepository $userRepository
     * @Route("/",  name="index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN", statusCode="404")
     * @return Response
     */
    public function index(UserRepository $userRepository): Response
    {

        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @Route("/sign-up", name="new", methods={"GET","POST"})
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, ['group' => 'new']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            //generate and set email_token (for the email checking)
            $token = $this->emailService->gen_uuid();
            $user->setEmailToken($token);

            //sending the confirmation email
            if(!$this->emailService->confirmRegistration($user->getLogin(), $user->getEmail(), $user->getEmailToken())) {
                return $this->render('user/new.html.twig', [
                    'user' => $user,
                    'form' => $form->createView()
                ]);
            }

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param User $user
     * @return Response
     * @Route("/{login}", name="show", methods={"GET"})
     * SHOW_PROFILE => CONST variable in : UserVoter
     * user => $user in function parameter
     * @IsGranted("SHOW_PROFILE", subject="user", statusCode="404")
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @param Request $request
     * @param User $user
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @Route("/edit/{login}", name="edit", methods={"GET","POST"})
     * @return Response
     *
     * EDIT_PROFILE => CONST variable in : UserVoter
     * user => $user in function parameter
     * @IsGranted("EDIT_PROFILE", subject="user", statusCode="404")
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder): Response
    {

        $form = $this->createForm(UserType::class, $user, ['group' => 'edit']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_user_index', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param User $user
     * @Route("/confirmdelete/{id}", name="confirmdelete")
     * @return Response
     */
    public function confirmDeleteUser(User $user): Response
    {
        return $this->render('user/_delete_form.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @param User $user
     * @Route("/delete/{id}", name="delete", methods={"GET", "POST"})
     * @return Response
     */
    public function delete(User $user): Response
    {
        $this->userService->deleteUser($user->getId(), $user);
        return $this->redirectToRoute('app_security_logout');
    }
}
