<?php

namespace App\Service;

use App\Entity\Event;
use App\Repository\EventRepository;
use App\Repository\PlatformRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class EventService
{
    private EntityManagerInterface $em;
    private UserRepository $userRepository;
    private PlatformRepository $platformRepository;
    private EventRepository $eventRepository;
    private WeekService $weekService;

    public function __construct(
        EntityManagerInterface $em,
        UserRepository $userRepository,
        PlatformRepository $platformRepository,
        EventRepository $eventRepository,
        WeekService $weekService
    ) {
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->platformRepository = $platformRepository;
        $this->eventRepository = $eventRepository;
        $this->weekService = $weekService;
    }

    public function filteredSearch(
        int $userId,
        int $platformId,
        int $week,
        int $year
    ): Array {
        $eventFilter = [
            'user' => $this->userRepository->find($userId),
            'platform' => $this->platformRepository->find($platformId),
            'date' => $week && $year? $this->weekService->weekInfo($week, $year) : null,
        ];

        return $this->eventRepository->filteredSearch($eventFilter);
    }
}
