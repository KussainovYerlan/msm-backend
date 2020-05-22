<?php

declare(strict_types=1);

namespace App\Serializer;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\Mapping\MappingException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class DoctrineEntityDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    protected EntityManagerInterface $entityManager;

    protected DenormalizerInterface $serializer;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function setDenormalizer(DenormalizerInterface $denormalizer)
    {
        $this->serializer = $denormalizer;
    }

    /**
     * {@inheritdoc}
     *
     * @throws EntityNotFoundException
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if (!$entity = $this->entityManager->getRepository($class)->find($data['id'])) {
            throw EntityNotFoundException::fromClassNameAndIdentifier($class, [$data['id']]);
        }

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        try {
            $isEntity = $this->entityManager->getMetadataFactory()->getMetadataFor($type) instanceof ClassMetadata;
        } catch (MappingException $mappingException) {
            $isEntity = false;
        }

        return $isEntity && 1 === count($data) && array_key_exists('id', $data) && null !== $data['id'];
    }
}
