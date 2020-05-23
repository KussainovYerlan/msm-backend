<?php

namespace App\Service;

use App\Entity\Event;
use App\Repository\EventRepository;
use App\Repository\PlatformRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class WeekService
{
    public function weekInfo($week, $year) {
        $date = (new DateTime())->setISODate($year, $week);
        
        return [
            'number' => $week,
            'startDate' => $date->format('Y-m-d'),
            'endDate' => $date->modify('+6 days')->format('Y-m-d'),
        ];
    }
}
