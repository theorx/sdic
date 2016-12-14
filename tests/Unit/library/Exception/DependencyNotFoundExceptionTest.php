<?php

namespace Tests\SDIC\Exception;

use Theorx\SDIC\Exception\DependencyNotFoundException;

/**
 * Class DependencyNotFoundExceptionTest
 *
 * @author  Lauri Orgla <theorx@hotmail.com>
 * @coversDefaultClass \Theorx\SDIC\Exception\DependencyNotFoundException
 * @package Tests\SDIC\Exception
 */
class DependencyNotFoundExceptionTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers ::__construct
     */
    public function testConstructorCreatesCorrectMessage() {

        $instance = new DependencyNotFoundException('test');
        $this->assertEquals($instance->message, 'Dependency test not found in the container');
    }
}
