<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
if (empty($trackedItems))
{
    echo $this->alerts->info("You currently have no items being tracked.");
}
else
{
    $rcnt = 1;
    foreach ($trackedItems as $k => $r)
    {
        $img = $info = $url = $latestPrice = null;

        try
        {
            $url = $this->functions->checkAmazonAssociateID($r->url);

            $latestPrice = $this->tracker->getLatestPrice($r->id);

            $priceDisplay = number_format($latestPrice->price, 2);
        }
        catch (Exception $e)
        {
            $this->functions->sendStackTrace($e);
            continue;
        }

        if ($rcnt == 1) echo PHP_EOL . "<div class='row homepageItemRow'>" . PHP_EOL;

echo <<< EOS

    <div class='col-lg-4 col-md-4 col-sm-4 col-xs-12 trackingItem'>
        <div class='panel panel-default'>
            <div class='panel-heading'>
                <a href='/tracker/details/{$r->id}'>{$r->itemName}</a>
            </div>

            <div class='panel-body' onclick="tracker.viewDetails({$r->id});">
                <img class='img-responsive' src='{$r->imgUrl}'>
            </div>

            <div class='panel-footer'>
                <a href="{$url}" class='btn btn-success btn-sm' target='_blank'><i class='fa fa-dollar'></i></a>
                <button type='button' class='btn btn-warning btn-sm' onclick="tracker.grabInfo(this, {$r->id});"><i class='fa fa-refresh'></i></button>
                <button type='button' class='btn btn-danger btn-sm' onclick="tracker.unassignItem(this,{$r->id});"><i class='fa fa-trash-o'></i></button>
    
                <label class='pricePreview pull-right'>
                \${$priceDisplay}
                </label>

            </div>
        </div> <!-- .panel -->

    </div> <!-- col-3 -->
EOS;

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

?>
