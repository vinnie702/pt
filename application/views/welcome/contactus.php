<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php

$attr = array
    (
        'name' => 'contactusForm',
        'id' => 'contactusForm'
    );

echo form_open('#', $attr);
?>

<div class='row'>
    <div class='col-lg-8 col-m-8 col-s-12 col-xs-12'>

        <h1><i class='fa fa-phone'></i> Contact Us</h1>

        <p class='lead'>
        Do you have something to say? We would love to hear it! Feel free to reach out to us and share any feedback or suggestions you may have!
        </p>

        <div class='row'>
        <div class='col-lg-4 col-md-4 col-sm-4 col-xs-4'>
            <label for='name'>Name</label>
            <input type='text' class='form-control' name='name' id='name' value="" placeholder='John Smith'>
        </div>
        <div class='col-lg-4 col-md-4 col-sm-4 col-xs-4'>
            <label for='email'>E-mail</label>
            <input type='text' class='form-control' name='email' id='email' value="" placeholder='email@domain.com'>
        </div>
        <div class='col-lg-4 col-md-4 col-sm-4 col-xs-4'>
            <label for='phone'>Phone Number</label>
            <input type='text' class='form-control' name='phone' id='phone' value="" placeholder='888-444-9350'>
        </div>
        </div>
        <div class='row contactUsRow'>
            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                <label for='message'>Message</label>
                <textarea name='message' id='message' class='form-control' rows=6'></textarea>
            </div>
        </div> <!-- .row -->


        <div class='row contactUsRow'>
            <div class='col-lg-12 col-m-12 col-s-12 col-xs-12'>
                <button type='button' class='btn btn-primary' id='submitBtn'>Submit</button>
            </div>
        </div> <!-- .row -->

    </div> <!-- col-8 -->

    <div class='col-lg-4 col-md-4 col-sm-12 col-xs-12'>
        <h3>ProductPriceTracker.com</h3>
<p><i class="fa fa-phone"></i> <abbr title="Phone">P</abbr>: (888) 444-9350</p>
      <p><i class="fa fa-envelope-o"></i> <abbr title="Email">E</abbr>: <a href="mailto:info@productpricetracker.com">info@productpricetracker.com</a></p>
      <p><i class="fa fa-clock-o"></i> <abbr title="Hours">H</abbr>: Monday - Friday: 9:00 AM to 5:00 PM</p>

      <ul class="list-unstyled list-inline list-social-icons">
        <li class="tooltip-social facebook-link"><a href="https://www.facebook.com/cgiSolution" target='_blank' data-toggle="tooltip" data-placement="top" title="Facebook"><i class="fa fa-facebook-square fa-2x"></i></a></li>

        </ul>

    </div>

</div> <!-- .row -->

</form>
