<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 20/06/2018
 * Time: 23:18
 */

namespace Tests\Serializer;

use Giansalex\Serializer\JmsGenerator;
use Symfony\Component\Yaml\Yaml;

class YamlSaveTest extends \PHPUnit_Framework_TestCase
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

        foreach ($result as $class => $props) {
            $yaml = Yaml::dump([$class => $props], 4);
            $name = $this->getNameClass($class);
            file_put_contents('model.'.$name.'.yaml', $yaml);
        }
    }

    /**
     * @param string $class
     * @return string
     */
    private function getNameClass($class) {
        $path = explode('\\', $class);

        return array_pop($path);
    }
}