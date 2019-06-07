<div class="portlet-body">
    <div class="table-container" style="">

        <div id="datatable_ajax_wrapper" class="dataTables_wrapper dataTables_extended_wrapper no-footer">
            <div class="row">
                <div class="col-md-8 col-sm-12">
                    <div class="dataTables_paginate paging_bootstrap_extended" id="datatable_ajax_paginate">
                        <div class="pagination-panel"> Page <a href="#" class="btn btn-sm default prev disabled"><i
                                    class="fa fa-angle-left"></i></a><input type="text"
                                class="pagination-panel-input form-control input-sm input-inline input-mini"
                                maxlenght="5" style="text-align:center; margin: 0 5px;"><a href="#"
                                class="btn btn-sm default next"><i class="fa fa-angle-right"></i></a> of <span
                                class="pagination-panel-total">4</span></div>
                    </div>
                    <div class="dataTables_length" id="datatable_ajax_length"><label><span
                                class="seperator">|</span>View <select name="datatable_ajax_length"
                                aria-controls="datatable_ajax" class="form-control input-xs input-sm input-inline">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="150">150</option>
                                <option value="-1">All</option>
                            </select> records</label></div>
                    <div class="dataTables_info" id="datatable_ajax_info" role="status" aria-live="polite"><span
                            class="seperator">|</span>Found total 178 records</div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="table-group-actions pull-right">
                        <span></span>
                        <select class="table-group-action-input form-control input-inline input-small input-sm">
                            <option value="">Select...</option>
                            <option value="Cancel">Cancel</option>
                            <option value="Cancel">Hold</option>
                            <option value="Cancel">On Hold</option>
                            <option value="Close">Close</option>
                        </select>
                        <button class="btn btn-sm green table-group-action-submit">
                            <i class="fa fa-check"></i> Submit</button>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover table-checkable dataTable no-footer"
                    id="datatable_ajax" aria-describedby="datatable_ajax_info" role="grid">
                    <thead>
                        <tr role="row" class="heading">
                            <th width="2%" class="sorting_disabled" rowspan="1" colspan="1">
                                <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                    <input type="checkbox" class="group-checkable" data-set="#sample_2 .checkboxes">
                                    <span></span>
                                </label>
                            </th>
                            <th width="5%" class="sorting_disabled" rowspan="1" colspan="1"> Record&nbsp;# </th>
                            <th width="15%" class="sorting_disabled" rowspan="1" colspan="1"> Date </th>
                            <th width="200" class="sorting_disabled" rowspan="1" colspan="1"> Customer </th>
                            <th width="10%" class="sorting_disabled" rowspan="1" colspan="1"> Ship&nbsp;To </th>
                            <th width="10%" class="sorting_disabled" rowspan="1" colspan="1"> Price </th>
                            <th width="10%" class="sorting_disabled" rowspan="1" colspan="1"> Amount </th>
                            <th width="10%" class="sorting_disabled" rowspan="1" colspan="1"> Status </th>
                            <th width="10%" class="sorting_disabled" rowspan="1" colspan="1"> Actions </th>
                        </tr>
                        <tr role="row" class="filter">
                            <td rowspan="1" colspan="1"> </td>
                            <td rowspan="1" colspan="1">
                                <input type="text" class="form-control form-filter input-sm" name="order_id"> </td>
                            <td rowspan="1" colspan="1">
                                <div class="input-group date date-picker margin-bottom-5" data-date-format="dd/mm/yyyy">
                                    <input type="text" class="form-control form-filter input-sm" readonly=""
                                        name="order_date_from" placeholder="From">
                                    <span class="input-group-btn">
                                        <button class="btn btn-sm default" type="button">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>
                                <div class="input-group date date-picker" data-date-format="dd/mm/yyyy">
                                    <input type="text" class="form-control form-filter input-sm" readonly=""
                                        name="order_date_to" placeholder="To">
                                    <span class="input-group-btn">
                                        <button class="btn btn-sm default" type="button">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>
                            </td>
                            <td rowspan="1" colspan="1">
                                <input type="text" class="form-control form-filter input-sm" name="order_customer_name">
                            </td>
                            <td rowspan="1" colspan="1">
                                <input type="text" class="form-control form-filter input-sm" name="order_ship_to"> </td>
                            <td rowspan="1" colspan="1">
                                <div class="margin-bottom-5">
                                    <input type="text" class="form-control form-filter input-sm" name="order_price_from"
                                        placeholder="From"> </div>
                                <input type="text" class="form-control form-filter input-sm" name="order_price_to"
                                    placeholder="To">
                            </td>
                            <td rowspan="1" colspan="1">
                                <div class="margin-bottom-5">
                                    <input type="text"
                                        class="form-control form-filter input-sm margin-bottom-5 clearfix"
                                        name="order_quantity_from" placeholder="From"> </div>
                                <input type="text" class="form-control form-filter input-sm" name="order_quantity_to"
                                    placeholder="To">
                            </td>
                            <td rowspan="1" colspan="1">
                                <select name="order_status" class="form-control form-filter input-sm">
                                    <option value="">Select...</option>
                                    <option value="pending">Pending</option>
                                    <option value="closed">Closed</option>
                                    <option value="hold">On Hold</option>
                                    <option value="fraud">Fraud</option>
                                </select>
                            </td>
                            <td rowspan="1" colspan="1">
                                <div class="margin-bottom-5">
                                    <button class="btn btn-sm green btn-outline filter-submit margin-bottom">
                                        <i class="fa fa-search"></i> Search</button>
                                </div>
                                <button class="btn btn-sm red btn-outline filter-cancel">
                                    <i class="fa fa-times"></i> Reset</button>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr role="row" class="odd">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="1"><span></span></label></td>
                            <td>1</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>8</td>
                            <td><span class="label label-sm label-info">Closed</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="even">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="2"><span></span></label></td>
                            <td>2</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>5</td>
                            <td><span class="label label-sm label-info">Closed</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="odd">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="3"><span></span></label></td>
                            <td>3</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>9</td>
                            <td><span class="label label-sm label-info">Closed</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="even">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="4"><span></span></label></td>
                            <td>4</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>4</td>
                            <td><span class="label label-sm label-success">Pending</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="odd">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="5"><span></span></label></td>
                            <td>5</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>8</td>
                            <td><span class="label label-sm label-info">Closed</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="even">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="6"><span></span></label></td>
                            <td>6</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>1</td>
                            <td><span class="label label-sm label-info">Closed</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="odd">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="7"><span></span></label></td>
                            <td>7</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>5</td>
                            <td><span class="label label-sm label-info">Closed</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="even">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="8"><span></span></label></td>
                            <td>8</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>6</td>
                            <td><span class="label label-sm label-success">Pending</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="odd">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="9"><span></span></label></td>
                            <td>9</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>6</td>
                            <td><span class="label label-sm label-danger">On Hold</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="even">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="10"><span></span></label></td>
                            <td>10</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>3</td>
                            <td><span class="label label-sm label-success">Pending</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="odd">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="11"><span></span></label></td>
                            <td>11</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>5</td>
                            <td><span class="label label-sm label-info">Closed</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="even">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="12"><span></span></label></td>
                            <td>12</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>7</td>
                            <td><span class="label label-sm label-info">Closed</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="odd">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="13"><span></span></label></td>
                            <td>13</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>7</td>
                            <td><span class="label label-sm label-danger">On Hold</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="even">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="14"><span></span></label></td>
                            <td>14</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>3</td>
                            <td><span class="label label-sm label-success">Pending</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="odd">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="15"><span></span></label></td>
                            <td>15</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>1</td>
                            <td><span class="label label-sm label-success">Pending</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="even">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="16"><span></span></label></td>
                            <td>16</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>7</td>
                            <td><span class="label label-sm label-danger">On Hold</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="odd">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="17"><span></span></label></td>
                            <td>17</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>8</td>
                            <td><span class="label label-sm label-info">Closed</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="even">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="18"><span></span></label></td>
                            <td>18</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>2</td>
                            <td><span class="label label-sm label-danger">On Hold</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="odd">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="19"><span></span></label></td>
                            <td>19</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>6</td>
                            <td><span class="label label-sm label-danger">On Hold</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="even">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="20"><span></span></label></td>
                            <td>20</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>10</td>
                            <td><span class="label label-sm label-info">Closed</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="odd">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="21"><span></span></label></td>
                            <td>21</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>1</td>
                            <td><span class="label label-sm label-info">Closed</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="even">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="22"><span></span></label></td>
                            <td>22</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>6</td>
                            <td><span class="label label-sm label-info">Closed</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="odd">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="23"><span></span></label></td>
                            <td>23</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>9</td>
                            <td><span class="label label-sm label-success">Pending</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="even">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="24"><span></span></label></td>
                            <td>24</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>2</td>
                            <td><span class="label label-sm label-danger">On Hold</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="odd">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="25"><span></span></label></td>
                            <td>25</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>1</td>
                            <td><span class="label label-sm label-danger">On Hold</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="even">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="26"><span></span></label></td>
                            <td>26</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>4</td>
                            <td><span class="label label-sm label-danger">On Hold</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="odd">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="27"><span></span></label></td>
                            <td>27</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>7</td>
                            <td><span class="label label-sm label-success">Pending</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="even">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="28"><span></span></label></td>
                            <td>28</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>2</td>
                            <td><span class="label label-sm label-danger">On Hold</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="odd">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="29"><span></span></label></td>
                            <td>29</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>9</td>
                            <td><span class="label label-sm label-danger">On Hold</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="even">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="30"><span></span></label></td>
                            <td>30</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>7</td>
                            <td><span class="label label-sm label-success">Pending</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="odd">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="31"><span></span></label></td>
                            <td>31</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>8</td>
                            <td><span class="label label-sm label-danger">On Hold</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="even">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="32"><span></span></label></td>
                            <td>32</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>5</td>
                            <td><span class="label label-sm label-success">Pending</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="odd">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="33"><span></span></label></td>
                            <td>33</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>2</td>
                            <td><span class="label label-sm label-danger">On Hold</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="even">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="34"><span></span></label></td>
                            <td>34</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>7</td>
                            <td><span class="label label-sm label-danger">On Hold</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="odd">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="35"><span></span></label></td>
                            <td>35</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>2</td>
                            <td><span class="label label-sm label-danger">On Hold</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="even">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="36"><span></span></label></td>
                            <td>36</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>6</td>
                            <td><span class="label label-sm label-danger">On Hold</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="odd">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="37"><span></span></label></td>
                            <td>37</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>6</td>
                            <td><span class="label label-sm label-danger">On Hold</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="even">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="38"><span></span></label></td>
                            <td>38</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>7</td>
                            <td><span class="label label-sm label-danger">On Hold</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="odd">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="39"><span></span></label></td>
                            <td>39</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>2</td>
                            <td><span class="label label-sm label-danger">On Hold</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="even">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="40"><span></span></label></td>
                            <td>40</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>2</td>
                            <td><span class="label label-sm label-info">Closed</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="odd">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="41"><span></span></label></td>
                            <td>41</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>7</td>
                            <td><span class="label label-sm label-success">Pending</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="even">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="42"><span></span></label></td>
                            <td>42</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>10</td>
                            <td><span class="label label-sm label-success">Pending</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="odd">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="43"><span></span></label></td>
                            <td>43</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>3</td>
                            <td><span class="label label-sm label-success">Pending</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="even">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="44"><span></span></label></td>
                            <td>44</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>10</td>
                            <td><span class="label label-sm label-success">Pending</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="odd">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="45"><span></span></label></td>
                            <td>45</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>4</td>
                            <td><span class="label label-sm label-info">Closed</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="even">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="46"><span></span></label></td>
                            <td>46</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>3</td>
                            <td><span class="label label-sm label-success">Pending</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="odd">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="47"><span></span></label></td>
                            <td>47</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>2</td>
                            <td><span class="label label-sm label-success">Pending</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="even">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="48"><span></span></label></td>
                            <td>48</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>1</td>
                            <td><span class="label label-sm label-success">Pending</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="odd">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="49"><span></span></label></td>
                            <td>49</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>2</td>
                            <td><span class="label label-sm label-success">Pending</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                        <tr role="row" class="even">
                            <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]"
                                        type="checkbox" class="checkboxes" value="50"><span></span></label></td>
                            <td>50</td>
                            <td>12/09/2013</td>
                            <td>Jhon Doe</td>
                            <td>Jhon Doe</td>
                            <td>450.60$</td>
                            <td>6</td>
                            <td><span class="label label-sm label-info">Closed</span></td>
                            <td><a href="javascript:;" class="btn btn-sm btn-outline grey-salsa"><i
                                        class="fa fa-search"></i> View</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-md-8 col-sm-12">
                    <div class="dataTables_paginate paging_bootstrap_extended">
                        <div class="pagination-panel"> Page <a href="#" class="btn btn-sm default prev disabled"><i
                                    class="fa fa-angle-left"></i></a><input type="text"
                                class="pagination-panel-input form-control input-sm input-inline input-mini"
                                maxlenght="5" style="text-align:center; margin: 0 5px;"><a href="#"
                                class="btn btn-sm default next"><i class="fa fa-angle-right"></i></a> of <span
                                class="pagination-panel-total">4</span></div>
                    </div>
                    <div class="dataTables_length"><label><span class="seperator">|</span>View <select
                                name="datatable_ajax_length" aria-controls="datatable_ajax"
                                class="form-control input-xs input-sm input-inline">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="150">150</option>
                                <option value="-1">All</option>
                            </select> records</label></div>
                    <div class="dataTables_info"><span class="seperator">|</span>Found total 178 records</div>
                </div>
                <div class="col-md-4 col-sm-12"></div>
            </div>
        </div>
    </div>
</div>