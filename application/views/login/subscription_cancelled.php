<html lang="en">
    <head>
        <?php $this->load->view("partial/head"); ?>        
    </head>

    <body class="page-md">


       <section id="pricing" class="wrapper">
        <div class="container">

            <a class="btn btn-danger" style="margin-top: 20px; float: right; " href="<?php echo base_url();?>index.php/home/logout" style="text-decoration: none; color: #FFF "><i class="fa fa-power-off"></i> Salir</a>
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="heading centered">
                        <div class="page-subscription-cancelled">
            <div class="page-logo">
                <a href="<?php  echo URL_POS?>">
                    <?php echo img(
                    array(
                        'src' => $this->Appconfig->get_logo_image(),
                    )); ?>
                </a>
            </div>
        </div>
                    </div>
                </div>
                </div>

            <div style="margin: 30px 0;"></div>

                            <div class="pricing-content-1">
                                <div class="row">
                                    
                                    <div class="col-md-3">
                                      
                                        <div class="price-column-container border-active" style="border: solid 3px #005996 !important;">
                                            <div class="price-table-head bg-blue" style="height: 125px !important; border-bottom: 3px solid #005996 !important;">
                                                <?php
                                                echo "<h2>".lang('login_main_features')."</h2>"; 
                                                ?>
                                            </div>
                                            <div class="arrow-down border-top-blue"></div>
                                            <div class="price-table-pricing">
                                            </div>
                                            <div class="price-table-content">
                                                <div style="font-size: 15px !important; width: 90%;">
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-3 text-right mobile-padding">
                                                        <i class="fa fa-desktop" style="margin-top: 30%;"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">
                                                        <?php
                                                            echo lang('login_features_one'); 
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-3 text-right mobile-padding">
                                                        <i class="fa fa-bell-o" style="margin-top: 30%;"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">
                                                        <?php
                                                            echo lang('login_features_two'); 
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-3 text-right mobile-padding">
                                                        <i class="fa fa-globe" style="margin-top: 30%;"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">
                                                        <?php
                                                            echo lang('login_features_three'); 
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-3 text-right mobile-padding">
                                                        <i class="fa fa-envelope-o" style="margin-top: 30%;"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">
                                                        <?php
                                                            echo lang('login_features_four'); 
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-3 text-right mobile-padding">
                                                        <i class="fa fa-area-chart" style="margin-top: 30%;"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">
                                                        <?php
                                                            echo lang('login_features_five'); 
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-3 text-right mobile-padding">
                                                        <i class="fa fa-money" style="margin-top: 30%;"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">
                                                        <?php
                                                            echo lang('login_features_six'); 
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-3 text-right mobile-padding">
                                                        <i class="fa fa-clock-o" style="margin-top: 30%;"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">
                                                        <?php
                                                            echo lang('login_features_seven'); 
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-3 text-right mobile-padding">
                                                        <i class="fa fa-comments-o" style="margin-top: 30%;"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">
                                                        <?php
                                                            echo lang('login_features_eight'); 
                                                        ?>
                                                    </div>
                                                </div>
                                                
                                                </div>
                                            </div>
                                            <div class="arrow-down arrow-grey"></div>
                                            </div>
                                            <div class="price-table-footer">
                                            </div>
                                            <div style="margin-bottom: 20px;"></div>
                                        </div>

                                    <div class="col-md-3">
                                        <div class="price-column-container border-active" style="height: 760px !important; border: solid 3px #880005 !important;">
                                            <div class="price-table-head bg-red" style="height: 125px !important; border-bottom: 3px solid #880005 !important;">
                                                <?php
                                                echo "<h2>".lang('login_monthly_plan')."</h2>"; 
                                                ?>
                                            </div>
                                            <div class="arrow-down border-top-red"></div>
                                            <div class="price-table-pricing">
                                                <div style="margin: 10px 0;">
                                                    <?php
                                                        echo "<h4><strong>".lang('login_include')."</strong></h4>"; 
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="price-table-content">
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-3 text-right mobile-padding">
                                                        <i class="glyphicon glyphicon-ok"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">
                                                        <?php
                                                            echo lang('login_includes_one'); 
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-3 text-right mobile-padding">
                                                        <i class="glyphicon glyphicon-ok"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">
                                                        <?php
                                                            echo lang('login_includes_two'); 
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-3 text-right mobile-padding">
                                                        <i class="glyphicon glyphicon-ok"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">
                                                        <?php
                                                            echo lang('login_includes_three'); 
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-3 text-right mobile-padding">
                                                        <i class="glyphicon glyphicon-ok"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">
                                                        <?php
                                                            echo lang('login_includes_four'); 
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-3 text-right mobile-padding">
                                                        <i class="glyphicon glyphicon-ok"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">
                                                        <?php
                                                            echo lang('login_includes_five'); 
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="arrow-down arrow-grey"></div>

                                            <div class="price-table-footer">
                        <?php echo form_open($gateway_url,['style'=>'margin: 8px 0px;', 'method'=>'POST']);?>                    
                            <input name="merchantId"      type="hidden"  value="<?php echo $merchantId;?>"   >
                            <input name="accountId"       type="hidden"  value="<?php echo $accountId;?>" >
                            <input name="description"     type="hidden"  value="Pago de punto de venta por 1 mes"  >
                            <input name="referenceCode"   type="hidden"  value="<?php echo $referenceCode;?>" >
                            <input name="amount"          type="hidden"  value="<?php echo $one_month;?>"   >
                            <input name="tax"             type="hidden"  value="0"  >
                            <input name="taxReturnBase"   type="hidden"  value="0" >
                            <input name="currency"        type="hidden"  value="<?php echo $currency;?>" >
                            <input name="signature"       type="hidden"  value="<?php echo md5($api_key."~".$merchantId."~".$referenceCode."~".$one_month."~".$currency);?>">
                            <input name="test"            type="hidden"  value="<?php echo $test;?>" >
                            <input name="responseUrl"     type="hidden"  value="<?php echo $responseUrl;?>" >
                            <input name="confirmationUrl" type="hidden"  value="<?php echo $confirmationUrl;?>" >
                            <input name="extra1"          type="hidden"  value="<?php echo $extra1;?>" >
                            <?php if(!$this->config->item('es_franquicia')){ ?>
                            <button onclick="this.form.urlOrigen.value = window.location.href;" class="btn btn-primary"><?php echo lang('login_subscription_one_month'); ?></button>                     
                            <?php }?>
                        </form>

                        <div style="width: 90%; border: #A9A9A9 solid 0.01px; margin: 10px auto; "></div>
                        
                        <?php echo form_open($gateway_url,['style'=>'margin: 8px 0px;', 'method'=>'POST']);?> 
                            <input name="merchantId"      type="hidden"  value="<?php echo $merchantId;?>"   >
                            <input name="accountId"       type="hidden"  value="<?php echo $accountId;?>" >
                            <input name="description"     type="hidden"  value="Pago de punto de venta por 2 meses"  >
                            <input name="referenceCode"   type="hidden"  value="<?php echo $referenceCode;?>" >
                            <input name="amount"          type="hidden"  value="<?php echo $two_months;?>"   >
                            <input name="tax"             type="hidden"  value="0"  >
                            <input name="taxReturnBase"   type="hidden"  value="0" >
                            <input name="currency"        type="hidden"  value="<?php echo $currency;?>" >
                            <input name="signature"       type="hidden"  value="<?php echo md5($api_key."~".$merchantId."~".$referenceCode."~".$two_months."~".$currency);?>">
                            <input name="test"            type="hidden"  value="<?php echo $test;?>" >
                            <input name="responseUrl"     type="hidden"  value="<?php echo $responseUrl;?>" >
                            <input name="confirmationUrl" type="hidden"  value="<?php echo $confirmationUrl;?>" >
                            <input name="extra1"          type="hidden"  value="<?php echo $extra1;?>" >
                            <?php if(!$this->config->item('es_franquicia')){ ?>
                            <button onclick="this.form.urlOrigen.value = window.location.href;" class="btn btn-primary"><?php echo lang('login_subscription_two_months'); ?></button>                        
                            <?php } ?>
                        </form>
                        
                        <div style="width: 90%; border: #A9A9A9 solid 0.01px; margin: 10px auto; "></div>

                        <?php echo form_open($gateway_url,['style'=>'margin: 8px 0px;', 'method'=>'POST']);?> 
                            <input name="merchantId"      type="hidden"  value="<?php echo $merchantId;?>"   >
                            <input name="accountId"       type="hidden"  value="<?php echo $accountId;?>" >
                            <input name="description"     type="hidden"  value="Pago de punto de venta por 3 meses"  >
                            <input name="referenceCode"   type="hidden"  value="<?php echo $referenceCode;?>" >
                            <input name="amount"          type="hidden"  value="<?php echo $three_months;?>"   >
                            <input name="tax"             type="hidden"  value="0"  >
                            <input name="taxReturnBase"   type="hidden"  value="0" >
                            <input name="currency"        type="hidden"  value="<?php echo $currency;?>" >
                            <input name="signature"       type="hidden"  value="<?php echo md5($api_key."~".$merchantId."~".$referenceCode."~".$three_months."~".$currency);?>">
                            <input name="test"            type="hidden"  value="<?php echo $test;?>" >
                            <input name="responseUrl"     type="hidden"  value="<?php echo $responseUrl;?>" >
                            <input name="confirmationUrl" type="hidden"  value="<?php echo $confirmationUrl;?>" >
                            <input name="extra1"          type="hidden"  value="<?php echo $extra1;?>" >
                            <?php if(!$this->config->item('es_franquicia')){ ?>
                            <button onclick="this.form.urlOrigen.value = window.location.href;" class="btn btn-primary"><?php echo lang('login_subscription_three_months'); ?></button>                      
                            <?php } ?>
                        </form>
                        <?php if(!$this->config->item('es_franquicia')){ ?>
                        <font color="red">
                        <?php echo "<b>".lang('login_saving_three_months')."</b>"; ?></font>
                        <?php } ?>
                        <div style="width: 90%; border: #A9A9A9 solid 0.01px; margin: 10px auto; "></div>
                        
                        <?php echo form_open($gateway_url,['style'=>'margin: 8px 0px;', 'method'=>'POST']);?> 
                            <input name="merchantId"      type="hidden"  value="<?php echo $merchantId;?>"   >
                            <input name="accountId"       type="hidden"  value="<?php echo $accountId;?>" >
                            <input name="description"     type="hidden"  value="Pago de punto de venta por 6 meses"  >
                            <input name="referenceCode"   type="hidden"  value="<?php echo $referenceCode;?>" >
                            <input name="amount"          type="hidden"  value="<?php echo $six_months;?>"   >
                            <input name="tax"             type="hidden"  value="0"  >
                            <input name="taxReturnBase"   type="hidden"  value="0" >
                            <input name="currency"        type="hidden"  value="<?php echo $currency;?>" >
                            <input name="signature"       type="hidden"  value="<?php echo md5($api_key."~".$merchantId."~".$referenceCode."~".$six_months."~".$currency);?>">
                            <input name="test"            type="hidden"  value="<?php echo $test;?>" >
                            <input name="responseUrl"     type="hidden"  value="<?php echo $responseUrl;?>" >
                            <input name="confirmationUrl" type="hidden"  value="<?php echo $confirmationUrl;?>" >
                            <input name="extra1"          type="hidden"  value="<?php echo $extra1;?>" >
                            <?php if(!$this->config->item('es_franquicia')==true){ ?>
                            <button onclick="this.form.urlOrigen.value = window.location.href;" class="btn btn-primary"><?php echo lang('login_subscription_six_months'); ?></button>                        
                            <?php } ?>
                        </form>
                        <?php if(!$this->config->item('es_franquicia')){ ?>
                        <font color="red">
                        <?php echo "<b>".lang('login_saving_six_months')."</b>"; ?></font>
                        <?php } ?>
                                            <div style="height: 30px"></div>
                                        </div>
                                    </div>
                                    <div style="margin-bottom: 20px;"></div>
                                    </div>
                                
                                    <div class="col-md-3">
                                        <div class="price-column-container border-active" style="height: 760px !important; border: solid 3px #005D54 !important;">
                                            <div class="price-table-head bg-green" style="height: 125px !important; border-bottom: 3px solid #005D54 !important;">
                                                <?php
                                                echo "<h2>".lang('login_annual_plan')."</h2>"; 
                                                ?>
                                            </div>
                                            <div class="arrow-down border-top-green"></div>
                                            <div class="price-table-pricing">
                                                <div style="margin: 10px 0;">
                                                    <?php
                                                        echo "<h4><strong>".lang('login_include')."</strong></h4>"; 
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="price-table-content">
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-3 text-right mobile-padding">
                                                        <i class="glyphicon glyphicon-ok"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">
                                                        <?php
                                                            echo lang('login_includes_one'); 
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-3 text-right mobile-padding">
                                                        <i class="glyphicon glyphicon-ok"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">
                                                        <?php
                                                            echo lang('login_includes_two'); 
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-3 text-right mobile-padding">
                                                        <i class="glyphicon glyphicon-ok"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">
                                                        <?php
                                                            echo lang('login_includes_three'); 
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-3 text-right mobile-padding">
                                                        <i class="glyphicon glyphicon-ok"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">
                                                        <?php
                                                            echo lang('login_includes_four'); 
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-3 text-right mobile-padding">
                                                        <i class="glyphicon glyphicon-ok"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">
                                                        <?php
                                                            echo lang('login_includes_five'); 
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="arrow-down arrow-grey"></div>
                                            
                                            <div class="price-table-footer">
                        <?php echo form_open($gateway_url,['style'=>'margin: 8px 0px;', 'method'=>'POST']);?> 
                            <input name="merchantId"      type="hidden"  value="<?php echo $merchantId;?>"   >
                            <input name="accountId"       type="hidden"  value="<?php echo $accountId;?>" >
                            <input name="description"     type="hidden"  value="Pago de punto de venta por 1 año"  >
                            <input name="referenceCode"   type="hidden"  value="<?php echo $referenceCode;?>" >
                            <input name="amount"          type="hidden"  value="<?php echo $one_year;?>"   >
                            <input name="tax"             type="hidden"  value="0"  >
                            <input name="taxReturnBase"   type="hidden"  value="0" >
                            <input name="currency"        type="hidden"  value="<?php echo $currency;?>" >
                            <input name="signature"       type="hidden"  value="<?php echo md5($api_key."~".$merchantId."~".$referenceCode."~".$one_year."~".$currency);?>">
                            <input name="test"            type="hidden"  value="<?php echo $test;?>" >
                            <input name="responseUrl"     type="hidden"  value="<?php echo $responseUrl;?>" >
                            <input name="confirmationUrl" type="hidden"  value="<?php echo $confirmationUrl;?>" >
                            <input name="extra1"          type="hidden"  value="<?php echo $extra1;?>" >
                            <?php if(!$this->config->item('es_franquicia')){ ?>
                            <button onclick="this.form.urlOrigen.value = window.location.href;" class="btn btn-primary"><?php echo lang('login_subscription_year'); ?></button>                        
                            <?php } ?>
                        </form>

                        <div style="width: 90%; border: #A9A9A9 solid 0.01px; margin: 10px auto; "></div>

                        <?php echo form_open($gateway_url,['style'=>'margin: 8px 0px;', 'method'=>'POST']);?> 
                            <input name="merchantId"      type="hidden"  value="<?php echo $merchantId;?>"   >
                            <input name="accountId"       type="hidden"  value="<?php echo $accountId;?>" >
                            <input name="description"     type="hidden"  value="Renovación anual"  >
                            <input name="referenceCode"   type="hidden"  value="<?php echo $referenceCode;?>" >
                            <input name="amount"          type="hidden"  value="<?php echo $renovation;?>"   >
                            <input name="tax"             type="hidden"  value="0"  >
                            <input name="taxReturnBase"   type="hidden"  value="0" >
                            <input name="currency"        type="hidden"  value="<?php echo $currency;?>" >
                            <input name="signature"       type="hidden"  value="<?php echo md5($api_key."~".$merchantId."~".$referenceCode."~".$renovation."~".$currency);?>">
                            <input name="test"            type="hidden"  value="<?php echo $test;?>" >
                            <input name="responseUrl"     type="hidden"  value="<?php echo $responseUrl;?>" >
                            <input name="confirmationUrl" type="hidden"  value="<?php echo $confirmationUrl;?>" >
                            <input name="extra1"          type="hidden"  value="<?php echo $extra1;?>" >
                            <?php if(!$this->config->item('es_franquicia')){ ?>
                            <button onclick="this.form.urlOrigen.value = window.location.href;" class="btn btn-primary"><?php echo lang('login_subscription_renovation'); ?></button>                        
                            <?php }?>
                        </form>
                                            <div style="height: 30px"></div>
                                            </div>
                                        </div>
                                    <div style="margin-bottom: 20px;"></div>
                                    </div>


                                    
                                    <div class="col-md-3">
                                        <div class="price-column-container border-active" style="height: 760px !important; border: solid 3px #470065 !important;">
                                            <div class="price-table-head bg-purple" style="height: 125px !important; border-bottom: 3px solid #470065 !important;">
                                            <div>
                                                    <?php
                                                echo "<h2>".lang('login_departament_store')."</h2>"; 
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="arrow-down border-top-purple"></div>
                                            <div class="price-table-pricing">
                                                <div style="margin: 10px 0;">
                                                    <?php
                                                        echo "<h4><strong>".lang('login_include')."</strong></h4>"; 
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="price-table-content">
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-3 text-right mobile-padding">
                                                        <i class="glyphicon glyphicon-ok"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">
                                                        <?php
                                                            echo lang('login_includes_one'); 
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-3 text-right mobile-padding">
                                                        <i class="glyphicon glyphicon-ok"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">
                                                        <?php
                                                            echo lang('login_includes_two'); 
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-3 text-right mobile-padding">
                                                        <i class="glyphicon glyphicon-ok"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">
                                                        <?php
                                                            echo lang('login_includes_three'); 
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-3 text-right mobile-padding">
                                                        <i class="glyphicon glyphicon-ok"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">
                                                        <?php
                                                            echo lang('login_includes_four'); 
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="row mobile-padding">
                                                    <div class="col-xs-3 text-right mobile-padding">
                                                        <i class="glyphicon glyphicon-ok"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-left mobile-padding">
                                                        <?php
                                                            echo lang('login_includes_five'); 
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="arrow-down arrow-grey"></div>
                                            <div class="price-table-footer">
                        <?php echo form_open($gateway_url,['style'=>'margin: 8px 0px;', 'method'=>'POST']);?> 
                            <input name="merchantId"      type="hidden"  value="<?php echo $merchantId;?>"   >
                            <input name="accountId"       type="hidden"  value="<?php echo $accountId;?>" >
                            <input name="description"     type="hidden"  value="Pago de punto de venta por 1 año"  >
                            <input name="referenceCode"   type="hidden"  value="<?php echo $referenceCode;?>" >
                            <input name="amount"          type="hidden"  value="<?php echo $one_year;?>"   >
                            <input name="tax"             type="hidden"  value="0"  >
                            <input name="taxReturnBase"   type="hidden"  value="0" >
                            <input name="currency"        type="hidden"  value="<?php echo $currency;?>" >
                            <input name="signature"       type="hidden"  value="<?php echo md5($api_key."~".$merchantId."~".$referenceCode."~".$one_year."~".$currency);?>">
                            <input name="test"            type="hidden"  value="<?php echo $test;?>" >
                            <input name="responseUrl"     type="hidden"  value="<?php echo $responseUrl;?>" >
                            <input name="confirmationUrl" type="hidden"  value="<?php echo $confirmationUrl;?>" >
                            <input name="extra1"          type="hidden"  value="<?php echo $extra1;?>" >
                            <?php if(!$this->config->item('es_franquicia')){ ?>
                            <button onclick="this.form.urlOrigen.value = window.location.href;" class="btn btn-primary"><?php echo lang('login_subscription_store_adds'); ?></button>                      
                            <?php } ?>
                        </form>
                        <?php if(!$this->config->item('es_franquicia')){ ?>
                            <font color="red">
                            <?php echo "<b>".lang('login_saving_store_adds')."</b>"; ?></font>
                        <?php } ?>
                                            <div style="height: 50px"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="page-footer-custom" style="color: #fff; ">
                <center><?php echo YEAR ?> &copy; <?php 
                 if($this->config->item('es_franquicia')){ 
                    $data_commpany= $this->Appconfig->get_data_commpany($this->config->item('resellers_id'));
                    echo $name_commpany=$data_commpany->short_name;
                 }else{
                    echo NAME_COMPANY ;
                 }
                ?>. Versión <span class="label label-info"> <?php echo APPLICATION_VERSION; ?></span></center>
            </div>
                </section>

		<script>
        jQuery(document).ready(function() {     
          	Metronic.init(); // init metronic core components
          	Layout.init(); // init current layout
          	Login.init();
          	Demo.init();
               	// init background slide images
               	$.backstretch([
                
                /*"<?php echo base_url();?>assets/admin/pages/media/bg/10.png"
                "<?php echo base_url();?>assets/admin/pages/media/bg/2.jpg",
                "<?php echo base_url();?>assets/admin/pages/media/bg/3.jpg",
                "<?php echo base_url();?>assets/admin/pages/media/bg/4.jpg",
                "<?php echo base_url();?>assets/admin/pages/media/bg/7.jpg",*/
                "<?php echo base_url();?>assets/admin/pages/media/bg/8.jpg",
                /*"<?php echo base_url();?>assets/admin/pages/media/bg/9.jpg"*/
                ], 
                {
                  	fade: 1000,
                  	duration: 8000
        		}
            );
        });
        </script>

        <div style="height: 50px;"></div>
	</body>
</html>
