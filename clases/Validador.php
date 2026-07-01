<?php

class Validador
{
    public static function requerido($dato)
    {
        return !empty(trim($dato));
    }

    public static function correo($correo)
    {
        return filter_var($correo, FILTER_VALIDATE_EMAIL);
    }

    public static function edad($edad)
    {
        return is_numeric($edad) && $edad >= 12 && $edad <= 100;
    }

    public static function celular($celular)
    {
        return preg_match('/^[0-9]{8,15}$/', $celular);
    }
}