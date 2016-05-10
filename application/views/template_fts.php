<?php
$ms_session = $this->session->userdata('mail_status');
$search_subj = $this->session->userdata('search_subj');
$div_data = $this->session->userdata('get_div');
$session = $this->session->userdata('check_login');
$fts_session = $this->session->userdata('check_login');

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
    </head>
    <script src="<?= base_url(); ?>bower_components/jquery/jquery.min.js"></script>
    <script type="text/javascript">
        base_url = "<?php echo base_url(); ?>";
    </script>
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
            $("#recieved_ datepicker").datepicker({dateFormat: 'dd-mm-yy'});
            $("#from_datepicker").datepicker({dateFormat: 'dd-mm-yy'});
            $("#to_datepicker").datepicker({dateFormat: 'dd-mm-yy'});
            $("#action_datepicker").datepicker({dateFormat: 'dd-mm-yy'});
            $("#close_datepicker").datepicker({dateFormat: 'dd-mm-yy'});
        });</script>
    <!-----enc datepicker--->

    <script src="<?php echo base_url(); ?>js/maitra-header.js"></script>
    <style>
        #accordion li {
            display: block;
            position: relative;
            overflow: hidden;
        }
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
        $(document).ready(function () {

            $("#adv_search_div").hide();
            $("#adv_search").click(function () {
                //$("#adv_search_div").css("display","block");
                $("#adv_search_div").toggle();
            });
            $("#resetbutton").click(function (e) {
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
                success: function (response) {
                },
                dataType: "json"
            });
                        
            $("#status").change(function () {
                var status = $("#status").val();
                $.ajax({
                    url: "<?= base_url(); ?>?c=home&m=mail_status",
                    type: 'POST',
                    data: "status=" + status,
                    success: function (result) {
                        window.location.reload(true);
                    }
                });
            });
            
        });</script>
    <style>
        .template_tags{
            background-color: #ffffff;background-image: none;border: 1px solid #cccccc;border-radius: 4px;box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;color: #555555;font-size: 14px;height: 38px;line-height: 1.42857;padding: 8px 0px;transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;position:absolute;margin-left:125px;margin-top:110px;padding-left:15px;width:175px;
        }
    </style>  		


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
            <div class="navbar-inner"><?php //echo "<pre>";print_r($divisions);          ?>
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
                        <form action="<?php echo site_url() ?>?c=fts&m=global_search" method="post">
                            <button class="btn btn-primary pull-right" style="position: absolute; z-index: 9; height:38px; right:15px;"><i class="glyphicon glyphicon-search"></i></button>
                            <input type="text" name="query" class="search-query form-control" placeholder="Search" id="search_icon_txt" value="">
                        </form>
                    </div>
                    <div class="btn-group col-md-3 theme-container animated tada">              
                        <button class="btn btn-primary dropdown-toggle w100" data-toggle="modal" data-target="#myModal_search" name="adv_search" type="button" style="height:38px;">Advanced Search</button>
                    </div>
                    <div class="col-md-2 pull-right">


                        <div class="btn-group pull-right w100">
                <button class="btn btn-primary dropdown-toggle w100 display-name" data-toggle="dropdown">
                    <i class="glyphicon glyphicon-user"></i>
                    <span class="caret"></span>
                    <span class="hidden-sm hidden-xs">
                                    <?php
                                    //echo "Logged in as: ".ucfirst($session['login_name']);
                                    echo ucfirst($session['login_name']);

		  if($session['login_type'] == 3 ) { ?>
		  <?php echo "( ".$div_data['division']." )"; 
			}else if($session['login_type'] == 4) {
			echo "( ".$div_data['division']." / ".$div_data['subdivision']." )";
		   } ?>
                                </span>
                    
                            </button>
                            <ul class="dropdown-menu">
                    <li><?php //if($session['login_type']==3 || $session['login_type']==4 || $session['login_type']==1) { ?>          

                                    <?php if(!$session['login_ad']) { ?>
                                        <a id="modal_trigger" href="#modal">Change Password</a>
                                    <?php } ?>

                                    <span style="color:#F00;text-align:left;">
<?php echo $this->session->flashdata('message'); ?>
                                    </span>
            <?php //} ?></li>
                                <li class="divider"></li>
                    <li><a href="<?=base_url();?>?c=home&m=logout">Logout</a></li>
                            </ul>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>

            </div>
        </div>
        <div class="ch-container">
            <div class="row">
                <!---------Left Sidebar--->
                <div class="col-sm-3 col-lg-2">
                    <div class="sidebar-nav">
                        <div class="nav-canvas" style="margin-top:10px">
                            <div class="nav-sm nav nav-stacked">

                            </div>


                            <div>
                                <div id="accordion" >
                                   <!--<h3><span class="icon-dashboard"></span>Mail Tracking System</h3>-->
                                    <!-- <h3 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Mail Tracking System</a>
        </h3>-->
                                    <?php
                                    if ($session['login_type'] == 1 ||
                                            $session['login_type'] == 2 ||
                                            $session['login_type'] == 3 ||
                                            $session['login_type'] == 4) {
                                        ?>
        <div class="panel-heading ui-accordion-header ui-state-default ui-accordion-icons ui-corner-all">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Mail Tracking System</a>
        </h4>
      </div>
                                    <div id="collapse1" class="panel-collapse collapse">

                                        <ul class="nav nav-pills nav-stacked main-menu list-group-item">
<?php if ($session['login_type'] != 3 && $session['login_type'] != 4) { ?>
                                                <li class="accordion" <?= ((!isset($_GET['stat']))) ? ' style="display:block;"' : ''; ?>>
                                                    <a href="#"><i class="glyphicon glyphicon-inbox"></i><span> In Mail</span></a>
                                                    <ul class="nav nav-pills nav-stacked">
                                                        <li <?= ($_GET['m'] == "dashboard" && (!isset($_GET['in'])) or ( isset($_GET['stat'])) && ($_GET['stat'] == 'inmail') ) ? ' class="active"' : ''; ?>>
                                                            <a href="<?= base_url(); ?>?c=home&m=dashboard">In Mail List</a></li>
                                                        <li <?= ($_GET['m'] == "inmailentry" && !isset($_GET['ref'])) ? 'class="active"' : ''; ?>>
                                                            <a href="<?= base_url(); ?>?c=inmail&m=inmailentry">In Mail Entry</a></li>

                                                    </ul>
                                                </li>
                                                <li class="accordion" <?= ((!isset($_GET['stat']))) ? ' style="display:block;"' : ''; ?>>
                                                    <a href="#"><i class="glyphicon glyphicon-share-alt"></i><span> Out Mail</span></a>
                                                    <ul class="nav nav-pills nav-stacked">
                                                        <li <?= ($_GET['m'] == "outmaillist" && (!isset($_GET['out'])) or ( isset($_GET['stat'])) && ($_GET['stat'] == 'outmail')) ? 'class="active"' : ''; ?>>
                                                            <a href="<?= base_url(); ?>?c=outmail&m=outmaillist">Out Mail List</a></li>
                                                        <li <?= ($_GET['m'] == "outmailentry" && !isset($_GET['ref'])) ? 'class="active"' : ''; ?>>
                                                            <a href="<?= base_url(); ?>?c=outmail&m=outmailentry">Out Mail Entry</a></li>

                                                        <li>
                                                            <a href="<?= base_url(); ?>?c=outmail&m=print_address_form">Print Address</a></li>
                                                    </ul>
                                                </li>
<?php } ?>

                                            <li class="accordion">

                                                <a href="#"><i class="glyphicon glyphicon-filter"></i><span> Quick View</span></a>
                                                <ul class="nav nav-pills nav-stacked" <?= ((isset($_GET['in'])) or ( isset($_GET['out']))) ? ' style="display:block;"' : ''; ?>>
                                                    <li <?= ((isset($_GET['in']) && $_GET['in'] == 'day')) ? 'class="active"' : ''; ?>>
                                                        <a href="<?= base_url(); ?>?c=home&m=dashboard&in=day<?php
                                                           if ($session['login_type'] == 3 || $session['login_type'] == 4) {
                                                               echo $division_id . $subdivision_id;
                                                           }
                                                           ?>">In Current Day List</a></li>
                                                    <li <?= ((isset($_GET['in']) && $_GET['in'] == 'week')) ? 'class="active"' : ''; ?>>
                                                        <a href="<?= base_url(); ?>?c=home&m=dashboard&in=week<?php
                                                           if ($session['login_type'] == 3 || $session['login_type'] == 4) {
                                                               echo $division_id . $subdivision_id;
                                                           }
                                                           ?>">In Current Week List</a></li>
                                                    <li <?= ((isset($_GET['in']) && $_GET['in'] == 'month')) ? 'class="active"' : ''; ?>>
                                                        <a href="<?= base_url(); ?>?c=home&m=dashboard&in=month<?php
                                                           if ($session['login_type'] == 3 || $session['login_type'] == 4) {
                                                               echo $division_id . $subdivision_id;
                                                           }
                                                           ?>">In Current Month List</a></li>
                                                    <li <?= ((isset($_GET['out']) && $_GET['out'] == 'day')) ? 'class="active"' : ''; ?>>
                                                        <a href="<?= base_url(); ?>?c=outmail&m=outmaillist&out=day<?php
                                                           if ($session['login_type'] == 3 || $session['login_type'] == 4) {
                                                               echo $division_id . $subdivision_id;
                                                           }
                                                           ?>">Out Current Day List</a></li>
                                                    <li <?= ((isset($_GET['out']) && $_GET['out'] == 'week')) ? 'class="active"' : ''; ?>>
                                                        <a href="<?= base_url(); ?>?c=outmail&m=outmaillist&out=week<?php
                                                           if ($session['login_type'] == 3 || $session['login_type'] == 4) {
                                                               echo $division_id . $subdivision_id;
                                                           }
                                                           ?>">Out Current Week List</a></li>
                                                    <li <?= ((isset($_GET['out']) && $_GET['out'] == 'month')) ? 'class="active"' : ''; ?>>
                                                        <a href="<?= base_url(); ?>?c=outmail&m=outmaillist&out=month<?php
                                                           if ($session['login_type'] == 3 || $session['login_type'] == 4) {
                                                               echo $division_id . $subdivision_id;
                                                           }
                                                           ?>">Out Current Month List</a></li>
                                                        <?php
//print_r($search_history);	 die;	
                                                        if (!empty($search_history)) {

                                                            foreach ($search_history as $search) {
                                                                $t_active = "";
                                                                if (isset($_GET['temp_name']) && $_GET['temp_name'] == $search['template_name']) {
                                                                    $t_active = "active";
                                                                }
                                                                ?>

                                                            <li class="<?php echo $t_active; ?>">
                                                                <a href="<?= base_url(); ?>?c=search&m=todays_listpage&temp_name=<?= $search['template_name'] ?><?php
                                                                   if ($session['login_type'] == 3 || $session['login_type'] == 4) {
                                                                       echo $division_id . $subdivision_id;
                                                                   }
                                                                   ?>" ><?php echo ucfirst($search['template_name']); ?></a></li>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                </ul>
                                            </li>
                                            <?php
                                            $division = 0;
                                            $subdivision = 0;
                                            $div_session = $this->session->userdata('get_div');
                                            if (isset($_GET['division_id'])) {
                                                $division = $_GET['division_id'];
                                            }
                                            if (isset($_GET['subdivision_id'])) {
                                                $subdivision = $_GET['subdivision_id'];
                                            }
                                            ?>
                                            <?php
                                            foreach ($divisions as $div) {
                                                $div_active = "";
                                                $show_subdiv = "";
                                                if ((!isset($_GET['temp_name']) && !isset($_GET['in']) && !isset($_GET['out'])) && $division == $div['id'] or ( (isset($_GET['status'])) && $_GET['status'] != 'edit')) {
                                                    $show_subdiv = "style='display:block'";
                                                    $div_active = "active";
                                                    if (isset($subdivision) && $subdivision != '') {
                                                        $div_active = "";
                                                    }
                                                }
                                                ?>                        
                                                <li class="accordion <?= $div_active; ?>">                            
                                                            <?php //if($div['id']==$division){   ?>
                                                            <?php if ($session['login_type'] == 3) { ?>                         
                                                        <a href="<?= base_url(); ?>?c=home&m=division_list&division_id=<?= $div['id'] ?>" class="div_link"><i class="glyphicon glyphicon-list-alt"></i><span> <?php
                                                                if ($div['division'] != "") {
                                                                    echo $div['division'];
                                                                }
                                                                ?></span></a>
                                                        <?php
                                                    }// } 

                                                    if (isset($div_session['division_id']) && $div['id'] == $div_session['division_id']) {
                                                        ?>
                                                                <?php if ($session['login_type'] == 4) { ?>                         
                                                            <a href="<?= base_url(); ?>?c=home&m=division_list&division_id=<?= $div['id'] ?>" class="div_link"><i class="glyphicon glyphicon-list-alt"></i><span> <?php
                                                                    if ($div['division'] != "") {
                                                                        echo $div['division'];
                                                                    }
                                                                    ?></span></a>
                                                            <?php
                                                        }
                                                    }


                                                    if ($session['login_type'] == 1 || $session['login_type'] == 2) {
                                                        ?>
                                                        <a href="<?= base_url(); ?>?c=home&m=division_list&division_id=<?= $div['id'] ?>" class="div_link"><i class="glyphicon glyphicon-list-alt"></i><span> <?php
                                                                if ($div['division'] != "") {
                                                                    echo $div['division'];
                                                                }
                                                                ?></span></a>
                                                        <?php } ?>


                                                    <ul class="nav nav-pills nav-stacked" <?= $show_subdiv; ?>>
                                                        <?php
                                                        foreach ($div['subdivisions'] as $subdiv) {
                                                            $sdiv_active = "";
                                                            ?>                        
                                                            <?php
                                                            if (isset($subdivision) && $subdivision == $subdiv['id']) {
                                                                $sdiv_active = "active";
                                                            }
                                                            ?>
                                                            <li class="<?= $sdiv_active; ?>"><a href="<?= base_url(); ?>?c=home&m=division_list&division_id=<?= $div['id'] ?>&subdivision_id=<?= $subdiv['id'] ?>" class="subdiv_link"><?= $subdiv['code']; ?></a></li>

                                                <?php } ?>
                                                    </ul>
                                                </li>
<?php } ?>


                                        </ul>
                                    </div>
                                    <?php }; ?>
<?php if (do_action('checkststus.plugin') == 1 && $session['login_type'] != 2) { ?>
                                        <!--<h3><a href="#">File Tracking System</a></h3>-->
                                        <!--<h3 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">File Tracking System</a>
        </h3>-->
       <?php /*?> <div class="panel-heading ui-accordion-header ui-state-default ui-accordion-icons ui-corner-all">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">File Tracking System</a>
        </h4>
      </div><?php */?>
                                        <div id="collapse2" class="panel-collapse collapse in collapsecustom">

                                            <ul class="nav nav-pills nav-stacked main-menu list-group-item">
                                                <li class="accordion">
                                                    <a href="#"><i class="glyphicon glyphicon-file"></i><span> File</span></a>
                                                    <ul class="nav nav-pills nav-stacked" <?= (($_GET['m'] == "add_file_data") OR ($_GET['m'] == "list_all_files" && !isset($_GET['div_id'])) OR ($_GET['m'] == "file_check")) ? ' style="display:block;"' : ''; ?>>
                                                    <?php
                                                    if ($session['login_type'] == 1 ||
                                                            $session['login_type'] == 3 ||
                                                            $session['login_type'] == 4 ||
                                                            $session['login_type'] == 5) {
                                                        ?>
                                                <li <?= ($_GET['m'] == "add_file_data") ? 'class="active"' : ''; ?>>
                                                    <a href="<?= base_url(); ?>?c=fts&m=add_file_data"><i class="glyphicon glyphicon-file"></i>&nbsp;<span>Request New File</span></a>
                                                </li>
                                                    <?php } ?>
                                                <li <?= ($_GET['m'] == "list_all_files" && !isset($_GET['div_id'])) ? 'class="active"' : ''; ?>>
                                                    <a href="<?= base_url(); ?>?c=fts&m=list_all_files"><i class="glyphicon glyphicon-list-alt"></i>&nbsp;<span>File List</span></a>
                                                </li>
    <?php
                                            if($session['login_type'] != 2 && $session['login_type'] != 1 && $session['login_type'] != 5){
        ?>	
                                                    <li <?= ($_GET['m'] == "file_check") ? 'class="active"' : ''; ?>>	
                                                        <a href="<?= base_url(); ?>?c=fts&m=file_check">
                                                            <i class="glyphicon glyphicon-qrcode"></i>&nbsp;<span>Check In/Out</span></a>
                                                    </li>
                                                    <?php
                                                }
                                                ?>
                                                    </ul>
                                                </li>
                                                <!-- Quick View -->
                                                <li class="accordion">
                                                    <a href="#"><i class="glyphicon glyphicon-filter"></i><span> Quick View</span></a>
                                                    <ul class="nav nav-pills nav-stacked" <?=((isset($_GET['temp_id'])))?' style="display:block;"':'';?>>
                                                        <?php 
                                                        if(!empty($search_fts_templates)){
                                                            foreach($search_fts_templates as $search_fts_template){ 
                                                                $t_active= "";
                                                                if(isset($_GET['temp_id']) && $_GET['temp_id']==$search_fts_template->id){
                                                                        $t_active="active";
                                                                }
                                                                ?>
                                                                    <li class="<?php echo $t_active;?>">
                                                                        <a href="<?=base_url();?>?c=fts&m=advanced_search&temp_id=<?=$search_fts_template->id; ?>" ><?php echo ucfirst($search_fts_template->name); ?></a></li>
                                                        <?php	
                                                            }
                                                        }
                                                        ?>
                                                    </ul>
                                                </li>
                                                <!-- Quick View -->
                        <?php //$this->load->view('template_common') ?>
                                            </ul>







                                        </div>
<?php } ?>
                                        <?php if ($session['login_type'] == 1) {//Admin only ?>
                                            <div class="panel-heading ui-accordion-header ui-state-default ui-accordion-icons ui-corner-all">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">Settings</a>
                                                </h4>
                                            </div>
                                            <div id="collapse3" class="panel-collapse collapse">
                                                <ul class="nav nav-pills nav-stacked main-menu list-group-item">
                                                        <li <?= ($_GET['m'] == "plugins") ? 'class="active"' : ''; ?>>
                                                            <a href="<?= base_url(); ?>?c=home&m=plugins"><i class="glyphicon glyphicon-book"></i> Plugin List</a></li>
                                                        <li <?= ($_GET['m'] == "users") ? 'class="active"' : ''; ?>>
                                                            <a href="<?= base_url(); ?>?c=admin&m=users"><i class="glyphicon glyphicon-user"></i> Users List</a></li>
                                                        <li <?= ($_GET['m'] == "division") ? 'class="active"' : ''; ?>>
                                                            <a href="<?= base_url(); ?>?c=admin&m=division"><i class="glyphicon glyphicon-list-alt"></i> Divisions List</a></li>
                                                        <li <?= ($_GET['m'] == "subdivision") ? 'class="active"' : ''; ?>>
                                                            <a href="<?= base_url(); ?>?c=admin&m=subdivision"><i class="glyphicon glyphicon-list-alt"></i> Subdivisions List</a></li>
                                                        <li>
                                                            <a href="<?= base_url(); ?>?c=admin&m=settings"><i class="glyphicon glyphicon-cog"></i> General</a></li>
                                                        <li>
                                                            <a href="<?= base_url(); ?>?c=admin&m=activities"><i class="glyphicon glyphicon-list"></i> Activity Log</a></li>
                                                        <li <?= ($_GET['m'] == "address_form" or $_GET['m'] == "manage_address_book") ? 'class="active"' : ''; ?>>
                                                            <a href="<?= base_url(); ?>?c=admin&m=manage_address_book"><i class="glyphicon glyphicon-book"></i> Manage Address</a></li>
                                                        <li <?= ((isset($_GET['stat']) && $_GET['stat'] == "manageinmail" or $_GET['m'] == "manage_inmails") or ( $_GET['m'] == 'inmailentry' && isset($_GET['ref']))) ? 'class="active"' : ''; ?>>
                                                            <a href="<?= base_url(); ?>?c=admin&m=manage_inmails"><i class="glyphicon glyphicon-envelope"></i> Manage Inmails</a></li>
                                                        <li <?= ((isset($_GET['stat']) && $_GET['stat'] == "manageoutmail" or $_GET['m'] == "manage_outmails") or ( $_GET['m'] == 'outmailentry' && isset($_GET['ref']))) ? 'class="active"' : ''; ?>>
                                                            <a href="<?= base_url(); ?>?c=admin&m=manage_outmails"><i class="glyphicon glyphicon-envelope"></i> Manage Outmails</a></li>
                                                        <li>
                                                            <a href="<?= base_url(); ?>?c=inmail&m=inmail_staging"><i class="glyphicon glyphicon-envelope"></i> Staging Inmails</a></li>
                                                        <li>
                                                            <a href="<?= base_url(); ?>?c=outmail&m=outmail_staging"><i class="glyphicon glyphicon-envelope"></i> Staging Outmails</a></li>
                                                        <li>
                                                            <a href="#" onClick="db_download();"><i class="glyphicon glyphicon-download-alt"></i> Backup DB</a></li>

                                                        <li>
                                                            <a href="<?= base_url(); ?>?c=admin&m=purge_records"><i class="glyphicon glyphicon-download-alt"></i> Purge Closed Records</a></li>
                                                </ul>
                                            </div>
                                        <?php } ?>
                                </div>
                            </div>
                            <!-- accordion -->

                        </div>
                    </div>
                </div>
                <!-------End Left Sidebar-->

                <div id="content" class="col-lg-10 col-sm-9">
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
        <!--p style="width:30%; float:left;">&copy; <a href="#" target="_blank"><?=ucfirst($settings['copyright_text']);?></a> <?=date("Y");?></p-->
         <p class="col-lg-3 text-left"> <a href="<?php base_url();?>?c=home&m=gnu"><?php echo "GNU General Public License";?></a> </p>
        <p class="col-lg-6 text-center">Maitra - Mail Tracking System (ver 1.7), <?=ucfirst($settings['footer_text']);?></p>    
        <!--p style="width:30%; float:left;"><a href="#"><?php echo $this->config->item('system_ip');?></a>
                            </p--->
		 <p class="col-lg-3 text-right"><a href="<?=base_url();?>?c=home&m=credits">Credits</a></p>

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
        <script src="<?php echo base_url(); ?>js/template_fts.js"></script>

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
                        <form id="adv_search_form" action="<?= base_url(); ?>?c=fts&m=advanced_search<?php
if ($session['login_type'] == 3 || $session['login_type'] == 4) {
    echo $division_id . $subdivision_id;
}
?>" method="post" class="form-horizontal">
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">Search In :</label>
                                <div class="col-sm-4">
                                    <select name="division" id="division"  class="form-control">
                                        <option value="">Select Division</option>
<?php foreach ($divisions as $division): ?>
                                            <option class="select-option" value="<?php echo $division['id'] ?>"><?php echo $division['division'] ?></option>
<?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-sm-4 "> 
                                    <select name="subdivision" id="subdivision" class="form-control">
                                        <option value="">Select Subdivision</option>                   
                                    </select>
                                </div>
                            </div>

                            <div class="form-group search_by_form_group">
                                <label for="inputEmail3" class="col-sm-2 control-label">Search By :</label>
                                <div class="col-sm-4">
                                    <select name="search_by[]" id="search_by" class="form-control search_by_select">
                                        <option value="">Select</option>
                                        <option value="file_number">File Number</option>
                                        <option value="file_subject">File Subject</option>
                                        <option value="department">Department</option>
                                        <option value="volume">Volume</option>
                                        <option value="file_contact_email">Contact Email</option>
                                        <option value="barcode_id">Barcode</option>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" id="search_by_txt" name="search_by_txt[]" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">Created Date Range :</label>
                                <div class="col-sm-4">
                                    <label for="exampleInputEmail1">From</label>
                                    <input type="text" class="form-control"  id="from_datepicker" name="from_datepicker" placeholder="From" value="">    
                                </div>

                                <div class="col-sm-4">
                                    <label for="exampleInputEmail1">To</label>
                                    <input type="text" class="form-control"  id="to_datepicker" name="to_datepicker" placeholder="To" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Include Deleted File Too :</label>
                                <div class="checkbox checkbox-inline">
                                    <input type="checkbox" value="1" id="deleted_file" name="deleted_file">
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
                                <label class="col-sm-2 control-label">Save Template :</label>
                                        <div class="col-sm-3">
                                            <select name="template_id" id="template_id" class="form-control">
                                                <option value="">Select Template</option>
                                                <?php foreach ($fts_advanced_search_template_name as $record){ ?>
                                                <option value="<?php echo $record->id ?>"><?php echo $record->name ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                <div class="col-sm-2"><input id="save_template_txt" type="text" placeholder="New Template name" name="save_template_txt" value="" class="form-control"/></div>
                                <div class="col-sm-2"><button class="btn btn-success" id="save_template_btn" name="save_template_btn" disabled>Save Template</button></div>
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
        
<div class="modal fade confirmation-modal" id="template_delete_confirmation_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title text-center" id="myModalLabel">Delete Template</h3>
                <h5 id="file_heading"></h5>
            </div>
            <div class="modal-body">
                Are you sure want to delete template?
            </div>
            <div class="modal-footer">
                <button class="confirm btn btn-primary" type="button" data-dismiss="modal">
                    Yes
                </button>
                <button class="cancel btn btn-default" type="button" data-dismiss="modal">
                    No
                </button>
            </div>
        </div>
    </div>
</div>

    <?php $this->load->view('partial/role_switching_popup') ?>
    <?php $this->load->view('partial/message_popup') ?>

        <script>

            $("#modal_trigger").leanModal({top: 200, overlay: 0.6, closeButton: ".modal_close"});
            $(function () {
                // Calling Register Form
                $("#register_form").click(function () {
                    $(".social_login").hide();
                    $(".user_register").show();
                    $(".header_title").text('Register');
                    return false;
                });
                $("#chpasssubmit").click(function (e) {
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
            })

            //FOR ADVANCED SEARCH ADDED

            //SUB-DIVISION
            $(document).on('change', '#division', function () {
                $.ajax({
                    url: "?c=fts&m=get_subdivision",
                    type: 'POST',
                    data: {division_id: $(this).val()},
                    dataType: 'JSON',
                    success: function (serverData) {
                        var length = serverData.length;
                        var innerHtml =
                                '<option class="select-option" value="">' +
                                'Select Subdivision </option>';
                        for (var i = 0; i < length; i++) {
                            innerHtml +=
                                    '<option class="select-option" value="' +
                                    serverData[i].id + '">' +
                                    serverData[i].subdivision + '</option>';
                        }

                        $('#subdivision').html(innerHtml);
                    }
                });
            });
            //MODAL RESET ON CLOSE
            $('#myModal_search').on('hidden.bs.modal', function () {
                document.getElementById('adv_search_form').reset();
            });
            //DATE PICKER BETWEEN
            $('#from_datepicker').datepicker({
                dateFormat: 'dd-mm-yy',
                onClose: function (selectedDate) {
                    $("#to_datepicker").datepicker("option", "minDate", selectedDate);
                }
            });
            $('#to_datepicker').datepicker({
                dateFormat: 'dd-mm-yy',
                onClose: function (selectedDate) {
                    $("#from_datepicker").datepicker("option", "maxDate", selectedDate);
                }
            })
            //FOR ADVANCED SEARCH FINISHED


        </script>



    </body>
</html>