<?php

namespace App\Security;

use App\Entity\Lesson;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class LessonVoter extends Voter
{
    const EDIT = 'edit';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::EDIT])) {
            return false;
        }

        if (!$subject instanceof Lesson) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Lesson $lesson */
        $lesson = $subject;

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($lesson, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canEdit(Lesson $lesson, User $user)
    {
        return $user === $lesson->getMaster();
    }
}
