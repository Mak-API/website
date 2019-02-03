<?php

namespace App\Service;


use App\Entity\PaymentPlan;
use App\Repository\PaymentPlanRepository;
use Doctrine\ORM\EntityManager;

class PaymentService
{
    /**
     * @var PaymentPlanRepository
     */
    private $paymentServiceRepository;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(PaymentPlanRepository $paymentPlanRepository, EntityManager $entityManager)
    {
        $this->paymentServiceRepository = $paymentPlanRepository;
        $this->entityManager = $entityManager;
    }

    public function getPaymentPlan(int $id)
    {
        return $this->paymentServiceRepository->find($id);
    }

    public function getPaymentPlans()
    {
        return $this->paymentServiceRepository->findAll();
    }

    /**
     * Creates a payment plan.
     *
     * @param string $name
     * @param string $description
     * @param int $requests
     * @param int $periodicity
     * @return PaymentPlan
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createPaymentPlan(string $name, string $description, int $requests, int $periodicity)
    {
        $paymentPlan = new PaymentPlan();
        $paymentPlan->setName($name)
            ->setDescription($description)
            ->setRequests($requests)
            ->setPeriodicity($periodicity);

        $this->entityManager->persist($paymentPlan);
        $this->entityManager->flush($paymentPlan);

        return $paymentPlan;
    }

    /**
     * Updates a payment plan.
     *
     * @param PaymentPlan $paymentPlan
     * @param string $name
     * @param string $description
     * @param int $requests
     * @param int $periodicity
     * @throws \Doctrine\ORM\ORMException
     */
    public function updatePaymentPlan(PaymentPlan $paymentPlan, string $name, string $description, int $requests, int $periodicity)
    {
        $paymentPlan->setName($name)
            ->setDescription($description)
            ->setRequests($requests)
            ->setPeriodicity($periodicity);

        $this->entityManager->persist($paymentPlan);
    }

    /**
     * Returns the free payment plan.
     *
     * @return PaymentPlan
     */
    public function getFreePaymentPlan()
    {
        return $this->paymentServiceRepository->find(1);
    }
}