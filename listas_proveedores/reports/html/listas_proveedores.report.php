<?php
/*
 *Dev.: Steven Santacruz Garcia
 *Date.: 12/04/18
 */

class ListadoPacientes_report
{
    /**
     * @var $datos
     * Vector de datos o parametros para generar el reporte
     */
    public $datos;
    /**
     * @var string $title
     */
    public $title = '';
    /**
     * @var string $author
     */
    public $author = '';
    /**
     * @var string $sizepage
     */
    public $sizepage = 'leter';
    /**
     * @var string $Orientation
     */
    public $Orientation = '';
    /**
     * @var boolean $grayScale
     */
    public $grayScale = false;
    /**
     * @var array $headers
     */
    public $headers = array();
    /**
     * @var array $footers
     */
    public $footers = array();
    /**
     * Constuctor de la clase - recibe el vector de datos
     *
     * @param array $datos Arreglo de datos pasados por referencia
     *
     * @return boolean
     */
    public function ListadoPacientes_report($datos = array())
    {
        $this->datos = $datos;
        return true;
    }
    /**
     * Metodo donde se obtiene el membrete que se le dara al reporte
     *
     * @return array
     */
    public function GetMembrete()
    {
        $mmb = array(
            'file'           => false,
            'datos_membrete' => array(
                'titulo'    => "LISTADO PROVEEDOR",
                'subtitulo' => 'FECHA 1 ',
                'logo'      => 'logocliente.png',
                'align'     => 'left',
            ),
        );
        return $mmb;
    }
    /**
     * Metodo donde se crea el cuerpo del reporte
     *
     * @return string
     */
    public function CrearReporte()
    {
        IncludeClass("ConexionBD");
        IncludeClass('CapacitacionSQL', 'classes', 'app', 'Capacitacion');

        $sql = new CapacitacionSQL();

        $pacientes = $sql->ObtenerPacientesII($this->datos);

        $html .= "<table class=\"normal_10\" width=\"100%\" align=\"center\" border=\"1\" rules=\"all\">\n";
        $html .= "  <tr class=\"label\" align=\"center\">";
        $html .= "    <td>PACIENTE</td>";
        $html .= "    <td>IDENTIFICACION</td>";
        $html .= "    <td>FECHA</td>";

        $html .= "  </tr>";

        foreach ($pacientes as $k => $d) {

            $html .= "  <tr >";
            $html .= "    <td>" . $d['primer_nombre'] . " " . $d['segundo_nombre'] . " " . $d['primer_apellido'] . " " . $d['segundo_apellido'] . "</td>";
            $html .= "    <td>" . $d['tipo_id_paciente'] . " " . $d['paciente_id'] . "</td>";
            $html .= "    <td>" . $d['fecha_ingreso'] . "</td>";

            $html .= "  </tr>";
        }

        $html .= "</table>";

        return $html;
    }
}
