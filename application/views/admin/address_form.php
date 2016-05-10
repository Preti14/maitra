<script src="<?=base_url();?>js/jquery.validate.min-1.11.1.js"></script>
<script>
$(document).ready(function() {	
	
	var addr_type = '<?=isset($address['type'])?$address['type']:'';?>';
	if(addr_type){
		$("#address_type").val(addr_type);
	}

	$.validator.addMethod("custom_uniquetitle", function(value,element) {
		var url="<?php echo base_url(); ?>?c=inmail&m=fetch_title";
		var title= $("#alias").val();
		result = $.ajax({
			type: "POST",
			url: url,
			data:{ 'title': title },
			cache: false,
			async: false, 
			dataType: "text",
			success: function (response)	
			{	
				//alert(response);			
				if(response !=''){
					return false;	
				}
			}}).responseText ;
			//alert(result);
			if(result) 
			return false;
		else return true;
	}, "Already exits Alias.");
	
	$("#submitbutton").click(function(){
	var addr_id = '<?=isset($address['id'])?$address['id']:'';?>';
		if(addr_id)
			$( "#alias" ).rules( "remove", "custom_uniquetitle" );
	});
	
		 
$("#add_address_form").validate({
    	errorElement: "span",
		errorPlacement: function(error, element) {
                        error.appendTo(element.next("span"));
        },
        // Specify the validation rules
        rules: {
           // name: "required",
            alias: {
				required:true,
				custom_uniquetitle:true,				
			},
			city: "required",
			pincode : {
				required:true,
				number:true,
				minlength:6,
				maxlength:6				
			},
			address_type: "required",
			
           
        },
        
        // Specify the validation error messages
        messages: {
           // name: "Please enter Name",
			 alias: {
				 required: "Please enter Title",
                custom_uniquetitle: "Already Exist Alias"
            },
            city: "Please enter City",
			 pincode: {
				 required: "Please enter Pincode",
                 number: "Please enter only number",
				 minlength:"Please enter 6 Digit",
				 maxlength: "Please enter 6 Digit"
            },
            address_type: "Please enter the address type",
        },
        
        submitHandler: function(form) {
           form.submit();
        }
    });
});
</script>
<div class="box col-md-6">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class=""></i> Address Form</h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>                   
                </div>
            </div>
            <div class="box-content">              
            	<?php echo validation_errors(); ?>
            	<form role="form" id="add_address_form" action="<?=base_url();?>?c=admin&m=add_address" method="post">
                <input type="hidden" id="addr_id" name="addr_id" value="<?=isset($address['id'])?$address['id']:'';?>">	
                <div class="form-group">
                	<label for="title">Alias* : </label>
                	<input type="text" name="alias" id="alias" class="form-control" value="<?=isset($address['title'])?$address['title']:'';?>"><span style="color:#F00;"></span>
                    </div>
				<div class="form-group">
                	<label for="name">Name : </label>
                	<input type="text" class="form-control" value="<?=isset($address['name'])?$address['name']:'';?>" id="name" name="name"><span style="color:#F00;"></span>
                    </div>
                <div class="form-group">
                	<label for="address1">Address1 : </label>
                	<input type="text" name="address1" id="address1"  class="form-control" value="<?=isset($address['address1'])?$address['address1']:'';?>">
                    </div>
                <div class="form-group">
                	<label for="address2">Address2 : </label>
                	<input type="text" name="address2" id="address2"  class="form-control" value="<?=isset($address['address2'])?$address['address2']:'';?>">
                    </div>
                <div class="form-group">
                	<label for="address3">Address3 : </label>
                	<input type="text" name="address3" id="address3" class="form-control" value="<?=isset($address['address3'])?$address['address3']:'';?>">
                    </div>
                <div class="form-group">
                	<label for="city">City* : </label>
                	<input type="text" name="city" id="city" value="<?=isset($address['city'])?$address['city']:'';?>" class="form-control"><span style="color:#F00;"></span>
                    </div>
				<div class="form-group">
                	<label for="state">State : </label>
                	<input type="text" class="form-control" value="<?=isset($address['state'])?$address['state']:'';?>" id="state" name="state">
                </div>
				<div class="form-group">
                	<label for="country">Country : </label>
                	<input type="text" name="country" id="country" value="<?=isset($address['country'])?$address['country']:'';?>" class="form-control"> 
                </div>
                <input  type="hidden" id="type" value="" name="type"/> 
				<div class="form-group">
                	<label for="pincode">Pincode* : </label>
                	<input type="text" class="form-control" value="<?=isset($address['pincode'])?$address['pincode']:'';?>" id="pincode" name="pincode"><span style="color:#F00;"></span>
				</div>
                <div class="form-group">
                	<label for="address_type">Address Type* : </label>
                    <select class="form-control" id="address_type" name="address_type">
                    	<option class="select-option" value=''>Choose Address Type</option>
                        <option class="select-option" value='1'>Permanent</option>
                        <option class="select-option" value='2'>Temporary</option>
                        </select><span style="color:#F00;"></span>
                    </div>
                    
                     <input type="hidden" id="search_addr" name="search_addr" value="<?=isset($_GET['address'])?$_GET['address']:'';?>" />
                	<button id="submitbutton"  class="btn btn-default" type="submit" value="Submit">Submit</button>
				
            	</form>
			</div>
        </div>
    </div>
    