<div class="box col-md-6">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class=""></i>
				<?php if(isset($_GET['subdivision_id'])) echo "Update Subdivision";
				else echo "Add Subdivision"; ?></h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>                   
                </div>
            </div>
            <div class="box-content">
                <form role="form" id="subdivision_form" method="post" action="<?=base_url();?>?c=admin&m=subdivision_add">
                
                	<input type="hidden" id="subdivid" name="subdivid" value="<?=isset($row['id'])?$row['id']:'';?>">					<?php //echo "<pre>"; print_r($row);?>
                    <div class="form-group">
                        <label for="subdivision">Subdivision : </label>
                        <input type="text" class="form-control" id="subdivision" name="subdivision" placeholder="Subdivision" value="<?=isset($row['subdivision'])?$row['subdivision']:'';?>"><span style="color:#F00;"></span>
                    </div>
                   
                   <div class="form-group">
                        <label for="code">Code : </label>
                        <input type="text" class="form-control" id="subdiv_code" name="subdiv_code" placeholder="Subdivision Code" value="<?=isset($row['code'])?$row['code']:'';?>"><span style="color:#F00;"></span>
                    </div>
                    
                    <div class="form-group">
                	<label for="division">Division : </label>
                    <select class="form-control" id="division_id" name="division_id">
                    	<option class="select-option" value=''>Choose Division</option>
						<?php foreach($divisions as $div){
							if($div['id'] == $row['division_id']) {?>
                        		<option class="select-option" value="<?=$div['id'];?>" selected="selected"><?=$div['division'];?></option>
							<?php }else {?>
                            	<option class="select-option" value="<?=$div['id'];?>"><?=$div['division'];?></option>
							<?php } ?>
                        <?php } ?>
                    </select><span style="color:#F00;"></span>
                	</div>
                    
                    <button type="submit" id="subdiv_submit" class="btn btn-default">Submit</button>
                    <button class="btn btn-default" id="subdiv_cancel">Cancel</button>
                </form>

            </div>
        </div>
    </div>
<div class="box col-md-6">
    <div class="box-inner">
    <div class="box-header well" data-original-title="">
        <h2><i class=""></i> Division List</h2>

        <div class="box-icon">
			<a href="<?php echo base_url();?>?c=admin&m=subdivision" class="" style="width:20px;line-height:0; padding:3px"><i class="glyphicon glyphicon-plus"></i></a>
            <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
        </div>
    </div>
    <div class="box-content">
    <table class="table table-striped table-bordered bootstrap-datatable datatable responsive">
    <thead>
    <tr>
        <th>Code</th>
        <th>Subdivision</th>
        <th>Division</th>	
        <th style="text-align:center" colspan="2">Action</th>   
                                                 
    </tr>
    </thead>
    <tbody>
    <?php foreach ($subdivisions as $subdiv){?>
        <tr>
        	<td><?=$subdiv['code'];?></td>
            <td><?=$subdiv['subdivision'];?></td>
            <td><?=$subdiv['division'];?></td>
			<td style="text-align:center"><a href="<?=base_url();?>?c=admin&m=subdivision&subdivision_id=<?=$subdiv['id'];?>">Edit</a></td>
            <td style="text-align:center"><a href="javascript:void(0);" class="confirm" id="<?=$subdiv['id'];?>">Delete</a></td>
        </tr>
        <?php }?>        
    </tbody>
    </table>
   
    </div>
    </div>
    </div>

<script>
$(function () {
	$("#subdivision_form").validate({  
		errorElement: "span",
		errorPlacement: function(error, element) {
                        error.appendTo(element.next("span"));
        },  
        // Specify the validation rules
        rules: {
            subdivision: "required",
			subdiv_code: "required",
			division_id: "required"			
        },        
        // Specify the validation error messages
        messages: {
            subdivision: "Subdivision Name is required",
            subdiv_code: "Subdivision Code is required",
			division_id: "Division is required"
        }
    });
	
	$(".confirm").each(function(){
	var subdiv_id = this.id;
	$("#"+subdiv_id).confirm({
		text: "Are you sure that you really want to delete this subdivision?",
		title: "Confirmation required",
		confirm: function(button) {
			$.ajax({
			url: "<?=base_url();?>?c=admin&m=deletesubdivision",
			type: 'POST',
			data: "subdiv_id="+subdiv_id,
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
	
	$('#subdiv_submit').click(function() {
		if($("#subdivision_form").valid()){
			$.confirm({
				text: "Are you sure that you really want to continue?",
				title: "Confirmation required",
				confirm: function(button) {	
					$("form#subdivision_form").submit();
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
	
	$("#subdiv_cancel").click(function(e) {
		e.preventDefault();
		if($("#subdivid").val()){
			window.location.href = "<?=base_url();?>?c=admin&m=subdivision";
		}else{
		 $(':input','#subdivision_form').removeAttr('checked').val('');
		}
	});
	
});
</script>
