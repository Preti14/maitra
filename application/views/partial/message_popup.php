<script>
    $(function () {
        var errorMsg = '<?php echo $this->session->flashdata('message') ?>';
        if (errorMsg != "") {
            $('#out_msg_cont').html(errorMsg);
            $('#out_msg').modal('show');
        }
    });
</script>
<!-- MESSAGE FLASH START -->
<div id="out_msg" class="modal fade">
    <div class="modal-dialog">

        <!-- dialog body -->
        <div class="modal-content ">
            <div class="p-t-20 p-b-20">
                <p class="text-center">
                    <span id="out_msg_cont"></span>
                </p>
                <div class="text-center">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- MESSAGE FLASH END -->