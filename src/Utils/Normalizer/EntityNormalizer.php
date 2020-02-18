<?php


namespace App\Utils\Normalizer;


use App\Entity\Performance;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;

/**
 * Entity normalizer
 */
class EntityNormalizer extends ObjectNormalizer
{
    /**
     * Entity manager
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * Entity normalizer
     * @param EntityManagerInterface $em
     * @param ClassMetadataFactoryInterface|null $classMetadataFactory
     * @param NameConverterInterface|null $nameConverter
     * @param PropertyAccessorInterface|null $propertyAccessor
     * @param PropertyTypeExtractorInterface|null $propertyTypeExtractor
     */
    public function __construct(
        EntityManagerInterface $em,
        ?ClassMetadataFactoryInterface $classMetadataFactory = null,
        ?NameConverterInterface $nameConverter = null,
        ?PropertyAccessorInterface $propertyAccessor = null,
        ?PropertyTypeExtractorInterface $propertyTypeExtractor = null
    )
    {
        parent::__construct($classMetadataFactory, $nameConverter, $propertyAccessor, $propertyTypeExtractor);

        // Entity manager
        $this->em = $em;
    }

    /**
     * @inheritDoc
     */
    public function supportsDenormalization($data, $type, string $format = null)
    {
        return (strpos($type, 'App\\Entity\\') === 0 || strpos($type, 'ArrayCollection'));
    }

    /**
     * @inheritDoc
     */
    public function denormalize($data, $class = Performance::class, string $format = null, array $context = [])
    {
        if(isset($data['id'])){
           $object = $this->em->getReference($class, $data['id']);
           $context[AbstractNormalizer::OBJECT_TO_POPULATE] = $object;
        }
        return parent::denormalize($data, $class, $format, $context);
    }
}