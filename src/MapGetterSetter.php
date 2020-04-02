<?php

namespace MEkramy\OOPUtil;

use InvalidArgumentException;

/**
 * Make getter/setter methods available by property call
 *
 * @author m ekramy <m@ekramy.ir>
 * @access public
 * @version 1.0.0
 */
trait MapGetterSetter
{
    /**
     * Get attribute by property call
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        $getter = $this->__toCamelCase("get $name");
        if (method_exists($this, $getter)) {
            return $this->{$getter}();
        }
        return $this->__onGetFailed($name);
    }

    /**
     * Set attribute by property call
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set(string $name, $value): void
    {
        $setter = $this->__toCamelCase("set $name");
        if (method_exists($this, $setter)) {
            $this->{$setter}($value);
        }else{
            $this->__onSetFailed($name, $value);
        }
    }

    /**
     * Call when getter not defined
     *
     * @param string $name
     * @return mixed
     */
    protected function __onGetFailed(string $name)
    {
        throw new InvalidArgumentException("$name property not exists!");
    }

    /**
     * Call when setter not defined
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    protected function __onSetFailed($name, $value): void
    {
        throw new InvalidArgumentException("$name property not exists!");
    }


    /**
     * Convert string to camelCase
     *
     * @param string $str
     * @return string
     */
    private function __toCamelCase(string $str): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $str))));
    }
}
