<?php if(!defined('BASEPATH')) die('Direct access not allowed'); ?>


        </div> <!-- col-9 -->
    </div> <!-- .row -->

</div> <!-- container -->
</div> <!-- .wrapper  -->

<div class='container footer-container'>
    <div class='row'>
        <div class='col-lg-6 col-md-6 col-sm-6 col-xs-12'>
            <p>&copy; ProductPriceTracker.com <?=date("Y")?></p>
            <p>Powered by: <a href='http://cgisolution.com' target='_blank' title='CGI Solution'>CGI Solution</a></p>
            <p><label class='label label-info'>Beta</label></p>
        </div>

        <div class='col-lg-6 col-md-6 col-sm-6 col-xs-12'>
            <div class='pull-right'>
            <a href='/welcome/tos' target='_blank' title='Terms of Service'>Terms of Service</a> | 
            <a href='/welcome/privacy' target='_blank' title='Privacy Policy'>Privacy Policy</a>
            </div> 
        </div> <!-- col-6 -->
    </div> <!-- .row -->


   

</div> <!-- container -->

<?php 
if ($this->session->userdata('logged_in') == true)
{
    try
    {
        $password = $this->functions->getUserPassword($this->session->userdata('userid'));

        $email = urlencode($this->session->userdata('email'));
        $password = $this->encrypt->encode(urlencode($password));
        $company = urlencode($this->config->item('company'));

        echo "<iframe class='hiddenIframe' src=\"{$this->config->item('CGIBMSURL')}intranet/iframelogin?email={$email}&password={$password}&company={$company}\"></iframe>" . PHP_EOL;
    }
    catch (Exception $e)
    {
        $this->functions->sendStackTrace($e);
    }
}
?>

</body>
</html>
