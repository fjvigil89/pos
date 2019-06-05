
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-social-dribbble font-green hide"></i>
                    <span class="caption-subject font-dark bold uppercase">Table Pagination</span>
                </div>
                <div class="actions">
                    <a class="btn btn-circle btn-icon-only btn-default" href="<?php echo site_url().'/citas/calendar' ?>">
                        <i class="icon-cloud-upload"></i>
                    </a>
                    <a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
                        <i class="icon-wrench"></i>
                    </a>
                    <a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
                        <i class="icon-trash"></i>
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="bootstrap-table">
                    
                    
                        <div class="fixed-table-body">
                            <div class="fixed-table-loading" style="top: 41px; display: none;">Loading, please wait...
                            </div>
                            <table id="table-pagination" data-toggle="table"
                                data-url="http://localhost/pos/index.php/citas/getApiSchedule" data-height="299"
                                data-pagination="true" data-search="true" class="table table-hover"
                                style="margin-top: -40px;">
                                <thead>
                                    <tr>
                                        <th style="text-align: right; " >
                                            <div class="th-inner  ">Title</div>
                                            
                                        </th>
                                        <th style="text-align: center; " >
                                            <div class="th-inner  ">start</div>
                                            
                                        </th>
                                        <th style="text-align: center; " >
                                            <div class="th-inner  ">End</div>
                                            
                                        </th>
                                        <th style="text-align: center; " >
                                            <div class="th-inner  ">color</div>
                                            
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($schedule as $key => $value) { ?>
                                    <tr data-index= <?php echo $key ?> >                                        
                                        <td style="text-align: right; "><?php echo $value['title']; ?></td>
                                        <td style="text-align: center; "><?php echo $value['start']; ?></td>
                                        <td style="text-align: center; "><?php echo $value['end']; ?></td>
                                        <td style="text-align: center; "><?php echo $value['color']; ?></td>
                                    </tr> 
                                <?php } ?>                                                                         
                                </tbody>
                            </table>
                        </div>
                        <div class="fixed-table-footer" style="display: none;">
                            <table>
                                <tbody>
                                    <tr></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="fixed-table-pagination" style="display: block;">
                            
                        </div>
                    
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>