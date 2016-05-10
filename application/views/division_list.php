<div role="tabpanel" style="margin-top:10px">
<?php $login_data = $this->session->userdata('check_login');
$div_data      = $this->session->userdata('div_data');
$suffix="";
$sdiv  = ($div_data['subdivision_id']!='')?"&subdivision_id=".$div_data['subdivision_id']:'';
if($div_data)
$suffix = "&division_id=".$div_data['division_id'].$sdiv; ?>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#inmail" aria-controls="inmail" role="tab" data-toggle="tab">Inmail List</a></li>
    <li role="presentation" class=""><a href="<?=base_url();?>?c=outmail&m=outmail_division_list<?=$suffix;?>">Outmail List</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="inmail">
    	<form id="distributedtable" method="post">
        <div class="col-md-10"> <?php echo $pagination1;//$this->pagination->create_links(); ?></div>
        <div class="clearfix"></div>
  <table id="distributed_list" class="table table-striped add-style table-bordered bootstrap-datatable datatable responsive">
    <thead>
      <tr>
        <th class="no-print" style="width:7%">Id</th>
        <th class="only-print">Id</th>
        <th style="width:9%">Received Date</th>
        <th style="width:9%">Mail Reference</th>
        <th style="width:9%">Date </th>
        <th style="width:15%">Subject</th>
        <th style="width:9%">From</th>
        <th style="width:9%">To</th>
        <th style="width:9%">Copy To</th>
        <?php if($login_data['login_type']==3 || $login_data['login_type']==4) { ?>
        <th style="width:9%">Action Date</th>
        <th style="width:9%">Close Date</th>
        <?php } ?>
      </tr>
    </thead>
    <tbody>
      <?php	
	  
	  if(isset($_GET['division_id']))
	  {
		  $division_id = "&division_id=".$_GET['division_id'];
	  } else { $division_id =""; }
	  
	  if(isset($_GET['subdivision_id']))
	  {
		  $subdivision_id = "&subdivision_id=".$_GET['subdivision_id'];
	  } else { $subdivision_id =""; }
	  
		if(empty($distributed_list)){?>
      <tr>
        <td colspan="10" style="text-align:center"> No Mails are found</td>
      </tr>
      <?php } else{  //echo "<pre>";print_r($distributed_list);die;
		foreach($distributed_list as $dist_list){?>
      <tr style="page-break-after:always;">
        <?php if(isset($distributed_list)){
			 $created_year = date("y", strtotime($dist_list['created_on']));
				if($distributed_list[0]['type'] == '1'){	
			 ?>
        <td class="no-print"><a href="<?=base_url();?>?c=inmail&m=inmailview&mailid=<?=urlencode($dist_list['id']).$division_id.$subdivision_id; ?>">
          <?=$dist_list['mail_no']; ?>
          </a></td>
        <td class="only-print"><?=$dist_list['mail_no']; ?></td>
        <?php }else { ?>
        <td class="no-print"><a href="<?=base_url();?>?c=outmail&m=outmailview&mailid=<?=urlencode($dist_list['id']).$division_id.$subdivision_id; ?>">
          <?=$dist_list['mail_no']; ?>
          </a></td>
        <td class="only-print"><?=$dist_list['mail_no']; ?></td>
        <?php	}
		}?>
        <td><?=$dist_list['recieved_date'];?></td>
        <td><?=$dist_list['mail_ref'];?></td>
        <td><?=$dist_list['date'];?></td>
        <td><?=$dist_list['subject'];?></td>
        <td><?=isset($dist_list['from'])?$dist_list['from']:'';?></td>
        <td><?php if(!empty($dist_list['to'])){ echo $dist_list['to']; }?></td>
        <td><?php if(!empty($dist_list['cc'])){ echo $dist_list['cc']; }?></td>
        <?php if($login_data['login_type']==3 || $login_data['login_type']==4) { ?>
        <td><?=$dist_list['action_date'];?></td>
        <td><?=$dist_list['close_date'];?></td>
        <?php } ?>
      </tr>
      <?php } //end foreach
	} ?>
      <!-------------------->
    </tbody>
  </table>
</form>	
<div class="col-md-9"><?php echo $pagination1;?></div>
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
</script> 
