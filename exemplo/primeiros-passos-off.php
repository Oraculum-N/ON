<?php
    /******************************************************
    * Arquivo apenas para instruções caso o Apache
    * não esteja com o Módulo Rewrite Ativo!
    ******************************************************/
    define('URL', './');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Oraculum Framework</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="shortcut icon" href="<?php echo URL; ?>public/favicon.ico" type="image/x-icon" title="&Iacute;cone" />
        <link rel="icon" href="<?php echo URL; ?>public/favicon.gif" type="image/gif" />
        <link rel='stylesheet' media='screen' href='<?php echo URL; ?>public/css/geral.css' type='text/css' />
        <link rel='stylesheet' media='screen' href='<?php echo URL; ?>public/css/bootstrap.css' type='text/css' />
    </head>
    <body>
		<div id="all">
			<?php include './views/elements/menu.php'; ?>
<div id="content">
    <h1>Primeiros Passos</h1>
<p>
Identificamos que o seu servidor Web n&atilde;o est&aacute; com reescrita de URL habilitada.<br /><br />
Para habilitar &eacute; simples:

<h2>No Windows:</h2>
<p>Edite o arquivo httpd.conf e procure por mod_rewrite. Remova o ponto e v&iacute;rgula do come&ccedil;o da linha, salve o arquivo e reinicie o Apache.
<br /><br />
<small>Obs: Alguns softwares servidores no Windows como Wamp e Xamp possuem gerenciadores que podem simplificar este processo atrav&eacute;s de um menu (Apache &gt; M&oacute;dulos Apache)</small></p>
<br /><br />
<h2>No Linux:</h2>
<p>
Pode ser realizado o mesmo procedimento feito no Windows, ou ent&atilde;o execute o comando <strong>a2enmod rewrite</strong>, e reinicie o Apache.
<br /><br />

Acesse novamente o link <strong>Primeiros Passos</strong> dispon&iacute;vel no menu at&eacute; que apare&ccedil;a uma p&aacute;gina diferente dessa. (Atualizar a p&aacute;gina n&atilde;o funcionar&aacute;)

</p>
</div>
<?php
    include 'views/elements/footer.php';
