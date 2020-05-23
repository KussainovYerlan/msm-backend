<?php

namespace App\Service;

use App\Entity\Lesson;
use App\Repository\LessonRepository;
use App\Repository\PlatformRepository;
use App\Repository\SubGroupRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class LessonService
{
    private EntityManagerInterface $em;
    private UserRepository $userRepository;
    private PlatformRepository $platformRepository;
    private LessonRepository $lessonRepository;
    private SubGroupRepository $subGroupRepository;
    private WeekService $weekService;

    public function __construct(
        EntityManagerInterface $em,
        UserRepository $userRepository,
        PlatformRepository $platformRepository,
        LessonRepository $lessonRepository,
        SubGroupRepository $subGroupRepository,
        WeekService $weekService
    ) {
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->platformRepository = $platformRepository;
        $this->lessonRepository = $lessonRepository;
        $this->subGroupRepository = $subGroupRepository;
        $this->weekService = $weekService;
    }

    public function filteredSearch(
        int $userId,
        int $platformId,
        int $subGroupId,
        int $week,
        int $year
    ): Array {
        $lessonFilter = [
            'user' => $this->userRepository->find($userId),
            'platform' => $this->platformRepository->find($platformId),
            'subGroup' => $this->subGroupRepository->find($subGroupId),
            'date' => $week && $year ? $this->weekService->weekInfo($week, $year) : null,
        ];

        return $this->lessonRepository->filteredSearch($lessonFilter);
    }
}
