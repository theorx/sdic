<?php

namespace theorx\SDIC\Exception;

/**
 * Class DependencyNotFoundException
 *
 * @author  Lauri Orgla <theorx@hotmail.com>
 *
 * @package theorx\SDIC\Exception
 */
class DependencyNotFoundException extends \Exception {

    /**
     * @var string
     */
    public $message = 'Dependency %s not found in the container';
    /**
     * @var int
     */
    public $code = 90;

    /**
     * DependencyNotFoundException constructor.
     *
     * @author Lauri Orgla <theorx@hotmail.com>
     *
     * @param string $name
     */
    public function __construct($name = '') {

        $this->message = sprintf($this->message, $name);
    }
}