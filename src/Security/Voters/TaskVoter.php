<?php

namespace App\Security\Voters;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class TaskVoter extends Voter
{
    const SEE = 'seeTask';
    const CREATE = 'createTask';
    const DELETE = 'deleteTask';
    const EDIT = 'editTask';
    
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::SEE, self::CREATE, self::DELETE, self::EDIT])) {
            return false;
        }

        // only vote on `Post` objects
        if ($subject != null && !$subject instanceof Task) {
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

        // you know $task is a Task object, thanks to `supports()`
        
        $task = $subject;

        switch ($attribute) {
            case self::SEE:
                return $this->canSee();
            case self::CREATE:
                return $this->canCreate();
            case self::DELETE:
                return $this->canDelete($task, $user);
            case self::EDIT:
                return $this->canEdit($task, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canSee()
    {
        if ($this->security->isGranted('ROLE_USER','ROLE_ADMIN'))
        {
            return true;
        }
    }
    
    private function canCreate()
    {
        if ($this->security->isGranted('ROLE_USER','ROLE_ADMIN'))
        {
            return true;
        }
    }
    
    private function canDelete(Task $task, User $user)
    {
        if ($task->getUser() == $user || ($task->getUser()->getUsername() == "ANONYMOUS" && $this->security->isGranted('ROLE_ADMIN')))
        {
            return true;
        }
    }

    private function canEdit(Task $task, User $user)
    {
        // if they can delete, they can edit
        if ($this->canDelete($task, $user)) {
            return true;
        }
    }
    
}