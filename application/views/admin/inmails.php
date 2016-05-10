<div id="success-msg" class="alert alert-success"  style="display:none"></div>
<div id="error-msg" class="alert alert-danger" style="display:none"></div>
 <?php 
 	$login_data = $this->session->userdata('check_login');
 	//echo $login_data['login_type'];
	if($login_data['login_type']==1){
  ?>
<div style="width:20%;float:right;margin-right:20px">
<form method="post" action="" id="search_form"><!--<?php echo site_url()?>?c=admin&m=manage_inmails-->
<button style="position: absolute; z-index: 9; height:38px; margin-left:184px;" class="btn btn-default pull-right"><i class="glyphicon glyphicon-search"></i></button>
<input type="text" id="search_txt" placeholder="Mail number Search" class="search-query form-control" name="mail_number" value="<?php echo isset($_GET['mail_number'])?$_GET['mail_number']:''; ?>">
</form>
</div>
<?php } ?>

<div class="box col-md-12">
    <div class="box-inner">
        <div class="box-header well" data-original-title="">
        	<h2><i class=""></i>
				<?php echo 'In Mail List';?>
			</h2>
        
        <div class="box-icon">
        <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
        </div>
        </div>
    <div class="box-content">
    <form id="distributedtable" method="post">
    <table id="distributed_list" class="table table-striped add-style table-bordered bootstrap-datatable datatable responsive">
    
    <thead>
    <tr>
    	<th class="no-print" style="width:3%"><input type="checkbox" value="" id="checkall" class="checkall"></th>
        <th class="no-print" style="width:8%">Id</th><th class="only-print">Id</th>
        <th style="width:9%">Received Date</th>  
        <th style="width:8%">Mail Ref</th> 
        <th style="width:8%">Date</th> 
        <th style="width:10%">Subject</th>    
        <th style="width:9%">From</th>
        <th style="width:10%">To</th>
        <th style="width:8%">Copy To</th>
        <th style="width:8%">Division / Subdivision</th>
        <th style="width:8%">Action Date</th>
        <th style="width:8%">Close Date</th>
        <th style="text-align:center;width:7%" class="no-print">Action</th> 
    </tr>
    </thead>
     <tbody>
	<?php	
		if(empty($distributed_list)){?>
		<tr><td colspan="13" style="text-align:center"> No Mails are found</td></tr>
    <?php } else{  //echo "<pre>";print_r($distributed_list);die;
		foreach($distributed_list as $dist_list){
			$created_year = date("y", strtotime($dist_list['created_on']));
			$mailno_with_year = $dist_list['mail_no'];?>
        <tr style="page-break-after:always;">
        	<td class="no-print"><input type="checkbox" id="checkbox_<?=$dist_list['id'];?>" class="checkbox_option" name="checkbox" value="<?=$dist_list['id'];?>"></td>
            <td style="width:30px" class="no-print"><a href="<?=base_url();?>?c=inmail&m=inmailview&mailid=<?=urlencode($dist_list['id'])?>&stat=manageinmail" data-toggle="tooltip" data-original-title="View"><?=$mailno_with_year;?></a></td>
	    <td class="only-print"><?=$mailno_with_year;?></td>
        	<td style="width:100px" class="date-length"><?=$dist_list['recieved_date'];?></td>
            <td class="add-letter-space ref-length"><?=$dist_list['mail_ref'];?></td>
        	<td style="width:120px" class="date-length"><?=$dist_list['date'];?></td>
            <td class="sub-length"><?=$dist_list['subject'];?></td>
            <td><?=isset($dist_list['from'])?$dist_list['from']:'';?></td>
            <td><?php if(!empty($dist_list['to'])){ echo $dist_list['to']; }?></td> 
            <td><?php if(!empty($dist_list['cc'])){ echo $dist_list['cc']; }?></td>
            <td><?php echo $dist_list['division'];
			if(isset($dist_list['subdivision']))
				echo " / ".$dist_list['subdivision'];?>
			<input type="hidden" name="valid-option" id="<?=$dist_list['id'];?>" value="<?=$dist_list['division_id'];?>"/></td>
            <td style="width:100px" class="date-length"><?=$dist_list['action_date'];?></td>
            <td style="width:100px" class="date-length"><?=$dist_list['close_date'];?></td>
            <td style="text-align:center; width:100px" class="no-print">
            <?php $search = ""; 
			if(isset($_GET['mail_number'])) 
			$search = "&mail_number=".$_GET['mail_number'];?>
            <a title="Edit" href="<?=base_url();?>?c=inmail&m=inmailentry&mailid=<?=$dist_list['id'].$search;?>&ref=edit" class="link-color"><i class="glyphicon glyphicon-edit"></i></a> | <a title="Delete" href="javascript:void(0);" class="confirm red-icon" id="<?=$dist_list['id'];?>" onclick="single_delete(<?=$dist_list['id'];?>)"><i class="glyphicon glyphicon-remove"></i></a>
            
            </td>
		</tr>
        <?php } //end foreach
	} ?><!-------------------->
    </table>
    <div class="col-md-12">
    <?php echo $this->pagination->create_links(); ?>
    </div>
    <div class="col-md-12">
    <button type="button" id="delete_multiplemails" class="btn btn-default">Delete</button>
     </div>
     <div class="clearfix"></div>
    </form>
    
    </div>
    </div>
    </div>

<script>
$(document).ready(function() {	
	$("#distributedtable input[name='valid-option']").each(function() {
		var value=$(this).attr('value');
		var id=$(this).attr('id');
		
		if( value ==''){
			span=$('<span class="label label-warning">Not Assigned</span>');
			$("td#msg_"+id).append(span);	
		}
	});
	
	
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
	
	$("#delete_multiplemails").confirm({
		text: "Are you sure that you really want to delete these selected Mails?",
		title: "Confirmation required",
		confirm: function(button) {
			var checkValues = $('#distributedtable input[name="checkbox"]:checked').map(function()
            { 
				return $(this).val();
            }).get();
			if(checkValues!=''){
			$.ajax({
				url: "<?=base_url()?>?c=admin&m=delete_multiplemails",
				type: "post",
				data: "mailids="+checkValues,
				success:function(response){
					if(response){
						$("#success-msg").html('<strong>Success: </strong>The selected mails are removed from the list.');
						$("#success-msg").slideDown();
						window.scrollTo(10,0);
						setTimeout(function(){ 
							window.location.reload(true);
						},2000);						
					}
				}
			});
			}else{
				$("#error-msg").html('<strong>Error: </strong>No mails are selected.');
				$("#error-msg").slideDown();
				window.scrollTo(10,0);
				setTimeout(function(){
					$("#error-msg").slideUp();
				},2000);
			}
		},
		cancel: function(button) {
			return false;
		},
		confirmButton: "Yes",
		cancelButton: "No",
		post: true
	});	
	
	$("#search_form").submit(function(e) {	
      	$("#search_form").attr("action", "<?php echo site_url()?>?c=admin&m=manage_inmails&mail_number="+$("#search_txt").val());
    });
	
});
function single_delete(mail_id){
	$.confirm({
		text: "Are you sure that you really want to delete this Inmail?",
		title: "Confirmation required",
		confirm: function(button) {
			$.ajax({
			url: "<?=base_url();?>?c=admin&m=delete_single_mail",
			type: 'POST',
			data: "mail_id="+mail_id,
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
</script>
