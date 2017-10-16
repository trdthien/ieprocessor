<?php

namespace Shopmacher\IEProcessor\Mapper;

use Shopmacher\IEProcessor\MapperConfig;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\PropertyInfo\Type;

/**
 * Class IEMapper
 * @package Shopmacher\IEProcessor\Mapper
 */
class ObjectMapper implements MapperInterface
{
    /**
     * @var MapperConfigLoaderInterface
     */
    private $config;

    /**
     * ArrayObjectMapper constructor.
     * @param MapperConfig $config
     */
    public function __construct(MapperConfig $config = null)
    {
        $this->config = $config;
    }

    /**
     * @inheritdoc
     */

    public function mapping($data, $class = null)
    {
        if (!is_array($data)) {
            throw new \InvalidArgumentException('Data must be array');
        }

        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
        }

        $collection = [];

        $object = new $class;

        foreach ($data as $key => $record) {
            $accessor = PropertyAccess::createPropertyAccessor();
            foreach ($record as $property => $propertyValue) {
                if ($accessor->isWritable($object, $property)) {
                    $typedValue = $this->getTypedValue($class, $property, $propertyValue);
                    $accessor->setValue($object, $property, $typedValue);
                    continue;
                } else {
                    // TODO: logs or do something?
                }
            }
            $collection[$key] = $object;
        }

        return $collection;
    }

    /**
     * @return PropertyInfoExtractor
     */
    private function setUpExtractor()
    {
        $phpDocExtractor = new PhpDocExtractor();
        $reflectionExtractor = new ReflectionExtractor();
        // array of PropertyTypeExtractorInterface
        $typeExtractors = array($phpDocExtractor, $reflectionExtractor);
        // array of PropertyListExtractorInterface
        $listExtractors = array($reflectionExtractor);
        // array of PropertyAccessExtractorInterface
        $accessExtractors = array($reflectionExtractor);
        // array of PropertyDescriptionExtractorInterface
        $descriptionExtractors = array($phpDocExtractor);

        $propertyInfo = new PropertyInfoExtractor(
            $listExtractors,
            $typeExtractors,
            $descriptionExtractors,
            $accessExtractors
        );

        return $propertyInfo;
    }

    /**
     * @param $class
     * @param null $key
     * @return Type|null|\string[]
     */
    private function getPropertyInfo($class, $key = null)
    {
        $propertyInfo = $this->setUpExtractor();

        if ($key) {
            $types = $propertyInfo->getTypes($class, $key);
            return count($types) ? $types[0] : null;
        }

        return $propertyInfo->getProperties($class);
    }

    /**
     * @param $type
     * @return bool
     */
    private function isSimpleType($type)
    {
        return in_array($type, ['string', 'boolean', 'bool', 'integer', 'int', 'float']);
    }

    /**
     * @param $class
     * @param $key
     * @param $value
     * @return array|mixed
     * @throws \Exception
     */
    private function getTypedValue($class, $key, $value)
    {
        $type = $this->getPropertyInfo($class, $key);
        $builtInType = $type->getBuiltinType();
        // Check if the type is a simple type (string, int, bool, etc.)
        if ($this->isSimpleType($builtInType)) {
            return $value;
        } elseif ($builtInType === 'array') {
            if ($collectionValueType = $type->getCollectionValueType()) {
                if ($collectionClassName = $collectionValueType->getClassName()) {
                    $collectionValue = [];
                    foreach ($value as $val) {
                        $collectionValue[] = $this->mapping([$val], $collectionClassName)[0];
                    }
                    return $collectionValue;
                }
            }
        } elseif ($builtInType === 'object') {
            $className = $type->getClassName();
            return $this->mapping([$value], $className)[0];
        } else {
            throw new \Exception('Type does not support');
        }
    }
}
