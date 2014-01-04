<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tracker extends CI_Controller
{

    function Tracker ()
    {
        parent::__construct();

        $this->load->driver('cache');

        $this->functions->checkLoggedIn();

        $this->load->model('tracker_model', 'tracker', true);
    }

    public function landing ()
    {
        $header['headscript'] = $this->functions->jsScript('tracker.js');
        $header['onload'] = "tracker.landingInit();";
        $header['singleCol'] = true;

        try
        {
            $body['trackedItems'] = $this->tracker->getTrackingItems();
        }
        catch (Exception $e)
        {
            $this->functions->sendStackTrace($e);
        }


        $this->load->view('template/header', $header);
        $this->load->view('tracker/landing', $body);
        $this->load->view('template/footer');
    }

    /**
     * TODO: short description.
     *
     * @return TODO
     */
    public function addurl ()
    {
        if ($_POST)
        {
            try
            {
                $id = $this->tracker->insertTrackingItem($_POST);
                $this->functions->jsonReturn('SUCCESS', 'Item has been added to your list of items being tracked.', $id);
            }
            catch (Exception $e)
            {
                $this->functions->sendStackTrace($e);
                $this->functions->jsonReturn('ERROR', $e->getMessage());
            }
        }
    }
}
