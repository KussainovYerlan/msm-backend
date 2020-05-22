<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Class RegistrationController.
 *
 * @SWG\Tag(name="Register")
 * @Rest\Route("register")
 *
 * @Rest\View(serializerGroups={"id", "non_sensitive_data"})
 */
class RegistrationController extends AbstractFOSRestController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Register user.
     * This call creates a new object.
     *
     * @SWG\Parameter(name="object", in="body", @Model(type=User::class, groups={"deserialize"}), description="Fields of object")
     * @SWG\Response(
     *     response=201,
     *     description="Returns created object",
     *     @Model(type=User::class, groups={"id", "non_sensitive_data"})
     * )
     *
     * @Rest\Post("")
     * @ParamConverter("user", converter="fos_rest.request_body", options={"deserializationContext": {"groups": {"deserialize"}}})
     */
    public function register(User $user, ConstraintViolationList $validationErrors): View
    {
        if ($validationErrors->count()) {
            return $this->view($validationErrors, 400);
        }

        $user = $this->userService->register($user);

        return $this->view($user, Response::HTTP_CREATED);
    }
}
