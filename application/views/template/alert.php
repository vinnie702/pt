<div id='site-alert'></div>
<?php
if (isset($_GET['site-alert']) && !empty($_GET['site-alert']))
{
    echo "<div class='alert alert-warning'>" .
        "<button type='button' class='close' data-dismiss='alert'>&times;</button>" .
        "<h4>Alert!</h4>" . urldecode($_GET['site-alert']) . "</div>";
}
if (isset($_GET['site-info']) && !empty($_GET['site-info']))
{
    echo "<div class='alert alert-info'>" .
        "<button type='button' class='close' data-dismiss='alert'>&times;</button>" .
        "<h4>Information!</h4>" . urldecode($_GET['site-info']) . "</div>";
}
if (isset($_GET['site-success']) && !empty($_GET['site-success']))
{
    echo "<div class='alert alert-success'>" .
        "<button type='button' class='close' data-dismiss='alert'>&times;</button>" .
        "<h4>Success!</h4>" . urldecode($_GET['site-success']) . "</div>";
}

if (isset($_GET['site-error']) && !empty($_GET['site-error']))
{
    echo "<div class='alert alert-danger'>" .
        "<button type='button' class='close' data-dismiss='alert'>&times;</button>" .
        "<h4>Error!</h4>" . urldecode($_GET['site-error']) . "</div>";
}
