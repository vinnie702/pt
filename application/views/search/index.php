<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<h1><i class='fa fa-search'></i> Search</h1>
    
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
                $assigned = $this->tracker->checkTrackingItemAssigned($v['id'], $this->session->userdata('userid'));
            }

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

        if ($logged_in == true && $assigned == false)
        {
            echo "<div class='panel-footer'>" . PHP_EOL;

            echo "<button type='button' class='btn btn-info btn-sm' onclick=\"tracker.assignItem(this, {$v['id']});\"><i class='fa fa-plus-circle'></i></button>" . PHP_EOL;

            echo "</div> <!-- .panel-footer -->";
        }
        elseif ($logged_in == true && $assigned == true)
        {
            echo "<div class='panel-footer'>" . PHP_EOL;

            echo "<button type='button' class='btn btn-warning btn-sm' onclick=\"tracker.grabInfo(this, {$v['id']});\"><i class='fa fa-refresh'></i></button>" . PHP_EOL;
            echo "<button type='button' class='btn btn-danger btn-sm pull-right' onclick=\"tracker.unassignItem(this,{$v['id']});\"><i class='fa fa-trash-o'></i></button>" . PHP_EOL;

            echo "</div> <!-- .panel-footer -->";
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
