<?php
    ON\Register::set('titulo', 'Suporte');
    $bancos = ON\Register::get('bancos');
    $nomebanco = ON\Register::get('nomebanco');
    $model = ON\Register::get('model');
    $arquivo = ON\Register::get('arquivo');
    $control = ON\Register::get('control');
    $view = ON\Register::get('view');
    $viewlistar = ON\Register::get('viewlistar');
    $msg = ON\Register::get('msg');
 ?>
<div id="content" style="text-align:left;">
    <h1>Gerador de C&oacute;digos</h1>
<p>Atualmente o Oraculum possui um gerador de c&oacute;digos simples, onde voc&ecirc; informa qual o driver do Banco de Dados, os dados de conex&otilde;es, a base e a tabela alvo.<br />
Com isso &eacute; criado um <strong>controlador</strong> e <strong>views</strong> para realizar os processos de listagem e exclus&atilde;o (devido ao quesito flexibilidade, ainda n&atilde;o est&aacute; gerando c&oacute;digo de cadastro e edi&ccedil;&otilde;es).<br />
Vale observar que ao informar os campos abaixo, caso voc&ecirc; informe dados de conex&atilde;o inv&aacute;lidos, dever&aacute; retorar erro.</p>
<form method="post" action="?" style="text-align:left;">
	<?php if (count($bancos) > 0): ?>
	Banco de Dados<br />
	<select name="banco" id="banco">
		<?php foreach ($bancos as $banco): ?>
			<option><?php echo $banco; ?></option>
		<?php endforeach; ?>
	</select> <small>(Escolha um dos Drivers PDO dispon&iacute;veis no seu servidor)</small>
	<?php endif; ?><br />
	Servidor:<br />
	<input type="text" name="servidor" id="servidor" value="127.0.0.1" />
	<br />
	Base de Dados:<br />
	<input type="text" name="base" id="base" value="" />
	<br />
	Usu&aacute;rio:<br />
	<input type="text" name="usuario" id="usuario" value="root" />
	<br />
	Senha:<br />
	<input type="password" name="senha" id="senha" value="" />
	<br />
	Tabela:<br />
	<input type="text" name="tabela" id="tabela" value="" />
	<br />
	<input type="submit" name="send" id="send" value="Gerar" />
</form>
<hr />
<?php if ($msg != ''): ?>
	<?php alert($msg); ?>
<?php endif; ?>
<?php if ($model != ''): ?>
	models/<?php echo $nomebanco; ?>.php
	<div style="border:1px solid #444; padding:10px;"><?php highlight_string($model); ?></div>
<?php endif; ?>

<?php if ($control != ''): ?>
	controls/<?php echo $arquivo; ?>.php
	<div style="border:1px solid #444; padding:10px;"><?php highlight_string($control); ?></div>
<?php endif; ?>

<?php if ($viewlistar != ''): ?>
	views/<?php echo $arquivo; ?>-listar.php
	<div style="border:1px solid #444; padding:10px;"><?php
highlight_string($viewlistar); ?></div>
<?php endif; ?>
</div>