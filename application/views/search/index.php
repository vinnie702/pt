<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<h1><i class='fa fa-search'></i> Search</h1>

    <p class='lead'>Below are the search results for <strong>&ldquo;<?=$q?>&rdquo;</strong></p>

<input type='hidden' id='token' value='<?=$this->security->get_csrf_hash()?>'>

<?php
// print_r($itemResults);

$totalItemCnt = 0;

if (empty($itemResults['matches']))
{
    echo $this->alerts->info('Was not able to find any items being tracked with that search.');
}
else
{
    $logged_in = $this->session->userdata('logged_in');

    $rcnt = 1;
    foreach ($itemResults['matches'] as $k => $v)
    {
        $img = $info = $assigned = null;

        try
        {
            $info = $this->grabber->getTrackingItemInfo($v['id']);

            if ($logged_in  == true)
            {
                $b['assigned'] = $assigned = $this->tracker->checkTrackingItemAssigned($v['id'], $this->session->userdata('userid'));
            }

            $b['width'] = 3;

            $b['url'] = $this->functions->checkAmazonAssociateID($info->url);

            $b['latestPrice'] = $latestPrice = $this->tracker->getLatestPrice($info->id);
            $b['highPrice'] = $highPrice = $this->tracker->getLatestPrice($info->id, 'price', 'desc');
            $b['lowPrice'] = $lowPrice = $this->tracker->getLatestPrice($info->id, 'price', 'asc');



            $b['priceDisplay'] = number_format($latestPrice->price, 2);

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
