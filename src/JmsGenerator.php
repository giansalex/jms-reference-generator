<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 31/01/2018
 * Time: 10:39 AM
 */

namespace Giansalex\Serializer;

use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
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
     * @var PropertyInfoExtractor
     */
    private $extractor;

    /**
     * Swagger constructor.
     */
    public function __construct()
    {
        $this->extractor = $this->getPropertyExtractor();
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

        return ['definitions' => $this->all];
    }

    /**
     * @return PropertyInfoExtractor
     */
    private function getPropertyExtractor()
    {
        $phpDocExtractor = new PhpDocExtractor();
        $reflectionExtractor = new ReflectionExtractor();

        // array of PropertyListExtractorInterface
        $listExtractors = array($reflectionExtractor);

        // array of PropertyTypeExtractorInterface
        $typeExtractors = array($phpDocExtractor, $reflectionExtractor);

        // array of PropertyDescriptionExtractorInterface
        $descriptionExtractors = array($phpDocExtractor);

        // array of PropertyAccessExtractorInterface
        $accessExtractors = array($reflectionExtractor);

        $this->extractor = new PropertyInfoExtractor(
            $listExtractors,
            $typeExtractors,
            $descriptionExtractors,
            $accessExtractors
        );

        return $this->extractor;
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

                    $prop = ['type' => $this->getItemArray($type)];
                } elseif ($tipo == 'object') {
                    $className = $type->getClassName();
                    if ($this->isDateTime($className)) {
                        $prop = ['type' => 'DateTime'];
                    } else {
                        $name = $this->registerClass($className);
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

        $parent = get_parent_class($class);
        $props = [];
        if ($parent) {
            $props = $this->getProperties($parent);
        }

        $props = array_merge($props, $this->getProperties($class));
        $this->all[$class] = [
            'properties' => $props
        ];

        return $class;
    }

    private function getItemArray(Type $type)
    {
        $typeCollection = $type->getCollectionValueType();
        if (empty($typeCollection)) {
            return 'array<string>';
        }

        $className = $typeCollection->getClassName();
        if ($className) {
            $name = $this->registerClass($className);
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