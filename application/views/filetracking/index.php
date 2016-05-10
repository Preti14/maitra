<div class="form-group">
    <label for="cycle">Cycles:</label>
    <select id="cycle" class="form-control">
        <?php foreach ($cycles as $value): ?>
            <option><?php echo $value; ?></option>
        <?php endforeach; ?>
    </select>
</div>
<h5 id="file_heading"><?php echo $file_heading; ?></h5>
<table style="" id="file_track_table" class="table table-striped table-bordered bootstrap-datatable datatable responsive">
    <thead>
        <tr>
            <th>Current Location</th> 
            <th>Purpose</th>
            <th>Date/ Time</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody id="fileList">
        <?php if (!empty($files)): foreach ($files as $file): ?>
                <tr>
                    <td><?php echo $file->current_location; ?></td>
                    <td><?php echo (($file->purpose != '') ? $file->purpose : '--'); ?></td>
                    <td><?php echo date('d-m-y h:i:s A', strtotime($file->created_date)); ?></td>
                    <td><?php echo $file->status; ?></td>
                </tr>
            <?php
            endforeach;
        else:
            ?>
            <tr>
                <td colspan="4" class="err_msg">Post(s) not available.</td>
            </tr>
        <?php endif; ?>
                <?php if (!empty($files)): ?>
            <tr><td colspan="4" style="border: none !important; background: #FFF !important;">
            <?php echo $this->ajax_pagination->create_links(); ?>
                </td></tr>
<?php endif; ?>
    </tbody>
</table>