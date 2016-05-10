<!-- Morris Charts CSS -->
<link href="<?php echo base_url(); ?>/bower_components/morrisjs/morris.css" rel="stylesheet">
<!-- Morris Charts JavaScript -->
<script src="<?php echo base_url(); ?>/bower_components/raphael/raphael-min.js"></script>
<script src="<?php echo base_url(); ?>/bower_components/morrisjs/morris.min.js"></script>
<?php 
    $div_data = $this->session->userdata('get_div');
    $session = $this->session->userdata('check_login');

    $mailurl = "";
    $fileurl = "?c=fts&m=list_all_files";

    if ($session['login_type'] == 1 || $session['login_type'] == 2) {
        $mailurl = "?c=home&m=dashboard";
        //$fileurl = "?c=fts&m=list_all_files";
    }

    if ($session['login_type'] == 3) {
        $mailurl = "?c=home&m=division_list&division_id=" . $div_data['division_id'];
        $fileurl = "?c=fts&m=file_check";
    }

    if ($session['login_type'] == 4) {
        $mailurl = "?c=home&m=division_list&division_id=" . $div_data['division_id'] . "&subdivision_id=" . $div_data['subdivision_id'];
        $fileurl = "?c=fts&m=file_check";
    }

//    if ($session['login_type'] == 5) {
//        $fileurl = "?c=fts&m=list_all_files";
//    }
?>
<div class="box col-md-12">
    <h3 style="margin-bottom:20px;">Dashboard</h3>
    <div class="box-inner m-b-14">
        <div class="box-header well" data-original-title="">
            <h2><i class=""></i>Applications</h2>
            <div class="box-icon"> 
                <a href="#" class="btn btn-minimize btn-round btn-default">
                    <i class="glyphicon glyphicon-chevron-up"></i>
                </a> 
            </div>
        </div>
        <div class="box-content">
            <div class="col-md-offset-2 col-md-8" style="padding: 15px 0;">
                <?php
                if ($session['login_type'] == 1 ||
                        $session['login_type'] == 2 ||
                        $session['login_type'] == 3 ||
                        $session['login_type'] == 4) {
                ?>
                <div class="col-md-6 center-text">
                    <div class="box-inner">
                        <div class="box-content">
                            <a href="<?php echo $mailurl; ?>">
                                <img src="<?php echo base_url() . 'img/mail.png'; ?>" height="64" />
                                <h4>Mail Tracking System</h4>
                            </a>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <?php if( do_action('checkststus.plugin') == 1 &&  $session['login_type'] != 2) { ?>
                <div class="col-md-6 center-text">                    
                    <div class="box-inner">
                        <div class="box-content">
                            <a href="<?php echo $fileurl; ?>">
                                <img src="<?php echo base_url() . 'img/docs.png'; ?>" height="64" />
                                <h4>File Tracking System</h4>
                            </a>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            <div class="clearfix"></div>
        </div> <!-- box-content -->                
    </div> <!-- box-inner -->    
</div> <!-- box col-md-12 -->
<div class="clearfix"></div>
<!-- File Request Chart -->
<?php if( do_action('checkststus.plugin') == 1 &&  $session['login_type'] != 2) { ?>
<div class="col-md-6 center-text">
    <div class="box-inner">
        <div class="box-header well" data-original-title="">
            <h2><i class=""></i>File Request Report</h2>
            <div class="box-icon"> 
                <a href="#" class="btn btn-minimize btn-round btn-default">
                    <i class="glyphicon glyphicon-chevron-up"></i>
                </a> 
            </div>
        </div>
        <div class="box-content">
            <div id="fileChartContainer"></div>
        </div> <!-- box-content -->
    </div> <!-- box-inner -->
</div>
<script type="text/javascript">
$(function(){    
    $.ajax({
        url: "?c=dashboard&m=getFileChart",
        type: 'POST',
        data: {division_id: $(this).val()},
        dataType: 'JSON',
        success: function (serverData) {
            Morris.Bar({
                element: 'fileChartContainer',
                data: serverData,
                xkey: 'y',
                ykeys: ['a'],
                labels: ['File'],
                hideHover: 'auto',
                resize: true
            });
        }
    });
});
</script>
<?php } ?>
<!-- In-Mail Chart -->
<?php
if ($session['login_type'] == 1 ||
        $session['login_type'] == 2 ||
        $session['login_type'] == 3 ||
        $session['login_type'] == 4) {
?>
<div class="col-md-6 center-text">
    <div class="box-inner">
        <div class="box-header well" data-original-title="">
            <h2><i class=""></i>In-Mail Report</h2>
            <div class="box-icon"> 
                <a href="#" class="btn btn-minimize btn-round btn-default">
                    <i class="glyphicon glyphicon-chevron-up"></i>
                </a> 
            </div>
        </div>
        <div class="box-content">
            <div id="inmailChartContainer"></div>
        </div> <!-- box-content -->
    </div> <!-- box-inner -->
</div>
<script type="text/javascript">
$(function(){
    $.ajax({
        url: "?c=dashboard&m=getMailChart",
        type: 'POST',
        data: {division_id: $(this).val()},
        dataType: 'JSON',
        success: function (serverData) {
            Morris.Bar({
                element: 'inmailChartContainer',
                data: serverData,
                xkey: 'y',
                ykeys: ['a'],
                labels: ['In Mail'],
                barColors: ["#7cc9f9"],
                hideHover: 'auto',
                resize: true
            });
        }
    });
});
</script>
<?php } ?>