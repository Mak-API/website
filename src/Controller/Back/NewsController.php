<?php

namespace App\Controller\Back;

use App\Entity\News;
use App\Form\NewsType;
use App\Repository\NewsRepository;
use App\Service\NewsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package App\Controller\Back
 * @Route("/news", name="app_admin_news_")
 * @IsGranted("ROLE_ADMIN", statusCode="404")
 * @var NewsService
 */
class NewsController extends AbstractController
{
    /**
     * @var NewsService
     */
    private $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    /**
     * @param NewsRepository$newsRepository
     * @Route("/", name="index", methods={"GET"})
     * @return Response
     */
    public function index(NewsRepository $newsRepository): Response
    {
        return $this->render('back/news/index.html.twig', [
            'news' => $newsRepository->findAll(),
        ]);
    }

    /**
     * @param Request $request
     * @Route("/new", name="new", methods={"GET","POST"})
     * @return Response
     */
    public function new(Request $request): Response
    {
        $news = new News();
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->newsService->setTimestamps($news, $this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($news);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_news_index');
        }

        return $this->render('back/news/new.html.twig', [
            'news' => $news,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param News $news
     * @Route("/{id}", name="show", methods={"GET"})
     * @return Response
     */
    public function show(News $news): Response
    {
        return $this->render('components/editorial_pages/news/show.html.twig', [
            'news' => $news,
        ]);
    }

    /**
     * @param Request $request
     * @param News $news
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     * @return Response
     */
    public function edit(Request $request, News $news): Response
    {
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_admin_news_index', [
                'id' => $news->getId(),
            ]);
        }

        return $this->render('back/news/edit.html.twig', [
            'news' => $news,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param News $news
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @return Response
     */
    public function delete(Request $request, News $news): Response
    {
        if ($this->isCsrfTokenValid('delete'.$news->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($news);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_news_index');
    }
}
