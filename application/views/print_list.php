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

<div class="modal-dialog no-print">
    <div class="modal-content no-print">
        <div class="modal-header no-print">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>Address</h3>
            <a href="javascript:void(0);" id="print" onClick="">Print</a>
        </div>
        <div class="modal-body" id="modal-body" style="height:auto;width:595px;max-height:842px;">      
			<?php $i=1; 
			$j=1;?>
        	<div style="height:150px; margin:0px; padding:0px;">	
				<?php foreach($to_address as $to) {?> 
					<div class="" style="line-height:0.9;width:180px;margin-bottom: 10px;margin-top: 10px;position:relative;min-height:1px;padding-left:15px;padding-right:15px;float:left; border:1px solid; min-height:150px;max-height:150px;">
                        <!--<p><strong>Mail No- </strong><?php echo $to['mail_no']; ?></p>
                        <p><?php echo $to['title']; ?></p>
                        <p><?php echo $to['address1']; ?></p>
                        <p><?php echo $to['address2']; ?></p>
                        <p><?php echo $to['address3']; ?></p>
                        <p><?php echo $to['city']; ?></p>
                        <p><?php echo $to['state']; ?></p>
                        <p><?php echo $to['country']; ?></p>
                        <p><?php echo $to['pincode']; ?></p>-->
					</div>
					<?php /*$i++;
					if($i>3){
						$i=1;
						$j++;
						if($j!=5)
						echo "</div><div style='height:150px; margin:0px; padding:0px;'>";
					}
					if($j>5){
						$j=1;
						echo "<div class='brk'></div>";
					}*/
				} ?>
			</div>   
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
