<?php

class Firmador
{
    // En un proyecto real esto iría en una variable de entorno (.env),
    // no directamente en el código. Para el parcial es suficiente así.
    private static $clave = "itech_clave_secreta_2025_openssl";

    /**
     * Genera una firma HMAC-SHA256 (motor criptográfico OpenSSL).
     */
    public static function firmar($datos)
    {
        return hash_hmac('sha256', $datos, self::$clave);
    }

    /**
     * Verifica que la firma coincida con los datos originales.
     * Usa hash_equals() para evitar ataques de timing.
     */
    public static function verificar($datos, $firmaGuardada)
    {
        $firmaCalculada = self::firmar($datos);
        return hash_equals($firmaCalculada, $firmaGuardada);
    }

    /**
     * Arma el string estándar que se firma, para que
     * guardar.php y reporte.php generen siempre la misma cadena.
     */
    public static function construirCadena($identidad, $nombre, $apellido, $correo, $celular, $sexo)
    {
        return $identidad . "|" . $nombre . "|" . $apellido . "|" . $correo . "|" . $celular . "|" . $sexo;
    }
}