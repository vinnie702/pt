<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller
{

    function Search ()
    {
        parent::__construct();

        $this->load->model('search_model', 'search', true);

        $this->load->driver('cache');

        $config = array
            (
                'server' => $this->config->item('server'),
                'connect_timeout' => $this->config->item('connect_timeout'),
                'array_result' => $this->config->item('array_result')
            );

        $this->load->library('sphinxsearch', $config);
    }


    /**
     * TODO: short description.
     *
     * @return TODO
     */
    public function index ()
    {
        $header['singleCol'] = true;

        $body['folder'] = 0;

        try
        {
            $body['itemResults'] = $this->sphinxsearch->Query($_GET['q'], 'trackingItems');
        }
        catch (Exception $e)
        {
            $this->functions->sendStackTrace($e);
        }

        $this->load->view('template/header', $header);
        $this->load->view('search/index', $body);
        $this->load->view('template/footer');
    }
}
