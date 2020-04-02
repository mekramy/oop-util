<?php

namespace MEkramy\OOPUtil;

use MEkramy\OOPUtil\Chainer;

/**
 * Make chaining functionality available for class
 *
 * @author m ekramy <m@ekramy.ir>
 * @access public
 * @version 1.0.0
 */
trait CanChained
{
    /**
     * Start class chaining method call
     *
     * @return \MEkramy\Chaining\Chainer
     */
    public function chaining(): Chainer
    {
        return new Chainer($this, array_values($this->__canChain()), array_values($this->__cantChain()));
    }

    /**
     * Methods list to include in chaining call
     *
     * @return array
     */
    protected function __canChain(): array
    {
        return [];
    }

    /**
     * Methods list to exclude in chaining call
     *
     * @return array
     */
    protected function __cantChain(): array
    {
        return [];
    }
}
