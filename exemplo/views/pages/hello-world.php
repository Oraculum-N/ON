<?php
    ON\Register::set('titulo', 'Suporte');
 ?>
<div id="content">
    <h1>Hello World</h1>
<p>
<p>Crie um arquivo no diret&oacute;rio de controladores (como padr&atilde;o est&aacute; localizada em controls/pages) chamado <strong>helloworld.php</strong> e inclua o seguinte conte&uacute;do:</p>
    
    

<p>
<?php 
    $code = '<?php
    use ON\App;
    class HelloWorldController{
        public function __construct() {
            App::loadView()
                ->addTemplate(\'geral\')
                ->loadPage(\'helloworld\');
            }
        }';
    highlight_string($code);
?>
</p>
<p>&nbsp;</p>
<p>se desejar pode incluir apenas</p>
    
<p>
<?php 
    $code = '<?php
    use ON\App;
    App::loadView()
        ->addTemplate(\'geral\')
        ->loadPage(\'helloworld\');';
    highlight_string($code);
?>
</p>
<p>A fun&ccedil;&atilde;o <strong>loadPage</strong> carrega a view com o nome <em>helloworld</em>, logo, voc&ecirc; deve criar um arquivo <strong>helloworld.php</strong> na pasta das views (como padr&atilde;o est&aacute; localizada em views/pages mas o caminho tamb&eacute;m pode ser alterado)</p>
<p>Inclua neste arquivo o que quiser...</p>
<p>Como padr&atilde;o, os arquivos que vem junto com o Oraculum possuem configurado a fun&ccedil;&atilde;o de front controller, logo, se voc&ecirc; acessar a url do seu sistema seguido de helloworld voc&ecirc; deve abrir a p&aacute;gina que voc&ecirc; acabou de criar...</p>
<p>D&uacute;vidas podem ser sanadas atrav&eacute;s do menu suporte ;)</p>
<p>Boa sorte</p>

</p>
</div>