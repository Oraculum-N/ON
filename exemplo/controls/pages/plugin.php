<?php

$plugin = Oraculum_Request::getvar('plugin');
Oraculum::Load('Plugins');

switch ($plugin):
    case 'calendar':
        Oraculum_Plugins::Load('calendar');
        $calendar = new Oraculum_Calendar();
        Oraculum_Register::set('calendar', $calendar);
    break;
    case 'captcha':
        Oraculum_Plugins::Load('captcha');
        $vcaptcha = Oraculum_Request::getvar('captcha');
        if ($vcaptcha == 'generate') {
            $captcha = new Oraculum_Captcha();
            exit;
        }
    break;
    case 'password-generator':
        Oraculum_Plugins::Load('password-generator');
        $password1 = new Oraculum_PasswordGenerator(1);
        $password2 = new Oraculum_PasswordGenerator(2);
        $password3 = new Oraculum_PasswordGenerator(3);
        Oraculum_Register::set('password1', $password1);
        Oraculum_Register::set('password2', $password2);
        Oraculum_Register::set('password3', $password3);
    break;
    default:
        $plugin = 'default';
endswitch;

    Oraculum_WebApp::LoadView()
        ->AddTemplate('geral')
        ->LoadPage('plugin-'.$plugin);
