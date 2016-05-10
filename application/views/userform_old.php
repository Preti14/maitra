<div id="success-msg" class="alert alert-success"  style="display:none"></div>
<div id="error-msg" class="alert alert-danger" style="display:none"></div>
<div class="box col-md-5" >
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class=""></i>
				<?php if(isset($_GET['userid'])) echo "Edit User Form";
				else echo "Add User Form"; ?></h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>                   
                </div>
            </div>
            <div class="box-content">
                <form name="ad_employee_search_form" id="ad_employee_search_form">
                    <div class="form-group">
                        <button type="button" id="ad_employee_search_button" class="btn btn-primary pull-right" style="position: absolute; z-index: 9; height:38px; right:35px;"><i class="glyphicon glyphicon-search"></i></button>
                        <input name="ad_employee_search" class="form-control" placeholder="Employee Number" id="ad_employee_search" value="" type="text">
                    </div>
                </form>
                <form role="form" name="user_form" id="user_form" method="post" action="<?php echo base_url();?>?c=admin&m=user_insert">
					<?php echo validation_errors();?><?php //echo "<pre>";print_r($row);?>
                	<input type="hidden" id="edit_userid" name="edit_userid" value="<?php echo isset($row['id'])?$row['id']:'';?>">				
                    <div class="form-group">
                        <label for="mailref">First Name* : </label>
                        <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" value="<?php echo isset($row['firstname'])?$row['firstname']:'';?>"><span style="color:#F00;"></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="mailref">Last Name : </label>
                        <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name" value="<?php echo isset($row['lastname'])?$row['lastname']:'';?>"><span style="color:#F00;"></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="mailref">Email* : </label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="<?php echo isset($row['email'])?$row['email']:'';?>"><span style="color:#F00;"></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="mailref">Mobile Number : </label>
                        <input type="text" class="form-control" id="mobile_no" name="mobile_no" placeholder="Mobile Number" value="<?php echo isset($row['mobile_no'])?$row['mobile_no']:'';?>">
                        <span style="color:#F00;"></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="mailref">User Name* : </label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="User Name" value="<?php echo isset($row['username'])?$row['username']:'';?>"><span style="color:#F00;"></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="mailref">Password* : </label>
                        <input type="password" class="form-control" id="input_password" name="input_password" placeholder="Password" value="" autocomplete="off"><span style="color:#F00;"></span>
                        <input type="hidden" id="exist_password" name="exist_password"value="<?php echo isset($row['password'])?$row['password']:'';?>">
                    </div>
                    
                     <div class="form-group">
                        <label for="mailref">Confirm Password* : </label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" value="" autocomplete="off"><span style="color:#F00;"></span>
                    </div>      
                   
                    <div class="form-group">
                        <label for="role_popup">Role* :</label>
                        <button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#role_assigning_modal">
                            Add
                            <i class="glyphicon glyphicon-plus"></i>
                        </button>
                    </div>
<!--                    <div class="form-group">
                	<label for="user type">User Type* : </label>
                    <select class="form-control" id="user_type" name="user_type">
                    	<option class="select-option" value=''>Choose Option </option>
						<?php foreach($user_types as $ut){?>
                        <option class="select-option" value="<?php echo $ut['id'];?>"><?php echo $ut['user_type'];?></option>
                        <?php } ?>
                    </select><span style="color:#F00;"></span>
                	</div>
                    
                    <div id="division_part">
                    <div class="form-group">
                	<label for="division">Division: </label>
                    <select class="form-control" id="division_id" name="division_id">
                    	<option class="select-option" value=''>Choose Option </option>
						<?php foreach($divisions as $div){?>
                        <?php if($div['id']==$row['division_id']){?>
                        <option class="select-option" value="<?php echo $div['id'];?>" selected><?php echo $div['division'];?></option>
                        <?php }else{?>
                     	<option class="select-option" value="<?php echo $div['id'];?>"><?php echo $div['division'];?></option>
                        <?php } } ?>
                    </select>
                	</div>
                    </div>
                    <div id="subdivision_part">
                    <div class="form-group">
                	<label for="division">Subdivision : </label>
                    <select class="form-control" id="subdivision_id" name="subdivision_id">
                    	
                    </select>
                	</div>
                    </div>-->
                    
                    <input type="hidden" id="user_type_id" name="user_type_id" value="<?php echo isset($row['user_type_id'])?$row['user_type_id']:'';?>" />
            
                    <div id="role_table_wrapper" class="form-group">
                        <table id="role_table" class="table table-striped table-bordered bootstrap-datatable datatable responsive">
                            <thead>
                                <tr>
                                    <th>Role</th>
                                    <th>Department</th>
                                    <th>Default</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($_GET['userid'])) { ?>
                                    <?php foreach ($role_detail as $value) { ?>
                                    <tr data-id="">
                                        <td data-role_id="<?php echo $value->role_id ?>">
                                            <?php echo $value->role ?>
                                        </td>
                                        <td data-division_id="<?php echo $value->division_id ?>"
                                            data-sub_division_id="<?php echo $value->sub_division_id ?>">
                                        <?php echo $value->department ?>
                                        </td>
                                        <td class="text-center">
                                            <input name="default" type="radio" 
                                                   <?php if($value->is_default) { ?>checked="" <?php } ?>
                                                   >
                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="delete"><i class="glyphicon glyphicon-remove-circle" data-original-title="Delete" data-toggle="tooltip"></i></a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr class="no_records"><td colspan="5" style="text-align:center">No Roles are found</td></tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <button id="user_submit" type="submit" class="btn btn-default">Submit</button>
                    <button class="btn btn-default" id="user_cancel">Cancel</button>
                </form>

            </div>
        </div>
    </div>
<div class="box col-md-7">
    <div class="box-inner">
    <div class="box-header well" data-original-title="">
        <h2><i class=""></i> Users List</h2>
		
        <div class="box-icon">
			<a href="<?php echo base_url();?>?c=admin&m=users" class="" style="width:20px;line-height:0; padding:3px"><i class="glyphicon glyphicon-plus"></i></a>
            <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
        
        </div>
    </div>
    <div class="box-content">
    <form id="distributedtable" method="post">
    <table class="table table-striped table-bordered bootstrap-datatable datatable responsive">
    <thead>
    <tr>
    	<th>User Details</th>
        <!--<th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>-->
        <th>User Type</th>
        <th style="text-align:center" colspan="2">Action</th>
    </tr>
    </thead>
    <tbody>
    <?php if(empty($users_list)){?>
		<tr><td colspan="6" style="text-align:center">No Users are found</td></tr>
    <?php } else{  //echo "<pre>";print_r($users_list);
	foreach($users_list as $user){?>
        <tr>
        	<td><strong>User Name :</strong> <?php echo $user['username'];?> <br />
             <strong>First Name :</strong> <?php echo $user['firstname'];?> <br />
             <strong>Last Name :</strong> <?php echo $user['lastname'];?> <br />
             <strong>Email :</strong> <?php echo $user['email'];?></td>
            
            
            
            <?php if(isset($user['division'])) {
				$div_details = "Division: ".$user['division'];
				$div_details.=isset($user['subdivision'])?"Subdivision: ".$user['subdivision']:'';?>
            <td><a data-toggle="tooltip" title="" href="#" data-original-title="<?php echo $div_details;?>"><?php echo $user['user_type'];?></a></td><?php }else{ ?>
            <td><?php echo $user['user_type'];?></td><?php } ?>
            <td style="text-align:center"><a href="<?php echo base_url();?>?c=admin&m=users&userid=<?php echo $user['id'];?>">Edit</a></td>
            <td style="text-align:center"><a class="confirm" href="javascript:void(0);" id="<?php echo $user['id'];?>">Delete</a></td>
        </tr>
        <?php } //end foreach
		} //end else ?> 
    </tbody>
    </table>
    </form>
    </div>
    </div>
</div> 
<?php $this->load->view('partial/role_assigning_popup'); ?>
<script>
$(document).ready(function() {

	$("#division_part").hide();
	$("#subdivision_part").hide();
	var user_type_id=$("#user_type_id").val();
	var div_id=$( "#division_id" ).val();
	
	if(user_type_id!=''){
		$("#user_type").val(user_type_id);
		if($("#user_type").val()==3){
			$("#division_part").show('slow');
			$("#subdivision_part").hide('');
			$("#subdivision_id").val('');
		}else if($("#user_type").val()==4){
			$("#division_part").show('');
			$("#subdivision_part").show('');
		}else{
			$("#division_part").hide();
			$("#subdivision_part").hide('');
		}		
	}
	if(div_id!=''){
		var subdiv_id='<?php echo isset($row['subdivision_id'])?$row['subdivision_id']:'';?>';
		fetch_subdiv(div_id, subdiv_id);
	}
	
	$("#user_type").change(function() {
		if($("#user_type").val()==3){
			$("#division_part").show('slow');
			$("#subdivision_part").hide('');
			$("#subdivision_id").val('');
		}else if($("#user_type").val()==4){
			$("#division_part").show('');
			$("#subdivision_part").show('');
		}else{
			$("#division_part").hide();
			$("#subdivision_part").hide('');
		}
	});
	
	$( "#division_id" ).change(function () {
		var div_id=$( "#division_id" ).val();
		fetch_subdiv(div_id);
	});
	
	$.validator.addMethod("check_exist", function(value, element) {
		  // allow any non-whitespace characters as the host part
		 
		 var x = $.ajax({
			url: "<?php echo base_url();?>?c=admin&m=check_username",
			type: 'POST',
			async: false,
			data: "username="+value,
		 }).responseText;
			if(x==1)
				return true;
			else return false;
		},"Username already exist");
		
	$("#user_form").validate({
    	errorElement: "span",
		errorPlacement: function(error, element) {
                        error.appendTo(element.next("span"));
        },
        // Specify the validation rules
        rules: {
            firstname:{
				 required : true,
				 maxlength:15
			},
			lastname:{
				 maxlength:15
			},
            email: {
				required:true,
				email:true,				
			},
			mobile_no: {
				number:true,
				minlength:10,
				maxlength:10
			},
			username:{
				 required : true,
				 minlength:4,
				 maxlength:15,
				 check_exist:true
			},
			input_password : {
				required:true,
				minlength:6			
			},
			confirm_password : {
				required:true,
				minlength:6,	
				equalTo: "#input_password"		
			},
			user_type: "required",
			division_id: "required"
           
        },
        
        // Specify the validation error messages
        messages: {
            firstname:{
				required: "Please enter First Name",
				maxlength:"First name exceeds the maximum characters"
			},
			lastname:{
				 maxlength:"Last name exceeds the maximum characters"
			},
			email: {
				required: "Please enter The Email",
                email: "Please enter The Email in correct format"
            },
			mobile_no:{
				number: "Please enter mobile number in correct format",
				minlength:"Please enter 10 numbers",
				maxlength:"Please enter 10 numbers"
			},
            username:{
				required: "Please enter User Name",
				minlength:"Please enter atleast 4 chararecters",
				maxlength:"Username exceeds the maximum characters"
			},
			input_password: {
				 required: "Please enter Password",
				 minlength:"Please enter atleast 6 Digit"
            },
			confirm_password: {
				 required: "Please enter the Confirm Password",
				 minlength:"Please enter atleast 6 Digit",
				 
            },
            user_type: "Please enter User Type",
			division_id: "Please enter the division"
        }
    });
	
	
	
	/*$('#user_form').submit(function() {
		if($("#user_form").valid()){
		   var status = confirm("Click OK to continue?");
		   if(status == false){
		   		return false;
		   }
		   else{
		   		return true; 
		   }
		}
	});	*/	
	$('#user_submit').click(function() {
		var edit_userid=$("#edit_userid").val();
		if(edit_userid!=''){
			$('#input_password').rules('remove',"required");
			$('#confirm_password').rules('remove',"required");
			$('#username').rules('remove',"check_exist");
		}
		if($("#user_form").valid()){
			$.confirm({
				text: "Are you sure that you really want to continue?",
				title: "Confirmation required",
				confirm: function(button) {	
					$("form#user_form").submit();
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

	$(".confirm").each(function(){
	var user_id = this.id;
	$("#"+user_id).confirm({
		text: "Are you sure that you really want to delete this user?",
		title: "Confirmation required",
		confirm: function(button) {
			$.ajax({
			url: "<?php echo base_url();?>?c=admin&m=deleteuser",
			type: 'POST',
			data: "user_id="+user_id,
			success: function(response) {
				if(response==1){
					$("#success-msg").html('<strong>Success: </strong> The user has been deleted Successfully.');
						$("#success-msg").slideDown();
						window.scrollTo(10,0);
						setTimeout(function(){ 
							window.location.reload(true);
						},2000);
					
				}else if(response == "error"){
					$("#error-msg").html('<strong>Error: </strong> The user cannot be deleted. This user has been assigned for some inmails/outmails.');
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
	});	
	
	$("#user_cancel").click(function(e) {
		e.preventDefault();
		if($("#edit_userid").val()){
			window.location.href = "<?php echo base_url();?>?c=admin&m=users";
		}else{
		 $(':input','#user_form').removeAttr('checked').val('');
		}
	});
});

function fetch_subdiv(div_id,subdiv_id){
	$.getJSON("<?php echo base_url();?>?c=admin&m=ajax_multiple_sub_division",{div_id: div_id}, function(j){
			var options = '';
//			options += '<option class="select-option" value="" >Choose Subdivision </option>';
			for (var i = 0; i < j.length; i++) {
				if(j[i].id==subdiv_id)
        		options += '<option data-div_id = ' + j[i].division_id + ' selected="selected" class="select-option" value="' + j[i].id + '">' + j[i].code + '</option>';
				else
				options += '<option data-div_id = ' + j[i].division_id + ' class="select-option" value="' + j[i].id + '">' + j[i].code + '</option>';
      		}
			$("select#subdivision_id").html(options);
    	});
}

</script>
