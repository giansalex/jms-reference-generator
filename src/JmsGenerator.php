<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 31/01/2018
 * Time: 10:39 AM
 */

namespace Giansalex\Serializer;

use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;
use Symfony\Component\PropertyInfo\Type;

/**
 * Class Swagger
 */
class JmsGenerator
{
    /**
     * @var array
     */
    private $all;

    /**
     * @var PropertyInfoExtractorInterface
     */
    private $extractor;

    /**
     * Swagger constructor.
     * @param PropertyInfoExtractorInterface $extractor
     */
    public function __construct(PropertyInfoExtractorInterface $extractor)
    {
        $this->extractor = $extractor;
    }

    /**
     * @param string $class
     * @return array
     */
    public function fromClass($class)
    {
        return $this->fromClasses([$class]);
    }

    /**
     * @param array $classes
     * @return array
     */
    public function fromClasses(array $classes)
    {
        $this->all = [];
        foreach ($classes as $class) {
            $this->registerClass($class);
        }

        return $this->all;
    }

    /**
     * @param string $class
     * @return array
     */
    function getProperties($class)
    {
        $props = [];
        $properties = $this->extractor->getProperties($class);
        foreach ($properties as $property) {
            $types = $this->extractor->getTypes($class, $property);

            if ($types == null) {
                exit();
            }
            foreach ($types as $type) {
                /**@var $type Type*/
                $tipo = $type->getBuiltinType();
                if ($tipo == 'array') {

                    $prop = ['type' => $this->getItemArray($type, $class)];
                } elseif ($tipo == 'object') {
                    $className = $type->getClassName();
                    if ($this->isDateTime($className)) {
                        $prop = ['type' => 'DateTime'];
                    } else {
                        $name = $className === $class ? $class : $this->registerClass($className);
                        $prop = ['type' => $name];
                    }
                } else {
                    $prop = ['type' => $tipo];
                }

                $props[$property] = $prop;
            }
        }

        return $props;
    }

    /**
     * @param string $class
     * @return string
     */
    private function registerClass($class)
    {
        if (isset($this->all[$class])) {
            return $class;
        }

        $props = $this->getProperties($class);
        $this->all[$class] = [
            'properties' => $props
        ];

        return $class;
    }

    private function getItemArray(Type $type, string $class)
    {
        $typeCollection = $type->getCollectionValueTypes()[0] ?? null;
        if (empty($typeCollection)) {
            return 'array<string>';
        }

        $className = $typeCollection->getClassName();
        if ($className) {
            $name = $className === $class ? $class : $this->registerClass($className);
            $itemType = 'array<'.$name.'>';
        } else {
            $type = $typeCollection->getBuiltinType();
            $itemType = 'array<'.$type.'>';
        }

        return $itemType;
    }

    /**
     * @param $className
     * @return bool
     */
    private function isDateTime($className)
    {
        return
            $className == 'DateTimeInterface' ||
            $className == 'DateTime' ||
            $className == 'DateTimeImmutable';
    }
}