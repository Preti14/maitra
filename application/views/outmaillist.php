<div class="box col-md-12">
    <div class="box-inner">
    <div class="box-header well" data-original-title="">
        <h2><i class=""></i> Out Mail List</h2>

        <div class="box-icon">
            <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
        
        </div>
    </div>
    <?php $login_data = $this->session->userdata('check_login'); ?>
    <div class="box-content">
    <form id="distributedtable" method="post">
    <div class="col-md-10"> <?php echo $this->pagination->create_links(); ?></div>
    <div class="clearfix"></div>

    <table id="out_mail_list" class="table table-striped table-bordered bootstrap-datatable datatable responsive add-style">
    <thead>
    <tr>
    <th class="no-print" style="width:7%">Id</th>
      <th class="only-print">Id</th>
      <th style="width:9%">Dispatch Date</th>
      <th style="width:8%">Mail Ref</th>
      <th style="width:9%">Date</th>
      <th style="width:15%">Subject</th>
      <th style="width:8%">From</th>
      <th style="width:9%">To</th>
     <th style="width:9%">Action Date</th>
      <th style="width:9%">Division / Subdivision</th>
      <?php if($login_data['login_type']==3) { ?>
      
      <th style="width:9%">Close Date</th>
      <?php } ?>
       <th>Comments</th>
    </tr>
    </thead>
    <tbody>
    <?php if(empty($outmail_list)){?>
		<tr><td colspan="11" style="text-align:center">No Mails are found</td></tr>
    <?php } else{   //echo "<pre>";print_r($outmail_list);
	foreach($outmail_list as $dist_list){
		 $created_year = date("y", strtotime($dist_list['created_on']));
		 $mailno_with_year = $dist_list['mail_no'];?>
        <tr style="page-break-after:always;">
            <td style="width:30px" class="no-print"><a href="<?=base_url();?>?c=outmail&m=outmailview&mailid=<?=$dist_list['id'];?>&stat=outmail" data-toggle="tooltip" data-original-title="View"><?=$mailno_with_year;?></a></td>
	    <td class="only-print"><?=$mailno_with_year;?></td>            
            <td style="width:100px" class="date-length"><?=$dist_list['recieved_date'];?></td>
            <td class="ref-length"><?=$dist_list['mail_ref'];?></td>
            <td style="width:120px" class="date-length"><?=$dist_list['date'];?></td>
            <td class="sub-length"><?=$dist_list['subject'];?></td>
            
            <td>
				<?php if(!empty($dist_list['from'])){ echo $dist_list['from']; } ?>
			</td>
            <td><?php if(!empty($dist_list['to'])){
						echo $dist_list['to']; }?>
			</td>
            <td><?php if(!empty($dist_list['action_date'])){ echo $dist_list['action_date']; }?></td>
            <td><?php echo $dist_list['division'];
			if(isset($dist_list['subdivision']))
				echo " / ".$dist_list['subdivision'];?></td>
            <?php if($login_data['login_type']==3) { ?>
            <td style="width:100px" class="date-length"><?=$dist_list['close_date'];?></td>
            <?php } ?>
            <td class="sub-length"> <?php  
				
				   if(!empty($dist_list['comments'][0]['cmt'])){
					$dist_list['comments'][0]['cmt'] = "##".$dist_list['comments'][0]['cmt'];	
					}
				echo $dist_list['comments'][0]['cmt'];
            ?>  </td>
        </tr>
        <?php } //end foreach
		} //end else ?> 
    </tbody>
    </table>
    </form>
   <div class="col-md-9"> <?php echo $this->pagination->create_links(); ?></div>
    <div>
      <?php if(isset($_GET['out']) &&($_GET['out']!='') && (!empty($outmail_list)) ){
	
    	$xls = "?c=outmail&m=outmail_xls&out=".$_GET['out'];
		$csv = "?c=outmail&m=outmail_csv&out=".$_GET['out'];
		$print = "?c=outmail&m=outmail_print&out=".$_GET['out']; ?>
  <a class="btn btn-primary" href="<?=base_url().$xls;?>">Excel</a> <a class="btn btn-primary" href="<?=base_url().$csv;?>">Csv</a> <a class="btn btn-primary" onclick="printDiv_list();">Print</a>
  <?php } ?>
  </div>  
  <div class="clearfix"></div>  
    </div>    
    </div>
   </div>
    
<script>
$(document).ready(function() {
	
});

function printDiv_list(){	
	
	//window.open('<?php //echo site_url();?>?c=outmail&m=outmail_print&out=<?php //echo $_GET['out']; ?>','_newtab');
}

</script>