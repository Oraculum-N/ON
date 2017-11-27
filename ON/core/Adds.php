<?php

namespace ON;

class Adds
{
    public static function load($plugin)
    {
        $plugin = strtolower($plugin);
        $arquivo = dirname(__FILE__).'/../adds/'.$plugin.'.php';
        if (file_exists($arquivo)):
              include_once $arquivo; else:
                throw new Exception('[Error '.__METHOD__.'] Plugin nao encontrado');
        endif;
    }
}
