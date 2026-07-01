<?php
require_once "config/Conexion.php";

$db = (new Conexion())->conectar();

$paises = $db->query("SELECT * FROM paises ORDER BY nombre_pais ASC")->fetchAll(PDO::FETCH_ASSOC);
$areas  = $db->query("SELECT * FROM areas_interes ORDER BY id_area ASC")->fetchAll(PDO::FETCH_ASSOC);


$fechaFormulario = date("d/m/Y");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario iTECH</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

<div class="contenedor">
    <h1>Formulario de Inscripción</h1>
    <p class="fecha-formulario">Fecha: <?= $fechaFormulario ?></p>

    <form action="guardar.php" method="POST">

        <label for="identidad">Documento de Identificación</label>
        <input type="text" id="identidad" name="identidad" placeholder="Documento de identificación" required>

        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" placeholder="Nombre" required>

        <label for="apellido">Apellido</label>
        <input type="text" id="apellido" name="apellido" placeholder="Apellido" required>

        <label for="edad">Edad</label>
        <input type="number" id="edad" name="edad" placeholder="Edad" min="12" max="100" required>

        <label for="sexo">Sexo</label>
        <select id="sexo" name="sexo" required>
            <option value="">Seleccione sexo</option>
            <option value="Masculino">Masculino</option>
            <option value="Femenino">Femenino</option>
        </select>

        <label for="id_pais">País de Residencia</label>
        <select id="id_pais" name="id_pais" required>
            <option value="">Seleccione país</option>
            <?php foreach ($paises as $pais): ?>
                <option value="<?= htmlspecialchars($pais['id_pais'], ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars($pais['nombre_pais'], ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="nacionalidad">Nacionalidad</label>
        <input type="text" id="nacionalidad" name="nacionalidad" placeholder="Nacionalidad" required>

        <fieldset class="contacto">
            <legend>Información de Contacto</legend>

            <label for="correo">Correo</label>
            <input type="email" id="correo" name="correo" placeholder="ejemplo@correo.com" required>

            <label for="celular">Celular</label>
            <input type="text" id="celular" name="celular" placeholder="Celular (8 a 15 dígitos)" required>
        </fieldset>

        <label>Tema Tecnológico que le gustaría aprender</label>
        <div class="checkbox-grupo">
            <?php foreach ($areas as $area): ?>
                <label class="checkbox-item">
                    <input type="checkbox" name="areas[]" value="<?= htmlspecialchars($area['id_area'], ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars($area['nombre_area'], ENT_QUOTES, 'UTF-8') ?>
                </label>
            <?php endforeach; ?>
        </div>

        <label for="observacion">Observaciones o Consulta sobre el evento</label>
        <textarea id="observacion" name="observacion" placeholder="Escriba aquí sus observaciones o consulta"></textarea>

        <input type="hidden" name="fecha_formulario" value="<?= $fechaFormulario ?>">

        <button type="submit">Guardar inscripción</button>
    </form>

    <a href="reporte.php" class="enlace-reporte">Ver reporte</a>
</div>

<footer class="footer-itech">
    <p>&copy; <?= date("Y") ?> iTECH. All rights reserved.</p>

</footer>

</body>
</html>