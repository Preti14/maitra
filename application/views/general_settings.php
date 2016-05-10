<div id="success-msg" class="alert alert-success"  style="display:none"> <strong>Success: </strong>The settings has been changed.</div>
<div class="box col-md-6">
  <div class="box-inner">
    <div class="box-header well" data-original-title="">
      <h2><i class=""></i> General Settings</h2>
      <div class="box-icon"> <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a> </div>
    </div>
    <div class="box-content">
      <form role="form" id="settings_form" method="post" action="<?=base_url();?>?c=admin&m=settings" enctype="multipart/form-data">
        <?php //print_r($settings);?>
        <div class="form-group">
          <label for="site_title">Site Title : </label>
          <input type="text" class="form-control sett" id="site_title" name="site_title" placeholder="Site Title" value="<?=isset($settings['site_title'])?$settings['site_title']:'';?>">
        </div>
        <div class="form-group">
          <label for="site_logo">Site Logo : </label>
          <?php if(isset($settings['site_logo'])) { ?>
          <div style="height:auto;padding-top: 20px;"> <img src="<?=base_url();?>img/logo/<?=$settings['site_logo'];?>"/> </div>
          <?php } ?>
          <input type="file" class="sett" id="site_logo" name="site_logo">
          <span id="errlogo" style="color:#F00;"></span> </div>
        <div class="form-group">
          <label for="copyright_text">Copyright Text : </label>
          <input type="text" class="form-control sett" id="copyright_text" name="copyright_text" placeholder="Copyright Text" value="<?=isset($settings['copyright_text'])?$settings['copyright_text']:'';?>">
        </div>
        <div class="form-group">
          <label for="temporary_addr_count">Temporary Address Count : </label>
          <input type="text" class="form-control sett" id="temporary_addr_count" name="temporary_addr_count" placeholder="Temporary Address Count" value="<?=isset($settings['temporary_addr_count'])?$settings['temporary_addr_count']:'';?>">
          <span id="errmsg" style="color:#F00;"></span> </div>
        <div class="form-group">
          <label for="from_address">Home Address : </label>
          <input type="text" class="form-control sett" id="from_address" name="from_address" placeholder="Home Address" value="<?=isset($settings['from_address'])?$settings['from_address']:'';?>">
          <input type="hidden" id="from_address_id" name="from_address_id" value="<?=isset($settings['from_address_id'])?$settings['from_address_id']:'';?>">
        </div>
        <div class="form-group">
          <label for="copyright_text">No of Records : </label>
          <input type="text" class="form-control sett" id="no_of_records" name="no_of_records" placeholder="No of Records" value="<?=isset($settings['no_of_records'])?$settings['no_of_records']:'';?>">
        </div>
        
        <div class="form-group">
          <label for="footer_text">Footer Text : </label>
          <input type="text" class="form-control sett" id="footer_text" name="footer_text" placeholder="Footer Text" value="<?=isset($settings['footer_text'])?$settings['footer_text']:'';?>">
        </div> 
        <div class="form-group">
          <label for="from_address">Mail no Prefix : </label>
          <div class="clearfix"></div>
          <div class="col-md-8" style="padding-left:0px">
            <input type="text" class="form-control sett" id="mail_no_prefix" name="mail_no_prefix" placeholder="Mail no Prefix" value="<?=!empty($settings['mail_no_prefix'])?$settings['mail_no_prefix']:'14';?>" maxlength="2" autocomplete="off">
          </div>
          <div class="col-md-4" style="padding-right:0px">
            <input id="mail_no_reset" name="mail_no_reset" type="checkbox" <?php echo ($settings['mail_no_reset']==1 ? 'checked' : '');?>>
            Reset </div>
            <div class="clearfix"></div>
            <span id="errmsg_pre" style="color:#F00;"></span>
        </div>
        
        <button id="settings_submit" type="submit" class="btn btn-default" >Submit</button>
      </form>
    </div>
  </div>
</div>
<script>
$(function () {
	$(".sett").keyup(function(){
		if ($(this).val()!='' ){
			$('#settings_submit').prop("disabled", false);
		}else{
			$('#settings_submit').prop("disabled", true);
		}
	});
	
	$("#mail_no_reset").change(function(){
		$('#settings_submit').prop("disabled", true);
		if($("#mail_no_reset").prop('checked') && $("#mail_no_prefix").val()){ 
			$('#settings_submit').prop("disabled", false);
		}
	});
	
	$("#site_logo").change(function(event) {
		var fileExtension = ['jpeg', 'jpg', 'png'];
		if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
			$("#errlogo").html("Only '.jpeg','.jpg', '.png' formats are allowed").show();
		}else{
			$("#errlogo").html("Only '.jpeg','.jpg', '.png' formats are allowed").hide();
		}
	});
	
	var success = '<?=isset($success)?$success:'';?>';
	if(success!=''){
		$("#success-msg").slideDown();
		setTimeout(function(){ 
			window.location.href = '<?=base_url();?>?c=admin&m=settings';
		},2000);
	}
	
	$("#temporary_addr_count").keypress(function (e) {
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
        $("#errmsg").html("Digits Only").show().fadeOut("slow");
               return false;
    }
   });
   
   $( "#from_address" ).autocomplete({
		minLength:2,
		source: function( request, respond ) {
		$.post( "<?php echo base_url(); ?>?c=inmail&m=from_autocomp", { from: request.term },
			function( response ) { 
				respond($.parseJSON(response));
			
		});
	 }
	});
	
	$("#settings_submit").confirm({
		text: "Are you sure that you really want to continue?",
		title: "Confirmation required",
		confirm: function(button) {
			$('form#settings_form').submit();
		},
		cancel: function(button) {
			return false;
		},
		confirmButton: "Yes",
		cancelButton: "No",
		post: true
	});	
	
//	$("#mail_no_prefix").keyup(function(e) {
//        if($("#mail_no_prefix").val().length  == 2 ){
//			$.ajax({
//				url: "<?php echo base_url();?>?c=admin&m=check_mail_no",
//				type: 'POST',
//				data: "mail_no_prefix="+$("#mail_no_prefix").val(),
//				success: function(response) {
//					if(response==1){
//						$("#errmsg_pre").html("This Prefix is already used").show().fadeOut(2000);
//						setTimeout(function(){ 
//							$("#mail_no_prefix").val('');
//							$('#settings_submit').prop("disabled", true);
//						},1500);
//						
//					}
//				
//				}
//			});
//		}
//    });
	
	$( "#from_address" ).change(function(e) {
    var form_data = { title:$( "#from_address" ).val() };
	var res_from_addr = $.ajax({
					url: "<?php echo site_url()?>?c=inmail&m=fetch_address_id",
					type: 'POST',
					data: form_data,
					dataType:"json",
					async: false,
					success: function(response) {
					  //alert(response);
					 }
				  });
				   string = $.parseJSON(res_from_addr.responseText);
				   $("#from_address_id").val(string);
	 
	});
});
</script>