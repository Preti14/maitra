<?php if(!empty($files)): foreach($files as $file): ?>
<tr>
    <td><?php echo $file->current_location; ?></td>
    <td><?php echo (($file->purpose!='')?$file->purpose:'--'); ?></td>
    <td><?php echo date('d-m-y h:i:s A', strtotime($file->created_date)); ?></td>
    <td><?php echo $file->status; ?></td>
</tr>
<?php endforeach; else: ?>
<tr>
    <td colspan="4" class="err_msg">Post(s) not available.</td>
</tr>
<?php endif; ?>
<?php if(!empty($files)): ?>
<tr><td colspan="4" style="border: none !important; background: #FFF !important;">
<?php echo $this->ajax_pagination->create_links(); ?>
</td></tr>
<?php endif; ?>