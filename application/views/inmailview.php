<script src="<?php echo base_url();?>js/jquery.validate.min-1.11.1.js"></script>
<div id="success-msg" class="alert alert-success"  style="display:none"></div>
<div id="error-msg" class="alert alert-danger" style="display:none"></div>
<div class="box col-md-6">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class=""></i> In Mail</h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>                   
                </div>
            </div>
            <div class="box-content">
    <?php $session=$this->session->userdata('check_login');
	$div_data = $this->session->userdata('get_div');
	$ms_session = $this->session->userdata('mail_status'); ?>
    <table class="table table-striped table-bordered bootstrap-datatable datatable responsive">
    
    <tbody>
    <?php if(empty($inmail_details)){?>
		<tr><td style="text-align:center">No details available</td></tr>
    <?php } else{  $created_year = date("y", strtotime($inmail_details['created_on']));
	//echo "<pre>";print_r($inmail_details);?>
    	<tr>
        	<td class="view-label">Mail Number</td>
        	<td class="view-value"><?php echo $inmail_details['mail_no']; ?></td>
		</tr>
		<tr>
        	<td class="view-label">Mail Reference</td>
        	<td class="view-value"><?php echo $inmail_details['mail_ref']; ?></td>
		</tr>
        <tr>
            <td class="view-label">Mail Date</td>
        	<td class="view-value"><?php echo $inmail_details['date']; ?></td>
		</tr>
        <tr>
            <td class="view-label">Received Date</td>
        	<td class="view-value"><?php echo $inmail_details['recieved_date']; ?></td>
        </tr>
        <tr>
        	<td class="view-label">From</td>
        	<td class="view-value">
			<?php if(isset($inmail_details['from']['title']))
			echo  '<a href="#" id="from">'.$inmail_details['from']['title'].'</a>'; 
			else 
			echo  "(EMPTY)";?></td>
            <div id="from_id" style="display:none"><?php echo $inmail_details['from']['id']; ?></div>
         
        </tr>
        <tr>
        	<td class="view-label">To</td>
        	<td class="view-value">
				<?php foreach($inmail_details['to'] as $inm_to){
					if(end($inmail_details['to']) != $inm_to){ ?>
						<a href="#" id="to" onclick="to_details(<?php echo $inm_to['id'];?>)"><?php echo $inm_to['title'];?></a><?php echo ", "; }
					else { ?>
						<a href="#" id="to" onclick="to_details(<?php echo $inm_to['id'];?>)"><?php echo $inm_to['title'];?></a><?php } ?>
                    
                <?php } ?>
            </td>
        </tr>
        <tr>
        	<td class="view-label">Cc</td>
        	<td class="view-value">
            <?php foreach($inmail_details['cc'] as $inm_cc){
					if(end($inmail_details['cc']) != $inm_cc){ ?>
						<a href="#" id="cc" onclick="cc_details(<?php echo $inm_cc['id'];?>)"><?php echo $inm_cc['title'];?></a><?php echo ", "; }
					else { ?>
						<a href="#" id="cc" onclick="cc_details(<?php echo $inm_cc['id'];?>)"><?php echo $inm_cc['title'];?></a><?php } ?>
                <?php } ?>
            </td>
        </tr>
        <tr>
        	<td class="view-label">Subject</td>
        	<td class="view-value"><?php echo $inmail_details['subject']; ?></td>
        </tr>
        <!-------Division Form----------->
        <tr><td colspan="2">
        <form name="form_division" method="post">
        <table class="table table-striped table-bordered bootstrap-datatable datatable responsive">
        <tr>
        	<td class="view-label">Division</td>
        	<td class="view-value">
            	<select class="form-control own" id="division_id" name="division_id"  <?php if($inmail_details['status'] == 1 && $session['login_type'] ==2){ echo "disabled='disabled'";}?>>
                    	<option class="select-option" value=''>Choose Division</option>
						<?php foreach($divisions as $div){
							if($div['id'] == $inmail_details['division_id']) {?>
                        		<option class="select-option" value="<?php echo $div['id'];?>" selected="selected"><?php echo $div['division'];?></option>
							<?php }else {?>
                            	<option class="select-option" value="<?php echo $div['id'];?>"><?php echo $div['division'];?></option>
							<?php } ?>
                        <?php } ?>
                    </select>	
			</td>
        </tr>
        <tr>
        	<td class="view-label">Subdivision</td>
        	<td class="view-value">
            	<select class="form-control own" id="subdivision_id" name="subdivision_id" <?php if($inmail_details['status'] == 1 && $session['login_type'] ==2){  echo "disabled='disabled'";}?>>
                </select>
			</td>
        </tr>
        <tr>
        	<td class="view-label">Action Date</td>
            <td class="view-value"><input type="text" class="form-control own" 
           <?php if($inmail_details['status'] == 1 && $session['login_type'] ==2){ echo "id=''";}else{ echo "id='action_datepicker'";}?>  name="action_date" placeholder="Action Date" style="background: url(<?php echo base_url();?>img/calendar.gif) no-repeat scroll 10px 10px; cursor:pointer; background-position:right 10px top 10px;background-color:#ffffff !important; opacity:1 !important;" value="<?php echo isset($inmail_details['action_date'])?$inmail_details['action_date']:'';?>"  disabled="disabled"></td>
        </tr>
        <tr>
        	<td class="view-label">Close Date</td>
            <td class="view-value"><input type="text" class="form-control own" <?php if($inmail_details['status'] == 1 && $session['login_type'] ==2){ echo "id=''";}else{ echo "id='close_datepicker'";}?>  name="close_date" placeholder="Close Date" style="background: url(<?php echo base_url();?>img/calendar.gif) no-repeat scroll 10px 10px; cursor:pointer; background-position:right 10px top 10px;" value="<?php echo isset($inmail_details['close_date'])?$inmail_details['close_date']:'';?>" disabled="disabled">
            <input type="hidden" name=="close" id="close" /></td>
        </tr>
        <tr><td colspan="2">
        	<button type="submit" id="div_submit" class="btn btn-default"  disabled="disabled">Save</button>
            <!--button id="close_submit" class="btn btn-default" style="float:right">Close Mail</button-->
           
        </td></tr>
        </form></table>
        </td></tr>
        <!-------End Division Form----------->
	<?php } ?>
    </tbody>
    </table>
    </div>
    </div>
</div>

<div class="box col-md-6">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class=""></i> Comments</h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>                   
                </div>
            </div>
            <div class="box-content">
    <table id="cmts_table" class="table table-striped table-bordered bootstrap-datatable datatable responsive">
    
    <tbody>
    <?php if(!empty($comments)){  
		foreach($comments as $comment){ ?>
		<tr><td class="cmts" colspan="2"><?php echo $comment['comments'];?></td></tr>
        <tr class="cmts_details"><td class="cmts_left">By: <?php echo ucfirst($comment['firstname']);?></td>
        	<td class="cmts_right">On: <?php echo $comment['date'].' hrs';?></td>
		</tr>
	<?php } }?>
		
    </tbody>
    </table>
    
    <form id="form-inmail-comments" method="post">
    <div class="form-group">
    	<input type="hidden" id="mailid" name="mailid" value="<?php echo isset($inmail_details['id'])?$inmail_details['id']:'';?>">
        <label for="mailref">Comments:</label><br />
        <textarea class="autogrow" id="inmail-comments" name="inmail_comments" style="width:60%"></textarea>
        
    </div>
    <input type="submit" id="submit-comment"  class="btn btn-default" value="Submit"/>
    </form>
    
    </div>
    </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
    </div>
<script>
$(function() {
	$("#inmail-comments").val('');
	$("#myModal").hide();
	 
	$('#submit-comment').click(function(){
		if($("#form-inmail-comments").valid())
		{
			
			$.confirm({
				text: "Are you sure that you really want to continue?",
				title: "Confirmation required",
				confirm: function(button) {
					var comments=$("#inmail-comments").val();
					var mailid=$("#mailid").val();
					$.ajax({
						url: "<?php echo site_url()?>?c=inmail&m=insert_comments",
						type: 'POST',
						data: "comments="+comments+"&mailid="+mailid,
						success: function(response) {
							if(response==1){
								window.location.reload(true);
							}
						}
					});
				},
				cancel: function(button) {
					return false;
				},
				confirmButton: "Yes",
				cancelButton: "No",
				post: true
			});
		}
		return false;
	});	
	
	$("#form-inmail-comments").validate({    
        
        rules: {
            inmail_comments: "required"
		},
		messages: {
            inmail_comments: "Please enter the comments"
		},
		submitHandler: function(form) {
            form.submit();
        }
    });
	
	$('#from').click(function (e) {
        e.preventDefault();
		var id =$("#from_id").html();
        var modal=$('#myModal').modal('show');
		modal.load('<?php echo base_url();?>?c=inmail&m=email_details&mailid='+id)
    });
	
	
	
	var div_id =$( "#division_id" ).val();
	var subdiv_id ='<?php echo $inmail_details['subdivision_id'];?>';
	$.ajax({
		url: "<?php echo base_url()?>?c=inmail&m=check_user",
		type: "post",
		//data: "div_id="+div_id+"&subdiv_id="+subdiv_id+"&login_id="+login_id,
		data: "div_id="+div_id+"&subdiv_id="+subdiv_id,
		success:function(response){
			if(response){
				$('#action_datepicker').prop("disabled", false);
				$('#close_datepicker').prop("disabled", false);
				if($("#close_datepicker").val()){
					disable_inputs();
				}
			}
		}
	});
	
	/**/
	
	if($("#close_datepicker").val()){
		disable_inputs();
	}
				
	$(".own").change(function(){
		if ($(this).val()!='' ){
			$('#div_submit').prop("disabled", false);
		}else{
			$('#div_submit').prop("disabled", true);
		}
	});
	/**/
	
	//To get subdivision 
	fetch_subdiv(div_id, subdiv_id);
	$( "#division_id" ).change(function () {
		var div_id=$( "#division_id" ).val();
		fetch_subdiv(div_id);
	});

	$("#div_submit").confirm({
		text: "Are you sure that you really want to continue?",
		title: "Confirmation required",
		confirm: function(button) {
			var div_data = { div_id:$( "#division_id" ).val(),
			subdiv_id:$( "#subdivision_id" ).val(),
			mail_id:'<?php echo $inmail_details['id']; ?>',
			mail_no:'<?php echo $inmail_details['mail_no']; ?>',
			action_date:$("#action_datepicker").val(),
			close_date:$("#close_datepicker").val()};
			save(div_data);
		},
		cancel: function(button) {
			return false;
		},
		confirmButton: "Yes",
		cancelButton: "No",
		post: true
	});
	
	/*View Changes based on login type*/
	var login_type = '<?php echo $session['login_type'];?>';
	if(login_type == 4){
		$('#division_id').prop("disabled", true);
	}else if(login_type == 3){
		disable_div();
		$("#division_id").change(function(e) {
            if($('#division_id').val() != '<?php echo $div_data['division_id'];?>'){
				$('#subdivision_id').prop("disabled", true);
				
			}else{
				$('#subdivision_id').prop("disabled", false);
				
			}
        });
	}else if(login_type == 1){
		$('#action_datepicker').prop("disabled", false);
		$('#close_datepicker').prop("disabled", false);
		$('#division_id').prop("disabled", false);
		$('#subdivision_id').prop("disabled", false);
	}

});


function disable_div(){
	if($('#division_id').val() != '<?php echo $div_data['division_id'];?>'){
		$('#division_id').prop("disabled", true);
		$('#subdivision_id').prop("disabled", true);
		
		$("#action_datepicker").css("opacity", '0.6');
		$("#close_datepicker").css("opacity", '0.6');
	}else{
		$('#subdivision_id').prop("disabled", false);
						
	}
}

function to_details(id){
	var modal=$('#myModal').modal('show');
	modal.load('<?php echo base_url();?>?c=inmail&m=email_details&mailid='+id)
}

function cc_details(id){
	var modal=$('#myModal').modal('show');
	modal.load('<?php echo base_url();?>?c=inmail&m=email_details&mailid='+id)
}

function fetch_subdiv(div_id,subdiv_id){
	$.getJSON("<?php echo base_url();?>?c=inmail&m=fetchsubdivision",{div_id: div_id}, function(j){
			var options = '<option class="select-option" value="0"> Select subdivision</option>';
			for (var i = 0; i < j.length; i++) {
				if(j[i].id==subdiv_id)
        		options += '<option selected="selected" class="select-option" value="' + j[i].id + '">' + j[i].code + '</option>';
				else
				options += '<option class="select-option" value="' + j[i].id + '">' + j[i].code + '</option>';
      		}
			$("select#subdivision_id").html(options);
    	});
}

function disable_inputs()
{
	$('#division_id').prop("disabled", true);
	$('#subdivision_id').prop("disabled", true);
	$('#action_datepicker').prop("disabled", true);
	$("#action_datepicker").css("opacity", '0.6');
	$('#close_datepicker').prop("disabled", true);
	$("#close_datepicker").css("opacity", '0.6');
}

function save(div_data){
	$.ajax({
                url: "<?php echo base_url()?>?c=inmail&m=edit_division",
                type: "post",
                data: div_data,
                success:function(response){
					if(response==1){
						$("#success-msg").html('<strong>Success: </strong>The changes have been updated successfully.');
						$("#success-msg").slideDown();
						window.scrollTo(10,0);
						setTimeout(function(){ 
							window.location.reload(true);
						},2000);						
					}else{
						$("#error-msg").html('<strong>Error: </strong>The changes have not updated.');
						$("#error-msg").slideDown();
						window.scrollTo(10,0);
						setTimeout(function(){
							$("#error-msg").slideUp();
						},2000);
					}
                }/*,
				error: function (XMLHttpRequest, textStatus, errorThrown) {
			    	alert(XMLHttpRequest.responseText);
				}*/
				
		});
}
</script>
<style>
.view-label{
	width:40%;
}
.view-value{
	width:60%;
}
</style>