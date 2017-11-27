<?php

    /*
        A classe Register serve para registrar variaveis
        entre arquivos (controladores, views e models)
        Para definir: Oraculum_Register::set('variavel', 'valor');
        Para recuperar: Oraculum_Register::get('variavel');
    */
    ON\Register::set('titulo', 'Home');
?>
<a href="http://oraculumframework.org/" target="_blank">
	<img src="<?php echo URL; ?>public/img/oraculum.png" alt="Oraculum Framework" title="Oraculum" id="logo" />
</a>
<br />
<div id="content">
    <em>Oraculum Framework :: <?php echo $_SERVER['SERVER_NAME']; ?></em>
<p>
<br />
<?php echo ON\Text::saudacao(); ?> nobre desenvolvedor!<br />
Voc&ecirc; est&aacute; agora sendo apresentado a uma aplica&ccedil;&atilde;o modelo,
desenvolvida inteiramente com o Oraculum Framework para que voc&ecirc; possa
entender de forma uma forma mais simples poss&iacute;vel o seu funcionamento.<br /><br />
Sinta-se &agrave; vontade para explorar os c&oacute;digos e fazer altera&ccedil;&otilde;es
para se familiarizar com este novo ambiente de desenvolvimento.<br /><br />

Qualquer d&uacute;vida acesse o site oficial do projeto e entre em contato:<br />
<a href="http://oraculumframework.org/" target="_blank">
http://oraculumframework.org/</a><br /><br />

Oraculum Framework<br />
<em>Um novo conceito em desenvolvimento Web</em>
</p>
</div>