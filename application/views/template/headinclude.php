<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>

    <title><?php if(!empty($title)) echo "{$title} - "; ?> Produce Price Tracker.com</title>


    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <!-- jQuery -->
    <script type="text/javascript" src="/public/js/jquery-1.10.2.min.js"></script>

    <!-- Bootstrap -->
    <script type='text/javascript' src='/public/bootstrap3.0.3/js/bootstrap.min.js'></script>
    <!-- <link type="text/css" href="/public/bootstrap3.0.3/css/bootstrap.min.css" rel="Stylesheet" /> -->
    <link type="text/css" href="/public/css/bootstrap/bootstrap.spacelab.min.css" rel="Stylesheet" />


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


<?php if ($datatables == true) : ?>
        <script type='text/javascript' src='/public/datatables1.9.4/media/js/jquery.dataTables.min.js'></script>
        <style type='text/css' title='currentStyle'>
            @import '/public/datatables1.9.4/media/css/jquery.dataTables.css';
        </style>
<?php endif; ?>



<?php if ($this->config->item('live') == true) : ?>

<!-- Google Analytics -->
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-46843273-1', 'productpricetracker.com');
ga('send', 'pageview');

</script>
<?php else: ?>
    <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<?php endif; ?>

<?php if ($charts == true) : ?>
    <script type='text/javascript' src='/public/FusionCharts/FusionCharts.js'></script>
<?php endif; ?>


    <?=$this->functions->jsScript('global.js')?>
<?php
if($this->session->userdata('logged_in') === true)
{
    echo <<< EOS
<script type='text/javascript'>
global.logged_in = true;
global.userid = {$this->session->userdata('userid')}
</script>
EOS;
echo PHP_EOL;
}

if($this->session->userdata('admin') === true)
{
    echo <<< EOS
<script type='text/javascript'>
    global.admin = true;
</script>
EOS;
}

?>

<script type='text/javascript'>
global.bmsUrl = "<?=$this->config->item('CGIBMSURL')?>";
</script>

