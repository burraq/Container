<?php

use Burraq\Container\Container;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    protected $container;

    public $offset = [
        'key' => __CLASS__,
    ];

    public function setUp()
    {
        $this->container = new Container($this->offset);
    }

    public function test_ContainerInstance()
    {
        $this->assertInstanceOf(Container::class, $this->container);
    }

    public function test_Magic__set()
    {
        $this->container->setTest = 1;

        $this->assertTrue(isset($this->container->setTest));
        $this->assertEquals(1, $this->container->setTest);
    }

    public function test_Magic__get()
    {
        $this->container->getTest = 1;

        $this->assertEquals(1, $this->container->getTest);
    }

    public function test_Magic__isset()
    {
        $this->container->issetTest = 1;

        $this->assertTrue(isset($this->container->issetTest));
    }

    public function test_Magic__unset()
    {
        $this->container->unsetTest = 1;

        if (isset($this->container->unsetTest)) {
            unset($this->container->unsetTest);
        }

        $this->assertFalse(isset($this->container->unsetTest));
    }

    public function test_Magic__invoke()
    {
        $invokeable = new Container();

        $invokeable(['invoke' => 1]);

        $this->assertEquals(1, $invokeable->invoke);
    }

    //------------------------------------------------------------------------
    // Container interface
    //------------------------------------------------------------------------

    public function raw()
    {
        $rawold = $this->container['raw'] = function ($container) {
            return new Container;
        };

        $raw = $this->container->raw($this->container['raw']);

        $this->assertSame($rawold, $raw);
    }

    public function test_Keys()
    {
        $array = ['k1' => 'v1', 'k2' => 'v2'];

        $container = new Container($array);

        $this->assertEquals(array_keys($array), $container->keys());
    }

    //------------------------------------------------------------------------
    // ArrayAccess interface
    //------------------------------------------------------------------------

    public function test_offsetExists()
    {
        $this->assertTrue(isset($this->offset['key']));
    }

    public function test_offsetGet()
    {
        $this->assertEquals($this->offset['key'], $this->container['key']);

        $this->container['Func'] = function ($container) {
            return new Container;
        };

        $this->assertInstanceOf(Container::class, $this->container['Func']);
    }

    public function test_offsetSet()
    {
        $test = $this->container['offsetSet'] = 1;

        if (isset($this->container['offsetSet'])) {
            $this->assertEquals(1, $test);
        }

        $this->Container['offsetSet'] = 0;

        $this->assertNotEquals(0, $test);
    }

    public function test_offsetUnset()
    {
        $this->container['offsetUnset'] = 1;

        if (isset($this->container['offsetUnset'])) {
            unset($this->container['offsetUnset']);

            $this->assertFalse(isset($this->container['offsetUnset']));
        }
    }
}
