<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 31/01/2018
 * Time: 10:51 AM
 */

namespace Tests\Giansalex\Serializer;

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

        var_dump($result);
    }

}