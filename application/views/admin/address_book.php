<div id="success-msg" class="alert alert-success"  style="display:none"></div>

<div style="width:20%;float:right;margin-right:20px">
<form method="post" action="" id="search_form"><!--<?php echo site_url()?>?c=admin&m=manage_inmails-->
<button style="position: absolute; z-index: 9; height:38px; margin-left:184px;" class="btn btn-default pull-right"><i class="glyphicon glyphicon-search"></i></button>
<input type="text" id="search_addr" placeholder="Search by Alias/Name" class="search-query form-control" name="search_addr" value="<?php echo isset($_GET['address'])?$_GET['address']:''; ?>">
</form>
</div>

<div class="box col-md-12">
    <div class="box-inner">
    <div class="box-header well" data-original-title="">
        <h2><i class=""></i> Manage Address Book </h2>

        <div class="box-icon">
        	<a href="<?=base_url();?>?c=admin&m=address_form" class="btn btn-primary btn-lg" style="width:120px;line-height:0; padding:11px 10px">Add Address</a>
            <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
        </div>
    </div>
    <div class="box-content">
    <form id="address_tbl" method="post">
    <table id="address_list" class="table table-striped table-bordered bootstrap-datatable datatable responsive">
    <thead>
    <tr>
    	<th class="no-print"><input type="checkbox" value="" id="checkall" class="checkall"></th>
        <th>Name</th>
        <th>Alias</th>
        <th>Address1</th>
        <th>Address2</th>	
        <th>Address3</th>
        <th>City</th>
        <th>State</th>
        <th>Pincode</th>
        <th style="text-align:center;" class="no-print">Action</th>                              
    </tr>
    </thead>
    <tbody>
    <?php if(empty($address)){?>
		<tr><td colspan="10" style="text-align:center"> No Addresses are found</td></tr>
    <?php } else{ 
    foreach ($address as $addr){?>
        <tr>
        	<td class="no-print"><input type="checkbox" id="checkbox_<?=$addr['id'];?>" class="checkbox_option" name="checkbox" value="<?=$addr['id'];?>"></td>
        	<td><?=$addr['name'];?></td>
            <td><?=$addr['title'];?></td>
            <td><?=$addr['address1'];?></td>
            <td><?=$addr['address2'];?></td>
            <td><?=$addr['address3'];?></td>
            <td><?=$addr['city'];?></td>
            <td><?=$addr['state'];?></td>
            <td><?=$addr['pincode'];?></td>
			<td style="text-align:center; width:100px" class="no-print">
            
            <?php $search = ""; 
			if(isset($_GET['address'])) 
			$search = "&address=".$_GET['address'];?>
            <a title="Edit" href="<?=base_url();?>?c=admin&m=address_form&addr_id=<?=$addr['id'].$search;?>"  class="link-color"><i class="glyphicon glyphicon-edit"></i></a> | <a href="javascript:void(0);"  title="Delete" class="confirm red-icon" id="<?=$addr['id'];?>"><i class="glyphicon glyphicon-remove"></i></a>
            
            </td>
        </tr>
        <?php } } ?>        
    </tbody>
    </table>
   <div class="col-md-12">
    <?php echo $this->pagination->create_links(); ?>
    </div>
    <div class="col-md-12">
   <button type="button" id="delete_multiaddress" class="btn btn-default">Delete</button>
    </div>
     <div class="clearfix"></div>
   </form>
    </div>
    </div>
    </div>

<script>
$(document).ready(function() {	
	
	$(".confirm").each(function(){
	var addr_id = this.id;
	$("#"+addr_id).confirm({
		text: "Are you sure that you really want to delete this Address?",
		title: "Confirmation required",
		confirm: function(button) {
			$.ajax({
			url: "<?=base_url();?>?c=admin&m=delete_address",
			type: 'POST',
			data: "addr_id="+addr_id,
			success: function(response) {
				if(response==1){
					window.location.reload(true);
				}
			},
			/*error: function (XMLHttpRequest, textStatus, errorThrown) {
			    alert(XMLHttpRequest.responseText);
			},*/
			});
		},
		cancel: function(button) {
			return false;
		},
		confirmButton: "Yes",
		cancelButton: "No",
		post: true
	});
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
	
	$("#delete_multiaddress").confirm({
		text: "Are you sure that you really want to delete these selected addresses?",
		title: "Confirmation required",
		confirm: function(button) {
			var checkValues = $('#address_tbl input[name="checkbox"]:checked').map(function()
            { 
				return $(this).val();
            }).get();
			$.ajax({
				url: "<?=base_url()?>?c=admin&m=delete_multiaddress",
				type: "post",
				data: "addrids="+checkValues,
				success:function(response){
					if(response){
						$("#success-msg").html('<strong>Success: </strong>The selected addresses are removed from the list.');
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
	
	$("#search_form").submit(function(e) {	
		
		var url_enc =encodeURIComponent($("#search_addr").val());
      	$("#search_form").attr("action", "<?php echo site_url()?>?c=admin&m=manage_address_book&address="+url_enc);
    });	
});
</script>
