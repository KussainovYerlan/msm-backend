<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    private EntityManagerInterface $em;
    private UserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(
        EntityManagerInterface $em,
        UserRepository $userRepository,
        UserPasswordEncoderInterface $userPasswordEncoder
    ) {
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function register(User $user): User
    {
        $user->setPassword($this->userPasswordEncoder->encodePassword($user, $user->getPassword()));
        $user->setStatus(User::STATUS_ACTIVE);

        $this->save($user);

        return $user;
    }

    public function save(User $user)
    {
        $this->em->persist($user);
        $this->em->flush();
    }
}
