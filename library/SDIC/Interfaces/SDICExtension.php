<?php

namespace Theorx\SDIC\Interfaces;

/**
 * @author  Lauri Orgla <theorx@hotmail.com>
 *
 * Interface SDICExtension
 *
 * @package Theorx\SDIC\Interfaces
 */
interface SDICExtension {

    /**
     * Return list of dependencies as name => callback to extend the container
     *
     * @author Lauri Orgla <theorx@hotmail.com>
     *
     * @return array
     */
    public function registerDependencies() : array;

}