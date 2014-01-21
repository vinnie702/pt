<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller
{


    function Users ()
    {
        parent::__construct();

        $this->load->driver('cache');
        
        $this->functions->checkLoggedIn();

        $this->load->model('users_model', 'users', true);
        // $this->load->model('welcome_model', 'welcome', true);
        // $this->load->model('tracker_model', 'tracker', true);
    }

    /**
     * TODO: short description.
     *
     * @return TODO
     */
    public function index ()
    {
        if (!$this->functions->isCompanyAdmin())
        {
            show_404();
            // header("Location: /?site-error=" . urlencode("You do not have permission to view that page"));
            exit;
        }

        $header['headscript'] = $this->functions->jsScript('users.js');
        $header['onload'] = "users.indexInit();";
        $header['singleCol'] = true;
        $header['datatables'] = true;

        try
        {
            $body['companyUsers'] = $this->users->getCompanyUsers();
        }
        catch (Exception $e)
        {
            $this->functions->sendStackTrace($e);
        }


        $this->load->view('template/header', $header);
        $this->load->view('users/index', $body);
        $this->load->view('template/footer');
    }


}
