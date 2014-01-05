<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<chart caption='Price over past 30 days' showLegend='0' numdivlines='9' lineThickness='2' showValues='0' numVDivLines='22' formatNumberScale='1' labelDisplay='ROTATE' slantLabels='1' anchorRadius='2' anchorBgAlpha='50' showAlternateVGridColor='1' anchorAlpha='100' animation='1' limitsDecimalPrecision='0' divLineDecimalPrecision='1'>

<?php

echo "<categories>" . PHP_EOL;

for ($i = 32; $i >= 0; $i -= 1)
{
    $mk = mktime(0, 0, 0, date("m"), date("d") - $i, date("Y"));

    $date = date("m/d", $mk);

    echo "\t<category label='{$date}' />" . PHP_EOL;
}

echo "</categories>" . PHP_EOL;

echo "<dataset seriesName='Price' color='0080C0' anchorBorderColor='0080C0' >" . PHP_EOL;

for ($i = 32; $i >= 0; $i -= 1)
{
    $val = 0.00;

    try
    {
        $mk = mktime(0, 0, 0, date("m"), date("d") - $i, date("Y"));

        $date = date("Y-m-d", $mk);

        $val = $this->tracker->getDayPrice($id, $date);

        if ($val === false) $val = 0;
    }
    catch (Exception $e)
    {
        $this->functions->sendStackTrace($e);
    }

    echo "\t<set value='{$val}' />" . PHP_EOL;
}

echo "</dataset>" . PHP_EOL;;

/*
    <categories >

        <category label='00:00' />

        <category label='01:00' />

        <category label='02:00' />

        <category label='03:00' />

        <category label='04:00' />

        <category label='05:00' />

        <category label='06:00' />

        <category label='07:00' />

        <category label='08:00' />

        <category label='09:00' />

        <category label='10:00' />

        <category label='11:00' />

        <category label='12:00' />

        <category label='13:00' />

        <category label='14:00' />

        <category label='15:00' />

        <category label='16:00' />

        <category label='17:00' />

        <category label='18:00' />

        <category label='19:00' />

        <category label='20:00' />

        <category label='21:00' />

        <category label='22:00' />

        <category label='23:00' />

    </categories>

    <dataset seriesName='Sat' color='0080C0' anchorBorderColor='0080C0' >

        <set value='36' />

        <set value='71' />

        <set value='85' />

        <set value='92' />

        <set value='101' />

        <set value='116' />

        <set value='164' />

        <set value='180' />

        <set value='192' />

        <set value='262' />

        <set value='319' />

        <set value='489' />

        <set value='633' />

        <set value='904' />

        <set value='1215' />

        <set value='1358' />

        <set value='1482' />

        <set value='1666' />

        <set value='1811' />

        <set value='2051' />

        <set value='2138' />

        <set value='2209' />

        <set value='2247' />

        <set value='2301' />

        </dataset>
*/
?>
</chart>
