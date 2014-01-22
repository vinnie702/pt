<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller
{

    function Welcome ()
    {
        parent::__construct();

        $this->load->driver('cache');

        $this->load->model('welcome_model', 'welcome', true);
        $this->load->model('tracker_model', 'tracker', true);
        $this->load->model('users_model', 'users', true);
    }

    public function index ()
    {

        $header['headscript'] = $this->functions->jsScript('welcome.js');
        $header['headscript'] .= $this->functions->jsScript('tracker.js');
        $header['singleCol'] = true;

        $body['utm_campaign'] = $header['utm_campaign'] = urldecode($_GET['utm_campaign']);

        try
        {
            $body['topTrackedItems'] = $this->tracker->getTopTrackedItems(4);
        }
        catch (Exception $e)
        {
            $this->functions->sendStackTrace($e);
        }

        $this->load->view('template/header', $header);
        $this->load->view('welcome/index', $body);
        $this->load->view('template/footer');
    }

    /**
     * TODO: short description.
     *
     * @return TODO
     */
    public function login ()
    {

        if ($_POST)
        {
            try
            {
                if (empty($_POST['email']) || empty($_POST['password']))
                {
                    header("Location: /welcome/login?site-error=" . urlencode("Please enter an email address and password in order to login!"));
                    exit;
                }
    
                $check = $this->functions->checkLogin($_POST['email'], $_POST['password']);

                if ($check === false)
                {
                    // invalid login
                    header("Location: /welcome/login?site-error=" . urlencode("Invalid username or password"));
                    exit;
                }
                else
                {
                    // checks company
                    $compCheck = $this->functions->checkCompany($check->id);

                    if ($compCheck == false)
                    {
                        // they are not assigned to price tracker company
                        header("Location: /welcome/login?site-error=" . urlencode("This user does not have access to this site."));
                        exit;
                    }

                    // set session
                    $this->functions->setLoginSession($check->id, $_POST['email'], ($check->firstName . ' ' . $check->lastName), true);

                     // user tried accessing a page while not logged in - takes them back to that page instead of landing
                    if (!empty($_POST['ref']))
                    {
                        header("Location: /" . $_POST['ref']);
                        exit;
                    }

                    header("Location: /tracker/landing");
                    exit;
                }
            }
            catch (Exception $e)
            {
                $this->functions->sendStackTrace($e);
                header("Location: /welcome/login?site-error=" . urlencode("Please enter an email address and password in order to login!"));
                exit;
            }

        }

        $header['headscript'] = $this->functions->jsScript('welcome.js');
        $header['onload'] = "welcome.loginInit();";
        $header['title'] = "Login";
        
        $body['utm_campaign'] = $header['utm_campaign'] = urldecode($_GET['utm_campaign']);

        $header['singleCol'] = true;
        $header['bcText'] = "<i class='fa fa-sign-in'></i> Login";

        try
        {

        }
        catch (Exception $e)
        {
            $this->functions->sendStackTrace($e);
        }


        $this->load->view('template/header', $header);
        $this->load->view('welcome/login');
        $this->load->view('template/footer');
    }


    public function logout($ajax = 0)
    {
        // page will not cache
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

        $email = $this->session->userdata('email');

        // destorys entire session
        $this->session->sess_destroy();

        if ((bool) $ajax == true)
        {
            $this->functions->jsonReturn('SUCCESS', "You have logged out!");
        }
        else
        {
            header("Location: /welcome/login?site-alert=" . urlencode("You have logged out!") . '&email=' . urlencode($email));
            exit;
        }
    }

    /**
     * TODO: short description.
     *
     * @return TODO
     */
    public function contactus ()
    {

        $body['utm_campaign'] = $header['utm_campaign'] = urldecode($_GET['utm_campaign']);
        $header['headscript'] = $this->functions->jsScript('welcome.js');
        $header['onload'] = "welcome.contactusInit();";
        $header['singleCol'] = true;
    

        $this->load->view('template/header', $header);
        $this->load->view('welcome/contactus', $body);
        $this->load->view('template/footer');
    }

    public function sendcontactus ()
    {
        if ($_POST)
        {
            try
            {
                // saves contat form in db
                $this->welcome->insertContactus($_POST);


                $subject = "New Contact Us Form! {$_POST['name']}";


                $message = "
                <h1>New Contact Us Form</h1>

                <p><strong>Name:</strong> {$_POST['name']}</p>
                <p><strong>E-mail:</strong> {$_POST['email']}</p>
                <p><strong>Phone:</strong> {$_POST['phone']}</p>
                <p><strong>Message:</strong></p>
                " .
                nl2br($_POST['message']);

                $emailTo = array ('bvinall@cgisolution.com', 'wgallios@cgisolution.com');
                // $emailTo = array ('brandonvinall@gmail.com', 'williamgallios@gmail.com');

                // will now send out email
                $this->functions->sendEmail($subject, $message, $emailTo);

                $this->functions->jsonReturn('SUCCESS', "Contact us form has been received!");

            }
            catch(Exception $e)
            {
                $this->functions->sendStackTrace($e);
                $this->functions->jsonReturn('ERROR', $e->getMessage());
            }

        }

        $this->functions->jsonReturn('ERROR', 'GET not supported!');
    }




    public function tos ()
    {
        $header['singleCol'] = true;

        $header['bcText'] = "Terms of Service";

        $this->load->view('template/header', $header);
        $this->load->view('welcome/tos', $body);
        $this->load->view('template/footer');
    }

    public function privacy ()
    {
        $header['singleCol'] = true;

        $header['bcText'] = "Privacy Policy";

        $this->load->view('template/header', $header);
        $this->load->view('welcome/privacy', $body);
        $this->load->view('template/footer');
    }


    /**
     * TODO: short description.
     *
     * @return TODO
     */
    public function loginas ()
    {
        // page will not cache
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");


        if (!$this->functions->isCompanyAdmin())
        {
            show_404();
        }

        try
        {


            $email = $this->users->getEmail($_POST['user']);
            $name = $this->users->getName($_POST['user']);

            // destorys entire session
            // $this->session->sess_destroy();

            $this->functions->setLoginSession($_POST['user'], $email, $name, true);

            $this->functions->jsonReturn('SUCCESS', "You are now logged in as {$name}!");
            // header("Location: /?site-success=" . urlencode("You are now logged in as {$name}!"));
            // exit;
        }
        catch (Exception $e)
        {
            $this->functions->sendStackTrace($e);
            $this->functions->jsonReturn('ERROR', $e->getMessage());
            // header("Location: /users?site-error=" . urlencode("Error logging as users!"));
            // exit;
        }

    }

    public function forgotpassword ()
    {
        if ($_POST)
        {
            try
            {
                $this->load->model('users_model', 'users', true);

                // get user ID from email address
                $user = $this->users->getIDFromEmail($_POST['fpEmail']);
error_log("USER:  {$user} from: {$_POST['fpEmail']}");
                if (empty($user))
                {
                    $this->functions->jsonReturn('ALERT', "Unable to find any accounts linked to that e-mail address!");
                }
                else
                {
                    // gets home company
                    $company = $this->config->item('company');
                error_log("company $company");
                    $requestID = $this->welcome->insertPasswordResetRequest($user, $company);

                    // get user email Address
                    $emailTo = $this->users->getEmail($user);

                    error_log("Email To: {$emailTo}");

                    // $companyName = $this->config->item('companyName');

                    $subject = "Password Reset";

                    $msg = "<h1>Password Reset</h1><p><a href='http://" . $_SERVER['HTTP_HOST'] . "/welcome/resetpassword/?requestID=" . urlencode($requestID)  . "' target='_blank'>Click here to reset your password</a></p>";
                    
                    $this->functions->sendEmail($subject, $msg, $emailTo);
                }

                $this->functions->jsonReturn('SUCCESS', "An e-mail will be sent to <strong>{$_POST['fpEmail']}</strong> with instructions on how to reset the password if there is an account associated with that e-mail address.", $requestID);


            }
            catch(Exception $e)
            {
                $this->functions->sendStackTrace($e);
                $this->functions->jsonReturn('ERROR', $e->getMessage());
            }
        }
    }

    public function resetpassword ()
    {
        $header['headscript'] = $this->functions->jsScript('welcome.js');
        $header['onload'] = "welcome.resetpasswordInit();";
        $header['singleCol'] = true;

        $body['requestID'] = $requestID = urldecode($_GET['requestID']);

        // no requestId was passed
        if (empty($requestID))
        {
            header("Location: /intranet/login");
            exit;
        }

        try
        {
            // first verify requestID is valid
            $user = $this->welcome->getPasswordResetUser($requestID);

            if (empty($user))
            {
                header("Location: /welcome/login?site-alert=" . urlencode("Unable to find a valid password reset request based upon that password request ID"));
                exit;
            }

            $body['email'] = $this->users->getEmail($user);

        }
        catch(Exception $e)
        {
            $this->functions->sendStackTrace($e);
        }

        $this->load->view('template/header', $header);
        $this->load->view('welcome/resetpassword', $body);
        $this->load->view('template/footer');
    }

    /**
     * used to process a password request
     */
    public function processpasswordrequest ()
    {
        if ($_POST)
        {
            try
            {
                // first verify requestID is valid
                $user = $this->welcome->getPasswordResetUser($_POST['requestID']);

                if (empty($user)) $this->functions->jsonReturn('ALERT', "Unable to find a valid password reset request based upon that password request ID");

                // first reset password
                $this->welcome->updateUserPassword($user, $_POST['password']);

                // deactivates all password reset requests for that user - they need to fill out the form again to reset their password again
                $this->welcome->deactivatePasswordRequests($user);
                
                $this->functions->jsonReturn('SUCCESS', "Password has been reset");
            }
            catch(Exception $e)
            {
                $this->functions->sendStackTrace($e);
                $this->functions->jsonReturn('ERROR', $e->getMessage());
            }
        }
    }


    /**
     * TODO: short description.
     *
     * @return TODO
     */
    public function test()
    {
        // echo "hey";
        show_404();
    }

    public function fblogin ()
    {
        if ($_POST)
        {
            try
            {
                // check if there is an account that has that facebookID
                $user = $this->functions->getUserIDFromFacebookID($_POST['facebookID']);

                if ($user === false) $this->functions->jsonReturn('ALERT', 'Unable to find an account linked to that account.');

                $this->functions->setLoginSession($user->id, $user->email, ($user->firstName . ' ' . $user->lastName), true);

                // user tried accessing a page while not logged in - takes them back to that page instead of landing
                /*
                if (!empty($_POST['ref']))
                {
                    header("Location: /" . $_POST['ref']);
                    exit;
                }
                */

                $this->functions->jsonReturn('SUCCESS', 'You are now logged in!');
            }
            catch (Exception $e)
            {
                $this->functions->sendStackTrace($e);
                $this->functions->jsonReturn('ERROR', $e->getMessage());
            }
        }
    }
}
