<?php

class FiledownloadController extends BaseUser{

    protected $phpExel;
    protected $catalog;

    protected function inputData(){

        parent::inputData();

        include_once (LIB.'/PHPExcel.php');
        $this->phpExel = new PHPExcel();

        /*Установка активного листа*/
        $this->phpExel->setActiveSheetIndex(0);
        $activeSheet = $this->phpExel->getActiveSheet();
        /*Установка активного листа*/

        /*Создание дополнитьельного листа*/

        //$this->phpExel->createSheet(1);

        /*Создание дополнитьельного листа*/

        /*Ориентация листа*/
        $activeSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
        /*Ориентация листа*/

        /*Размер листа для печати*/
        $activeSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        /*Размер листа для печати*/

        /*Поля документа*/
        $activeSheet->getPageMargins()->setTop(0.5);
        $activeSheet->getPageMargins()->setRight(0.75);
        $activeSheet->getPageMargins()->setBottom(0.5);
        $activeSheet->getPageMargins()->setLeft(0.75);
        /*Поля документа*/

        /*Название листа*/
        $activeSheet->setTitle('Тестовый лист');
        /*Название листа*/

        /*Фиксированная строка внизу документа*/
        $activeSheet->getHeaderFooter()->setOddFooter('&L&B'.$activeSheet->getTitle().'&RСтраница &P из &N');
        /*Фиксированная строка внизу документа*/

        /*Установка шрифта по умолчанию*/
        $this->phpExel->getDefaultStyle()->getFont()->setName('Arial');
        /*Установка шрифта по умолчанию*/

        /*Размер шрифта*/
        $this->phpExel->getDefaultStyle()->getFont()->setSize(10);
        /*Размер шрифта*/

        /*Ширина столбцов*/
        $activeSheet->getColumnDimension('A')->setWidth(30);
        $activeSheet->getColumnDimension('B')->setWidth(70);
        $activeSheet->getColumnDimension('C')->setWidth(20);
        /*Ширина столбцов*/

        /*Объединение яцеек*/
        $activeSheet->mergeCells('A1:C1');
        /*Объединение яцеек*/

        /*Высотя рядов*/
        $activeSheet->getRowDimension('1')->setRowHeight(60);
        /*Высотя рядов*/

        /*Добавление текстовых данных в ячейку*/
        $activeSheet->setCellValue('A1', 'Ура первый вывод в файл');
        /*Добавление текстовых данных в ячейку*/

        /*СТили для ячейки*/
        $style_header = array(
                            'font' => array(
                                            'bold' => true,
                                            'name' => 'Times new Roman',
                                            'size' => 20,
                                            'color' => array(
                                                            'rgb' => 'ffffff'
                                                            ),
                                            ),
                            'alignment' => array(
                                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                                ),
                            'fill' => array(
                                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                            'color' => array('rgb' => '2e778f')
                                            ),
                            );

        $style_slogan = array(
            'font' => array(
                'italic' => true,
                'name' => 'Times new Roman',
                'size' => 11,
                'color' => array(
                    'rgb' => 'ffffff'
                ),
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '2e778f')
            ),
            'borders' => array(
                                'bottom' => array(
                                                'style' => PHPExcel_Style_Border::BORDER_THICK
                                                )
                                )
        );

        $style_date = array(

            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'cfcfcf')
            ),
            'borders' => array(
                'bottom' => array(
                    'style' => PHPExcel_Style_Border::BORDER_NONE
                )
            )
        );

        $style_date1 = array(

            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'cfcfcf')
            ),
            'borders' => array(
                'left' => array(
                    'style' => PHPExcel_Style_Border::BORDER_NONE
                )
            )
        );

        $style_head_content = array(
            'font' => array(
                'name' => 'Times new Roman',
                'size' => 10,
                'color' => array(
                'rgb' => 'ffffff'
                ),
                'bold' => true,
                'italic' => true
             ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '2e778f')
            ),
        );

        $style_content = array(
            'font' => array(
                'name' => 'Times new Roman',
                'size' => 10,
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => true
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'cfcfcf')
            ),
        );

        $style_wrap = array(

            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('rgb' => '696969'),
                    ),
                    'outline' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THICK
                                )
            )
        );

        $activeSheet->getStyle('A1:C1')->applyFromArray($style_header);
        $activeSheet->getStyle('A2:C2')->applyFromArray($style_slogan);
        $activeSheet->getStyle('A4:B4')->applyFromArray($style_date);
        $activeSheet->getStyle('C4')->applyFromArray($style_date1);
        $activeSheet->getStyle('A6:C6')->applyFromArray($style_head_content);

        /*СТили для ячейки*/

        /*Объединение яцеек*/
        $activeSheet->mergeCells('A2:C2');
        /*Объединение яцеек*/

        /*Добавление текстовых данных в ячейку*/
        $activeSheet->setCellValue('A2', 'Mess with the best die like the rest');
        /*Добавление текстовых данных в ячейку*/

        /*Объединение яцеек*/
        $activeSheet->mergeCells('A4:B4');
        /*Объединение яцеек*/

        /*Добавление текстовых данных в ячейку*/
        $activeSheet->setCellValue('A4', 'Дата создания');
        /*Добавление текстовых данных в ячейку*/

        /*Добавление текстовых данных в ячейку*/
        $date = date("d-m-Y");
        $activeSheet->setCellValue('C4', $date);
        /*Добавление текстовых данных в ячейку*/

        /*Установка типа данных для ячейки*/
        $activeSheet->getStyle('C4')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
        /*Установка типа данных для ячейки*/

        /*Добавление текстовых данных в ячейку*/
        $activeSheet->setCellValue('A6', 'Название');
        /*Добавление текстовых данных в ячейку*/

        /*Добавление текстовых данных в ячейку*/
        $activeSheet->setCellValue('B6', 'Описание');
        /*Добавление текстовых данных в ячейку*/

        /*Добавление текстовых данных в ячейку*/
        $activeSheet->setCellValue('C6', 'Изображение');
        /*Добавление текстовых данных в ячейку*/
        
        $this->catalog = $this->object_model->getDownloadFile();

        /*Заполняем файл данными из БД*/

        $row_start = 6;
        $current_row = $row_start;

        foreach($this->catalog as $value){
            $current_row++;
            $activeSheet->setCellValue('A'.$current_row, $value['name']);
            $activeSheet->setCellValue('B'.$current_row, $value['content']);
            $activeSheet->getStyle('B'.$current_row)->getAlignment()->setWrapText(true);
            $activeSheet->getRowDimension("$current_row")->setRowHeight(60);
            $activeSheet->getStyle('A'.$current_row)->applyFromArray($style_content);

            /*Вставляем изображение в ячейку*/
            $objecImg = new PHPExcel_Worksheet_Drawing();
            $objecImg->setWorksheet($this->phpExel->getActiveSheet());
            $objecImg->setName('img'.$current_row);
            $objecImg->setPath(TEMPLATE.'img/'.$value['foto']);
            $objecImg->setCoordinates('C'.$current_row);

            /*Смещение изображения*/
            $objecImg->setOffsetX(5*$current_row);
            $objecImg->setOffsetY(2);
            /*Смещение изображения*/

            /*Вставляем изображение в ячейку*/
        }

        /*Заполняем файл данными из БД*/

        $activeSheet->getStyle('A'.$row_start.':C'.$current_row)->applyFromArray($style_wrap);
    }

    protected function outputData(){
        /*Отдача файла на скачивание*/
        header('Content-Type:application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="excel.xls"');

        $objectWriter = PHPExcel_IOFactory::createWriter($this->phpExel, 'Excel5');
        $objectWriter->save('php://output');
        /*Отдача файла на скачивание*/

        /*Сохранение документа в файл*/
        /*  $objectWriter = PHPExcel_IOFactory::createWriter($this->phpExel, 'Excel5');
            $objectWriter->save('file.xls');

            $this->redirect(PATH);*/
        /*Сохранение документа в файл*/
        exit();
    }
}
