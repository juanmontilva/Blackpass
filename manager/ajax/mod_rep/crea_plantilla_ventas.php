<?php
/** Include PHPExcel */
	require_once '../../dist/librerias/phxexcel/PHPExcel.php';

	$objPHPExcel = new PHPExcel();

	/*Info General Excel*/
	$objPHPExcel->
    getProperties()
        ->setCreator("TEDnologia.com")
        ->setLastModifiedBy("TEDnologia.com")
        ->setTitle("Exportar Excel con PHP")
        ->setSubject("Documento de prueba")
        ->setDescription("Documento generado con PHPExcel")
        ->setKeywords("usuarios phpexcel")
        ->setCategory("reportes");
    

    /* Datos Hojas */
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Nombreqd')
            ->setCellValue('B1', 'E-mail')
            ->setCellValue('C1', 'Twitter')
            
            ->setCellValue('A2', 'David')
            ->setCellValue('B2', 'dvd@gmail.com')
            ->setCellValue('C2', '@davidvd')

            ->setCellValue('A3', 'Diego')
            ->setCellValue('B3', 'diego@gmail.com')
            ->setCellValue('C3', '@Diego');

    /*Nombre de la página*/
    $objPHPExcel->getActiveSheet()->setTitle('Usuarios');
	$objPHPExcel->setActiveSheetIndex(0);

	/*Crear Filtro Hoja*/
	$objPHPExcel->getActiveSheet()->setAutoFilter('A1:C2');

	/* Columnas AutoAjuste */
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="excel_generado.xls"'); //nombre del documento
	header('Cache-Control: max-age=0');
	
	$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
	$objWriter->save('php://output');
	exit;
?> 
