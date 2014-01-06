<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<h2><?=$info->itemName?></h2>

<div class='row'>
    <div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>
        <img src='<?=$info->imgUrl?>' class='img-responsive'>
    </div>

    <div class='col-lg-9 col-md-9 col-sm-9 col-xs-9'>

 <?php 
    echo $this->functions->stripTags($info->description);

    // echo strip_tags($info->description);
    
    // echo $info->description;


    ?>
        <a href='<?=$this->functions->checkAmazonAssociateID($info->url)?>' class='btn btn-warning btn-lg buyBtn' target='_blank'>Buy Item</a>
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
