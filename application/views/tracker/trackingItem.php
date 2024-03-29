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

                            <?php if ($this->session->userdata('logged_in') == true) : ?>

                            <?php 
                            try
                            {
                                $diff = $this->tracker->calcPriceDiffPrevDay($r->id);
                                
                                $lastPriceDate = $this->tracker->getLatestPriceDate($r->id);
                            }
                            catch (Exception $e)
                            {
                                $this->functions->sendStackTrace($e);
                            }
                            ?>
                            <tr>
                            <td class='percentChange'><?=$diff?>% 
                                <?php if ($diff <> 0) echo "<i class='fa fa-arrow-" . (($diff > 0) ? 'up danger' : 'down success') . "'></i>"; ?>
                                </td>
                                    <td colspan='2'><span class='text-muted'><?=date("m/d g:i A T", strtotime($lastPriceDate))?></span></td>
                            </tr>
                            <?php endif; ?>

                        </tbody>
                    </table>
                        <div class='cleafix'></div>
            </div> <!-- .panel-body -->

            <div class='panel-footer'>
                <?php if ($noBtns == true OR $this->session->userdata('logged_in') !== true) : 
                    echo "&nbsp;";
                else:
                ?>
                        <a href="<?=$url?>" class='btn btn-success btn-sm' target='_blank'><i class='fa fa-dollar'></i></a>

                    <?php if ($assigned == true) : ?>

                        <button type='button' class='btn btn-warning btn-sm' onclick="tracker.grabInfo(this, <?=$r->id?>);"><i class='fa fa-refresh'></i></button>
                        <button type='button' class='btn btn-danger btn-sm' onclick="tracker.unassignItem(this,<?=$r->id?>);"><i class='fa fa-trash-o'></i></button>

                    <?php else: ?>
                        <button type='button' class='btn btn-info btn-sm' onclick="tracker.assignItem(this, <?=$r->id?>);"><i class='fa fa-plus-circle'></i></button>
                    <?php endif; ?>
                <?php endif; ?>

            </div> <!-- .panel-footer -->
        </div> <!-- .panel -->

    </div> <!-- col-<?=$width?> -->

<?php
echo PHP_EOL;
?>
