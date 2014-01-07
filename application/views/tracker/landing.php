<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<input type='hidden' id='token' value='<?=$this->security->get_csrf_hash()?>'>

    <div class='panel panel-default'>
        <div class='panel-heading'><i class='fa fa-bar-chart-o'></i> Track an Item</div>

        <div class='panel-body'>

<?php

$attr = array
    (
        'name' => 'trackitForm',
        'id' => 'trackitForm',
        'onsubmit' => "return false;"
    );

echo form_open('#', $attr);
?>

    <p>Copy and Paste the URL of the item you wish to track below.</p>

            <input type='text' class='form-control' name='url' id='url' value="" placeholder='http://amazon.com/'>

</form>

        </div> <!-- .panel-body -->

        <div class='panel-footer'>
            <button type='button' class='btn btn-primary' id='trackitBtn'>Track It!</button>
        </div>

    </div> <!-- .panel -->




    </div> <!-- col-3 -->
    <div class='col-lg-9 col-md-9 col-sm-9 col-xs-12' id='trackingItemDisplay'>

<?php
/*
<h1><i class='fa fa-bar-chart-o'></i> Track an Item</h1>


<p class='lead'>Copy and Paste the URL of the item you wish to track below.</p>

<?php

$attr = array
    (
        'name' => 'trackitForm',
        'id' => 'trackitForm',
        'onsubmit' => "return false;"
    );

echo form_open('#', $attr);
?>

<div class='row'>
    <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>

        <div class='input-group'>
            <input type='text' class='form-control' name='url' id='url' value="" placeholder='http://amazon.com/'>
                <span class='input-group-btn'>
                    <button type='button' class='btn btn-primary' id='trackitBtn'>Track It!</button>
                </span>
        </div> <!-- .input-group -->

    </div> <!-- col-12 -->
</div> <!-- .row -->

</form>
 */
// <div id='trackingItemDisplay'></div>
?>
