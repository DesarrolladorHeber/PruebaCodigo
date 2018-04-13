<?php
/*
 *Dev.: Steven Santacruz Garcia
 *Date.: 12/04/18
 */

class listas_proveedoresHTML
{

    public function ListasPreciosProveedores($action, $proveedores, $request, $conteo, $pagina, $permisos)
    {

        $html = OpenTable("LISTAS DE PRECIOS PROVEEDORES");

        $html .= "<script>";
        $html .= "  function ConsultarProveedor(formulario){ ";
        $html .= "      formulario.action = \"" . $action['buscar'] . "\";\n";
        $html .= "      formulario.submit();\n";
        $html .= "  }";

        $html .= "  function EliminarLista(codigo_lista, pagina)\n";
        $html .= "  {\n";
        $html .= "    if(confirm('ESTA SEGURO QUE DESEA ELIMINAR ESTA LISTA DE PRECIOS?'))\n";
        $html .= "      {\n";
        $html .= "          xajax_EliminarLista(codigo_lista, pagina);\n";
        $html .= "      }\n";
        $html .= "  }\n";

        $html .= "</script>";

        $html .= "<div style=\"text-align:center\" class=\"normal_10AN\">";
        $html .= "      <form name=\"form_proveedor\" method=\"post\" action=\"javascript:ConsultarProveedor(document.form_proveedor)\" id=\"form_proveedor\">\n";
        $html .= "        <table border=\"0\" width=\"75%\" align=\"center\" class=\"modulo_table_list\">";

        $html .= "          <tr class=\"modulo_table_list_title\">";

        $html .= "            <td align=\"center\" colspan=\"4\">";
        $html .= "              LISTA DE PRECIOS";
        $html .= "            </td>\n";
        $html .= "          </tr>\n";

        $html .= "          <tr class=\"modulo_list_claro\">";
        $html .= "            <td width=\"18%\" class=\"formulacion_table_list\" align=\"center\">";
        $html .= "              TIPO DE IDENTIFICACION ";
        $html .= "            </td>";

        $html .= "            <td class=\"label\" align=\"left\">";
        $html .= "              <select name=\"tipo_id_tercero\" class=\"select\">";
        $html .= "                  <option value=\"\">---SELECCIONE---</option>";
        $html .= "                  <option value=\"CC\"> Cedula de ciudadanía </option>";
        $html .= "                  <option value=\"NIT\"> NIT </option>";
        $html .= "              </select>\n";
        $html .= "            </td>";

        $html .= "            <td width=\"18%\" class=\"formulacion_table_list\" align=\"center\">";
        $html .= "              IDENTIFICACION ";
        $html .= "            </td>";

        $html .= "            <td class=\"label\" align=\"center\">";
        $html .= "              <input size=\"30\" type=\"text\" class=\"input-text\" id=\"tercero_id\" name=\"tercero_id\">";
        $html .= "            </td>";

        $html .= "          </tr>";

        $html .= "          <tr>";

        $html .= "            <td width=\"18%\" class=\"formulacion_table_list\" align=\"center\">";
        $html .= "              NOMBRE PROVEEDOR ";
        $html .= "            </td>";

        $html .= "            <td class=\"label\"  colspan=\"3\">";
        $html .= "              <input size=\"114\" type=\"text\" class=\"input-text\" id=\"nombre_tercero\" name=\"nombre_tercero\">";
        $html .= "            </td>";

        $html .= "          </tr>";

        $html .= "          <tr class=\"modulo_table_list_title\">";
        $html .= "            <td align=\"center\" colspan=\"4\">";
        $html .= "              <input type=\"submit\" class=\"input-submit\" value=\"Buscar\">";
        $html .= "            </td>";
        $html .= "          </tr>";

        $html .= "        </table>";
        $html .= "      </form>";
        $html .= "</div>";

        $html .= "<br>";

        $html .= "<div style=\"text-align:center\">\n";
        $html .= "  <a href=\"" . $action['crearLista'] . "\" class=\"label_error\">\n";
        $html .= "    <img src=\"" . GetThemePath() . "/images/pplan.png\" border=\"0\"> CREAR LISTA DE PRECIOS\n";
        $html .= "  </a>\n";
        $html .= "</div>\n";

        $html .= "<br>\n";

        if (!empty($proveedores)) {

            $html .= "<table class=\"modulo_table_list\" width=\"100%\" align=\"center\">\n";
            $html .= "  <tr class=\"formulacion_table_list\">";
            $html .= "    <td>COD LISTA</td>";
            $html .= "    <td>IDENTIFICACION</td>";
            $html .= "    <td>NOMBRE PROVEEDOR</td>";
            $html .= "    <td>FECHA CREACION</td>";
            $html .= "    <td colspan=\"2\">OPCION</td>";
            $html .= "  </tr>";

            $est = "";

            foreach ($proveedores as $k => $d) {
                $est = ($est == "modulo_list_claro") ? "modulo_list_oscuro" : "modulo_list_claro";

                $html .= "  <tr class=\"" . $est . "\">";
                $html .= "    <td>" . $d['cod_lista'] . "</td>";
                $html .= "    <td>" . $d['tipo_id_tercero'] . " - " . $d['tercero_id'] . "</td>";
                $html .= "    <td>" . $d['nombre_tercero'] . "</td>";
                $html .= "    <td>" . $d['fecha_creacion'] . "</td>";
                $html .= "    <td align=\"center\">\n";

                $html .= "      <a class=\"label_error\"
                               href=\"" . $action['consultarLista'] . URLRequest(array("datos_proveedor" => $d, "insert" => '0')) . "\"
                               title=\"Listado de precios\">\n";
                $html .= "        <img src=\"" . GetThemePath() . "/images/pconsultar.png\" border=\"0\">VER\n";
                $html .= "      </a>\n";

                $html .= "      <td align=\"center\" width=\"8%\">\n";
                $html .= "        <a href=\"javascript:EliminarLista(" . $d['cod_lista'] . ", '" . $pagina . "')\" class=\"label_error\">\n";
                $html .= "            <img src=\"" . GetThemePath() . "/images/elimina.png\" border=\"0\"> ELIMINAR\n";
                $html .= "        </a>\n";
                $html .= "      </td>\n";

                $html .= "    </td>";
                $html .= "  </tr>";
            }

            $html .= "</table>";
            $html .= "<br>";
            $pgh = AutoCarga::factory("ClaseHTML");
            $html .= $pgh->ObtenerPaginado($conteo, $pagina, $action['paginador']);

        } else {
            $html .= "<div style=\"text-align:center\" class=\"label_error\">\n";
            $html .= "  LA BUSQUEDA NO ARROJO RESULTADOS \n";
            $html .= "</div>\n";
        }

        $html .= "<br>";
        $html .= "<div style=\"text-align:center\" class=\"label_error\">";
        $html .= "  <a href=\"" . $action['volver'] . "\">VOLVER</a>";
        $html .= "</div>";
        $html .= CloseTable();

        return $html;
    }

    public function ListaProveedores($action, $proveedores, $request, $conteo, $pagina, $permisos)
    {

        $html = OpenTable("CREAR LISTA PROVEEDORES");

        $html .= "<script>";

        $html .= "  function ConsultarProveedor(formulario){ ";
        $html .= "      formulario.action = \"" . $action['buscar'] . "\";\n";
        $html .= "      formulario.submit();\n";
        $html .= "  }";

        $html .= "  function CrearLista(tipo_id_tercero, tercero_id, nombre_tercero)\n";
        $html .= "  {\n";
        $html .= "    if(confirm('¿DESEA CREAR LA LISTA DE PRECIOS DE '+nombre_tercero+' ? '))\n";
        $html .= "      {\n";
        $html .= "          xajax_CrearLista(tipo_id_tercero, tercero_id, nombre_tercero);\n";
        $html .= "      }\n";
        $html .= "  }\n";

        $html .= "</script>";

        $html .= "<div style=\"text-align:center\" class=\"normal_10AN\">";
        $html .= "      <form name=\"form_proveedor\" method=\"post\" action=\"javascript:ConsultarProveedor(document.form_proveedor)\" id=\"form_proveedor\">\n";
        $html .= "        <table border=\"0\" width=\"75%\" align=\"center\" class=\"modulo_table_list\">";

        $html .= "          <tr class=\"modulo_table_list_title\">";

        $html .= "            <td align=\"center\" colspan=\"4\">";
        $html .= "              LISTA DE PRECIOS";
        $html .= "            </td>\n";
        $html .= "          </tr>\n";

        $html .= "          <tr class=\"modulo_list_claro\">";
        $html .= "            <td width=\"18%\" class=\"formulacion_table_list\" align=\"center\">";
        $html .= "              TIPO DE IDENTIFICACION ";
        $html .= "            </td>";

        $html .= "            <td class=\"label\" align=\"left\">";
        $html .= "              <select name=\"tipo_id_tercero\" class=\"select\">";
        $html .= "                  <option value=\"\">---SELECCIONE---</option>";
        $html .= "                  <option value=\"CC\"> Cedula de ciudadanía </option>";
        $html .= "                  <option value=\"NIT\"> NIT </option>";
        $html .= "              </select>\n";
        $html .= "            </td>";

        $html .= "            <td width=\"18%\" class=\"formulacion_table_list\" align=\"center\">";
        $html .= "              IDENTIFICACION ";
        $html .= "            </td>";

        $html .= "            <td class=\"label\" align=\"center\">";
        $html .= "              <input size=\"30\" type=\"text\" class=\"input-text\" id=\"tercero_id\" name=\"tercero_id\">";
        $html .= "            </td>";

        $html .= "          </tr>";

        $html .= "          <tr>";

        $html .= "            <td width=\"18%\" class=\"formulacion_table_list\" align=\"center\">";
        $html .= "              NOMBRE PROVEEDOR ";
        $html .= "            </td>";

        $html .= "            <td class=\"label\"  colspan=\"3\">";
        $html .= "              <input size=\"114\" type=\"text\" class=\"input-text\" id=\"nombre_tercero\" name=\"nombre_tercero\">";
        $html .= "            </td>";

        $html .= "          </tr>";

        $html .= "          <tr class=\"modulo_table_list_title\">";
        $html .= "            <td align=\"center\" colspan=\"4\">";
        $html .= "              <input type=\"submit\" class=\"input-submit\" value=\"Buscar\">";
        $html .= "            </td>";
        $html .= "          </tr>";

        $html .= "        </table>";
        $html .= "      </form>";
        $html .= "</div>";

        $html .= "<br>";

        if (!empty($proveedores)) {

            $html .= "<table class=\"modulo_table_list\" width=\"100%\" align=\"center\">\n";
            $html .= "  <tr class=\"formulacion_table_list\">";
            $html .= "    <td>IDENTIFICACION</td>";
            $html .= "    <td>NOMBRE PROVEEDOR</td>";
            $html .= "    <td>OPCIONES</td>";
            $html .= "  </tr>";

            $est = "";

            foreach ($proveedores as $k => $d) {
                $est = ($est == "modulo_list_claro") ? "modulo_list_oscuro" : "modulo_list_claro";

                $html .= "  <tr class=\"" . $est . "\">";
                $html .= "    <td>" . $d['tipo_id_tercero'] . " - " . $d['tercero_id'] . "</td>";
                $html .= "    <td>" . $d['nombre_tercero'] . "</td>";
                $html .= "    <td align=\"center\">\n";

                $html .= "        <a href=\"javascript:CrearLista('" . $d['tipo_id_tercero'] . "', '" . $d['tercero_id'] . "', '" . $d['nombre_tercero'] . "')\" class=\"label_error\">\n";
                $html .= "            <img src=\"" . GetThemePath() . "/images/pplan.png\" border=\"0\">CREAR\n";
                $html .= "        </a>\n";

                $html .= "    </td>";
                $html .= "  </tr>";
            }

            $html .= "</table>";
            $html .= "<br>";
            $pgh = AutoCarga::factory("ClaseHTML");
            $html .= $pgh->ObtenerPaginado($conteo, $pagina, $action['paginador']);

            $html .= "<br>";
            $html .= "<div style=\"text-align:center\" class=\"label_error\">";
            $html .= "  <a href=\"" . $action['volver'] . "\">VOLVER</a>";
            $html .= "</div>";
            $html .= CloseTable();

        } else {
            $html .= "<div style=\"text-align:center\" class=\"label_error\">\n";
            $html .= "  LA BUSQUEDA NO ARROJO RESULTADOS \n";
            $html .= "</div>\n";
        }
        return $html;

    }

    public function ListaPreciosProveedor($action, $listaProveedores, $request, $permisos)
    {
        $ctl = AutoCarga::factory("ClaseUtil");

        $html = OpenTable("LISTA DE PRECIOS " . $request['datos_proveedor']['nombre_tercero'] . " ");

        $html .= "<script>";

        $html .= "  var opcion = 1;\n";
        $html .= "  var buscar = false;\n";

        $html .= "  function BuscarProductoLista(offset)\n";
        $html .= "  {\n";
        $html .= "    if(opcion == 1)\n";
        $html .= "    {\n";
        $html .= "      buscar = true;\n";
        $html .= "      xajax_BuscarPreciosListaProveedores(xajax.getFormValues('form_productos'),offset," . $request['datos_proveedor']['cod_lista'] . ")\n";
        $html .= "    }";
        $html .= "    else if(opcion == 2)";
        $html .= "    {\n";
        $html .= "      buscar = true;\n";
        $html .= "      xajax_BuscarListaProveedores(xajax.getFormValues('form_productos'),offset," . $request['datos_proveedor']['cod_lista'] . ")\n";
        $html .= "    }\n";
        $html .= "  }\n";

        $html .= "  function SetOption(op)\n";
        $html .= "  {\n";
        $html .= "    opcion = op;\n";
        $html .= "    if(opcion == 2 && buscar)\n";
        $html .= "      BuscarProductoLista(1);\n";
        $html .= "  }\n";

        $html .= "  function EvaluarValor(precio,iva,total,checkbox)\n";
        $html .= "  {\n";
        $html .= "    e = document.getElementById(precio);\n";
        // $html .= "    e1 = document.getElementById(iva);\n";
        $html .= "    var valor_iva = document.getElementById(iva).value;\n";
        $html .= "    var precio_iva = (e.value * valor_iva)/100;\n";
        $html .= "    var precio_total = parseInt(e.value) + parseInt(precio_iva);\n";
        $html .= "    try\n";
        $html .= "    {\n";
        $html .= "      if(is_numeric(e.value))\n";
        $html .= "      {\n";
        // $html .= "        $(e).keyup(function (){\n";
        // $html .= "          e.value = (e.value + '').replace(/[^0-9]/g, '');\n";
        // $html .= "        });\n";
        // $html .= "        $(e1).keyup(function (){\n";
        // $html .= "          e1.value = (e1.value + '').replace(/[^0-9]/g, '');\n";
        // $html .= "        });\n";
        $html .= "        if(e.value*1 <= 0)\n";
        $html .= "        {\n";
        $html .= "          e.value = '';\n";
        $html .= "          e.style.background ='#FF0000';\n";
        $html .= "          $( \"#\"+checkbox+\"\" ).prop( \"checked\", false );\n";
        $html .= "        }\n";
        $html .= "        else\n";
        $html .= "        {\n";
        $html .= "          document.getElementById(total).value = precio_total;\n";
        $html .= "          $( \"#\"+checkbox+\"\" ).prop( \"checked\", true );\n";
        $html .= "          e.style.background ='#FFFFFF';\n";
        $html .= "        }\n";
        $html .= "      }\n";
        $html .= "      else\n";
        $html .= "      {\n";
        $html .= "        e.style.background ='#FF0000';\n";
        $html .= "        $( \"#\"+checkbox+\"\" ).prop( \"checked\", false );\n";
        $html .= "      }\n";
        $html .= "    }\n";
        $html .= "  catch(error){};\n";
        $html .= "  $(document).ready(function (){\n";
        $html .= "      $('.monto').keyup(function (){\n";
        $html .= "          this.value = (this.value + '').replace(/[^0-9]/g, '');\n";
        $html .= "      });\n";
        $html .= "  });\n";
        $html .= "  }\n";

        $html .= "  function GuardarListaProveedor()\n";
        $html .= "  {\n";
        //$html .= "alert('hola perros');";
        $html .= "      if(confirm(\"¿DESEA GUARDAR ESTOS PRODUCTOS PARA ESTA LISTA DE PRECIOS?\"))\n";
        $html .= "      {\n";
        $html .= "          xajax_GuardarListaProveedor(xajax.getFormValues('frm_lista_proveedor_precios')," . $request['datos_proveedor']['cod_lista'] . ", " . $pagina . ");\n";
        $html .= "      }\n";
        $html .= "  }\n";

        $html .= "  function EliminarProducto(codigo_producto, descripcion, pagina)\n";
        $html .= "  {\n";
        $html .= "    if(confirm('¿DESEA BORRAR EL PRODUCTO '+ descripcion +' DE LA LISTA DE PRECIOS?'))\n";
        $html .= "      {\n";
        $html .= "          xajax_EliminarProducto(" . $request['datos_proveedor']['cod_lista'] . ", codigo_producto, pagina);\n";
        $html .= "      }\n";
        $html .= "  }\n";

        $html .= "  function ModificarProducto(codigo_producto)\n";
        $html .= "  {\n";
        $html .= "    xajax_ModificarProducto(" . $request['datos_proveedor']['cod_lista'] . ", codigo_producto);\n";
        $html .= "  }\n";

        $html .= "  function GuardarInformacionProducto()\n";
        $html .= "  {\n";
        //$html .= "alert('hola perros');";
        $html .= "      if(confirm(\"¿DESEA ACTUALIZAR ESTE PRODUCTO ?\"))\n";
        $html .= "      {\n";
        $html .= "          xajax_GuardarInformacionProducto(xajax.getFormValues('frm_modificar')," . $request['datos_proveedor']['cod_lista'] . ", " . $pagina . ");\n";
        $html .= "      }\n";
        $html .= "  }\n";

        $html .= "      function CargarArchivo(forma)\n";
        $html .= "      {\n";
        $html .= "        arch = forma.archivo_productos.value;\n";
        $html .= "        if(arch == \"\")\n";
        $html .= "        {\n";
        $html .= "          alert(\"NO SE HA SLECCIONADO EL ARCHIVO A SUBIR\");\n";
        $html .= "          return;\n";
        $html .= "        }\n";
        $html .= "        aux = explode(\".\",arch);\n";
        $html .= "        ext = strtolower(aux[aux.length - 1]);\n";
        $html .= "        if(aux.length == 1 || ext != \"csv\")\n";
        $html .= "        {\n";
        $html .= "          alert(\"SOLO SE PERMITEN ARCHIVOS CON EXTENSION .CSV\");\n";
        $html .= "          return;\n";
        $html .= "        }\n";
        $html .= "        forma.action = \"" . $action['subir_archivo'] . "&codigo_lista=" . $request['datos_proveedor']['cod_lista'] . "\";\n";
        $html .= "        forma.submit();\n";
        $html .= "      }\n";

        $html .= "</script>";

        $html .= "<div style=\"text-align:center\" class=\"normal_10AN\">";
        $html .= "      <form name=\"form_productos\" method=\"post\" action=\"javascript:BuscarProductoLista(0)\" id=\"form_productos\">\n";
        $html .= "        <table border=\"0\" width=\"75%\" align=\"center\" class=\"modulo_table_list\">";

        $html .= "          <tr class=\"modulo_table_list_title\">";

        $html .= "            <td align=\"center\" colspan=\"4\">";
        $html .= "              PRODUCTOS ";
        $html .= "            </td>\n";
        $html .= "          </tr>\n";

        $html .= "          <tr class=\"modulo_list_claro\">";
        $html .= "            <td width=\"18%\" class=\"formulacion_table_list\" align=\"center\">";
        $html .= "              TIPO DE PRODUCTO ";
        $html .= "            </td>";

        $html .= "            <td class=\"label\" align=\"left\">";
        $html .= "              <select name=\"grupo_id\" class=\"select\">";
        $html .= "                  <option value=\"\">---SELECCIONE---</option>";

        for ($i = 0; $i < sizeof($listaProveedores['grupo_producto']); $i++) {
            $html .= "<option value=" . $listaProveedores['grupo_producto'][$i]['grupo_id'] . ">"
                . $listaProveedores['grupo_producto'][$i]['descripcion'] .
                "</option>";
        }
        $html .= "              </select>\n";
        $html .= "            </td>";

        $html .= "            <td width=\"18%\" class=\"formulacion_table_list\" align=\"center\">";
        $html .= "              CODIGO PRODUCTO ";
        $html .= "            </td>";

        $html .= "            <td class=\"label\" align=\"left\">";
        $html .= "              <input size=\"30\" type=\"text\" class=\"input-text\" id=\"codigo_producto\" name=\"codigo_producto\">";
        $html .= "            </td>";

        $html .= "          </tr>";

        $html .= "          <tr>";

        $html .= "            <td width=\"18%\" class=\"formulacion_table_list\" align=\"center\">";
        $html .= "              NOMBRE PRODUCTO ";
        $html .= "            </td>";

        $html .= "            <td class=\"label\" colspan=\"3\">";
        $html .= "              <input size=\"114\" type=\"text\" class=\"input-text\" id=\"descripcion\" name=\"descripcion\">";
        $html .= "            </td>";

        $html .= "          </tr>";

        $html .= "          <tr class=\"modulo_table_list_title\">";
        $html .= "            <td align=\"center\" colspan=\"4\">";
        $html .= "              <input type=\"submit\" class=\"input-submit\" value=\"Buscar\">";
        $html .= "            </td>";
        $html .= "          </tr>";

        $html .= "        </table>";
        $html .= "      </form>";
        $html .= "</div>";

        $html .= "<div class=\"tab-pane\" id=\"productos\">\n"; //INICIO DIV PESTAÑAS
        $html .= "  <script>  tabPane1 = new WebFXTabPane( document.getElementById( \"productos\" ), false); </script>\n";

        $html .= "  <div id=\"precios_proveedor\" class=\"tab-page\" >\n";
        $html .= "    <h2 class=\"tab\" id=\"tab_precios_proveedor\" >LISTA DE PRECIOS PROVEEDOR</h2>\n";
        $html .= "    <script>  tabPane1.addTabPage( document.getElementById(\"precios_proveedor\")); </script>\n";

        $htm = "<form name=\"frm_modificar\" id=\"frm_modificar\" action=\"javascript:GuardarInformacionProducto()\" method=\"post\">\n";
        $htm .= " <div id=\"producto_modificar\"></div>\n";
        $htm .= "</form>\n";

        $html .= "      <div id=\"resultado_precios_proveedor\">"; //INICIO DIV PESTAÑA LISTA DE PRECIOS PROVEEDOR

        $html .= "      </div>\n"; //FIN DIV PESTAÑA LISTA DE PRECIOS PROVEEDOR

        $html .= "  </div>\n";

        $html .= "  <div id=\"adicionar_productos\" class=\"tab-page\" >\n";
        $html .= "    <h2 class=\"tab\" id=\"tab_adicionar_productos\" >ADICIONAR PRODUCTOS</h2>\n";
        $html .= "    <script>  tabPane1.addTabPage( document.getElementById(\"adicionar_productos\")); </script>\n";

        $html .= "      <div id=\"resultado_adicionar_productos\"> "; //INICIO DIV PESTAÑA ADICIONAR PRECIOS PROVEEDOR

        $html .= "      </div>\n"; //FIN DIV PESTAÑA ADICIONAR PRECIOS PROVEEDOR

        $html .= "  </div>\n";

        $html .= "</div>\n"; //FIN DIV PESTAÑAS

        $html .= "<br>\n";

        //Desarrollador: Steven LVI.

        $html .= "<br>";
        $html .= "<div style=\"text-align:center\" class=\"label_error\">";
        $html .= "  <a href=\"" . $action['volver'] . "\">VOLVER</a>";
        $html .= "</div>";

        $html .= $this->CrearVentana(500, 300, $htm, "MODIFICAR", 700, 400, $ht2, "BUSCAR PRODUCTOS");

        $html .= "<script>\n";

        $html .= "  var html1 = document.getElementById('tab_precios_proveedor').innerHTML;\n";
        $html .= "  var html2 = document.getElementById('tab_adicionar_productos').innerHTML;\n";
        $html .= "  html1 = html1.replace(\"#\",\"javascript:SetOption(1)\");\n";
        $html .= "  html2 = html2.replace(\"#\",\"javascript:SetOption(2)\");\n";
        $html .= "  document.getElementById('tab_precios_proveedor').innerHTML = html1;\n";
        $html .= "  document.getElementById('tab_adicionar_productos').innerHTML = html2;\n";

        $html .= "</script>\n";

        $html .= CloseTable();

        return $html;

    }

    public function BuscarPreciosListaProveedoresView($action, $listaProveedores, $request, $conteo, $pagina, $permisos)
    {

        $html = "";

        if (!empty($listaProveedores['producto'])) {

            //$html .= $this->CrearVentana(500,300,$htm,"MODIFICAR",700,400,$ht2,"BUSCAR PRODUCTOS");

            $html .= "<br>";

            $html .= "<table class=\"modulo_table_list\" width=\"100%\" align=\"center\">\n";
            $html .= "  <tr class=\"formulacion_table_list\">";
            $html .= "    <td>CODIGO PRODUCTO</td>";
            $html .= "    <td>NOMBRE PRODUCTO</td>";
            $html .= "    <td>PRECIO SIN IVA</td>";
            $html .= "    <td>IVA</td>";
            $html .= "    <td>UNIDAD DE EMPAQUE</td>";
            $html .= "    <td>VALOR TOTAL</td>";
            $html .= "    <td colspan=\"2\">OPCIONES</td>";
            $html .= "  </tr>";

            $est = "";

            $html .= "<form name=\"frm_lista_proveedor\" id=\"frm_lista_proveedor\" action=\"\" method=\"post\">\n";

            foreach ($listaProveedores['producto'] as $k => $d) {

                $est = ($est == "modulo_list_claro") ? "modulo_list_oscuro" : "modulo_list_claro";

                $fncjs = "EvaluarValor('inp_precio_" . $d['codigo_producto'] . "',
                                   'inp_iva_" . $d['codigo_producto'] . "',
                                   'inp_precio_total_" . $d['codigo_producto'] . "',
                                   'chk_cargo_" . $d['codigo_producto'] . "')";

                $html .= "  <tr class=\"" . $est . "\">";
                $html .= "    <td>" . $d['codigo_producto'] . "</td>";
                $html .= "    <td>" . $d['descripcion'] . "</td>";
                $html .= "    <td>$ " . $d['precio_bruto'] . "</td>";
                $html .= "    <td>" . $d['iva'] . " %</td>";
                $html .= "    <td>" . $d['unidad_empaque'] . "</td>";
                $html .= "    <td>$ " . $d['precio_neto'] . "</td>";

                $html .= "      <td align=\"center\" width=\"8%\">\n";
                $html .= "        <a href=\"javascript:ModificarProducto('" . $d['codigo_producto'] . "')\" class=\"label_error\">\n";
                $html .= "            <img src=\"" . GetThemePath() . "/images/modificar.png\" border=\"0\"> EDITAR\n";
                $html .= "        </a>\n";
                $html .= "      </td>\n";

                $html .= "      <td align=\"center\" width=\"8%\">\n";
                $html .= "        <a href=\"javascript:EliminarProducto('" . $d['codigo_producto'] . "','" . $d['descripcion'] . "','" . $pagina . "')\" class=\"label_error\">\n";
                $html .= "            <img src=\"" . GetThemePath() . "/images/elimina.png\" border=\"0\"> ELIMINAR\n";
                $html .= "        </a>\n";
                $html .= "      </td>\n";

                $html .= "  </tr>";
            }

            $html .= "</from>";

            $html .= "</table>";
            $html .= "<br>";
            $pgh     = AutoCarga::factory("ClaseHTML");
            $action1 = "BuscarProductoLista(";
            $html .= $pgh->ObtenerPaginadoXajax($conteo, $pagina, $action1);
        } else {

            $html .= "<div style=\"text-align:center\" class=\"label_error\">\n";
            $html .= "  LA BUSQUEDA NO ARROJO RESULTADOS \n";
            $html .= "</div>\n";

        }
        return $html;
    }

    public function BuscarListaProveedoresView($action, $listaProveedores, $request, $conteo, $pagina, $permisos)
    {
        $html = "";
        if (!empty($listaProveedores['producto'])) {

            //$html .= "<div style=\"text-align:center\">\n";
            $html .= "    <form name=\"subir\" enctype=\"multipart/form-data\" action=\"javascript:CargarArchivo(document.subir)\" method = \"post\">\n";
            $html .= "      <table width=\"70%\" align=\"right\">\n";
            $html .= "        <tr>\n";
            $html .= "          <td width=\"50%\">\n";
            $html .= "            <table width=\"100%\" class=\"modulo_table_list\" align=\"right\">\n";
            $html .= "              <tr class=\"modulo_list_claro\">\n";
            $html .= "                  <td width=\"30%\" class=\"formulacion_table_list\" >SUBIR ARCHIVO CSV</td>\n";
            $html .= "                  <td >\n";
            $html .= "                  <input type=\"file\" class=\"input-submit\" name=\"archivo_productos\" id=\"archivo_productos\">\n";
            $html .= "                </td>\n";
            $html .= "                  <td >\n";
            $html .= "                  <input type=\"submit\" class=\"input-submit\" name=\"subir\" value=\"Aceptar\">\n";
            $html .= "                </td>\n";
            $html .= "              </tr>\n";
            $html .= "              <tr class=\"modulo_list_claro\">\n";
            $html .= "                  <td colspan=\"3\">\n";
            $html .= "                  NOTA: EL FORMATO DEBE ESTAR EN CSV Y DEBE TENER LA SIGUIENTE
                                        ESTRUCTURA(SEPARADO POR PUNTO Y COMA): CÓDIGO PRODUCTO, PRECIO SIN IVA,
                                        IVA Y UNIDAD DE EMPAQUE, EL ARCHIVO NO DEBE TENER CABECERA.\n";
            $html .= "                  </td>\n";
            $html .= "              </tr>\n";
            $html .= "            </table>\n";
            $html .= "          </td>\n";
            $html .= "        </tr>\n";
            $html .= "      </table>\n";
            $html .= "    </form>\n";
            $html .= "</br>";

            $html .= "<form name=\"frm_lista_proveedor_precios\" id=\"frm_lista_proveedor_precios\" action=\"javascript:GuardarListaProveedor();\" method=\"post\">\n";

            $html .= "<table class=\"modulo_table_list\" width=\"100%\" align=\"center\">\n";

            $html .= "    <td align=\"right\" width=\"80%\" colspan=\"7\">\n";
            $html .= "      <input type=\"submit\" class=\"input-submit\" id=\"guardar_lista\"
                                   value=\"GUARDAR\">\n";
            $html .= "    </td>\n";

            $html .= "  <tr class=\"formulacion_table_list\">";
            $html .= "    <td>CODIGO PRODUCTO</td>";
            $html .= "    <td>NOMBRE PRODUCTO</td>";
            $html .= "    <td>PRECIO SIN IVA</td>";
            $html .= "    <td>IVA</td>";
            $html .= "    <td>UNIDAD DE EMPAQUE</td>";
            $html .= "    <td>VALOR TOTAL</td>";
            $html .= "    <td>OPCIONES</td>";
            $html .= "  </tr>";

            $est = "";

            foreach ($listaProveedores['producto'] as $k => $d) {

                $est = ($est == "modulo_list_claro") ? "modulo_list_oscuro" : "modulo_list_claro";

                $fncjs = "EvaluarValor('inp_precio_" . $d['codigo_producto'] . "',
                                   'inp_iva_" . $d['codigo_producto'] . "',
                                   'inp_precio_total_" . $d['codigo_producto'] . "',
                                   'chk_cargo_" . $d['codigo_producto'] . "')";

                $html .= "  <tr class=\"" . $est . "\">";
                $html .= "    <td>" . $d['codigo_producto'] . "</td>";
                $html .= "    <td>" . $d['descripcion'] . "</td>";

                $html .= "    <td align=\"center\">\n";

                $html .= "        $<input class=\"monto\"
                                    type=\"text\" min=\"0\" step=\"any\" name=\"productos[" . $d['codigo_producto'] . "][precio]\"
                                    id=\"inp_precio_" . $d['codigo_producto'] . "\" ";
                $html .= "          class=\"input-text\" style=\"width:60%\" value= \"0\" onkeyup=\"" . $fncjs . "\" >\n";

                $html .= "    </td>";

                $html .= "    <td align=\"center\">\n";

                $html .= "         <input class=\"monto\"
                                      type=\"text\" min=\"0\" maxlength=\"2\" step=\"any\" name=\"productos[" . $d['codigo_producto'] . "][iva]\"
                                      id=\"inp_iva_" . $d['codigo_producto'] . "\" ";
                $html .= "            class=\"input-text\" style=\"width:30%\" value= \"0\" onkeyup=\"" . $fncjs . "\" > % \n";

                $html .= "    </td>";

                $html .= "    <td align=\"center\">\n";

                $html .= "         <input class=\"monto\"
                                    type=\"text\" min=\"0\" step=\"any\" name=\"productos[" . $d['codigo_producto'] . "][monto_empaque]\"
                                    id=\"inp_monto_empaque_" . $d['codigo_producto'] . "\" ";
                $html .= "          class=\"input-text\" style=\"width:40%\" value= \"0\">\n";

                $html .= "    </td>";

                $html .= "    <td align=\"center\">\n";

                $html .= "         $<input class=\"monto_total\"
                                      type=\"number\" min=\"0\" step=\"any\" name=\"productos[" . $d['codigo_producto'] . "][monto_total]\"
                                      id=\"inp_precio_total_" . $d['codigo_producto'] . "\" ";
                $html .= "            class=\"input-text\" style=\"width:60%\" value= \"0\" readonly >\n";

                $html .= "    </td>";

                $html .= "    <td align=\"center\">\n";

                $html .= "        <input type=\"checkbox\" id=\"chk_cargo_" . $d['codigo_producto'] . "\"
                                   name=\"productos[" . $d['codigo_producto'] . "][chk_cargo]\"
                                   value=\"1\" >\n";

                $html .= "    </td>";

                $html .= "  </tr>";
            }

            $html .= "<br>";
            $html .= "    <td align=\"right\" width=\"100%\" colspan=\"7\">\n";
            $html .= "      <input type=\"submit\" class=\"input-submit\" id=\"guardar_lista\"
                                   value=\"GUARDAR\">\n";
            $html .= "    </td>\n";

            $html .= "<br>";
            $pgh     = AutoCarga::factory("ClaseHTML");
            $action1 = "BuscarProductoLista(";
            $html .= $pgh->ObtenerPaginadoXajax($conteo, $pagina, $action1);

            $html .= "</table>";
            $html .= "</form>\n";

            $html .= "<br>\n";

            //$html .= "</div>\n";

        } else {
            $html .= "<div style=\"text-align:center\" class=\"label_error\">\n";
            $html .= "  LA BUSQUEDA NO ARROJO RESULTADOS \n";
            $html .= "</div>\n";
        }
        return $html;
    }

    public function CrearVentana($tmn = 370, $tmny = "'auto'", $contenido1, $titulo1, $tmn2 = 370, $tmny2 = "'auto'", $contenido2, $titulo2)
    {
        $html .= "<script>\n";
        $html .= "  var contenedor = 'Contenedor';\n";
        $html .= "  var titulo = 'titulo';\n";
        $html .= "  var hiZ = 4;\n";
        $html .= "  function OcultarSpan(capa)\n";
        $html .= "  { \n";
        $html .= "      try\n";
        $html .= "      {\n";
        $html .= "          e = xGetElementById(capa);\n";
        $html .= "          e.style.display = \"none\";\n";
        $html .= "      }\n";
        $html .= "      catch(error){}\n";
        $html .= "  }\n";
        $html .= "  function MostrarSpan(capa)\n";
        $html .= "  { \n";
        $html .= "      try\n";
        $html .= "      {\n";
        $html .= "          e = xGetElementById(capa);\n";
        $html .= "          e.style.display = \"\";\n";
        $html .= "        Iniciar();\n";
        $html .= "      }\n";
        $html .= "      catch(error){alert(error)}\n";
        $html .= "  }\n";
        $html .= "  function MostrarSpan2(capa)\n";
        $html .= "  { \n";
        $html .= "      try\n";
        $html .= "      {\n";
        $html .= "          e = xGetElementById(capa);\n";
        $html .= "          e.style.display = \"\";\n";
        $html .= "        Iniciar2();\n";
        $html .= "      }\n";
        $html .= "      catch(error){alert(error)}\n";
        $html .= "  }\n";

        $html .= "    function MostrarTitle(Seccion)\n";
        $html .= "  {\n";
        $html .= "      xShow(Seccion);\n";
        $html .= "  }\n";
        $html .= "  function OcultarTitle(Seccion)\n";
        $html .= "  {\n";
        $html .= "      xHide(Seccion);\n";
        $html .= "  }\n";

        $html .= "  function myOnDragStart(ele, mx, my)\n";
        $html .= "  {\n";
        $html .= "    window.status = '';\n";
        $html .= "    if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
        $html .= "    else xZIndex(ele, hiZ++);\n";
        $html .= "    ele.myTotalMX = 0;\n";
        $html .= "    ele.myTotalMY = 0;\n";
        $html .= "  }\n";
        $html .= "  function myOnDrag(ele, mdx, mdy)\n";
        $html .= "  {\n";
        $html .= "    if (ele.id == titulo) {\n";
        $html .= "      xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
        $html .= "    }\n";
        $html .= "    else {\n";
        $html .= "      xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
        $html .= "    }  \n";
        $html .= "    ele.myTotalMX += mdx;\n";
        $html .= "    ele.myTotalMY += mdy;\n";
        $html .= "  }\n";
        $html .= "  function myOnDragEnd(ele, mx, my)\n";
        $html .= "  {\n";
        $html .= "  }\n";
        $html .= "    function Iniciar()\n";
        $html .= "  {\n";
        $html .= "      contenedor = 'Contenedor';\n";
        $html .= "      titulo = 'titulo';\n";
        $html .= "        ele = xGetElementById('Contenido');\n";
        $html .= "    xResizeTo(ele," . $tmn . ", " . $tmny . ");\n";
        $html .= "      ele = xGetElementById(contenedor);\n";
        $html .= "    xResizeTo(ele," . $tmn . ", " . $tmny . ");\n";
        $html .= "    xMoveTo(ele, xClientWidth()/4, xScrollTop()+20);\n";
        $html .= "      ele = xGetElementById(titulo);\n";
        $html .= "    xResizeTo(ele," . ($tmn - 20) . ", 20);\n";
        $html .= "      xMoveTo(ele, 0, 0);\n";
        $html .= "    xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
        $html .= "      ele = xGetElementById('cerrar');\n";
        $html .= "    xResizeTo(ele,20, 20);\n";
        $html .= "      xMoveTo(ele," . ($tmn - 20) . ", 0);\n";
        $html .= "  }\n";
        $html .= "    function Iniciar2()\n";
        $html .= "  {\n";
        $html .= "      contenedor = 'Contenedor2';\n";
        $html .= "      titulo = 'titulo2';\n";
        $html .= "        ele = xGetElementById('Contenido2');\n";
        $html .= "    xResizeTo(ele," . $tmn2 . ", " . $tmny2 . ");\n";
        $html .= "      ele = xGetElementById(contenedor);\n";
        $html .= "    xResizeTo(ele," . $tmn2 . ", " . $tmny2 . ");\n";
        $html .= "    xMoveTo(ele, xClientWidth()/4, xScrollTop()+20);\n";
        $html .= "      ele = xGetElementById(titulo);\n";
        $html .= "    xResizeTo(ele," . ($tmn2 - 20) . ", 20);\n";
        $html .= "      xMoveTo(ele, 0, 0);\n";
        $html .= "    xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
        $html .= "      ele = xGetElementById('cerrar2');\n";
        $html .= "    xResizeTo(ele,20, 20);\n";
        $html .= "      xMoveTo(ele," . ($tmn2 - 20) . ", 0);\n";
        $html .= "  }\n";
        $html .= "</script>\n";
        $html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
        $html .= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;text-align:center;\">" . $titulo1 . "</div>\n";
        $html .= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Contenedor')\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
        $html .= "  <div id='Contenido' class='d2Content'>\n";
        $html .= "  " . $contenido1;
        $html .= "  </div>\n";
        $html .= "</div>\n";
        $html .= "<div id='Contenedor2' class='d2Container' style=\"display:none;z-index:4\">\n";
        $html .= "  <div id='titulo2' class='draggable' style=\"    text-transform: uppercase;text-align:center;\">" . $titulo2 . "</div>\n";
        $html .= "  <div id='cerrar2' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Contenedor2')\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
        $html .= "  <div id='Contenido2' class='d2Content'>\n";
        $html .= "  " . $contenido2;
        $html .= "  </div>\n";
        $html .= "</div>\n";
        return $html;
    }

    public function FormaMensajeProductos($action, $mensaje, $repetidos, $enlista, $noInventario, $noValidos, $datosIncompletos)
    {
        $html = ThemeAbrirTabla('MENSAJE');
        $html .= "<table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\" >\n";
        $html .= "  <tr class=\"normal_10AN\">\n";
        $html .= "    <td align=\"center\">\n" . $mensaje . "</td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= "<br>\n";

        $est = 'modulo_list_oscuro';
        if (!empty($repetidos)) {
            $contador = 1;
            $html .= "<table align=\"center\" width=\"50%\">\n";
            $html .= "  <tr class=\"formulacion_table_list\">\n";
            $html .= "    <td colspan=\"3\">PRODUCTOS ENCONTRADOS MAS DE UNA VEZ EN EL ARCHIVO </td>\n";
            $html .= "  </tr>\n";
            $html .= "  <tr class=\"" . $est . "\" >\n";

            foreach ($repetidos as $key => $dtl) {
                $html .= "    <td width=\"33%\" >" . $key . "</td>\n";
                if ($contador == 3) {
                    $est = ($est == 'modulo_list_oscuro') ? 'modulo_list_claro' : 'modulo_list_oscuro';
                    $html .= "  </tr>\n";
                    $html .= "  <tr class=\"" . $est . "\"  >\n";
                    $contador = 0;
                }
                $contador++;
            }

            $aux = sizeof($repetidos) % 3;
            if ($aux > 0) {
                $html .= "    <td colspan=\"" . (3 - $aux) . "\"> Productos repetidos. </td>\n";
            }

            $html .= "  </tr>\n";
            $html .= "</table>\n";
            $html .= "<br>\n";
        }

        if (!empty($enlista)) {
            $contador = 1;
            $html .= "<table align=\"center\" width=\"50%\">\n";
            $html .= "  <tr class=\"formulacion_table_list\">\n";
            $html .= "    <td colspan=\"3\">PRODUCTOS QUE YA SE ENCUENTRAN EN LA LISTA DE PRECIOS</td>\n";
            $html .= "  </tr>\n";
            $html .= "  <tr class=\"" . $est . "\" >\n";

            foreach ($enlista as $key => $dtl) {
                $html .= "    <td width=\"33%\" >" . $key . "</td>\n";
                if ($contador == 3) {
                    $est = ($est == 'modulo_list_oscuro') ? 'modulo_list_claro' : 'modulo_list_oscuro';
                    $html .= "  </tr>\n";
                    $html .= "  <tr class=\"" . $est . "\"  >\n";
                    $contador = 0;
                }
                $contador++;
            }

            $aux = sizeof($enlista) % 3;
            if ($aux > 0) {
                $html .= "    <td colspan=\"" . (3 - $aux) . "\"> Productos existentes en la lista de precios. </td>\n";
            }

            $html .= "  </tr>\n";
            $html .= "</table>\n";
            $html .= "<br>\n";
        }

        if (!empty($noValidos)) {
            $contador = 1;
            $html .= "<table align=\"center\" width=\"50%\">\n";
            $html .= "  <tr class=\"formulacion_table_list\">\n";
            $html .= "    <td colspan=\"3\">PRODUCTOS CON DATOS ERRADOS O CARACTERES ESPECIALES.</td>\n";
            $html .= "  </tr>\n";
            $html .= "  <tr class=\"" . $est . "\" >\n";

            foreach ($noValidos as $key => $dtl) {
                $html .= "    <td width=\"33%\" >" . $key . "</td>\n";
                if ($contador == 3) {
                    $est = ($est == 'modulo_list_oscuro') ? 'modulo_list_claro' : 'modulo_list_oscuro';
                    $html .= "  </tr>\n";
                    $html .= "  <tr class=\"" . $est . "\"  >\n";
                    $contador = 0;
                }
                $contador++;
            }

            $aux = sizeof($noValidos) % 3;
            if ($aux > 0) {
                $html .= "    <td colspan=\"" . (3 - $aux) . "\"> Productos con datos errados o caracteres especiales. </td>\n";
            }

            $html .= "  </tr>\n";
            $html .= "</table>\n";
            $html .= "<br>\n";
        }

        if (!empty($datosIncompletos)) {
            $contador = 1;
            $html .= "<table align=\"center\" width=\"50%\">\n";
            $html .= "  <tr class=\"formulacion_table_list\">\n";
            $html .= "    <td colspan=\"3\">PRODUCTOS CON DATOS FALTANTES O SIN DELIMITACION POR ';'.</td>\n";
            $html .= "  </tr>\n";
            $html .= "  <tr class=\"" . $est . "\" >\n";

            foreach ($datosIncompletos as $key => $dtl) {
                $html .= "    <td width=\"33%\" >" . $key . "</td>\n";
                if ($contador == 3) {
                    $est = ($est == 'modulo_list_oscuro') ? 'modulo_list_claro' : 'modulo_list_oscuro';
                    $html .= "  </tr>\n";
                    $html .= "  <tr class=\"" . $est . "\"  >\n";
                    $contador = 0;
                }
                $contador++;
            }

            $aux = sizeof($datosIncompletos) % 3;
            if ($aux > 0) {
                $html .= "    <td colspan=\"" . (3 - $aux) . "\"> Productos con datos faltantes o sin delimitacion por ';'. </td>\n";
            }

            $html .= "  </tr>\n";
            $html .= "</table>\n";
            $html .= "<br>\n";
        }

        if (!empty($noInventario)) {
            $contador = 1;
            $html .= "<table align=\"center\" width=\"50%\">\n";
            $html .= "  <tr class=\"formulacion_table_list\">\n";
            $html .= "    <td colspan=\"3\">PRODUCTOS DEL ARCHIVO QUE NO ESTAN EN EL INVENTARIO</td>\n";
            $html .= "  </tr>\n";
            $html .= "  <tr class=\"" . $est . "\" >\n";

            foreach ($noInventario as $key => $dtl) {
                $html .= "    <td width=\"33%\" >" . $key . "</td>\n";
                if ($contador == 3) {
                    $est = ($est == 'modulo_list_oscuro') ? 'modulo_list_claro' : 'modulo_list_oscuro';
                    $html .= "  </tr>\n";
                    $html .= "  <tr class=\"" . $est . "\"  >\n";
                    $contador = 0;
                }
                $contador++;
            }

            $aux = sizeof($noInventario) % 3;
            if ($aux > 0) {
                $html .= "    <td colspan=\"" . (3 - $aux) . "\"> Productos no existen en inventario. </td>\n";
            }

            $html .= "  </tr>\n";
            $html .= "</table>\n";
            $html .= "<br>\n";
        }
        $html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
        $html .= "  <tr>\n";
        $html .= "      <td align=\"center\"><br>\n";
        $html .= "          <form name=\"form\" action=\"" . $action['volver'] . "\" method=\"post\">";
        $html .= "              <input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">";
        $html .= "          </form>";
        $html .= "      </td>";
        $html .= "  </tr>";
        $html .= "</table>";
        $html .= ThemeCerrarTabla();
        return $html;
    }

    public function FormaMensajeModulo($action, $mensaje)
    {
        $html = ThemeAbrirTabla('MENSAJE');
        $html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
        $html .= "  <tr>\n";
        $html .= "      <td>\n";
        $html .= "        <table width=\"100%\" class=\"modulo_table_list\">\n";
        $html .= "          <tr class=\"normal_10AN\">\n";
        $html .= "            <td align=\"center\">\n" . $mensaje . "</td>\n";
        $html .= "          </tr>\n";
        $html .= "        </table>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr>\n";
        $html .= "      <td align=\"center\"><br>\n";
        $html .= "          <form name=\"form\" action=\"" . $action['volver'] . "\" method=\"post\">";
        $html .= "              <input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">";
        $html .= "          </form>";
        $html .= "      </td>";
        $html .= "  </tr>";
        $html .= "</table>";
        $html .= ThemeCerrarTabla();
        return $html;
    }

}
