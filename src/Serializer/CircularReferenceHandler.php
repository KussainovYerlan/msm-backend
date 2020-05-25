<?php

namespace App\Serializer;

use App\Entity\SubGroup;
use Symfony\Component\Routing\RouterInterface;

class CircularReferenceHandler
{
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function __invoke($object)
    {
        switch ($object) {
            case $object instanceof SubGroup:
                return $this->router->generate("app_subgroup_list", ["list" => $object->getId()]);
        }

        return $object->getId();
    }
}