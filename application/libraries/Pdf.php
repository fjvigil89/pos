<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';
 
class Pdf extends TCPDF
{
    function __construct()
    {
        parent::__construct();
    }
    /**
    *se configuran los valores globales del pdf
    */
    private function confifuarar($pdf){      
 
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM); 
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);  
        // ---------------------------------------------------------
        // establecer el modo de fuente por defecto
        $pdf->setFontSubsetting(true);
        //Si tienes que imprimir carácteres ASCII estándar, puede utilizar las fuentes básicas como
        // Helvetica para reducir el tamaño del archivo.
        $pdf->SetFont("Helvetica", '', 7, '', true);
    }
    /**
    * se configura la cabecera del pdf
    */
    private function configuar_header($pdf,$rango_fecha,$titulo_reporte){

        $PDF_HEADER_STRING = lang("reports_date").": ".date('m/d/Y')."\n".$rango_fecha;
        $PDF_HEADER_TITLE=$titulo_reporte;
        $pdf->SetCreator(PDF_CREATOR);
        //$pdf->SetAuthor('Israel Parra');
        $pdf->SetTitle($titulo_reporte);
        $pdf->SetSubject('');
        //$pdf->SetKeywords('TCPDF, PDF, example, test, guide'); 

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $PDF_HEADER_TITLE , $PDF_HEADER_STRING, array(0, 0, 0), array(0, 0, 0));
        //$pdf->setFooterData($tc = array(0, 64, 0), $lc = array(0, 64, 128));   
    }


    public function generar_pdf_reporte_table($data=array(), $rango_fecha="",$titulo_reporte="",$ntable=0) {
        $pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
         $this->configuar_header($pdf,$rango_fecha,$titulo_reporte);
         $this->confifuarar($pdf);
        // Añadir una página
        $pdf->AddPage();
        $html = '';
    // se muestran los datos
        $html = '';
        $html .= "<style type=text/css>";
        $html.="table, td ,th{ border: 0.1px solid black; padding:5;}";      
        $html .= "</style>";
        $html .= '<table border="0" cellpadding="5" cellspacing="0">';
        $html.='<thead> <tr  align="center">';
        for ($i = 0; $i < $ntable; $i++) 
        {           
            $html .= "<th > <strong>".lang("table")." ".($i+1)."</strong></th>";
        }

        $html .= "</tr></thead>";        
       // se crear el cuerpo de la tabla        
            $html .= '<tbody><tr  align="right">';
            
            for ($i = 0; $i < $ntable; $i++) 
            {               
                $html .="<td>";
                if (isset($data[($i + 1)]))
                     $html.= $data[($i + 1)];
                else
                     $html.="0";                               
                
                $html .="</td>";
            }         
             $html .="</tr></tbody>";
        
        $html .= "</table>";      
       
        $pdf->writeHTML($html,true,false,false,false,'');
        ob_end_clean();
        $nombre_archivo = utf8_decode($titulo_reporte.".pdf");
        
        $pdf->Output($nombre_archivo, 'I');

    }
    public function generar_pdf_reporte($data=array(), $headers=array(), $rango_fecha="",$titulo_reporte="", $summary_data=array()) {
        $pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
           
        $this->configuar_header($pdf,$rango_fecha,$titulo_reporte);
        $this->confifuarar($pdf); 
        // Añadir una página
        $pdf->AddPage();        
       
        //preparamos y maquetamos el contenido a crear
        if(!empty($summary_data) ){
                $html = '';
                $html .= "<style>";
            
                 $html .= "div.test {
                color: #00000;       
                font-family: helvetica;
                font-size: 11pt;      
               
                text-align: right;       
                
            }";
            // se muestra el summary_data
            $html.='th{
                 background-color: #F8F9F9;
            }';
               
                $html .= "</style>";
               
                $html .= '<table cellspacing="3" cellpadding="1" ><tr>';
                    foreach($summary_data as $name=>$value)
                    {   
                    $html .= "<th > ";        
                    $html .= '<div class="test">';
                          $html .=" <div>
                                <strong>".str_replace(' ','&nbsp;', to_currency((int)$value))."
                                </strong>
                            </div> ";

                            $html .=" <div> ".lang('reports_'.$name)." </div>";
                        $html .="</div>";
                        $html .="</th>";
                    }
                $html.="</tr></table><br>";

            $pdf->writeHTML($html, true, false, true, false, '');
        }
        // se muestran los datos
        $html = '';
        $html .= "<style type=text/css>";
        $html.="table, td ,th{ border: 0.1px solid black; padding:5;}";      
        $html .= "</style>";
        $html .= '<table border="0" cellpadding="5" cellspacing="0">';
        $html.='<thead> <tr  align="center">';
        foreach ($headers as $header) 
		{			
			$html .= "<th > <strong>".strip_tags($header['data'])."</strong></th>";
		}

        $html .= "</tr></thead>";

        
       // se crear el cuerpo de la tabla
        foreach($data as $datarow)
		{
			 $html .= '<tbody><tr  align="right">';
			
			foreach($datarow as $cell)
			{				
				$html .="<td>";
				$html .=strip_tags($cell['data']);
				$html .="</td>";
			}			
			 $html .="</tr></tbody>";
             //$html.="<tfoot> </tfoot>";


		} 
        // para mostar los totales en el tfooter

       /* $html.='<tfoot> <tr  align="right">';
        $html .= "<td > <strong>".lang('total')."</strong></td>";
        foreach($summary_data as $name=>$value)
        {           
            $html .= "<td > <strong>".str_replace(' ','&nbsp;', to_currency((int)$value))."</strong></td>";
        }        
        $html .= "</tr></tfoot>"; */     
        $html .= "</table>";      

       
 	    $pdf->writeHTML($html,true,false,false,false,'');

        $nombre_archivo = utf8_decode($titulo_reporte.".pdf");
        $pdf->Output($nombre_archivo, 'I');
    
	}
        /**
        *Genera reporte detallado
        */
    public function generar_pdf_reporte_detallado($summary_data=array(), $headers=array(), $rango_fecha="",$titulo_reporte="", $overall_summary_data=array(),$details_data=array()) {
        $pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
           
        $this->configuar_header($pdf,$rango_fecha,$titulo_reporte);
        $this->confifuarar($pdf); 
        // Añadir una página
        $pdf->AddPage('L');
       // se muestra el summary_data
       $html = '';
		$html .= "<style>";

		$html .= "div.test {
		color: #00000;       
		font-family: helvetica;
		font-size: 11pt;      
	
		text-align: right;       
		
        }";
    
       $html.='th{
		background-color: #F8F9F9;
	    }';
	
		$html .= "</style>";
	
		$html .= '<table cellspacing="3" cellpadding="1" ><tr>';
			foreach($overall_summary_data as $name=>$value) 
			{   
			$html .= "<th > ";        
			$html .= '<div class="test">';
				$html .=" <div>
						<strong>".str_replace(' ','&nbsp;', to_currency($value))."
						</strong>
					</div> ";

					$html .=" <div> ".lang('reports_'.$name)." </div>";
				$html .="</div>";
				$html .="</th>";
			}
		$html.="</tr></table><br>";
       // $pdf->AddPage('L');// L horizontal and p vertical
		$pdf->writeHTML($html, true, false, true, false, '');
        //preparamos y maquetamos el contenido a crear
             

        
        $pdf->writeHTML(get_model_tabla_detailed_sales($summary_data,$headers,$details_data),true,false,false,false,'');
        
       

       
        $nombre_archivo = utf8_decode($titulo_reporte.".pdf");
        $pdf->Output($nombre_archivo, 'I');
    
    }

    

}