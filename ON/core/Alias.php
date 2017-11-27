<?php

namespace ON;

/*
     * Arquivo com funcoes globais que na verdade chamam funcoes de
     * suas respectivas classes
     */
    class Alias
    {
        public static function addAlias($newfunction, $originalfunction)
        {
            if (function_exists($newfunction)):
                throw new Exception('[Error '.__METHOD__.'] A funcao \''.$newfunction.'\' ja existe'); elseif (!is_callable($originalfunction)):
                throw new Exception('[Error '.__METHOD__.'] A funcao \''.$originalfunction.'\' nao pode ser chamada'); else:
                eval('function '.$newfunction.'() {
					$args=func_get_args();
					return call_user_func_array(\''.$originalfunction.'\',$args);
				       }');
            endif;
        }

        public static function loadAlias($class)
        {
            if (($class == 'Request') || ($class == 'All')):
                /**********************************
                 * Tratamento de requisicoes
                 **********************************/
                //Request::getpagina();
                self::addAlias('post', 'ON\Request::post');
            self::AddAlias('get', 'ON\Request::get');
            self::AddAlias('getSess', 'ON\Request::getSess');
            self::AddAlias('setSess', 'ON\Request::setSess');
            self::AddAlias('unsetsess', 'ON\Request::unsetSess');
            self::AddAlias('init_sess', 'ON\Request::initSess');
            self::AddAlias('set_cookie', 'ON\Request::setCookie');
            self::AddAlias('cookie', 'ON\Request::setCookie');
            self::AddAlias('getpagina', 'ON\Request::getPagina');
            self::AddAlias('getid', 'ON\Request::getid');
            self::AddAlias('getlast', 'ON\Request::getLast');
            self::AddAlias('getvar', 'ON\Request::getVar');
            self::AddAlias('gets', 'ON\Request::gets');
            endif;
            if (($class == 'Crypt') || ($class == 'All')):
                /**********************************
                * Tratamento de criptografia
                **********************************/
                self::AddAlias('strcrypt', 'ON\Crypt::strcrypt');
            self::AddAlias('strdcrypt', 'ON\Crypt::strdcrypt');
            self::AddAlias('blowfish', 'ON\Crypt::blowfish');
            self::AddAlias('blowfishcheck', 'ON\Crypt::blowfishcheck');
            endif;
            if (($class == 'HTTP') || ($class == 'All')):
                /**********************************
                * Tratamento de parametros HTTP
                **********************************/
                self::AddAlias('redirect', 'ON\HTTP::redirect');
            self::AddAlias('ip', 'ON\HTTP::ip');
            self::AddAlias('host', 'ON\HTTP::host');
            endif;
            if (($class == 'Validate') || ($class == 'All')):
                /**********************************
                * Tratamento de formularios
                **********************************/
                self::AddAlias('validar', 'ON\Validate::validar');
            self::AddAlias('verificaCPF', 'ON\Validate::verificaCPF');
            self::AddAlias('verificaEmail', 'ON\Validate::verificaEmail');
            endif;
            if (($class == 'Text') || ($class == 'All')):
                /**********************************
                * Tratamento de informacoes textuais
                **********************************/
                self::AddAlias('moeda', 'ON\Text::moeda');
            self::AddAlias('moedaSql', 'ON\Text::moedaSql');
            self::AddAlias('data', 'ON\Text::data');
            self::AddAlias('dataSql', 'ON\Text::dataSql');
            self::AddAlias('hora', 'ON\Text::hora');
            self::AddAlias('saudacao', 'ON\Text::saudacao');
            self::AddAlias('getpwd', 'ON\Text::getpwd');
            self::AddAlias('inflector', 'ON\Text::inflector');
            self::AddAlias('plural', 'ON\Text::plural');
            endif;
            if (($class == 'Files') || ($class == 'All')):
                /**********************************
                * Tratamento de inclusao de arquvos
                **********************************/
                self::AddAlias('inc', 'ON\Files::inc');
            endif;
            if (($class == 'Logs') || ($class == 'All')):
                /**********************************
                * Tratamento de erros e logs
                **********************************/
                self::AddAlias('alert', 'ON\Logs::alert');
            endif;
        }
    }
