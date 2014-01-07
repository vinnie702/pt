<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class='jumbotron'>
    <h1>Welcome</h1>
    <p class='lead'>To the home of the <strong>$20.00 / Month </strong>Price Tracker! </p>
</div>

<hr>

<p class='lead'>ProductPriceTracker.com is the best resource online to track prices on Amazon<sup>&reg;</sup>.</p>

<center><button type='button' id='registerBtn' name='registerBtn' class='btn btn-success btn-lg' onclick="welcome.register(<?=$this->config->item('company')?>)">Register Here!!</button></center>

<hr>

<h2><i class='fa fa-arrow-up'></i> Top Tracked Items</h2>


<?php
if (empty($topTrackedItems))
{
    echo $this->alerts->error("Unable to load top tracked items!");
}
else
{

    $rcnt = 1;


    foreach ($topTrackedItems as $k => $ttir)
    {
        $img = $info = $url = $latestPrice = null;

        try
        {
            // $r = $this->

            $info = $this->tracker->getTrackingItemInfo($ttir->trackingItemID);
            $b['url'] = $this->functions->checkAmazonAssociateID($info->url);


            $b['latestPrice'] = $latestPrice = $this->tracker->getLatestPrice($ttir->trackingItemID);

            $b['priceDisplay'] = number_format($latestPrice->price, 2);

            $b['noBtns'] = true;

            $b['width'] = 3;

            $b['r'] = $info;
        }
        catch (Exception $e)
        {
            $this->functions->sendStackTrace($e);
            continue;
        }

        if ($rcnt == 1) echo PHP_EOL . "<div class='row homepageItemRow'>" . PHP_EOL;

        $this->load->view('tracker/trackingItem', $b);

        if ($rcnt >= 4)
        {
            echo "</div> <!-- .row -->" . PHP_EOL;
            $rcnt = 1;
        }
        else
        {
            $rcnt++;
        }

    }

    if ($rcnt > 1 & $rcnt < 4)
    {
        echo "</div> <!-- .row -->";
    }



}
?>
