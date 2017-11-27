<?php

namespace ON;

class Text
{
    // Converter moeda
    public static function moeda($string, $cifrao = true)
    {
        $string = round($string, 2);
        $string = number_format($string, 2, ',', '.');
        if ($cifrao):
                $string = MOEDA.' '.$string;
        endif;

        return $string;
    }

    // Converter moeda para formato SQL
    public static function moedaSql($string)
    {
        $string = str_replace(',', '.', $string);
        $string = round($string, 2);
        $string = number_format($string, 2, '.', ',');
        $string = round($string, 2);

        return $string;
    }

    // Fornecer/Converter data em formato brasileiro
    public static function data($data = null, $notnull = true)
    {
        if (!is_null($data)):
                return date('d/m/Y', strtotime($data)); elseif ($notnull):
                return date('d/m/Y'); else:
                return;
        endif;
    }

    // Fornecer/Converter data do formato brasileiro para o formato do sql
    public static function dataSql($data = null, $notnull = true)
    {
        if (!is_null($data)):
                $data = implode('-', array_reverse(explode('/', $data)));

        return $data; elseif ($notnull):
                return date('Y-m-d'); else:
                return;
        endif;
    }

    // Fornecer/Converter hora em formato brasileiro
    public static function hora($data = null, $notnull = true)
    {
        if (!is_null($data)):
                return date('H:i:s', strtotime($data)); elseif ($notnull):
                return date('H:i:s'); else:
                return;
        endif;
    }

    // Fornecer/Converter data e hora em formato brasileiro
    public static function dataHora($data = null, $notnull = true)
    {
        if (!is_null($data)):
                return date('d/m/Y H:i:s', strtotime($data)); elseif ($notnull):
                return date('d/m/Y H:i:s'); else:
                return;
        endif;
    }

    // Determinar o saudacao do dia
    public static function saudacao()
    {
        $hora = date('H');
        if (($hora >= 6) && ($hora < 12)):
                $saudacao = 'Bom Dia'; elseif (($hora >= 12) && ($hora < 18)):
                $saudacao = 'Boa Tarde'; elseif (($hora >= 18) || ($hora < 6)):
                $saudacao = 'Boa Noite';
        endif;

        return $saudacao;
    }

    public static function getpwd($estrutura = [])
    {
        $pwd = '<span class="url_pwd">/<a href="'.BASE_URL.'/">home</a>';
        $total = count($estrutura);
        $contador = 0;
        foreach ($estrutura as $link=>$descricao):
                $pwd .= '/';
        if (($contador + 1) == $total):
                    $pwd .= '<a href="'.BASE_URL.'/'.$link.'" ';
        $pwd .= 'style="font-weight: bold;">';
        $pwd .= $descricao;
        $pwd .= '</a>'; else:
                    $pwd .= '<a href="'.BASE_URL.'/'.$link.'">';
        $pwd .= $descricao;
        $pwd .= '</a>';
        endif;
        $contador++;
        endforeach;
        $pwd .= '</span><br />';

        return $pwd;
    }

    public static function inflector($palavra, $n = 1, $return = true, $addnumber = true)
    {
        $palavra = $n > 1 ? self::plural($palavra) : $palavra;
        if ($addnumber):
                $str = $n.' '.$palavra; else:
                $str = $palavra;
        endif;
        if ($return):
                echo $str;

        return; else:
                return $str;
        endif;
    }

    public static function plural($palavra)
    {
        if (preg_match('/[sz]$/', $palavra) ||
                    (preg_match('/[^aeioudgkprt]h$/', $palavra))):
                $palavra .= 'es'; elseif (preg_match('/[^aeiou]y$/', $palavra)):
                $palavra = substr_replace($palavra, 'ies', -1); elseif (preg_match('/[x]$/', $palavra)):
                $palavra = $palavra; elseif (preg_match('/[m]$/', $palavra)):
                $palavra = substr_replace($palavra, 'ns', -1); elseif ((preg_match('/[til]$/', $palavra)) ||
                    (preg_match('/[ssil]$/', $palavra))):
                $palavra = substr_replace($palavra, 'eis', -2); elseif (preg_match('/[il]$/', $palavra)):
                $palavra = substr_replace($palavra, 's', -1); elseif (preg_match('/[l]$/', $palavra)):
                $palavra = substr_replace($palavra, 'is', -1); elseif (preg_match('/[rnsz]$/', $palavra)):
                $palavra .= 'es'; else:
                $palavra .= 's';
        endif;

        return $palavra;
    }

    public static function removeAcentos($string)
    {
        $acentos = [
                'A' => '/['.chr(194).chr(192).chr(193).chr(196).chr(195).']/',
                'E' => '/['.chr(202).chr(200).chr(201).chr(203).']/',
                'I' => '/['.chr(206).chr(205).chr(204).chr(207).']/',
                'O' => '/['.chr(212).chr(213).chr(210).chr(211).chr(214).']/',
                'U' => '/['.chr(219).chr(217).chr(218).chr(220).']/',
                'C' => '/['.chr(199).']/',
                'N' => '/['.chr(209).']/',
                'a' => '/['.chr(226).chr(227).chr(224).chr(225).chr(228).']/',
                'e' => '/['.chr(234).chr(232).chr(233).chr(235).']/',
                'i' => '/['.chr(238).chr(237).chr(236).chr(239).']/',
                'o' => '/['.chr(244).chr(245).chr(242).chr(243).chr(246).']/',
                'u' => '/['.chr(251).chr(250).chr(249).chr(252).']/',
                'c' => '/['.chr(231).']/',
                'n' => '/['.chr(241).']/',
            ];

        return preg_replace(array_values($acentos),
                                array_keys($acentos),
                                $string);
    }

    public static function t($constant, $autoreturn = true)
    {
        if ($autoreturn):
                echo constant($constant);

        return; else:
                return constant($constant);
        endif;
    }

    public static function lang($constant, $autoreturn = true)
    {
        return self::t('LANG_'.strtoupper($constant), $autoreturn);
    }

    public static function httplink($link = null)
    {
        if (!is_null($link)):
                $valid = (strpos($link, 'http://') > 0);
        if (!$valid) {
            $valid = (strpos($link, 'https://') > 0);
        }
        if (!$valid) {
            $link = 'http://'.$link;
        }

        return $link; else:
                return;
        endif;
    }

    public static function mascara($string, $mascara = null)
    {
        if (!is_null($mascara)):
                $result = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mascara) - 1; $i++):
                    if ($mascara[$i] == '#'):
                        if (isset($string[$k])):
                            $result .= $string[$k++];
        endif; else:
                        if (isset($mascara[$i])):
                            $result .= $mascara[$i];
        endif;
        endif;
        endfor;

        return $result; else:
                throw new Exception('[Error '.__METHOD__.'] Mascara nao informada');
        endif;
    }
}
