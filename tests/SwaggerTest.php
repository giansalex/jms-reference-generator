<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 31/01/2018
 * Time: 10:51 AM
 */

namespace Tests\Serializer;

use Giansalex\Serializer\JmsGenerator;
use Giansalex\Serializer\PropertyExtractorFactory;

class SwaggerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var JmsGenerator
     */
    private $swagger;

    protected function setUp()
    {
        $factory = new PropertyExtractorFactory();
        $this->swagger = new JmsGenerator($factory->getExtractor());
    }

    public function testFromObject()
    {
        $result = $this->swagger->fromClass(MyObject::class);

        $this->assertTrue(isset($result[MyObject::class]));

        $props = $result[MyObject::class]['properties'];
        $this->assertTrue(isset($props['id']));
        $this->assertTrue(isset($props['name']));
        $this->assertTrue(isset($props['date']));
        $this->assertTrue(isset($props['mount']));
        $this->assertTrue(isset($props['valid']));
        $this->assertTrue(isset($props['notes']));
    }

    public function testFromSubObject()
    {
        $result = $this->swagger->fromClass(SubObject::class);

        $this->assertTrue(isset($result[SubObject::class]));

        $props = $result[SubObject::class]['properties'];
        $this->assertEquals(8, count($props));
    }
}