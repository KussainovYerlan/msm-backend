<?php

namespace App\Service;

use App\Repository\PlatformRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class EventService
{
    private EntityManagerInterface $em;
    private UserRepository $userRepository;
    private PlatformRepository $platformRepository;

    public function __construct(
        EntityManagerInterface $em,
        UserRepository $userRepository,
        PlatformRepository $platformRepository
    ) {
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->platformRepository = $platformRepository;
    }
}
