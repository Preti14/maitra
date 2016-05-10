<?php
/*
 * START
 * FTS Side bar
 */
/*
 * START
 * List ul li division 
 */
foreach ($divisions as $div):
    ?> 

    <li class="accordion"<?= ($_GET['m'] == "list_all_files" && filter_input(INPUT_GET,
            'div_id') == $div['id']) ? 'class="active"' : '';
    ?>>                            

        <a class="div_link" href="<?= base_url(); ?>?c=fts&m=list_all_files&div_id=<?php echo $div['id'] ?>">
            <i class="glyphicon glyphicon-list-alt"></i>
            <span> 
                <?php
                if ($div['division'] != ""):
                    echo $div['division'];
                endif;
                ?>
            </span>
        </a>

        <?php
        if (count($div['subdivisions']) > 0 && filter_input(INPUT_GET, 'div_id') == $div['id']):
            ?>
            <ul class="nav nav-pills nav-stacked" style="display: block;">
                <?php
                foreach ($div['subdivisions'] as $subDivision):
                    ?>

                    <li class="">
                        <a class="subdiv_link" href="<?= base_url(); ?>?c=fts&m=list_all_files&div_id=<?php echo $div['id'] ?>&subdivision_id=<?php echo $subDivision['id'] ?>" class="subdiv_link">
                            <?php echo $subDivision['subdivision'] ?>
                        </a>
                    </li>
                    <?php
                endforeach;
                ?>
            </ul>
            <?php
        endif;
        ?>

    </li>
    <?php
endforeach;
/*
 * END
 * List ul li division 
 */
/*
 * START
 * FTS Side bar
 */
?>