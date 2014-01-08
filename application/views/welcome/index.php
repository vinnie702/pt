<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class='jumbotron'>
    <h1>Welcome</h1>

    <p class='lead'>ProductPriceTracker.com is leading online resource for tracking prices from Amazon<sup>&reg;</sup>. The regular price will be $20.00 / Month, however while we are currently in <label class='label label-info'>Beta</label>, you can sign up today and lock in your monthly rate at only <strong>$9.99 / Month </strong>.</p>

<button type='button' id='registerBtn' name='registerBtn' class='btn btn-success btn-lg' onclick="welcome.register(<?=$this->config->item('company')?>)"><i class='fa fa-pencil'></i> Register Now</button>
</div>

<div class='row'>
    <div class='col-lg-4 col-md-4 col-sm-4 col-xs-12'>
        <h3><i class='fa fa-envelope'></i> E-mail Alerts</h3>
        <p>As a member, you have the ability to have e-mail alerts delivered to you every time a price increases or decreases in your tracking list.</p>
    </div>

    <div class='col-lg-4 col-md-4 col-sm-4 col-xs-12'>
        <h3><i class='fa fa-list'></i> Custom Tracking Lists</h3>

        <p>Users have the ability to add items to be tracked even if they are currently not being tracked in the system. Simply copy and paste the URL and the system will take over.</p>
    </div>

    <div class='col-lg-4 col-md-4 col-sm-4 col-xs-12'>
        <h3><i class='fa fa-clock-o'></i> Price History</h3>
        <p>Once an item is added to our tracking system, it's price will be checked at least daily for any variances.</p>
    </div>

</div> <!-- .row -->


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
