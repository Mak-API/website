<?php

namespace App\Controller\Back;

use App\Form\CronTasksType;
use App\Repository\CronTasksRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\CronTasks;

/**
 * Class CronTasksController
 * @package App\Controller\Back
 * @IsGranted("ROLE_ADMIN", statusCode="404")
 * @Route(path="/tasks", name="app_crontasks_")
 */
class CronTasksController extends AbstractController
{
    /**
     * @param CronTasksRepository $cronTasksRepository
     * @return Response
     * @Route(path="/", methods={"GET"}, name="index")
     */
    public function index(CronTasksRepository $cronTasksRepository): Response
    {
        return $this->render('back/crontasks/index.html.twig', [
            'activatedTasks' => $cronTasksRepository->findBy(['disabled' => 0])
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/add", name="add")
     */
    public function add(Request $request): Response
    {
        $crontasks = new CronTasks();
        $form = $this->createForm(CronTasksType::class, $crontasks);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($crontasks);
            $em->flush();
            return $this->redirectToRoute('app_crontasks_index');
        }
        return $this->render('back/crontasks/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param CronTasks $cronTasks
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route(path="/delete/{id}", name="delete")
     */
    public function remove(CronTasks $cronTasks): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($cronTasks);
        $em->flush();
        return $this->redirectToRoute('app_crontasks_index');
    }
}