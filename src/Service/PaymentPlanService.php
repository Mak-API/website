<?php
namespace App\Service;
use App\Entity\PaymentPlan;
use App\Repository\PaymentPlanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
class PaymentPlanService
{
    /**
     * @var PaymentPlanRepository
     */
    private $paymentServiceRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    public function __construct(PaymentPlanRepository $paymentPlanRepository, EntityManagerInterface $entityManager)
    {
        $this->paymentServiceRepository = $paymentPlanRepository;
        $this->entityManager = $entityManager;
    }
    public function getPaymentPlan(int $apiId)
    {
        return $this->paymentServiceRepository->find($apiId);
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
    public function createPaymentPlan(string $name, string $description, int $requests, int $periodicity, UserInterface $creator)
    {
        $paymentPlan = new PaymentPlan();
        $paymentPlan->setName($name)
            ->setDescription($description)
            ->setRequests($requests)
            ->setPeriodicity($periodicity)
            ->setCreatedBy($creator);
        $this->entityManager->persist($paymentPlan);
        $this->entityManager->flush();
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