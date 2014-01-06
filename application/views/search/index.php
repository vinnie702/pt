<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<h1><i class='fa fa-search'></i> Search</h1>

<?php
// print_r($itemResults);

$totalItemCnt = 0;

if (empty($itemResults['matches']))
{
    echo $this->alerts->info('Was not able to find any items being tracked with that search.');
}
else
{
    $rcnt = 1;
    foreach ($itemResults['matches'] as $k => $v)
    {
        $img = $info = null;

        try
        {
            $info = $this->grabber->getTrackingItemInfo($v['id']);
        }
        catch (Exception $e)
        {
            $this->functions->sendStackTrace($e);
            continue;
        }

        if ($rcnt == 1) echo PHP_EOL . "<div class='row homepageItemRow'>" . PHP_EOL;

echo <<< EOS

    <div class='col-lg-3 col-md-3 col-sm-3 col-xs-12 trackingItem'>
        <div class='panel panel-default'>
            <div class='panel-heading'>
                <a href='/tracker/details/{$v['id']}'>{$info->itemName}</a>
            </div>

            <div class='panel-body' onclick="search.viewDetails({$v['id']});">
                <img class='img-responsive' src='{$info->imgUrl}'>
            </div>

EOS;

        if ($this->session->userdata('logged_in') == true)
        {

            echo "<div class='panel-footer'>
                <button type='button' class='btn btn-info btn-sm' onclick=\"\"><i class='fa fa-plus-circle'></i></button>
            </div>";
        }

    echo "
        </div> <!-- .panel -->

    </div> <!-- col-3 -->";

    echo PHP_EOL;


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
