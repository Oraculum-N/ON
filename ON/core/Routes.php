<?php

namespace ON;

class Routes
{
    private function __construct()
    {
    }

    public static function add($origem, $destino)
    {
        $request = Request::request();
        $_SERVER['REQUEST_URI'] = str_replace($origem, $destino, $request);
    }
}
