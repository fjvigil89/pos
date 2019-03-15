<?php
if($eDatos->email !='') :
    $quien=$eDatos->first_name." ".$eDatos->last_name;
    $equipo="<b>".lang("technical_supports_email_disp").": </b>".$eDatos->type_team;
    $falla="<b>".lang("technical_supports_email_ftec").": </b>".$eDatos->damage_failure;
    $Informacion=lang("technical_supports_email_menj")." <b>$eDatos->state</b>, ".lang("technical_supports_email_menj2");
    ?>
    <html> 
        <body> 
            <div style="width: 100%;background: #DDDDDD;padding: 30px;overflow: hidden;height: auto;">
                <div style="margin: 10px auto 10px auto;font-weight: 700;text-align: center;">
                        <?php echo $this->config->item('company'); ?>
                    </div>
                <div style="width: 400px;background: #FFFFFF;border: 1px #B3B4BD solid;padding: 10px;text-align: center; margin: auto;">
                    <?php echo  img(array('src' => base_url().'assets/template/images/perfil.JPG'));
                    ?>
                    <div style="margin: 10px auto 10px auto;font-weight: 700;text-align: center;">
                        <?php echo $quien ?>
                    </div>
                    <div style="margin: 5px auto 5px auto;font-weight: normal;text-align: left;padding: 10px;">
                        <?php echo $equipo?><br>
                        <?php echo $falla?> 
                    </div>
                    <div style="margin: 5px auto 5px auto;font-weight: normal;text-align: left;padding: 10px; color: #DDDDDD">
                         <?php echo $Informacion?> 
                    </div>
                </div>
            </div>
        </body>
    </html>    
<?php endif; ?>


 