<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<hr>
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
        $img = $info = null;

        try
        {
            /*
            $info = $this->store->getItemInfo($item);
            // print_r($info);

            $shortDesc = nl2br($info->shortDescription);
        
            $img = $this->store->getItemMainImage($item);

            if (empty($img)) $imgDisplay = "<i class='fa fa-tag item-blank-img img-responsive'></i>";
            else $imgDisplay = "<img src='{$this->config->item('CGIBMSURL')}genimg/render/200?img=" . urlencode($img) . "&path=" . urlencode("uploader/{$this->config->item('company')}") . "' class='img-responsive'>";
            */
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
                <a href='/tracker/details/{$r->id}'>{$r->itemName}</a>
            </div>

            <div class='panel-body' onclick="tracker.viewDetails({$r->id});">
                <img class='img-responsive' src='{$r->imgUrl}'>
            </div>

            <div class='panel-footer'>
                <button type='button' class='btn btn-warning btn-xs' onclick="tracker.grabInfo(this, {$r->id});"><i class='fa fa-refresh'></i></button>
                <button type='button' class='btn btn-danger btn-xs' onclick="tracker.unassignItem(this,{$r->id});"><i class='fa fa-trash-o'></i></button>
            </div>
        </div> <!-- .panel -->

    </div> <!-- col-3 -->
EOS;

/*
echo <<< EOS
    <div class='col-lg-3 col-md-3 col-sm-3 col-xs-12 homepageItem'>
        <div class='wrapper'>
            <div onclick="store.viewItemDetails({$item}, 0)">
                <div id='trackItemAlert_{$r->id}'></div>
                {$imgDisplay}
                <label>{$r->itemName}</label>

                <img class='img-responsive' src='{$r->imgUrl}'>
            </div> <!-- onclick div container -->

        <div class='itemPriceContainer'>
            <span class='price'>\${$info->retailPrice}</span>
            <button type='button' class='btn btn-warning' onclick="tracker.grabInfo(this, {$r->id});"><i class='fa fa-refresh'></i></button>
        </div> <!-- .itemPriceContainer -->

        </div> <!-- .wrapper -->
    </div> <!-- col-3 -->
EOS;
 */
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

?>
