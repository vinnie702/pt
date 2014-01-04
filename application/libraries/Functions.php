<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Functions
{
    private $ci;

    public function __construct()
    {
        $this->ci =& get_instance();

        // if connected to DB
        if (class_exists('CI_DB'))
        {

        }
    }

    public function jsScript($name, $path = 'public/js/')
    {
        return "<script type='text/javascript' src='/min/?f={$path}{$name}{$this->ci->config->item('min_debug')}&amp;{$this->ci->config->item('min_version')}'></script>" . PHP_EOL;
    }

    /**
     * TODO: short description.
     *
     * @param mixed $cssFile 
     * @param mixed $path    Optional, defaults to 'public/css/'. 
     *
     * @return TODO
     */
    public function cssScript($cssFile, $path = 'public/css/')
    {
        return "<link rel='stylesheet' type='text/css' href=\"/min/?f={$path}{$cssFile}{$this->ci->config->item('min_debug')}&amp;{$this->ci->config->item('min_version')}\" />" . PHP_EOL;
    }


    /**
     * Saves stack trace error in error log
     */
    public function sendStackTrace ($e)
    {

        $body = "Stack Trace Error:\n\n";
        $body .= "URL: {$_SERVER["SERVER_NAME"]}{$_SERVER["REQUEST_URI"]}\n";
        $body .= "Referer: {$_SERVER['HTTP_REFERER']}\n";
        // $body .= "User ID: {$ci->session->userdata('userid')}\n\n";
        $body .= "Message: " . $e->getMessage() . "\n\n";
        $body .= $e;

        error_log($body);
    }

    public function checkLogin ($email, $passwd)
    {
        $this->ci->db->select('id, status, admin');
        $this->ci->db->from('users');
        $this->ci->db->where('email', $email);
        $this->ci->db->where('passwd', sha1($passwd));
        // $this->ci->db->where_in('status', array(1,3));
        $this->ci->db->where('status', 1); // only allows active users to login

        $query = $this->ci->db->get();

        $results = $query->result();

        if (empty($results)) return false;

    return $results[0];
    }

    /**
     * TODO: short description.
     *
     * @return TODO
     */
    public function setLoginSession ($user, $email, $logged_in = true)
    {

        if (empty($user)) throw new Exception("User ID is empty!");
        if (empty($email)) throw new Exception("Email is empty!");

        $array = array
            (
                'userid' => (int) $user,
                'email' => $email,
                'logged_in' => $logged_in
            );

        $this->ci->session->set_userdata($array);

        return true;
    }

    /**
     * Checks if user is logged into backend
     *
     * @return boolean TRUE if logged in
     */
    public function checkLoggedIn ()
    {
        if ($this->ci->session->userdata('logged_in') === true)
        {
            // do nothing
        }
        else
        {
            header("Location: /welcome/login?site-error=" . urlencode("You are not logged in") . "&ref=" . uri_string());
            exit;
        }
    }


    /**
     * checks if the user is assigned to the company in config
     *
     * @param mixed $userid 
     *
     * @return boolean - true if assigned
     */
    public function checkCompany ($userid = 0)
    {
        if (empty($userid)) $userid = $this->ci->session->userdata('userid');

        $userid = intval($userid);

        if (empty($userid)) throw new Exception("User ID is empty!");

        // gets company ID
        $company = $this->ci->config->item('company');

        if (empty($company)) throw new Exception("Company ID is empty!");

        $mtag = "checkCompany-{$userid}-{$company}";

        $data = $this->ci->cache->memcached->get($mtag);

        if (!$data)
        {
            $this->ci->db->from('userCompanies');
            $this->ci->db->where('userid', $userid);
            $this->ci->db->where('company', $company);

            $data = $this->ci->db->count_all_results();

            $this->ci->cache->memcached->save($mtag, $data, $this->ci->config->item('cache_timeout'));
        }

        if ((int) $data > 0) return true;

        return false;
    }
}
