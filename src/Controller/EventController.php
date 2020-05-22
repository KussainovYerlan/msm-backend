<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Platform;
use App\Repository\EventRepository;
use App\Service\EventService;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Class EventController
 * @SWG\Tag(name="Events")
 * @Security(name="Bearer")
 * 
 * @Rest\Route("events")
 * @Rest\View(serializerGroups={"id", "event"})
 */
class EventController extends AbstractFOSRestController
{
    private EventRepository $eventRepository;
    private EntityManagerInterface $em;
    private EventService $eventService;

    public function __construct(
        EventRepository $eventRepository,
        EntityManagerInterface $em,
        EventService $eventService
    ) {
        $this->eventRepository = $eventRepository;
        $this->em = $em;
        $this->eventService = $eventService;
    }

    /**
     * List of objects.
     * This call takes into account all objects.
     * @SWG\Response(
     *     response=200,
     *     description="Returns list of objects",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Event::class, groups={"id", "event"}))
     *     )
     * )
     * 
     * @Rest\Get("")
     */
    public function list(): View
    {
        $events = $this->eventRepository->findAll();

        return $this->view($events, Response::HTTP_OK);
    }

    /**
     * Get object.
     * This call takes into account one object.
     * @SWG\Response(
     *     response=200,
     *     description="Returns one object",
     *     @Model(type=Event::class, groups={"id", "event"})
     * )
     * 
     * @Rest\Get("/{id}")
     */
    public function one(Event $event): View
    {
        return $this->view($event, Response::HTTP_OK);
    }

    /**
     * Create object.
     * This call creates a new object.
     * @SWG\Response(
     *     response=201,
     *     description="Returns created object",
     *     @Model(type=Event::class, groups={"id", "event"})
     * )
     * 
     * @Rest\Post("")
     * 
     * @RequestParam(name="platform", requirements=@Assert\Positive, nullable=false, strict=true, description="Platform id")
     * @RequestParam(name="startingAt", nullable=false, strict=true, description="Event date and time of start")
     * @RequestParam(name="description", requirements=@Assert\Length(min = 2, max = 2048), nullable=false, strict=true, description="Event desciption")
     * @RequestParam(name="name", requirements=@Assert\Length(min = 2, max = 255), nullable=false, strict=true, description="Event name")
     * @RequestParam(name="participants", nullable=false, strict=true, description="Platform id")
     */
    public function post(ParamFetcherInterface $paramFetcher): View
    {
        $event = $this->eventService->create(
            $paramFetcher->get('platform'),
            $paramFetcher->get('startingAt'),
            $paramFetcher->get('description'),
            $paramFetcher->get('name'),
            $paramFetcher->get('participants'),
            $this->getUser()
        );

        return $this->view($event, Response::HTTP_CREATED);
    }

    /**
     * Update object.
     * This call updates the object.
     * @SWG\Response(
     *     response=200,
     *     description="Returns one object",
     *     @Model(type=Event::class, groups={"id", "event"})
     * )
     * @SWG\Response(
     *     response=403,
     *     description="Access Denied."
     * )
     * 
     * @Rest\Put("/{id}")
     * @RequestParam(name="platform", requirements=@Assert\Positive, nullable=false, strict=true, description="Platform id")
     * @RequestParam(name="startingAt", nullable=false, strict=true, description="Event date and time of start")
     * @RequestParam(name="description", requirements=@Assert\Length(min = 2, max = 2048), nullable=false, strict=true, description="Event desciption")
     * @RequestParam(name="name", requirements=@Assert\Length(min = 2, max = 255), nullable=false, strict=true, description="Event name")
     * @RequestParam(name="participants", nullable=false, strict=true, description="Platform id")
     */
    public function put(Event $event, ParamFetcherInterface $paramFetcher): View
    {
        $this->denyAccessUnlessGranted('edit', $event);

        $event = $this->eventService->edit(
            $event,
            $paramFetcher->get('platform'),
            $paramFetcher->get('startingAt'),
            $paramFetcher->get('description'),
            $paramFetcher->get('name'),
            $paramFetcher->get('participants')
        );

        return $this->view($event, Response::HTTP_OK);
    }

    /**
     * Delete object.
     * This call removes the object.
     * @SWG\Response(
     *     response=200,
     *     description="Returns empty response"
     * )
     * 
     * @Rest\Delete("/{id}")
     */
    public function delete(Event $event): View
    {
        $this->em->remove($event);
        $this->em->flush();

        return $this->view([], Response::HTTP_OK);
    }
}
