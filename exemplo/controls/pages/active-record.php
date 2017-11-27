<?php
    use ON\App;
use ON\Register;

Register::set('titulo', 'Active Record');
    App::loadView()
        ->addTemplate('geral')
        ->loadPage('active-record');
