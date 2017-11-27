<?php

namespace ON;

class Exception extends \Exception
{
    public function __construct($message, $code = null)
    {
        parent::__construct($message, $code);
    }

    public function __toString()
    {
        return 'Code: '.$this->getCode().
                   '<br />Message: '.htmlentities($this->getMessage());
    }

    public function getException()
    {
        return $this;
    }

    public static function getStaticException($exception)
    {
        return $exception->getException();
    }

    public static function showException($e)
    {
        //Oraculum::Load('Logs');
        $filecode = null;
        $message = $e->getMessage();
        $code = $e->getCode();
        $file = $e->getFile();
        $line = $e->getLine();
        $trace = $e->getTrace();
        $traceasstring = $e->getTraceAsString();
        $report = '<h1>'.$message.'</h1>';
        $report .= '<strong>Error Code:</strong> #'.$code.'<br />';
        $report .= '<strong>Source File:</strong> '.$file.' <strong>line '.$line.'</strong><br />';
        $report .= '<h3>Backtrace</h3>';
        foreach ($trace as $error):
                if (isset($error['file'])):
                    $filecode = $error['file'];
        $report .= '<hr />';
        $report .= '<strong>File:</strong> '.$filecode.' <strong>line '.$error['line'].'</strong><br />'; else:
                    $filecode = null;
        endif;
        $args = [];
        if ($error['args']):
                    foreach ($error['args'] as $arg):
                        if (is_object($arg)):
                            $args[] = get_class($arg).' object'; elseif (is_array($arg)):
                            $args[] = implode(',', $arg); else:
                            $args[] = (string) $arg;
        endif;
        endforeach;
        endif;

        if (isset($error['class'])):
                    $report .= '<span style="color:#0a0;">'.$error['class'].'</span>';
        endif;
        if (isset($error['type'])):
                    $report .= '<font style="color:#000;">'.$error['type'].'</font>';
        endif;
        $report .= '<strong style="color:#00a;">'.$error['function'].'</strong>';
        $report .= '(<font style="color:#a00;">\''.implode(', ', $args).'\'</font>);';

        if (!is_null($filecode)):
                    if (file_exists($filecode)):
                        $cod = 'sourcecodedebug'.time().rand();
        $report .= '<br /><a href="#alert"'.$cod.'" onclick="document.getElementById(\''.$cod.'\').style.display=\'block\';" style="color:#00a;">';
        $report .= 'Show Source Code</a>';
        $report .= '<div id="'.$cod.'" style="display:none;border:1px solid #444;background-color:#fff; word-wrap:break-word;">'.highlight_file($filecode, true).'</div><br />';
        endif;
        endif;
        endforeach;
        $report = '<div style=\'float:left;text-align:left;\'>'.$report.'</div>';
        //echo $report;
        Logs::alert($report);
    }

    public static function showError($code = null, $message = null, $file = null, $line = null, $context = null)
    {
        //Oraculum::Load('Logs');
        $filecode = null;
        $report = '<h1>'.$message.'</h1>';
        $report .= '<strong>Error Code:</strong> #'.$code.'<br />';
        $report .= '<strong>Source File:</strong> '.$file.' <strong>line '.$line.'</strong><br />';
        $report .= '<h3>Backtrace</h3>';
        $report = '<div style=\'float:left;text-align:left;\'>'.$report.'</div>';
        //echo $report;
        Logs::alert($report);
    }

    public static function stop()
    {
        restore_exception_handler();
        restore_error_handler();
    }

    public static function start()
    {
        set_exception_handler(['ON\Exception', 'showException']);
        set_error_handler(['ON\Exception', 'showError']);
    }

    public static function displayErrors()
    {
        ini_set('display_errors', true);
        error_reporting(E_ALL | E_STRICT);
    }
}
