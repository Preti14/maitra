<?php
$ms_session = $this->session->userdata('mail_status');
$search_subj = $this->session->userdata('search_subj');
$div_data = $this->session->userdata('get_div');
$session = $this->session->userdata('check_login');
$fts_session = $this->session->userdata('fts_check_login');

//print_r($div_data); die();
$loginurl = "";

if ($session['login_type'] == 1 || $session['login_type'] == 2) {
    $loginurl = "?c=home&m=dashboard";
}

if ($session['login_type'] == 3) {
    $loginurl = "?c=home&m=division_list&division_id=" . $div_data['division_id'];
}

if ($session['login_type'] == 4) {
    $loginurl = "?c=home&m=division_list&division_id=" . $div_data['division_id'] . "&subdivision_id=" . $div_data['subdivision_id'];
}

if ($session['login_type'] == 5) {
    $loginurl = "?c=fts&m=list_all_files";
}
$loginurl = "?c=home&m=dashboard";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!--
                ===
                This comment should NOT be removed.
        
                Charisma v2.0.0
        
                Copyright 2012-2014 Muhammad Usman
                Licensed under the Apache License v2.0
                http://www.apache.org/licenses/LICENSE-2.0
        
                http://usman.it
                http://twitter.com/halalit_usman
          
        -->
        <meta charset="utf-8">
        <title>Maitra</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Charisma, a fully featured, responsive, HTML5, Bootstrap admin template.">
        <meta name="author" content="Muhammad Usman">
  <script src="<?php echo base_url(); ?>js/constant.js"></script>
        <!-- The styles -->
        <link id="bs-css" href="<?= base_url(); ?>css/bootstrap-cerulean.min.css" rel="stylesheet">
        <link href="<?= base_url(); ?>css/charisma-app.css" rel="stylesheet">
        <link href="<?= base_url(); ?>bower_components/responsive-tables/responsive-tables.css" rel="stylesheet">
        <link href="<?= base_url(); ?>css/jquery.noty.css" rel="stylesheet">
        <link href="<?= base_url(); ?>css/tagit-stylish-yellow.css" rel="stylesheet" type="text/css">
        <link href="<?= base_url(); ?>css/dataTables.tableTools.css" rel="stylesheet">
        <!-- jQuery ------>
        <script src="<?= base_url(); ?>bower_components/jquery/jquery.min.js"></script>

        <script type="text/javascript" src="<?= base_url(); ?>js/jquery.leanModal.min.js"></script>
        <link rel="stylesheet" href="<?= base_url(); ?>css/font-awesome.min.css?>" />

        <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
            <![endif]-->

        <!-- The fav icon -->
        <link rel="shortcut icon" href="<?= base_url(); ?>img/favicon.ico">
        <link href="<?= base_url(); ?>css/styles.css" rel="stylesheet">
        <script src="<?= base_url(); ?>js/jquery.1.11.1.min.js"></script>
        <!---for datepicker-->

        <script src="<?= base_url(); ?>js/jquery-ui.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>js/jquery.easyModal.js"></script>
        <link rel="stylesheet" href="<?= base_url(); ?>css/jquery-ui.css">
        <script>
            $(function () {
            $("#datepicker").datepicker({dateFormat: 'dd-mm-yy'});
            $("#recieved_datepicker").datepicker({dateFormat: 'dd-mm-yy'});
            $("#from_datepicker").datepicker({dateFormat: 'dd-mm-yy'});
            $("#to_datepicker").datepicker({dateFormat: 'dd-mm-yy'});
            $("#action_datepicker").datepicker({dateFormat: 'dd-mm-yy'});
            $("#close_datepicker").datepicker({dateFormat: 'dd-mm-yy'});
            });
        </script>
        <!-----enc datepicker--->
  <script src="<?php echo base_url(); ?>js/maitra-header.js"></script>
        <style>
            #lean_overlay {
                position: fixed;
                z-index:100;
                top: 0px;
                left: 0px;
                height:100%;
                width:100%;
                background: #000;
                display: none;
            }

            .popupContainer{
                position:absolute;
                width:330px;
                height: auto;
                left:45%;
                top:80px;
                background: #FFF;
                border-radius:6px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
            }

            #modal_trigger {display:block;}

            /*.btn {padding:10px 20px; color: #FFF;}*/


            /*.btn:hover {background: #E4E4E2;}
            .btn_red:hover {background: #C12B05;}*/

            a.btnch {color:#fff; text-align: right; font-size:11px; text-decoration: none;}
            a.btn_red {color: #FFF;}
            .one_half {width:50%; display: block; float:left;}
            .one_half.last {width:45%; margin-left:5%;}


            /* Popup Styles*/
            .popupHeader {font-size:16px;}
            .popupHeader {background:#2fa4e7; position:relative; padding:10px 20px; border-bottom:1px solid #DDD; font-weight:bold;}
            .popupHeader .modal_close {position: absolute; right: 0; top:0; padding:10px 15px; cursor: pointer; color:#fff; font-size:12px;}
            .popupHeader span { color:#fff!important;}

            .popupBody {padding:20px;}
        </style>

        <script>
            $(document).ready(function(){

            $("#adv_search_div").hide();
            $("#adv_search").click(function(){
            //$("#adv_search_div").css("display","block");
            $("#adv_search_div").toggle();
            });
            $("#save_template_txt").keypress(function(){
            var save_template_txt = $(this).val();
            if (save_template_txt){
            $("#save_template_btn").attr('disabled', false);
            $("#delete_template_btn").attr('disabled', false);
            } else{
            $("#save_template_btn").attr('disabled', true);
            $("#delete_template_btn").attr('disabled', true);
            }
            });
            $("#save_template_txt").click(function() {
            var save_template_txt = $(this).val();
            if (save_template_txt){
            $("#save_template_btn").attr('disabled', false);
            $("#delete_template_btn").attr('disabled', false);
            } else{
            $("#save_template_btn").attr('disabled', true);
            $("#delete_template_btn").attr('disabled', true);
            }

            });
            $("#template_id").change(function(){
            var template_id = $(this).val();
            if (template_id != ''){
            $("#save_template_btn").attr('disabled', false);
            $("#delete_template_btn").attr('disabled', false);
            $("#save_template_txt").prop('type', 'hidden');
            $("#save_template_txt").val($("#template_id :selected").text());
            $.getJSON("<?= base_url(); ?>? c=search&m=fetch_all_temp&tid=" + template_id + "", function(dat a){
            $.each(data, function(index, ite m)
            {
            $("#inmail_o utmail option[value='" + item.type + "']").attr("sel ected", "selected");
            $("#division option[value='" + item.division + "']").attr("se lected", "selected");
            $("#division").trigger("change");
            $("#subdivision  option[value='" + item.subdivision + "']").attr(" selected", "selected");
            $("#m_status option[value='" + item.status + "']").attr("selected", "selected");
            $("#from_datepicker").val(item.from_date);
            $("#to_datepicker").val(item.to_d ate);
            //$("#save_t emplate_txt").val(item.template_name);

            if (item.template_name == '') {
            $("#save_template_t x t").val('');
            $('#sa ve_template_txt ').removeAttr('value');
            //$ ( '#save_te m plate_txt').trigger("r eset");							
            } else {
            //$("#save_template _txt").val(item.template_name);
            }


            if (item.subje c t) {
            $("#search_by  option[value='3']").attr("selected", "selected");
            $(" #search_by_txt").val(item.subject);
            }
            if (item.mail_ref) {
            $("#search_by option[value='6']").attr("selected", "selecte d");
            $("#search_by_txt").val(item.mail_ref);
            }

            /*if(item.from_date) {  
             $("#search_by option[valu e='1']").attr("selected","selected");
             $("#search_by_txt").val(item.from_date);
             }	
             
             if(item.t o_date) {  
             $("#search_by option[value='2'] ").attr("selected","selec ted");
             $("#search_by_txt").val(item.to_date);
             }	*/

            if (item.copy_to) {
            $("#search_by option[value='5']").attr("selected", "selected");
            $("#search_by_txt").val(item.copy_to);
            }

            if (item.comments) {
            $("#search_by option[value='4']").attr("selected", "selected");
            $("#search_by_txt").val(item.com ments);
            }

            if (item.from_mails) {
            $("#search_by option[value='1']").attr("selected", "selected");
            $("#search_by_txt").val(item.from_date);
            }

            if (item.to) {
            $("#search_by option[val ue='2']").attr("selected", "selected");
            $("#search_ by_txt").val(item.to_date);
            }
            //$('#save_templa te_txt').find('input:password').attr({type:"text"});

            });
            });
            } else if (template_id == ''){
            $("#inmail_outmail option").removeAttr("selected");
            $("#save_ templat e_txt").prop('t ype', 'text');
            $('#save_template_txt').removeAttr('value');
            $("#save_template_btn").attr('disabled', true);
            $("#delete_template_btn").at tr('disabled', true);
            //$('#sav e_template_txt').trigger("reset");	
            }
            });
            <!-------------------------------- Fetch Division & Sub division ------------------------------------ - >
                    var items = "";
            $.getJSON("<?= base_url(); ?>?c=search&m=fetch_division", function(data){
            items += "<option selected value=''>Select Division</option>";
            $.each(data, function(index, item)
            {
            items += "<option value='" + item.id + "'>" + item.division + "</option>";
            });
            $("#division").html(items);
            });
            $("#division").change(function(){
            var form_data = {
            id: $(this).val(),
            };
            var subdiv_items = "";
            $.ajax({
            url: '<?= base_url(); ?>?c=search&m=select_rel_div',
                    async: false,
                    dataType:"json",
                    data: form_data,
                    success: function(response){
                    subdiv_items += "<option selected value=''>Select Subdivision</option>";
                    $.each(response, function(index, item)
                    {
                    subdiv_items += "<option value='" + item.id + "'>" + item.code + "</option>";
                    });
                    $("#subdivision").html(subdiv_items);
                    },
            });
            });
            $("#resetbutton").click(function(e) {
            //$("#adv_search_div").css("display","none");
            //$("#adv_search_div").hide();
            $('#adv_search_form').trigger("reset");
            $("label").remove(".error");
            $('#save_template_txt').removeAttr('value');
            $("#save_template_txt").val('');
            $("#search_err").hide();
            return true;
            });
            tags = $.ajax({
            url: "<?= base_url(); ?>?c=search&m=get_templates",
                    async: false,
                    success: function(response){},
                    dataType:"json"
            });
            var availableTags = $.parseJSON(tags.responseText); //alert(tags.responseText);
            var option_list = '<option value="">Select Template</option>';
            if (availableTags.length > 0) {

            $.each(availableTags, function(i, item) {
            option_list += '<option value="' + item.value + '">' + item.label + '</option>';
            });
            }
            $("#template_id").html(option_list);
            /*var $input = $("#save_template_txt").autocomplete({
             source: availableTags,
             minLength: 0,
             select:function( event, ui ) {$("#save_template_btn").attr('disabled',false);}
             }).addClass("template_tags");
             
             $("<button type='button'>&nbsp;</button>")                     
             .attr("tabIndex", -1)                     
             .attr("title", "Show All Items")                     
             .insertAfter($input)                     
             .button({                         
             icons: {                             
             primary: "ui-icon-triangle-1-s"                         
             },                         
             text: false                     
             })
             .removeClass("ui-corner-all")
             .css("background","url('<?= base_url(); ?>img/down_arrow.png')")
             .css("position","absolute")
             .css("margin-left","272px")  
             .css("margin-top","112px")
             .css("width","25px")  
             .css("height","34px") 
             .click(function() {                    
             // close if already visible                         
             if ($input.autocomplete("widget").is(":visible")) {      
             $input.autocomplete( "close" );
             return;                         
             }                                              
             $(this).blur();                                                 
             $input.autocomplete("search", "" );                         
             $input.focus();                     
             });*/

            $("#save_template_txt").keyup(function() {
            $("#template_id").val('');
            if ($("#template_id").val() != ''){
            $("#delete_template_btn").attr('disabled', false);
            $("#save_template_btn").attr('disabled', false);
            } else{
            //$("#delete_template_btn").attr('disabled',true);

            }
            });
            $("#status").change(function() {
            var status = $("#status").val();
            $.ajax({
            url: "<?= base_url(); ?>?c=home&m=mail_status",
                    type: 'POST',
                    data: "status=" + status,
                    success: function(result) {
                    window.location.reload(true);
                    }
            });
            });
            $("#save_template_btn").click(function(e) {

            var temp_stat = $("#template_id").val();
            if (temp_stat == '') {

            $.ajax({
            url: "<?= base_url(); ?>?c=search&m=check_temp_name",
                    type: 'POST',
                    data: "temp_name=" + $("#save_template_txt").val(),
                    async:false,
                    success: function(response) {
                    if (response == 1){
                    $("#search_err").show().html('The template name already exist');
                    e.preventDefault();
                    }
                    else{
                    $("#search_err").css("display", "none");
                    }
                    }
            });
            }

            });
            $("#save_template_btn").attr('disabled', true);
            });
        </script>
        <style>
            .template_tags{
                background-color: #ffffff;background-image: none;border: 1px solid #cccccc;border-radius: 4px;box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;color: #555555;font-size: 14px;height: 38px;line-height: 1.42857;padding: 8px 0px;transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;position:absolute;margin-left:125px;margin-top:110px;padding-left:15px;width:175px;
            }
        </style>  		
    </head>

    <body>

        <?php
        if (isset($_GET['division_id'])) {
            $division_id = "&division_id=" . $_GET['division_id'];
        } else {
            $division_id = "";
        }

        if (isset($_GET['subdivision_id'])) {
            $subdivision_id = "&subdivision_id=" . $_GET['subdivision_id'];
        } else {
            $subdivision_id = "";
        }
        ?>


        <div id="modal" class="popupContainer" style="display:none;">
            <header class="popupHeader">
                <span class="header_title">Change Password </span>
                <span class="modal_close"><i class="glyphicon glyphicon-remove"></i></span>
            </header>

            <section class="popupBody">
                <!-- Change Password -->			 

                <form name="chpass_form" id="name_pass" action="<?= base_url(); ?>?c=home&m=update_password<?php echo $division_id . $subdivision_id; ?>" method="post">        
                    <table class="t1"> 
                        <tbody>
                            <tr class="r0"><td><span style="font-size:14px; font-weight:bold">Old Password</span></td>
                                <td><input type="password" name="old_password" id="old_password" class="form-control" maxlength="10"></td></tr>            
                            <tr class="r1"><td><span style="font-size:14px; font-weight:bold">Password</span></td>
                                <td><input type="password" name="password" id="password" class="form-control" maxlength="10">Minimum 6 characters</td></tr>
                            <tr class="r0"><td><span style="font-size:14px; font-weight:bold">Password (Retype)</span> </td>
                                <td><input type="password" name="password2" id="password2" class="form-control"  maxlength="10"></td></tr>
                            <tr class="r1">            
                                <td style="text-align:right;"></td>
                                <td>
                                    <input type="submit" name="chpasssubmit" id="chpasssubmit" class="btn btn-success" value="Submit">
                                    <button type="button" class="modal_close btn btn-danger" data-dismiss="modal" aria-hidden="true">Close</button>
                                </td>
                            </tr>            
                        </tbody></table>
                </form>

            </section>
        </div>


        <div class="navbar navbar-default" role="navigation">
            <div class="navbar-inner"><?php //echo "<pre>";print_r($divisions);        ?>
                <div class="col-md-3">
                <!--<a href="<?= base_url(); ?>?c=home&m=dashboard" class="navbar-brand">-->
                    <a href="<?php echo $loginurl; ?>" class="navbar-brand">
                        <span><?= strtoupper($settings['site_title']); ?></span></a><br />         
                        <!--<a href="<?php echo $loginurl; ?>" style="color: #fff; margin: 15px 0px 10px -282px;font-weight:bold; text-decoration:none"><i class="glyphicon glyphicon-home" style="color: #fff; margin:25px 0 0 0px;"></i> Home</a>-->

                </div>
 <div class="col-xs-1">		
	                    <button class="btn btn-primary" id="role_switching_modal_trigger"><i class="glyphicon glyphicon-random"></i></button>		
	                </div>
                <div class="col-md-8 pull-right" >





                    <div class="btn-group col-md-5 open">
                        <form action="<?php echo site_url() ?>?c=search&m=search_subject<?php
                        if ($session['login_type'] == 3 || $session['login_type'] == 4) {
                            echo $division_id . $subdivision_id;
                        }
                        ?>" method="post">
                            <button class="btn btn-primary pull-right" style="position: absolute; z-index: 9; height:38px; right:15px;"><i class="glyphicon glyphicon-search"></i></button>
                            <input type="text" name="query" class="search-query form-control" placeholder="Search" id="search_icon_txt" value="<?= isset($search_subj['txt']) ? $search_subj['txt'] : '' ?>">
                            <input type="hidden" name="uri_link" value="<?php echo $_GET['c'] ?>" >

                        </form>
                    </div>
                    <div class="btn-group col-md-3 theme-container animated tada">              
                        <button class="btn btn-primary dropdown-toggle w100" data-toggle="modal" data-target="#myModal_search" name="adv_search" type="button" style="height:38px;">Advanced Search</button>



                    </div>
                    <div class="btn-group col-md-2 open">
                        <select name="status" id="status" class="form-control" >
                            <option class="select-option" value="0" <?= $ms_session == 0 ? ' selected="selected"' : ''; ?>>All</option>
                            <option class="select-option" value="1" <?= $ms_session == 1 ? ' selected="selected"' : ''; ?>>Active</option>
                            <option class="select-option" value="3" <?= $ms_session == 3 ? ' selected="selected"' : ''; ?>>Overdue</option>
                            <option class="select-option" value="2" <?= $ms_session == 2 ? ' selected="selected"' : ''; ?>>Closed</option>
                        </select>
                    </div>
                    <div class="col-md-2 text-right">


                        <div class="btn-group pull-right w100">
                            <button class="btn btn-primary dropdown-toggle w100 display-name" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user"></i>
                                <span class="caret"></span>
                                <span class="hidden-sm hidden-xs">
                                    <?php
                                    //echo "Logged in as: ".ucfirst($session['login_name']);
                                    echo ucfirst($session['login_name']);

                                    if ($session['login_type'] == 3) {
                                        ?>
                                        <?php
                                        echo "( " . $div_data['division'] . " )";
                                    } else if ($session['login_type'] == 4) {
                                        echo "( " . $div_data['division'] . " / " . $div_data['subdivision'] . " )";
                                    }
                                    ?>
                                </span>

                            </button>
                            <ul class="dropdown-menu">
                                <li><?php //if($session['login_type']==3 || $session['login_type']==4 || $session['login_type']==1) {        ?>          

                                    <?php if(!$session['login_ad']) { ?>
                                        <a id="modal_trigger" href="#modal">Change Password</a>
                                    <?php } ?>

                                    <span style="color:#F00;text-align:left;">
                                        <?php echo $this->session->flashdata('message'); ?>
                                    </span>
                                    <?php //}   ?></li>
                                <li class="divider"></li>
                                <li><a href="<?= base_url(); ?>?c=home&m=logout">Logout</a></li>
                            </ul>
                        </div>
                        <div class="clear"></div>




                    </div>

                </div>
                <?php /* if($session['login_type'] == 3) {
                  $div_data=$this->session->userdata('get_div');?>
                  <div class="pull-right" style="margin-top:5px;"><h4 style="color:#FFF">Welcome <?=ucfirst($session['login_name']);?> ( Division : <?=$div_data['division'];?>, Subdivision: <?=$div_data['subdivision'];?> )</h4></div>
                  <?php } */ ?>

            </div>
        </div>
        <div class="ch-container">
            <div class="row">
                <div id="content" class="col-sm-12">
                    <!-- content starts -->
                    <div class="row">
                        <?= $this->content ?>
                    </div>
                    <!-- content ends -->
                </div><!--/#content.col-md-0-->

            </div><!--/fluid-row-->
            <hr>
        </div><!--/.fluid-container-->

        <div class="clearfix"></div>
        <footer>
            <div class="col-md-12 maitra-bot">
                    <!--p style="width:30%; float:left;">&copy; <a href="#" target="_blank"><?= ucfirst($settings['copyright_text']); ?></a> <?= date("Y"); ?></p-->
                <p class="col-lg-3 text-left"> <a href="<?php base_url(); ?>?c=home&m=gnu"><?php echo "GNU General Public License"; ?></a> </p>
                <p class="col-lg-6 text-center">Maitra - Mail Tracking System (ver 1.7), <?= ucfirst($settings['footer_text']); ?></p>    
                <!--p style="width:30%; float:left;"><a href="#"><?php echo $this->config->item('system_ip'); ?></a>
                </p--->
                <p class="col-lg-3 text-right"><a href="<?= base_url(); ?>?c=home&m=credits">Credits</a></p>

            </div>     
        </footer>

        <!-- external javascript -->

        <script src="<?= base_url(); ?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

        <!-- library for cookie management -->
        <script src="<?= base_url(); ?>js/jquery.cookie.js"></script>
        <!-- calender plugin -->
        <script src='<?= base_url(); ?>bower_components/moment/min/moment.min.js'></script>
        <script src='<?= base_url(); ?>bower_components/fullcalendar/dist/fullcalendar.min.js'></script>
        <!-- data table plugin -->
        <script src='<?= base_url(); ?>js/jquery.dataTables.min.js'></script>
        <script type="text/javascript" src="<?= base_url(); ?>js/dataTables.tableTools.js"></script>
        <!-- select or dropdown enhancer -->
        <script src="<?= base_url(); ?>bower_components/chosen/chosen.jquery.min.js"></script>
        <!-- plugin for gallery image view -->
        <script src="<?= base_url(); ?>bower_components/colorbox/jquery.colorbox-min.js"></script>
        <!-- notification plugin -->
        <script src="<?= base_url(); ?>js/jquery.noty.js"></script>
        <!-- library for making tables responsive -->
        <script src="<?= base_url(); ?>bower_components/responsive-tables/responsive-tables.js"></script>

        <!-- star rating plugin -->
        <script src="<?= base_url(); ?>js/jquery.raty.min.js"></script>
        <!-- for iOS style toggle switch -->
        <script src="<?= base_url(); ?>js/jquery.iphone.toggle.js"></script>
        <!-- autogrowing textarea plugin -->
        <script src="<?= base_url(); ?>js/jquery.autogrow-textarea.js"></script>
        <!-- multiple file upload plugin -->
        <script src="<?= base_url(); ?>js/jquery.uploadify-3.1.min.js"></script>
        <!-- history.js for cross-browser state change on ajax -->
        <script src="<?= base_url(); ?>js/jquery.history.js"></script>
        <!-- application script for Charisma demo -->
        <script src="<?= base_url(); ?>js/charisma.js"></script>
        <script src="<?= base_url(); ?>js/jquery.confirm.js"></script>
        <script>
            function db_download()
            {
            window.location.href = "<?= base_url(); ?>?c=admin&m=database_download";
            }
        </script> 

        <!-- Modal search -->
        <div class="modal fade bs-example-modal-lg" id="myModal_search" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title text-center" id="myModalLabel">Advanced Search Form </h3>
                        <h6 class="text-center"><span id="search_err" style="display:none;color:#F00;"></span></h6>
                    </div>
                    <div class="modal-body">
                        <?php echo validation_errors(); ?>
                        <form id="adv_search_form" action="<?= base_url(); ?>?c=search&m=search_mails<?php
                        if ($session['login_type'] == 3 || $session['login_type'] == 4) {
                            echo $division_id . $subdivision_id;
                        }
                        ?>" method="post" class="form-horizontal">
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">Search In :</label>
                                <div class="col-sm-2">
                                    <select name="inmail_outmail" id="inmail_outmail" class="form-control">
                                        <option value="" selected>Select</option>
                                        <option value="1">InMail</option>
                                        <option value="2">OutMail</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <select name="division" id="division"  class="form-control">
                                        <option value="">Select Division</option>
                                    </select>
                                </div>
                                <div class="col-sm-3 "> 
                                    <select name="subdivision" id="subdivision" class="form-control">
                                        <option value="">Select Subdivision</option>                   
                                    </select>
                                </div>
                                <div class="col-sm-1 none-padding-space"> 
                                    <select name="m_status" id="m_status" class="form-control none-padding-space">
                                        <option selected value="0">All</option>
                                        <option value="1">Active</option>
                                        <option value="3">Overdue</option>
                                        <option value="2">Closed</option>
                                    </select>
                                </div>

                            </div>

                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">Search By :</label>
                                <div class="col-sm-3">
                                    <select name="search_by" id="search_by" class="form-control">
                                        <option value="" selected>Select </option>
                                        <option value="3">Subject</option>
                                        <option value="6">Mail Ref</option>
                                        <option value="1">From</option>
                                        <option value="2">To</option>
                                        <option value="5">Copy To</option>
                                        <option value="4">Comments</option>

                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" id="search_by_txt" name="search_by_txt" class="form-control"/>
                                </div>
                            </div>
                            
         <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">Search By Comments :</label>
        <div class="col-sm-3">
         <select name="with_comm" id="with_comm" class="form-control">
                        <option value="0" selected>All </option>
                        <option value="1">Without Comments</option>
                        <option value="2">With Comments</option>
                       </select>
        </div>
   	 </div>

                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">Date Range :</label>
                                <div class="col-sm-3">
                                    <label for="exampleInputEmail1">From</label>
                                    <input type="text" class="form-control"  id="from_datepicker" name="from_datepicker" placeholder="From" value="">    
                                </div>

                                <div class="col-sm-3">
                                    <label for="exampleInputEmail1">To</label>
                                    <input type="text" class="form-control"  id="to_datepicker" name="to_datepicker" placeholder="To" value="">
                                </div>
                            </div>
                            <div  class="clearfix"></div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">&nbsp;</label>
                                <div class="col-sm-3"><button value="search" class="btn btn-success" id="searchbutton">Search</button>&nbsp; &nbsp;
                                    <button class="btn btn-danger" id="resetbutton" type="button" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>

                            <div  class="clearfix"></div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">Save Template :</label>
                                <div class="col-sm-3"><select name="template_id" id="template_id" class="form-control"></select></div>
                                <div class="col-sm-2"><input id="save_template_txt" type="text" placeholder="New Template name" name="save_template_txt" value="" class="form-control"/></div>
                                <div class="col-sm-2"><button class="btn btn-success" formaction="<?= base_url(); ?>?c=search&m=search_mails<?php
                                    if ($session['login_type'] == 3 || $session['login_type'] == 4) {
                                        echo $division_id . $subdivision_id;
                                    }
                                    ?>" id="save_template_btn" >Save Template</button>
                                </div>
                                <div class="col-sm-2"> <button class="btn btn-danger" id="delete_template_btn" disabled>Delete Template</button></div>
                            </div>

                        </form>
                    </div> 
                    <!--<div class="modal-footer">
                   <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                   <button type="button" class="btn btn-primary">Save changes</button>
                 </div>-->     
                </div>

            </div>
        </div>

 <?php $this->load->view('partial/role_switching_popup') ?>		
	        <?php $this->load->view('partial/message_popup') ?>

        <script>
            $(document).ready(function(){

            $("#delete_template_btn").confirm({
            text: "Are you sure that you really want to delete this template?",
                    title: "Confirmation required",
                    confirm: function(button) {
                    var template_id = $("#template_id").val();
                    $.ajax({
                    url: "<?= base_url(); ?>?c=search&m=delete_template",
                            type: 'POST',
                            data: "template_id=" + template_id,
                            success: function(response) {
                            if (response == 1){
                            //window.location.reload(true);
                            window.location = "<?php echo $loginurl; ?>";
                            }
                            }
                    });
                    },
                    cancel: function(button) {
                    return false;
                    },
                    confirmButton: "Yes",
                    cancelButton: "No",
                    post: true
            });
            $("#modal_trigger").leanModal({top : 200, overlay : 0.6, closeButton: ".modal_close" });
            // Calling Register Form
            $("#register_form").click(function(){
            $(".social_login").hide();
            $(".user_register").show();
            $(".header_title").text('Register');
            return false;
            });
            $("#chpasssubmit").click(function(e){
            e.preventDefault();
            var old_password = document.getElementById('old_password');
            var password = document.getElementById('password');
            var password2 = document.getElementById('password2');
            var password_length = $("input#password").val().length;
            var password2_length = $("input#password2").val().length;
            if (!old_password.value) {
            old_password.focus();
            document.getElementById("old_password").innerHTML = "required";
            alert("Enter the old password");
            return false;
            }
            if (!password.value) {
            password.focus();
            document.getElementById("password").innerHTML = "required";
            alert("Enter the New password");
            return false;
            }

            if (password_length < 6 || password2_length < 6) {
            password.focus();
            alert("Please enter minimum 6 characters");
            return false;
            }

            if (!password2.value) {
            password2.focus();
            document.getElementById("password2").innerHTML = "required";
            alert("Re-type Password again!");
            return false;
            }

            if (password.value != password2.value) {
            password.focus();
            alert("New Passwords should be same");
            return false;
            }

            $("#name_pass").submit();
            });
            });

        </script>



    </body>
</html>