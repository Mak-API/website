<?php
/**
 * Created by PhpStorm.
 * User: Backins
 * Date: 21/02/2019
 * Time: 20:58
 */

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class UserVoter extends Voter
{
    /**
     * @var Security
     */
    private $security;

    const SHOW = 'SHOW_PROFILE';
    const EDIT = 'EDIT_PROFILE';

    /**
     * UserVoter constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param string $attribute
     * @param User $targettedUser
     * @return bool
     */
    protected function supports($attribute, $targettedUser): bool
    {
        if (!in_array($attribute, [self::SHOW, self::EDIT])) {
            return false;
        }
        if (!$targettedUser instanceof User) {
            return false;
        }
        return true;
    }

    /**
     * @param string $attribute
     * @param mixed $targettedUser
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $targettedUser, TokenInterface $token): bool
    {
        // If we want ROLE_SUPER_ADMIN can do anything => uncomment
        /*if ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
            return true;
        }*/

        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        switch ($attribute) {
            case self::SHOW:
                return $this->canShow($targettedUser, $user);
            case self::EDIT:
                return $this->canEdit($targettedUser, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    /**
     * @param User $targettedUser
     * @param User $user
     * @return bool
     */
    public function canShow(User $targettedUser, User $user): bool
    {
        return $user === $targettedUser;
    }

    /**
     * @param User $targettedUser
     * @param User $user
     * @return bool
     */
    private function canEdit(User $targettedUser, User $user): bool
    {
        return $user === $targettedUser;
    }
}