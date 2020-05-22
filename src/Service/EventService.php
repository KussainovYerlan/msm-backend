<?php

namespace App\Service;

use App\Entity\Event;
use App\Entity\Platform;
use App\Entity\User;
use App\Repository\PlatformRepository;
use App\Repository\UserRepository;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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


    public function create(
        int $platformId, 
        string $startingAt, 
        string $description,
        string $name,
        array $participants,
        User $master
    ): Event {
        $event = new Event();
        $event->setStartingAt(new \DateTime($startingAt));
        $event->setDescription($description);
        $event->setName($name);
        $event->setMaster($master);

        foreach($participants as $participantId) {
            $participant = $this->userRepository->find($participantId);
            $event->addParticipant($participant);
        }

        $platform = $this->platformRepository->find($platformId);
        $event->setPlatform($platform);

        $this->em->persist($event);
        $this->em->flush();

        return $event;
    }

    public function edit(
        Event $event,
        int $platformId, 
        string $startingAt, 
        string $description,
        string $name,
        array $participants
    ): Event {
        $event->setStartingAt(new \DateTime($startingAt));
        $event->setDescription($description);
        $event->setName($name);

        foreach($participants as $participantId) {
            $participant = $this->userRepository->find($participantId);
            $event->addParticipant($participant);
        }

        $platform = $this->platformRepository->find($platformId);
        $event->setPlatform($platform);

        $this->em->persist($event);
        $this->em->flush();

        return $event;
    }
}
