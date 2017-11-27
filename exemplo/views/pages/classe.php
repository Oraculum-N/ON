<?php
    use ON\Register;

//Oraculum::Load('Register');
    Register::set('titulo', 'Suporte');
    $content = Register::get('content');
 ?>
<div id="content">
    <?php if (!is_null($content)): ?>
<div class="class">
    <?php echo $content; ?>
</div>
    <?php else: ?>
        Documenta&ccedil;&atilde;o n&atilde;o encontrada ;(
    <?php endif; ?>
</div>
