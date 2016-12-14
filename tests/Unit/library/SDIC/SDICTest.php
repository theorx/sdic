<?php

namespace Tests\SDIC;

use Theorx\SDIC\SDIC;

/**
 * Class SDICTest
 *
 * @author  Lauri Orgla <theorx@hotmail.com>
 * @coversDefaultClass \Theorx\SDIC\SDIC
 * @package Tests\SDIC
 */
class SDICTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var SDIC
     */
    protected $sdicInstance;

    public function setUp() {

        parent::setUp();

        $this->sdicInstance = new SDIC();
    }

    /**
     * @covers ::register
     * @covers ::getRegistry
     */
    public function testRegisterRegistersDependency() {

        $name = 'TestDependency';
        $callback = function() {

            return "ReturnValue";
        };

        $this->sdicInstance->register($name, $callback);
        $registry = $this->sdicInstance->getRegistry();

        $this->assertArrayHasKey($name, $registry);
        $this->assertEquals($registry[$name], $callback);
    }
}