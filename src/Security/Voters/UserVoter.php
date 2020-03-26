<?php

namespace App\Security\Voters;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class UserVoter extends Voter
{
    const SEE = 'seeUsers';
    const EDIT = 'editUser';
    
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::SEE, self::EDIT])) {
            return false;
        }

        // only vote on `Post` objects
        if ($subject != null && !$subject instanceof User) {
            return false;
        }
        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        switch ($attribute) {
            case self::SEE:
                return $this->canSeeUsers();
            case self::EDIT:
                return $this->canEditUser();
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canSeeUsers()
    {
        if ($this->security->isGranted('ROLE_ADMIN'))
        {
            return true;
        }
    }
    
    private function canEditUser()
    {
        // if they can see, they can edit
        if ($this->canSeeUsers()) {
            return true;
        }
    }
    
}