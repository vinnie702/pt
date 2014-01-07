<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class='jumbotron'>
    <h1>Welcome</h1>
    <p class='lead'>To the home of the <strong>$20.00 / Month </strong>Price Tracker! </p>
</div>

<hr>

<p class='lead'>ProductPriceTracker.com is the best resource online to track prices on Amazon<sup>&reg;</sup>.</p>

<center><button type='button' id='registerBtn' name='registerBtn' class='btn btn-success btn-lg' onclick="welcome.register(<?=$this->config->item('company')?>)">Register Here!!</button></center>
