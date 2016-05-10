<script src="<?=base_url();?>js/jquery.validate.min-1.11.1.js"></script>
<div id="success-msg" class="alert alert-success"  style="display:none"></div>
<div id="error-msg" class="alert alert-danger" style="display:none"></div> 

<div class="box col-md-5">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class=""></i> 
                <?php if(isset($ref) && $ref!='') echo "Edit Out Mail";else echo "Out Mail Entry";?></h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>                   
                </div>
            </div>
            <div class="box-content">
                <form id="outmailentry" role="form" method="post" action="<?=base_url();?>?c=outmail&m=outmailentry_insert&ref=<?php echo $ref; ?>">
                <input  type="hidden" id="type" value="" name="type"/>
                <input  type="hidden" id="mail_type" value="2" name="mail_type" />
                	<input type="hidden" id="edit_mailid" name="edit_mailid" value="<?=isset($row['id'])?$row['id']:'';?>">
                    <!--input type="hidden" id="mailno" name="mailno" value="<?=isset($row['mail_no'])?$row['mail_no']:'';?>"--->					
			<?php  $created_year = isset($row['mail_no'])?date("y", strtotime($row['created_on'])):'';//echo "<pre>"; print_r($row);?>
                    <div class="form-group col-md-6" style="padding-left:0px">
                       <!-- <label for="mailno">Mail No : </label>-->
                        <input type="text" class="form-control" placeholder="Mail No" value="<?=isset($row['mail_no'])?$row['mail_no']:$mail_number;?>" readonly="readonly"  id="mailno" name="mailno">
                    </div>
                   
                    <div class="form-group col-md-6" style="padding-right:0px;padding-left:0px">
                       <!-- <label for="mailref">Mail Ref : </label>-->
                        <input type="text" class="form-control" id="mailref" name="mailref" placeholder="Mail Ref" value="<?=isset($row['mail_ref'])?$row['mail_ref']:'';?>" maxlength="40"> 
                    </div>
                    
                    <div class="form-group col-md-6" style="padding-left:0px">
                       <!-- <label for="date">Mail Date : </label>-->
                        <input type="text" class="form-control" id="datepicker" name="date" placeholder="Date" style="background: url(<?=base_url();?>img/calendar.gif) no-repeat scroll 10px 10px; cursor:pointer; background-position:right 10px top 10px;" value="<?=isset($row['date'])?$row['date']:'';?>">
                    </div>
                    
                    <div class="form-group col-md-6" style="padding-right:0px;padding-left:0px">
                       <!-- <label for="date">Dispatched Date : </label>-->
                        <input type="text" class="form-control" id="recieved_datepicker" name="recieved_date" placeholder="Dispatched Date" style="background: url(<?=base_url();?>img/calendar.gif) no-repeat scroll 10px 10px; cursor:pointer; background-position:right 10px top 10px;" value="<?=isset($row['recieved_date'])?$row['recieved_date']:'';?>">
                    </div>
                               
                    <div class="form-group"  style="clear:both">
                   <input type="hidden" id="from_new" name="from_new"/>
                   <input type="hidden" id="from_title" value="<?=isset($row['from']['title'])?$row['from']['title']:'';?>" /> <input type="hidden" id="from_id" value="<?=isset($row['from']['id'])?$row['from']['id']:'';?>" />
                       <!-- <label for="from">From : </label>-->
                        <div id="wrap">
                        <div class=" form-group box">	
                        <ul data-name="demo4" id="demo4" class="tagit">                       
                        <li style="width:150px" class="tagit-new"><input type="text"  class="tagit-input ui-autocomplete-input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true"><tester style="position: absolute; top: -9999px; left: -9999px; width: auto; font-size: 13.3333px; font-family: MS Shell Dlg; font-weight: 400; letter-spacing: normal; white-space: nowrap;"></tester></li><ul class="ui-autocomplete ui-menu ui-widget ui-widget-content ui-corner-all" role="listbox" aria-activedescendant="ui-active-menuitem" style="z-index: 1; top: 0px; left: 0px; display: none;"></ul></ul>
                        </div>
                    </div>
                    </div>
                   
                    <div class="form-group">
                    	<input type="hidden" id="to_new" name="to_new" />
                        <input type="hidden" id="to_title" value="<?=isset($row['to'])?$row['to']:'';?>" />
                        <input type="hidden" id="to_id" value="<?=isset($row['to_id'])?$row['to_id']:'';?>" />
                        <!--<label for="to">To : </label>-->
                        <div id="wrap">
                        <div class=" form-group box">	
                        <ul data-name="demo2" id="demo2" class="tagit">                      
                        <li style="width:150px" class="tagit-new">
                        <input type="text"  class="tagit-input ui-autocomplete-input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true"><tester style="position: absolute; top: -9999px; left: -9999px; width: auto; font-size: 13.3333px; font-family: MS Shell Dlg; font-weight: 400; letter-spacing: normal; white-space: nowrap;"></tester></li><ul class="ui-autocomplete ui-menu ui-widget ui-widget-content ui-corner-all" role="listbox" aria-activedescendant="ui-active-menuitem" style="z-index: 1; top: 0px; left: 0px; display: none;"></ul></ul>
                        </div>
                    </div>
                    </div>
                    
                    <div class="form-group" id="to_attention">
                    Attention :<a id="to_att_link" href="javascript:void(0);" onclick="togg_to_attention()" style="float:right">Show</a>
                    <table id="to_attention_table" style="border:1px solid #ccc;width:100%;"></table>                
                    </div>
                    
                    <input type="hidden" id="to_attention_values" name="to_attention_values" value="<?=isset($row['to_attention'])?$row['to_attention']:'';?>"/>
                              
                    <div class="form-group">
                    	<input type="hidden" id="cc_new" name="cc_new" />
                        <input type="hidden" id="cc_title" value="<?=isset($row['cc'])?$row['cc']:'';?>" />
                        <input type="hidden" id="cc_id" value="<?=isset($row['cc_id'])?$row['cc_id']:'';?>" />
                       <!-- <label for="cc">Cc : </label>-->
                        <div id="wrap">
                        <div class=" form-group box">	
                        <ul data-name="demo3" id="demo3" class="tagit">                        
                        <li style="width:150px" class="tagit-new"><input type="text"  class="tagit-input ui-autocomplete-input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" ><tester style="position: absolute; top: -9999px; left: -9999px; width: auto; font-size: 13.3333px; font-family: MS Shell Dlg; font-weight: 400; letter-spacing: normal; white-space: nowrap;"></tester></li><ul class="ui-autocomplete ui-menu ui-widget ui-widget-content ui-corner-all" role="listbox" aria-activedescendant="ui-active-menuitem" style="z-index: 1; top: 0px; left: 0px; display: none;"></ul></ul>
                        </div>
                    </div>
                    </div>
                    
                    <div class="form-group" id="cc_attention">
                   Attention :<a id="cc_att_link" href="javascript:void(0);" onclick="togg_cc_attention()" style="float:right">Show</a>
                    <table id="cc_attention_table" style="border:1px solid #ccc;width:100%;"></table>                
                    </div> 
                    <input type="hidden" id="cc_attention_values" name="cc_attention_values" value="<?=isset($row['cc_attention'])?$row['cc_attention']:'';?>"/>                  
                   
                    <div class="form-group">
                       <!-- <label for="subject">Subject : </label>-->
                        <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject" value="<?=isset($row['subject'])?$row['subject']:'';?>">
                    </div>
                    
                    <div class="form-group col-md-6" style="padding-left:0px">
                <!--	<label for="division">Originated By : </label>-->
                    <select class="form-control" id="division_id" name="division_id">
                    	<option class="select-option" value=''>Choose Division </option>
						<?php foreach($divisions as $div){
                        	if($div['id'] == $row['division_id']) {?>
                        		<option class="select-option" value="<?=$div['id'];?>" selected="selected"><?=$div['division'];?></option>
							<?php }else {?>
                            	<option class="select-option" value="<?=$div['id'];?>"><?=$div['division'];?></option>
							<?php } ?>
                        <?php } ?>
                    </select>
                	</div>
                    
                    <div class="form-group col-md-6" style="padding-right:0px;padding-left:0px">
                	<!--<label for="division">Subdivision : </label>-->
                    <select class="form-control" id="subdivision_id" name="subdivision_id">
                    	
                    </select>
                	</div>
                    
                    <div class="form-group" style="clear:both">
                	<!--<label for="comments">Comments:</label><br />-->
        			<textarea class="form-control" id="comments" name="comments" placeholder="Comments"><?=isset($row['comments'])?$row['comments']:'';?></textarea>
                	</div>
                    
                    <div class="checkbox">
                    <label>
                        <input id="hindi" name="hindi" type="checkbox" >Is Hindi
                    </label>
                	</div>
                    <input type="hidden" id="mail_status" name="mail_status" value="<?=isset($row['status'])?$row['status']:'';?>" />
                    <input type="hidden" id="language" name="language" value="<?=isset($row['language'])?$row['language']:'';?>" />
                    <input type="hidden" id="created_on" name="created_on" value="<?=isset($row['created_on'])?$row['created_on']:'';?>" />
                    <input type="hidden" id="created_by" name="created_by" value="<?=isset($row['created_by'])?$row['created_by']:'';?>" />
                    <input type="hidden" id="updated_on" name="updated_on" value="<?=isset($row['updated_on'])?$row['updated_on']:'';?>" />
                    <input type="hidden" id="updated_by" name="updated_by" value="<?=isset($row['updated_by'])?$row['updated_by']:'';?>" />
                    
                     <input type="hidden" id="search_mail_no" name="search_mail_no" value="<?=isset($_GET['mail_number'])?$_GET['mail_number']:'';?>" />
                     
                    <button id="otm_submit" type="submit" class="btn btn-default">Submit</button>
                    <button class="btn btn-default" id="entry_cancel">Cancel</button>
                </form>

            </div>
        </div>
    </div>
    <!--/span-->
<?php  if(!isset($ref) || (isset($ref) && $ref=='')){ ?>  
	<div class="box col-md-7">
    <div class="box-inner">
    <div class="box-header well" data-original-title="">
        <h2><i class=""></i> Out Mail Staging List</h2>

        <div class="box-icon">
            <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
        </div>
    </div>
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
            <td><a href="<?=base_url();?>?c=outmail&m=outmailentry&mailid=<?=$stag_list['id'];?>">
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
     <button type="button" id="distribute" class="btn btn-default">Dispatch</button> 
     <button type="button" id="delete_staging" class="btn btn-default">Delete</button>
     </div>
     <div class="clearfix"></div>
    </form>
    </div>
    <div  style="display:none; border: 2px solid;min-height:470px;height:auto;margin-left: -350.5px;margin-top: 0;position: fixed;top: 50px; width: 450px;z-index: 1052; background-color:white" id="mail-entry-form"  title="Add New Entry" > <div style="background-color:#3986ac;height:50px;" title="Add New Entry"><span style="background-color:#3986ac;height:50px;color:white; font-size:24px; font-size:32px;padding-left:110px">Add New Entry</span></div><div style="height:20px;"></div>
              
              <?php echo validation_errors(); ?>
              <form id="add_entry_form" action="" method="post">
            
                <div class="">
                  <table>
                  <tr>
                    	<div style="padding-bottom:10px"> <label for="title" style="margin-left:50px">Alias : </label><input type="text" name="title" id="title" style="padding-right:50px;margin-left:35px;width:260px;"  class="text ui-widget-content ui-corner-all"></span>
                        </div>
                   </tr>
                  	<tr>
                  		<div style="padding-bottom:10px"><label style="margin-left:50px"  for="name">Name :  </label><input type="text"  style="padding-right:50px;margin-left:30px;width:260px;"  class="text ui-widget-content ui-corner-all" value="" id="name" name="name">
                        </div>
                    </tr>
                   <tr>
                   		<div style="padding-bottom:10px"> <label for="address1" style="margin-left:50px">Address : </label><input type="text" name="address1" id="address1" style="padding-right:50px;margin-left:10px;width:300px;"  class="text ui-widget-content ui-corner-all">
                        </div>
                   </tr>
                   <tr>
                   		<div style="padding-bottom:10px"> <input type="text" name="address2" id="address2" style="padding-right:50px;margin-left:125px;width:300px;"  class="text ui-widget-content ui-corner-all">
                        </div>
                   </tr>
                   <tr>
                   		<div style="padding-bottom:10px"><input type="text" name="address3" id="address3" style="padding-right:50px;margin-left:125px;width:300px;"   class="text ui-widget-content ui-corner-all">
                        </div>
                   </tr>
                   <tr>	
                   		<div style="padding-bottom:10px"> <label for="city" style="margin-left:50px">City : </label><input type="text" name="city" id="city" value="" style="padding-right:50px;margin-left:40px" class="text ui-widget-content ui-corner-all">
                        </div>
                  </tr>
                  <tr>
                    	<div style="padding-bottom:10px"><label for="state" style="margin-left:50px">State : </label><input type="text" class="text ui-widget-content ui-corner-all" value="" id="state" name="state" style="padding-right:50px;margin-left:30px">
                    	</div>      
                  </tr>
                  <tr> 
                   		<div style="padding-bottom:10px"><label for="country" style="margin-left:50px">Country : </label><input style="padding-right:50px;margin-left:11px" type="text" name="country" id="country" value="" class="text ui-widget-content ui-corner-all">          
                   		</div>
                 </tr>
				  <tr><input  type="hidden" id="type" value="" name="type"/> <input  type="hidden" id="mail_type" value="" name="mail_type"/>
                 		<div style="padding-bottom:10px"><label for="pincode" style="margin-left:50px">Pincode : </label><input type="text" class="text ui-widget-content ui-corner-all" style="padding-right:50px;margin-left:10px" value="" id="pincode" name="pincode">
				   </div>	
                 </tr>
				<tr>
               <div style="padding-left:150px"> <button id="submitbutton"  class="btn btn-default"  value="Submit">Submit</button> <span>&nbsp;</span><button id="resetbtn" class="btn btn-default" onclick="close_form()"   >Cancel</button></div>
               
                </tr>	
                
                
<!-- Allow form submission with keyboard without duplicating the dialog button -->
</table>

                </div>
              </form>
            </div>
    </div>
    </div>
<?php } ?>	
<script src="<?=base_url();?>js/tagit.js"></script>    
<script>
$(function () {
	
	$("#mail-entry-form").hide();
	$("#to_new").val('');
	$("#name").val('');
	$("#title").val('');
	$("#address1").val('');
	$("#address2").val('');
	$("#address3").val('');
	$("#city").val('');
	$("#state").val('');
	$("#country").val('');
	$("#pincode").val('');	
	$("#from_new").val('');
	$("#cc_new").val('');
	getMails();	 
	
	if($('ul#demo2 li').hasClass('tagit-choice')){
		$('ul#demo2 li input:text').removeAttr('placeholder');
	}else{
		$('ul#demo2 li input:text').attr('placeholder', 'To');
	}
	if($('ul#demo4 li').hasClass('tagit-choice')){
		$('ul#demo4 li input:text').removeAttr('placeholder');
	}else{
		$('ul#demo4 li input:text').attr('placeholder', 'From');
	}
	if($('ul#demo3 li').hasClass('tagit-choice')){
		$('ul#demo3 li input:text').removeAttr('placeholder');
	}else{
		$('ul#demo3 li input:text').attr('placeholder', 'Cc');
	}
		

	function getMails(){
			res = $.ajax({
				url: '<?=base_url();?>?c=inmail&m=fetch_email',
				async: false,
				success: function(response){},
				dataType:"json",
			
    		});
				
		var availableTags = $.parseJSON(res.responseText);
		$('#demo4').tagit({tagSource:availableTags,maxTags:1});
		$('#demo2').tagit({tagSource:availableTags});
		
		$('#demo3').tagit({tagSource:availableTags});
		}	
		<!--for edit form-->
		var from_title = $("#from_title").val();
		var from_id = $("#from_id").val();
		$('#from_new').val(from_id+'|');  
		$('#demo4').tagit('add',from_title);
		if(!from_title){
			from_address = '<?=isset($settings['from_address'])?$settings['from_address']:'';?>';
			$('#demo4').tagit('add',from_address);
			frm_id = '<?=isset($settings['from_address_id'])?$settings['from_address_id']:'';?>'; 
			$('#from_new').val(frm_id+'|');
		}
		
		var to_title = $("#to_title").val();
		if(to_title){
			var to_id = $("#to_id").val();
			$('#to_new').val(to_id+'|'); 
			//$('#demo2').tagit('add',to_title);
			var words = to_title.split("~");
			var to_attention =  $("#to_attention_values").val();
			var to_att = to_attention.split("~");
			for(var i=0;i<words.length;i++){
				$('#demo2').tagit('add',{label: words[i], value: words[i]});
				var to_att_val='';
				if(to_att[i])
					to_att_val = to_att[i];
				$("#to_attention_table").append('<tr><td><span name="'+words[i]+'">'+words[i]+'</span></td><td>For <input type="text" class="form-control"  value="'+to_att_val+'"/></td></tr>');
			}
		}
		
		var cc_title = $("#cc_title").val();
		if(cc_title){
			var cc_id = $("#cc_id").val();
			$('#cc_new').val(cc_id+'|'); 
			//$('#demo2').tagit('add',to_title);
			var words = cc_title.split("~");
			var cc_attention =  $("#cc_attention_values").val();
			var cc_att = cc_attention.split("~");
			for(var i=0;i<words.length;i++){
				$('#demo3').tagit('add',{label: words[i], value: words[i]});
				var cc_att_val='';
				if(cc_att[i])
					cc_att_val = cc_att[i];
				$("#cc_attention_table").append('<tr><td><span name="'+words[i]+'">'+words[i]+'</span></td><td>For <input type="text" class="form-control"  value="'+cc_att_val+'"/></td></tr>');
			}
		}
 
		<!----end edit form->
	
		
	$('#demo2ResetTags').click(function () {
		$('#demo2').tagit('reset');
	});
	
	function showTags_to(tags) { 
		
		console.log(tags);
		var string='';
		var string1 ='';
		for (var i in tags) { 
			var form_data = {
				title: tags[i].value,
			};
			//alert(form_data);
			result = $.ajax({
					url: "<?php echo site_url()?>?c=inmail&m=fetch_address_id",
					type: 'POST',
					data: form_data,
					dataType:"json",
					async: false,
					success: function(response) {
					}
				  });
			 string = $.parseJSON(result.responseText);
			 //alert(string);
			 string1 += string  +"|";
			
		}
		//alert(string1);
		$("#to_new").val(string1);
	}
	
	function showTags_cc(tags) { 
		
		console.log(tags);
		var string='';
		var string1 ='';
		for (var i in tags) { 
			var form_data = {
				title: tags[i].value,
			};
			
			result = $.ajax({
					url: "<?php echo site_url()?>?c=inmail&m=fetch_address_id",
					type: 'POST',
					data: form_data,
					dataType:"json",
					async: false,
					success: function(response) {
					 //return response.address_id;
					 }
				  });
			 string = $.parseJSON(result.responseText);
			 string1 += string  +"|";
			
		}
		//alert(string1);
		$("#cc_new").val(string1);
	}

	
	function showTags_from(tags) { 
		
		console.log(tags);
		var string='';
		var string1 ='';
		for (var i in tags) { 
			var form_data = {
				title: tags[i].value,
			};
			
			result = $.ajax({
					url: "<?php echo site_url()?>?c=inmail&m=fetch_address_id",
					type: 'POST',
					data: form_data,
					dataType:"json",
					async: false,
					success: function(response) {
					 //return response.address_id;
					 }
				  });
			 string = $.parseJSON(result.responseText);
			 string1 += string  +"|";
			
		}
		//alert(string1);
		$("#from_new").val(string1);
	}


	setInterval("$('#fork').effect('pulsate', { times:1 }, 500);", 5000);
	
	$("ul").click(function(e) {
      var strChosen = $(this).attr('id');
     $("#type").val(strChosen); 
    });
	
	$("#demo2").focusout(function (){
     	showTags_to($("#demo2").tagit("tags"));
		 
    });
	
	$("#demo3").focusout("keyup", function(){
     	showTags_cc($("#demo3").tagit("tags"));
		 
    });
	
	$("#demo4").focusout("keyup", function(){
     	showTags_from($("#demo4").tagit("tags"));
		 $(".tagit-input").val("");
    });
	
	$("#resetbtn").click(function(e) {
		$("#mail-entry-form").easyModal({
						
							overlayClose: true,
							closeOnEscape: true
						}); 
						$(".tagit-input").val("");	
						$("#form-bg").remove(); 			
						return false;
		
        
    });
	
	$('#submitbutton').click(function() {	
			
			if($("#add_entry_form").valid()){	
			
				var txt_type = $('#type').val();
				  var form_data = {
					name: $('#name').val(),
					title: $('#title').val(),
					address1: $('#address1').val(),
					address2: $('#address2').val(),
					address3: $('#address3').val(),
					city: $('#city').val(),
					state: $('#state').val(),
					country: $('#country').val(),
					pincode: $('#pincode').val(),
					type: $('#mail_type').val(),
					
				  };
				
				  $.ajax({
					url: "<?php echo site_url()?>?c=inmail&m=insert_address",
					type: 'POST',
					data: form_data,
					dataType:"json",
					success: function(response) {
					  var opts = (response);
						
					  if(txt_type == "demo2"){				  
						$("#demo2").tagit("add",opts.title);
						showTags_to($("#demo2").tagit("tags"));	
					  
					  }
					   if(txt_type == "demo3"){				  
						$("#demo3").tagit("add",opts.title);		
						showTags_cc($("#demo3").tagit("tags"));
					  }
					  if(txt_type == "demo4"){
						 $("#demo4").tagit("add",opts.title);		
						showTags_from($("#demo4").tagit("tags"));
						$("#demo4").attr('disabled','disabled'); 
					  }
					$("#form-bg").remove(); 
					$("#mail-entry-form").hide();
					$('#name').val('');
					$('#title').val('');
					$('#address1').val('');
					$('#address2').val('');
					$('#address3').val('');
					$('#city').val('');
					$('#state').val('');
					$('#country').val('');
					$('#pincode').val('');
					$("#form-bg").remove(); 	
					}
				  });
			}
		 return false;
		 
		 
		 
		});
	
	
	
	
	$.validator.addMethod("custom_uniquetitle", function(value,element) {
		var url="<?php echo base_url(); ?>?c=inmail&m=fetch_title";
		var title= $("#title").val();
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
	}, "Already exits title.");
	
	
	$("#add_entry_form").validate({
    
        // Specify the validation rules
        rules: {
           // name: "required",
            title: {
				required:true,
				custom_uniquetitle:true,				
			},
			city: "required"/*,
			pincode : {
				required:true,
				number:true,
				minlength:6,
				maxlength:6				
			},*/
			
			
           
        },
        
        // Specify the validation error messages
        messages: {
            //name: "Please enter Name",
			 title: {
				 required: "Please enter Title",
                custom_uniquetitle: "Already Exist title"
            },
            city: "Please enter City"/*,
			 pincode: {
				 required: "Please enter Pincode",
                 number: "Please enter only number",
				 minlength:"Please enter within 6 Digit",
				 maxlength: "Please enter within 6 Digit"
            },*/
            
        },
        
        submitHandler: function(form) {
            form.submit();
        }
    });
	
	

	
	
	$("#otm_submit").click(function() {
		var to_values = $("#to_attention input").map(function() {
            return $(this).val();
		}).get().join("|");
		$("#to_attention_values").val(to_values);
		var cc_values = $("#cc_attention input").map(function() {
            return $(this).val();
		}).get().join("|");
		$("#cc_attention_values").val(cc_values);
	});
	
	
	
	//to set division in edit form
	var div_id=$( "#division_id" ).val();
	var subdiv_id='<?=isset($row['subdivision_id'])?$row['subdivision_id']:'';?>';
	fetch_subdiv(div_id, subdiv_id);
	
	//to post the value of selected option in dropdown
	$( "#division_id" ).change(function () {
		var div_id=$( "#division_id" ).val();
		fetch_subdiv(div_id);
	});
	
	
	$("#otm_submit").confirm({
		text: "Are you sure that you really want to continue?",
		title: "Confirmation required",
		confirm: function(button) {
			$('form#outmailentry').submit();
		},
		cancel: function(button) {
			return false;
		},
		confirmButton: "Yes",
		cancelButton: "No",
		post: true
	});
	
	$("#entry_cancel").click(function(e) {
		e.preventDefault();
		if($("#mailno").val()){
			<?php if(isset($_GET['ref'])) {?>
			var search_url='';
			<?php if(isset($_GET['mail_number'])) {?>
			search_mail_no = '<?php echo $_GET['mail_number'];?>';
			search_url = "&mail_number="+search_mail_no;
			<?php } ?>
			window.location.href = "<?=base_url();?>?c=admin&m=manage_outmails"+search_url;
			<?php }else{ ?>
			window.location.href = "<?=base_url();?>?c=outmail&m=outmailentry";
			<?php } ?>
		}else{
		 $(':input','#outmailentry').removeAttr('checked').val('');
		 $('#demo2').tagit('reset');
		 $('#demo3').tagit('reset');
		 $('#demo4').tagit('reset');
		 $("#to_attention_table tr").remove();
		 $("#cc_attention_table tr").remove();
		}
	});
	
	$( "#subject" ).autocomplete({
		minLength:2,
		source: function( request, respond ) {
		$.post( "<?php echo base_url(); ?>?c=inmail&m=subject_autocomp", { subject: request.term },
			function( response ) { 
				respond($.parseJSON(response));
			
		});
	 }
	});
	
	

	$("#to_attention_table").css("display","none");
	$("#cc_attention_table").css("display","none");
	
	//to set language hindi in edit form 
	var lang = $("#language").val();
	if(lang=='Hindi')
		$("#hindi").prop('checked',true);
		
		
	/*Start Staging list*/	
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
									
									if(response == 1){
										$("#success-msg").html('<strong>Success: </strong>The selected mails are added to dispatched list.');
										$("#success-msg").slideDown();
										window.scrollTo(10,0);
										setTimeout(function(){ 
											window.location.reload(true);
										},2000);						
									}else{
										$("#error-msg").html('<strong>Error: </strong> Incomplete mails cannot be dispatched.');
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
		}
		else if( value == 1){
			span=$('<span class="label label-warning">Nil Initiate By</span>');	
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
	/*End staging list*/
});



function fetch_subdiv(div_id,subdiv_id){ 
	$.getJSON("<?=base_url();?>?c=inmail&m=fetchsubdivision",{div_id: div_id}, function(j){
			var options = '';
			options += '<option class="select-option" value="" >Choose Subdivision </option>';
			for (var i = 0; i < j.length; i++) {
				if(j[i].id==subdiv_id)
        		options += '<option selected="selected" class="select-option" value="' + j[i].id + '">' + j[i].code + '</option>';
				else
				options += '<option class="select-option" value="' + j[i].id + '">' + j[i].code + '</option>';
      		}
			$("select#subdivision_id").html(options);
    	});
}

function togg_to_attention(){
	$("#to_attention_table").toggle();
	if($("#to_att_link").html()=='Show')
		$("#to_att_link").html('Hide');
	else
		$("#to_att_link").html('Show');
	
}
function togg_cc_attention(){
	$("#cc_attention_table").toggle();
	if($("#cc_att_link").html()=='Show')
		$("#cc_att_link").html('Hide');
	else
		$("#cc_att_link").html('Show');
	
}

</script>
<style>
.error {
	margin-right:50px;margin-left:125px;color:red;	
}
</style>
