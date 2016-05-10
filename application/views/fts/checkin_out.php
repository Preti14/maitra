<script>

var ajaxReq    = null;  
var searchword = "";
$(document).ready(function() {

	$("#file_barcode").keyup(function(){
		
		searchword = $(this).val();
		if((searchword.length) > 3) {
			clearTimeout(timer);
			var timer = setTimeout(function(){
				if (ajaxReq != null) ajaxReq.abort();
				var form_data = { barcode:searchword };
				var ajaxReq   = $.ajax({
									url: "<?=base_url()?>?c=fts&m=get_file_details",
									type: 'POST',
									data: form_data,
									dataType:"json",
									success: function(data){
									   var res = JSON.stringify(data);
									   var objr= $.parseJSON(res);
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
                <form role="form" id="check_doc" name="check_doc" method="post">
                    
                                                     
                    <div class="form-group">
                        <label for="alias">Scan Code: </label>
                        <input type="text" class="form-control" id="file_barcode" name="file_barcode" placeholder="File Mirco QR Code">
                    <span style="color:#F00;" id="err_msg"></span>    
                    </div> 
             		<div class="form-group" id="file_details" style="visibility:hidden">
                    <div>File ID:</div><div id="txt_file_id"></div>
                    <div>File Name:</div><div id="txt_file_name"></div>
                    <div>Status:</div><div id="txt_file_status"></div>
                    </div>
                    <button class="btn btn-primary" id="btn_check_doc" style="visibility:hidden">Check Out</button>
                    
                </form>

            </div>
        </div>
    </div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>
