<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 22/06/2018
 * Time: 20:17
 */

namespace Giansalex\Serializer;

use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;

interface ExtractorFactoryInterface
{
    /**
     * @return PropertyInfoExtractorInterface
     */
    public function getExtractor();
}