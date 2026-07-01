<?php

require_once "vendor/autoload.php";
require_once "config/Conexion.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$db = new Conexion();

$sql = "
SELECT 
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

$documento = new Spreadsheet();
$hoja = $documento->getActiveSheet();
$hoja->setTitle("Reporte iTECH");

$encabezado = [
    "Identidad",
    "Nombre",
    "Apellido",
    "Edad",
    "Sexo",
    "País",
    "Nacionalidad",
    "Correo",
    "Celular",
    "Temas",
    "Observación",
    "Fecha de Registro"
];

$hoja->fromArray($encabezado, null, "A1");

$fila = 2;

foreach ($datos as $dato) {
    $hoja->setCellValue("A" . $fila, $dato['identidad']);
    $hoja->setCellValue("B" . $fila, $dato['nombre']);
    $hoja->setCellValue("C" . $fila, $dato['apellido']);
    $hoja->setCellValue("D" . $fila, $dato['edad']);
    $hoja->setCellValue("E" . $fila, $dato['sexo']);
    $hoja->setCellValue("F" . $fila, $dato['nombre_pais']);
    $hoja->setCellValue("G" . $fila, $dato['nacionalidad']);
    $hoja->setCellValue("H" . $fila, $dato['correo']);
    $hoja->setCellValue("I" . $fila, $dato['celular']);
    $hoja->setCellValue("J" . $fila, $dato['temas']);
    $hoja->setCellValue("K" . $fila, $dato['observacion']);
    $hoja->setCellValue("L" . $fila, $dato['fecha_registro'] ? date("d/m/Y H:i", strtotime($dato['fecha_registro'])) : '-');
    $fila++;
}

// Autoajustar ancho de columnas
foreach (range('A', 'L') as $columna) {
    $hoja->getColumnDimension($columna)->setAutoSize(true);
}

$writer = new Xlsx($documento);

if (!is_dir("doc_exportados")) {
    mkdir("doc_exportados");
}

$archivo = "doc_exportados/reporte_itech.xlsx";
$writer->save($archivo);

header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=reporte_itech.xlsx");
header("Cache-Control: max-age=0");
readfile($archivo);
exit;