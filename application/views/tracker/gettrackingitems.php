<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
if (empty($trackedItems) && empty($itemResults['matches']))
{
    echo $this->alerts->info("You currently have no items being tracked.");
}
else
{
    $rcnt = 1;


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
