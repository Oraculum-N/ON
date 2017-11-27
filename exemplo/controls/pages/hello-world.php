<?php
    use ON\App;
use ON\Register;

Register::set('titulo', 'Hello World');
    App::loadView()
        ->addTemplate('geral')
        ->loadPage('hello-world');
