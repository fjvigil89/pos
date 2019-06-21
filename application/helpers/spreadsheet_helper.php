<?php
require_once(APPPATH.'libraries/PHPExcel/PHPExcel.php');

function array_to_spreadsheet($arr)
{	
	$CI =& get_instance();
	PHPExcel_Shared_File::setUseUploadTempDirectory(true);
	$objPHPExcel = new PHPExcel();
	
	//Default all cells to text
	$objPHPExcel->getDefaultStyle()->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	
	for($k = 0;$k < count($arr);$k++)
	{
		for($j = 0;$j < count($arr[$k]); $j++)
		{
			if($arr[$k][$j]==lang('items_expiration_date')){
				$objPHPExcel->getActiveSheet()->getStyle('H')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
			}
			$objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow($j, $k+1, $arr[$k][$j]);
			if($arr[$k][$j]==lang('items_name') || $arr[$k][$j]==lang('items_category') || $arr[$k][$j]==lang('items_unit_price') || $arr[$k][$j]==lang('items_cost_price')){
				//apply the style 
				$style = array('font' => array('color' => array('rgb' => 'E60026')));
				$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($j,$k+1)->applyFromArray($style);
				
			}else{
				$style = array('font' => array('color' => array('rgb' => '000000')));
				$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($j,$k+1)->applyFromArray($style);
			}
			
		}
	}
	
	if ($CI->config->item('spreadsheet_format') == 'XLSX')
	{
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);		
	}
	else
	{
		$objWriter = new PHPExcel_Writer_CSV($objPHPExcel);
	}
	
	ob_start();
	$objWriter->save('php://output');
	$excelOutput = ob_get_clean();
	
	return $excelOutput;
}

function file_to_obj_php_excel($inputFileName)
{
	$CI =& get_instance();
	PHPExcel_Shared_File::setUseUploadTempDirectory(true);
	if ($CI->config->item('spreadsheet_format') == 'XLSX')
	{
		$objReader = new PHPExcel_Reader_Excel2007();
	}
	else
	{
		$objReader = new PHPExcel_Reader_CSV();
		PHPExcel_Cell::setValueBinder(new TextValueBinder());
	}
	
	$objReader->setReadDataOnly(true);
	$objPHPExcel = $objReader->load($inputFileName);
	
	return $objPHPExcel;
}

class TextValueBinder implements PHPExcel_Cell_IValueBinder
{
	public function bindValue(PHPExcel_Cell $cell, $value = null) 
	{
	    $cell->setValueExplicit($value, PHPExcel_Cell_DataType::TYPE_STRING);
	    return true;
	}
}