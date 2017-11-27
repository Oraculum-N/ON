<?php
    use ON\App;
use ON\Register;
use ON\Request;

$classe = Request::getvar('classe');

$files = '<h2>Oraculum_Files</h2><br /><strong>inc</strong><br />
Oraculum_Files::inc($file)<br />
<br />
Verifica se o arquivo informado pela vari&aacute;vel $file existe,
e se existir inclu&iacute; o mesmo e retorna TRUE, sen&atilde;o retorna FALSE.<br />
<br />
PAR&Acirc;METROS<br />
  $file STRING<br />
  Retorno: BOOL<br />
<strong>file_filter</strong><br />
Oraculum_Files::file_filter($filetype, $filter, $negative)<br />
<br />
Verifica se o mimetype do arquivo &eacute; v&aacute;lido de acordo com o vetor
$filter,
onde s&atilde;o listadas as extens&otilde;es. Caso o valor da vari&aacute;vel
$negative seja falso, ser&aacute; verificado se o mimetype corresponde a uma das
extens&otilde;es do vetor $filter. Caso o valor da vari&aacute;vel $negative
seja verdadeiro, ser&aacute; verificado se o mimetype n&atilde;o corresponde a
nenhuma das extens&otilde;es do vetor $filter.<br />
<br />
PAR&Acirc;METROS<br />
  $filetype STRING<br />
  $filter ARRAY<br />
  $negative BOOL<br />
  Retorno: BOOL<br />';
$forms = '<h2>Oraculum_Forms</h2><strong>forms</strong><br />
Oraculum_Forms::forms($valor, $tipo, $notnull) <br />
<br />
Verifica se o valor da vari&aacute;vel $valor &eacute; v&aacute;lido de acordo com o tipo definido pela vari&aacute;vel $tipo. Caso o valor da vari&aacute;vel $valor seja NULL, o retorno da fun&ccedil;&atilde;o depender&aacute; do valor da vari&aacute;vel $notnull, retornando TRUE caso $notnull seja FALSE, e FALSE caso $notnull seja TRUE. <br />
<br />
Segue abaixo a rela&ccedil;&atilde;o de tipos dispon&iacute;veis: <br />
<br />
  Tipos: <br />
    s: string <br />
    n: num&eacute;rico <br />
    i: inteiro <br />
    c: CPF <br />
    e: email <br />
    E: email validando o dom&iacute;nio <br />
    d: data <br />
    N: nulo <br />
    <br />
PAR&Acirc;METROS <br />
  $valor STRING <br />
  $tipo STRING <br />
  $notnull BOOL <br />
  Retorno: BOOL<br />
<strong>verificaCPF</strong><br />
Oraculum_Forms::verificaCPF($cpf) <br />
<br />
Verifica se o valor da vari&aacute;vel $cpf &eacute; um CPF v&aacute;lido de acordo com o padr&atilde;o brasileiro. <br />
<br />
PAR&Acirc;METROS <br />
  $cpf STRING <br />
  Retorno: BOOL <br />
<strong>verificaEmail</strong><br />
Oraculum_Forms::verificaEmail($email) <br />
<br />
Verifica se o valor da vari&aacute;vel $email &eacute; um e-mail v&aacute;lido, verificando o DNS do dom&iacute;nio do endere&ccedil;o de e-mail. <br />
<br />
PAR&Acirc;METROS <br />
  $email STRING <br />
  Retorno: BOOL';

$http = '<h2>Oraculum_HTTP</h2><br />
    <strong>redirect</strong><br />
    Oraculum_HTTP::redirect($url)<br /> <br />
    Realiza o redirecionamento para uma URL determinada pelo par&acirc;metro $url.<br />
    Caso o redirecionamento atrav&eacute;s de PHP n&atilde;o seja realizado o redirecionamento atrav&eacute;s de Javascript.<br /> <br />
    PAR&Acirc;METROS<br /> $url STRING<br /> Retorno: -<br />
    <strong>ip</strong><br />
    Oraculum_HTTP::ip()<br /> <br />
    Retorna o valor de $_SERVER[&quot;REMOTE_ADDR&quot;], que contem o endere&ccedil;o IP do usu&aacute;rio.<br /> <br />
    PAR&Acirc;METROS<br />
    Retorno: STRING<br />
    <strong>host</strong><br />
    Oraculum_HTTP::host()<br /> <br />
    Retorna o valor de $_SERVER[&quot;REMOTE_HOST&quot;], que contem o endere&ccedil;o de dom&iacute;nio do usu&aacute;rio.<br />
    Caso n&atilde;o seja encontrado algum valor, ser&aacute; retornado o endere&ccedil;o IP do usu&aacute;rio.<br /> <br />
    PAR&Acirc;METROS<br /> Retorno: STRING';
$logs = '<h2>Oraculum_Logs</h2><br /> 5.1 alert<br /> Oraculum_Forms::alert($mensagem, $log)<br /> <br /> Exibe uma mensagem de alerta de uma forma mais apresent&aacute;vel, &uacute;til para depura&ccedil;&atilde;o.<br /> Caso o valor da vari&aacute;vel $log seja TRUE, ser&aacute; gravado o alerta em um arquivo de log.<br /> <br /> PAR&Acirc;METROS<br /> $mensagem STRING<br /> $log BOOL<br /> Retorno: -<br /> 5.2 showException<br /> Oraculum_Forms::showException($e)<br /> <br /> Fun&ccedil;&atilde;o para tratar exce&ccedil;&otilde;es.<br /> <br /> PAR&Acirc;METROS <br /> $e EXCEPTION<br /> Retorno: -';
$http = '<h2>Oraculum_Request</h2><br /> 6.1 post<br /> Oraculum_Forms::post($indice, [$tipo])<br /> <br /> Captura o valor de um &iacute;ndice da vari&aacute;vel superglobal $_POST, realizando filtro de acordo com o tipo definido.<br /> Caso n&atilde;o tenha sido definido nenhum valor para o &iacute;ndice, ser&aacute; retornado NULL, sem gerar nenhum alerta de &iacute;ndice n&atilde;o definido.<br /> <br /> PAR&Acirc;METROS<br /> $indice STRING<br /> $tipo STRING<br /> Retorno: STRING|NULL<br /> <br /> $tipo:<br /> &quot;s&quot; (padr&atilde;o): Captura apenas strings, filtrando tags HTML e PHP.<br /> &quot;n&quot;: Captura apenas valores num&eacute;ricos, filtrando qualquer outra vari&aacute;vel, caso o valor n&atilde;o seja um n&uacute;mero, ser&aacute; retornado NULL.<br /> &quot;h&quot;: Captura todo o valor, sem realizar nenhum filtro, recebendo tags HTML, PHP, etc.<br /> 6.2 get<br /> Oraculum_Request::get($indice, [$tipo])<br /> <br /> Captura o valor de um &iacute;ndice da vari&aacute;vel superglobal $_GET, realizando filtro de acordo com o tipo definido.<br /> Caso n&atilde;o tenha sido definido nenhum valor para o &iacute;ndice, ser&aacute; retornado NULL, sem gerar nenhum alerta de &iacute;ndice n&atilde;o definido.<br /> <br /> PAR&Acirc;METROS<br /> $indice STRING<br /> $tipo STRING<br /> Retorno: STRING|NULL<br /> <br /> $tipo:<br /> &quot;s&quot; (padr&atilde;o): Captura apenas strings, filtrando tags HTML e PHP.<br /> &quot;n&quot;: Captura apenas valores num&eacute;ricos, filtrando qualquer outra vari&aacute;vel, caso o valor n&atilde;o seja um n&uacute;mero, ser&aacute; retornado NULL.<br /> 6.3 file<br /> Oraculum_Request::file($indice, $atributo, $filter) Oraculum_Request::file($indice, $atributo, $filter)<br /> <br /> Captura o valor de um &iacute;ndice da vari&aacute;vel superglobal $_FILE, realizando filtro de acordo com o tipo definido.<br /> Caso n&atilde;o tenha sido definido nenhum valor para o &iacute;ndice, ser&aacute; retornado NULL, sem gerar nenhum alerta de &iacute;ndice n&atilde;o definido.<br /> <br /> PAR&Acirc;METROS<br /> $indice STRING<br /> $tipo STRING<br /> Retorno: STRING|NULL<br /> <br /> $tipo:<br /> &quot;s&quot; (padr&atilde;o): Captura apenas strings, filtrando tags HTML e PHP.<br /> &quot;n&quot;: Captura apenas valores num&eacute;ricos, filtrando qualquer outra vari&aacute;vel, caso o valor n&atilde;o seja um n&uacute;mero, ser&aacute; retornado NULL.<br /> 6.4 sess<br /> Oraculum_Request::sess($indice)<br /> <br /> Captura o valor de um &iacute;ndice da vari&aacute;vel superglobal $_SESSION, realizando filtro de acordo com o tipo definido.<br /> Caso n&atilde;o tenha sido definido nenhum valor para o &iacute;ndice, ser&aacute; retornado NULL, sem gerar nenhum alerta de &iacute;ndice n&atilde;o definido.<br /> <br /> PAR&Acirc;METROS<br /> $indice STRING<br /> Retorno: STRING|NULL<br /> 6.5 setsess<br /> Oraculum_Request::setsess($indice, $valor)<br /> <br /> Define o valor de um &iacute;ndice da vari&aacute;vel superglobal $_SESSION<br /> <br /> PAR&Acirc;METROS<br /> $indice STRING<br /> $valor STRING<br /> Retorno: BOOL<br /> 6.6 unsetsess<br /> Oraculum_Request::unsetsess($indice)<br /> <br /> Remove um &iacute;ndice da vari&aacute;vel superglobal $_SESSION<br /> <br /> PAR&Acirc;METROS<br /> $indice STRING<br /> Retorno: BOOL<br /> 6.7 init_sess<br /> Oraculum_Request::init_sess()<br /> <br /> Inicializa a sess&atilde;o, definindo o diret&oacute;rio de armazenamento de sess&otilde;es de acordo com a constante DIR_TMP, caso o valor desta seja diferente do par&acirc;metro de configura&ccedil;&atilde;o session.save_path.<br /> <br /> PAR&Acirc;METROS<br /> Retorno: -<br /> 6.8 cookie<br /> Oraculum_Request::cookie($indice)<br /> <br /> Captura o valor de um &iacute;ndice da vari&aacute;vel superglobal $_COOKIE.<br /> Caso n&atilde;o tenha sido definido nenhum valor para o &iacute;ndice, ser&aacute; retornado NULL, sem gerar nenhum alerta de &iacute;ndice n&atilde;o definido.<br /> <br /> PAR&Acirc;METROS<br /> $indice STRING<br /> Retorno: STRING|NULL<br /> 6.9 set_cookie<br /> Oraculum_Request::set_cookie($nome, $valor, $expirar=null)<br /> <br /> Define o valor de um &iacute;ndice da vari&aacute;vel superglobal $_COOKIE, com seu prazo de expira&ccedil;&atilde;o<br /> <br /> PAR&Acirc;METROS<br /> $indice STRING<br /> $valor STRING<br /> $expirar INT (Padr&atilde;o: 604800 | 7 dias)<br /> Retorno: BOOL<br /> 6.10 getpagina<br /> Oraculum_Request::getpagina($gets)<br /> <br /> Captura o n&uacute;mero de uma p&aacute;gina da URL, seguindo o padr&atilde;o /page/[N&Uacute;MERO].<br /> Caso n&atilde;o tenha sido definido nenhum valor para o &iacute;ndice, ser&aacute; retornado NULL, sem gerar nenhum alerta de &iacute;ndice n&atilde;o definido.<br /> <br /> PAR&Acirc;METROS<br /> $gets ARRAY|NULL<br /> Retorno: STRING|NULL<br /> 6.11 getid<br /> Oraculum_Request::getid($gets, $posicao)<br /> <br /> Captura um c&oacute;digo/ID da URL, de acordo com uma posi&ccedil;&atilde;o pr&eacute;-definida pela vari&aacute;vel $posicao, que determina o segmento que ser&aacute; capturado da URL de acordo com o padr&atilde;o (TOTAL_DE_SEGMENTOS-$posicao)<br /> Caso n&atilde;o tenha sido definido nenhum valor para o &iacute;ndice, ser&aacute; retornado NULL, sem gerar nenhum alerta de &iacute;ndice n&atilde;o definido.<br /> <br /> PAR&Acirc;METROS<br /> $gets ARRAY|NULL<br /> $posicao INT (Padr&atilde;o 1)<br /> Retorno: STRING|NULL<br /> 6.12 getlast<br /> Oraculum_Request::getlast($gets)<br /> <br /> Captura o &uacute;ltimo segmento de uma URL<br /> <br /> PAR&Acirc;METROS<br /> $gets ARRAY|NULL<br /> Retorno: STRING<br /> 6.13 getvar<br /> Oraculum_Request::getvar($index, $default)<br /> <br /> Captura o segmento de uma URL, precedido pelo par&acirc;metro $index.<br /> Caso o par&acirc;mero $index n&atilde;o seja definido, ser&aacute; retornado o &uacute;ltimo segmento da URL.<br /> Caso o par&acirc;metro n&atilde;o seja encontrado ser&aacute; retornado o valor da vari&aacute;vel $default.<br /> <br /> PAR&Acirc;METROS<br /> $index STRING|1 (Padr&atilde;o: 1)<br /> $default STRING<br /> Retorno: STRING<br /> 6.14 gets<br /> Oraculum_Request::gets()<br /> <br /> Captura a URL e converte em um vetor de segmentos divididos pelo caractere /<br /> Caso o par&acirc;mero $index n&atilde;o seja definido, ser&aacute; retornado o &uacute;ltimo segmento da URL.<br /> Caso o par&acirc;metro n&atilde;o seja encontrado ser&aacute; retornado o valor da vari&aacute;vel $default.<br /> <br /> PAR&Acirc;METROS<br /> Retorno: ARRAY<br /> 6.15 request<br /> Oraculum_Request::request()<br /> <br /> Captura a URL ($_SERVER[&quot;REQUEST_URI&quot;]).<br /> <br /> PAR&Acirc;METROS<br /> Retorno: STRING';
$routes = '<h2>Oraculum_Routes</h2><br /> 7.1 add<br /> Oraculum_Routes::add($origem, $destino)<br /> <br /> Altera partes da URL, substituindo as ocorr&ecirc;ncias de $origem para $destino<br /> <br /> PAR&Acirc;METROS<br /> $origem STRING<br /> $destino STRING<br /> Retorno: NULL<br /> 7.2 check<br /> Oraculum_Routes::check()<br /> <br /> Inclui o arquivo routes.php da pasta de controladores da pasta do projeto<br /> apps/[PROJECT]/controllers/routes.php<br /> Este arquivo deve ter chamadas para a fun&ccedil;&atilde;o abstrata Oraculum_Routes::add';
$security = '<h2>Oraculum_Security</h2><br /> 8.1 clearSqlInject<br /> Oraculum_Security::clearSqlInject()<br /> <br /> Realiza um filtro nas vari&aacute;veis globais $_GET, $_POST e $_REQUEST<br /> <br /> PAR&Acirc;METROS<br /> Retorno: -';
$text = '<h2>Oraculum_Text</h2><br /> 9.1 moeda<br /> Oraculum_Text::moeda($string, $cifrao)<br /> <br /> Converte uma string para um valor monet&aacute;rio no padr&atilde;o brasileiro.<br /> Caso o valor do par&acirc;metro $cifrao seja TRUE, ser&aacute; retornado tamb&eacute;m a<br /> constante MOEDA antes do valor monet&aacute;rio.<br /> <br /> PAR&Acirc;METROS<br /> $string STRING<br /> $cifrao BOOL (Padr&atilde;o: TRUE)<br /> Retorno: STRING<br /> 9.2 moedasql<br /> Oraculum_Text::moedasql($string)<br /> <br /> Converte uma string correspondente ao valor monet&aacute;rio no padr&atilde;o brasileiro,<br /> para o padr&atilde;o de n&uacute;meros flutuantes compat&iacute;vel com o padr&atilde;o SQL.<br /> <br /> PAR&Acirc;METROS<br /> $string STRING<br /> Retorno: STRING<br /> 9.3 data<br /> Oraculum_Text::data($data, $notnull)<br /> <br /> Tenta converter uma string para uma data no padr&atilde;o brasileiro (dd/mm/YYYY).<br /> Caso o valor do par&acirc;metro $notnull seja TRUE, ser&aacute; retornada a data atual.<br /> Caso contr&aacute;rio ser&aacute; retornado NULL.<br /> <br /> PAR&Acirc;METROS<br /> $data STRING<br /> $notnull BOOL (Padr&atilde;o: TRUE)<br /> Retorno: STRING<br /> 9.4 data_sql<br /> Oraculum_Text::data_sql($data, $notnull)<br /> <br /> Tenta converter uma string para uma data do padr&atilde;o brasileiro (dd/mm/YYYY),<br /> para o padr&atilde;o de datas compat&iacute;vel com o padr&atilde;o SQL (YYYY-mm-dd).<br /> Caso o valor do par&acirc;metro $notnull seja TRUE, ser&aacute; retornada a data atual.<br /> Caso contr&aacute;rio ser&aacute; retornado NULL.<br /> <br /> PAR&Acirc;METROS<br /> $data STRING<br /> $notnull BOOL (Padr&atilde;o: TRUE)<br /> Retorno: STRING<br /> 9.5 data_mysql<br /> Oraculum_Text::data_mysql($data, $notnull)<br /> <br /> O mesmo que Oraculum_Text::data_sql()<br /> <br /> PAR&Acirc;METROS<br /> $data STRING<br /> $notnull BOOL (Padr&atilde;o: TRUE)<br /> Retorno: STRING<br /> 9.6 getpwd<br /> Oraculum_Text::getpwd($estrutura)<br /> <br /> Cria atrav&eacute;s do vetor $estrutura, um menu horizontal com os &iacute;ndices do vetor,<br /> para a cria&ccedil;&atilde;o de estruturas elaboradas de navega&ccedil;&atilde;o de sites e sistemas.<br /> <br /> PAR&Acirc;METROS<br /> $estrutura ARRAY<br /> Retorno: STRING<br /> 9.7 inflector<br /> Oraculum_Text::inflector($palavra, $n, $return, $addnumber)<br /> <br /> Analisa o valor do par&acirc;metro $n, se este for maior do que 1, o valor do par&acirc;metro $palavra &eacute; convertido para o plural.<br /> Caso contr&aacute;rio, se mantem no singular.<br /> Caso o valor do par&acirc;metro $return seja TRUE, ser&aacute; impresso na tela o resultado, caso contr&aacute;rio o resultado ser&aacute; o retorno da fun&ccedil;&atilde;o.<br /> Caso o valor do par&acirc;metro $addnumber seja TRUE, o valor do par&acirc;metro $n ser&aacute; adicionado no in&iacute;cio do resultado.<br /> <br /> PAR&Acirc;METROS<br /> $palavra STRING<br /> $n INT (Padr&atilde;o: 1)<br /> $return BOOL (Padr&atilde;o: TRUE)<br /> $addnumber BOOL (Padr&atilde;o: TRUE)<br /> Retorno: STRING<br /> 9.8 plural<br /> Oraculum_Text::plural($palavra)<br /> Converte uma palavra para o plural de acordo com o Portugu&ecirc;s do Brasil.<br /> <br /> PAR&Acirc;METROS<br /> $palavra STRING<br /> Retorno: STRING<br /> 9.9 removeacentos<br /> Oraculum_Text::removeacentos($string)<br /> Converte os caracteres especiais do valor do par&acirc;metro $string para<br /> caracteres normais.<br /> <br /> Converte uma palavra para o plural de acordo com o Portugu&ecirc;s do Brasil.<br /> <br /> PAR&Acirc;METROS<br /> $palavra STRING<br /> Retorno: STRING<br /> 9.10 t<br /> Oraculum_Text::t($constant)<br /> Retorna o valor da constante definida pelo par&acirc;metro $constant.<br /> <br /> PAR&Acirc;METROS<br /> $palavra STRING<br /> Retorno: STRING<br /> 9.11 lang<br /> Oraculum_Text::lang($constant)<br /> Retorna o valor da constante definida pelo par&acirc;metro $constant e pelo prefixo &quot;LANG_&quot;.<br /> PAR&Acirc;METROS<br /> $palavra STRING<br /> Retorno: STRING';
$views = '<strong>Oraculum_Views</strong><br /> 10.1 layout<br /> Oraculum_Views::layout($tipo, $autoreturn)<br /> <br /> Retorna o endere&ccedil;o absoluto onde s&atilde;o armazenados os arquivos do Layout do projeto, de acordo com o par&acirc;metro $tipo.<br /> Os arquivos de Layout do projeto s&atilde;o armazenados por padr&atilde;o nas seguintes pastas de acordo com o par&acirc;metro $tipo:<br /> css: /layout/[PROJECT]/css/<br /> img: /layout/[PROJECT]/img/<br /> js: /layout/[PROJECT]/js/<br /> swf: /layout/[PROJECT]/swf/<br /> <br /> Caso o valor do par&acirc;metro $autoreturn seja TRUE, a fun&ccedil;&atilde;o ir&aacute; imprimir o endere&ccedil;o, caso contr&aacute;rio, a fun&ccedil;&atilde;o apenas ir&aacute; retornar o endere&ccedil;o.<br /> BOOL<br /> <br /> PAR&Acirc;METROS<br /> $tipo STRING<br /> $autoreturn BOOL<br /> Retorno: STRINT|-<br /> 10.2 loadview<br /> Oraculum_Views::loadview($view, $modulo, $titulo, $vars)<br /> Realiza a inclus&atilde;o do arquivo da camada View correspondente aos par&acirc;metros $view e $modulo,<br /> que representam a a&ccedil;&atilde;o e o m&oacute;dulo que desejam ser inclu&iacute;dos.<br /> O par&acirc;metro $titulo serve para retornar uma vari&aacute;vel com t&iacute;tulos de p&aacute;ginas HTML<br /> J&aacute; o vetor representado pelo par&acirc;metro $vars, tem a utilidade de receber as demais vari&aacute;veis<br /> que houver necessidade de se passar entre uma camada e outra.<br /> <br /> PAR&Acirc;METROS<br /> $view STRING<br /> $modulo STRING<br /> $titulo STRING<br /> $vars ARRAY<br /> Retorno: STRING';
//$string='';
//echo htmlentities(nl2br(htmlentities(utf8_decode($string))));exit;
if (isset($$classe)) {
    Register::set('content', $$classe);
}
    App::loadView()
        ->addTemplate('geral')
        ->loadPage('classe');
