<div class="box col-md-6">
        <div class="box-inner">
            <div class="box-header well" data-original-title="" style="padding:6px;">
                <h2><i class=""></i> Print Address</h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round btn-default" style="padding-top:3px;"><i class="glyphicon glyphicon-chevron-up"></i></a>                   
                </div>
            </div>
            <div class="box-content">
                <form role="form" id="print_form" name="print_form" method="post">
					<!-- <div class="form-group">
                        <label for="date">From : </label>
                        <input type="text" class="form-control" id="from_datepicker" name="from" placeholder="From" style="background: url(<?=base_url();?>img/calendar.gif) no-repeat scroll 10px 10px; cursor:pointer; background-position:right 10px top 10px;">
                     
                    </div>
                    
                    <div class="form-group">
                        <label for="date">To : </label>
                        <input type="text" class="form-control" id="to_datepicker" name="to" placeholder="To" style="background: url(<?=base_url();?>img/calendar.gif) no-repeat scroll 10px 10px; cursor:pointer; background-position:right 10px top 10px;">
                       
                    </div>  -->
                    
                    <?php 
					
				
					/*if(!empty($this->session->userdata('print_alias'))) 
					{ 				 			
						$print_alias = $this->session->userdata('print_alias');												
					}  else {
						$print_alias = "";
					}*/
					$print_alias = "";
						$p_alias = $this->session->userdata('print_alias');	
						if(isset($p_alias))
						$print_alias = $p_alias;
					
											 
					?>
                                  
                    <div class="form-group">
                        <label for="alias">Search by Alias : </label>
                        <input type="text" class="form-control" id="alias" name="alias" value="<?php echo $print_alias; ?>" placeholder="Alias to search">
                    <span style="color:#F00;" id="err_msg"></span>    
                    </div> 
					
					<div class="form-group">
                        <label for="for_alias">For : </label>
                        <input type="text" class="form-control" id="for_alias" name="for_alias" value="" placeholder="For">
                    </div>                          
             
                    <button class="btn btn-primary" id="print_address">Print Address</button>
                    
                     <input type="button" class="btn btn-primary" id="reset" value="Reset">
                </form>

            </div>
        </div>
    </div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>
<script>
$(function() {
	
$("#reset").click(function() {
	
	//document.print_form.getElementById('alias').value="";
	var alias =$("#alias").val();
	
	if($("#alias").val()!='')
	{
		$("#alias").val("");
	}
	if($("#for_alias").val()!='')
	{
		$("#for_alias").val("");
	}
	
	<?php $this->session->unset_userdata('print_alias'); ?>
	
	location.reload(); 
	
	
});
	
$("#print_address").click(function(e) {
	e.preventDefault();
	//if($("#print_form").valid()){
	if( ($("#from_datepicker").val()!='' && $("#to_datepicker").val()!='') || $("#alias").val()!=''){
		$("#err_msg").html("Please give Alias to search").hide();
		var modal=$('#myModal').modal('show');
		var alias = $("#alias").val();
		var for_alias = $("#for_alias").val();
		modal.load('<?=base_url();?>?c=outmail&m=print_address', { 'for_alias': for_alias ,'alias': alias} )
	}else{
		$("#err_msg").html("Please give Alias to search").show();
               return false;
		
	}
});

	 $( "#alias" ).autocomplete({
		minLength:2,
		source: function( request, respond ) {
		$.post( "<?php echo base_url(); ?>?c=outmail&m=search_alias", { alias: request.term },
			function( response ) { 
				respond($.parseJSON(response));
			
		});
	 }
	});
});
</script>