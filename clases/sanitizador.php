<?php

class Sanitizador
{
    public static function texto($dato)
    {
        return trim(htmlspecialchars($dato, ENT_QUOTES, 'UTF-8'));
    }

    public static function titulo($dato)
    {
        $dato = mb_strtolower(trim($dato), 'UTF-8');
        return mb_convert_case($dato, MB_CASE_TITLE, 'UTF-8');
    }

    public static function correo($correo)
    {
        return filter_var(trim($correo), FILTER_SANITIZE_EMAIL);
    }

    public static function numero($numero)
    {
        return filter_var($numero, FILTER_SANITIZE_NUMBER_INT);
    }
}