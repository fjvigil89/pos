<!-- body Modal -->
<div class="modal-dialog" style="min-width:70%">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h3 class="modal-title">Pedidos de tienda online</h3>
      
    </div>
    <div class="modal-body">
      <style type="text/css">
      </style>
      <!-- Register Items. @contains : Items table -->
      <div class="register-box register-items paper-cut">
        <div class="register-items-holder">

<p><b>Datos del cliente</b></p>
<ul>
  <li><b>Nombre y apellido:</b> <?=$orders_invoices[0]["first_name"]?> <?=$orders_invoices[0]["last_name"];?></li>
  <li><b>Telefono:</b> <?=$orders_invoices[0]["phone"]?></li>
  <li><b>Email:</b> <?=$orders_invoices[0]["email"]?></li>
  <li><b>Direccion:</b> <?=$orders_invoices[0]["address"]?></li>
</ul>

<p><b>Datos del pedido</b></p>
<ul>
  <li><b>Id pedido:</b> <?=$orders_invoices[0]["order_id"]?></li>
  <li><b>Fecha pedido :</b> <?=date('d.M.Y / H:i:s',$orders_invoices[0]["date"])?></li>
  <li><b>Tipo de pago :</b> <?=$orders_invoices[0]["payment_type"]?></li>

<?php 

    if ($orders_invoices[0]["processed"] == 0) {
        $class = 'bg-danger';
        $type = 'No Procesado';
    }
    if ($orders_invoices[0]["processed"] == 1) {
        $class = 'bg-success';
        $type = 'Procesado';
    }
    if ($orders_invoices[0]["processed"] == 2) {
        $class = 'bg-warning';
        $type = 'Rechazado';
    } 


?>

<li><b>Estado de pedido :</b> <span class="<?=$class?>"><?=$type?></span></li>


</ul>


                
  <table id="register" class="table table-hover">


    <thead>
        <tr class="register-items-header">
                <th>ID</th>
                <th>Nombre</th>
                <th style="text-align:center;">precio</th>
                <th style="text-align:center;">Cantidad</th>
                <th>Inventario</th>
        </tr>
    </thead>

    <tbody>
              <?php
              if(count($orders_invoices[0]["products"]) == 0) { ?>
              <tr class="cart_content_area">
                <td colspan='8'>
                  <div class='text-center text-warning' >
                    <h3>
                      No hay productos
                    </h3>
                  </div>
                </td>
              </tr>
              <tbody>
              <?php 
              }
              else
              {

               foreach(unserialize($orders_invoices[0]["products"]) as $product_id => $cantidad) {
               
               $data=$this->Item->get_info($product_id);

              ?>



<tr>
<td style="text-align:center;"><?=$data->item_id?></td>
<td style="text-align:center;"><?=$data->name?></td>
<td style="text-align:center;"><?=$data->unit_price?></td>
<td style="text-align:center;"><?=$cantidad?></td>
<td style="text-align:center;"><?=anchor($controller_name."/inventory/$data->item_id/", "<i class='fa fa-cubes'></i>".lang('common_inv'),array('class'=>'btn btn-xs btn-block default btn-inventory','title'=>lang($controller_name.'_count')))?></td>


</tr>        



              <?php }   ?>
              <?php }   ?>
              </tbody>
</table>


            
        </div>

      </div>
      <!-- /.Register Items -->

    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
    </div>
  </div>
</div>

<script type="text/javascript">
  
$(".plainoverlay").hide();
$(".jQuery-plainOverlay-progress").hide();



</script>


