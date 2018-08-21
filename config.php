<?php
require_once 'podio-php/PodioAPI.php';

$esTest = true;


$client_id = 'ordendecompra';
$client_secret = 'sd1eDVaKMw4K9iYSuaA4z7BlKeul4k8SfOszGFlQWJ7Fk1bz3m3v3eRsDJuPtLZm';



if(!$esTest){
    //Produccion
    $appClientes_id = '20022559';
    $appClientes_token = '96bd3d29940c48efbc9d7b8e2f53a003';

    $appVendedor_id = '20040865';
    $appVendedor_token = 'c4ddff2f692a48f68a1c73554148d989';

    $appFactura_id = '20862053';
    $appFactura_token = 'ef458f06a59b4483bc01883e4efd714b';

    $appOC_id = '20022554';
    $appOC_token = '9d837e1ae6224897b089c729382a9463';

    $appPedido_id = '20040874';
    $appPedido_token = 'ce364c6c0be445d0a78076de2cf2dec3';

    $appProducto_id = '20022555';
    $appProducto_token = 'a12b1afd99374e669220495bc66b0928';
}else{
    //Test

    $appClientes_id = '21251029';
    $appClientes_token = '9b18f439a22d4123b640fba57c932c91';

    $appVendedor_id = '21251027';
    $appVendedor_token = 'b0a907bac3384f6c8740051ccde71ea3';

    $appFactura_id = '21251031';
    $appFactura_token = '549f393cfe944695bfc0ecf8e9c8f5d7';

    $appOC_id = '21251025';
    $appOC_token = '4d67a0a23ca54719b86a04e202fe4e2e';

    $appPedido_id = '21251030';
    $appPedido_token = '0e817919e63e4b37934539ebf6a01bdd';

    $appProducto_id = '21251026';
    $appProducto_token = '65adea58942847bf932cf4c47d8e26af';

}




