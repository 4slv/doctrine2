<?php

declare(strict_types=1);

namespace Doctrine\ORM\Mapping\Driver\Annotation\Binder;

use Doctrine\ORM\Annotation;
use Doctrine\ORM\Mapping;

/**
 * Class MappedSuperClassMetadataBinder
 *
 * @package Doctrine\ORM\Mapping\Driver\Annotation\Binder
 * @since 3.0
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 */
class MappedSuperClassMetadataBinder
{
    /**
     * @var Mapping\ClassMetadataBuildingContext
     */
    private $metadataBuildingContext;

    /**
     * @var \ReflectionClass
     */
    private $reflectionClass;

    /**
     * [dreaming] One day we would eliminate this and only do: $reflectionClass->getAnnotations()
     *
     * @var array<string, object>
     */
    private $classAnnotations;

    /**
     * @todo guilhermeblanco This should disappear once we instantiation happens in the Driver
     *
     * @var Mapping\ClassMetadata
     */
    private $classMetadata;

    /**
     * ComponentMetadataBinder constructor.
     *
     * @param \ReflectionClass                     $reflectionClass
     * @param array<string, object>                $classAnnotations
     * @param Mapping\ClassMetadata                $classMetadata
     * @param Mapping\ClassMetadataBuildingContext $metadataBuildingContext
     */
    public function __construct(
        \ReflectionClass $reflectionClass,
        array $classAnnotations,
        Mapping\ClassMetadata $classMetadata,
        Mapping\ClassMetadataBuildingContext $metadataBuildingContext
    )
    {
        $this->reflectionClass         = $reflectionClass;
        $this->classAnnotations        = $classAnnotations;
        $this->classMetadata           = $classMetadata;
        $this->metadataBuildingContext = $metadataBuildingContext;
    }

    /**
     * @return Mapping\ClassMetadata
     *
     * @throws Mapping\MappingException
     */
    public function bind() : Mapping\ClassMetadata
    {
        $classMetadata = $this->classMetadata;

        $this->processMappedSuperclassAnnotation($classMetadata, $this->classAnnotations[Annotation\MappedSuperclass::class]);

        return $classMetadata;
    }

    /**
     * @param Mapping\ClassMetadata       $classMetadata
     * @param Annotation\MappedSuperclass $mappedSuperclassAnnotation
     *
     * @return void
     */
    private function processMappedSuperclassAnnotation(
        Mapping\ClassMetadata $classMetadata,
        Annotation\MappedSuperclass $mappedSuperclassAnnotation
    ) : void
    {
        if ($mappedSuperclassAnnotation->repositoryClass !== null) {
            $classMetadata->setCustomRepositoryClassName(
                $classMetadata->fullyQualifiedClassName($mappedSuperclassAnnotation->repositoryClass)
            );
        }

        $classMetadata->isMappedSuperclass = true;
        $classMetadata->isEmbeddedClass = false;
    }
}
