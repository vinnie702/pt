<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<h2><?=$info->itemName?></h2>
<input type='hidden' id='token' value='<?=$this->security->get_csrf_hash()?>'>

<div class='row'>
    <div class='col-lg-3 col-md-3 col-sm-3 col-xs-12'>
        <img src='<?=$info->imgUrl?>' class='img-responsive'>
    </div>

    <div class='col-lg-9 col-md-9 col-sm-9 col-xs-12'>

    <table class='table table-bordered'>
        <thead>
            <tr>
                <th><i class='fa fa-dollar'></i> Current</th>
                <th><i class='fa fa-dollar'></i> Highest</th>
                <th><i class='fa fa-dollar'></i> Lowest</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class='currentPrice'>$<?=number_format($latestPrice->price, 2)?></td>
                <td class='highPrice'>$<?=number_format($highPrice->price, 2)?></td>
                <td class='lowPrice'>$<?=number_format($lowPrice->price, 2)?></td>
            </tr>
        </tbody>
    </table>

 <?php 
    echo $this->functions->stripTags($info->description);

    // echo strip_tags($info->description);
    
    // echo $info->description;


?>

<div class='row'>
    <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
        <a href='<?=$this->functions->checkAmazonAssociateID($info->url)?>' class='btn btn-success btn-lg buyBtn' target='_blank'><i class='fa fa-dollar'></i> Buy Item</a>

        <?php
        if ($this->session->userdata('logged_in') == true AND $assigned == false)
        {
            echo "<button type='button' class='btn btn-info btn-lg buyBtn' onclick=\"tracker.assignItem(this, {$id});\"><i class='fa fa-plus-circle'></i> Track It!</button>" . PHP_EOL;
        }
        elseif ($this->session->userdata('logged_in') == true AND $assigned == true)
        {
            echo "<button type='button' class='btn btn-warning btn-lg buyBtn' onclick=\"tracker.grabInfo(this, {$id});\"><i class='fa fa-refresh'></i> Refresh</button>" . PHP_EOL;
            echo "<button type='button' class='btn btn-danger btn-lg buyBtn' onclick=\"tracker.unassignItem(this,{$id});\"><i class='fa fa-trash-o'></i> Unassign</button>" . PHP_EOL;
        }
            ?>
    </div>
</div> <!-- .row -->



    </div>
</div> <!-- .row -->

<div class='row'>
        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12' id='priceChartContainer'>
            Loading Price Graph...
    </div> <!-- col12 -->

    <script type='text/javascript'>
    <!--
        var priceChartVar = new FusionCharts("/public/FusionCharts/MSLine.swf", 'priceChart', '100%', '400', '0');
        priceChartVar.setXMLUrl("/tracker/pricexml/<?=$id?>");
        priceChartVar.render("priceChartContainer");
    //-->
    </script>
</div> <!-- .row -->
