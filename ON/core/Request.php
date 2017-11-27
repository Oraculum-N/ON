<?php

namespace ON;

class Request
{
    public static function defineTmpDir($dir = '/tmp/')
    {
        if (!defined('TMP_DIR')):
                define('TMP_DIR', $dir);
        endif;
    }

    // Receber variaveis por $_POST
    public static function post($indice, $tipo = 's')
    {
        if ((isset($_POST[$indice])) && ($_POST[$indice] != '')):
                $valor = $_POST[$indice];
        if ($tipo != 'h'):
                    $valor = strip_tags($valor);
        $valor = htmlentities($valor, ENT_SUBSTITUTE, 'ISO-8859-1'); elseif ($tipo == 'n'):
                    $valor = floor($valor);
        if ($valor == 0):
                        $valor = null;
        endif;
        endif; else:
                $valor = null;
        endif;

        return $valor;
    }

    // Receber variaveis por $_GET
    public static function get($indice, $tipo = 's')
    {
        if ((isset($_GET[$indice])) && ($_GET[$indice] != '')):
                $valor = $_GET[$indice]; else:
                $valor = null;
        endif;
        $valor = strip_tags($valor);
        $valor = htmlentities($valor, ENT_SUBSTITUTE, 'ISO-8859-1');
        $valor = addslashes($valor);
        if ($tipo == 'n'):
                $valor = floor($valor);
        if ($valor == 0):
                    $valor = null;
        endif;
        endif;

        return $valor;
    }

    // Desconvertendo para UTF8 valores passados por $_POST
    public static function postUtf8Decode()
    {
        foreach ($_POST as $key=>$value):
                if (mb_detect_encoding($value) == 'UTF-8'):
                    $_POST[$key] = utf8_decode($value);
        endif;
        endforeach;
    }

    // Convertendo para UTF8 valores passados por $_POST
    public static function postUtf8Encode()
    {
        foreach ($_POST as $key=>$value):
                if (mb_detect_encoding($value) != 'UTF-8'):
                    $_POST[$key] = utf8_encode($value);
        endif;
        endforeach;
    }

    // Desconvertendo para UTF8 valores passados por $_GET
    public static function getUtf8Decode()
    {
        foreach ($_GET as $key=>$value):
                if (mb_detect_encoding($value) == 'UTF-8'):
                    $_GET[$key] = utf8_decode($value);
        endif;
        endforeach;
    }

    // Convertendo para UTF8 valores passados por $_GET
    public static function getUtf8Encode()
    {
        foreach ($_GET as $key=>$value):
                if (mb_detect_encoding($value) != 'UTF-8'):
                    $_GET[$key] = utf8_encode($value);
        endif;
        endforeach;
    }

    // Receber variaveis por $_FILES
    public static function file($indice, $atributo = null, $filter = null)
    {
        if ((isset($_FILES[$indice])) && ($_FILES[$indice] != '')):
                $file = $_FILES[$indice];
        if (($file['error'] == 0) && (!is_null($filter))):
                    $filter = Files::fileFilter($file['type'], $filter);
        if (!$filter):
                        return false;
        endif;
        endif;
        if (is_null($atributo)):
                    return $file; else:
                    return $file[$atributo];
        endif; else:
                return;
        endif;
    }

    // Capturar sessao
    public static function getSess($indice)
    {
        if (isset($_SESSION[$indice])):
                $valor = $_SESSION[$indice]; else:
                $valor = null;
        endif;

        return $valor;
    }

    // Gravar sessao
    public static function setSess($indice, $valor)
    {
        if ((isset($indice)) && (isset($valor))):
                $_SESSION[$indice] = $valor;

        return true; else:
                return false;
        endif;
    }

    // Remover variavel de sessao
    public static function unsetSess($indice)
    {
        if (isset($indice)):
                unset($_SESSION[$indice]);

        return true; else:
                return false;
        endif;
    }

    // Inicializar sessao
    public static function initSess()
    {
        self::defineTmpDir();
        if (!isset($_SESSION)):
                if (ini_get('session.save_path') != TMP_DIR):
                    session_save_path(TMP_DIR);
        endif;
        session_start();
        endif;
    }

    // Gravar cookie
    public static function setCookie($nome, $valor, $expirar = null)
    {
        $expirar = (is_null($expirar)) ? (time() + 604800) : $expirar;
        $return = setcookie($nome, $valor, $expirar);

        return $return;
    }

    // Ler cookie
    public static function getCookie($indice)
    {
        if (isset($_COOKIE[$indice])):
                $valor = $_COOKIE[$indice]; else:
                $valor = null;
        endif;

        return $valor;
    }

    // Capturar pagina
    public static function getPagina($gets = null)
    {
        if (is_null($gets)):
                $gets = self::gets();
        endif;
        $pagina = null;
        foreach ($gets as $chave=>$valor):
                if ($chave > 1):
                    if ($gets[$chave - 1] == 'page'):
                        $pagina = $valor;
        endif;
        endif;
        endforeach;

        return $pagina;
    }

    // Capturar ID
    public static function getId($gets = null, $posicao = 1)
    {
        if (is_null($gets)):
                $gets = self::gets();
        endif;
        $id = (int) floor($gets[count($gets) - $posicao]);

        return $id;
    }

    // Capturar ultimo parametro
    public static function getLast($gets = null)
    {
        if (is_null($gets)):
                $gets = self::gets();
        endif;
        $acao = ($gets[count($gets) - 1]);

        return $acao;
    }

    // Capturar parametro
    public static function getVar($index = 1, $default = null)
    {
        $gets = self::gets();
        if (is_string($index)):
                if (($key = array_search($index, $gets)) === false):
                    return $default;
        endif;
        $index = $key + 2;
        endif;
        $index = (int) $index - 1;

        return isset($gets[$index]) ? $gets[$index] : $default;
    }

    // Capturar URI
    public static function gets()
    {
        $request = self::request();
        $gets = explode('/', str_replace('?', '/', $request));

        return $gets;
    }

    public static function request()
    {
        return $_SERVER['REQUEST_URI'];
    }

    public static function requestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function referer()
    {
        if (isset($_SERVER['HTTP_REFERER'])):
                return $_SERVER['HTTP_REFERER']; else:
                return;
        endif;
    }
}
