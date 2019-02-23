<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\UserService;
use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController
 * @Route(path="/user", name="app_user_")
 * @package App\Controller
 * @var EmailService
 */
class UserController extends AbstractController
{

    private $emailService;
    private $userService;

    public function __construct(EmailService $emailService, UserService $userService)
    {
        $this->emailService = $emailService;
        $this->userService = $userService;
    }

    /**
     * @Route("/",  name="index", methods={"GET"})
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
     * @Route("/new", name="new", methods={"GET","POST"})
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
        } //else if ($this->userService->isDeleted($user->getEmail())){}

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
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
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     * @return Response
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
        //$this->userService->deleteUser($user->getId(), $user);
        //$test = 'toto';
        return $this->render('user/_delete_form.html.twig', [
           'user' => $user,
          //  'test' => $test,
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
        return $this->redirectToRoute('app_logout');
    }
}
