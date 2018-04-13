<?php
/*
 *Dev.: Steven Santacruz Garcia
 *Date.: 12/04/18
 */
function BuscarListaProveedores($form, $offset, $cod_lista)
{
    //echo "Hola perros ====> ".$offset;

    $objResponse = new xajaxResponse();

    $sql = AutoCarga::factory("listas_proveedoresSQL", "classes", "app", "listas_proveedores");
    $mdl = AutoCarga::factory("listas_proveedoresHTML", "views", "app", "listas_proveedores");

    if (isset($request['Permisos'])) {
        SessionSetVar("Permisoslistas_proveedores", $request['Permisos']);
    }

    $permisos = SessionGetVar("Permisoslistas_proveedores");

    $listaProveedores = $sql->ObtenerListaProveedores($form, $offset, $cod_lista);

    $html = $mdl->BuscarListaProveedoresView($action, $listaProveedores, $request, $sql->conteo, $sql->pagina, $permisos);

    $objResponse->assign("resultado_adicionar_productos", "innerHTML", $html);
    $objResponse->script("$( \"#info_capa_i\" ).scrollTop( 0 )");

    return $objResponse;
}

function BuscarPreciosListaProveedores($form, $offset, $cod_lista)
{
    //echo "Hola perros ====> ".$offset;

    $objResponse = new xajaxResponse();

    $sql = AutoCarga::factory("listas_proveedoresSQL", "classes", "app", "listas_proveedores");
    $mdl = AutoCarga::factory("listas_proveedoresHTML", "views", "app", "listas_proveedores");

    if (isset($request['Permisos'])) {
        SessionSetVar("Permisoslistas_proveedores", $request['Permisos']);
    }

    $permisos = SessionGetVar("Permisoslistas_proveedores");

    $listaProveedores = $sql->ObtenerPreciosListaProveedores($form, $offset, $cod_lista);

    $html = $mdl->BuscarPreciosListaProveedoresView($action, $listaProveedores, $request, $sql->conteo, $sql->pagina, $permisos);

    $objResponse->assign("resultado_precios_proveedor", "innerHTML", $html);
    $objResponse->script("$( \"#info_capa_i\" ).scrollTop( 0 )");

    return $objResponse;
}

function GuardarListaProveedor($form, $cod_lista, $pagina)
{

    $objResponse = new xajaxResponse();
    $mdl         = AutoCarga::factory("listas_proveedoresSQL", "classes", "app", "listas_proveedores");
    $view        = AutoCarga::factory("listas_proveedoresHTML", "views", "app", "listas_proveedores");

    $msj       = "";
    $chk_cargo = 0;
    if (!empty($form)) {
        foreach ($form['productos'] as $key => $producto) {
            if ($producto['chk_cargo'] == 1) {
                $chk_cargo++;
            }

            if ($producto['chk_cargo'] == 1 && $producto['precio'] <= 0) {
                $msj = "EL PRECIO DEL PRODUCTO " . $key . " DEBE SER MAYOR A CERO.";
                $objResponse->alert($msj);
                return $objResponse;
            }

            if ($producto['chk_cargo'] == 1 && $producto['monto_empaque'] <= 0) {
                $msj = "LA UNIDAD DE EMPAQUE DEL PRODUCTO " . $key . " DEBE SER MAYOR A CERO.";
                $objResponse->alert($msj);
                return $objResponse;
            }

            if ($producto['chk_cargo'] == 1 && $producto['monto_total'] <= 0) {
                $msj = "EL VALOR TOTAL DEL PRODUCTO " . $key . " DEBE SER MAYOR A CERO.";
                $objResponse->alert($msj);
                return $objResponse;
            }

        }
    }

    if ($chk_cargo != 0) {

        $rst = $mdl->InsertProductosListaProveedor($form, $cod_lista);

        if ($rst) {
            $objResponse->alert("SE AGREGO CORRECTAMENTE LOS PRODUCTOS.");
            $objResponse->script("BuscarProductoLista('" . $pagina . "');");

            $listaProveedores = $mdl->ObtenerPreciosListaProveedores($form, $offset, $cod_lista);

            $html = $view->BuscarPreciosListaProveedoresView($action, $listaProveedores, $request, $mdl->conteo, $mdl->pagina, $permisos);

            $objResponse->assign("resultado_precios_proveedor", "innerHTML", $html);
            // $objResponse->script("location.reload();");
        } else {
            $objResponse->alert("ERROR " . $obje->mensajeDeError);
        }

    } else {
        $objResponse->alert("NO SE HA SELECCIONADO NINGUN PRODUCTO.");
    }

    return $objResponse;

}

function EliminarLista($cod_lista, $pagina)
{
    $objResponse = new xajaxResponse();
    $mdl         = AutoCarga::factory("listas_proveedoresSQL", "classes", "app", "listas_proveedores");

    $rst = $mdl->EliminarLista($cod_lista);

    if ($rst) {
        $objResponse->alert("SE ELIMINO LA LISTA CORRECTAMENTE.");
        $objResponse->script("location.reload();");
    } else {
        $objResponse->alert("ERROR " . $obje->mensajeDeError);
    }

    return $objResponse;
}

function CrearLista($tipo_id_tercero, $tercero_id, $nombre_tercero)
{
    $objResponse = new xajaxResponse();
    $mdl         = AutoCarga::factory("listas_proveedoresSQL", "classes", "app", "listas_proveedores");

    $rst = $mdl->CrearListaProveedor($tipo_id_tercero, $tercero_id, $nombre_tercero);

    if ($rst) {
        $objResponse->alert("LISTA CREADA CON EXITO.");
        $ir = ModuloGetURL('app', 'listas_proveedores', 'controller', 'ListasDePreciosProveedores');
        $objResponse->script("window.location.replace('" . $ir . "');");
    } else {
        $objResponse->alert("ERROR " . $obje->mensajeDeError);
    }

    return $objResponse;
}

function EliminarProducto($cod_lista, $codigo_producto, $pagina)
{
    $objResponse = new xajaxResponse();
    $mdl         = AutoCarga::factory("listas_proveedoresSQL", "classes", "app", "listas_proveedores");

    $rst = $mdl->EliminarProducto($cod_lista, $codigo_producto);

    if ($rst) {
        $objResponse->alert("PRODUCTO ELIMINADO DE LA LISTA");
        $objResponse->script("BuscarProductoLista('" . $pagina . "');");
    } else {
        $objResponse->alert("ERROR " . $obje->mensajeDeError);
    }

    return $objResponse;
}

function ModificarProducto($cod_lista, $codigo_producto)
{
    $objResponse = new xajaxResponse();
    $mdl         = AutoCarga::factory("listas_proveedoresSQL", "classes", "app", "listas_proveedores");

    $producto = $mdl->ObtenerInformacionProductosListasDePrecios($cod_lista, $codigo_producto);

    // echo "<pre>";
    // echo "lista ====> ".$cod_lista;
    // print_r($producto);
    // echo "</pre>";

    $fncjsMOD = "EvaluarValor(  'inp_precio_" . $producto['codigo_producto'] . "',
                                'inp_iva_" . $producto['codigo_producto'] . "',
                                'inp_precio_total_" . $producto['codigo_producto'] . "',
                                'chk_cargo_" . $producto['codigo_producto'] . "')";

    $html = "<input type=\"hidden\" name=\"cod_lista\" value=\"" . $cod_lista . "\">\n";
    $html .= "<input type=\"hidden\" name=\"codigo_producto\" value=\"" . $codigo_producto . "\">\n";
    $html .= "<input type=\"hidden\" name=\"costo\" value=\"" . $producto['costo'] . "\">\n";

    $html .= "<table width=\"100%\" class=\"modulo_table_list\" align=\"center\">\n";
    $html .= " <tr class=\"formulacion_table_list\">\n";
    $html .= "   <td colspan=\"2\">MODIFICAR PRODUCTO</td>\n";
    $html .= " </tr>\n";

    $html .= " <tr class=\"formulacion_table_list\">\n";
    $html .= "   <td width=\"30%\" align=\"left\" >CODIGO</td>\n";
    $html .= "   <td width=\"70%\" align=\"left\" class=\"modulo_list_claro\">" . $producto['codigo_producto'] . "</td>\n";
    $html .= " </tr>\n";

    $html .= " <tr class=\"formulacion_table_list\">\n";
    $html .= "   <td align=\"left\" >PRODUCTO</td>\n";
    $html .= "   <td align=\"left\" class=\"modulo_list_claro\">" . $producto['descripcion'] . "</td>\n";
    $html .= " </tr>\n";

    $html .= "</table>\n";

    $html .= "<br>\n";

    $html .= "<table width=\"100%\" class=\"modulo_table_list\" align=\"center\">\n";

    $html .= "  <tr class=\"modulo_list_claro\">\n";
    $html .= "    <td class=\"formulacion_table_list\" width=\"25%\">UNIDAD DE EMPAQUE</td>\n";
    $html .= "    <td colspan=\"3\">\n";

    $html .= "         <input class=\"monto_empaque\"
                                    type=\"number\" min=\"0\" step=\"any\" name=\"productos[" . $producto['codigo_producto'] . "][monto_empaque]\"
                                    id=\"inp_monto_empaque_" . $producto['codigo_producto'] . "\" ";
    $html .= "          class=\"input-text\" style=\"width:40%\" value=\"" . ($producto['unidad_empaque']) . "\" >\n";
    // $html .= "      <input type=\"text\" class=\"input-text\" name=\"porcentaje\" value=\"" . ($producto['unidad_empaque']) . "\" style=\"width:30%\" onkeypress=\"return acceptNum(event)\" onkeyup=\"CalcularValor(document.frm_modificar,null,'" . $producto['costo'] . "',this.value)\">\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";

    $html .= "  <tr class=\"modulo_list_claro\">\n";
    $html .= "    <td class=\"formulacion_table_list\" width=\"25%\">PRECIO BRUTO</td>\n";
    $html .= "    <td colspan=\"3\">\n";

    $html .= "         <input class=\"monto\"
                                    type=\"number\" min=\"0\" step=\"any\" name=\"productos[" . $producto['codigo_producto'] . "][precio]\"
                                    id=\"inp_precio_" . $producto['codigo_producto'] . "\" ";
    $html .= "          class=\"input-text\" style=\"width:60%\" value=\"" . ($producto['precio_bruto']) . "\" onkeyup=\"" . $fncjsMOD . "\" >\n";

    // $html .= "      <input type=\"text\" class=\"input-text\" name=\"porcentaje\" value=\"" . ($producto['precio_bruto']) . "\" style=\"width:30%\" onkeypress=\"return acceptNum(event)\" onkeyup=\"CalcularValor(document.frm_modificar,null,'" . $producto['costo'] . "',this.value)\">\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";

    $html .= "  <tr class=\"modulo_list_claro\">\n";
    $html .= "    <td class=\"formulacion_table_list\" width=\"25%\">IVA</td>\n";
    $html .= "    <td colspan=\"3\">\n";

    $html .= "         <input class=\"monto_iva\"
                                      type=\"number\" min=\"0\" step=\"any\" name=\"productos[" . $producto['codigo_producto'] . "][iva]\"
                                      id=\"inp_iva_" . $producto['codigo_producto'] . "\" ";
    $html .= "            class=\"input-text\" style=\"width:40%\" value=\"" . ($producto['iva']) . "\" onkeyup=\"" . $fncjsMOD . "\" > % \n";
    // $html .= "      <input type=\"text\" class=\"input-text\" name=\"porcentaje\" value=\"" . ($producto['iva']) . "\" style=\"width:30%\" onkeypress=\"return acceptNum(event)\" onkeyup=\"CalcularValor(document.frm_modificar,null,'" . $producto['costo'] . "',this.value)\">\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";

    $html .= "  <tr class=\"modulo_list_claro\">\n";
    $html .= "    <td class=\"formulacion_table_list\" width=\"25%\">PRECIO NETO</td>\n";
    $html .= "    <td colspan=\"3\">\n";

    $html .= "          <input class=\"monto_total\"
                                      type=\"number\" min=\"0\" step=\"any\" name=\"productos[" . $producto['codigo_producto'] . "][monto_total]\"
                                      id=\"inp_precio_total_" . $producto['codigo_producto'] . "\" ";
    $html .= "            class=\"input-text\" style=\"width:60%\" value=\"" . ($producto['precio_neto']) . "\" readonly >\n";

    $html .= "    </td>\n";
    $html .= "  </tr>\n";

    $html .= "</table>\n";

    $html .= "<div id=\"error_modificar\" class=\"label_error\" style=\"text-align:center\"></div>\n";
    $html .= "<table width=\"50%\" align=\"center\">\n";
    $html .= "  <tr>\n";
    $html .= "    <td align=\"center\">\n";
    $html .= "      <input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Guardar\"/>\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    $html .= "</table>\n";

    $objResponse->assign("producto_modificar", "innerHTML", $html);
    $objResponse->script("MostrarSpan('Contenedor');");
    return $objResponse;
}

function GuardarInformacionProducto($form, $cod_lista, $pagina)
{

    $objResponse = new xajaxResponse();
    $mdl         = AutoCarga::factory("listas_proveedoresSQL", "classes", "app", "listas_proveedores");

    $msj = "";
    if (!empty($form)) {
        foreach ($form['productos'] as $key => $producto) {
            if ($producto['precio'] <= 0) {
                $msj = "EL PRECIO DEL PRODUCTO " . $key . " DEBE SER MAYOR A CERO.";
                $objResponse->alert($msj);
                return $objResponse;
            }
        }
    }

    $rst = $mdl->ActualizarProductoLista($form, $cod_lista);

    if ($rst) {
        $objResponse->alert("SE ACTUALIZO EL PRODUCTO CORRECTAMENTE.");
        $objResponse->script("OcultarSpan('Contenedor');");
        $objResponse->script("BuscarProductoLista('" . $pagina . "');");
    } else {
        $objResponse->alert("ERROR " . $obje->mensajeDeError);
    }

    return $objResponse;
}
