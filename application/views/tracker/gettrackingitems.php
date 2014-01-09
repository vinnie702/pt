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
        $img = $info = $assigned = $b = null;

        try
        {

            if ($logged_in  == true)
            {
                $b['assigned'] = $assigned = $this->tracker->checkTrackingItemAssigned($v['id'], $this->session->userdata('userid'));
            }

            // skips items that are not assigned
            if ($assigned === false) continue;

            $info = $b['r'] = $this->grabber->getTrackingItemInfo($v['id']);

            $b['latestPrice'] = $latestPrice = $this->tracker->getLatestPrice($v['id']);
            $b['highPrice'] = $highPrice = $this->tracker->getLatestPrice($v['id'], 'price', 'desc');
            $b['lowPrice'] = $lowPrice = $this->tracker->getLatestPrice($v['id'], 'price', 'asc');

            $b['priceDisplay'] = number_format($latestPrice->price, 2);

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
                $b['assigned'] = true;
                $b['url'] = $this->functions->checkAmazonAssociateID($r->url);

                $b['latestPrice'] = $latestPrice = $this->tracker->getLatestPrice($r->id);
                $b['highPrice'] = $highPrice = $this->tracker->getLatestPrice($r->id, 'price', 'desc');
                $b['lowPrice'] = $lowPrice = $this->tracker->getLatestPrice($r->id, 'price', 'asc');



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
