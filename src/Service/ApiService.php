<?php

namespace App\Service;

use App\Entity\Api;
use App\Entity\User;
use App\Repository\ApiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ApiService
{
    /**
     * @var ApiRepository
     */
    private $apiRepository;

    /**
     * @var PaymentPlanService
     */
    private $paymentPlanService;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ApiService constructor.
     * @param ApiRepository $apiRepository
     * @param PaymentPlanService $paymentPlanService
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ApiRepository $apiRepository, PaymentPlanService $paymentPlanService, EntityManagerInterface $entityManager)
    {
        $this->apiRepository = $apiRepository;
        $this->paymentPlanService = $paymentPlanService;
        $this->entityManager = $entityManager;
    }

    public function getApi(int $id): Api
    {
        return $this->apiRepository->find($id);
    }

    public function getApis(): array
    {
        return $this->apiRepository->findAll();
    }

    /**
     * Creates an API
     *
     * @param string $name
     * @param string $description
     * @param User $creator
     * @return Api
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createApi(string $name, string $description, UserInterface $creator): Api
    {
        $api = new Api();
        $api->setName($name)
            ->setDescription($description)
            ->setCreatedBy($creator)
            ->setStatus(Api::STATUS_INIT)
            ->setPaymentPlan($this->paymentPlanService->getFreePaymentPlan());

        $this->entityManager->persist($api);
        $this->entityManager->flush();

        return $api;
    }

    /**
     * Deletes an api
     *
     * @param Api $api
     * @return Api
     */
    public function deleteApi(Api $api)
    {
        return $api->setDeleted(true);
    }
}