<!-- ROLE SWITCHING OPTION START -->
<div class="modal fade" id="role_switching_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form role="form" id="role_switching_form" name="role_switching_form" method="post" action="?c=home&m=switch_role">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title text-center" id="myModalLabel">Select Role:</h3>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label class="form_field_label" for="role_switching_roles">Role</label>
                            <select class="form-control" id="role_switching_roles" name="role_switching_roles">
                                <option class="select-option" value="">Select Role</option>
                                <?php foreach ($data['roles'] as $role) { ?>
                                    <option class="select-option" value="<?php echo $role->id ?>"><?php echo $role->user_type ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label class="form_field_label" for="role_switching_division">Division</label>
                            <select class="form-control" id="role_switching_division" name="role_switching_division">
                                <option class="select-option" value="">Select Division</option>
                                <?php foreach ($data['division'] as $division) { ?>
                                    <option class="select-option" value="<?php echo $division['id'] ?>"><?php echo $division['division'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label class="form_field_label" for="role_switching_sub_division">Sub Division</label>
                            <select class="form-control" id="role_switching_sub_division" name="role_switching_sub_division">
                                <option class="select-option" value="">Select Subdivision</option>
                            </select>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
                <div class="modal-footer">
                    <button id="role_switching_form_submit" type="submit" class="confirm btn btn-primary">Submit</button>
                    <button class="cancel btn btn-default" type="button" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<!-- ROLE SWITCHING OPTION END -->