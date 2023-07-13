<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


// Route::get('home', 'Actividades\ActividadesController@index');


Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

// Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);

Route::get('/', function()
{
    if(Auth::guest()){
        return View::make('/auth/login');
    } else {
        return View::make('/home');
    }
});

Route::get('/home', function()
{
    if(Auth::guest()){
        return View::make('/auth/login');
    } else {
        return View::make('/home');
    }
});

Route::get('auth/logout', 'Auth\AuthController@logout');
Route::get('/reporteArticulo', 'Reporte\Articulo@index');
Route::get('/reporteArticuloProveedor', 'Reporte\ArticuloProveedores@query');
Route::get('/dashboard', 'Reporte\Dashboard@reporte');
Route::get('/panelcontrol', 'Reporte\PanelControl@panel');
Route::get('/transferenciasarticulos', 'Reporte\TransferenciasArticulos@query');
Route::get('/reportesArticulosWeb', 'Reporte\ReportesArticulosWeb@getArticulosWeb');
Route::get('/reportesArticulosWebnew/query', 'Reporte\ReportesArticulosWeb@query');
Route::post('/reportesArticulosWebnew/update', 'Reporte\ReportesArticulosWeb@update');
Route::get('/altaArticulo', 'Articulo\Alta@nuevoArticulo');
Route::get('/ordenesCompras', 'Articulo\OrdenesCompras@query');
Route::get('/reporteFinanciero', 'Contabilidad\ReporteFinanciero@query');
Route::get('/reporteFinancieroGraficoGanancia', 'Contabilidad\ReporteFinanciero@getDataGraficoGanancia');
Route::get('/reporteFinancieroGraficoFacturacion', 'Contabilidad\ReporteFinanciero@getDataGraficoFacturacion');
Route::get('/reporteFinancieroFacturacionVendedores', 'Contabilidad\ReporteFinanciero@getDataFacturacionVendedores');
Route::get('artimasVendidos', 'Reporte\ArticuloMasVendidos@Reporte');

Route::resource('articulos', 'Articulo\ArticulosController');
Route::resource('cierreDiario', 'CierreDiario\CierreDiarioController');
Route::resource('facturaWeb', 'CierreDiario\FacturaWebController');
Route::resource('clientes', 'Cliente\ClientesController');
Route::get('controlcierre', 'CierreDiario\ControlCierreDiario@index');
Route::get('controlcierreconsulta', 'CierreDiario\ControlCierreDiario@cierrecaja');
Route::get('controlcierrefactura', 'CierreDiario\ControlCierreDiario@cierreFacturas');

Route::get('clientesedit/{nroCliente}','Cliente\ClientesController@edit');
Route::get('articuloedit/{nroArticulo}','Articulo\ArticulosController@edit');
Route::get('articulocreate','Articulo\ArticulosController@create');

/*Pedidos*/
Route::resource('pedidos', 'Pedido\PedidosController');
Route::get('panelPedidos', 'Pedido\PanelController@panel');
Route::get('facturados', 'Pedido\PanelController@facturados');
Route::get('procesados', 'Pedido\PanelController@procesados');
Route::get('pedidospagos', 'Pedido\PanelController@pedidosPagos');
Route::get('empaquetados', 'Pedido\PanelController@empaquetados');
Route::get('cancelados', 'Pedido\PanelController@cancelados');
Route::get('todos', 'Pedido\PanelController@todos');
Route::get('asignaciongeneral','Pedido\AsignacionGeneral@inbox');
Route::get('asignaciongeneral/query','Pedido\AsignacionGeneral@query');
Route::get('asignaciongeneral/vendedoras','Pedido\AsignacionGeneral@vendedoras');
Route::post('asignaciongeneral/update','Pedido\AsignacionGeneral@update');
/*Pedidos Eficientes*/
Route::get('pedidoeficienteindex','Pedido\PedidoEficiente@index');
Route::get('/pedidoeficienteArticuloPedidos','Pedido\PedidoEficiente@articuloPedidos');
Route::post('/pedidoeficienteArticuloPedidos/agregar','Pedido\PedidoEficiente@agregar');


/*BI*/
Route::resource('biclientearticulos', 'Api\Bi\ClientesArticulosController');
Route::resource('biclientearticulosbyfactura', 'Api\Bi\ClientesArticulosController@consultaArticulosByFactura');
Route::resource('biclientefacturas', 'Api\Bi\ClientesFacturasController');
Route::resource('mapa', 'Api\Bi\MapaController');
Route::get('articulosclientes','Api\Bi\ArticulosClientes@query');
Route::get('artremanentes','Api\Bi\ArticulosRemanentes@index');
Route::get('consultaartremanentes','Api\Bi\ArticulosRemanentes@query');
Route::get('consultavendedoras','Api\Bi\Vendedoras@index');

/*Promociones*/
Route::resource('panelpromocion', 'Promociones\PanelPromocionController');
Route::get('promocionestado','Promociones\EstadoPanel@index');
Route::get('activarpromocion','Promociones\PromocionController@activar');
Route::get('finalizarpromocion','Promociones\PromocionController@finalizar');
Route::get('eliminarpromocion','Promociones\PromocionController@eliminar');
Route::resource('promocion','Promociones\PromocionController');

/*Mapa*/
Route::get('/mapadatos', 'Api\Bi\MapaController@datos');
Route::get('/rankclientes', 'Api\Bi\MapaController@rankClientes');

/*Editar General*/
Route::get('/editargeneral','Articulo\Editar@index');
Route::get('/editargeneral/query','Articulo\Editar@query');
Route::post('/editargeneral/update','Articulo\Editar@update');

/*Import Excel*/
Route::get('/importExport', 'Articulo\ImportExcel@importExport');
Route::post('/importExcel', 'Articulo\ImportExcel@importExcel');

/*Sincronización de Articulos*/
Route::get('/sincroArticulos', 'Articulo\ImportSincro@index');

/* Se utiliza para resolver problemas particulates
Cambia el num. de articulo en la tabla campras*/

Route::get('/issue','Articulo\Resolissue@run');
Route::get('/issueGanancia','Problemas\Ganancias@run');

/*Tienda Nube*/
Route::get('/tienda','Articulo\ArtTiendaNube@index');
Route::get('consultaecomerce', 'ProveedorEcomerce\TiendaNube@statusGeneral');
Route::resource('consultadetalladaecomerce', 'ProveedorEcomerce\TiendaNube@statusPorCorrida');
Route::get('/tiendanubeGetArticulosInbox', 'Api\GetArticulosTiendaNube@inbox');
Route::get('/vistaExportaExcel', 'Api\GetArticulosTiendaNube@vistaExportaExcel');
//Exporta a Excel la tabla NewArtiTN
Route::get('downloadExcel/{type}', 'Api\GetArticulosTiendaNube@downloadExcel');

/*Ordenes Tienda Nube*/
Route::get('/ordenestiendanube','TiendaNube\Ordenes@index');
Route::get('/importarordenes','TiendaNube\Ordenes@main');
Route::post('/crearpedido','TiendaNube\Ordenes@nuevoPedido');

/*Reporte Vendedores */
Route::get('/reportevendedoras', 'Reporte\Vendedoras@pedidos');
Route::get('/pedidosAsignados', 'Reporte\Vendedoras@asignados');
Route::get('/pedidosEnProceso', 'Reporte\Vendedoras@enProceso');
Route::get('/pedidosParaFacturar', 'Reporte\Vendedoras@paraFacturar');
Route::get('/pedidosEmpaquetados', 'Reporte\Vendedoras@empaquetados');

/*WhatsApp Marketing */
Route::get('/whatsappMkT','Whatsapp\Marketing@index');

/*Test*/
Route::get('/test','Test\Test@test');
Route::get('/testconvert','Test\Test@convert');
    Route::get('/checkout','TiendaNube\CarritosAbandonados@main');
/*Notas Adhesivas*/
Route::resource('notasadmin','Notas\NotasAdhesivasAdmin');
Route::get('/notasadhesivas', 'Notas\NotasAdhesivas@index');

/*Compra Automatica*/
Route::get('/compraautomail', 'Articulo\CompraAuto@inicio');
Route::get('/compraauto', 'Articulo\CompraAuto@index');

/*Mail*/
Route::get('/serverStatusMail','Mail\ServerStatusMail@serverStatusMail');

/*Reporte Auto Replicación*/
Route::get('/autosinc','Api\Automation\ReplicaTN@view');

/*codigos de Barra*/
Route::get('/barcode', 'Articulo\Barcode@crearCodigo');
Route::get('/barcodeDoble', 'Articulo\Barcode@crearCodigoDoble');

/*Reporte Contabilidad*/
Route::get('/reportesalonpedidos', 'Contabilidad\VistaPedidosSalon@index');
Route::get('/reportefacturas', 'Contabilidad\Facturas@index');
Route::get('/listarfacturas', 'Contabilidad\Facturas@listarfacturas');
Route::get('/tipo_pagos', 'Contabilidad\Facturas@tipo_pagos');
Route::get('/estados_financiera', 'Contabilidad\Facturas@estados_financiera');
Route::post('updateFactura/update','Contabilidad\Facturas@update');
Route::get ('/reporteinversion','Contabilidad\ReporteInversion@index');
Route::post ('/consultaInversion','Contabilidad\ReporteInversion@consulta');

/*Carritos Abandonados*/
Route::get('/carritosAbandonados', 'TiendaNube\CarritosAbandonados@index');
Route::get('/carritosAbandonados/query', 'TiendaNube\CarritosAbandonados@query');
Route::get('/carritosAbandonados/vendedoras', 'TiendaNube\CarritosAbandonados@vendedoras');
Route::post('/carritosAbandonados/updateVendedora', 'TiendaNube\CarritosAbandonados@updateVendedora');
Route::get('/carritosAbandonados/notasCarritos', 'TiendaNube\CarritosAbandonados@notasCarritos');
Route::get('/carritosAbandonados/agregarNota', 'TiendaNube\CarritosAbandonados@agrrgarNotaCarritoAbandonado');
Route::post('/carritosAbandonados/finalizarCarrito', 'TiendaNube\CarritosAbandonados@finalizarCarrito');

/*DashBoar Panel de Control*/
Route::get('/consultaempaquetados', 'Reporte\DashBoard\Consultas@consultaEmpaquetados');
Route::get('/consultacarritosabandonados', 'Reporte\DashBoard\Consultas@carritosAbandanados');
Route::get('/relojesoperativos', 'Reporte\DashBoard\Consultas@relojesOperativos');
Route::get('/tablaPedidos', 'Reporte\DashBoard\Consultas@tablaPedidos');
Route::get('/tablaClienteFidel', 'Reporte\DashBoard\Consultas@tablaClientesFidel');

/*Clientes Fidelizacion*/
Route::get('/clientesFidelizacion','ClientesFidelizacion\ClientesFidel@index');
Route::get('/clientesFidelizacion/query','ClientesFidelizacion\ClientesFidel@query');
Route::post('/clientesFidelizacion/update', 'ClientesFidelizacion\ClientesFidel@update');
Route::get('/clientesFidelizacion/agregarNotas', 'ClientesFidelizacion\ClientesFidel@agregarNotas');
Route::get('/clientesFidelizacion/notas', 'ClientesFidelizacion\ClientesFidel@notas');
Route::post('/clientesFidelizacion/finalizarClienteFidel', 'ClientesFidelizacion\ClientesFidel@finalizarClienteFidel');
Route::get('/clientesFidelizacion/biFidel', 'ClientesFidelizacion\ClientesFidel@biFidel');
Route::get('clientesFidelizacion/setParametros','ClientesFidelizacion\ClientesFidel@setParametros');
Route::get('clientesFidelizacion/etapasFidel','ClientesFidelizacion\ClientesFidel@etapas_fidel');
Route::post('clientesFidelizacion/guardarParametros','ClientesFidelizacion\ClientesFidel@guardarParametros');

/*Personal*/
Route::get('/fichaje','Personal\Fichaje@index');
Route::get('/fichajeCodigo','Personal\Fichaje@consultaEmpleado');
Route::get('/ingreso','Personal\Fichaje@ingreso');
Route::get('/egreso','Personal\Fichaje@egreso');
/*ABMPersonal*/
Route::get('abmpersonal','Personal\AbmPersonal@index');
Route::get('getcodigo','Personal\AbmPersonal@obtngoCodigoBarra');
Route::get('guardarPersonal','Personal\AbmPersonal@guardar');
Route::post('/guardar-imagen', 'Personal\ImagenController@guardar');

/*Estadisticas*/
Route::get('estadisticas/{users_id}','Personal\Estadisticas@index');
Route::get('estadisticaPedidos','Personal\Estadisticas@pedidos');
Route::get('obtengoFoto','Personal\Estadisticas@obtengoFoto');
Route::get('obtengoDatosPersonales','Personal\Estadisticas@obtengoDatosPersonales');
Route::get('obtengoCantPedidos','Personal\Estadisticas@obtengoCantPedidos');
Route::get('obtengoVentasSalon','Personal\Estadisticas@ventasSalon');
Route::get('obtengoVentasSalonTotales','Personal\Estadisticas@obtengoVentasSalonTotales');
Route::get('obtengoPedidosCancelados','Personal\Estadisticas@obtengoPedidosCancelados');
Route::get('obtengoCantidadNoEncuesta','Personal\Estadisticas@obtengoCantidadNoEncuesta');
Route::get('obtengoCantidadTotalesParaNoEncuesta','Personal\Estadisticas@obtengoCantidadTotalesParaNoEncuesta');
Route::get('obtengoFidelClientes','Personal\Estadisticas@obtengoFidelClientes');
Route::get('reportePedidosCancelados','Personal\Estadisticas@reportePedidosCancelados');

/*Control Fichaje*/
Route::get('/listaFichaje','Personal\ControlFichaje@control');
Route::get('listaMensual','Personal\ControlFichaje@listaMensual');
Route::get('cantDiasAusentes','Personal\ControlFichaje@cantDiasAusentes');
Route::get('listaDiasAusentes','Personal\ControlFichaje@listaDiasAusentes');

/*Control Objetivo*/
Route::get('listaObjetivos','Personal\Objetivos\ControlObjetivos@listarObjetivos');
Route::post('objetivosUpdate','Personal\Objetivos\ControlObjetivos@update');
Route::get('crearObjetivo','Personal\Objetivos\ControlObjetivos@crearObjetivo');
Route::get('resetObjetivos','Personal\Objetivos\ControlObjetivos@resetObjetivos');
Route::get('autoCargaObjetivos','Personal\Objetivos\ControlObjetivos@autoCargaObjetivos');


Route::group(['prefix' => 'api'],
    function () {
        Route::get('/listar', 'Api\FacturacionH@listar');
        Route::get('/borrargasto', 'Api\GastosController@delete');
        Route::get('/consultagastos', 'Api\ReporteController@query');
        Route::get('/consultagastosanual', 'Api\ReporteController@queryMenAnual');
        Route::get('/listaitems', 'Api\ListaItemsController@query');
        Route::get('/cajamin', 'Api\FacturacionH@cajaMin');
        Route::get('/login', 'Api\Login@authentic');
      //  Route::get('/crearusuario', 'Api\Login@crearLogin');
        Route::get('/reportes', 'Api\ReporteFacturacionH@reportes');
        Route::get('/reportesDashboardVentas', 'Api\ReportesDashboard@ventas');
        Route::get('/reportesDashboardVendedoras', 'Api\ReportesDashboard@vendedoras');
        Route::get('/reportesArticulos', 'Api\ReporteArticulos@masVendidos');
        Route::get('/proveedores', 'Api\ReporteProveedores@getProveedores');
        Route::get('/listaAllArticulos', 'Api\ListaAllArticulos@query');
        Route::get('/cotidolar', 'Api\CotiDolar@query');
        Route::get('/datosproveedor', 'Api\Proveedor@getInfo');
        Route::get('/listavendedoras', 'Api\ListaVendedoras@query');
        Route::get('/getnumpedido', 'Api\GeneraNroPedidos@generar');
        Route::post('/creopedido', 'Api\CreoPedido@inPedido');
        Route::get('/grafico', 'Api\DatosGrafico@obtengoArticulo');
        Route::get('/graficoVendedora', 'Api\DatosGrafico@obtengoArticuloVendedora');
        Route::get('/listaPedidosWeb', 'Api\ListaPedidosWeb@query');
        Route::get('/cierreCajaFacturaWeb', 'Api\CierreCajaFacturaWeb@query');
        Route::get('/modificoSiEsWeb', 'Api\ModificarArticuloWeb@modifico');
        Route::get('/sku', 'Api\Sku@query');
        Route::get('/refresh', 'Api\ArticuloProveedores@query');
        Route::get('/cancelarPedidoPropuesta', 'Api\cancelarPedido@propuesta');
        Route::get('/cancelarPedido', 'Api\cancelarPedido@cancelar');
        Route::get('/comentarios', 'Api\ListaComentariosWeb@query');
        Route::get('/registrosllamadas', 'Api\ListaRegistroLlamadasWeb@query');
        Route::get('/agregarcomentarios', 'Api\AgregaComentariosWeb@agregar');
        Route::get('/agregarregistrollamadas', 'Api\AgregarRegistroLlamadas@agregar');

        Route::get('/proveedoresSelect', 'Api\ProveedoresSelect@query');
        Route::get('/provinciasSelect', 'Api\ProvinciasSelect@query');
        Route::get('/relesWebSelect', 'Api\RolesSelect@query');
        Route::get('/listaSelectProveeor','Api\ProveedoresSelect@selectAllProveedoresMultiple');

        Route::get('/getcontrolpedidos', 'Api\GetControlPedidosMobil@query');
        Route::get('/getpedidos', 'Api\GetPedidoMobil@query');

        //  Route::post('/creopedido' , array('uses'  => 'Api\CreoPedido@inPedido'));

        /*BI*/
        Route::get('/biclientes', 'Api\Bi\Clientes@query');
        Route::get('/biseguimiento', 'Api\Bi\SeguimientoClientes@query');
        Route::get('/datoscliente', 'Api\Bi\DatosClientes@query');
        Route::get('/vendedoras','Api\Bi\Vendedoras@productividad');
        Route::get('/pedidosPendientes','Api\Bi\Vendedoras@pedidosPendientes');

        /*Tienda Nube*/
        //Route::get('/tiendanube', 'Api\ABMTiendaNube@abmProductos');
        Route::get('/tiendanube', 'Api\ABMTiendaNubeNew@getProductos');
        Route::get('/tiendanubebug', 'Api\ABMTiendaNubeBug@getProductos');
        Route::get('/tiendanubeabm', 'Api\ABMTiendaNubeNew@abmProductos');
        Route::get('/tiendanubesincroArticulos', 'Api\ABMTiendaNubeNew@sincroArticulos');
        //Baja los articulos de Tienda Nube a la base NewArtiTN
        Route::get('/tiendanubeGetArticulos', 'Api\GetArticulosTiendaNube@getArticulos');

        /*Sincronización Articulos*/
        Route::get('/artisinc', 'Api\OutSincro@listaArticulos');
        Route::get('/getartsinc', 'Api\GetArtSincro@listaArticulosRemotos');
        Route::get('/inArtisinc', 'Api\InArtSincro@nuevo');

        /*Cambio empaquetado de un pedido*/
        Route::get('/pedidoenviado', 'Api\PedidoEnviado@enviado');

        /*Pedidos - Transporte*/
        Route::get('/transortePedido', 'Api\TransportePedido@modificarTransporte');
        /*Pedidos - Instancia*/
        Route::get('/instanciaPedidos', 'Api\InstanciaPedido@modificoInstanciaPedido');

        /*Articulos Mas Vendidos*/
        Route::get('/artimasvendidos', 'Api\GetArtiMasVendidos@listaArticulos');

        /*Encuesta Redes*/
        Route::get('/encuestaRedes', 'Api\EncuestaRedes@updateEncuesta');
        Route::get('/encuestaRedesConsulta', 'Api\EncuestaRedes@consultaEncuesta');

        /*Elimina tabla Auto Replica*/
        Route::get('/deleteautosinctable', 'Api\Automation\ReplicaTN@delete');

        /*Ordenes de compra*/
        Route::get('/ordencompras', 'Api\OC\OrdenCompras@consulta');

        /*AMB Articulos*/
        Route::get('/abmarticulos', 'Api\Articulos\GetArticulos@consulta');
        Route::get('/fotoarticulo', 'Api\Articulos\GetArticulos@foto');
        Route::get('/compraauto', 'Api\Articulos\CompraAuto@consulta');
        Route::get('/compraauto_llenarTabulador', 'Api\Articulos\CompraAuto@llenarTablaTabulador');
        Route::get('/compraauto_agregar', 'Api\Articulos\CompraAuto@agregarArticulo');
        Route::post('/compraauto/editar', 'Api\Articulos\CompraAuto@editarUmbralAlerta');
        Route::post('/compraauto/eliminar', 'Api\Articulos\CompraAuto@eliminarArticulo');

        /*Reporte Articulo Proveedor*/
        Route::get('/reporteArticuloProveedor', 'Api\Reportes\ArticuloProveedor@query');

        /*CheckOut Ordenes Articulos*/
        Route::get('/ordencheckoutInTN', 'Api\OC\OrdenArti@inTn');
        Route::get('/ordencheckoutInLocalSystem', 'Api\OC\OrdenArti@inLocalSystem');
        Route::get('/ordencheckoutInDiff', 'Api\OC\OrdenArti@inDiff');

        /*Reporte PedidosSalon */
        Route::get('/ventasSalonFacturado', 'Api\Reportes\PedidosSalon@ventasSalonFacturado');
        Route::get('/ventasSalonCantidad', 'Api\Reportes\PedidosSalon@ventasSalonCantidad');
        Route::get('/ventasPedidosFacturados', 'Api\Reportes\PedidosSalon@ventasPedidosFacturados');
        Route::get('/ventasPedidosCantidad', 'Api\Reportes\PedidosSalon@ventasPedidosCantidad');

        /*ABM Clientes*/
        Route::get('/abmclientes', 'Api\Clientes\GetClientes@consulta');

        /*Reportes Pedidos*/
        Route::get('/get_todos', 'Api\Pedidos\GetPedidos@todos');
        Route::get('/get_facturados', 'Api\Pedidos\GetPedidos@facturados');

        /*Define pago del pedido*/
        Route::get('/estadopago', 'Api\EstadoPagoPedido@modificoEstadoPago');
    });
