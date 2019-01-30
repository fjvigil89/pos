  <div class="table-responsive">
    <?php
    $name= ($type==="0" ? 'asignacion':'deduccion');

     ?>
    <table id="<?php echo 'table-'.$name ?>" class="table table-bordered table-striped  table-condensed table-hover detailed-reports  tablesorter" id="sortable_table">
      <thead>
        <tr  style="font-weight:bold">
        <!--   <td style="width:15px; display: none" class='leftmost'></td>-->
          <td  style="width:20px"  class='rightmost'><?php echo lang('nomina_porcentaje'); ?></td>
          <td style="width:20px" align="center"><?php echo lang('nomina_monto'); ?></td>
          <td   align=""><?php echo lang('nomina_descripcion'); ?></td>
          <td style="width:100px"  align=""><?php echo lang('nomina_fecha'); ?></td>
          <td style="width:200px"  align=""><?php echo lang('nomina_opciones'); ?></td>
        </tr>
      </thead>
      <tbody>

  </tr>
        <?php foreach ($dedu_asig as $dato): ?>
            <?php if ($dato->tipo===$type): ?>
              <tr>
                <td class="hidden-print" style="display: none;">
                  <input type="checkbox"  class="css-checkbox"  name="eliminar_registro" value="<?php echo $dato->id ?>"/>
                  <label for="select_all" class="css-label cb0"></label>
                  <input type="hidden" name="<?php echo $name."[]"; ?>" value="<?php echo $dato->id ?>">

                </td>
                <td class="hidden-print">
                  <?php echo $dato->porc ?>
                    <input type="hidden" name="<?php echo $name."[]"; ?>" value="<?php echo $dato->porc ?>">
                </td>
                <td class="hidden-print">
                  <?php echo $dato->monto ?>
                    <input type="hidden" name="<?php echo $name."[]"; ?>" value="<?php echo $dato->monto ?>">
                </td>
                <td class="hidden-print">
                  <?php echo $dato->descripcion?>
                    <input type="hidden" name="<?php echo $name."[]"; ?>" value="<?php echo $dato->descripcion?>">
                </td>
                <td class="hidden-print">
                  <?php echo $dato->created ?>
                </td>
                <td class="hidden-print">
                  <a onclick="selecciona_fila(this)" class="btn btn-xs btn-primary btn-editable" href="<?php echo base_url().'index.php/nomina/asig_dedu_modal/2'; ?>"
                     data-toggle="modal" data-target="#myModal">
                      <i class="fa fa-pencil"></i>
                      <?php echo lang("common_edit"); ?>
                                          </a>
                    <button type="button" onclick="eliminarRegistro(this)" class="btn btn-xs btn-danger btn-editable "  >
                        <i class="fa fa-trash-o"></i>
                        <?php echo lang("common_delete"); ?>
                      </button>
              </td>
              </tr>
            <?php endif; ?>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
