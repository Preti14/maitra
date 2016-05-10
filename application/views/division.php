<div class="box col-md-6">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class=""></i>
				<?php if(isset($_GET['division_id'])) echo "Update Division";
				else echo "Add Division"; ?></h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>                   
                </div>
            </div>
            <div class="box-content">
                <form role="form" id="division_form" method="post" action="<?=base_url();?>?c=admin&m=division_add">
                
                	<input type="hidden" id="divid" name="divid" value="<?=isset($row['id'])?$row['id']:'';?>">					<?php //echo "<pre>"; print_r($row);?>
                    <div class="form-group">
                        <label for="division">Division : </label>
                        <input type="text" class="form-control" id="txt_division" name="txt_division" placeholder="Division" value="<?=isset($row['division'])?$row['division']:'';?>"> <span style="color:#F00;"></span>
                    </div>
                   
                   <div class="form-group">
                        <label for="code">Code : </label>
                        <input type="text" class="form-control" id="div_code" name="div_code" placeholder="Division Code" value="<?=isset($row['code'])?$row['code']:'';?>"> <span style="color:#F00;"></span>
                    </div>
                   
                   <div style="clear:both"></div>
                    <button id="div_submit" type="submit" class="btn btn-default">Submit</button>
                    <button class="btn btn-default" id="div_cancel">Cancel</button>
                </form>

            </div>
        </div>
    </div>
<div class="box col-md-6">
    <div class="box-inner">
    <div class="box-header well" data-original-title="">
        <h2><i class=""></i> Division List</h2>

        <div class="box-icon">
			<a href="<?php echo base_url();?>?c=admin&m=division" class="" style="width:20px;line-height:0; padding:3px"><i class="glyphicon glyphicon-plus"></i></a>
            <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
        </div>
    </div>
    <div class="box-content">
    <table class="table table-striped table-bordered bootstrap-datatable datatable responsive">
    <thead>
    <tr>
        <th>Code</th>
        <th>Division </th>	
        <th style="text-align:center" colspan="2">Action</th>   
                                                 
    </tr>
    </thead>
    <tbody>
    <?php foreach ($divisions as $div){?>
        <tr>
        	<td><?=$div['code'];?></td>
            <td><?=$div['division'];?></td>
			<td style="text-align:center"><a href="<?=base_url();?>?c=admin&m=division&division_id=<?=$div['id'];?>&status=edit">Edit</a></td>
            <td style="text-align:center"><a href="javascript:void(0);" class="confirm" id="<?=$div['id'];?>">Delete</a></td>
        </tr>
        <?php }?>        
    </tbody>
    </table>
   
    </div>
    </div>
    </div>

<script>
$(function () {
	$("#division_form").validate({ 
		errorElement: "span",
		errorPlacement: function(error, element) {
                        error.appendTo(element.next("span"));
        },   
        // Specify the validation rules
        rules: {
            txt_division: "required",
			div_code: "required",			
        },        
        // Specify the validation error messages
        messages: {
            txt_division: "Division Name is required",
            div_code: "Division Code is required",
			
        }
    });
	
	$(".confirm").each(function(){
	var div_id = this.id;
	$("#"+div_id).confirm({
		text: "Are you sure that you really want to delete this division?",
		title: "Confirmation required",
		confirm: function(button) {
			$.ajax({
			url: "<?=base_url();?>?c=admin&m=deletedivision",
			type: 'POST',
			data: "div_id="+div_id,
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
	
	$('#div_submit').click(function() {
		if($("#division_form").valid()){
			$.confirm({
				text: "Are you sure that you really want to continue?",
				title: "Confirmation required",
				confirm: function(button) {	
					$("form#division_form").submit();
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
	
	$("#div_cancel").click(function(e) {
		e.preventDefault();
		if($("#divid").val()){
			window.location.href = "<?=base_url();?>?c=admin&m=division";
		}else{
		 $(':input','#division_form').removeAttr('checked').val('');
		}
	});
	
});
</script>
