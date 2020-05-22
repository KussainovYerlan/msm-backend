<?php

namespace App\Controller;

use App\Entity\BigGroup;
use App\Repository\BigGroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Class BigGroupController.
 *
 * @SWG\Tag(name="Big groups")
 * @Security(name="Bearer")
 *
 * @Rest\Route("big-groups")
 * @Rest\View(serializerGroups={"id", "big-group"})
 */
class BigGroupController extends AbstractFOSRestController
{
    private BigGroupRepository $bigGroupRepository;
    private EntityManagerInterface $em;

    public function __construct(
        BigGroupRepository $bigGroupRepository,
        EntityManagerInterface $em
    ) {
        $this->bigGroupRepository = $bigGroupRepository;
        $this->em = $em;
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
     *         @SWG\Items(ref=@Model(type=BigGroup::class, groups={"id", "big-group"}))
     *     )
     * )
     *
     * @Rest\Get("")
     */
    public function getBigGroupsAction(): View
    {
        $bigGroups = $this->bigGroupRepository->findAll();

        return $this->view($bigGroups, Response::HTTP_OK);
    }

    /**
     * Get object.
     * This call takes into account one object.
     *
     * @SWG\Parameter(name="id", in="query", type="number", description="Id of object")
     * @SWG\Response(
     *     response=200,
     *     description="Returns one object",
     *     @Model(type=BigGroup::class, groups={"id", "big-group"})
     * )
     *
     * @Rest\Get("/{big-group}")
     */
    public function getBigGroupAction(BigGroup $bigGroup): View
    {
        return $this->view($bigGroup, Response::HTTP_OK);
    }

    /**
     * Create object.
     * This call creates a new object.
     *
     * @SWG\Parameter(name="object", in="body", @Model(type=BigGroup::class, groups={"deserialize"}), description="Fields of object")
     * @SWG\Response(
     *     response=200,
     *     description="Returns created object",
     *     @Model(type=BigGroup::class, groups={"id", "big-group"})
     * )
     *
     * @Rest\Post("")
     * @ParamConverter("bigGroup", converter="fos_rest.request_body", options={"deserializationContext": {"groups": {"deserialize"}}})
     */
    public function postBigGroupsAction(BigGroup $bigGroup, ConstraintViolationList $validationErrors): View
    {
        if ($validationErrors->count()) {
            return $this->view($validationErrors, 400);
        }

        $this->em->persist($bigGroup);
        $this->em->flush();

        return $this->view($bigGroup, Response::HTTP_CREATED);
    }

    /**
     * Update object.
     * This call updates the object.
     *
     * @SWG\Parameter(name="id", in="query", type="number", description="Id of object")
     * @SWG\Parameter(name="object", in="body", @Model(type=BigGroup::class, groups={"deserialize"}), description="Fields of object")
     * @SWG\Response(
     *     response=200,
     *     description="Returns one object",
     *     @Model(type=BigGroup::class, groups={"id", "big-group"})
     * )
     *
     * @Rest\Put("/{big-group}")
     * @ParamConverter("bigGroup", converter="fos_rest.request_body", options={"deserializationContext": {"groups": {"deserialize"}}})
     */
    public function putBigGroupsAction(BigGroup $bigGroup, ConstraintViolationList $validationErrors): View
    {
        if ($validationErrors->count()) {
            return $this->view($validationErrors, 400);
        }

        $this->em->persist($bigGroup);
        $this->em->flush();

        return $this->view($bigGroup, Response::HTTP_CREATED);
    }

    /**
     * Delete object.
     * This call removes the object.
     *
     * @SWG\Parameter(name="id", in="query", type="number", description="Id of object")
     * @SWG\Response(
     *     response=200,
     *     description="Returns empty response"
     * )
     *
     * @Rest\Delete("/{big-group}")
     */
    public function deleteBigGroupsAction(BigGroup $bigGroup): View
    {
        $this->em->remove($bigGroup);
        $this->em->flush();

        return $this->view([], Response::HTTP_OK);
    }
}
