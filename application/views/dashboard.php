<div class="box col-md-12">
<div class="box-inner">
<div class="box-header well" data-original-title="">
  <h2><i class=""></i>
    <?php if(!empty($distributed_list)){
					if($distributed_list[0]['type'] == '1'){
						echo 'In Mail List';
					}else{
						echo 'Out Mail List';
					}
				}else{
        			echo 'In Mail List';
        		}
				 ?>
  </h2>
<div class="box-icon"> <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a> </div>
</div>




<div class="box-content">
<?php $login_data = $this->session->userdata('check_login');?>
<form id="distributedtable" method="post" action="">
<div class="col-md-8"><?php echo $this->pagination->create_links(); ?></div>
<div class="col-md-4">
<!--select id="cnt_per_page">
<option value="10">10</option>
<option value="10">20</option>
<option value="10">50</option>
<option value="10">100</option></select--->
<?php //echo "<pre>";print_r($this->session->userdata); ?>

<h4 align="right">
<strong><?php if(isset($search) && $search==1) echo "No of Records: ".$total_rows ; ?>
</strong></h4></div>
<div class="clearfix"></div>
<?php 
if(isset($_GET['division_id']))
		  {
			  $division_id = "&division_id=".$_GET['division_id'];
		  } else { $division_id =""; }
		  
		  if(isset($_GET['subdivision_id']))
		  {
			  $subdivision_id = "&subdivision_id=".$_GET['subdivision_id'];
		  } else { $subdivision_id =""; } 
		  
		  if(isset($_GET['in']))
		  {
			  $in_value = "&in=".$_GET['in'];
		  } else { $in_value =""; }
		  
		   if(isset($_GET['out']))
		  {
			  $out_value = "&out=".$_GET['out'];
		  } else { $out_value =""; }
		  
		  if(!isset($_GET['in']) && !isset($_GET['out']))
		  {
			  $instat = "&stat=inmail";
			  $outstat = "&stat=outmail";
		  } else { $instat = "";
			  		$outstat = ""; }
		  
		  ?>
          
          


<table  id="distributed_list" width="100%" class="table table-striped add-style table-bordered bootstrap-datatable datatable responsive">

  <thead>
    <tr>
<th class="no-print" style="width:7%">Id</th>
      <th class="only-print">Id</th>
      <th style="width:9%">Received Date</th>
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
    <?php	
		if(empty($distributed_list)){?>
    <tr>
      <td colspan="10" style="text-align:center"> No Mails are found</td>
    </tr>
    <?php } else{  //echo "<pre>";print_r($distributed_list);die;
		foreach($distributed_list as $dist_list){?>
    <tr style="page-break-after:always;">
      <?php if(isset($distributed_list)){
			  $created_year = date("y", strtotime($dist_list['created_on']));
			  $mailno_with_year = $dist_list['mail_no'];
				if($distributed_list[0]['type'] == '1'){	
			 ?>
      <td class="no-print"><a href="<?=base_url();?>?c=inmail&m=inmailview&mailid=<?=urlencode($dist_list['id']).$instat.$in_value.$division_id.$subdivision_id; ?>" data-toggle="tooltip" data-original-title="View">
        <?=$mailno_with_year;?>
        </a></td>
      <td class="only-print"><?=$mailno_with_year;?></td>
      <?php }else { ?>
      <td class="no-print"><a href="<?=base_url();?>?c=outmail&m=outmailview&mailid=<?=urlencode($dist_list['id']).$outstat.$out_value.$division_id.$subdivision_id; ?>" data-toggle="tooltip" data-original-title="View">
        <?=$mailno_with_year;?>
        </a></td>
      <td class="only-print"><?=$mailno_with_year;?></td>
      <?php	}
		}?>
      <td><?=$dist_list['recieved_date'];?></td>
      <td class="add-letter-space"><?=$dist_list['mail_ref'];?></td>
      <td><?=$dist_list['date'];?></td>
      <td class="sub-length"><?=$dist_list['subject'];?></td>
      <td><?=isset($dist_list['from'])?$dist_list['from']:'';?></td>
      <td><?php if(!empty($dist_list['to'])){ echo $dist_list['to']; }?></td>
      <td><?php if(!empty($dist_list['action_date'])){ echo $dist_list['action_date']; }?></td>
      <td id="msg_<?=$dist_list['id'];?>"><?php echo $dist_list['division'];
			if(isset($dist_list['subdivision']))
				echo " / ".$dist_list['subdivision'];?>
        <input type="hidden" name="valid-option" id="<?=$dist_list['id'];?>" value="<?=$dist_list['division_id'];?>"/></td>
      <?php if($login_data['login_type']==3) { ?>
        <td><?=$dist_list['close_date'];?></td>
      <?php } ?>
      <td class="sub-length"> <?php  
				
				   if(!empty($dist_list['comments'][0]['cmt'])){
					$dist_list['comments'][0]['cmt'] = "##".$dist_list['comments'][0]['cmt'];	
					}
				echo $dist_list['comments'][0]['cmt'];
            ?>  </td>
    </tr>
    <?php } //end foreach
	} ?>
    <!-------------------->
  </tbody>
</table>
</form>
    
<div class="col-md-9"> <?php echo $this->pagination->create_links(); ?></div>
<div class="col-md-3">
  <?php if(!empty($distributed_list) && isset($search) &&($search ==1)){
	
    	$xls = "?c=search&m=mail_xls";
		$csv = "?c=search&m=mail_csv";
		$print = "?c=search&m=mail_print"; ?>
  <a class="btn btn-primary" href="<?=base_url().$xls;?>">Excel</a> <a class="btn btn-primary" href="<?=base_url().$csv;?>">Csv</a> <a class="btn btn-primary" href="<?=base_url().$print;?>" target="_blank">Print</a>
  <?php } ?>
  
   <?php if(isset($_GET['in']) &&($_GET['in']!='') && (!empty($distributed_list))){
	
    	$xls = "?c=home&m=inmail_xls&in=".$_GET['in'];
		$csv = "?c=home&m=inmail_csv&in=".$_GET['in'];
		$print = "?c=home&m=inmail_print&in=".$_GET['in']; ?>
  <a class="btn btn-primary" href="<?=base_url().$xls;?>">Excel</a> <a class="btn btn-primary" href="<?=base_url().$csv;?>">Csv</a> 
  <a class="btn btn-primary" href="<?=base_url().$print;?>" >Print</a>
  
  <?php } ?>
 
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
	
	/*$("#cnt_per_page").change(function(e) {
       $("#distributedtable").submit(); 
    });*/
});


function printDiv_list(){	
	
	//window.open('<?php echo site_url();?>?c=home&m=inmail_print&in=<?php //echo $_GET['in']; ?>','_newtab');
}

</script> 