<?php
/*
 *Dev.: Steven Santacruz Garcia
 *Date.: 12/04/18
 */
//ssss
class listas_proveedoresSQL extends ConexionBD
{
    public function ObtenerListasProveedores($permisos, $form)
    {
        // echo "<pre>";
        // print_r($form);
        // echo "</pre>";
        $sql = " SELECT terpro.cod_lista,
                        terpro.tercero_id,
                        terpro.tipo_id_tercero,
                        ter.nombre_tercero,
                        TO_CHAR(terpro.fecha_creacion,'DD TMMonth YYYY')
                        as fecha_creacion,
                        terpro.estado
                FROM listas_proveedores AS terpro
                LEFT JOIN terceros AS ter ON (terpro.tipo_id_tercero = ter.tipo_id_tercero AND
                                              terpro.tercero_id = ter.tercero_id) ";
        $sql .= " WHERE  ter.sw_estado = '1' ";
        $sql .= " AND  terpro.estado = '1' ";
        //$sql .= " AND    terpro.empresa_id = '" . $permisos['empresa_id'] . "' ";

        if ($form[tipo_id_tercero] != '') {
            $sql .= " AND terpro.tipo_id_tercero = '" . $form['tipo_id_tercero'] . "' ";
        }

        if ($form[tercero_id] != '') {
            $sql .= " AND terpro.tercero_id = '" . $form['tercero_id'] . "' ";
        }

        if ($form[nombre_tercero] != '') {
            $sql .= " AND ter.nombre_tercero LIKE '" . $form['nombre_tercero'] . "%' ";
        }

        if (!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM (" . $sql . ") A", $form['offset'])) {
            return false;
        }

        $sql .= " ORDER BY ter.nombre_tercero ASC ";
        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset . " ";

        // $this->debug = true;

        if (!$rst = $this->ConexionBaseDatos($sql)) {
            return false;
        }

        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    public function ObtenerProveedores($permisos, $form)
    {

        // echo "<pre>";
        // print_r($form);
        // echo "</pre>";

        $sql = " SELECT  DISTINCT(terpro.tercero_id),
                        terpro.tipo_id_tercero,
                        ter.nombre_tercero
                FROM terceros_proveedores AS terpro
                LEFT JOIN terceros AS ter ON (terpro.tipo_id_tercero = ter.tipo_id_tercero AND
                                              terpro.tercero_id = ter.tercero_id) ";
        $sql .= " WHERE  ter.sw_estado = '1' ";
        $sql .= " AND    terpro.empresa_id = '" . $permisos['empresa_id'] . "' ";
        $sql .= " AND    terpro.tercero_id NOT IN (SELECT tercero_id
                                                   FROM listas_proveedores
                                                   WHERE estado = '1')";

        if ($form[tipo_id_tercero] != '') {
            $sql .= " AND terpro.tipo_id_tercero = '" . $form['tipo_id_tercero'] . "' ";
        }

        if ($form[tercero_id] != '') {
            $sql .= " AND terpro.tercero_id = '" . $form['tercero_id'] . "' ";
        }

        if ($form[nombre_tercero] != '') {
            $sql .= " AND ter.nombre_tercero LIKE '" . $form['nombre_tercero'] . "%' ";
        }

        if (!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM (" . $sql . ") A", $form['offset'])) {
            return false;
        }

        $sql .= " ORDER BY ter.nombre_tercero ASC ";
        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset . " ";

        // $this->debug = true;

        if (!$rst = $this->ConexionBaseDatos($sql)) {
            return false;
        }

        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    public function ObtenerListaProveedores($form, $offset, $cod_lista)
    {

        // echo "<pre>";
        // print_r($form);
        // echo "</pre>";

        $sql = "SELECT grupo_id,
                       descripcion
                FROM inv_grupos_inventarios ";

        if (!$rst = $this->ConexionBaseDatos($sql)) {
            return false;
        }

        $datos = array();

        while (!$rst->EOF) {
            $datos['grupo_producto'][] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }

        $sql = " SELECT codigo_producto,
                        descripcion
                 FROM inventarios_productos ";
        $sql .= " WHERE estado = '1' ";
        $sql .= " AND codigo_producto NOT IN (SELECT codigo_producto
                                              FROM lista_precios_proveedores
                                              WHERE cod_lista = " . $cod_lista . ") ";

        if ($form[grupo_id] != '') {
            $sql .= " AND grupo_id = '" . $form['grupo_id'] . "' ";
        }

        if ($form[codigo_producto] != '') {
            $sql .= " AND codigo_producto = '" . $form['codigo_producto'] . "' ";
        }

        if ($form[descripcion] != '') {
            $sql .= " AND descripcion LIKE UPPER('" . $form['descripcion'] . "%') ";
        }

        if (!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM (" . $sql . ") A", $offset)) {
            return false;
        }

        $sql .= " ORDER BY descripcion ASC ";
        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset . " ";

        //$this->debug = true;

        if (!$rst = $this->ConexionBaseDatos($sql)) {
            return false;
        }

        while (!$rst->EOF) {
            $datos['producto'][] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        // echo "<pre>";
        // print_r($datos);
        // echo "</pre>";

        return $datos;
    }

    public function ListaProducto()
    {

        $sql = " SELECT codigo_producto
                 FROM inventarios_productos ";
        $sql .= " WHERE estado = '1' ";

        if (!$rst = $this->ConexionBaseDatos($sql)) {
            return false;
        }

        $datos = array();

        while (!$rst->EOF) {
            $datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }

        $rst->Close();
        return $datos;
    }

    public function CrearListaProveedor($tipo_id_tercero, $tercero_id, $nombre_tercero)
    {

        $sql = "  SELECT cod_lista
                  FROM listas_proveedores ";
        $sql .= " WHERE tipo_id_tercero = '" . $tipo_id_tercero . "' ";
        $sql .= " AND tercero_id = '" . $tercero_id . "' ";

        if (!$rst = $this->ConexionBaseDatos($sql)) {
            return false;
        }

        $proveedor = $rst->fields[0];

        $rst->Close();

        if (!empty($proveedor)) {

            $sql = "  UPDATE listas_proveedores SET ";
            $sql .= " estado   = '1' ";
            $sql .= " WHERE tipo_id_tercero = '" . $tipo_id_tercero . "' ";
            $sql .= " AND tercero_id = '" . $tercero_id . "' ";

            if (!$rst = $this->ConexionBaseDatos($sql)) {
                return false;
            }

            $rst->Close();

            // $sql = "INSERT INTO log_lista_proveedores
            //                (cod_lista,
            //                 codigo_producto,
            //                 usuario,
            //                 tipo_log)
            //                 VALUES (";
            // $sql .= "    " . $proveedor . ", ";
            // $sql .= "    '0000', ";
            // $sql .= "     " . UserGetUID() . ",";
            // $sql .= "       5 ";
            // $sql .= "      ) ";

            // if (!$rst = $this->ConexionBaseDatos($sql)) {
            //     return false;
            // }

            // $rst->Close();

        } else {

            $sql = "INSERT INTO listas_proveedores
                         (tipo_id_tercero,
                          tercero_id,
                          usuario,
                          estado)
              VALUES (";
            $sql .= "    '" . $tipo_id_tercero . "', ";
            $sql .= "    '" . $tercero_id . "', ";
            $sql .= "     " . UserGetUID() . ",";
            $sql .= "    '1' ) ";

            if (!$rst = $this->ConexionBaseDatos($sql)) {
                return false;
            }

            $rst->Close();

        }
        return true;
    }

    public function ObtenerPreciosListaProveedores($form, $offset, $cod_lista)
    {
        // echo "<pre>";
        // print_r($form);
        // echo "</pre>";

        $sql = "SELECT grupo_id,
                       descripcion
                FROM inv_grupos_inventarios ";

        if (!$rst = $this->ConexionBaseDatos($sql)) {
            return false;
        }

        $datos = array();

        while (!$rst->EOF) {
            $datos['grupo_producto'][] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }

        $sql = " SELECT inv_pro.codigo_producto,
                        inv_pro.descripcion,
                        pre_pro.*
                 FROM inventarios_productos AS inv_pro
                 LEFT JOIN lista_precios_proveedores AS pre_pro ON (inv_pro.codigo_producto = pre_pro.codigo_producto)";
        $sql .= " WHERE estado = '1' ";
        $sql .= " AND pre_pro.cod_lista = " . $cod_lista . " ";

        if ($form[grupo_id] != '') {
            $sql .= " AND inv_pro.grupo_id = '" . $form['grupo_id'] . "' ";
        }

        if ($form[codigo_producto] != '') {
            $sql .= " AND inv_pro.codigo_producto = '" . $form['codigo_producto'] . "' ";
        }

        if ($form[descripcion] != '') {
            $sql .= " AND inv_pro.descripcion LIKE UPPER('" . $form['descripcion'] . "%') ";
        }

        if (!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM (" . $sql . ") A", $offset)) {
            return false;
        }

        $sql .= " ORDER BY descripcion ASC ";
        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset . " ";

        // $this->debug = true;

        if (!$rst = $this->ConexionBaseDatos($sql)) {
            return false;
        }

        while (!$rst->EOF) {
            $datos['producto'][] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        // echo "<pre> HOLAAAAAAAAAA";
        // print_r($datos);
        // echo "</pre>";

        return $datos;
    }

    public function ListaProductosProveedores($cod_lista)
    {
        $sql = " SELECT inv_pro.codigo_producto
                 FROM inventarios_productos AS inv_pro
                 LEFT JOIN lista_precios_proveedores AS pre_pro ON (inv_pro.codigo_producto = pre_pro.codigo_producto)";
        $sql .= " WHERE estado = '1' ";
        $sql .= " AND pre_pro.cod_lista = " . $cod_lista . " ";

        // echo $sql;

        if (!$rst = $this->ConexionBaseDatos($sql)) {
            return false;
        }

        $datos = array();

        while (!$rst->EOF) {
            $datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }

        $rst->Close();
        return $datos;
    }

    public function InsertProductosListaProveedor($form, $cod_lista)
    {

        // echo "<pre>";
        // print_r($form);
        // echo "</pre>";
        if (!empty($form)) {
            foreach ($form['productos'] as $key => $producto) {
                if ($producto['chk_cargo'] == 1 && $producto['precio'] >= 0) {

                    $sql = "INSERT INTO lista_precios_proveedores
                           (cod_lista,
                            codigo_producto,
                            precio_bruto,
                            iva,
                            precio_neto,
                            unidad_empaque)
                            VALUES (";
                    $sql .= "    " . $cod_lista . ", ";
                    $sql .= "    '" . $key . "', ";
                    $sql .= "    " . $producto['precio'] . ", ";
                    $sql .= "    " . $producto['iva'] . ", ";
                    $sql .= "    " . $producto['monto_total'] . ", ";
                    $sql .= "    " . $producto['monto_empaque'] . " ";

                    $sql .= "      ) ";

                    //echo $sql;

                    if (!$rst = $this->ConexionBaseDatos($sql)) {
                        return false;
                    }

                    $rst->Close();

                    $sql = "INSERT INTO log_lista_proveedores
                           (cod_lista,
                            codigo_producto,
                            usuario,
                            tipo_log)
                            VALUES (";
                    $sql .= "    " . $cod_lista . ", ";
                    $sql .= "    '" . $key . "', ";
                    $sql .= "     " . UserGetUID() . ",";
                    $sql .= "       1 ";
                    $sql .= "      ) ";

                    if (!$rst = $this->ConexionBaseDatos($sql)) {
                        return false;
                    }

                    $rst->Close();

                }
            }
        }

        return true;
    }

    public function EliminarLista($cod_lista)
    {

        // echo "<pre>";
        // print_r($cod_lista);
        // echo "</pre>";
        //
        $sql = "  DELETE FROM lista_precios_proveedores WHERE ";
        $sql .= " cod_lista = " . $cod_lista . " ";

        if (!$rst = $this->ConexionBaseDatos($sql)) {
            return false;
        }

        $rst->Close();

        $sql = "  UPDATE listas_proveedores SET ";
        $sql .= " estado   = '0' ";
        $sql .= " WHERE cod_lista     = " . $cod_lista . " ";

        if (!$rst = $this->ConexionBaseDatos($sql)) {
            return false;
        }

        $rst->Close();

        // $sql = "INSERT INTO log_lista_proveedores
        //                    (cod_lista,
        //                     codigo_producto,
        //                     usuario,
        //                     tipo_log)
        //                     VALUES (";
        // $sql .= "    " . $cod_lista . ", ";
        // $sql .= "    '0000', ";
        // $sql .= "     " . UserGetUID() . ",";
        // $sql .= "       4 ";
        // $sql .= "      ) ";

        // if (!$rst = $this->ConexionBaseDatos($sql)) {
        //     return false;
        // }

        // $rst->Close();

        return true;

    }

    public function EliminarProducto($cod_lista, $codigo_producto)
    {

        // echo "<pre>";
        // print_r($cod_lista);
        // echo "</pre>";

        $sql = "  DELETE FROM lista_precios_proveedores ";
        $sql .= " WHERE cod_lista = " . $cod_lista . " ";
        $sql .= " AND codigo_producto = '" . $codigo_producto . "' ";

        if (!$rst = $this->ConexionBaseDatos($sql)) {
            return false;
        }

        $rst->Close();

        $sql = "INSERT INTO log_lista_proveedores
                           (cod_lista,
                            codigo_producto,
                            usuario,
                            tipo_log)
                            VALUES (";
        $sql .= "    " . $cod_lista . ", ";
        $sql .= "    '" . $codigo_producto . "', ";
        $sql .= "     " . UserGetUID() . ",";
        $sql .= "       3 ";
        $sql .= "      ) ";

        if (!$rst = $this->ConexionBaseDatos($sql)) {
            return false;
        }

        $rst->Close();

        return true;

    }

    public function ObtenerInformacionProductosListasDePrecios($cod_lista, $codigo_producto)
    {
        $sql = " SELECT inv_pro.codigo_producto,
                        inv_pro.descripcion,
                        pre_pro.precio_bruto,
                        pre_pro.iva,
                        pre_pro.precio_neto,
                        pre_pro.unidad_empaque
                 FROM inventarios_productos AS inv_pro
                 LEFT JOIN lista_precios_proveedores AS pre_pro ON (inv_pro.codigo_producto = pre_pro.codigo_producto)";
        $sql .= " WHERE estado = '1' ";
        $sql .= " AND pre_pro.cod_lista = " . $cod_lista . " ";
        $sql .= " AND pre_pro.codigo_producto = '" . $codigo_producto . "' ";

        if (!$rst = $this->ConexionBaseDatos($sql)) {
            return false;
        }

        $datos = array();

        if (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }

        $rst->Close();
        return $datos;
    }

    public function ActualizarProductoLista($form, $cod_lista)
    {

        // echo "<pre>";
        // print_r($form);
        // echo "</pre>";
        if (!empty($form)) {
            foreach ($form['productos'] as $key => $producto) {

                $sql = "  UPDATE lista_precios_proveedores SET ";
                $sql .= " precio_bruto   = " . $producto['precio'] . ", ";
                $sql .= " iva            = " . $producto['iva'] . ", ";
                $sql .= " precio_neto    = " . $producto['monto_total'] . ", ";
                $sql .= " unidad_empaque = " . $producto['monto_empaque'] . " ";

                $sql .= " WHERE cod_lista     = " . $cod_lista . " ";
                $sql .= " AND codigo_producto = '" . $key . "' ";

                //echo $sql;

                if (!$rst = $this->ConexionBaseDatos($sql)) {
                    return false;
                }

                $rst->Close();

                $sql = "INSERT INTO log_lista_proveedores
                           (cod_lista,
                            codigo_producto,
                            usuario,
                            tipo_log)
                            VALUES (";
                $sql .= "    " . $cod_lista . ", ";
                $sql .= "    '" . $key . "', ";
                $sql .= "     " . UserGetUID() . ",";
                $sql .= "       2 ";
                $sql .= "      ) ";

                if (!$rst = $this->ConexionBaseDatos($sql)) {
                    return false;
                }

                $rst->Close();

            }
        }

        return true;
    }

    /**
     * Metodo donde se hace el ingreso del archivo de capitacion
     *
     * @param array $datos Arreglo de datos con la informacion del request
     *
     * @return boolean
     */
    public function SubirArchivoProductos($datos)
    {
        // echo "<pre>";
        // print_r($datos);
        // echo "</pre>";

        if ($_FILES['archivo_productos']['error'] != 0) {
            switch ($_FILES['archivo_productos']['error']) {
                case UPLOAD_ERR_INI_SIZE:
                    $this->mensajeDeError = "EL ARCHIVO QUE SE ESTA SUBIENDO EXCEDE EL TAMAÑO PERMITIDO";
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $this->mensajeDeError = "EL ARCHIVO QUE SE ESTA SUBIENDO EXCEDE EL TAMAÑO PERMITIDO EN LA FORMA";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $this->mensajeDeError = "EL ARCHIVO SOLO FUE SUBIDO PARCIALMENTE";
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $this->mensajeDeError = "EL ARCHIVO NO FUE SUBIDO";
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $this->mensajeDeError = "NO HAY DIRECTORIO TEMPORAL PARA SUBIR EL ARCHIVO";
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $this->mensajeDeError = "HA OCURRIDO UN ERROR AL MOMENTO DE COPIAR EL ARCHIVO A DISCO";
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $this->mensajeDeError = "HA OCURRIDO UN ERROR CON LA EXTENSION DEL ARCHIVO";
                    break;
                default:
                    $this->mensajeDeError = "HA OCURRIDO UN ERROR DESCONOCIDO MIENTRAS SE REALIZABA EL PROCESO";
                    break;
            }
            return false;
        }

        $productos = $repetidos = $enlista = $noInventario = $noValidos = $datosIncompletos = array();

        if (is_uploaded_file($_FILES['archivo_productos']['tmp_name'])) {

            $errores = array();

            $actuales = $this->ListaProductosProveedores($datos['codigo_lista']);
            $costos   = $this->ListaProducto();

            $dir_siis       = GetVarConfigAplication('DIR_SIIS');
            $nombre_archivo = $_FILES['archivo_productos']['name'];

            $this->BorrarArchivos($dir_siis . "tmp/" . $nombre_archivo);
            move_uploaded_file($_FILES['archivo_productos']['tmp_name'], $dir_siis . "tmp/" . $nombre_archivo);

            $this->ConexionTransaccion();

            $sql = "INSERT INTO listas_archivos_cargados ";
            $sql .= " ( ";
            $sql .= "     lista_archivo_cargado_id, ";
            $sql .= "     empresa_id, ";
            $sql .= "     descripcion, ";
            $sql .= "     fecha_registro, ";
            $sql .= "     usuario_id ";
            $sql .= " )";
            $sql .= "VALUES";
            $sql .= " (";
            $sql .= "     DEFAULT, ";
            $sql .= "    '01', ";
            $sql .= "    '" . $nombre_archivo . "', ";
            $sql .= "     NOW(), ";
            $sql .= "     " . $datos['usuario_id'] . " ";
            $sql .= " )";

            if (!$rst = $this->ConexionTransaccion($sql)) {
                return false;
            }

            $lines = fopen($dir_siis . "tmp/" . $nombre_archivo, "r");

            while (($tmp = fgetcsv($lines, 50096, ";")) !== false) {

                if ($tmp[0] != "") {

                    if (empty($productos[$tmp[0]])) {

                        $productos[$tmp[0]] = '1';
                        if (count($tmp) != 4) {
                            $datosIncompletos[$tmp[0]] = $tmp[0];
                        } else if (empty($costos[trim($tmp[0])])) {
                            $noInventario[$tmp[0]] = $tmp[0];
                        } else if (!empty($actuales[trim($tmp[0])])) {
                            $enlista[$tmp[0]] = $tmp[0];
                        } else if (!ctype_digit($tmp[1]) || !ctype_digit($tmp[2]) || !ctype_digit($tmp[3])) {
                            $noValidos[$tmp[0]] = $tmp[0];
                        } else {

                            $codigo_producto = trim($tmp[0]);
                            $precio_bruto    = intval($tmp[1]);
                            $iva             = intval($tmp[2]);
                            $unidad_empaque  = intval($tmp[3]);
                            $precio_neto     = $precio_bruto + ($precio_bruto * $iva / 100);

                            $sql = "INSERT INTO lista_precios_proveedores
                           (cod_lista,
                            codigo_producto,
                            precio_bruto,
                            iva,
                            precio_neto,
                            unidad_empaque)
                            VALUES (";
                            $sql .= "    " . $datos['codigo_lista'] . ", ";
                            $sql .= "    '" . $codigo_producto . "', ";
                            $sql .= "    " . $precio_bruto . ", ";
                            $sql .= "    " . $iva . ", ";
                            $sql .= "    " . $precio_neto . ", ";
                            $sql .= "    " . $unidad_empaque . " ";

                            $sql .= "      ) ";

                            if (!$rst = $this->ConexionTransaccion($sql)) {
                                return false;
                            }

                            $sql = "INSERT INTO log_lista_proveedores
                           (cod_lista,
                            codigo_producto,
                            usuario,
                            tipo_log)
                            VALUES (";
                            $sql .= "    " . $datos['codigo_lista'] . ", ";
                            $sql .= "    '" . $codigo_producto . "', ";
                            $sql .= "     " . UserGetUID() . ",";
                            $sql .= "       1 ";
                            $sql .= "      ) ";

                            if (!$rst = $this->ConexionTransaccion($sql)) {
                                return false;
                            }

                        }
                    } else {
                        $repetidos[$tmp[0]] = $tmp[0];
                    }

                }
            }

            $this->Commit();
            fclose($lines);
        }
        return array("repetidos" => $repetidos, "enlista" => $enlista, "noInventario" => $noInventario, "noValidos" => $noValidos, "datosIncompletos" => $datosIncompletos);
        //return true;
    }
    /**
     * Funcion que permite hacer un borrado de los archivos temporales
     *
     * @param string $path Ruta del archivo temporal
     *
     * @return boolean
     */
    public function BorrarArchivos($file)
    {
        unlink($path . "/" . $file);
        return true;
    }

}
