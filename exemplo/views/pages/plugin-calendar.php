    <?php
    Oraculum_Register::set('titulo', 'Home');

    $ano = Oraculum_Request::getvar('ano'); //Captura o que tiver depois de /ano na URL
    $mes = Oraculum_Request::getvar('mes'); //Captura o que tiver depois de /mes na URL

    $calendar = Oraculum_Register::get('calendar'); // Captura o objeto criado no controlador

    $calendar->addEvent('Hoje &eacute; o dia que voc&ecirc; est&aacute; testando o Oraculum Framework!',
            [date('Y'), date('m'), date('d')]); // Adiciona um evento na agenda
    $calendar->seturl(URL.'plugin/calendar/ano/%y/mes/%m'); // Define qual o padrao da URL
    $calendar->setmonth((int) $mes);
    $calendar->setyear((int) $ano);
    $calendar->showmonth(false);
    $controller = $calendar->getcontroller();
    echo $controller;
    $calendar->show();
?>
<link rel='stylesheet' media='screen' href='<?php echo URL; ?>public/css/calendar.css' type='text/css' />