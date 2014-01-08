<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
if (empty($width)) $width = 4;
?>

    <div class='col-lg-<?=$width?> col-md-<?=$width?> col-sm-<?=$width?> col-xs-12 trackingItem'>
        <div class='panel panel-default'>
            <div class='panel-heading'>
                <a href='/tracker/details/<?=$r->id?>'><?=$r->itemName?></a>
            </div>

            <div class='panel-body' onclick="tracker.viewDetails(<?=$r->id?>);">
                <img class='img-responsive' src='<?=$r->imgUrl?>'>
            </div>

            <div class='panel-footer'>
                <?php if ($noBtns == true) : 
                    echo "&nbsp;";
                else:
                ?>
                <a href="<?=$url?>" class='btn btn-success btn-sm' target='_blank'><i class='fa fa-dollar'></i></a>
                <button type='button' class='btn btn-warning btn-sm' onclick="tracker.grabInfo(this, <?=$r->id?>);"><i class='fa fa-refresh'></i></button>
                <button type='button' class='btn btn-danger btn-sm' onclick="tracker.unassignItem(this,<?=$r->id?>);"><i class='fa fa-trash-o'></i></button>
                <?php endif; ?>

                <label class='pricePreview pull-right'>
                        $<?=$priceDisplay?>
                </label>

            </div>
        </div> <!-- .panel -->

    </div> <!-- col-<?=$width?> -->

<?php
echo PHP_EOL;
?>
