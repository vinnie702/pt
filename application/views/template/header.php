<?php if(!defined('BASEPATH')) die('Direct access not allowed'); ?>
    <?php include_once 'headinclude.php'; ?>


    <?=$headscript?>

  </head>

<body<?=(empty($onload)) ? null : " onload=\"{$onload}\""?>>

<div class='container'>

<div class='row'>
    <div class='col-lg-4 col-md-4 col-sm-4 col-xs-4'>

        <h1>Price Tracker</h1>

    </div>

    <div class='col-lg-8 col-md-8 col-sm-8 col-xs-8'>
        <?php if ($this->session->userdata('logged_in') == true) : ?>
            <a href='/welcome/logout' class='btn btn-warning pull-right mainLoginLink'><i class='fa fa-sign-out'></i> Log Out</a>
        <?php else: ?>
            <a href='/welcome/login' class='btn btn-warning pull-right mainLoginLink'><i class='fa fa-sign-in'></i> Login</a>
        <?php endif; ?>
    </div>


</div> <!-- .row -->


<div class='row'>
    <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
        <nav class="navbar navbar-default" role="navigation">
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
                <ul class='nav navbar-nav'>
                    <li><a href='/' title='Home'><i class='fa fa-home'></i></a></li>
<?php
if ($this->session->userdata('logged_in') == true)
{
echo <<< EOS
        <li><a href='#'><i class='fa fa-cog'></i> Account Settings</a></li>
EOS;
}
?>
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
                    <div class='input-group'>
                        <input type='text' class='form-control' name='q' id='q' value="<?=urldecode($_GET['q'])?>" placeholder='Search'>
                            <span class='input-group-btn'>
                                <button type='submit' class='btn btn-default'><i class='fa fa-search'></i></button>
                            </span>
                    </div> <!-- .input-group -->
                </form>
            </div> <!-- .navbar-collapse -->
        </nav>


<ol class="breadcrumb">
    <li><a href='/' title='Home'><i class='fa fa-home'></i></a></li>
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

            <ul class='list-group'>
                <li class='list-group-item active'><a href='#' data-toggle='tab'> Amazon</a></li>
                <li class='list-group-item'><a href='#' data-toggle='tab'>Wal-Mart</a></li>
                <li class='list-group-item'><a href='#' data-toggle='tab'>Target</a></li>
                </ul>
    </div> <!-- col-3 -->

    <div class='col-lg-9 col-md-9 col-sm-9 col-xs-12'>
<?php endif; ?>

