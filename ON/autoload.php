<?php

    /**
     * @param string $class The fully-qualified class name.
     *
     * @return void
     */
    function onLoader($class)
    {
        if (!defined('PATH')):
            define('PATH', dirname(__FILE__).DS);
        endif;
        if (strpos($class, 'ON') === true):
            return;
        endif;
        $class = str_replace('ON\\', '', $class);
        $classfile = PATH.'core\\'.$class.'.php';
        if (file_exists($classfile)):
            include_once $classfile; else:
            //throw new Exception('[Error autoload] File not found ('.$classfile.')');
        endif;
    }
    spl_autoload_register('onLoader');
