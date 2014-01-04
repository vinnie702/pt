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

}
