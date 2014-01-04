<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>

    <title><?php if(!empty($title)) echo "{$title} - "; ?>High Speed Audio</title>


    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <!-- jQuery -->
    <script type="text/javascript" src="/public/js/jquery-1.10.2.min.js"></script>

    <!-- Bootstrap -->
    <script type='text/javascript' src='/public/bootstrap3.0.3/js/bootstrap.min.js'></script>
    <link type="text/css" href="/public/bootstrap3.0.3/css/bootstrap.min.css" rel="Stylesheet" />


    <!-- jQueryUI -->
    <link type="text/css" href="/public/jquery-ui-1.10.3/css/ui-lightness/jquery-ui-1.10.3.custom.min.css" rel="Stylesheet" />
    <script type="text/javascript" src="/public/jquery-ui-1.10.3/js/jquery-ui-1.10.3.custom.min.js"></script>

    <!-- main site css -->
    <?=$this->functions->cssScript('main.css')?>

    <!-- font awsome stuff -->
    <link rel="stylesheet" href="/public/font-awesome-4.0.3/css/font-awesome.min.css">
<!--[if lt IE 9]>
      <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<?php if ($this->config->item('live') == true) : ?>

<!-- Google Analytics -->

<?php else: ?>
    <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<?php endif; ?>


    <?=$this->functions->jsScript('global.js')?>
