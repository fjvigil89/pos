<style>
.modal-header-success {
    color:#fff;
    padding:9px 15px;
    border-bottom:1px solid #eee;
    background-color: #26a69a;
}
</style>
<?php echo form_open_multipart('',array('id'=>'customer_form','class'=>'form-horizontal')); 	?>
<div class="form-body">
    <h4><?php echo lang('common_fields_required_message'); ?></h4>
    <div class="form-group">
        <?php $required =  "requireds";?>
            <?= form_input(array(
						"type"=>"hidden",
						'name'=>'person_id',
						'id'=>'person_id',
						'value'=>"-1")
					);?>
        <label
            class="<?php echo $required ?> control-label col-md-3"><?php echo '<a class="help_config_required  tooltips " data-placement="left" title="'.lang("common_first_name_help").'">'.lang('common_first_name').'</a>'.':'?>
           
        </label>
        <div class="col-md-8">
            <?php echo form_input(array(
						'class'=>'form-control form-inps',
						'name'=>'first_name',
                        'id'=>'first_name',
                        "required"=>"",
						'value'=>"")
					);?>
        </div>
    </div>

    <div class="form-group">
        <?php echo form_label(lang('common_last_name').':', 'last_name',array('class'=>'col-md-3 requireds control-label')); ?>
        <div class="col-md-8">
            <?php echo form_input(array(
					'class'=>'form-control form-inps',
					'name'=>'last_name',
                    'id'=>'last_name',
                    "required"=>"",
					'value'=>"")
				);?>
        </div>
    </div>
    <div class="form-group">
        <?php echo form_label("Identificación/".lang('customers_account_number').':', 'account_number',array('class'=>'col-md-3 requireds control-label ')); ?>
        <div class="col-md-8">
            <?php echo form_input(array(
									'name'=>'account_number',
                                    'id'=>'account_number',
                                    "required"=>"",
									'class'=>'company_names form-control',
									'value'=>""));
								?>
        </div>
    </div>

    <div class="form-group">
        <?php echo form_label(lang('common_email').':', 'email',array('class'=>'col-md-3 control-label')); ?>
        <div class="col-md-8">
            <?php echo form_input(array(
					'class'=>'form-control form-inps',
					'name'=>'email',
					'id'=>'email',
					'type' => 'email',
                    'value'=>"")
					);?>
        </div>
    </div>

    <div class="form-group">
        <?php echo form_label(lang('common_phone_number').':', 'phone_number',array('class'=>'col-md-3 control-label')); ?>
        <div class="col-md-8">
            <?php echo form_input(array(
					'class'=>'form-control form-inps',
					'name'=>'phone_number',
					'id'=>'phone_number',
					'value'=>""));?>
        </div>
    </div>

    <div class="form-group">
        <?php echo form_label(lang('common_address_1').':', 'address_1',array('class'=>'col-md-3 control-label')); ?>
        <div class="col-md-8">
            <?php echo form_input(array(
					'class'=>'form-control form-inps',
					'name'=>'address_1',
					'id'=>'address_1',
					'value'=>""));?>
        </div>
    </div>

    <div class="form-group">
        <?php echo form_label(lang('common_city').':', 'city',array('class'=>'col-md-3 control-label')); ?>
        <div class="col-md-8">
            <?php echo form_input(array(
					'class'=>'form-control form-inps',
					'name'=>'city',
					'id'=>'city',
					'value'=>""));?>
        </div>
    </div>

    <div class="form-group">
        <?php echo form_label(lang('common_state').':', 'state',array('class'=>'col-md-3 control-label ')); ?>
        <div class="col-sm-8">
            <?php echo form_input(array(
					'class'=>'form-control form-inps',
					'name'=>'state',
					'id'=>'state',
					'value'=>""));?>
        </div>
    </div>

    <div class="form-group">
        <?php echo form_label(lang('common_country').':', 'country',array('class'=>'col-md-3 control-label')); ?>
        <div class="col-md-8">
            <?php echo form_input(array(
					'class'=>'form-control form-inps',
					'name'=>'country',
					'id'=>'country',
					'value'=>""));?>
        </div>
    </div>

    <?php if (!empty($tiers)) { ?>
    <div class="form-group">
        <?php echo form_label(lang('customers_tier_type').':', 'tier_type',array('class'=>'col-md-3 control-label')); ?>
        <div class="col-md-8">
            <?php echo form_dropdown('tier_id', $tiers, 0, 'id="tier_id" class="bs-select form-control"');?>
        </div>
    </div>
    <?php } ?>
    <div class="col-md-offset-3 col-md-9">
           <p id="error-customer"><p>
        </div>
</div>

<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            <?php
                echo form_button(array(
                'name'=>'submitf',
                'id'=>'submitf',
                'type' => 'submit',
                'class'=>'btn btn-primary'),
                lang('common_submit'));
            
                echo form_button(array(
                'name' => 'cancel',
                'id' => 'cancel',
                'class' => 'btn  btn-danger',
                'value' => 'true',
                'content' => lang('common_cancel')
                ));
        
        ?>
        </div>
    </div>
</div>


<?php echo form_close(); ?>

<script>

    $("#cancel").click(function(){
        $("#close-modal").click();
    });

    $("#customer_form").submit(function(e) {
        e.preventDefault();
        $("#error-customer").html("");
        (async function(){      
           var person_id = $("#person_id").val();
            if(await es_valido_datos())
            {            
                var data_customer = $("#customer_form").serializeArray();
                data_customer = get_obj(data_customer);
                save_customer(person_id ,data_customer);
            }
        })();       
    });

    function get_obj(datos)
    {
        var new_data = {};

        for (const key in datos)
        {
            var dato = datos[key];
            new_data[dato.name] = dato.value;
        }
        return new_data;
    }

    async function  es_valido_datos() 
    {
        var ok = true;
        
        $("#first_name").val($("#first_name").val().trim());
        $("#last_name").val($("#last_name").val().trim());

        if($("#first_name").val()=="")
        {
            $("#error-customer").html("El nombre es requerido");
            ok = false;
        }

        else if($("#last_name").val()=="")
        {
            $("#error-customer").html("los apellidos son requeridos");
            ok = false;
        }
        else if($("#account_number").val()=="")
        {
            $("#error-customer").html("La identificación  es requerida");
            ok = false;
        }
        else if($("#account_number").val().trim().length < 1){
            $("#error-customer").html("La identificación  debe tener minimo 1 caracter");
            ok = false;
        }
        
        else if($("#email").val() != "" && await objCustomer.email_exists($("#email").val()) )
        {
            ok = confirm("El correo electrónico ya se encuentra registrado. Desea registrar el cliente de todas formas?");
        }
        $("#account_number").val($("#account_number").val().trim());
        
        return ok;
        
    }
    async function save_customer(person_id = -1, data)
    {
        try{
            var result = await objCustomer.save(person_id,data);
            if(result)
            {
                if(person_id >0)
                    carga_body();// carga tabla de clientes                    
                    
                $("#close-modal").click();
            }
                
            else
                alert("Error al guardar los datos");

        }catch(e)
        {
            $("#error-customer").html(e);
        }
    }

    async function get_customer(person_id)
    {       
        if(person_id > 0)
        {
            var info_c = await objCustomer.get_info(person_id);

            $("#person_id").val(person_id);
            $("#first_name").val(info_c.first_name);
            $("#last_name").val(info_c.last_name);
            $("#account_number").val(info_c.account_number);
            $("#email").val(info_c.email);
            $("#phone_number").val(info_c.phone_number);
            $("#address_1").val(info_c.address_1);
            $("#city").val(info_c.city);
            $("#state").val(info_c.state);
            $("#country").val(info_c.country);
            $('#tier_id option[value="'+info_c.tier_id+'"]').attr("selected",true);
        }
        else                                    
            $("#person_id").val("-1");            
       
    }
</script>