<?php
    Oraculum_Register::set('titulo', 'Home');
    $password1 = Oraculum_Register::get('password1'); // Captura o objeto criado no controlador
    $password2 = Oraculum_Register::get('password2'); // Captura o objeto criado no controlador
    $password3 = Oraculum_Register::get('password3'); // Captura o objeto criado no controlador
    echo $password1.'<br />';
    echo $password2.'<br />';
    echo $password3.'<br />';
