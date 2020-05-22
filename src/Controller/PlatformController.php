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

    public function getPlatformsAction(): View
    {
        $platforms = $this->platformRepository->findAll();

        return $this->view($platforms, Response::HTTP_OK);
    }

    public function getPlatformAction(Platform $platform): View
    {
        return $this->view($platform, Response::HTTP_OK);
    }

    /**
     * @ParamConverter("platform", converter="fos_rest.request_body", options={"deserializationContext": {"groups": {"deserialize"}}})
     */
    public function postPlatformsAction(Platform $platform): View
    {
        $this->em->persist($platform);
        $this->em->flush();

        return $this->view($platform, Response::HTTP_CREATED);
    }

    /**
     * @ParamConverter("platform", converter="fos_rest.request_body", options={"deserializationContext": {"groups": {"deserialize"}}})
     */
    public function putPlatformsAction(Platform $platform): View
    {
        $this->em->persist($platform);
        $this->em->flush();

        return $this->view($platform, Response::HTTP_CREATED);
    }

    public function deletePlatformsAction(Platform $platform): View
    {
        $this->em->remove($platform);
        $this->em->flush();

        return $this->view([], Response::HTTP_OK);
    }
}
