<html>
    <head>
        <title>File Cover</title>
        <link href="<?= base_url(); ?>css/file_cover_page.css" rel="stylesheet">
    </head>
    <body>

<!--        <div id="label_middle"  class="clearfix">
            <div class="pull-left"><img width="80" height="100" src="<?php echo base_url() ?>/img/logo/04-logo-rhqe1411042953.jpg">
                <div class="code-number"><?php echo $barcode_id ?></div>
            </div>
            <div class="pull-left">
                <div class="list-view">
                    <label>विभाग :</label><?php echo $file_department_hindi ?><br>
                    <label>DEP:</label><?php echo $department ?><br>
                    <label>विषय: </label><?php echo $file_subject_hindi ?><br>
                    <label>SUB:</label><?php echo $file_subject ?>
                </div>
            </div>
        </div>
        <div id="label_right_top" class="clearfix">
            <div class="code">
                <img src="<?php echo base_url() ?>/bar_codes/<?php echo $barcode_id ?>.png">
                <div class="code_number">
                    <?php echo $file_number ?>
                </div>
            </div>
            <div class="volume_part_case">
                VOL-<?php echo $volume ?>
                <?php if (strlen($file_part_number) !== 0): ?>/ PC-<?php echo $file_part_number;
            endif; ?>
            </div>
        </div>-->
        <div id="label_left_bottom">
            <div class="pull-left">
                <img src="<?php echo base_url() ?>/bar_codes/<?php echo $barcode_id ?>.png">
            </div>
            <div class="code_number">
                <?php echo $file_number ?>
            </div>
        </div>
    </body>
</html>

