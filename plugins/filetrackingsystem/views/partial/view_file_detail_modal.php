<!-- FILE VIEW START -->

<div class="modal fade" id="file_view_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title text-center" id="myModalLabel">File Detail</h3>
            </div>
            <div class="modal-body">
                    <div class="form-group">
                        <!--
                        <div class="col-sm-6">
                            <label class="form_field_label" for="view_file_name">File Name</label>
                            <input id="view_file_name" name="view_file_name" class="form-control" type="text" readonly placeholder="File Name">
                        </div>
                        -->
                        <div class="col-sm-6">
                            <label class="form_field_label" for="view_barcode_id">File ID</label>
                            <input id="view_barcode_id" name="view_barcode_id" class="form-control" type="text" readonly placeholder="File ID">
                        </div>
                        <div class="col-sm-6">
                            <label class="form_field_label" for="view_file_number">File No.</label>
                            <input id="view_file_number" name="view_file_number" class="form-control" type="text" readonly placeholder="File No.">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="form_field_label" for="view_file_subject">File Subject</label>
                            <input id="view_file_subject" name="view_file_subject" class="form-control" type="text" readonly placeholder="File Subject">
                        </div>
                        <div class="col-sm-6">
                            <label class="form_field_label" for="view_file_subject_hindi">File Subject Hindi</label>
                            <input id="view_file_subject_hindi" name="view_file_subject_hindi" class="form-control" type="text" readonly placeholder="File Subject Hindi">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="form_field_label" for="view_file_department">Department</label>
                            <input id="view_file_department" name="view_file_department" class="form-control" type="text" readonly placeholder="Department">
                        </div>

                        <div class="col-sm-6">
                            <label class="form_field_label" for="view_file_department_hindi">Department Hindi</label>
                            <input id="view_file_department_hindi" name="view_file_department_hindi" class="form-control" type="text" readonly placeholder="Department Hindi">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="form_field_label" for="view_file_volume">Volume</label>
                            <input id="view_file_volume" name="view_file_volume" class="form-control" type="text" readonly placeholder="Volume">
                        </div>
                        <div class="col-sm-6">
                            <label class="form_field_label" for="view_file_part_case">Part Case</label>
                            <input id="view_file_part_case" name="view_file_part_case" class="form-control" type="text" readonly placeholder="Part Case">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="form_field_label" for="view_file_division">Division</label>
                            <input id="view_file_division" name="view_file_division" class="form-control" type="text" readonly placeholder="Division">
                        </div>
                        <div class="col-sm-6">
                            <label class="form_field_label" for="view_file_sub_division">Sub-Division</label>
                            <input id="view_file_sub_division" name="view_file_sub_division" class="form-control" type="text" readonly placeholder="Sub-Division">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label class="form_field_label" for="view_file_unit">File Unit</label>
                            <input id="view_file_unit" name="view_file_unit" class="form-control" type="text" readonly placeholder="File Unit">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <div class="col-sm-6" id="view_button_pro">
                            <div id="view_addMoreEmail_1">
                                <label class="form_field_label" for="view_file_contact_email">Contact Email</label>
                                <input id="view_file_contact_email" name="edit_file_contact_email" class="form-control" type="text" readonly placeholder="Contact Email">
                            </div>
                        </div>
                        <div class="col-sm-6">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="form_field_label" for="view_file_date_opened">File Date Opened</label>
                            <input id="view_file_date_opened" name="view_file_date_opened" class="form-control" type="text" readonly placeholder="File Date Opened">
                        </div>
                        <div class="col-sm-6">
                            <label class="form_field_label" for="view_file_date_closed">File Date Closed</label>
                            <input id="view_file_date_closed" name="view_file_date_closed" class="form-control" type="text" readonly placeholder="File Date Closed">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="form_field_label" for="view_file_contact_landline">Contact Landline</label>
                            <input id="view_file_contact_landline" name="view_file_contact_landline" class="form-control" type="text" readonly placeholder="Contact Landline">
                        </div>


                        <div class="col-sm-6">
                            <label class="form_field_label" for="view_file_contact_mobile">Contact Mobile</label>
                            <input id="view_file_contact_mobile" name="view_file_contact_mobile" class="form-control" type="text" readonly placeholder="Contact Mobile">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="form_field_label" for="view_file_tags">File Tags</label>
                            <input id="view_file_tags" name="view_file_tags" class="form-control" type="text" readonly placeholder="File Tags">
                        </div>

                        <div class="col-sm-6">
                            <label class="form_field_label" for="view_file_category">File Category</label>
                            <input id="view_file_category" name="view_file_category" class="form-control" type="text" readonly placeholder="File Category">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-12">
                        <label class="form_field_label" for="view_office_purpose">Office Purpose</label>
                        <textarea class="form-control" id="view_office_purpose" name="view_office_purpose" readonly="" placeholder="Office Purpose" ></textarea>
                    </div>
                    <div class="clearfix"></div>
            </div>   
        </div>
    </div>
</div>

<!-- FILE VIEW END -->