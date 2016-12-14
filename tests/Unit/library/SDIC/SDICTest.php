<?php

namespace Tests\SDIC;

use Theorx\SDIC\Interfaces\SDICExtension;
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

    /**
     * @covers ::getInstances
     * @covers ::shared
     * @covers ::get
     */
    public function testGetInstancesReturnsArray() {

        $instance = new \stdClass();
        $instance->key = "value";
        $name = 'stdClass';

        $this->sdicInstance->register($name, function(SDIC $container) use ($name, $instance) {

            return $container->shared($name, function() use ($instance) {

                return $instance;
            });
        });

        $this->assertEquals($instance, $this->sdicInstance->get($name));
        $instances = $this->sdicInstance->getInstances();
        $this->assertArrayHasKey($name, $instances);
    }

    /**
     * @covers ::get
     */
    public function testGetSharedInstanceResolvesFromCache() {

        $this->sdicInstance->register('shared', function(SDIC $container) {

            return $container->shared('shared', function() {

                return microtime();
            });
        });

        $value = $this->sdicInstance->get('shared');
        $this->assertEquals($value, $this->sdicInstance->get('shared'));
    }

    /**
     * @covers ::get
     * @expectedException \Theorx\SDIC\Exception\DependencyNotFoundException
     */
    public function testGetMissingDependencyThrowsException() {

        $this->sdicInstance->get('missingDependency');
    }

    /**
     * @covers ::has
     */
    public function testHasReturnsTrue() {

        $this->sdicInstance->register('test', function() {
        });

        $this->assertTrue($this->sdicInstance->has('test'));
    }

    /**
     * @covers ::has
     */
    public function testHasReturnsFalse() {

        $this->assertFalse($this->sdicInstance->has('test'));
    }

    /**
     * @covers ::registerArray
     */
    public function testRegisterArrayCallsRegister() {

        $callback = function() {
        };

        $inputData = [
            'first'  => $callback,
            'second' => $callback
        ];

        $mockSDIC = $this->getMockBuilder(SDIC::class)->setMethods(['register'])
            ->disableOriginalConstructor()->getMock();

        $mockSDIC->expects($this->at(0))->method('register')->with($this->equalTo('first'), $this->equalTo($callback));
        $mockSDIC->expects($this->at(1))->method('register')->with($this->equalTo('second'), $this->equalTo($callback));

        /**
         * @var SDIC $mockSDIC
         */

        $mockSDIC->registerArray($inputData);
    }

    /**
     * @covers ::loadExtension
     * @covers ::getLoadedExtensions
     */
    public function testLoadExtensionLoadsExtension() {

        $this->sdicInstance->loadExtension(new class implements SDICExtension {

            public function registerDependencies() : array {

                return [
                    'test.dep.1' => function() {

                        return 'test.dep.1';
                    },
                    'test.dep.2' => function() {

                        return 'test.dep.2';
                    }
                ];
            }
        });

        $this->assertCount(1, $this->sdicInstance->getLoadedExtensions());
        $this->assertTrue($this->sdicInstance->has('test.dep.1'));
        $this->assertTrue($this->sdicInstance->has('test.dep.2'));
    }

    /**
     * @covers ::loadExtensions
     */
    public function testLoadExtensionsLoadsExtensions() {

        $ext1 = new class implements SDICExtension {

            public function registerDependencies() : array {

                return [
                    'test.dep.1' => function() {

                        return 'test.dep.1';
                    },
                    'test.dep.2' => function() {

                        return 'test.dep.2';
                    }
                ];
            }
        };

        $ext2 = new class implements SDICExtension {

            public function registerDependencies() : array {

                return [
                    'test.dep.3' => function() {

                        return 'test.dep.3';
                    },
                    'test.dep.4' => function() {

                        return 'test.dep.4';
                    }
                ];
            }
        };

        $extensions = [$ext1, $ext2];

        $mockSDIC = $this->getMockBuilder(SDIC::class)->setMethods(['loadExtension'])->getMock();

        $mockSDIC->expects($this->at(0))->method('loadExtension')->with($this->equalTo($ext1));
        $mockSDIC->expects($this->at(1))->method('loadExtension')->with($this->equalTo($ext2));

        /**
         * @var SDIC $mockSDIC
         */

        $mockSDIC->loadExtensions($extensions);
    }
}