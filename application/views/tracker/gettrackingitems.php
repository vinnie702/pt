<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// echo $q;
if (empty($trackedItems) && empty($itemResults['matches']))
{
    echo $this->alerts->info("You currently have no items being tracked.");
}
else
{
    $rcnt = 1;

    $logged_in = $this->session->userdata('logged_in');

    // goes through searched items
    foreach ($itemResults['matches'] as $k => $v)
    {
        $img = $info = $assigned = null;

        try
        {
            $info = $b['r'] = $this->grabber->getTrackingItemInfo($v['id']);

            $b['latestPrice'] = $latestPrice = $this->tracker->getLatestPrice($v['id']);

            $b['priceDisplay'] = number_format($latestPrice->price, 2);

            if ($logged_in  == true)
            {
                $assigned = $this->tracker->checkTrackingItemAssigned($v['id'], $this->session->userdata('userid'));
            }

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


    if (!empty($trackedItems))
    {
        foreach ($trackedItems as $k => $r)
        {
            $img = $info = $url = $latestPrice = null;

            try
            {
                $b['url'] = $this->functions->checkAmazonAssociateID($r->url);

                $b['latestPrice'] = $latestPrice = $this->tracker->getLatestPrice($r->id);

                $b['priceDisplay'] = number_format($latestPrice->price, 2);

                $b['r'] = $r;
            }
            catch (Exception $e)
            {
                $this->functions->sendStackTrace($e);
                continue;
            }

            if ($rcnt == 1) echo PHP_EOL . "<div class='row homepageItemRow'>" . PHP_EOL;

                $this->load->view('tracker/trackingItem', $b);

           echo PHP_EOL;


            if ($rcnt >= 3)
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
}

?>
