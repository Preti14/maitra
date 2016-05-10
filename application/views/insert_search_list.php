<div class="box col-md-12">
    <div class="box-inner">
    <div class="box-header well" data-original-title="">
        <h2><i class=""></i><?php if(isset($response)){}else{ echo "Search Results: ".ucfirst($insert_search_history[0]['template_name']);} ?></h2>

        <div class="box-icon">
            <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
        
        </div>
    </div>
    <div class="box-content">
    <form id="distributedtable" method="post">
    <table id="search_list" class="table table-striped table-bordered bootstrap-datatable datatable responsive">
    <thead>
        <tr>
            <th>Id</th>
            <?php if($insert_search_history[0]['type'] == '1'){ ?>
            	<th>Received Date</th><?php } else {?><th>Dispatched Date</th><?php } ?> 
            <th>Mail Reference</th>  
            <th>Date</th>
            <th>Subject</th>
            <th>From</th>
            <th>To</th>
            <th>Rpt</th>  
        	<th>Division / Subdivision</th>
        </tr>
	</thead>
    <tbody>
		<?php if(isset($insert_search_history)){
			if(empty($insert_search_history)){?>
            <tr><td colspan="8" style="text-align:center">No Mails are found</td></tr>
			<?php } else{
				foreach($insert_search_history as $dist_list){?>
					<?php if($insert_search_history[0]['type']==1){ $div=1;?>
                	<tr style="page-break-after:always;">
                    	<td style="width:30px"><a class="no-print" href="<?=base_url();?>?c=inmail&m=inmailview&mailid=<?=urlencode($dist_list['id'])?>"><?=$dist_list['mail_no'];?></a><span class="only-print"><?=$dist_list['mail_no'];?></span></td><?php }else{?>
                        <td style="width:30px"><a class="no-print" href="<?=base_url();?>?c=outmail&m=outmailview&mailid=<?=urlencode($dist_list['id'])?>"><?=$dist_list['mail_no'];?></a><span class="only-print"><?=$dist_list['mail_no'];?></span></td><?php } ?>
                        <td style="width:100px"><?=$dist_list['recieved_date'];?></td>
                        <td><?=$dist_list['mail_ref'];?></td>
                        <td style="width:120px"><?=$dist_list['date'];?></td>
                        <td><?=$dist_list['subject'];?></td>
                        <td><?=$dist_list['from_mails'];?></td>
                        <td><?php if(!empty($dist_list['to'])){   echo $dist_list['to']; }?></td>
                        <td><?php if(!empty($dist_list['cc'])){ echo $dist_list['cc']; }?></td>
                        <?php if(!empty($dist_list['division'])){
							$div=1; ?>
							<td id="msg_<?=$dist_list['id'];?>"><?=$dist_list['division'];
							if(isset($dist_list['subdivision']))
								echo " / ".$dist_list['subdivision'];?>
							<input type="hidden" name="valid-option" id="<?=$dist_list['id'];?>" value="<?=$dist_list['division_id'];?>"/></td>
						 <?php }else{ 
							$div=0;?>
							<td id="msg_<?=$dist_list['id'];?>">
							<input type="hidden" name="valid-option" id="<?=$dist_list['id'];?>" value="<?=$dist_list['division_id'];?>"/></td>
						<?php } ?>  
					</tr><?php }
				} //end foreach
				 
                }//end else ?> 
    </tbody>
    </table>
    </form>
    <?php /*if(!empty($insert_search_history)){?>
   <button class="btn btn-primary" onclick="window.location='<?=base_url()?>admin/searchlistexport/<?=$insert_search_history[0]['template_name'];?>/<?=$div;?>'">Export</button>
   <?php } */?>
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
	<?php if(isset($insert_search_history)){ ?>
	$('#search_list').dataTable({
				
		"sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row' <'col-md-3' i><'col-md-4' p><'col-md-4' T>>",
		"sPaginationType": "bootstrap",
		"iDisplayLength": 50,
		"bFilter": false,
		"bLengthChange":0,
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
                "print"
            ] }
		
			
    });
	<?php } ?>
});
</script> 
