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

<hr class='trackingSearch'>

<?php
    
$attr = array
    (
        'name' => 'trackSearchForm',
        'id' => 'trackSearchForm',
        'method' => 'GET'
    );

echo form_open('/tracker/landing', $attr);
?>

        <h4><i class='fa fa-search'></i> Search</h4>

        <div class='input-group'>
            <input type='text' class='form-control' name='q' id='q' value="<?=urldecode($_GET['q'])?>" placeholder='Toys'>
            <span class='input-group-btn'>
                <button type='button' class='btn btn-info' id='trackitBtn'><i class='fa fa-search'></i></button>
            </span>
        </div>

</form>



    </div> <!-- col-3 -->
    <div class='col-lg-9 col-md-9 col-sm-9 col-xs-12' id='trackingItemDisplay'>
