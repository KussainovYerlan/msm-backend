<?php

declare(strict_types=1);

namespace App\FOSRestBundle\Request;

use FOS\RestBundle\Request\RequestBodyParamConverter as BaseRequestBodyParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RequestBodyParamConverter extends BaseRequestBodyParamConverter implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function apply(Request $request, ParamConverter $configuration)
    {
        $id = $request->attributes->get('id');

        if (null !== $id) {
            $entityManager = $this->container->get('doctrine')->getManager();
            $entity = $entityManager->getRepository($configuration->getClass())->find($id);

            if (null === $entity) {
                throw new NotFoundHttpException();
            }

            $options = $configuration->getOptions();
            $options['deserializationContext']['object_to_populate'] = $entity;
            $configuration->setOptions($options);
        }

        return parent::apply($request, $configuration);
    }
}
