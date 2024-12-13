<?php 
require_once '../../dist/librerias/phxexcel/PHPExcel/IOFactory.php';
$file = $_GET['file'];
$objPHPExcel = PHPExcel_IOFactory::load("../mod_clientes/files/".$file);
foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
$worksheetTitle     = $worksheet->getTitle();
$highestRow         = $worksheet->getHighestRow(); // e.g. 10
$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
$nrColumns = ord($highestColumn) - 24;

echo '<table border="1" id="tabla_ventas" class="table table-bordered table-striped table-hover table-heading table-datatable" ><tr>';
for ($row = 2; $row <= $highestRow; ++ $row) {
echo '<tr>';
for ($col = 0; $col < $highestColumnIndex; ++ $col) {
$cell = $worksheet->getCellByColumnAndRow($col, $row);
$val = $cell->getValue();
$dataType = PHPExcel_Cell_DataType::dataTypeForValue($val);
echo '<td>' . $val . '<br></td>';
}
echo '</tr>';
}
echo '</table>';
}
echo "<br>Carga de Clientes_ ".$worksheetTitle." de ";
echo $nrColumns-1 . ' columnas (B-' . $highestColumn . ') ';
echo ' y ' . $highestRow . ' filas.';
//unlink($src);  