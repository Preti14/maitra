<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>Address</h3>
        </div>
        <div class="modal-body" id="modal-body">
            <!--p><?php echo $email_details['title'];
			if($email_details['attention']) echo " ( ".$email_details['attention']." ) "; ?></p>
            <p><?php echo $email_details['address3']; ?></p>
            <p><?php echo $email_details['city'];
            if($email_details['state'] and $email_details['city']) echo " , ";
            echo $email_details['state']; ?></p>
            <p><?php echo $email_details['pincode']; ?></p--->
            
            <p><?php echo $email_details['title'];			
			if($email_details['attention']) echo " ( ".$email_details['attention']." ) "; ?></p>            <?php //if($email_details['name']) echo "<p>".$email_details['name']."</p>"; ?>
			<?php if($email_details['address1']) echo "<p>".$email_details['address1']."</p>"; ?>      
            <?php if($email_details['address2']) echo "<p>".$email_details['address2']."</p>"; ?>
            <?php if($email_details['address3']) echo "<p>".$email_details['address3']."</p>"; ?>
            
            <?php if($email_details['city']) echo "<p>".$email_details['city']."</p>"; ?>
            <?php if($email_details['state']) echo "<p>".$email_details['state']."</p>"; ?>
            <?php if($email_details['pincode']) echo "<p>".$email_details['pincode']."</p>"; ?>
        </div>
        <div class="modal-footer">
        </div>
    </div>
</div>
