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
            ->setUpdatedBy($createdBy)
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
     * @param UserInterface $updatedBy
     * @return Api
     */
    public function updateApi(Api $api, string $name, string $description, UserInterface $updatedBy): Api
    {
        $api->setName($name)
            ->setDescription($description)
            ->setUpdatedBy($updatedBy)
            ->setUpdatedAt(new \DateTime());
        $this->entityManager->flush();
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

    /**
     * @param Api $api
     * @param string $path
     * @return Api
     */
    public function setPath(Api $api, string $path): Api
    {
        $api->setPath($path);
        $this->entityManager->flush();
        return $api;
    }

    /**
     * @param Api $api
     * @param string $downloadLink
     * @return Api
     */
    public function setDownloadLink(Api $api, string $downloadLink): Api
    {
        $api->setDownloadLink($downloadLink);
        $this->entityManager->flush();
        return $api;
    }
}