<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 2019-02-04
 * Time: 11:41
 */

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;

class UserListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if($entity instanceof User){
            $entity->setStatus('1');
            $entity->setRoles(array('ROLE_UNVERIFIED'));
        }
    }
}