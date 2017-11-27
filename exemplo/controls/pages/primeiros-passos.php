<?php
    use ON\App;
use ON\Register;

Register::set('titulo', 'Primeiros Passos');
    App::loadView()
        ->addTemplate('geral')
        ->loadPage('primeiros-passos');
