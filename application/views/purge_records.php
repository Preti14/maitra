<div id="success-msg" class="alert alert-success"  style="display:none"> <strong>Success: </strong>The Purge Record settings has been changed.</div>
<div class="box col-md-6">
  <div class="box-inner">
    <div class="box-header well" data-original-title="">
      <h2><i class=""></i> Purge Records Settings</h2>
      <div class="box-icon"> <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a> </div>
    </div>
    <div class="box-content">
    
      <form role="form" id="purge_records_form" method="post" action="<?=base_url();?>?c=admin&m=purge_records" enctype="multipart/form-data">
        <?php //print_r($settings);?>
         
         
        
        <div class="form-group">
          <label for="purge_records">No of Records to Purge : </label>
         <div style=" clear:both; margin-bottom:10px;"> <input type="text" class="form-control sett" id="purge_records" name="purge_records" placeholder="Purge Records" value="<?=isset($settings['purge_records'])?$settings['purge_records']:'';?>" style="width:25%; float:left; margin-right:15px;">
         <button id="purge_records_submit" type="submit" class="btn btn-default" disabled="disabled">Submit</button>
         </div>
         <label for="purge_records"><strong>Please click purge to delete the old records</strong> </label>
         <div style=" clear:both; margin-bottom:10px;">
          <input name="purge" id="purge" type="button" value="Purge" class="form-control sett" style="width:10%" />   </div>
        </div>
        <div id="loading" style="display:none;width:100%;">Purging the records ...</div>        
        <div id="response_value" class="alert alert-success" style="display:none"></div>        
        <div id="purgesuccess-msg" class="alert alert-success" style="display:none"> <strong>Success: </strong>Records has been purged successfully.</div>
               
        <div id="purgefailure-msg" class="alert alert-failure" style="color:#F00; display:none"> <strong>Failed: </strong>Failed to Purge Records</div>
        
        
      </form>
    </div>
  </div>
</div>
<script>
$(function () {
	$(".sett").click(function(){
		if ($(this).val()!='' ){
			$('#purge_records_submit').prop("disabled", false);
		}else{
			$('#purge_records_submit').prop("disabled", true);
		}
	});
	
	 
	 
	var success = '<?=isset($success)?$success:'';?>';
	if(success!=''){
		$("#success-msg").slideDown();
		setTimeout(function(){ 
			window.location.href = '<?=base_url();?>?c=admin&m=purge_records';
		},2000);
	}
	
	
	var purgesuccess = '<?=isset($purgesuccess)?$purgesuccess:'';?>';
	if(purgesuccess!=''){
		$("#purgesuccess-msg").slideDown();
		setTimeout(function(){ 
			window.location.href = '<?=base_url();?>?c=admin&m=purge_records';
		},2000);
	}
	
	
	var purgefailure = '<?=isset($purgefailure)?$purgefailure:'';?>';
	if(purgefailure!=''){
		$("#purgefailure-msg").slideDown();
		setTimeout(function(){ 
			window.location.href = '<?=base_url();?>?c=admin&m=purge_records';
		},2000);
	}
	
	
	
	$("#temporary_addr_count").keypress(function (e) {
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
        $("#errmsg").html("Digits Only").show().fadeOut("slow");
               return false;
    }
   });
   
 
	
	$("#purge_records_submit").confirm({
		text: "Are you sure that you really want to continue?",
		title: "Confirmation required",
		confirm: function(button) {
			$('form#purge_records_form').submit();
		},
		cancel: function(button) {
			return false;
		},
		confirmButton: "Yes",
		cancelButton: "No",
		post: true
	});	
	
 
 
 	$("#purge").click(function(e) {  
			e.preventDefault();
			preventRunDefault = true;
			$(document).ajaxStart(function () {
				$("#loading").show();
			}).ajaxStop(function () {
				$("#loading").hide();
			});      
			$.ajax({
				url: "<?php echo base_url();?>?c=admin&m=purging",
				type: 'POST',
				data: "purge_records="+$("#purge_records").val(),
				success: function(response) {
					 //alert(response);		
					 $("div#response_value").show();			 
					 $( "div#response_value" ).html(response);
					 /*setTimeout(function(){ 
						window.location.href = '<?=base_url();?>?c=admin&m=purge_records';
					},2000);*/
				}
			});		 
    });
	
	
	 
	
	
	
});
</script>