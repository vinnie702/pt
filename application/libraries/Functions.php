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
        $this->ci->db->select('id, firstName, lastName, status, admin');
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
    public function setLoginSession ($user, $email, $name, $logged_in = true)
    {

        if (empty($user)) throw new Exception("User ID is empty!");
        if (empty($email)) throw new Exception("Email is empty!");

        $array = array
            (
                'userid' => (int) $user,
                'email' => $email,
                'name' => $name,
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
    public function checkLoggedIn ($jsonReturn = false)
    {
        if ($this->ci->session->userdata('logged_in') === true)
        {
            // do nothing
        }
        else
        {
            if ($jsonReturn == true) $this->jsonReturn('ERROR', "You are not logged in!");

            header("Location: /welcome/login?site-error=" . urlencode("You are not logged in") . "&ref=" . uri_string());
            exit;
        }
    }

    /**
     * Used for ajax JSON post returns
     *
     * @param mixed $status   
     * @param mixed $msg      
     *
     * @return TODO
     */
    public function jsonReturn ($status, $msg, $id = 0, $html = null)
    {
        $return['status'] = $status;
        $return['msg'] = $msg;

        if (!empty($id)) $return['id'] = $id;

        if (!empty($html)) $return['html'] = $html;

        echo json_encode($return);

        exit;
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

    /**
     * creates directory if does not exist
     *
     * @param String $path - path to directory to create: Example $path = "public" . PATH_SEPARATOR . "uploads" . PATH_SEPARATOR . "folderName"
     *
     * @return boolean
     */
    public function createDir($path)
    {

        if (!is_dir($_SERVER['DOCUMENT_ROOT'] . $path))
        {
            $create = mkdir($_SERVER['DOCUMENT_ROOT'] . $path, 0777, true);

            if ($create === false) throw new exception("Unable to create directory:" . $_SERVER['DOCUMENT_ROOT'] . $path);
            // attempts to set permissions for folder to allow copy
            @chmod($_SERVER['DOCUMENT_ROOT'] . $path, 0777);
        }
        else
        {
            // already a directory
            return true;
        }

    return true;
    }

    public function stripTags ($s)
    {
        // $s = strip_tags($s, '<p><br><a><b><strong><i><u><h1><h2><h3><h4><h5><div><img><ul><ol><hr><li><span><label><dd><dt><dl><table><tbody><thead><tr><th><td>');
        $s = strip_tags($s, '<p><br><b><strong><i><u><h1><h2><h3><h4><h5><img><ul><ol><hr><li><span><label><dd><dt><dl><table><tbody><thead><tr><th><td><script>');

        return $s;
    }

    /**
     * TODO: short description.
     *
     * @param mixed $url 
     *
     * @return TODO
     */
    public function checkAmazonAssociateID ($url)
    {

        $pattern = '/tag=cgisolution-20/';

        $match = preg_match($pattern, $url);

        // print_r($match);

        // url does not have associate ID
        if (empty($match))
        {
            $url = $url . "&tag=" . $this->ci->config->item('amazonAssID');
        }

        return $url;
    }

    /**
     * TODO: short description.
     *
     * @param mixed $user 
     *
     * @return TODO
     */
    public function getUsersEmail ($user)
    {
        $user = intval($user);
        if (empty($user)) throw new Exception("User ID is empty!");

        $mtag = "checkCompany-{$userid}-{$company}";

        $data = $this->ci->cache->memcached->get($mtag);

        if (!$data)
        {
            $this->ci->db->select('email');
            $this->ci->db->from('users');
            $this->ci->db->where('id', $user);

            $query = $this->ci->db->get();

            $results = $query->result();

            $data = $results[0]->email;

            $this->ci->cache->memcached->save($mtag, $data, $this->ci->config->item('cache_timeout'));
        }

        if (empty($data)) return false;

        return $data;
    }

    public function sendEmail ($subject, $message, $to, $from = 'noreply@productpricetracker.com', $fromName = 'ProductPRiceTracker.com', $cc = null, $bcc = null, $config = null)
    {
        // if no config params were defined for sending the message
        // will use localhost relay
        if (empty($config))
        {
            $config['protocol'] = 'sendmail';
            $config['mailpath'] = '/usr/sbin/sendmail';
            // $config['charset'] = 'iso-8859-1';
            $config['wordwrap'] = false;
            $config['mailtype'] = 'html';
        }

        $this->ci->email->initialize($config);

        $this->ci->email->from($from, $fromName);

        if ($this->ci->config->item('live') == true)
        {
            // Adds To
            if (is_array($to))
            {
                foreach ($to as $t)
                {
                    $this->ci->email->to($t);
                }
            }
            else
            {
                $this->ci->email->to($to);
            }

            // Adds CC
            if (is_array($cc))
            {
                foreach ($cc as $c)
                {
                    $this->ci->email->cc($c);
                }
            }
            else
            {
                $this->ci->email->cc($cc);
            }

            // adds BCC
            if (is_array($bcc))
            {
                foreach ($bcc as $b)
                {
                    $this->ci->email->bcc($b);
                }
            }
            else
            {
                $this->ci->email->bcc($bcc);
            }

        }
        else
        {
            // not on live site, will send to dev email address
            $this->ci->email->to($this->ci->config->item('devEmail'));
        }
        $this->ci->email->subject($subject);
        $this->ci->email->message($message);

        $this->ci->email->send();
        
        // $this->ci->email->print_debugger();

    }

    /**
     * Checks if a user is a company admin (that is different from a site admin!)
     *
     * @param mixed $user    
     * @param mixed $company 
     *
     * @return boolean - true if they are a company admin
     */
    public function isCompanyAdmin ($user = 0)
    {
        $user = intval($user);

        if (empty($user)) $user = $this->ci->session->userdata('userid');

        if (empty($user)) throw new Exception("User ID is empty!");

        $company = $this->ci->config->item('company');

        if (empty($company)) throw new Exception("Company ID is empty!");

        $mtag = "isCompanyAdmin-{$user}-{$company}";

        $data = $this->ci->cache->memcached->get($mtag);

        if (!$data)
        {
            $this->ci->db->from('companyAdmins');
            $this->ci->db->where('userid', $user);
            $this->ci->db->where('company', $company);

            $data = $this->ci->db->count_all_results();

            $this->ci->cache->memcached->save($mtag, $data, $this->ci->config->item('cache_timeout'));
        }


        if ($data > 0) return true;

        return false;
    }


    /**
     * TODO: short description.
     *
     * @param mixed $group     
     * @param mixed $company   Optional, defaults to 0. 
     * @param mixed $orderCol  Optional, defaults to null. 
     * @param mixed $orderType Optional, defaults to null. 
     *
     * @return TODO
     */
    public function getCodes($group, $company = 0, $orderCol = 'display', $orderType = 'asc')
    {
        $tag = "codes{$group}-{$company}-{$orderCol}-{$orderType}";

        $ci =& get_instance();

        $data = $ci->cache->memcached->get($tag);

        if (empty($data))
        {
            $ci->db->from('codes');
            $ci->db->where('group', $group);
            $ci->db->where('code <>', 0);
            $ci->db->where('active', 1);
            $companyArray = array('0');

            if (!empty($company)) $companyArray[] = $company;

            $ci->db->where_in('company', $companyArray);

            if (empty($orderCol)) $ci->db->order_by('display', 'asc');
            else $ci->db->order_by($orderCol, $orderType);

            $query = $ci->db->get();

            $data = $query->result();

            $ci->cache->memcached->save($tag, $data, $ci->config->item('cache_timeout'));
        }

    return $data;
    }

    public function codeDisplay($group, $code)
    {
        if (empty($group)) throw new Exception("Group is empty!");
        if (empty($code)) throw new Exception("code is empty!");

        $mtag = "code-{$group}-{$code}";

        $data = $this->ci->cache->memcached->get($mtag);

        if (empty($data))
        {
            $this->ci->db->select('display');
            $this->ci->db->from('codes');
            $this->ci->db->where('group', $group);
            $this->ci->db->where('code', $code);

            $query = $this->ci->db->get();

            $results = $query->result();

            $data = $results[0]->display;

            $this->ci->cache->memcached->save($mtag, $data, $this->ci->config->item('cache_timeout'));
        }

        return $data;
    }



}
