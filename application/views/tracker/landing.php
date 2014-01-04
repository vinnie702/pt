<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

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

<hr>
<?php
if (empty($trackedItems))
{
    echo $this->alerts->info("You currently have no items being tracked.");
}
else
{

}

?>
