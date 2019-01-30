<div class="col-sm-12">
<div class="col-sm-3">
<div class="form-group">
    <?php
    $required = ($controller_name == "suppliers") ? "" : "requireds";
    ?>
    <label class="<?php echo $required ?> control-label"><?php echo '<a class="help_config_required  tooltips " data-placement="left" title="'.lang("common_first_name_help").'">'.lang('common_first_name').'</a>'.':'?>
        <!--<span class="required">* </span>-->
    </label>

        <?php echo form_input(array(
                'class'=>'form-control form-inps',
                'name'=>'first_name',
                'id'=>'first_name',
                'value'=>$person_info->first_name)
        );?>

</div>
</div>
<div class="col-sm-3">
<div class="form-group">
    <?php echo form_label(lang('common_last_name').':', 'last_name',array('class'=>'control-label')); ?>

        <?php echo form_input(array(
                'class'=>'form-control form-inps',
                'name'=>'last_name',
                'id'=>'last_name',
                'value'=>$person_info->last_name)
        );?>

</div>
</div>

<div class="col-sm-3">
<div class="form-group">
    <?php echo form_label(lang('common_email').':', 'last_name',array('class'=>'control-label')); ?>
    <?php // echo form_label('<a class="help_config_required  tooltips " data-placement="left" title="'.lang("common_email_help").'">'.lang('common_email').'</a>'.':', 'email',array('class'=>'control-label '.($controller_name == 'employees' ? 'requireds' : 'not_required'))); ?>

        <?php echo form_input(array(
                'class'=>'form-control form-inps',
                'name'=>'email',
                'id'=>'email',
                'type' => 'email',
                'value'=>$person_info->email)
        );?>

</div>
</div>
<div class="col-sm-3">
<div class="form-group">
    <?php echo form_label(lang('common_phone_number').':', 'phone_number',array('class'=>'col-md-3 control-label')); ?>

        <?php echo form_input(array(
            'class'=>'form-control form-inps',
            'name'=>'phone_number',
            'id'=>'phone_number',
            'value'=>$person_info->phone_number));?>

</div>
</div>
</div>
<div class="col-sm-12">
<div class="col-sm-4">
<div class="form-group">
    <?php echo form_label(lang('common_choose_avatar').':', 'avatar',array('class'=>'control-label')); ?>

        <?php echo form_upload(array(
            'name'=>'image_id',
            'id'=>'image_id',
            'class' => 'file form-control',
            'multiple' => "false",
            'data-show-upload' => 'false',
            'data-show-remove' => 'false',
            'value'=>$person_info->image_id));
        ?>

</div>
</div>

<div class="col-sm-4">
<?php if($person_info->image_id) {  ?>
    <div class="form-group">
        <?php echo form_label(lang('items_del_image').':', 'del_image',array('class'=>'control-label')); ?>

            <div class="md-checkbox-inline">
                <div class="md-checkbox">
                    <?php echo form_checkbox(array(
                        'name'=>'del_image',
                        'id'=>'del_image',
                        'class'=>'form-control delete-checkbox md-check',
                        'value'=>1
                    ));?>
                    <label for="del_image">
                        <span></span>
                        <span class="check"></span>
                        <span class="box"></span>
                    </label>
                </div>
            </div>

    </div>
<?php }  ?>
</div>
<div class="col-sm-8">
    <div class="form-group">	 
        <?php  //echo form_label('<a class="help_config_required  tooltips " data-placement="left" title="'.lang("common_phone_number_help").'"> Identificación/"'.lang('customers_account_number').'</a>'.':', 'account_number',array('class'=>'control-label '.($controller_name == 'employees' ? 'requireds' : 'not_required'))); ?>
        <label class="<?php echo $required ?> control-label"><?php echo '<a class="help_config_required  tooltips " data-placement="left" title="'.lang("common_first_name_help").'">Identificación/'.lang('customers_account_number').'</a>'.':'?>
            <input type="text" value="<?php echo $person_info->account_number; ?>" id="account_number" name="account_number" class="form-control" required>
    <?php // echo form_input(array(
    //'name'=>'account_number',
    //'id'=>'account_number',
    //'class'=>'company_names form-control', 
    //'value'=>$person_info->account_number));
    ?>

    </div>
</div>
<div class="col-sm-8">
<div class="form-group">
    <?php echo form_label(lang('common_address_1').':', 'address_1',array('class'=>'control-label')); ?>

        <?php echo form_input(array(
            'class'=>'form-control form-inps',
            'name'=>'address_1',
            'id'=>'address_1',
            'value'=>$person_info->address_1));?>

</div>
</div>
<div class="col-sm-8">
<div class="form-group">
    <?php echo form_label(lang('common_address_2').':', 'address_2',array('class'=>'control-label')); ?>

        <?php echo form_input(array(
            'class'=>'form-control form-inps',
            'name'=>'address_2',
            'id'=>'address_2',
            'value'=>$person_info->address_2));?>

</div>
</div>
<div class="col-sm-8">
<div class="form-group">
    <?php echo form_label(lang('common_city').':', 'city',array('class'=>'control-label')); ?>

        <?php echo form_input(array(
            'class'=>'form-control form-inps',
            'name'=>'city',
            'id'=>'city',
            'value'=>$person_info->city));?>

</div>
</div>
<div class="col-sm-12">
<div class="form-group">
    <?php echo form_label(lang('common_state').':', 'state',array('class'=>'control-label ')); ?>

        <?php echo form_input(array(
            'class'=>'form-control form-inps',
            'name'=>'state',
            'id'=>'state',
            'value'=>$person_info->state));?>

</div>
</div>
</div>
<div class="col-sm-12">
<div class="col-sm-4">
    <div class="form-group">
        <?php echo form_label(lang('common_comments').':', 'comments',array('class'=>'control-label')); ?>

        <?php echo form_textarea(array(
                'name'=>'comments',
                'id'=>'comments',
                'class' => 'form-control form-inps',
                'value'=>$person_info->comments,
                'rows'=>'5',
                'cols'=>'17')
        );?>

    </div>
</div>
<div class="col-sm-4">
<div class="form-group">
    <?php echo form_label(lang('common_zip').':', 'zip',array('class'=>'control-label')); ?>

        <?php echo form_input(array(
            'class'=>'form-control form-inps',
            'name'=>'zip',
            'id'=>'zip',
            'value'=>$person_info->zip));?>

</div>
</div>
<div class="col-sm-4">
<div class="form-group">
    <?php echo form_label(lang('common_country').':', 'country',array('class'=>'control-label')); ?>

        <?php echo form_input(array(
            'class'=>'form-control form-inps',
            'name'=>'country',
            'id'=>'country',
            'value'=>$person_info->country));?>

</div>
</div>
<div class="col-sm-8">
<?php
if ($this->Location->get_info_for_key('mailchimp_api_key'))
{
    ?>
    <div class="form-group">
        <div class="column">
            <?php echo form_label(lang('common_mailing_lists').':', 'mailchimp_mailing_lists',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label ')); ?>
        </div>

        <div class="column">
            <ul style="list-style: none; float:left;">
                <?php
                foreach(get_all_mailchimps_lists() as $list)
                {
                    echo '<li>';
                    echo form_checkbox(array('name'=> 'mailing_lists[]',
                        'id' => $list['id'],
                        'value' => $list['id'],
                        'checked' => email_subscribed_to_list($person_info->email, $list['id']),
                        'label'	=> $list['id']));
                    echo ' '.form_label($list['name'], $list['id'], array('style' => 'float: none;'));
                    echo '</li>';
                }
                ?>
            </ul>
        </div>
        <div class="cleared"></div>
    </div>
<?php }?>
</div>

