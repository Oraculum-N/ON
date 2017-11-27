<?php
    include './bootstrap.php'; // Carrega arquivo de inicializacao

    use ON\ON;

    $app = ON::App();
    $app->start()
        ->setBaseUrl('/ON/exemplo/') // Define qual a URL base
        ->setDefaultPage('home') // Define qual a pagina padrao
        ->setErrorPage('404') // Define qual a pagina de erro
        ->start();
