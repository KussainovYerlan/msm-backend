<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Platform;
use App\Repository\EventRepository;
use App\Service\EventService;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class EventController.
 *
 * @SWG\Tag(name="Events")
 * @Security(name="Bearer")
 *
 * @Rest\Route("events")
 * @Rest\View(serializerGroups={"event:read", "platform:read", "user:read"})
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
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns list of objects",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Event::class, groups={"event:read", "platform:read", "user:read"}))
     *     )
     * )
     * 
     * @Rest\Get("")
     * @Rest\QueryParam(name="user", requirements=@Assert\Positive, description="Master of events")
     * @Rest\QueryParam(name="platform", requirements=@Assert\Positive, description="Platform of events")
     * @Rest\QueryParam(name="week", requirements=@Assert\Positive, description="Week number")
     * @Rest\QueryParam(name="year", requirements=@Assert\Positive, description="Year number")
     */
    public function list(ParamFetcherInterface $paramFetcher): View
    {
        $events = $this->eventService->filteredSearch(
            (int)$paramFetcher->get('user'),
            (int)$paramFetcher->get('platform'),
            (int)$paramFetcher->get('week'),
            (int)$paramFetcher->get('year')
        );

        return $this->view($events, Response::HTTP_OK);
    }

    /**
     * Get object.
     * This call takes into account one object.
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns one object",
     *     @Model(type=Event::class, groups={"event:read", "platform:read", "user:read"})
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
     *
     * @SWG\Parameter(name="object", in="body", @Model(type=Event::class, groups={"event:write"}), description="Fields of object")
     * @SWG\Response(
     *     response=201,
     *     description="Returns created object",
     *     @Model(type=Event::class, groups={"event:read", "platform:read", "user:read"})
     * )
     *
     * @Rest\Post("")
     * @ParamConverter("event", converter="fos_rest.request_body", options={"deserializationContext": {"groups": {"event:write"}}})
     */
    public function post(Event $event, ConstraintViolationList $validationErrors): View
    {
        if ($validationErrors->count()) {
            return $this->view($validationErrors, 400);
        }

        $event->setMaster($this->getUser());

        $this->em->persist($event);
        $this->em->flush();

        return $this->view($event, Response::HTTP_CREATED);
    }

    /**
     * Update object.
     * This call updates the object.
     *
     * @SWG\Parameter(name="object", in="body", @Model(type=Event::class, groups={"event:write"}), description="Fields of object")
     * @SWG\Response(
     *     response=200,
     *     description="Returns updated object",
     *     @Model(type=Event::class, groups={"event:read", "platform:read", "user:read"})
     * )
     * @SWG\Response(
     *     response=403,
     *     description="Access Denied."
     * )
     *
     * @Rest\Put("/{id}")
     * @ParamConverter("event", converter="fos_rest.request_body", options={"deserializationContext": {"groups": {"event:write"}}})
     */
    public function put(Event $event, ConstraintViolationList $validationErrors): View
    {
        $this->denyAccessUnlessGranted('edit', $event);

        if ($validationErrors->count()) {
            return $this->view($validationErrors, 400);
        }

        $this->em->persist($event);
        $this->em->flush();

        return $this->view($event, Response::HTTP_OK);
    }

    /**
     * Delete object.
     * This call removes the object.
     *
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
