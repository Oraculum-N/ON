<?php
    use ON\App;
use ON\Register;

Register::set('titulo', 'Home');
    App::loadView()
        ->addTemplate('geral')
        ->loadPage('home');
