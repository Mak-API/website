<?php

namespace App\Controller\Back;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function add(): Response
    {
        return $this->render('back/crontasks/add.html.twig');
    }
}