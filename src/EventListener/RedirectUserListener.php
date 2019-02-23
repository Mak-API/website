<?php
/**
 * Created by PhpStorm.
 * User: Backins
 * Date: 21/02/2019
 * Time: 23:50
 */

namespace App\EventListener;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use App\Entity\User;

class RedirectUserListener
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;
    /**
     * @var RouterInterface
     */
    private $routerInterface;

    /**
     * RedirectUserListener constructor.
     * @param TokenStorage $tokenStorage
     * @param RouterInterface $routerInterface
     */
    public function __construct(TokenStorage $tokenStorage, RouterInterface $routerInterface)
    {
        $this->tokenStorage = $tokenStorage;
        $this->routerInterface = $routerInterface;
    }

    /**
     * @param GetResponseEvent $responseEvent
     */
    public function onKernelRequest(GetResponseEvent $responseEvent): void
    {
        if($this->isUserLogged() && $responseEvent->isMasterRequest()){
            $currentPath = $responseEvent->getRequest()->attributes->get('_route');
            if($this->isOnAnonymousPage($currentPath)){
                $response = new RedirectResponse($this->routerInterface->generate('app_default_index'));
                $responseEvent->setResponse($response);
            }
        }
    }

    /**
     * @return bool
     */
    private function isUserLogged(): bool
    {
        if(!$this->tokenStorage->getToken()){
            return false;
        }
        return $this->tokenStorage->getToken()->getUser() instanceof User;
    }

    /**
     * @param string $currentPath
     * @return bool
     */
    private function isOnAnonymousPage(string $currentPath): bool
    {
        return in_array(
            $currentPath,
            [
                'app_security_login',
                'app_user_new',
                'app_registration_confirm_registration',
                'app_registration_reset_password',
                'app_registration_send_confirmation_email',
            ]
        );
    }

}