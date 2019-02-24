<?php
/**
 * Created by PhpStorm.
 * User: Backins
 * Date: 24/02/2019
 * Time: 13:23
 */

namespace App\Controller\Back;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CronTasksController
 * @package App\Controller\Back
 * @IsGranted("ROLE_ADMIN", statusCode="404")
 * @Route(path="/admin/tasks" name="app_crontasks_")
 */
class CronTasksController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index()
    {
        return $this->render('back/crontasks/base_tasks.html.twig', []);
    }
}