<?php

namespace App\Controller\Front;

use App\Entity\News;
use App\Form\NewsType;
use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package App\Controller\Front
 * @Route("/news", name="app_news_")
 */
class NewsController extends AbstractController
{
    /**
     * @param NewsRepository $newsRepository
     * @Route("/", name="index", methods={"GET"})
     * @return Response
     */
    public function index(NewsRepository $newsRepository): Response
    {
        return $this->render('front/editorial/news/news_index.html.twig', [
            'news' => $newsRepository->findBy(['isPublished' => true]),
        ]);
    }

    /**
     * @param News $new
     * @Route("/{id}", name="show", methods={"GET"})
     * @return Response
     */
    public function show(News $new): Response
    {
        return $this->render('front/editorial/news/show.html.twig', [
            'new' => $new,
        ]);
    }

}
