<link id="bs-css" href="<?=base_url();?>css/bootstrap-cerulean.min.css" rel="stylesheet">
<style>
@media print
{    
    .no-print, .no-print *
    {
        display: none !important;
    }
	
}
</style>

<div class="modal-dialog no-print" style="width:300px">
    <div class="modal-content no-print">
        <div class="modal-header no-print">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>Address</h3>
            <?php if(($to_address)){?>
            <a href="javascript:void(0);" id="print" onClick="">Print</a>
            <?php } ?>
        </div>
        <div class="modal-body" id="modal-body" style="height:auto;width:300px;">      
			 <?php if(($to_address)){ ?>     
				
					<div class="brk" style="line-height:0.9;margin-bottom: 10px;margin-top: 10px;position:relative;min-height:1px;padding-left:15px;padding-right:15px;float:left; border:1px solid;height:auto; width:250px;">
                  		<p style="margin-top:5px;"><?php echo $to_address['title']; ?></p>
                        <p style="margin-top:5px;">
						<?php if($to_address['name']!=''){
							echo $to_address['name'];
								if($for_alias) 
									echo " <p>( for ".$for_alias." )</p> "; 
						}else{
							if($for_alias) 
								echo $for_alias; 
						}?></p>                         
                        <?php if($to_address['address1']) echo "<p>".$to_address['address1']."</p>"; ?>      
                        <?php if($to_address['address2']) echo "<p>".$to_address['address2']."</p>"; ?>
                        <?php if($to_address['address3']) echo "<p>".$to_address['address3']."</p>"; ?>
                        
                        <?php if($to_address['city']) echo "<p>".$to_address['city']."</p>"; ?>
                        <?php if($to_address['state']) echo "<p>".$to_address['state']."</p>"; ?>
                        <?php if($to_address['pincode']) echo "<p>".$to_address['pincode']."</p>"; ?>
                        <!-----p><?php 
						if($to_address['city']!="") { echo $to_address['city']; }
						if($to_address['pincode']) { echo " - ".$to_address['pincode']; }
						echo ", ".$to_address['state']; ?></p---------->
				
					</div>
                    
               <?php }else{ echo "No Address to print"; } ?>
        <div style="clear:both"></div>
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
