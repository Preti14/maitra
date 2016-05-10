<!-- FILE LABEL PRINT CONFIRMATION START -->
<div class="modal fade confirmation-modal" id="file_print_confirmation_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title text-center" id="myModalLabel">Print Label</h3>
                <h5 id="file_heading"></h5>
            </div>
            <div class="modal-body">
                Are you sure want to print?
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
<!-- FILE LABEL PRINT CONFIRMATION END -->

<!-- FILE LABEL PRINT OPTION START -->
<div class="modal fade" id="file_print_option_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title text-center" id="myModalLabel">Select Labels:</h3>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="col-sm-12 border_bottom">
                        <input type="checkbox" id="label-all" value="all">
                        <label for="label-all">Select All</label>
                    </div>
                    <div class="col-sm-12 print_label_options">
                        <input type="checkbox" id="label-1" value="1" data-lbx="qr_code">
                        <label for="label-1">QR Code</label>
                    </div>
                    <div class="col-sm-12 print_label_options">
                        <input type="checkbox" id="label-2" value="2" data-lbx="vol_pc">
                        <label for="label-2">volume/Part Case</label>
                    </div>
                    <div class="col-sm-12 print_label_options">
                        <input type="checkbox" id="label-3" value="3" data-lbx="logo">
                        <label for="label-3">Logo</label>
                    </div>
                    <div class="col-sm-12 print_label_options">
                        <input type="checkbox" id="label-4" value="4" data-lbx="dept">
                        <label for="label-4">Department</label>
                    </div>
                    <div class="col-sm-12 print_label_options">
                        <input type="checkbox" id="label-5" value="5" data-lbx="sub">
                        <label for="label-5">Subject</label>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="modal-footer">
                <button class="confirm btn btn-primary" type="button" data-dismiss="modal">
                    Print
                </button>
                <button class="cancel btn btn-default" type="button" data-dismiss="modal">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
</div>
<!-- FILE LABEL PRINT OPTION END -->