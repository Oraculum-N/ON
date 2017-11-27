<?php

namespace ON;

class App
{
    public function __construct()
    {
        header('X-Powered-By: ON Framework');
        self::checkDebug();
    }

    public static function loadView()
    {
        return new Views();
    }

    public static function loadControl()
    {
        return new Controls();
    }

    public static function loadModel($model)
    {
        return new Models($model);
    }

    public function view()
    {
        return new Views();
    }

    public function control()
    {
        return new Controls();
    }

    public function model()
    {
        return new Models();
    }

    public function setControlsDir($dir)
    {
        if (file_exists($dir)) {
            define('CONTROL_DIR', $dir);
        } else {
            throw new Exception('[Error '.__METHOD__.'] Diretorio nao encontrado ('.$dir.')');
        }
    }

    public function setViewsDir($dir)
    {
        if (file_exists($dir)) {
            define('VIEW_DIR', $dir);
        } else {
            throw new Exception('[Error '.__METHOD__.'] Diretorio nao encontrado ('.$dir.')');
        }
    }

    public function setModelsDir($dir)
    {
        if (file_exists($dir)) {
            define('MODEL_DIR', $dir);
        } else {
            throw new Exception('[[Error '.__METHOD__.'] ] Diretorio nao encontrado ('.$dir.')');
        }
    }

    public function start()
    {
        return new Start();
    }

    public static function checkDebug()
    {
        if ((defined('ON_DEBUG')) && (ON_DEBUG)):
                Exception::displayErrors();
        Exception::start(); else:
                ini_set('display_errors', false);
        endif;
    }
}
