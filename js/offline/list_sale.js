async function listar_sales(location=false){
  if(location==false){
    location= await objEmployee.get_logged_in_employee_current_location_id();
  }
  
  let ventas = await  objSale.get_sales_all(Number(location));
  let has_permission= await objEmployee.has_module_action_permission('offline', 'delete_sale_offline', (await objEmployee.get_logged_in_employee_info()).person_id);

  //console.log(has_permission);
  var content_table_list_sales="";
  for(let  i in ventas ){
      var numero_articulos=0;
      let synchronized="";
      let invoice_number=0;
      let delete_sale_no_synchronized="";
      let venta = ventas[i];
      let employee = await objEmployee.get_info(venta.employee_id);
      let customer= await objCustomer.get_info(venta.customer_id);
      let register= await objRegister.get_info(venta.register_id);
     //console.log(venta);

    //--- verificar si es factura o boleto ---
    if (venta.is_invoice) {
      invoice_number=venta.invoice_number;
    }else{
      invoice_number=venta.ticket_number;
    }

    //--- consulta Numero de articulos venta ---
      for(let j in venta.sales_items){
        let venta_sales_item = venta.sales_items[j];
        numero_articulos+=venta_sales_item.quantity_purchased;
      }

    //--- consulta numero de articulos Kit ---  
      let numero_articulos_kit=0;
      for(let j in venta.sales_item_kits){
        let sales_item_kit = venta.sales_item_kits[j];
        
        numero_articulos_kit+=sales_item_kit.quantity_purchased;
      }
      numero_articulos+=numero_articulos_kit;
      //--- verificacion estados sincronizacion ventas
      if (venta.synchronized==1){synchronized="<span class='label label-success'>Sincronizada</span>"}
      else if(venta.synchronized==0){synchronized="<span class='label label-primary'>Pendiente</span>"
      if(has_permission){
        delete_sale_no_synchronized="<button class='btn btn-danger  btn btn-xs  ' title='Eliminar Venta' onclick='delete_sales("+venta.sale_id+")'><i class='fa fa-trash-o'></i>Eliminar</button>"; 
      }
      }

      //--- Listar En tabla ventas Offline
      content_table_list_sales=content_table_list_sales
      +"<tr><td align='center'>"+ venta.sale_id +"</td>"
      +"<td align='center'>"+ venta.sale_time +"</td>"
      +"<td align='center'>"+ invoice_number +"</td>"
      +"<td align='center'>"+ employee.first_name+" "+ employee.last_name +"</td>"
      +"<td align='center'>"+ customer.first_name +"</td>"
      +"<td align='center'>"+ numero_articulos +"</td>"
      + "<td align='center'>"+ register.name +" </td>"
      +"<td align='center'>"+ await to_currency(venta.dato_imprimir.total) +"</td>"
      + "<td align='center'>"+ synchronized +" </td>"
      +"<td align='center'>"+delete_sale_no_synchronized
      +"<button class='btn btn-warning  btn btn-xs  ' title='Imprimir Venta' onclick='print_sales("+venta.sale_id+")'><i class='fa fa-print'></i>Imprimir</button></td>"
      +"</tr>";

  }
  $("#tableBody-list_sales").html(content_table_list_sales);

  $(document).ready(function() {
    $("#table-list_sales").DataTable({
            "language": {
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },

                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            },
            "order": [[ 0, "desc" ]]
    });
});

}
//--- eliminar Ventas ---
function delete_sales(sale_id){
  (async function(){
    if(await objEmployee.has_module_action_permission('offline', 'delete_sale_offline', (await objEmployee.get_logged_in_employee_info()).person_id)){
      if(confirm("¿Está seguro que quiere eliminar la venta "+sale_id+" ? \nLas ventas eliminadas no se  sincronizan")){
        await objSale.eliminar_sale(sale_id);
        window.location.reload(false);
      }
    }
  })();  
}
//--- imprimir Ventas ---
async function print_sales(id){
  localStorage.setItem("data_imprimir", id);
  window.location ="index.php/sales/imprimir";
  }