<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationController extends AbstractFOSRestController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Rest\Route("/register", name="register", methods={"POST"})
     * @Rest\QueryParam(name="email", requirements=@Assert\Email, nullable=false, strict=true, description="User email")
     * @Rest\QueryParam(name="password", requirements="\w+", nullable=false, strict=true, description="User password")
     * @ParamConverter("user", converter="fos_rest.request_body", options={"deserializationContext": {"groups": {"deserialize"}}})
     */
    public function register(User $user): View
    {
        $user = $this->userService->register($user);

        return $this->view($user, Response::HTTP_CREATED);
    }
}
