<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// echo $q;
if (empty($trackedItems))
{
    echo $this->alerts->info("You currently have no items being tracked.");
}
else
{
echo <<< EOS
    <table class='table table-hover table-bordered' id='mainDisplayTbl'>
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Current</th>
                <th>Highest</th>
                <th>Lowest</th>
                <th>Change</th>
                <th>Last Updated</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
EOS;

    foreach ($trackedItems as $r)
    {
        $img = $info = $url = $latestPrice = null;

        try
        {
            // $b['assigned'] = true;
            $url = $this->functions->checkAmazonAssociateID($r->url);

            $latestPrice = $this->tracker->getLatestPrice($r->id);
            $highPrice = $this->tracker->getLatestPrice($r->id, 'price', 'desc');
            $lowPrice = $this->tracker->getLatestPrice($r->id, 'price', 'asc');

            $priceDisplay = '$' . number_format($latestPrice->price, 2);

            $highPriceDisplay = '$' . number_format($highPrice->price, 2);
            $lowPriceDisplay = '$' . number_format($lowPrice->price, 2);

            $diff = $this->tracker->calcPriceDiffPrevDay($r->id);

            $lastPriceDate = $this->tracker->getLatestPriceDate($r->id);
        }
        catch (Exception $e)
        {
            $this->functions->sendStackTrace($e);
            continue;
        }

        echo "<tr>" . PHP_EOL;

        echo "\t<td width='20%' onclick=\"tracker.viewDetails({$r->id});\">{$r->itemName}</td>" . PHP_EOL;
        echo "\t<td onclick=\"tracker.viewDetails({$r->id});\">{$priceDisplay}</td>" . PHP_EOL;
        echo "\t<td onclick=\"tracker.viewDetails({$r->id});\">{$highPriceDisplay}</td>" . PHP_EOL;
        echo "\t<td onclick=\"tracker.viewDetails({$r->id});\">{$lowPriceDisplay}</td>" . PHP_EOL;
        echo "\t<td onclick=\"tracker.viewDetails({$r->id});\">{$diff}% ";

            if ($diff <> 0) echo "<i class='fa fa-arrow-" . (($diff > 0) ? 'up danger' : 'down success') . "'></i>";

        echo "</td>" . PHP_EOL;
        echo "\t<td onclick=\"tracker.viewDetails({$r->id});\"><span class='text-muted'>" . date("m/d g:i A T", strtotime($lastPriceDate)) . "</span></td>" . PHP_EOL;
        echo "\t<td>

            <a href=\"{$url}\" class='btn btn-success btn-sm' target='_blank'><i class='fa fa-dollar'></i></a>

            <button type='button' class='btn btn-warning btn-sm' onclick=\"tracker.grabInfo(this, {$r->id});\"><i class='fa fa-refresh'></i></button>
            <button type='button' class='btn btn-danger btn-sm' onclick=\"tracker.unassignItem(this,{$r->id});\"><i class='fa fa-trash-o'></i></button>
</td>" . PHP_EOL;

        echo "</tr>" . PHP_EOL;
    }

echo "</tbody>" . PHP_EOL;
echo "</table>" . PHP_EOL;
}
?>
