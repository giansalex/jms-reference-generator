<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 31/01/2018
 * Time: 10:51 AM
 */

namespace Tests\Serializer;

use Giansalex\Serializer\JmsGenerator;

class SwaggerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var JmsGenerator
     */
    private $swagger;

    protected function setUp()
    {
        $this->swagger = new JmsGenerator();
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

}