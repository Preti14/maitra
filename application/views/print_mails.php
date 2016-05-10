<link id="bs-css" href="<?=base_url();?>css/bootstrap-cerulean.min.css" rel="stylesheet">
<style>
@media print
{    
    .no-print, .no-print *
    {
        display: none !important;
    }
	table th tr
	{
		border:1px solid;
	}
	table tr { page-break-after:avoid;}
	
}
</style>

<div class="modal-dialog no-print" style="width:auto">
    <div class="modal-content no-print">
        <div class="modal-header no-print">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>Inmails</h3>
            <a href="javascript:void(0);" id="print" onClick="">Print</a>
        </div>
        <div class="modal-body" id="modal-body" style="">      
			 <table id="distributed_list" class="table table-striped table-bordered bootstrap-datatable datatable responsive" border="1" style="border-collapse:collapse">
          <thead>
            <tr>
              <th class="no-print">Id</th>
              <th class="only-print">Id</th>
              <th class="date-length">Received Date</th>
              <th class="ref-length">Mail Ref</th>
              <th class="date-length">Date</th>
              <th class="sub-length">Subject</th>
              <th>From</th>
              <th>To</th>
              <th>Copy To</th>
              <?php if($distributed_list[0]['type'] == '1'){ ?>
              <th>Division / Subdivision</th>
              <?php } ?>
              <th class="date-length">Action Date</th>
              <th class="date-length">Close Date</th>
            </tr>
          </thead>
          <tbody id="mails_tbody">
            <?php	
		if(empty($distributed_list)){?>
            <tr>
              <td colspan="10" style="text-align:center"> No Mails are found</td>
            </tr>
            <?php } else{  //echo "<pre>";print_r($distributed_list);die;
		foreach($distributed_list as $dist_list){?>
            <tr id="<?=$dist_list['id'];?>">
              <?php if(isset($distributed_list)){
			  $created_year = date("y", strtotime($dist_list['created_on']));
			  $mailno_with_year = $dist_list['mail_no'];
				if($distributed_list[0]['type'] == '1'){	
			 ?>
              <td style="width:30px" class="no-print" ><a href="<?=base_url();?>inmail/inmailview/<?=urlencode($dist_list['id'])?>">
                <?=$mailno_with_year;?>
                </a></td>
              <td class="only-print"><?=$mailno_with_year; ?></td>
              <?php }else { ?>
              <td style="width:30px" class="no-print" ><a href="<?=base_url();?>outmail/outmailview/<?=urlencode($dist_list['id'])?>">
                <?=$mailno_with_year; ?>
                </a></td>
              <td class="only-print"><?=$mailno_with_year; ?></td>
              <?php	}
		}?>
              <td style="width:100px" class="date-length"><?=$dist_list['recieved_date'];?></td>
              <td class="ref-length"><?=$dist_list['mail_ref'];?></td>
              <td style="width:120px" class="date-length"><?=$dist_list['date'];?></td>
              <td class="sub-length"><?=$dist_list['subject'];?></td>
              <td><?=isset($dist_list['from']['title'])?$dist_list['from']['title']:'';?></td>
              <td><?php if(!empty($dist_list['to'])){ echo $dist_list['to']; }?></td>
              <td><?php if(!empty($dist_list['cc'])){ echo $dist_list['cc']; }?></td>
              <?php if(isset($distributed_list)){
			if($distributed_list[0]['type'] == '1'){ ?>
              <td id="msg_<?=$dist_list['id'];?>"><?php echo $dist_list['division'];
			if(isset($dist_list['subdivision']))
				echo " / ".$dist_list['subdivision'];?>
                <input type="hidden" name="valid-option" id="<?=$dist_list['id'];?>" value="<?=$dist_list['division_id'];?>"/>
                </td>
              <?php } } ?>
              <td style="width:100px" class="date-length"><?=$dist_list['action_date'];?></td>
              <td style="width:100px" class="date-length"><?=$dist_list['close_date'];?></td>
            </tr>
            <?php } //end foreach
	} ?>
            <!-------------------->
          </tbody>
        </table>
        </div>
        <div class="modal-footer">
        </div>
    </div>
</div>
<script type="text/javascript" src="<?=base_url();?>js/jquery.print.js"></script>
<script type="text/javascript">
$(function() {
$("#print").click(function() {
	// Print the DIV.
	$("#modal-body").print();
		return (false);
	});
});
</script>
