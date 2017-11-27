<?php
use ON\App;

header('HTTP/1.1 404 Not Found');
    App::loadView()
        ->addTemplate('geral')
        ->loadPage('404');
