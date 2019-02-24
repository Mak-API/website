<?php

namespace App\Controller\Back;


use App\Form\CronTasksType;
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
 * @Route(path="/admin/tasks", name="app_crontasks_")
 */
class CronTasksController extends AbstractController
{
    /**
     * @return Response
     * @Route(path="/", methods={"GET"}, name="index")
     */
    public function index(): Response
    {
        return $this->render('back/crontasks/index.html.twig');
    }

    /**
     * @return Response
     */
    public function add(Request $request): Response
    {
        $crontasks = new CronTasks();
        $form = $this->createForm(CronTasksType::class, $crontasks);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dump('test');
        }
        return $this->render('back/crontasks/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}