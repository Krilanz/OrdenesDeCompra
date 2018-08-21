<?php
require_once 'config.php';

if(isset($_GET['GetOrdenDeCompra'])){
    require_once 'podio-php/PodioAPI.php';

    Podio::setup($client_id, $client_secret);
    Podio::authenticate_with_app($appOC_id, $appOC_token);
    
    
    $items = array();
    $ordenDeCompra =   PodioItem::get_by_app_item_id( $appOC_id, intval($_GET['Id']));
    
 
    $items['IdDePedido'] = intval($ordenDeCompra->fields["id-pedido-3"]-> values); 
    $items['ClienteId'] = $ordenDeCompra->fields["cliente"]-> values[0] -> item_id; 
    $items['NroOc'] = $ordenDeCompra->fields["numero-de-oc"] != null ? $ordenDeCompra->fields["numero-de-oc"] -> values : "";  
    $items['LugarDeEntrega'] = $ordenDeCompra->fields["lugar-de-entrega"] != null ? $ordenDeCompra->fields["lugar-de-entrega"] -> values['value'] : "";  
    $items['FechaDePrimeraEntrega'] = $ordenDeCompra->fields["fecha-de-primera-entrega"]-> values["start"] == null ? "" : $ordenDeCompra->fields["fecha-de-primera-entrega"]-> values["start"] -> format('Y-m-d H:i:s');
    $items['Turno'] = $ordenDeCompra->fields["turno"] != null ? $ordenDeCompra->fields["turno"] -> values : "";  
    $items['TipoDeEntrega'] = $ordenDeCompra->fields["tipo-de-entrega"] != null ? $ordenDeCompra->fields["tipo-de-entrega"] -> values : "";  
    $items['Vendio'] = $ordenDeCompra->fields["vendio-2"] != null ? $ordenDeCompra->fields["vendio-2"] -> values[0] -> item_id : "";  
    $items['Preparo'] = $ordenDeCompra->fields["preparo"] != null ? $ordenDeCompra->fields["preparo"] -> values : "";  
    $items['Observaciones'] = $ordenDeCompra->fields["observaciones"] != null ? $ordenDeCompra->fields["observaciones"] -> values : "";  
    $items['FormaDePago'] = $ordenDeCompra->fields["forma-de-pago"] != null ? $ordenDeCompra->fields["forma-de-pago"] -> values : "";  
    $items['Moneda'] = $ordenDeCompra->fields["moneda"] != null ? $ordenDeCompra->fields["moneda"] -> values : "";  
    $items['Status'] = $ordenDeCompra->fields["oc-status"] != null ? $ordenDeCompra->fields["oc-status"]-> values[0]['text'] : "";  
    
    //App Pedidos de Productos
 
    Podio::setup($client_id, $client_secret);
    Podio::authenticate_with_app($appPedido_id, $appPedido_token);
    
    //$detalles = PodioItem::get_field_value( $ordenDeCompra -> item_id) ;
      $detalles = PodioItem::filter($appPedido_id, [
        'filters' => [
            'oc' => $ordenDeCompra-> item_id
        ],
        'limit' => 500
    ]);
    $items['Articulos'] = array();
    foreach ($detalles as $pedidoDeProducto) {
        
        $articulo = array();
        //$pedidoDeProducto = PodioItem::get_by_app_item_id( $appPedido_id, $detalle['value']['app_item_id']);
        $articulo['PedidoDeProductoId'] = $pedidoDeProducto -> item_id;
        $articulo['ProductoId'] = $pedidoDeProducto->fields["producto-3"]-> values[0] -> item_id; 
        $articulo['TalleColor'] = $pedidoDeProducto->fields["talle-2"] != null ? $pedidoDeProducto->fields["talle-2"] -> values : "";   
        $articulo['Cantidad'] = $pedidoDeProducto->fields["cantidad"] != null ? $pedidoDeProducto->fields["cantidad"] -> values : "";   
        $articulo['Precio'] = $pedidoDeProducto->fields["precio-unitario"] != null ? $pedidoDeProducto->fields["precio-unitario"] -> values['value'] : "";   
        $articulo['TipoDeEnvasado'] = $pedidoDeProducto->fields["tipo-de-envasado"] != null ? $pedidoDeProducto->fields["tipo-de-envasado"] -> values : "";   
        $articulo['CantidadRestante'] = $pedidoDeProducto->fields["cantidad-restante"] != null ? $pedidoDeProducto->fields["cantidad-restante"] -> values : "";   
        $articulo['CantidadXEntrega'] = 0;// $pedidoDeProducto->fields["cantidad-solicitada-x-entrega"] != null ? $pedidoDeProducto->fields["cantidad-solicitada-x-entrega"] -> values : "";   
        $articulo['CantidadEntregada'] = $pedidoDeProducto->fields["cantidad-entregada-2"] != null ? $pedidoDeProducto->fields["cantidad-entregada-2"] -> values : "";   
        //$articulo['NroFactura'] = $pedidoDeProducto->fields["na-de-factura"] != null ? $pedidoDeProducto->fields["na-de-factura"] -> values : "";   
        $items['Articulos'][] = $articulo;
    }
    
    echo  json_encode($items, JSON_UNESCAPED_UNICODE);	
}
if(isset($_GET['GetProductos']))
{
    //do something

    require_once 'podio-php/PodioAPI.php';


    Podio::setup($client_id, $client_secret);
    Podio::authenticate_with_app($appProducto_id, $appProducto_token);
    $collection = PodioItem::filter($appProducto_id, array('limit' => 500));
    $items = array();
    // Output the title of each item
    foreach ($collection as $item) {
        $attributes = array();
        $attributes['Id'] =  $item->item_id ;        
        $attributes['Descripcion'] =  $item->fields["codigo"]  == null ? "" . $item->title  : $item->fields["codigo"]-> values . " - " . $item->title ;
        $items[] = $attributes;
    }
    
    echo json_encode($items, JSON_UNESCAPED_UNICODE);		
}


if(isset($_GET['GetClientes']))
{
    //do something

    require_once 'podio-php/PodioAPI.php';
    
    Podio::setup($client_id, $client_secret);
    Podio::authenticate_with_app($appClientes_id, $appClientes_token);
    $collection = PodioItem::filter($appClientes_id, array('limit' => 500));
    $items = array();
    // Output the title of each item
    foreach ($collection as $item) {
        $attributes = array();
        $attributes['Id'] =  $item->item_id ;
        $attributes['Descripcion'] =  $item->title ;
        $attributes['Direccion'] = $item->fields["donde-se-entrega"]  == null ? "" : $item->fields["donde-se-entrega"]-> values["value"] ;
        $attributes['FormaDePago'] = $item->fields["forma-de-pago"]  == null ? "" : $item->fields["forma-de-pago"]-> values ;
        $items[] = $attributes;
    }
    
    echo json_encode($items, JSON_UNESCAPED_UNICODE);		
}
  
if(isset($_GET['GetVendedores']))
{
    //do something

    require_once 'podio-php/PodioAPI.php';
    
    Podio::setup($client_id, $client_secret);
    Podio::authenticate_with_app($appVendedor_id, $appVendedor_token);
    $collection = PodioItem::filter($appVendedor_id, array('limit' => 500));
    $items = array();
    // Output the title of each item
    foreach ($collection as $item) {
        $attributes = array();
        $attributes['Id'] =  $item->item_id ;
        $attributes['Descripcion'] =  $item->fields["nombre"] == null ? "" : $item->fields["nombre"]-> values ;
        $items[] = $attributes;
    }
    
    echo json_encode($items, JSON_UNESCAPED_UNICODE);		
}

    
if(isset($_POST['submit']))
{
    require_once 'podio-php/PodioAPI.php';

    Podio::setup($client_id, $client_secret);
    Podio::authenticate_with_app($appOC_id, $appOC_token);

    if($_POST['IdDelPedido'] > 0){
        //Modifico Orden de Compra Cabecera
        $ordenVieja = PodioItem::get_by_app_item_id( $appOC_id, intval($_POST['IdDelPedido']));
        $ocNueva = PodioItem::update($ordenVieja-> item_id, array('fields' => array(
            "numero-de-oc" => $_POST['nroOC'] == "" ? null : $_POST['nroOC'],
            "cliente"=> intval($_POST['clienteId']),
            "lugar-de-entrega" => $_POST['lugarDeEntrega'] == "" ? null : $_POST['lugarDeEntrega'],
            "fecha-de-primera-entrega" => (string)$_POST['date'] == "" ? null : array('start' => (string)$_POST['date'] , "end" => null), 
            //"fecha" => array('start' => date("Y-m-d H:i:s")  , "end" => null) ,
            //"fecha-de-entrega" =>   date("Y-m-d H:i:s") ,
            "turno" => $_POST['turnoId'] == "" ? null : intval($_POST['turnoId']), 
            "tipo-de-entrega" => $_POST['tipoDeEntregaId'] == "" ? null : $_POST['tipoDeEntregaId'], 
            "observaciones" => $_POST['observaciones'] == "" ? null : $_POST['observaciones'] ,
            "vendio-2" => $_POST['vendedorId'] == "" ? null : intval($_POST['vendedorId']),  
            "preparo" => $_POST['preparo'] == "" ? null : $_POST['preparo'] ,
            "fecha-primera-entrega-via-form" => (string)$_POST['date'] == "" ? null : date("d", strtotime($_POST['date'])),
            "mes-primera-entrega-via-form" => (string)$_POST['date'] == "" ? null : date("m", strtotime($_POST['date'])),
            "ano-primera-entrega-via-form" => (string)$_POST['date'] == "" ? null : date("y", strtotime($_POST['date'])),    
            "forma-de-pago" => $_POST['formaDePago'] == "" ? null : $_POST['formaDePago'] ,
            "moneda" => $_POST['monedaId'] == "" ? null : intval($_POST['monedaId']), 
            "link-3" => 'http://dimex.innen.com.ar/OrdenesDeCompra/Index.php?id='. $ordenVieja-> app_item_id ,
        )));
        $ocNueva = PodioItem::get_by_app_item_id( $appOC_id, $ordenVieja-> app_item_id);
    }else{
        //Creo Orden de Compra Cabecera
        $ocNueva = PodioItem::create($appOC_id, array('fields' => array(
            "numero-de-oc" => $_POST['nroOC'] == "" ? null : $_POST['nroOC'],
            "cliente"=> intval($_POST['clienteId']),
            "lugar-de-entrega" => $_POST['lugarDeEntrega'] == "" ? null : $_POST['lugarDeEntrega'],
            "fecha-de-primera-entrega" => (string)$_POST['date'] == "" ? null : array('start' => (string)$_POST['date'] , "end" => null), 
            "fecha" => array('start' => date("Y-m-d H:i:s")  , "end" => null) ,
            "fecha-de-entrega" =>   date("Y-m-d H:i:s") ,
            "turno" => $_POST['turnoId'] == "" ? null : intval($_POST['turnoId']), 
            "tipo-de-entrega" => $_POST['tipoDeEntregaId'] == "" ? null : $_POST['tipoDeEntregaId'], 
            "observaciones" => $_POST['observaciones'] == "" ? null : $_POST['observaciones'] ,
            "vendio-2" => $_POST['vendedorId'] == "" ? null : intval($_POST['vendedorId']),  
            "preparo" => $_POST['preparo'] == "" ? null : $_POST['preparo'] ,
            "fecha-primera-entrega-via-form" => (string)$_POST['date'] == "" ? null : date("d", strtotime($_POST['date'])),
            "mes-primera-entrega-via-form" => (string)$_POST['date'] == "" ? null : date("m", strtotime($_POST['date'])),
            "ano-primera-entrega-via-form" => (string)$_POST['date'] == "" ? null : date("y", strtotime($_POST['date'])),
            "forma-de-pago" => $_POST['formaDePago'] == "" ? null : $_POST['formaDePago'] ,
            "moneda" => $_POST['monedaId'] == "" ? null : intval($_POST['monedaId']), 
            "oc-status" => 1
        )));
        
        PodioItem::update($ocNueva-> item_id, array('fields' => array(            
            "id-pedido-3" => $ocNueva-> app_item_id,
            "link-3" => 'http://dimex.innen.com.ar/OrdenesDeCompra/Index.php?id='. $ocNueva-> app_item_id ,
        )));
    }
    
    
    $ocId = $ocNueva-> item_id;

    Podio::setup($client_id, $client_secret);
   
    $index = 0;
    $pedidosDeProductos;
    
    foreach ($_POST as $key => $value ) {
        if (strpos($key, 'articulo') !== false) {
            if($_POST['id' . strval($index)] > 0){
                //Modifico Pedidos de Productos
                
                /*if($_POST['cantidadxentrega' . strval($index)]> $_POST['cantidadRestante' . strval($index)]){
                    echo '<script language="javascript">';
                    echo 'swal("Error","La Cantidad a Entregar no puede superar a la Cantidad Restante","error")';
                    echo '</script>';
                    return;
                }*/
                
                Podio::authenticate_with_app($appPedido_id, $appPedido_token);
                
                $pedidoDeProducto = PodioItem::update(intval($_POST['id' . strval($index)]), array('fields' => array(
                    "id-de-pedido-2" => intval($ocNueva-> app_item_id) ,
                    "producto-3" => intval($_POST['articulo' . strval($index)]),
                    "talle-2"=> $_POST['talleColor' . strval($index)],
                    "cantidad" => intval($_POST['cantidad' . strval($index)]),
                    "precio-unitario" => (string)$_POST['precio' . strval($index)] == "" ? null : floatval(str_replace("$","",$_POST['precio' . strval($index)])),
                    "tipo-de-envasado" => $_POST['tipoEnvasado'. strval($index)] == "" ? null : $_POST['tipoEnvasado' . strval($index)],
                    //"cantidad-restante" => $_POST['cantidadRestante'. strval($index)] == "" ? null : $_POST['cantidadRestante' . strval($index)],
                    "cantidad-solicitada-x-entrega" => (string)$_POST['cantidadxentrega'. strval($index)] == "" ? 0 : intval($_POST['cantidadxentrega' . strval($index)]),
                    "cantidad-entregada-2" => (string)$_POST['cantidadEntregada'. strval($index)] == "" ? 0 : intval($_POST['cantidadEntregada' . strval($index)]) + intval($_POST['cantidadxentrega' . strval($index)]),
                    //"na-de-factura" => $_POST['nroFactura'. strval($index)] == "" ? null : $_POST['nroFactura' . strval($index)],
                    "oc" =>  intval($ocNueva-> item_id) ,
                )));
                $pedidoDeProductoId = intval($_POST['id' . strval($index)]);
                $pedidosDeProductos[] = $pedidoDeProductoId;

            }else{
                Podio::authenticate_with_app($appPedido_id, $appPedido_token);
                
                $pedidoDeProducto = PodioItem::create($appPedido_id, array('fields' => array(
                    "id-de-pedido-2" => intval($ocNueva-> app_item_id) ,
                    "producto-3" => intval($_POST['articulo' . strval($index)]),
                    "talle-2"=> $_POST['talleColor' . strval($index)],
                    "cantidad" => intval($_POST['cantidad' . strval($index)]),
                    "precio-unitario" => (string)$_POST['precio' . strval($index)] == "" ? null : floatval(str_replace("$","",$_POST['precio' . strval($index)])),
                    "tipo-de-envasado" => $_POST['tipoEnvasado'. strval($index)] == "" ? null : $_POST['tipoEnvasado' . strval($index)],
                    //"cantidad-restante" => $_POST['cantidadRestante'. strval($index)] == "" ? null : $_POST['cantidadRestante' . strval($index)],
                    "cantidad-solicitada-x-entrega" => (string)$_POST['cantidadxentrega'. strval($index)] == "" ? 0 : intval($_POST['cantidadxentrega' . strval($index)]),
                    "cantidad-entregada-2" => $_POST['cantidadxentrega'. strval($index)] == "" ? 0 : intval($_POST['cantidadxentrega' . strval($index)]),
                    //"na-de-factura" => $_POST['nroFactura'. strval($index)] == "" ? null : $_POST['nroFactura' . strval($index)],
                    "oc" =>  intval($ocNueva-> item_id) ,
                )));
                $pedidoDeProductoId = $pedidoDeProducto-> item_id;
                $pedidosDeProductos[] = $pedidoDeProductoId;
                
                 
            }
            
            if(intval($_POST['cantidadxentrega'. strval($index)]) > 0){
                
                Podio::setup($client_id, $client_secret);
                Podio::authenticate_with_app($appFactura_id, $appFactura_token);
                //Creo la factura
                $fcNueva = PodioItem::create($appFactura_id, array('fields' => array(
                    //"numero" => $_POST['nroFactura'. strval($index)] == "" ? null : $_POST['nroFactura' . strval($index)],
                    "pedido-de-producto" =>  $pedidoDeProductoId,
                    "cliente" =>  intval($_POST['clienteId']),
                    "oc" =>  intval($ocNueva-> item_id) ,
                    "relacion" =>  $_POST['vendedorId'] == "" ? null : intval($_POST['vendedorId']),
                    "fecha-de-entrega-2" => (string)$_POST['date'. strval($index)] == "" ? null : array('start' => (string)$_POST['date'. strval($index)] , "end" => null), 
                    "cantidad-entregada" => intval($_POST['cantidadxentrega' . strval($index)])
                )));
                
                //App Historial de Entrega
                /*$appHistorial_id = '21030234';
                $appHistorial_token = '77789cf027e643739f01cdbf2c099b07';
                Podio::setup($client_id, $client_secret);
                Podio::authenticate_with_app($appHistorial_id, $appHistorial_token);
                //Creo el Historial
                PodioItem::create($appHistorial_id, array('fields' => array(
                    "orden-de-compra" => intval($ocId),
                    "entregas" =>  $fcNueva -> item_id,
                )));*/
            }
            
            
            $index ++;
        }
    }
    
    Podio::authenticate_with_app($appOC_id, $appOC_token);
    
    $ocNueva = PodioItem::update($ocNueva-> item_id, array('fields' => array(
                    "productos-solicitados" => $pedidosDeProductos,
                )));
    
    
    
     $_SESSION['NuevaFactura'] = 1;
     if($esTest){
        header("Location: http://dimex.innen.com.ar/OCVendedoresTest/ordenesDeCompra.php?NuevaFactura=1"); 
     }else{
        header("Location: http://dimex.innen.com.ar/OCVendedores/ordenesDeCompra.php?NuevaFactura=1");
     }
     
} 

?>