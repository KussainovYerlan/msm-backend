<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\Constraints as Assert;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
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

    public function __construct(
        EventRepository $eventRepository,
        EntityManagerInterface $em    
    ) {
        $this->eventRepository = $eventRepository;
        $this->em = $em;
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
     * @SWG\Parameter(name="object", in="body", @Model(type=Event::class, groups={"deserialize"}), description="Fields of object")
     * @SWG\Response(
     *     response=200,
     *     description="Returns created object",
     *     @Model(type=Event::class, groups={"id", "event"})
     * )
     * 
     * @Rest\Post("")
     * @ParamConverter("event", converter="fos_rest.request_body", options={"deserializationContext": {"groups": {"deserialize"}}})
     */
    public function post(Event $event, ConstraintViolationList $validationErrors): View
    {
        if ($validationErrors->count()) {
            return $this->view($validationErrors, 400);
        }

        $this->em->persist($event);
        $this->em->flush();

        return $this->view($event, Response::HTTP_CREATED);
    }

    /**
     * Update object.
     * This call updates the object.
     * @SWG\Parameter(name="object", in="body", @Model(type=Event::class, groups={"deserialize"}), description="Fields of object")
     * @SWG\Response(
     *     response=200,
     *     description="Returns one object",
     *     @Model(type=Event::class, groups={"id", "event"})
     * )
     * 
     * @Rest\Put("/{id}")
     * @ParamConverter("event", converter="fos_rest.request_body", options={"deserializationContext": {"groups": {"deserialize"}}})
     */
    public function put(Event $event, ConstraintViolationList $validationErrors): View
    {
        if ($validationErrors->count()) {
            return $this->view($validationErrors, 400);
        }

        $this->em->persist($event);
        $this->em->flush();

        return $this->view($event, Response::HTTP_CREATED);
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
