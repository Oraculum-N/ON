<?php
    /*
        Abaixo e' utilizada a classe Oraculum_Register,
        por isso e' utilizado Oraculum::Load('Regiter')
        para carregar o modulo necessario.

        O ideal e chamar os modulos no Bootstrap (arquivo de
        inicializacao), ou nos controladores.
        O motivo de estar aqui e' apenas para demonstracao.
    */

    /*
        A classe Oraculum_Register serve para registrar variaveis
        entre arquivos (controladores, views e models)
        Para definir: Oraculum_Register::set('variavel', 'valor');
        Para recuperar: Oraculum_Register::get('variavel');
    */
    ON\Register::set('titulo', 'Erro 404 - P&aacute;gina n&atilde;o encontrada');
 ?>
<div id="erro">
<h1>A p&aacute;gina n&atilde;o pode ser encontrada ;-(</h1>
<h2>HTTP ERRO 404</h2>
<strong>Causas mais comuns:</strong><br />

<ul>
  <li>
    Pode haver um erro na digita&ccedil;&atilde;o do endere&ccedil;o.
  </li>
  <li>
    Se voc&ecirc; clicou em um link, ele pode estar desatualizado.
  </li>
</ul>
<br />
<strong>Solu&ccedil;&otilde;es:</strong><br />
<ul>
  <li>
    Digite novamente o endere&ccedil;o.
  </li>
  <li>
    <a href="#" onclick="history.back();return false">Volte para a p&aacute;gina anterior.</a>
  </li>
  <li>
    <a href="<?php echo URL; ?>">Acesse a p&aacute;gina inicial e procure as informa&ccedil;&otilde;es que deseja.</a>
  </li>
</ul>
</div>
