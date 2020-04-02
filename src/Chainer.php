<?php

namespace MEkramy\OOPUtil;

use BadMethodCallException;

/**
 * Chaining method implementation for PHP classes
 *
 * @author m ekramy <m@ekramy.ir>
 * @access public
 * @version 1.0.0
 */
class Chainer
{
    /**
     * Class instance
     *
     * @var object
     */
    protected $instance;

    /**
     * Methods to include in chaining
     *
     * @var array
     */
    protected $includes = [];

    /**
     * Methods to exclude in chaining
     *
     * @var array
     */
    protected $excludes = [];

    /**
     * Create new Chainer instance
     *
     * @param object $instance
     * @param array $includes
     * @param array $excludes
     * @return void
     */
    public function __construct(object $instance, array $includes = [], array $excludes = [])
    {
        $this->instance = $instance;
        $this->includes = $includes;
        $this->excludes = $excludes;
    }

    /**
     * Magically call methods and return self to allow continue chaining
     *
     * @throws BadMethodCallException method not chainable
     * @return \MEkramy\OOPUtil\Chainer
     */
    public function __call($name, $arguments): Chainer
    {
        if (
            in_array($name, $this->excludes) ||
            (count($this->includes) > 0 && !in_array($name, $this->includes))
        ) {
            throw new BadMethodCallException("{$name} method not chainable!");
        }
        $this->instance->{$name}(...$arguments);
        return $this;
    }

    /**
     * Finish chaining call and return original instance
     *
     * @return object
     */
    public function getInstance()
    {
        return $this->instance;
    }
}
