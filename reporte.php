<?php

require_once "config/Conexion.php";
require_once "clases/Firmador.php";

$db = new Conexion();

$sql = "
SELECT 
    i.id_inscriptor,
    i.identidad,
    i.nombre,
    i.apellido,
    i.edad,
    i.sexo,
    p.nombre_pais,
    i.nacionalidad,
    i.correo,
    i.celular,
    i.observacion,
    i.firma,
    i.fecha_registro,
    GROUP_CONCAT(a.nombre_area SEPARATOR ', ') AS temas
FROM inscriptores i
INNER JOIN paises p ON i.id_pais = p.id_pais
LEFT JOIN inscriptor_areas ia ON i.id_inscriptor = ia.id_inscriptor
LEFT JOIN areas_interes a ON ia.id_area = a.id_area
GROUP BY i.id_inscriptor
ORDER BY i.id_inscriptor DESC
";

$datos = $db->obtenerTodos($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte - iTECH</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

<div class="contenedor">

    <h1>Reporte de Inscripciones</h1>

    <div class="acciones">
        <a href="index.php">Nuevo registro</a>
        <a href="exportar_excel.php">Exportar a Excel</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Estado</th>
                <th>Identidad</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Correo</th>
                <th>Celular</th>
                <th>Sexo</th>
                <th>País</th>
                <th>Nacionalidad</th>
                <th>Temas</th>
                <th>Observación</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($datos as $fila): ?>
                <?php
                    $cadenaFirma = Firmador::construirCadena(
                        $fila['identidad'],
                        $fila['nombre'],
                        $fila['apellido'],
                        $fila['correo'],
                        $fila['celular'],
                        $fila['sexo']
                    );
                    $valido = Firmador::verificar($cadenaFirma, $fila['firma']);
                ?>
                <tr>
                    <td>
                        <?php if ($valido): ?>
                            <span class="badge verde">✔ Validado</span>
                        <?php else: ?>
                            <span class="badge rojo">✘ Corrupto</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($fila['identidad'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($fila['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($fila['apellido'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($fila['correo'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($fila['celular'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($fila['sexo'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($fila['nombre_pais'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($fila['nacionalidad'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($fila['temas'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($fila['observacion'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= $fila['fecha_registro'] ? date("d/m/Y H:i", strtotime($fila['fecha_registro'])) : '-' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>

<footer>
    &copy; <?= date("Y") ?> iTECH. All rights reserved.
</footer>

</body>
</html>