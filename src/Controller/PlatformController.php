<?php

namespace App\Controller;

use App\Entity\Platform;
use App\Repository\PlatformRepository;
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

/**
 * Class PlatformController
 * @SWG\Tag(name="Platforms")
 * @Security(name="Bearer")
 * 
 * @Rest\Route("platforms")
 * @Rest\View(serializerGroups={"id", "platform"})
 */
class PlatformController extends AbstractFOSRestController
{
    private PlatformRepository $platformRepository;
    private EntityManagerInterface $em;

    public function __construct(
        PlatformRepository $platformRepository,
        EntityManagerInterface $em    
    ) {
        $this->platformRepository = $platformRepository;
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
     *         @SWG\Items(ref=@Model(type=Platform::class, groups={"id", "platform"}))
     *     )
     * )
     * 
     * @Rest\Get("")
     */
    public function list(): View
    {
        $platforms = $this->platformRepository->findAll();

        return $this->view($platforms, Response::HTTP_OK);
    }

    /**
     * Get object.
     * This call takes into account one object.
     * @SWG\Response(
     *     response=200,
     *     description="Returns one object",
     *     @Model(type=Platform::class, groups={"id", "platform"})
     * )
     * 
     * @Rest\Get("/{id}")
     */
    public function one(Platform $platform): View
    {
        return $this->view($platform, Response::HTTP_OK);
    }

    /**
     * Create object.
     * This call creates a new object.
     * @SWG\Parameter(name="object", in="body", @Model(type=Platform::class, groups={"deserialize"}), description="Fields of object")
     * @SWG\Response(
     *     response=201,
     *     description="Returns created object",
     *     @Model(type=Platform::class, groups={"id", "platform"})
     * )
     * 
     * @Rest\Post("")
     * @ParamConverter("platform", converter="fos_rest.request_body", options={"deserializationContext": {"groups": {"deserialize"}}})
     */
    public function post(Platform $platform): View
    {
        $this->em->persist($platform);
        $this->em->flush();

        return $this->view($platform, Response::HTTP_CREATED);
    }

    /**
     * Update object.
     * This call updates the object.
     * @SWG\Parameter(name="object", in="body", @Model(type=Platform::class, groups={"deserialize"}), description="Fields of object")
     * @SWG\Response(
     *     response=200,
     *     description="Returns one object",
     *     @Model(type=Platform::class, groups={"id", "platform"})
     * )
     * 
     * @Rest\Put("/{id}")
     * @ParamConverter("platform", converter="fos_rest.request_body", options={"deserializationContext": {"groups": {"deserialize"}}})
     */
    public function put(Platform $platform): View
    {
        $this->em->persist($platform);
        $this->em->flush();

        return $this->view($platform, Response::HTTP_OK);
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
    public function delete(Platform $platform): View
    {
        $this->em->remove($platform);
        $this->em->flush();

        return $this->view([], Response::HTTP_OK);
    }
}
