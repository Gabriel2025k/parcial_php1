<?php

require_once "config/Conexion.php";
require_once "clases/Sanitizador.php";
require_once "clases/Validador.php";
require_once "clases/Firmador.php";

$db = new Conexion();

// ---------- Sanitización ----------
$identidad     = Sanitizador::texto($_POST['identidad'] ?? '');
$nombre        = Sanitizador::titulo($_POST['nombre'] ?? '');
$apellido      = Sanitizador::titulo($_POST['apellido'] ?? '');
$edad          = Sanitizador::numero($_POST['edad'] ?? '');
$sexo          = Sanitizador::texto($_POST['sexo'] ?? '');
$id_pais       = Sanitizador::numero($_POST['id_pais'] ?? '');
$nacionalidad  = Sanitizador::titulo($_POST['nacionalidad'] ?? '');
$correo        = Sanitizador::correo($_POST['correo'] ?? '');
$celular       = Sanitizador::numero($_POST['celular'] ?? '');
$observacion   = Sanitizador::texto($_POST['observacion'] ?? '');
$areas         = $_POST['areas'] ?? [];

// ---------- Validación ----------
$errores = [];

if (!Validador::requerido($identidad)) $errores[] = "Identidad requerida.";
if (!Validador::requerido($nombre))    $errores[] = "Nombre requerido.";
if (!Validador::requerido($apellido))  $errores[] = "Apellido requerido.";
if (!Validador::edad($edad))           $errores[] = "Edad inválida (debe estar entre 12 y 100).";
if (!Validador::correo($correo))       $errores[] = "Correo inválido.";
if (!Validador::celular($celular))     $errores[] = "Celular inválido (8 a 15 dígitos).";
if (empty($id_pais))                   $errores[] = "Debe seleccionar un país.";
if (empty($sexo))                      $errores[] = "Debe seleccionar un sexo.";
if (empty($areas))                     $errores[] = "Debe seleccionar al menos un tema de interés.";

/**
 * Muestra una página de error con el diseño del sitio y detiene la ejecución.
 */
function mostrarError($listaErrores)
{
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Error en el formulario - iTECH</title>
        <link rel="stylesheet" href="css/estilo.css">
    </head>
    <body>
        <div class="caja-error">
            <h2>No se pudo guardar la inscripción</h2>
            <ul>
                <?php foreach ($listaErrores as $error): ?>
                    <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
            <a href="index.php">&larr; Volver al formulario</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}

if (!empty($errores)) {
    mostrarError($errores);
}

// ---------- Firma de integridad ----------
$cadenaFirma = Firmador::construirCadena($identidad, $nombre, $apellido, $correo, $celular, $sexo);
$firma = Firmador::firmar($cadenaFirma);

// ---------- Guardar inscriptor (con manejo de duplicados de BD) ----------
try {
    $sql = "INSERT INTO inscriptores 
    (identidad, nombre, apellido, edad, sexo, id_pais, nacionalidad, correo, celular, observacion, firma, fecha_registro)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $db->ejecutar($sql, [
        $identidad,
        $nombre,
        $apellido,
        $edad,
        $sexo,
        $id_pais,
        $nacionalidad,
        $correo,
        $celular,
        $observacion,
        $firma
    ]);
} catch (PDOException $e) {
    // Código 23000 = violación de restricción de integridad (UNIQUE, FK, etc.)
    if ($e->getCode() == 23000) {
        if (strpos($e->getMessage(), 'identidad') !== false) {
            mostrarError(["Ya existe una inscripción registrada con esa identidad."]);
        } elseif (strpos($e->getMessage(), 'correo') !== false) {
            mostrarError(["Ya existe una inscripción registrada con ese correo."]);
        } elseif (strpos($e->getMessage(), 'celular') !== false) {
            mostrarError(["Ya existe una inscripción registrada con ese celular."]);
        } else {
            mostrarError(["Ya existe un registro con datos duplicados."]);
        }
    } else {
        mostrarError(["Ocurrió un error al guardar los datos. Intente nuevamente."]);
    }
}

$id_inscriptor = $db->ultimoId();

// ---------- Guardar áreas de interés ----------
foreach ($areas as $id_area) {
    $db->ejecutar(
        "INSERT INTO inscriptor_areas (id_inscriptor, id_area) VALUES (?, ?)",
        [$id_inscriptor, $id_area]
    );
}

header("Location: reporte.php");
exit;