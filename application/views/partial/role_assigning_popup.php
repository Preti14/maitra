<!-- ROLE ASSIGNING OPTION START -->
<div class="modal fade" id="role_assigning_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form role="form" id="role_assigning_form" name="role_assigning_form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title text-center" id="myModalLabel">Select Role:</h3>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="user type">User Type* : </label>
                        <select class="form-control" id="user_type" name="user_type">
                            <option class="select-option" value=''>Choose Option </option>
                            <?php foreach ($user_types as $ut) { ?>
                                <option class="select-option" value="<?php echo $ut['id']; ?>"><?php echo $ut['user_type']; ?></option>
                            <?php } ?>
                        </select><span style="color:#F00;"></span>
                    </div>

                    <div id="division_part">
                        <div class="form-group">
                            <label for="division">Division: </label>
                            <select class="form-control" id="division_id" name="division_id" multiple="">
                                
                            </select>
                        </div>
                    </div>
                    <div id="subdivision_part">
                        <div class="form-group">
                            <label for="division">Subdivision : </label>
                            <select class="form-control" id="subdivision_id" name="subdivision_id" multiple="">

                            </select>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="modal-footer">
                    <button id="role_assigning_form_submit" type="button" class="btn btn-primary">Submit</button>
                    <button class="cancel btn btn-default" type="button" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<!-- ROLE ASSIGNING OPTION END -->