<?php if(!defined('BASEPATH')) die('Direct access not allowed'); ?>
    <?php include_once 'headinclude.php'; ?>


    <?=$headscript?>

  </head>

<body<?=(empty($onload)) ? null : " onload=\"{$onload}\""?>>

<div class='wrapper'>
<div class='container'>

<div class='row'>
    <div class='col-lg-4 col-md-4 col-sm-4 col-xs-4'>

        <h1>ProductPriceTracker.com</h1>

    </div>

    <div class='col-lg-8 col-md-8 col-sm-8 col-xs-8'>
        <?php if ($this->session->userdata('logged_in') == true) : ?>
            <a href='/welcome/logout' class='btn btn-warning pull-right mainLoginLink'><i class='fa fa-sign-out'></i> Log Out</a>
            <a href='<?=$this->config->item('CGIBMSURL')?>/user/edit/<?=$this->session->userdata('userid')?>' class='pull-right mainUserLink'>
                <i class='fa fa-user'></i> <?=$this->session->userdata('name')?>
            </a>
        <?php else: ?>
            <a href='/welcome/login' class='btn btn-warning pull-right mainLoginLink'><i class='fa fa-sign-in'></i> Login</a>
        <?php endif; ?>
    </div>


</div> <!-- .row -->


<div class='row'>
    <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
        <nav class="navbar navbar-default" role="navigation" id='main-top-nav'>
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
          </div>

          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul id='main-nav' class='nav navbar-nav'>
<?php
if ($this->session->userdata('logged_in') == true)
{
echo <<< EOS
        <li><a href='/tracker/landing' title='Home'><i class='fa fa-home'></i></a></li>
        <li><a href='{$this->config->item('CGIBMSURL')}user/edit/{$this->session->userdata('userid')}' target='_blank'><i class='fa fa-cog'></i> Account Settings</a></li>
EOS;
}
else
{
?>
    <li><a href='/' title='Home'><i class='fa fa-home'></i></a></li>
    <li><a href='<?=$this->config->item('CGIBMSURL')?>register/index/<?=$this->config->item('company')?><?php if (!empty($utm_campaign)) echo "?utm_campaign={$utm_campaign}"; ?>'><i class='fa fa-pencil'></i> Register</a></li>

<?php } ?>

    <li><a href='/welcome/contactus'><i class='fa fa-phone'></i> Contact Us</a></li>

    <?php if ($this->session->userdata('logged_in') == true AND $this->functions->isCompanyAdmin()) : ?>
        <li><a href='/users'><i class='fa fa-user'></i> Users</a></li>
    <?php endif; ?>

                </ul>

                <?php
                    $attr = array
                        (
                            'class' => 'navbar-form navbar-right',
                            'role' => 'search',
                            'method'=> 'get'
                        );

                    echo form_open('/search', $attr);
                ?>
                    <div class='form-group'>
                        <input type='text' class='form-control' name='q' id='q' value="<?=urldecode($_GET['q'])?>" placeholder='Search Tracked Items'>
                    </div> <!-- .form-group -->
                    <button type='submit' class='btn btn-info'><i class='fa fa-search'></i></button>
                </form>
            </div> <!-- .navbar-collapse -->
        </nav>


<ol class="breadcrumb">
<?php if ($this->session->userdata('logged_in') == true) : ?>
    <li><a href='/tracker/landing' title='Home'><i class='fa fa-home'></i></a></li>
<?php else : ?>
    <li><a href='/' title='Home'><i class='fa fa-home'></i></a></li>
<?php endif; ?>

<?php
if (!empty($breadcrumbs))
{
    foreach ($breadcrumbs as $folder)
    {
        try
        {
            $name = $this->functions->getFolderName($folder);
        }
        catch (Exception $e)
        {
            $this->functions->sendStackTrace($e);
            $name = "[Database Error]";
        }

        echo "\t<li><a href='/store/browse/{$folder}'><i class='fa fa-folder-open-o'></i> {$name}</a></li>" . PHP_EOL;
    }
}

// display for cart progression

if (!empty($breadcrumbsCart))
{
    echo $breadcrumbsCart;
}


if (!empty($bcText))
{
    echo "\t<li>{$bcText}</li>" . PHP_EOL;
}
?>
</ol>

    </div> <!-- .col-12 -->


</div> <!-- .row -->


<?php include 'alert.php'; ?>


<div class='row'>
<?php if ($singleCol == true) : ?>

    <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>

<?php else: ?>
    <div class='col-lg-3 col-md-3 col-sm-3 col-xs-12'>


<?php

/*
 ***** PUT this in the top of your view if you are using 2 columns ****
    </div> <!-- col-3 -->

        <div class='col-lg-9 col-md-9 col-sm-9 col-xs-12'>
 *
 */
?>
<?php endif; ?>

