<?php

namespace App\Controller;

use App\Entity\Lesson;
use App\Entity\Platform;
use App\Repository\LessonRepository;
use App\Repository\PlatformRepository;
use App\Repository\UserRepository;
use App\Service\LessonService;
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
 * Class LessonController.
 *
 * @SWG\Tag(name="Lessons")
 * @Security(name="Bearer")
 *
 * @Rest\Route("lessons")
 * @Rest\View(serializerGroups={"lesson:read", "id", "platform", "non_sensitive_data"})
 */
class LessonController extends AbstractFOSRestController
{
    private LessonRepository $lessonRepository;
    private LessonService $lessonService;
    private EntityManagerInterface $em;

    public function __construct(
        LessonRepository $lessonRepository,
        EntityManagerInterface $em,
        LessonService $lessonService
    ) {
        $this->lessonRepository = $lessonRepository;
        $this->em = $em;
        $this->lessonService = $lessonService;
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
     *         @SWG\Items(ref=@Model(type=Lesson::class, groups={"id", "lesson"}))
     *     )
     * )
     *
     * @Rest\Get("")
     * @Rest\QueryParam(name="user", requirements=@Assert\Positive, description="Master of events")
     * @Rest\QueryParam(name="platform", requirements=@Assert\Positive, description="Platform of events")
     * @Rest\QueryParam(name="sub-group", requirements=@Assert\Positive, description="Sub group of events")
     * @Rest\QueryParam(name="week", requirements=@Assert\Positive, description="Week number")
     * @Rest\QueryParam(name="year", requirements=@Assert\Positive, description="Year number")
     */
    public function list(ParamFetcherInterface $paramFetcher): View
    {
        $lessons = $this->lessonService->filteredSearch(
            (int)$paramFetcher->get('user'),
            (int)$paramFetcher->get('platform'),
            (int)$paramFetcher->get('sub-group'),
            (int)$paramFetcher->get('week'),
            (int)$paramFetcher->get('year'),
        );

        return $this->view($lessons, Response::HTTP_OK);
    }

    /**
     * Get object.
     * This call takes into account one object.
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns one object",
     *     @Model(type=Lesson::class, groups={"id", "lesson"})
     * )
     *
     * @Rest\Get("/{id}")
     */
    public function one(Lesson $lesson): View
    {
        return $this->view($lesson, Response::HTTP_OK);
    }

    /**
     * Create object.
     * This call creates a new object.
     *
     * @SWG\Parameter(name="object", in="body", @Model(type=Lesson::class, groups={"lesson:write", "id_related_entity"}), description="Fields of object")
     * @SWG\Response(
     *     response=201,
     *     description="Returns created object",
     *     @Model(type=Lesson::class, groups={"lesson:read", "id", "platform", "non_sensitive_data"})
     * )
     *
     * @Rest\Post("")
     * @ParamConverter("lesson", converter="fos_rest.request_body", options={"deserializationContext": {"groups": {"lesson:write", "id_related_entity"}}})
     */
    public function post(Lesson $lesson, ConstraintViolationList $validationErrors): View
    {
        if ($validationErrors->count()) {
            return $this->view($validationErrors, 400);
        }

        $lesson->setMaster($this->getUser());

        $this->em->persist($lesson);
        $this->em->flush();

        return $this->view($lesson, Response::HTTP_CREATED);
    }

    /**
     * Update object.
     * This call updates the object.
     *
     * @SWG\Parameter(name="object", in="body", @Model(type=Lesson::class, groups={"lesson:write", "id_related_entity"}), description="Fields of object")
     * @SWG\Response(
     *     response=200,
     *     description="Returns updated object",
     *     @Model(type=Lesson::class, groups={"lesson:read", "id", "platform", "non_sensitive_data"})
     * )
     * @SWG\Response(
     *     response=403,
     *     description="Access Denied."
     * )
     *
     * @Rest\Put("/{id}")
     * @ParamConverter("lesson", converter="fos_rest.request_body", options={"deserializationContext": {"groups": {"lesson:write", "id_related_entity"}}})
     */
    public function put(Lesson $lesson, ConstraintViolationList $validationErrors): View
    {
        $this->denyAccessUnlessGranted('edit', $lesson);

        if ($validationErrors->count()) {
            return $this->view($validationErrors, 400);
        }

        $this->em->persist($lesson);
        $this->em->flush();

        return $this->view($lesson, Response::HTTP_OK);
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
    public function delete(Lesson $lesson): View
    {
        $this->em->remove($lesson);
        $this->em->flush();

        return $this->view([], Response::HTTP_OK);
    }
}
