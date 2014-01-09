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

                <div align='center'> 
                <img class='img-responsive' src='<?=$r->imgUrl?>'>
                </div>
                <!-- <div class='row'> -->
                    <!-- <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'> -->

                    <table class='table itemPreview'>
                        <thead>
                            <tr>
                                <th>Current</th>
                                <th>Highest</th>
                                <th>Lowest</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class='currentPrice'>$<?=number_format($latestPrice->price, 2)?></td>
                                <?php if ($this->session->userdata('logged_in') == true) : ?>
                                    <td class='highPrice'>$<?=number_format($highPrice->price, 2)?></td>
                                    <td class='lowPrice'>$<?=number_format($lowPrice->price, 2)?></td>
                                <?php else : ?>
                                    <td class='highPrice'>Members Only</td>
                                    <td class='lowPrice'>Members Only</td>
                                <?php endif; ?>
                            </tr>
                        </tbody>
                    </table>
                        <div class='cleafix'></div>
            </div> <!-- .panel-body -->

            <div class='panel-footer'>
                <?php if ($noBtns == true) : 
                    echo "&nbsp;";
                else:
                ?>
                <a href="<?=$url?>" class='btn btn-success btn-sm' target='_blank'><i class='fa fa-dollar'></i></a>
                <button type='button' class='btn btn-warning btn-sm' onclick="tracker.grabInfo(this, <?=$r->id?>);"><i class='fa fa-refresh'></i></button>
                <button type='button' class='btn btn-danger btn-sm' onclick="tracker.unassignItem(this,<?=$r->id?>);"><i class='fa fa-trash-o'></i></button>
                <?php endif; ?>

                <?php /*
                <label class='pricePreview pull-right'>
                        $<?=$priceDisplay?>
                </label>
                */ ?>


            </div> <!-- .panel-footer -->
        </div> <!-- .panel -->

    </div> <!-- col-<?=$width?> -->

<?php
echo PHP_EOL;
?>
