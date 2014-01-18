<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<input type='hidden' id='token' value='<?=$this->security->get_csrf_hash()?>'>
<input type='hidden' id='viewType' value='<?=$viewType?>'>
<?php
/*
<h4><i class='fa fa-sort'></i> Sort By</h4>

<p>
<select name="sort" id="sort" class='form-control'>
    <option value=""></option>
    <!-- options -->
</select>
</p>
<hr>
 */
?>
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

<div id='leftSearchContainer'>

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

</div> <!-- #letSearchContainer -->

<hr>

<h4><i class='fa fa-eye'></i> View</h4>

<div class='btn-group'>
<?php
    if ((int) $viewType == 2) $tblDis = "disabled='disabled'";
    else $gridDis = "disabled='disabled'";
    ?>
        <button type='button' class='btn btn-default' id='gridViewBtn' <?=$gridDis?>><i class='fa fa-th'></i> Grid</button>
        <button type='button' class='btn btn-default'  id='tblViewBtn' <?=$tblDis?>><i class='fa fa-th-list'></i> Table</button>
</div>


    </div> <!-- col-3 -->
    <div class='col-lg-9 col-md-9 col-sm-9 col-xs-12' id='trackingItemDisplay'>
