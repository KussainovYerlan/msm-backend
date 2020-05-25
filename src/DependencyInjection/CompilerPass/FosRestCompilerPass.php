<?php

declare(strict_types=1);

namespace App\DependencyInjection\CompilerPass;

use App\FOSRestBundle\Request\RequestBodyParamConverter;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FosRestCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('fos_rest.converter.request_body')) {
            $definition = $container->getDefinition('fos_rest.converter.request_body');
            $definition->setClass(RequestBodyParamConverter::class);
            $definition->addMethodCall('setContainer', [new Reference('service_container')]);
        }
    }
}
