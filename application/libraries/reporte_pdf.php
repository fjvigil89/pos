
<?php defined('BASEPATH') or exit('No direct script access allowed');
Class reporte_pdf{

 public function __construct()
    {
    	$this->load->library('Pdf');
    }
     /*public function generar_pdf_reporte($data=array(), $headers=array(), $rango_fecha="",$titulo_reporte="") {
       
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
    	$PDF_HEADER_STRING = "Fecha actual: ".date('m/d/y')."\n".$rango_fecha;
    	$PDF_HEADER_TITLE=$titulo_reporte;
        $pdf->SetCreator(PDF_CREATOR);
        //$pdf->SetAuthor('Israel Parra');
        $pdf->SetTitle('Reporte');
        $pdf->SetSubject('Tutorial TCPDF');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
        $pdf->SetTextColor(0,0,5);
        
 
// datos por defecto de cabecera, se pueden modificar en el archivo tcpdf_config_alt.php de libraries/config
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $PDF_HEADER_TITLE , $PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
        $pdf->setFooterData($tc = array(0, 64, 0), $lc = array(0, 64, 128));
 
// datos por defecto de cabecera, se pueden modificar en el archivo tcpdf_config.php de libraries/config
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
 
// se pueden modificar en el archivo tcpdf_config.php de libraries/config
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
 
// se pueden modificar en el archivo tcpdf_config.php de libraries/config
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
 
// se pueden modificar en el archivo tcpdf_config.php de libraries/config
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
 
//relación utilizada para ajustar la conversión de los píxeles
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
 
 
// ---------------------------------------------------------
// establecer el modo de fuente por defecto
        $pdf->setFontSubsetting(true);
 
// Establecer el tipo de letra
 
//Si tienes que imprimir carácteres ASCII estándar, puede utilizar las fuentes básicas como
// Helvetica para reducir el tamaño del archivo.
        $pdf->SetFont("Helvetica", '', 8, '', true);
 
// Añadir una página
// Este método tiene varias opciones, consulta la documentación para más información.
        $pdf->AddPage("P","A4");
        $pdf->cell(0,0,"A4 7676",1,1,"C");
//fijar efecto de sombra en el texto
        $pdf->setTextShadow(array('enabled' => false, 'depth_w' => 0, 'depth_h' => 0, 'color' => array(196, 196, 196), 'opacity' => 0, 'blend_mode' => 'Normal'));
 
// Establecemos el contenido para imprimir
       
        //preparamos y maquetamos el contenido a crear
        $html = '';
        $html .= "<style type=text/css>";
       // $html .= "th{color: #fff; font-weight: bold; background-color: #222}";
        $html.="table, td ,th{ border: 0.1px solid black; padding:5;}";
        $html.="th{ text-aling: center;}";
        $html.="table tr{ text-aling:right;}";
       // $html .= "td{background-color: #AAC7E3; color: #fff}";
        $html .= "</style>";
       // $html .= "<h2>Localidades de "."-----"."</h2><h4>Actualmente: "."200"." localidades</h4>";
        $html .= "<table width='100%'  border='1' cellpadding='4' >";
        $html.="<tr>";
        foreach ($headers as $header) 
		{
			//$row[] = strip_tags($header['data']);
			$html .= "<th >".strip_tags($header['data'])."</th>";
		}
        $html .= "</tr>";
        
        //provincias es la respuesta de la función getProvinciasSeleccionadas($provincia) del modelo

        foreach($data as $datarow)
		{
			 $html .= "<tr>";
			$row = array();
			foreach($datarow as $cell)
			{
				
				$html .="<td>";
				$html .=strip_tags($cell['data']);
				$html .="</td>";
			}
			
			 $html .="</tr>";
		}

       
        $html .= "</table>";
 	//$pdf->writeHTML($html,true,false,false,false,"");
// Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
 
// ---------------------------------------------------------
// Cerrar el documento PDF y preparamos la salida
// Este método tiene varias opciones, consulte la documentación para más información.
        $nombre_archivo = utf8_decode($title.".pdf");
        $pdf->Output($nombre_archivo, 'I');
    
	}*/


}

?>