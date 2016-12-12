<?php

namespace Theorx\SDIC;

use Theorx\SDIC\Exception\DependencyNotFoundException;
use Theorx\SDIC\Interfaces\SDICExtension;

/**
 * Class SDIC
 *
 * @author  Lauri Orgla <theorx@hotmail.com>
 *
 * @package Theorx\SDIC
 */
class SDIC {

    /**
     * name => callback map
     *
     * @var array
     */
    protected $register = [];

    /**
     * @var array
     */
    protected $instances = [];

    /**
     * @var array
     */
    protected $extensions = [];

    /**
     * @author Lauri Orgla <theorx@hotmail.com>
     *
     *         Register name => callback,
     *         Callback example:
     *         function ($container) {
     *              return $container->shared(NAME, function(){
     *                  return {{shared instance}};
     *              });
     *          }
     *
     *         This method is chainable, register()->register()->register()-> etc..
     *
     * @param string   $name
     * @param callable $callback
     *
     * @return SDIC
     */
    public function register(string $name, callable $callback) : SDIC {

        $this->register[$name] = $callback;

        return $this;
    }

    /**
     * Used for creating shared instances
     *
     * @author Lauri Orgla <theorx@hotmail.com>
     *
     * @param string   $name
     * @param callable $callback
     *
     * @return mixed
     */
    public function shared(string $name, callable $callback) {

        if(!in_array($name, array_keys($this->instances))) {
            $this->instances[$name] = $callback();
        }

        return $this->instances[$name];
    }

    /**
     * Fetches dependency by name
     *
     * @author Lauri Orgla <theorx@hotmail.com>
     *
     * @param string $name
     *
     * @return mixed
     * @throws DependencyNotFoundException
     */
    public function get(string $name) {

        if(array_key_exists($name, $this->instances)) {
            return $this->instances[$name];
        }

        if(array_key_exists($name, $this->register)) {
            return $this->register[$name]($this);
        }

        throw new DependencyNotFoundException($name);
    }

    /**
     * Checks whether the container has given dependency
     *
     * @author Lauri Orgla <theorx@hotmail.com>
     *
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name) {

        return array_key_exists($name, $this->register);
    }

    /**
     * Gets shared instances
     *
     * @author Lauri Orgla <theorx@hotmail.com>
     * @return array
     */
    public function getInstances() {

        return $this->instances;
    }

    /**
     * Gets current registry
     *
     * @author Lauri Orgla <theorx@hotmail.com>
     * @return array
     */
    public function getRegistry() {

        return $this->register;
    }

    /**
     * Load container extension which is instance of class that implements SDICExtension
     *
     * @author Lauri Orgla <theorx@hotmail.com>
     *
     * @param SDICExtension $extension
     */
    public function loadExtension(SDICExtension $extension) {

        $this->extensions[] = $extension;

        foreach($extension->registerDependencies() as $name => $callback) {
            $this->register($name, $callback);
        }
    }

    /**
     * Loads a set of extensions
     *
     * @author Lauri Orgla <theorx@hotmail.com>
     *
     * @param array $extensions
     */
    public function loadExtensions(array $extensions) {

        foreach($extensions as $extension) {
            if($extension instanceof SDICExtension) {
                $this->loadExtension($extension);
            }
        }
    }

    /**
     * Gets list of loaded extensions
     *
     * @author Lauri Orgla <theorx@hotmail.com>
     * @return SDICExtension[]
     */
    public function getLoadedExtensions() : array {

        return $this->extensions;
    }
}