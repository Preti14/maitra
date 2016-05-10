<div class="box col-md-12">
    <div class="box-inner">
    <div class="box-header well" data-original-title="">
        <h2><i class=""></i> Recent Activities</h2>

        <div class="box-icon">
            <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
        
        </div>
    </div>
    <div class="box-content">
    <form id="activities_table">
    <table id="activities_list" class="table table-striped table-bordered bootstrap-datatable datatable responsive">
    <thead>
    <tr>
        <th>No </th>
        <th>Action</th>
        <th>Date</th>
    </tr>
    </thead>
    <tbody>
    <?php if(empty($activities)){?>
		<tr><td colspan="3" style="text-align:center">No Logs are found</td></tr>
    <?php } else{   //echo "<pre>";print_r($outmail_list);
	foreach($activities as $act){?>
        <tr>
            <td><?=$act['id'];?></td>
            <td><?=$act['action'];?></td>
            <td><?=$act['date'];?></td>
        </tr>
        <?php } //end foreach
		} //end else ?> 
    </tbody>
    </table>
   <?php if(!empty($activities)){?>
    <button type="button" id="delete_logs" class="btn btn-default">Delete</button>
    <?php } ?>
    </form>
    </div>
    </div>
    </div>
    
<script>
$(document).ready(function() {
	<?php if(isset($activities)){?>
  $('#activities_list').dataTable({
				
		"sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row' <'col-md-3' i><'col-md-4' p><'col-md-4' T>>",
		"sPaging":true,
		"sPaginationType": "bootstrap",
		"iDisplayLength": 10,
		"aLengthMenu":[ 10, 25, 50, 100 ],
		"bFilter": false,
		"aaSorting": [ [ 0, "desc" ] ],
		"aoColumnDefs":[		
		{  
			"aTargets": [ 0 ],
			"bSortable": true,
			"sType":"num-html",
		}
		],
		 "tableTools": {
            "aButtons": [
                "xls",
				"pdf",
                {
                "sExtends":"print",
				"sMessage": '<div class="datatables-print-msg">' +
                '<a class="btn close-print no-print" href="<?=base_url();?>?c=admin&m=activities">Back</a>' +
                '</div>'
				}
            ] }			
    });
	<?php } ?>
	
	$("#delete_logs").confirm({
		text: "Are you sure that you really want to delete all activities?",
		title: "Confirmation required",
		confirm: function(button) {
			$.ajax({
				url: "<?=base_url()?>?c=admin&m=delete_logs",
				type: "post",
				success:function(response){
					if(response){
						$("#success-msg").html('<strong>Success: </strong>All activities are deleted.');
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
});
</script>
