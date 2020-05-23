<?php

namespace App\Controller;

use App\Entity\SubGroup;
use App\Entity\Platform;
use App\Repository\SubGroupRepository;
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
 * Class SubGroupController.
 *
 * @SWG\Tag(name="SubGroups")
 * @Security(name="Bearer")
 *
 * @Rest\Route("sub-groups")
 * @Rest\View(serializerGroups={"sub-group:read", "id", "big-group"})
 */
class SubGroupController extends AbstractFOSRestController
{
    private SubGroupRepository $subGroupRepository;
    private EntityManagerInterface $em;

    public function __construct(
        SubGroupRepository $subGroupRepository,
        EntityManagerInterface $em
    ) {
        $this->subGroupRepository = $subGroupRepository;
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
     *         @SWG\Items(ref=@Model(type=SubGroup::class, groups={"sub-group:read", "id", "big-group"}))
     *     )
     * )
     *
     * @Rest\Get("")
     */
    public function list(): View
    {
        $subGroups = $this->subGroupRepository->findAll();

        return $this->view($subGroups, Response::HTTP_OK);
    }

    /**
     * Get object.
     * This call takes into account one object.
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns one object",
     *     @Model(type=SubGroup::class, groups={"sub-group:read", "id", "big-group"})
     * )
     *
     * @Rest\Get("/{id}")
     */
    public function one(SubGroup $subGroup): View
    {
        return $this->view($subGroup, Response::HTTP_OK);
    }

    /**
     * Create object.
     * This call creates a new object.
     *
     * @SWG\Parameter(name="object", in="body", @Model(type=SubGroup::class, groups={"sub-group:write"}), description="Fields of object")
     * @SWG\Response(
     *     response=201,
     *     description="Returns created object",
     *     @Model(type=SubGroup::class, groups={"sub-group:read", "id", "big-group"})
     * )
     *
     * @Rest\Post("")
     * @ParamConverter("subGroup", converter="fos_rest.request_body", options={"deserializationContext": {"groups": {"sub-group:write"}}})
     */
    public function post(SubGroup $subGroup, ConstraintViolationList $validationErrors): View
    {
        if ($validationErrors->count()) {
            return $this->view($validationErrors, 400);
        }

        $this->em->persist($subGroup);
        $this->em->flush();

        return $this->view($subGroup, Response::HTTP_CREATED);
    }

    /**
     * Update object.
     * This call updates the object.
     *
     * @SWG\Parameter(name="object", in="body", @Model(type=SubGroup::class, groups={"sub-group:write"}), description="Fields of object")
     * @SWG\Response(
     *     response=200,
     *     description="Returns updated object",
     *     @Model(type=SubGroup::class, groups={"sub-group:read", "id", "big-group"})
     * )
     * @SWG\Response(
     *     response=403,
     *     description="Access Denied."
     * )
     *
     * @Rest\Put("/{id}")
     * @ParamConverter("subGroup", converter="fos_rest.request_body", options={"deserializationContext": {"groups": {"sub-group:write"}}})
     */
    public function put(SubGroup $subGroup, ConstraintViolationList $validationErrors): View
    {
        if ($validationErrors->count()) {
            return $this->view($validationErrors, 400);
        }

        $this->em->persist($subGroup);
        $this->em->flush();

        return $this->view($subGroup, Response::HTTP_OK);
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
    public function delete(SubGroup $subGroup): View
    {
        $this->em->remove($subGroup);
        $this->em->flush();

        return $this->view([], Response::HTTP_OK);
    }
}
