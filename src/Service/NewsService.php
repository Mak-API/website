<?php

namespace App\Service;

use App\Entity\News;
use App\Entity\User;
use App\Repository\NewsRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class NewsService {

    /**
     * @var NewsRepository
     */
    private $newsRepository;

    public function __construct(NewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    public function setTimestamps(News $news, UserInterface $createdBy)
    {
        $news->setCreatedBy($createdBy);
        $news->setUpdatedBy($createdBy);
        $news->setCreatedAt(new \DateTime());
        $news->setUpdatedAt(new \DateTime());
    }
}