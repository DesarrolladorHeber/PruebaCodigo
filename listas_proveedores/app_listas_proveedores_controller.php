<?php
/*
*Dev.: Steven Santacruz Garcia
*Date.: 12/04/18
*/
class app_listas_proveedores_controller extends classModulo
{

    public function main()
    {

        $request  = $_REQUEST;
        $usuario  = UserGetUID();
        $permisos = ModuloGetPermisos($request['contenedor'], $request['modulo'], $usuario);

        SessionDelVar("Permisoslistas_proveedores");

        $url[0] = 'app';
        $url[1] = 'listas_proveedores';
        $url[2] = 'controller';
        $url[3] = 'ListasDePreciosProveedores';
        $url[4] = 'Permisos';

        $arreglo[0] = 'EMPRESA';

        $this->salida = MenuAcceso('LISTAS DE PRECIOS PROVEEDORES ', $arreglo, $permisos, $url, ModuloGetURL('system', 'Menu'));

        return true;
    }
    /**
     *
     */
    public function ListasDePreciosProveedores()
    {
        $sql  = AutoCarga::factory('listas_proveedoresSQL', 'classes', 'app', 'listas_proveedores');
        $html = AutoCarga::factory('listas_proveedoresHTML', 'views', 'app', 'listas_proveedores');

        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS("CrossBrowserDrag");

        $this->IncludeJS("CrossBrowser");

        IncludeFileModulo("listas_proveedoresXjx", "RemoteXajax", "app", "listas_proveedores");
        $this->SetXajax(array("EliminarLista", "CrearLista"),
            null, "ISO-8859-1");

        $request = $_REQUEST;

        // echo "<pre>";
        // print_r($request);
        // echo "</pre>";

        if (isset($request['Permisos'])) {
            SessionSetVar("Permisoslistas_proveedores", $request['Permisos']);
        }

        $permisos = SessionGetVar("Permisoslistas_proveedores");

        $proveedores = $sql->ObtenerListasProveedores($permisos, $request);

        $action['volver']         = ModuloGetURL('app', 'listas_proveedores', 'controller', 'main');
        $action['paginador']      = ModuloGetURL('app', 'listas_proveedores', 'controller', 'ListasDePreciosProveedores', array("mensaje" => 'PAGINANDO'));
        $action['consultarLista'] = ModuloGetURL('app', 'listas_proveedores', 'controller', 'ListaPrecios');
        $action['crearLista']     = ModuloGetURL('app', 'listas_proveedores', 'controller', 'ListaProveedores');

        $this->salida .= $html->ListasPreciosProveedores($action, $proveedores, $request, $sql->conteo, $sql->pagina, $permisos);

        return true;
    }

    public function ListaProveedores()
    {
        $sql  = AutoCarga::factory('listas_proveedoresSQL', 'classes', 'app', 'listas_proveedores');
        $html = AutoCarga::factory('listas_proveedoresHTML', 'views', 'app', 'listas_proveedores');

        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS("CrossBrowserDrag");
        $this->IncludeJS("CrossBrowser");

        IncludeFileModulo("listas_proveedoresXjx", "RemoteXajax", "app", "listas_proveedores");
        $this->SetXajax(array("EliminarLista", "CrearLista"),
            null, "ISO-8859-1");

        $request = $_REQUEST;

        // echo "<pre>";
        // print_r($request);
        // echo "</pre>";

        if (isset($request['Permisos'])) {
            SessionSetVar("Permisoslistas_proveedores", $request['Permisos']);
        }

        $permisos = SessionGetVar("Permisoslistas_proveedores");

        $proveedores = $sql->ObtenerProveedores($permisos, $request);

        $action['volver']         = ModuloGetURL('app', 'listas_proveedores', 'controller', 'ListasDePreciosProveedores');
        $action['paginador']      = ModuloGetURL('app', 'listas_proveedores', 'controller', 'ListaProveedores', array("mensaje" => 'PAGINANDO'));
        $action['consultarLista'] = ModuloGetURL('app', 'listas_proveedores', 'controller', 'ListaPrecios');

        $this->salida .= $html->ListaProveedores($action, $proveedores, $request, $sql->conteo, $sql->pagina, $permisos);

        return true;
    }

    public function ListaPrecios()
    {
        $sql  = AutoCarga::factory('listas_proveedoresSQL', 'classes', 'app', 'listas_proveedores');
        $html = AutoCarga::factory('listas_proveedoresHTML', 'views', 'app', 'listas_proveedores');

        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS("CrossBrowserDrag");
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("TabPaneLayout");
        $this->IncludeJS("TabPaneApi");
        $this->IncludeJS("TabPane");

        IncludeFileModulo("listas_proveedoresXjx", "RemoteXajax", "app", "listas_proveedores");
        $this->SetXajax(array("BuscarListaProveedores", "BuscarPreciosListaProveedores",
            "GuardarListaProveedor", "EliminarProducto",
            "ModificarProducto", "GuardarInformacionProducto"),
            null, "ISO-8859-1");

        $request = $_REQUEST;

        // echo "<pre>";
        // print_r($request);
        // echo "</pre>";

        if (isset($request['Permisos'])) {
            SessionSetVar("Permisoslistas_proveedores", $request['Permisos']);
        }

        $permisos = SessionGetVar("Permisoslistas_proveedores");

        //$listaProveedores = $sql->ObtenerListaProveedores($permisos, $request);

        //$listaPreciosProveedores = $sql->ObtenerPreciosListaProveedores($permisos, $request);

        $action['volver']        = ModuloGetURL('app', 'listas_proveedores', 'controller', 'ListasDePreciosProveedores');
        $action['subir_archivo'] = ModuloGetURL('app', 'listas_proveedores', 'controller', 'SubirArchivo');
        //$action['paginador'] = ModuloGetURL('app', 'listas_proveedores', 'controller', 'ListaPrecios', array("mensaje" => 'PAGINANDO'));

        if ($request['insert'] == '1') {
            $sql->CrearListaProveedor($request);
            //ModuloGetURL('app', 'listas_proveedores', 'controller', 'ListasDePreciosProveedores');
        }

        $this->salida .= $html->ListaPreciosProveedor($action, $listaProveedores, $request, $permisos);

        return true;
    }

    public function SubirArchivo()
    {
        $request = $_REQUEST;

        $request['usuario_id'] = UserGetUID();
        $arc                   = AutoCarga::factory('listas_proveedoresSQL', 'classes', 'app', 'listas_proveedores');
        $frm                   = AutoCarga::factory('listas_proveedoresHTML', 'views', 'app', 'listas_proveedores');

        $rst = $arc->SubirArchivoProductos($request);

        $action['volver'] = ModuloGetURL('app', 'listas_proveedores', 'controller', 'ListasDePreciosProveedores');
        if (!$rst) {
            $mensaje      = "HA OCURRIDO UN ERROR AL MOMENTO DE SUBIR EL ARCHIVO: <br>";
            $this->salida = $frm->FormaMensajeModulo($action, $mensaje);
        } else {
            $mensaje      = "EL ARCHIVO " . $_FILES['archivo_capitado']['name'] . ", FUE CARGADO CORRECTAMENTE";
            $this->salida = $frm->FormaMensajeProductos($action, $mensaje, $rst['repetidos'], $rst['enlista'], $rst['noInventario'], $rst['noValidos'], $rst['datosIncompletos']);
        }

        return true;
    }

}
