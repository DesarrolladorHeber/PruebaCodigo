<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: FormatoValoracionDiagrama.report.php,v 1.1.1.1 2012/05/08 16:36:54 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase reporte: FormatoValoracionDiagrama_report
  * Clase encargada de la creacion de un reporte en html
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1.1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
	class ListadoPacientes_report 
	{ 
		/**
    * @var $datos
    * Vector de datos o parametros para generar el reporte
		*/
    var $datos;		
 		/**
    * @var string $title
		*/
		var $title       = '';
 		/**
    * @var string $author
		*/
		var $author      = '';
 		/**
    * @var string $sizepage
		*/
		var $sizepage    = 'leter';
 		/**
    * @var string $Orientation
		*/
		var $Orientation = '';
 		/**
    * @var boolean $grayScale
		*/
    var $grayScale = false;
 		/**
    * @var array $headers
		*/
		var $headers = array();
 		/**
    * @var array $footers
		*/
		var $footers = array();
		/**
    * Constuctor de la clase - recibe el vector de datos
    *
    * @param array $datos Arreglo de datos pasados por referencia
    *
    * @return boolean
    */
		function ListadoPacientes_report($datos=array())
		{
			$this->datos=$datos;
			return true;
		}
		/**
    * Metodo donde se obtiene el membrete que se le dara al reporte
    *
    * @return array
    */
		function GetMembrete()
		{			
			$mmb = array(
              'file'=>false,
              'datos_membrete' => 
                array (
                  'titulo'=>"LISTADO PACIENTES",
                  'subtitulo'=>'FECHA 1 ',
                  'logo'=>'logocliente.png',
                  'align'=>'left'
                      )
                  );
			return $mmb;
		}
		/**
    * Metodo donde se crea el cuerpo del reporte
    *
    * @return string
    */
    function CrearReporte()
		{
      IncludeClass("ConexionBD");
      IncludeClass('CapacitacionSQL','classes','app','Capacitacion');

      $sql = new CapacitacionSQL();
      
      $pacientes = $sql->ObtenerPacientesII($this->datos);
      
      $html .= "<table class=\"normal_10\" width=\"100%\" align=\"center\" border=\"1\" rules=\"all\">\n";
      $html .= "  <tr class=\"label\" align=\"center\">";
      $html .= "    <td>PACIENTE</td>";
      $html .= "    <td>IDENTIFICACION</td>";
      $html .= "    <td>FECHA</td>";
  
      $html .= "  </tr>";
      
      
      foreach($pacientes as $k => $d)
      {
        
        $html .= "  <tr >";
        $html .= "    <td>".$d['primer_nombre']." ".$d['segundo_nombre']." ".$d['primer_apellido']." ".$d['segundo_apellido']."</td>";
        $html .= "    <td>".$d['tipo_id_paciente']." ".$d['paciente_id']."</td>";
        $html .= "    <td>".$d['fecha_ingreso']."</td>";

        $html .= "  </tr>";
      }
      
      $html .= "</table>";
      
      return $html;
    }
  }
?>