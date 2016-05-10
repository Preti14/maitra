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
        ===
    -->
    <meta charset="utf-8">
    <title>Maitra</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Charisma, a fully featured, responsive, HTML5, Bootstrap admin template.">
    <meta name="author" content="Muhammad Usman">

    <!-- The styles -->
    <link id="bs-css" href="<?=base_url();?>css/bootstrap-cerulean.min.css" rel="stylesheet">

    <link href="<?=base_url();?>css/charisma-app.css" rel="stylesheet">
    
    <!-- jQuery -->
    <script src="<?=base_url();?>bower_components/jquery/jquery.min.js"></script>
	
    <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- The fav icon -->
    <link rel="shortcut icon" href="<?=base_url();?>img/favicon.ico">

</head>

<body>
<div class="ch-container">
    <div>
        
    <div class="row">
        <div class="col-md-12 center login-header" style="height:auto;padding-top: 20px;">
        
        	<?php if($logo=='') $logo="icg_logo.png"; ?>
            <img src="<?=base_url();?>img/logo/<?=$logo;?>" />
        </div>
        <!--/span-->
    </div><!--/row-->
	<div class="row">
    	<div class="col-md-12 center" style="font-weight:bold; color:#2fa4e7;font-size:16px;letter-spacing:1px">
        <?php if($site_title!='') 
		echo strtoupper($site_title);
		else echo "MAITRA";?>
        </div>
    </div>
    <div class="row">
        <div class="well col-md-5 center login-box" style="width:33.3%">
        <?php if(isset($error)) { ?>
            <div class="alert alert-danger">
                <?php echo $error;?>
            </div><?php }else{?>
            <div class="alert alert-info">
                Please login with your Username and Password.
            </div><?php }?>
            <form class="form-horizontal" action="<?=base_url();?>?c=home&m=login" method="post">
                <fieldset>
                    <div class="input-group input-group-lg">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user blue"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="Username" value="<?php if(isset($_COOKIE['remember_me'])) echo $_COOKIE['remember_me']; ?><?php if(isset($_POST['username'])) echo $_POST['username']; ?>">
                    </div>
                    <div class="clearfix"></div><br>

                    <div class="input-group input-group-lg">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock blue"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Password">
                    </div>
                    <div class="clearfix"></div>
					<div class="w100 pull-left">
                    <div class="row">
                    <div class="input-prepend mat20 col-lg-8" style="text-align:left">
                        <label class="remember" for="remember"><input type="checkbox" id="remember_me" name="remember_me" <?php if(isset($_COOKIE['remember_me'])) { echo 'checked="checked"'; } else { echo ''; } ?> > Remember me</label>
                    </div>
                    <!--<div class="clearfix"></div>-->

                    <div class="col-lg-4 mat20">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                    </div>
                    </div>
                </fieldset>
            </form>
        </div>
        <!--/span-->
    </div><!--/row-->
    
    <div class="row">
<div style="font-size:13px" class="col-md-12">
        
        <p style="text-align:center;">Maitra - Mail Tracking System (ver 1.7), <?=ucfirst($footer_txt);?></p>    
       
        	
 </div>     
    </div>
</div><!--/fluid-row-->

</div><!--/.fluid-container-->

</body>

</html>
