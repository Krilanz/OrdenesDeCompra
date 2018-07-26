//Funcion para agregar un renglon a la grilla y para cargar de datos a los combos
$(document).ready(function(){
    var productos;
    var clientes;
    var vendedores;
    $.ajax({
        type: 'GET',
        url: 'PodioServices.php?GetProductos',           
        success: function(data) {
            data = JSON.parse(data);
            for (i = 0; i < data.length; i++) { 
                    productos += '<option value= "'+data[i].Id+'">' + data[i].Descripcion + '</option>';
            }
            $('.selectpicker').selectpicker();
            $('#articulo0').html(productos).selectpicker('refresh');
        },
        async: false // <- this turns it into synchronous
    });

    $.ajax({
        type: 'GET',
        url: 'PodioServices.php?GetClientes',           
        success: function(data) {
            data = JSON.parse(data);
            clientes = '<option value="">Ninguno</option>'
            for (i = 0; i < data.length; i++) { 
                    //clientes += "<option value='{" + '"Id"' + ":" + '"'+data[i].Id+'"' + "," +  '"Direccion"'  + ":" + '"'+data[i].Direccion+'"' + "}  ' >" + data[i].Descripcion + '</option>';
                     clientes += '<option data-direccion="'+data[i].Direccion+'" data-formaDePago="'+data[i].FormaDePago+'" value= "'+data[i].Id+'">' + data[i].Descripcion + '</option>';
                    
            }
            $('.selectpicker').selectpicker();
            $('#clienteId').html(clientes).selectpicker('refresh');
        },
        async: false // <- this turns it into synchronous
    });
    
     $.ajax({
        type: 'GET',
        url: 'PodioServices.php?GetVendedores',           
        success: function(data) {
            data = JSON.parse(data);
            vendedores = '<option value="">Ninguno</option>'
            for (i = 0; i < data.length; i++) { 
                
                    vendedores += '<option value= "'+data[i].Id+'">' + data[i].Descripcion + '</option>';
            }
            $('.selectpicker').selectpicker();
            $('#vendedorId').html(vendedores).selectpicker('refresh');
        },
        async: false // <- this turns it into synchronous
    });


    var i=1;
    $("#add_row").click(function(e){
       e.preventDefault();

       $('#addr'+i).html("<td>"+ (i+1) +"</td>\n\
        <td style='display:none;'><input type='number' name='id"+i+"'/></td>\n\
        <td><select class='form-control selectpicker' data-live-search='true' data-width='350px' name='articulo"+i+"' id='articulo"+i+"' placeholder='Artículo' ></select>  </td>\n\
        <td><input  name='talleColor"+i+"' type='text' class='form-control input-md' required></td>\n\
        <td><input  name='cantidad"+i+"' type='number' class='form-control input-md' required></td>\n\
        <td><input type='textbox' name='precio"+i+"' style='width:70px' class='form-control currency' value='$0.00'></td>\n\
        <td><input type='text' name='tipoEnvasado"+i+"' class='form-control' required/></td>\n\
        <td><input  name='cantidadxentrega"+i+"' type='text' class='form-control input-md' readonly></td> \n\
        <td><div class='input-group date'><input class='form-control' id='date' name='date"+i+"'style='width:140px;font-size: 12px' type='text' /></div></td>\n\
        <td><input type='number' name='cantidadEntregada"+i+"' value='0' class='form-control' readonly/></td>\n\
        <td><input type='text' name='cantidadRestante"+i+"' value='0' class='form-control' readonly/></td> ");

       $('#tab_logic').append('<tr id="addr'+(i+1)+'"></tr>');

       var date_input=$('input[id="date"]'); //our date input has the name "date"
       var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
        date_input.datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            locale: 'es'
        })
        
       $('.selectpicker').selectpicker();
       $('#articulo'+i).html(productos).selectpicker('refresh');
       
        $('.currency').blur(function()
        {
            $('.currency').formatCurrency();
        });

       i++; 
    });
    $("#delete_row").click(function(e){
            e.preventDefault();
             if(i>1){
             $("#addr"+(i-1)).html("");
             i--;
             }
    });


    var urlParams = new URLSearchParams(window.location.search);
    if(urlParams.has('id')) {
        var ordenDeCompraId = urlParams.get('id');
        
        $.ajax({
            type: 'GET',
            url: 'PodioServices.php?GetOrdenDeCompra',
            data: ({Id: ordenDeCompraId}),
            success: function(data) {
                data = JSON.parse(data);
                document.getElementById("IdDelPedido").value = data.IdDePedido;
                document.getElementById("clienteId").value = data.ClienteId;
                $('.selectpicker').selectpicker();
                $('#clienteId').html(clientes).selectpicker('val',data.ClienteId);
                document.getElementById("nroOC").value = data.NroOc;
                document.getElementById("lugarDeEntrega").value = data.LugarDeEntrega;
                document.getElementById("date").value = data.FechaDePrimeraEntrega;
                document.getElementById("turnoId").value = data.Turno[0].id;
                document.getElementById("tipoDeEntregaId").value = data.TipoDeEntrega;
                document.getElementById("monedaId").value = data.Moneda[0].id;
                document.getElementById("formaDePago").value = data.FormaDePago;
                $('.selectpicker').selectpicker();
                $('#turnoId').selectpicker('val',data.Turno[0].id);
                $('#tipoDeEntregaId').selectpicker('val',data.TipoDeEntrega);
                document.getElementById("vendedorId").value = data.Vendio;
                $('.selectpicker').selectpicker();
                $('#vendedorId').html(vendedores).selectpicker('val',data.Vendio);
                document.getElementById("preparo").value = data.Preparo;
                data.Observaciones = data.Observaciones.replace('<p>','');
                data.Observaciones = data.Observaciones.replace('</p>','');
                document.getElementById("observaciones").value = data.Observaciones;
                
                if(data.Status == "Completada")
                    swal("Advertencia","La Orden de Compra ya se encuentra Completada!","error");
                
                //Elimino el primer row
                $("#addr0").html("");
                i--;

                //e.preventDefault();
                for (z = 0; z < data.Articulos.length; z++) { 
                    
                    $('#addr'+z).html("<td>"+ (z+1) +"</td>\n\
                    <td style='display:none;'><input type='number' name='id"+z+"' value='"+data.Articulos[z].PedidoDeProductoId+"' readonly/></td>\n\
                    <td><select class='form-control selectpicker' data-live-search='true' data-width='350px' name='articulo"+z+"' id='articulo"+z+"' value='"+data.Articulos[z].ProductoId+"' placeholder='Artículo' readonly></select></td>\n\
                    <td><input name='talleColor"+z+"' value='"+data.Articulos[z].TalleColor+"' type='text' class='form-control input-md' required readonly></td>\n\
                    <td><input name='cantidad"+i+"' value='"+parseInt(data.Articulos[z].Cantidad)+"' type='number' class='form-control input-md' required readonly></td>\n\
                    <td><input name='precio"+z+"' value='$"+parseFloat(data.Articulos[z].Precio).toFixed(2)+"' type='textbox'  style='width:70px' class='form-control currency' readonly></td>\n\
                    <td><input type='text' name='tipoEnvasado"+z+"' value='"+data.Articulos[z].TipoDeEnvasado+"' class='form-control' required/></td>\n\
                    <td><input name='cantidadxentrega"+z+"' value='"+parseInt(data.Articulos[z].CantidadXEntrega)+"' type='text' oninput='validarCantidadxEntrega(this,"+z+")' class='form-control input-md'></td> \n\
                    <td><div class='input-group date'><input class='form-control' id='date' name='date"+z+"'style='width:140px;font-size: 12px' type='text' /></div></td>\n\
                    <td><input type='number' name='cantidadEntregada"+z+"' value='"+parseInt(data.Articulos[z].CantidadEntregada)+"'  class='form-control' readonly /></td>\n\
                    <td><input type='number' name='cantidadRestante"+z+"' value='"+parseInt(data.Articulos[z].CantidadRestante)+"' class='form-control' readonly/></td>");

                    $('#tab_logic').append('<tr id="addr'+(z+1)+'"></tr>');

                    var date_input=$('input[id="date"]'); //our date input has the name "date"
                    var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
                     date_input.datetimepicker({
                         format: 'YYYY-MM-DD HH:mm:ss',
                         locale: 'es'
                     })    
                     
                    $('.selectpicker').selectpicker();
                    //$('#articulo'+z).prop('disabled', true);
                    $('#articulo'+z).html(productos).selectpicker('refresh');
                    $('#articulo'+z).html(productos).selectpicker('val',data.Articulos[z].ProductoId);
                    $('.currency').blur(function()
                    {
                        $('.currency').formatCurrency();
                    });

                    i++;
                }
            },
            async: false // <- this turns it into synchronous
        });
    }
    
    
});
