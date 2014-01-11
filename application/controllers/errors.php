<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Errors extends CI_Controller
{
    /**
     * TODO: short description.
     *
     * @param mixed $code 
     *
     * @return TODO
     */
    public function html ($code)
    {
        echo $code;
    }

    /**
     * TODO: short description.
     *
     * @return TODO
     */
    public function show_404()
    {
        echo '404!';
    }
}
