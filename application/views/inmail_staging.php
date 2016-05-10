<div id="success-msg" class="alert alert-success"  style="display:none"></div>
<div id="error-msg" class="alert alert-danger" style="display:none"></div>
<div class="box col-md-12">
    <div class="box-inner">
    <div class="box-header well" data-original-title="">
        <h2><i class=""></i> Inmail Staging List</h2>

        <div class="box-icon">
            <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
        
        </div>
    </div>
    <?php $login_data = $this->session->userdata('check_login'); ?>
    <div class="box-content">
    <form id="stagingtable" method="post">
    <table id="staging_list" class="table table-striped table-bordered bootstrap-datatable datatable responsive">
    <thead>
    <tr>
    	<th><input type="checkbox" value="" id="checkall" class="checkall"></th>
        <th>Status</th>
        <th>Mail No </th>	
        <th>Mail Reference</th>
        <th>Subject</th>        
                                                 
    </tr>
    </thead>
    <tbody>
		<?php if(empty($staging_list)){?>
        <tr><td colspan="5" style="text-align:center">No Mails are found</td></tr>
        <?php } else{
        foreach ($staging_list as $stag_list){
			$created_year = date("y", strtotime($stag_list['created_on']));?>
        <tr>
            <td><input type="checkbox" id="checkbox_<?=$stag_list['id'];?>" class="checkbox_option" name="checkbox" value="<?=$stag_list['id'];?>"></td>
            <td class="validmsg" id="msg_<?=$stag_list['id'];?>" ><input type="text" name="valid-option" id="<?=$stag_list['id'];?>" style="display:none" value="<?=$stag_list['validation'];?>"/></td>
            <td><a href="<?=base_url();?>?c=inmail&m=inmailentry&mailid=<?=$stag_list['id'];?>">
	    <?=$stag_list['mail_no'];?></a></td>
            <td><?=$stag_list['mail_ref'];?></td>
            <td><?=$stag_list['subject'];?></td>
             
        </tr>
        <?php } }?>        
    </tbody>
    </table>
     <div class="col-md-12">
    <?php echo $this->pagination->create_links(); ?>
    </div>
    <div class="col-md-12">
     <button type="button" id="distribute" class="btn btn-default">Distribute</button>
     <button type="button" id="delete_staging" class="btn btn-default">Delete</button> 
     </div>
     <div class="clearfix"></div>
    </form>
    </div> 
    </div>
    </div>

<script>
$(document).ready(function(e) {
    $('#checkall').click(function () {    
		$('input:checkbox:not(:disabled)').prop('checked', this.checked);    
	});
	
	$(".checkbox_option:not(:disabled)").click(function(){
        if($(".checkbox_option:not(:disabled)").length == $(".checkbox_option:not(:disabled):checked").length) {
            $("#checkall").prop("checked", this.checked);
        } else {
            $("#checkall").removeAttr("checked");
        }
 
    });
	
	//to make distributed list
	$('#distribute').click(function () {
		
		var checkValues = $('#stagingtable input[name="checkbox"]:checked').map(function()
            { 
				return $(this).val();
            }).get();
		if(checkValues!=''){
			$.confirm({
				text: "Are you sure that you really want to continue?",
				title: "Confirmation required",
				confirm: function(button) {	
					$.ajax({
							url: "<?=base_url()?>?c=inmail&m=create_distributed_list",
							type: "post",
							data: "mailids="+checkValues,
							success:function(response){
								
								if(response==1){
									$("#success-msg").html('<strong>Success: </strong>The selected mails are added to distributed list.');
									$("#success-msg").slideDown();
									window.scrollTo(10,0);
									setTimeout(function(){ 
										window.location.reload(true);
									},2000);						
								}else{
									$("#error-msg").html('<strong>Error: </strong> Incomplete mails cannot be distributed.');
									$("#error-msg").slideDown();
									window.scrollTo(10,0);
									setTimeout(function(){ 
										$("#error-msg").hide();
									},2000);
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
		}else{
				$("#error-msg").html('<strong>Error: </strong>No mails are selected.');
				$("#error-msg").slideDown();
				window.scrollTo(10,0);
				setTimeout(function(){
					$("#error-msg").slideUp();
				},2000);
		}	
	});
	
	//to show different status in different colours
	var tabledata=$("#stagingtable td.validmsg");
	$("#stagingtable input[name='valid-option']").each(function() {
		var value=$(this).attr('value');//input value
		var id=$(this).attr('id');//td id
		if( value == 0 ){
			span=$('<span class="label label-danger">Incomplete</span>');
			//$("#checkbox_"+id).prop("disabled", true);
		}
		else if( value == 1){
			span=$('<span class="label label-warning">Unassigned</span>');	
		}
		else{
			span=$('<span class="label label-success">Ready</span>');	
		}
		$("#stagingtable td#msg_"+id).append(span);
	});	
	
	$("#delete_staging").click(function(e) {
     var checkValues = $('#stagingtable input[name="checkbox"]:checked').map(function()
            { 
				return $(this).val();
            }).get();
			if(checkValues!=''){   
	$.confirm({
		text: "Are you sure that you really want to delete these selected mails?",
		title: "Confirmation required",
		confirm: function(button) {
			
			$.ajax({
				url: "<?=base_url()?>?c=inmail&m=delete_staging_list",
				type: "post",
				data: "mailids="+checkValues,
				success:function(response){
					if(response){
						$("#success-msg").html('<strong>Success: </strong>The selected mails are removed from staging list.');
						$("#success-msg").slideDown();
						window.scrollTo(10,0);
						setTimeout(function(){ 
							window.location.reload(true);
						},2000);						
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
	}else{
				$("#error-msg").html('<strong>Error: </strong>No mails are selected.');
				$("#error-msg").slideDown();
				window.scrollTo(10,0);
				setTimeout(function(){
					$("#error-msg").slideUp();
				},2000);
			}	
	});
});
</script>