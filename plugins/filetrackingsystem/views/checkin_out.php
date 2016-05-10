<script type="text/javascript" src="<?= base_url(); ?>js/fts.js"></script>
<script>

    window.onload = function () {
        document.getElementById('file_barcode').focus();
    }

    var ajaxReq = null;
    var searchword = "";
    $(document).ready(function () {
        $("#check_doc").validate();
        
        $("#file_barcode").keyup(function () {

            $("#txt_file_id").html('');
            //$("#txt_file_name").html('');
            $("#txt_file_date").html('');
            $("#txt_file_user").html('');
            $("#txt_division").html('');
            $("#file_id").val('');
            $("#file_details").attr('style', 'visibility:hidden;');
            $("#btn_check_doc").attr('style', 'visibility:hidden;');
            $('#file_track_table').hide();
            $('#ajaxFileTrack').hide();
            $("#transit_purpose").hide();
            $("#transit_purpose_id").val('');
            $( "#transit_purpose_id" ).rules( "remove" );

            searchword = $(this).val();
            if ((searchword.length) > 1) {
                //clearTimeout(timer);
                setTimeout(function () {
                    if (ajaxReq != null)
                        ajaxReq.abort();
                    var form_data = {barcode: searchword};
                    var ajaxReq = $.ajax({
                        url: "<?= base_url() ?>?c=fts&m=get_file_details",
                        type: 'POST',
                        data: form_data,
                        dataType: "json",
                        success: function (data) {
                            var res = JSON.stringify(data);
                            var objr = $.parseJSON(res);

                            if (objr['file_id'] === undefined) {
                                $("#check_in_out_error").attr('style', 'visibility:visible;');
                            } else {
                                $('#file_track_table tbody').html('');
                                //innerHtmlFileTrack(objr['file_id']);
                                ajaxHtmlFileTrack(objr['file_id']);
                                if(objr['transit_purpose']=='1') {
                                    $("#transit_purpose").show();
                                    $( "#transit_purpose_id" ).rules( "add", {
                                        required: true,
                                    });
                                }
                                $('#ajaxFileTrack').show();
                                //$('#file_track_table').show();
                                $("#txt_file_id").html(objr['file_no']);
                            //$("#txt_file_name").html(objr['file_name'] + " Vol:" + objr['file_vol']);
                                $("#txt_file_date").html(objr['created_date']);
                                $("#txt_file_user").html(objr['file_user']);
                                $("#txt_division").html(objr['file_division']);
                                $('#txt_sub_division').html(objr['file_sub_division']);
                                $("#file_id").val(objr['file_id']);
                                $("#file_details").attr('style', 'visibility:visible;');
                                $("#check_status").val(objr['check_status']);
                                $('#file_owner_email').val(objr['file_contact_email']);
                                if (objr['check_status'] == 1) {

                                    $("#btn_check_doc").text("Check In");
                                    $("#btn_check_doc").attr('style', 'visibility:visible;');
                                }
                                else {

                                    $("#btn_check_doc").text("Check Out");
                                    $("#btn_check_doc").attr('style', 'visibility:visible;');

                                }
                                $("#check_in_out_error").attr('style', 'visibility:hidden;');
                            }
                        }
                    });
                }, 3500000000000);
            }
        });
    });
</script>
<div class="box col-md-6">
    <div class="box-inner">
        <div class="box-header well" data-original-title="" style="padding:6px;">
            <h2><i class=""></i> Check In / Check Out</h2>

            <div class="box-icon">
                <a href="#" class="btn btn-minimize btn-round btn-default" style="padding-top:3px;"><i class="glyphicon glyphicon-chevron-up"></i></a>                   
            </div>
        </div>
        <div class="box-content">
            <form role="form" id="check_doc" name="check_doc" method="post" action="?c=fts&m=file_check_submit">


                <div class="form-group">
                    <label for="alias">Scan Code: </label>
                    <input type="text" class="form-control" id="file_barcode" name="file_barcode" placeholder="File Mirco QR Code">
                    <span style="color:#F00;" id="err_msg"></span>    
                </div> 
                <div class="form-group" id="transit_purpose" style="display: none;">
                    <label for="transit_purpose_id">Purpose: </label>
                    <select class="form-control" id="transit_purpose_id" name="transit_purpose_id">
                        <option class="select-option" value="">Choose Purpose </option>
                    <?php foreach ($transit_purposes as $transit_purpose): ?>
                        <option class="select-option"
                                value="<?php echo $transit_purpose->id; ?>"><?php echo $transit_purpose->purpose; ?>
                        </option>
                    <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" id="file_details" style="visibility:hidden">
                    <!-- <div class="col-sm-4 control-label"><strong>File Name:</strong></div><div id="txt_file_name"></div> -->
                    <div class="col-sm-4 control-label"><strong>File ID:</strong></div><div id="txt_file_id"></div>
                    <div class="col-sm-4 control-label"><strong>Created Date:</strong></div><div id="txt_file_date"></div>
                    <div class="col-sm-4 control-label"><strong>File Owner:</strong></div><div id="txt_file_user"></div>
                    <div class="col-sm-4 control-label"><strong>Division :</strong></div><div id="txt_division"></div>
                    <div class="col-sm-4 control-label"><strong>Sub Division :</strong></div><div id="txt_sub_division"></div>
                </div>
                <label id="check_in_out_error" class="error center-block" style="visibility:hidden">Enter Correct Code</label>
                <button class="btn btn-primary" id="btn_check_doc" style="visibility:hidden">Check In</button>
                <input type="hidden" name="check_status" id="check_status">
                <input type="hidden" name="file_id" id="file_id">
                <input type="hidden" name="file_owner_email" id="file_owner_email">
            </form>

        </div>
    </div>
</div>

<!-- TRACKING START -->
<div class="box col-md-6">
    <div class="box-inner">
        <div class="box-header well" data-original-title="" style="padding:6px;">
            <h2><i class=""></i>File Tracking</h2>

            <div class="box-icon">
                <a href="#" class="btn btn-minimize btn-round btn-default" style="padding-top:3px;"><i class="glyphicon glyphicon-chevron-up"></i></a>                   
            </div>
        </div>
        <div class="box-content">            
            <div id="ajaxFileTrack"></div>
        </div>
    </div>
</div>
<!-- TRACKING END -->

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>
