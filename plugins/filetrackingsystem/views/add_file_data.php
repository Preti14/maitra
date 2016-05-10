<script src="<?= base_url(); ?>js/jquery.validate.min-1.11.1.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>js/fts.js"></script>
<div class="box col-md-5" id="box_create_file">
    <div class="box-inner">
        <div class="box-header well" data-original-title="">
            <h2><i class=""></i> 
                Create New File</h2>
            <div class="box-icon"> <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a> </div>
        </div>
        <div class="box-content">
            <form id="add_file_data" role="form" method="post" action="?c=fts&m=add_file_data">
                <!--
                <div class="form-group col-md-6">
                    <label class="form_field_label" for="file_name">File Name</label>
                    <input class="form-control" id="file_name" name="file_name" placeholder="File Name" type="text">
                </div>
                -->
                <div class="form-group col-md-6">
                    <label class="form_field_label" for="barcode_id">File ID</label>
                    <input class="form-control" id="barcode_id" name="barcode_id" placeholder="File ID" readonly="true" type="text">
                </div>
                <div class="form-group col-md-6"> 
                    <label class="form_field_label" for="file_no">File No.</label>
                    <input class="form-control" id="file_no" name="file_no" placeholder="File No." type="text">
                </div>
                <div class="clearfix"></div>
                <!--                <div class="form-group col-md-6"> 
                                    <input class="form-control" id="file_part_no" name="file_part_no" placeholder="File Part No." type="text">
                                </div>-->
                <div class="form-group col-md-6">
                    <label class="form_field_label" for="file_subject">File Subject</label>
                    <input class="form-control" id="file_subject" name="file_subject" placeholder="File Subject" type="text">
                </div>
                <div class="form-group col-md-6">
                    <label class="form_field_label" for="file_subject_hindi">File Subject Hindi</label>
                    <input class="form-control" id="file_subject_hindi" name="file_subject_hindi" placeholder="File Subject Hindi" type="text">
                </div>
                <div class="clearfix"></div>
                <div class="form-group col-md-6">
                    <label class="form_field_label" for="department">Department</label>
                    <input class="form-control" id="department" name="department" placeholder="Department" type="text">
                </div>
                <div class="form-group col-md-6">
                    <label class="form_field_label" for="file_department_hindi">Department Hindi</label>
                    <input class="form-control" id="file_department_hindi" name="file_department_hindi" placeholder="Department Hindi" type="text">
                </div>
                <div class="clearfix"></div>
                <div class="form-group col-md-6">
                    <label class="form_field_label" for="volume">Volume</label>
                    <select class="form-control" id="volume" name="volume">
                        <option class="select-option" value="">Choose Volume </option>
                        <?php
                        foreach (array(1 => 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII',
                    'VIII', 'IX', 'X', 'XI', 'XII', 'XIII', 'XIV', 'XV') as $romanLetter):
                            ?>
                            <option class="select-option" value="<?php echo $romanLetter ?>"><?php echo $romanLetter ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label class="form_field_label" for="part_case">Part Case</label>
                    <select class="form-control" id="part_case" name="part_case">
                        <option class="select-option" value="">Choose Part Case </option>
                        <?php
                        foreach (range(1, 15) as $unit):
                            ?>
                            <option class="select-option" value="<?php echo $unit ?>"><?php echo $unit ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="clearfix"></div>
                <div class="form-group col-md-6">
                    <label class="form_field_label" for="division">Division</label>
                    <select class="form-control" id="division" name="division">
                        <?php if ($login_type == 1 || $login_type == USER_TYPE_RECORD_MANAGER): ?>
                            <option class="select-option" value="">Choose Division </option>
                        <?php endif; ?>
                        <?php foreach ($divisions as $division): ?>
                            <?php if (($login_type == 3 || $login_type == 4)): ?>
                                <?php if ($division['id'] == $div_data['division_id']): ?>
                                    <option class="select-option"
                                            value="<?php echo $division['id'] ?>"><?php echo $division['division'] ?>
                                    </option>
                                <?php endif; ?>
                            <?php elseif ($login_type == 1 || $login_type == USER_TYPE_RECORD_MANAGER): ?>
                                <option class="select-option" 
                                        value="<?php echo $division['id'] ?>"><?php echo $division['division'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label class="form_field_label" for="sub_division">Sub-Division</label>
                    <select class="form-control" id="sub_division" name="sub_division">
                        <option class="select-option" value="">Choose Sub-Division </option>
                    </select>
                </div>
                <div class="clearfix"></div>
                <div class="form-group col-md-6">
                    <label class="form_field_label" for="file_date_opened">File Date Opened</label>
                    <input class="form-control" id="file_date_opened" name="file_date_opened" placeholder="File Date Opened" type="text" style="background: url(<?php echo base_url(); ?>img/calendar.gif) no-repeat scroll 10px 10px; cursor:pointer; background-position:right 10px top 10px;background-color:#ffffff !important; opacity:1 !important;">
                </div>
                <div class="form-group col-md-6">
                    <label class="form_field_label" for="file_date_closed">File Date Closed</label>
                    <input class="form-control" id="file_date_closed" name="file_date_closed" placeholder="File Date Closed" type="text" style="background: url(<?php echo base_url(); ?>/img/calendar.gif) no-repeat scroll 10px 10px; cursor:pointer; background-position:right 10px top 10px;background-color:#ffffff !important; opacity:1 !important;">
                </div>
                <div class="clearfix"></div>
                <div class="form-group col-md-6">
                    <label class="form_field_label" for="file_category">File Category</label>
                    <input class="form-control" id="file_category" name="file_category" placeholder="File Category" type="text">
                </div>
                <div class="form-group col-md-6">

                </div>
                <div class="clearfix"></div>
                <div class="form-group col-md-12">
                    <label class="form_field_label" for="file_unit">File Unit</label>
                    <input class="form-control" id="file_unit" name="file_unit" placeholder="File Unit" type="text" readonly="" value="<?php echo $file_unit_cost ?>">
                </div>
                <div class="clearfix"></div>
                <div class="form-group col-md-6" id="button_pro">
                    <label class="form_field_label" for="file_contact_email">Contact Email</label>
                    <div id="addMoreEmail_1">
                        <input class="form-control contact_email" id="file_contact_email" name="file_contact_email" placeholder="Contact Email" type="text">
                        <img class="add right" src="<?php echo base_url(); ?>img/add.png">
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label class="form_field_label" for="file_contact_mobile">Contact Mobile</label>
                    <input class="form-control" id="file_contact_mobile" name="file_contact_mobile" placeholder="Contact Mobile" type="text">
                </div>
                <div class="clearfix"></div>
                <div class="form-group col-md-6">
                    <label class="form_field_label" for="file_contact_landline">Contact Landline</label>
                    <input class="form-control" id="file_contact_landline" name="file_contact_landline" placeholder="Contact Landline" type="text">
                </div>
                <div class="form-group col-md-6">
                    <label class="form_field_label" for="file_tags">File Tags</label>
                    <textarea class="form-control" id="file_tags" name="file_tags" placeholder="File Tags" ></textarea>
                </div>
                <div class="clearfix"></div>
                <div class="form-group col-md-12">
                    <label class="form_field_label" for="office_purpose">Office Purpose</label>
                    <textarea class="form-control" id="office_purpose" name="office_purpose" placeholder="Office Purpose" ></textarea>
                </div>
                <div class="clearfix"></div>
                <button name="entry_submit" value="save" type="submit" class="btn btn-default" id="entry_submit">Save</button>
                <div class="clearfix"></div>
            </form>
        </div>
    </div>
</div>

<div id="box_file_list" class="box col-md-7">
    <div class="box-inner">
        <div class="box-header well" data-original-title="">
            <h2><i class=""></i> File List</h2>

            <div class="box-icon">
                <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
            </div>
        </div>

        <div class="box-content">
            <div class="btn-group col-md-7 m-b-14 no-gutter">
                <form action="<?php echo site_url() ?>?c=fts&m=add_file_data" name="file_filter" id="file_filter" method="post">
                    <div class="col-md-4">
                        <label class="form_field_label" for="filter_file_no">Filter By</label>
                    </div>
                    <div class="col-md-8">
                        <button name="filter_submit" value="filter_by" class="btn btn-primary pull-right" style="position: absolute; z-index: 9; height:38px; right:15px;"><i class="glyphicon glyphicon-search"></i></button>
                        <input type="text" name="filter_file_no" class="search-query form-control" placeholder="File No." id="filter_file_no" value="<?php echo ((isset($filter_file_no))?$filter_file_no:''); ?>">
                    </div>                    
                </form>
            </div>
            <div class="col-md-5 text-right">
                <a href="javascript:printContent('staging_list');">
                    <img src="<?php echo base_url(); ?>img/print.png" width="24px" border="0" title="Print" alt="Print" />
                </a>
            </div>
            <div class="clearfix"></div>
            <table id="staging_list" class="table table-striped table-bordered bootstrap-datatable datatable responsive">
                <thead>
                    <tr>
                        <th class="<?php echo (($sortby=='file_number' && $order == 'desc')?'descending':'ascending'); ?>"><a href="<?php echo $current_url; ?>&sortby=file_number&order=<?php echo (($sortby=='file_number')?(($order == 'desc')?'asc':'desc'):'asc'); ?>">File No.</a></th>
                                <th class="<?php echo (($sortby=='barcode_id' && $order == 'desc')?'descending':'ascending'); ?>"><a href="<?php echo $current_url; ?>&sortby=barcode_id&order=<?php echo (($sortby=='barcode_id')?(($order == 'desc')?'asc':'desc'):'asc'); ?>">File Details</a></th>
                        <th class="vcenter">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $norecords = TRUE;
                    foreach ($fileRecordSet as $record):
                        if (isset($record->id)):
                            ?>
                            <tr>
                                <td class="view" data-id="<?php echo $record->id ?>"><?= $record->file_number ?></td>
                                <td width="300">
                                    <!-- <div><b>Name:</b> <?php //echo $record->file_name ?></div> -->
                                    <div><b>File ID:</b> <?php echo (($record->barcode_id!='')?preg_replace('/-.*/', '', $record->barcode_id):'--'); ?></div>
                                    <div class="word_break"><b>Subject:</b> <?php echo $record->file_subject ?></div>
                                    <div><b>Created:</b> <?php
                                        echo
                                        date('d-m-y',
                                                strtotime($record->file_created_date))
                                        ?></div>
                                    <div  
                                        class="<?php echo record_manager_status_color($record->record_manager_status_id)?>"
                                        data-original-title="<?php echo (empty($record->comment)) ? 'No Comments.' : $record->comment ?>" 
                                        data-toggle="tooltip">
                                                <?php echo (empty($record->status_name)) ? "--" : $record->status_name; ?>
                                    </div>
                                </td>
                                <td class="vcenter">
                                    <a href="#" class="file_track"><i class="glyphicon glyphicon-map-marker" data-original-title="Track" data-toggle="tooltip"></i></a>&nbsp;&nbsp;|
                                    <?php if ($login_type == 1 || $login_type == USER_TYPE_RECORD_MANAGER): ?>
                                        <a href="#" class="print_barcode"><i class="glyphicon glyphicon-print" data-original-title="Print" data-toggle="tooltip"></i></a>&nbsp;&nbsp;|
                                    <?php endif; ?>
                                    <?php if (checkFileModifyPermission($record->id) && checkFileStatusPermission($record->id)): ?>
                                        <a href="#" class="edit"><i class="glyphicon glyphicon-edit" data-original-title="Edit" data-toggle="tooltip"></i></a>&nbsp;&nbsp;|
                                        <a href="#" class="clone"><i class="glyphicon glyphicon-retweet" data-original-title="Clone" data-toggle="tooltip"></i></a>&nbsp;&nbsp;|
                                        <a href="#" class="delete"><i class="glyphicon glyphicon-remove-circle" data-original-title="Delete" data-toggle="tooltip"></i></a>
                                    <?php else: ?>
                                        <a href="#"><i class="glyphicon glyphicon-lock" data-original-title="Can't Edit" data-toggle="tooltip"></i></a>&nbsp;&nbsp;|
                                        <a href="#"><i class="glyphicon glyphicon-lock" data-original-title="Can't Clone" data-toggle="tooltip"></i></a>&nbsp;&nbsp;|
                                        <a href="#"><i class="glyphicon glyphicon-lock" data-original-title="Can't Delete" data-toggle="tooltip"></i></a>
                                    <?php endif; ?>
                                    <?php if (!empty($record_manager_status)) { ?>
                                        &nbsp;&nbsp;|
                                        <a href="#" class="record_status" data-target="#file_record_status_modal" data-toggle="modal">
                                            <i class="glyphicon glyphicon-tasks" data-original-title="Status" data-toggle="tooltip">

                                            </i>
                                        </a>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php
                        elseif ($norecords):
                            $norecords = FALSE
                            ?>

                            <tr><td colspan='100%'>No Files Found</td></tr>
                            <?php
                        endif;
                    endforeach;
                    ?>
                </tbody>
            </table>
            <div class="col-md-12">
                <?= $this->pagination->create_links(); ?>
            </div>
            <div class="clearfix"></div>
        </div>

    </div>
</div>

<!-- FILE TRACKING START -->
<div class="modal fade bs-example-modal-lg" id="file_track_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                <h3 class="modal-title text-center" id="myModalLabel">File Tracking</h3>

            </div>
            <div class="modal-body">
                <div id="ajaxFileTrack"></div>
            </div>   
        </div>
    </div>
</div>
<!-- FILE TRACKING END -->

<!-- EDIT START -->

<div class="modal fade" id="file_edit_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title text-center" id="myModalLabel">File Edit</h3>
            </div>
            <div class="modal-body">
                <form id="file_edit_form" action="?c=fts&m=edit_file_data" method="post" class="form-horizontal">
                    <input id="edit_file_pk" name="edit_file_pk" class="form-control" type="hidden">
                    <div class="form-group">
                        <!--
                        <div class="col-sm-6">
                            <label class="form_field_label" for="edit_file_name">File Name</label>
                            <input id="edit_file_name" name="edit_file_name" class="form-control" type="text" readonly="true" placeholder="File Name">
                        </div>
                        -->
                        <div class="col-sm-6">
                            <label class="form_field_label" for="edit_barcode_id">File ID</label>
                            <input id="edit_barcode_id" name="edit_barcode_id" class="form-control" type="text" readonly="true" placeholder="File ID">
                        </div>
                        <div class="col-sm-6">
                            <label class="form_field_label" for="edit_file_number">File No.</label>
                            <input id="edit_file_number" name="edit_file_number" class="form-control" type="text" placeholder="File No.">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="form_field_label" for="edit_file_subject">File Subject</label>
                            <input id="edit_file_subject" name="edit_file_subject" class="form-control" type="text" placeholder="File Subject">
                        </div>
                        <div class="col-sm-6">
                            <label class="form_field_label" for="edit_file_subject_hindi">File Subject Hindi</label>
                            <input id="edit_file_subject_hindi" name="edit_file_subject_hindi" class="form-control" type="text" placeholder="File Subject Hindi">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="form_field_label" for="edit_file_department">Department</label>
                            <input id="edit_file_department" name="edit_file_department" class="form-control" type="text" placeholder="Department">
                        </div>

                        <div class="col-sm-6">
                            <label class="form_field_label" for="edit_file_department_hindi">Department Hindi</label>
                            <input id="edit_file_department_hindi" name="edit_file_department_hindi" class="form-control" type="text" placeholder="Department Hindi">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="form_field_label" for="edit_file_volume">Volume</label>
                            <select class="form-control" id="edit_file_volume" name="edit_file_volume">
                                <option class="select-option" value="">Choose Volume </option>
                                <?php
                                foreach (array(1 => 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII',
                            'VIII', 'IX', 'X', 'XI', 'XII', 'XIII', 'XIV', 'XV') as $romanLetter):
                                    ?>
                                    <option class="select-option" value="<?php echo $romanLetter ?>"><?php echo $romanLetter ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                        <div class="col-sm-6">
                            <label class="form_field_label" for="edit_file_part_case">Part Case</label>
                            <select class="form-control" id="edit_file_part_case" name="edit_file_part_case">
                                <option class="select-option" value="">Choose Part Case </option>
                                <?php
                                foreach (range(1, 15) as $unit):
                                    ?>
                                    <option class="select-option" value="<?php echo $unit ?>"><?php echo $unit ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="form_field_label" for="edit_file_division">Division</label>
                            <?php echo $divisionid; ?>
                            <select class="form-control" id="edit_file_division" name="edit_file_division">                                
                                <?php if ($login_type == 1 || $login_type == USER_TYPE_RECORD_MANAGER): ?>
                                    <option class="select-option" value="">Choose Division </option>
                                <?php endif; ?>
                                <?php foreach ($divisions as $division): ?>
                                    <?php if (($login_type == 3 || $login_type == 4)): ?>
                                        <?php if ($division['id'] == $divisionid): ?>
                                            <option class="select-option"
                                                    value="<?php echo $division['id'] ?>"><?php echo $division['division'] ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php elseif ($login_type == 1 || $login_type == USER_TYPE_RECORD_MANAGER): ?>
                                        <option class="select-option" 
                                                value="<?php echo $division['id'] ?>"><?php echo $division['division'] ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form_field_label" for="edit_file_sub_division">Sub-Division</label>
                            <select class="form-control" id="edit_file_sub_division" name="edit_file_sub_division">
                                <option class="select-option" value="">Choose Sub-Division </option>
                            </select>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label class="form_field_label" for="edit_file_unit">File Unit</label>
                            <input id="edit_file_unit" name="edit_file_unit" class="form-control" type="text" readonly="" placeholder="File Unit">
                        </div>
                    </div>
                    <div class="clearfix"></div>                    
                    <div class="form-group col-sm-6" id="edit_button_pro">
                        <label class="form_field_label" for="edit_file_contact_email">Contact Email</label>
                        <div id="edit_addMoreEmail_1">
                            <input class="form-control contact_email" id="edit_file_contact_email" name="edit_file_contact_email" placeholder="Contact Email" type="text">
                            <img class="add right" src="<?php echo base_url(); ?>img/add.png">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="form_field_label" for="edit_file_date_opened">File Date Opened</label>
                            <input id="edit_file_date_opened" name="edit_file_date_opened" class="form-control" type="text" placeholder="File Date Opened" style="background: url(img/calendar.gif) no-repeat scroll 10px 10px; cursor:pointer; background-position:right 10px top 10px;background-color:#ffffff !important; opacity:1 !important;">
                        </div>
                        <div class="col-sm-6">
                            <label class="form_field_label" for="edit_file_date_closed">File Date Closed</label>
                            <input id="edit_file_date_closed" name="edit_file_date_closed" class="form-control" type="text" placeholder="File Date Closed" style="background: url(img/calendar.gif) no-repeat scroll 10px 10px; cursor:pointer; background-position:right 10px top 10px;background-color:#ffffff !important; opacity:1 !important;">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="form_field_label" for="edit_file_contact_landline">Contact Landline</label>
                            <input id="edit_file_contact_landline" name="edit_file_contact_landline" class="form-control" type="text" placeholder="Contact Landline">
                        </div>


                        <div class="col-sm-6">
                            <label class="form_field_label" for="edit_file_contact_mobile">Contact Mobile</label>
                            <input id="edit_file_contact_mobile" name="edit_file_contact_mobile" class="form-control" type="text" placeholder="Contact Mobile">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="form_field_label" for="edit_file_tags">File Tags</label>
                            <input id="edit_file_tags" name="edit_file_tags" class="form-control" type="text" placeholder="File Tags">
                        </div>

                        <div class="col-sm-6">
                            <label class="form_field_label" for="edit_file_category">File Category</label>
                            <input id="edit_file_category" name="edit_file_category" class="form-control" type="text" placeholder="File Category">
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form_field_label" for="edit_office_purpose">Office Purpose</label>
                        <textarea class="form-control" id="edit_office_purpose" name="edit_office_purpose" placeholder="Office Purpose" ></textarea>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <div class="col-sm-12" style="text-align:center;">
                            <button value="edit" class="btn btn-info" id="file_edit_button">Save Changes</button>&nbsp; &nbsp;
                            <button class="btn btn-default" id="resetbutton" type="button" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>   
        </div>
    </div>
</div>

<!-- EDIT END -->
<!-- Start Clone -->

<div class="modal fade" id="file_clone_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title text-center" id="myModalLabel">File Clone</h3>
            </div>
            <div class="modal-body">
                <form id="file_clone_form" action="?c=fts&m=clone_file_data" method="post" class="form-horizontal">
                    <input id="clone_file_pk" name="clone_file_pk" class="form-control" type="hidden">
                    <div class="form-group">
                        <!--
                        <div class="col-sm-6">
                            <label class="form_field_label" for="clone_file_name">File Name</label>
                            <input id="clone_file_name" name="clone_file_name" class="form-control" type="text"  placeholder="File Name">
                        </div>
                        -->
                        <div class="col-sm-6">
                            <label class="form_field_label" for="clone_barcode_id">File ID</label>
                            <input id="clone_barcode_id" name="clone_barcode_id" class="form-control" type="text" readonly="true" placeholder="File ID">
                        </div>
                        <div class="col-sm-6">
                            <label class="form_field_label" for="clone_file_no">File No.</label>
                            <input id="clone_file_no" name="clone_file_no" class="form-control" type="text"  placeholder="File No.">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="form_field_label" for="clone_file_subject">File Subject</label>
                            <input id="clone_file_subject" name="clone_file_subject" class="form-control" type="text" placeholder="File Subject">
                        </div>

                        <div class="col-sm-6">
                            <label class="form_field_label" for="clone_file_subject_hindi">File Subject Hindi</label>
                            <input id="clone_file_subject_hindi" name="clone_file_subject_hindi" class="form-control" type="text" placeholder="File Subject Hindi">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="form_field_label" for="clone_file_department">Department</label>
                            <input id="clone_file_department" name="clone_file_department" class="form-control" type="text" placeholder="Department">
                        </div>

                        <div class="col-sm-6">
                            <label class="form_field_label" for="clone_file_department_hindi">Department Hindi</label>
                            <input id="clone_file_department_hindi" name="clone_file_department_hindi" class="form-control" type="text" placeholder="Department Hindi">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="form_field_label" for="clone_file_volume">Volume</label>
                            <select class="form-control" id="clone_file_volume" name="clone_file_volume">
                                <option class="select-option" value="">Choose Volume </option>
                                <?php
                                foreach (array(1 => 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII',
                            'VIII', 'IX', 'X', 'XI', 'XII', 'XIII', 'XIV', 'XV') as $romanLetter):
                                    ?>
                                    <option class="select-option" value="<?php echo $romanLetter ?>"><?php echo $romanLetter ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                        <div class="col-sm-6">
                            <label class="form_field_label" for="clone_file_part_case">Part Case</label>
                            <select class="form-control" id="clone_file_part_case" name="clone_file_part_case">
                                <option class="select-option" value="">Choose Part Case </option>
                                <?php
                                foreach (range(1, 15) as $unit):
                                    ?>
                                    <option class="select-option" value="<?php echo $unit ?>"><?php echo $unit ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="form_field_label" for="clone_file_division">Division</label>
                            <select class="form-control" id="clone_file_division" name="clone_file_division">
                                <?php if ($login_type == 1 || $login_type == USER_TYPE_RECORD_MANAGER): ?>
                                    <option class="select-option" value="">Choose Division </option>
                                <?php endif; ?>
                                <?php foreach ($divisions as $division): ?>
                                    <?php if (($login_type == 3 || $login_type == 4)): ?>
                                        <?php if ($division['id'] == $divisionid): ?>
                                            <option class="select-option"
                                                    value="<?php echo $division['id'] ?>"><?php echo $division['division'] ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php elseif ($login_type == 1 || $login_type == USER_TYPE_RECORD_MANAGER): ?>
                                        <option class="select-option" 
                                                value="<?php echo $division['id'] ?>"><?php echo $division['division'] ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form_field_label" for="clone_file_sub_division">Sub-Division</label>
                            <select class="form-control" id="clone_file_sub_division" name="clone_file_sub_division">
                                <option class="select-option" value="">Choose Sub-Division </option>
                            </select>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label class="form_field_label" for="clone_file_unit">File Unit</label>
                            <input id="clone_file_unit" name="clone_file_unit" class="form-control" type="text"  placeholder="File Unit" readonly>
                        </div>
                    </div>
                    <div class="clearfix"></div>                    
                    <div class="form-group col-sm-6" id="clone_button_pro">
                        <label class="form_field_label" for="clone_file_contact_email">Contact Email</label>
                        <div id="clone_addMoreEmail_1">
                            <input class="form-control contact_email" id="clone_file_contact_email" name="clone_file_contact_email" placeholder="Contact Email" type="text">
                            <img class="add right" src="<?php echo base_url(); ?>img/add.png">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="form_field_label" for="clone_file_date_opened">File Date Opened</label>
                            <input class="form-control" id="clone_file_date_opened" name="clone_file_date_opened" placeholder="File Date Opened" type="text" style="background: url(img/calendar.gif) no-repeat scroll 10px 10px; cursor:pointer; background-position:right 10px top 10px;background-color:#ffffff !important; opacity:1 !important;">
                        </div>
                        <div class="col-sm-6">
                            <label class="form_field_label" for="clone_file_date_closed">File Date Closed</label>
                            <input class="form-control" id="clone_file_date_closed" name="clone_file_date_closed" placeholder="File Date Closed" type="text" style="background: url(img/calendar.gif) no-repeat scroll 10px 10px; cursor:pointer; background-position:right 10px top 10px;background-color:#ffffff !important; opacity:1 !important;">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">

                        <div class="col-sm-6">
                            <label class="form_field_label" for="clone_file_contact_mobile">Contact Email</label>
                            <input id="clone_file_contact_mobile" name="clone_file_contact_mobile" class="form-control" type="text" placeholder="Contact Mobile">
                        </div>

                        <div class="col-sm-6">
                            <label class="form_field_label" for="clone_file_contact_landline">Contact Landline</label>
                            <input id="clone_file_contact_landline" name="clone_file_contact_landline" class="form-control" type="text" placeholder="Contact Landline">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="form_field_label" for="clone_file_tags">File Tags</label>
                            <input id="clone_file_tags" name="clone_file_tags" class="form-control" type="text" placeholder="File Tags">
                        </div>
                        <div class="col-sm-6">
                            <label class="form_field_label" for="clone_file_category">File Category</label>
                            <input id="clone_file_category" name="clone_file_category" class="form-control" type="text" placeholder="File Category">
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form_field_label" for="clone_office_purpose">Office Purpose</label>
                        <textarea class="form-control" id="clone_office_purpose" name="clone_office_purpose" placeholder="Office Purpose" ></textarea>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <div class="col-sm-12" style="text-align:center;">
                            <button value="Clone File" class="btn btn-info" id="file_clone_button">Clone File</button>&nbsp; &nbsp;
                            <button class="btn btn-default" id="resetbutton" type="button" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>   
        </div>
    </div>
</div>



<!-- End Clone -->


<!-- FILE DELETE START -->
<div class="modal fade confirmation-modal" id="file_delete_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title text-center" id="myModalLabel">File Delete</h3>
                
            </div>
            <div class="modal-body">
                <h5 id="file_heading"></h5>
                Are you sure that you really want to delete this file?
                <div class="clearfix remember"></div>
                <div class="form-group remember">
                    <label class="form_field_label" for="delete_comments">Please type your comments into the below box</label>
                    <textarea class="form-control" id="delete_comments" name="delete_comments" placeholder="Add a comments" ></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="confirm btn btn-primary" type="button" data-dismiss="modal">
                    Yes
                </button>
                <button class="cancel btn btn-default" type="button" data-dismiss="modal">
                    No
                </button>
            </div>
        </div>
    </div>
</div>
<!-- FILE DELETE END -->

<!-- FILE RECORD STATUS START -->
<?php
if (!empty($record_manager_status)) {
    ?>
    <div class="modal fade" id="file_record_status_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title text-center" id="myModalLabel">File Record Status</h3>

                </div>
                <div class="modal-body">
                    <form novalidate="novalidate" id="record_status_form" role="form" method="post">
                        <div class="form-group">
                            <div class="col-sm-4">
                                <select class="form-control" id="record_status_name" name="record_status_name">
                                    <option class="select-option" value="">Choose Status</option>
                                    <?php foreach ($record_manager_status as $row) { ?>
                                        <option class="select-option" 
                                                value="<?php echo $row->id ?>"
                                                ><?php echo $row->status_name ?></option>
                                            <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <textarea class="form-control" id="record_status_comment" name="record_status_comment" placeholder="Record Status Comment"></textarea>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button value="update" class="btn btn-info" id="record_status_button">Update</button>
                                <button class="btn btn-default" id="resetbutton" type="button" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<!-- FILE RECORD STATUS END -->

<!-- MESSAGE FLASH START -->
<div id="out_msg" class="modal fade">
    <div class="modal-dialog">

        <!-- dialog body -->
        <div class="modal-content ">
            <div class="p-t-20 p-b-20">
                <p class="text-center">
                    <span id="out_msg_cont"></span>
                </p>
                <div class="text-center">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- MESSAGE FLASH END -->
<!-- FILE VIEW START -->
<?php require_once (PLUGIN_VIEW_PATH . 'partial/view_file_detail_modal.php') ?>
<!-- FILE VIEW END -->
<!-- FILE LABEL PRINT CONFIRMATION START -->
<?php require_once (PLUGIN_VIEW_PATH . 'partial/print_label_confirmation_modal.php') ?>
<!-- FILE LABEL PRINT CONFIRMATION END -->
<script>
    var base_url = '<?php echo base_url(); ?>';

    $(function () {

        $('#box_create_file .btn-minimize').click(function (e) {
            var $target = $(this).parent().parent().next('.box-content');
            if ($target.is(':visible')) {
                $('#box_create_file').removeClass('col-md-5');
                $('#box_create_file').addClass('col-md-3');
                $('#box_file_list').removeClass('col-md-7');
                $('#box_file_list').addClass('col-md-9');

            }
            else {

                $('#box_file_list').removeClass('col-md-9');
                $('#box_file_list').addClass('col-md-7');

                $('#box_create_file').removeClass('col-md-3');
                $('#box_create_file').addClass('col-md-5');

            }
        });

        //DATE PICKER
        $("#file_date_opened, #file_date_closed, #clone_file_date_opened, #clone_file_date_closed, #edit_file_date_opened, #edit_file_date_closed").datepicker({dateFormat: 'dd-mm-yy'});
        //clone form validate

        $("#file_clone_form").validate({
            rules: {
                clone_file_name: {
                    required: true
                },
                clone_file_no: {
                    required: true,
                    maxlength: 6
                },
                clone_file_subject: {
                    required: true
                },
                clone_file_contact_email: {
                    required: true,
                    email: true
                },
                'clone_file_additional_contact_email[]': {
                    email: true
                },
                clone_file_department: {
                    required: true
                },
                clone_file_volume: {
                    required: true
                },
                clone_file_division: {
                    required: true
                }
            },
            messages: {
                clone_file_name: {
                    required: "Please enter file name"
                },
                clone_file_no: {
                    required: "Please enter file number",
                    remote: "File number already in use"
                },
                clone_file_subject: {
                    required: "Please enter file subject"
                },
                clone_file_contact_email: {
                    required: "Please enter the email",
                    email: "Please enter valid email"
                },
                clone_file_department: {
                    required: "Please enter the department name"
                },
                clone_file_volume: {
                    required: "Please select a volume"
                },
                clone_file_division: {
                    required: "Please select a division"
                }
            },
            submitHandler: function (form, event) {

                form.submit()
            }
        });
        //End clone form validation
        //FORM
        $("#add_file_data").validate({
            rules: {
                file_name: {
                    required: true
                },
                file_no: {
                    required: true,
                    maxlength: 6
                },
                file_subject: {
                    required: true
                },
                file_contact_email: {
                    required: true,
                    email: true
                },
                'file_additional_contact_email[]': {
                    email: true
                },
                department: {
                    required: true
                },
                volume: {
                    required: true
                },
                division: {
                    required: true
                }
            },
            messages: {
                file_name: {
                    required: "Please enter file name"
                },
                file_no: {
                    required: "Please enter file number",
                    remote: "File number already in use"
                },
                file_subject: {
                    required: "Please enter file subject"
                },
                file_contact_email: {
                    required: "Please enter the email",
                    email: "Please enter valid email"
                },
                department: {
                    required: "Please enter the department name"
                },
                volume: {
                    required: "Please select a volume"
                },
                division: {
                    required: "Please select a division"
                }
            },
            submitHandler: function (form, event) {
                event.preventDefault();

                $.ajax({
                    url: $('#add_file_data').attr('action'),
                    type: 'post',
                    data: $('#add_file_data').serialize(),
                    success: function (serverData) {

                        var data = JSON.parse(serverData);

                        if (data.hasOwnProperty('base_url')) {
                            window.location.href = data.base_url;
                        }

                        if (data.submit_button_name === 'entry_submit') {
                            document.getElementById('add_file_data').reset();
                        } else if (data.submit_button_name === 'entry_submit_barcode') {
                            var file_id = data.id;
                            printCoverPageLabel(file_id);
                            document.getElementById('add_file_data').reset();
                        }
                        location.reload();

                        $('#out_msg_cont').html('File saved successfully.');
                        $('#out_msg').modal('show');
                        setTimeout(function () {
                            $('#out_msg').modal('hide')
                        }, 4000);
                    }
                });

            }
        });

        //BARCODE PRINT IN LIST TAB
        $('.print_barcode').on('click', function () {
            var fileID = $(this).parent().siblings(':first').data('id');
            $("#file_print_option_modal .btn-primary").data('file_id', fileID);
            $("#file_print_option_modal").modal('show');
        });
                
        //BARCODE MODAL CLOSE RELOAD PAGE
        $('#barcode_modal').on('hidden.bs.modal', function () {
            document.getElementById('add_file_data').reset();
            location.reload();
        });

        //BARCODE MODAL CLOSE RELOAD PAGE
        $('#file_edit_modal').on('hidden.bs.modal', function () {
            document.getElementById('file_edit_form').reset();
            //location.reload();
        });
        $('#file_clone_modal').on('hidden.bs.modal', function () {
            document.getElementById('file_clone_form').reset();
            //location.reload();
        });
        //SUB-DIVISION
        $(document).on('change', '#division', function () {
            loadSubDivision($(this).val());
        });

        loadSubDivision($('#division').val());

        function loadSubDivision(divisionId) {

            $.ajax({
                url: "?c=fts&m=get_subdivision",
                type: 'POST',
                data: {division_id: divisionId},
                dataType: 'JSON',
                success: function (serverData) {
                    var length = serverData.length;
                    var innerHtml =
                            '<option class="select-option" value="">' +
                            'Choose Sub-Division </option>';

                    for (var i = 0; i < length; i++) {
                        innerHtml +=
                                '<option class="select-option" value="' +
                                serverData[i].id + '">' +
                                serverData[i].subdivision + '</option>';
                    }

                    $('#sub_division').html(innerHtml);
                }
            });

        }

        $(document).on('change', '#edit_file_division', function () {
            loadSubDivisionId($(this).val(), '', '#edit_file_sub_division');
        });
        
        /*
         Clone Select Division populate Subdivision
         */

        $(document).on('change', '#clone_file_division', function () {
            $.ajax({
                url: "?c=fts&m=get_subdivision",
                type: 'POST',
                data: {division_id: $(this).val()},
                dataType: 'JSON',
                success: function (serverData) {
                    var length = serverData.length;
                    var innerHtml =
                            '<option class="select-option" value="">' +
                            'Choose Sub-Division </option>';

                    for (var i = 0; i < length; i++) {
                        innerHtml +=
                                '<option class="select-option" value="' +
                                serverData[i].id + '">' +
                                serverData[i].subdivision + '</option>';
                    }

                    $('#clone_file_sub_division').html(innerHtml);
                }
            });
        });

        //FILE TRACK
        $('.file_track').on('click', function () {

            var file_number = $(this).parent().siblings(':first').data('id');

            //innerHtmlFileTrack(file_number);
            ajaxHtmlFileTrack(file_number);
        });

        //FILE EDIT
        $('#staging_list .edit').on('click', function () {
            var file_number = $(this).parent().siblings(':first').data('id');

            editFileDetail(file_number);

        });
        $('#staging_list .clone').on('click', function () {
            var file_number = $(this).parent().siblings(':first').data('id');

            cloneFileDetail(file_number);

        });

        //FILE DELETE
        $('#staging_list .delete').on('click', function () {
            var fileID = $(this).parent().siblings(':first').data('id');
            $("#file_delete_modal .btn-primary").data('file_id', fileID);
            //MODEL POP-UP
            $("#file_delete_modal").modal('show');
        });
        $("#file_delete_modal .btn-primary").on('click', function () {
            //deleteFileDetail($(this).data('file_id'));
            softDeleteFileDetail($(this).data('file_id'), $("#file_delete_modal #delete_comments").val());
        });

        //FILE VIEW DETAILS
        $('#staging_list .view').on('click', function () {
            var fileID = $(this).data('id');
            viewFileDetail(fileID);
        });
        
        //RECORD STATUS
        $('.record_status').on('click', function () {
            var filePK = $(this).parent().siblings(':first').data('id');

            updateRecordStatus(filePK);

        });
    });
</script>