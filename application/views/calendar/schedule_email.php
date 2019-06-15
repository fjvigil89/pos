
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $receipt_title; ?></title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	</head>
	<body>
	    <div id="receipt_wrapper" style="<?php echo is_rtl_lang()? 'direction:rtl':''?>">
	    
	     	<h3>
	     		<div id="sale_receipt">
				<?php echo $receipt_title; ?>
			</div>
		</h3>
	     <strong> 
	     	Title: <?php echo $title; ?>
	     </strong>
	     </br>
	     <strong> 
	     	Description: <?php echo $detail; ?>
	     </strong>
	     </br>
	     <strong> 
	     	Date start: <?php echo $start; ?>
	     </strong>
	     </br>
	     <strong> 
	     	Date end: <?php echo $end; ?>
	     </strong>
	     <?php if(isset($items)){ ?>
	     	<div class="portlet box green">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-cogs"></i>Products link </div>
                                        <div class="tools">
                                            <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                                            <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title=""> </a>
                                            <a href="javascript:;" class="reload" data-original-title="" title=""> </a>
                                            <a href="javascript:;" class="remove" data-original-title="" title=""> </a>
                                        </div>
                                    </div>
                                    <div class="portlet-body flip-scroll">
                                        <table class="table table-bordered table-striped table-condensed flip-content">
                                            <thead class="flip-content">
                                                <tr>
                                                    <th width="20%"> Imagen </th>
                                                    <th> name </th>
                                                    <th > Category </th>
                                                    <th class="numeric"> Price </th>
                                                </tr>
                                            </thead>
				    <tfoot>
				    <tr>
				    	<td colspan="3">Total</td>
					<td> <?php echo $total_price ?></td>
				    </tr>
				    </tfoot>
                                            <tbody>
				    
				    	<?php foreach ($items as $key => $value) { ?>
						<tr>
							<td> <img src="<?php echo site_url()."/schedules/getImagen/$value->image_id" ; ?>" alt="<?php echo $value->name; ?>" width="50" height="50">  </td>
							<td> <?php echo $value->name; ?> </td>
							<td > <?php echo $value->category; ?> </td>
							<td class="numeric"> <?php echo $value->unit_price;?> </td>
							
						</tr>                                                
							
					<?php } ?>

					
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
	     <?php } ?>   

	<footer>
	
	     <div id="company_name">
					<?php echo $this->config->item('company'); ?>
				</div>
				<div id="company_address">
					<?php echo nl2br($this->Location->get_info_for_key('address')); ?>
				</div>
				<div id="company_phone">
					<?php echo $this->Location->get_info_for_key('phone'); ?>
				</div>
				
			</div>
			
			
  	   </div>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	</footer>   

	</body>
</html>
