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

    public function getApi(int $apiId): Api
    {
        return $this->apiRepository->find($apiId);
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
     * @param UserInterface $createdBy
     * @return Api
     */
    public function createApi(string $name, string $description, UserInterface $createdBy): Api
    {
        $api = new Api();
        $api->setName($name)
            ->setDescription($description)
            ->setCreatedBy($createdBy)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \Datetime())
            ->setStatus(Api::STATUS_INIT)
            ->setPaymentPlan($this->paymentPlanService->getFreePaymentPlan());

        $this->entityManager->persist($api);
        $this->entityManager->flush();

        return $api;
    }

    /**
     * Updates an Api.
     *
     * @param Api $api
     * @param string $name
     * @param string $description
     * @return Api
     */
    public function updateApi(Api $api, string $name, string $description): Api
    {
        $api->setName($name)->setDescription($description);
        $this->entityManager->persist($api);
        return $api;
    }

    /**
     * Deletes an api
     *
     * @param Api $api
     * @return bool
     */
    public function deleteApi(Api $api): bool
    {
        $api->setDeleted(true);
        $this->entityManager->flush();
        return true;
    }
}