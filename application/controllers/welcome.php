<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller
{

    function Welcome ()
    {
        parent::__construct();

        $this->load->driver('cache');

        $this->load->model('welcome_model', 'welcome', true);
        $this->load->model('tracker_model', 'tracker', true);
    }

    public function index ()
    {

        $header['headscript'] = $this->functions->jsScript('welcome.js');
        $header['headscript'] .= $this->functions->jsScript('tracker.js');
        $header['singleCol'] = true;

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

                $emailTo = array ('bvinall@cgisolution.com', 'williamgallios@gmail.com');

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

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
