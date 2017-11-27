<?php

namespace ON;

class Register
{
    private $_vars = [];
    private static $_instance = null;

    public static function getInstance()
    {
        if (is_null(self::$_instance)):
                self::setInstance(new self());
        endif;

        return self::$_instance;
    }

    public static function setInstance(self $instance)
    {
        self::$_instance = $instance;
    }

    public static function set($id, $value)
    {
        $instance = self::getInstance();
        $instance->_vars[$id] = $value;
    }

    public static function get($id)
    {
        $instance = self::getInstance();
        if (isset($instance->_vars[$id])):
                return $instance->_vars[$id]; else:
                return;
        endif;
    }

    public static function getVars()
    {
        $instance = self::getInstance();
        if (isset($instance->_vars)):
                return $instance->_vars; else:
                return;
        endif;
    }
}
