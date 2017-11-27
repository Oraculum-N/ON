<?php

namespace ON;

class ON
{
    private static $_instance = null;

    public function __construct()
    {
    }

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

    public static function LoadContainer($lib = null)
    {
        if (is_null($lib)):
                throw new Exception('[Erro RO6] Tipo de Container nao informado'); else:
                $libfile = 'core/apps/'.$lib.'.php';
        if (file_exists(PATH.$libfile)):
                    include_once $libfile; else:
                    throw new Exception('[Erro RO12] Tipo de Container nao existente ('.$libfile.') ');
        endif;
        endif;
    }

    public static function App()
    {
        $instance = self::getInstance();

        return new App();
    }

    public static function ApiApp()
    {
    }
}
