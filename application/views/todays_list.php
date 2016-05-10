<div class="box col-md-12">
    <div class="box-inner">
    <div class="box-header well" data-original-title="">
        <h2><i class=""></i><?php //echo "<pre>";print_r($response);die;
        
        if(isset($_REQUEST['division_id']))	{			
			$division_url = "&division_id=".$_REQUEST['division_id']; 
			} else { $division_url =""; }
			
		if(isset($_REQUEST['subdivision_id']))	{			
			$subdivision_url = "&subdivision_id=".$_REQUEST['subdivision_id']; 
			} else { $subdivision_url=""; }
			
		if(isset($_REQUEST['temp_name']))	{			
			$temp_name = "&temp_name=".$_REQUEST['temp_name']; 
			} else { $temp_name=""; }
            
		echo "Search Results: ";
		if(isset($template_name)) echo ucfirst($template_name);	?></h2>

        <div class="box-icon">
            <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
        
        </div>
    </div>
    <div class="box-content"><?php $login_data = $this->session->userdata('check_login');?>
    <form id="distributedtable" method="post">
    <div class="col-md-8"><?php echo $this->pagination->create_links(); ?></div>
<div class="col-md-4">
<h4 align="right"><strong><?php if(isset($total_rows)) echo "No of Records: ".$total_rows ; ?>
</strong></h4></div>
<div class="clearfix"></div>
    <table id="todays_list" class="table table-striped table-bordered bootstrap-datatable datatable responsive add-style">
    <thead>
    <tr>
        <th class="no-print" style="width:7%">Id</th><th class="only-print">Id</th>
        <?php if(isset($response) and !empty($response)){ 
			if($response[0]['type'] == '1'){ ?>
		<th class="date-length" style="width:9%">Received Date</th><?php } else {?><th class="date-length" style="width:9%">Dispatch Date</th><?php } } ?>
        <th class="ref-length" style="width:8%">Mail Ref</th>
        <th class="date-length" style="width:9%">Date</th>
        <th class="sub-length" style="width:15%">Subject</th>
        <th style="width:8%">From</th>
        <th style="width:9%">To</th>
		<th class="date-length" style="width:9%">Action Date</th> 
        <th style="width:9%">Division / Subdivision</th>
         <?php if($login_data['login_type']==3) { ?>
       
        <th class="date-length" style="width:9%">Close Date</th>
        <?php } ?>
        <th>Comments</th>
    </tr>
    <tbody>
    <?php if(isset($response)){
		
		if(empty($response)){?>
		<tr><td colspan="10" style="text-align:center">
        <?php if(isset($searchmsg))echo $searchmsg; else echo "No Mails are found"; ?></td></tr>
    	<?php } else{   
			foreach($response as $key => $dist_list){
				 $created_year = date("y", strtotime($dist_list['created_on']));
				 $mailno_with_year = $dist_list['mail_no'];?>
			<tr style="page-break-after:always;"><?php if($response[0]['type'] == '1'){  ?>            
            	<td class="no-print"><a href="<?=base_url();?>?c=inmail&m=inmailview&mailid=<?=urlencode($dist_list['id']).$division_url.$subdivision_url.$temp_name;?>"><?=$mailno_with_year;?></a></td>
		<td class="only-print"><?=$mailno_with_year;?></td><?php }else{ ?>
				<td class="no-print"><a href="<?=base_url();?>?c=outmail&m=outmailview&mailid=<?=urlencode($dist_list['id']).$division_url.$subdivision_url.$temp_name; ?>"><?=$mailno_with_year;?></a></td>
				<td class="only-print"><?=$mailno_with_year;?></td>	
			<?php	}?>
            	<td class="date-length"><?=$dist_list['recieved_date'];?></td>
                <td class="ref-length"><?=$dist_list['mail_ref'];?></td>
                <td class="date-length"><?=$dist_list['date'];?></td>
				<td class="sub-length"><?=$dist_list['subject'];?></td>
				<td><?php if(!empty($dist_list['from'])){ echo $dist_list['from']; } ?></td>
				<td><?php if(!empty($dist_list['to'])){ echo $dist_list['to']; }?></td>
              	 <td class="date-length"><?=$dist_list['action_date'];?></td>                
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
                 <?php if($login_data['login_type']==3) { ?>
                
            	<td class="date-length"><?=$dist_list['close_date'];?></td>				
                <?php } ?>
                <td class="sub-length"> <?php  
				
				   if(!empty($dist_list['comments'][0]['cmt'])){
					$dist_list['comments'][0]['cmt'] = "##".$dist_list['comments'][0]['cmt'];	
					}
				echo $dist_list['comments'][0]['cmt'];
            ?>  </td>
            
			</tr>
			<?php } //end foreach
			}
	}//end else ?
	?>
    </tbody>
    </table>
    </form>
     <div class="col-md-9">
     <?php echo $this->pagination->create_links(); ?> 
     </div>
      <div class="col-md-3">
	<?php 
		if(isset($_REQUEST['inmail_outmail']) && $_REQUEST['inmail_outmail']=='' && isset($_REQUEST['division']) && $_REQUEST['division']=='' && isset($_REQUEST['subdivision']) && $_REQUEST['subdivision']=='' && isset($_REQUEST['search_by']) && $_REQUEST['search_by']=='' && isset($_REQUEST['search_by_txt']) && $_REQUEST['search_by_txt']=='' && isset($_REQUEST['from_datepicker']) && $_REQUEST['from_datepicker']=='' && isset($_REQUEST['to_datepicker']) && $_REQUEST['to_datepicker']=='' && isset($_REQUEST['template_id']) && $_REQUEST['template_id']=='' && isset($_REQUEST['save_template_txt']) && $_REQUEST['save_template_txt']=='')
		{
			//echo "No needs to print details";
		} else {
				if(isset($response))
				{ 
						if (!empty($response))
						{ ?>
						<a class="btn btn-primary" href="<?=base_url();?>?c=search&m=generate_xls">Excel</a>
						<a class="btn btn-primary" href="<?=base_url();?>?c=search&m=generate_csv">Csv</a> 
						<a class="btn btn-primary" onclick="printDiv();">Print</a>    
					<?php }
				} 
			} ?>
    </div>
     <div class="clearfix"></div>
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
	
});
function printDiv(){	
	
	window.open('<?php echo site_url();?>?c=search&m=printpdf','_newtab');
}
</script>
