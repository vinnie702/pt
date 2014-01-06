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
        header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

        if ($_POST)
        {
            try
            {
                $this->load->library('scraper');

                // first get the items Item ID
                $_POST['itemID'] = $this->scraper->getIDFromURL($_POST['url']);


                // checks if an item exists with that item ID
                $id = $this->tracker->itemExists($_POST['itemID']);

                if ($id === false)
                {
                    $id = $this->tracker->insertTrackingItem($_POST);
                }

                // assigns item to user
                $this->tracker->insertTrackItemUserAssign($id, $this->session->userdata('userid'));

                // downloads a copy the HTML
                $this->scraper->downloadHTML($id);

                $this->scraper->scrapeLatestData($id);

                $this->functions->jsonReturn('SUCCESS', 'Item has been added to your list of items being tracked.', $id);
            }
            catch (Exception $e)
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
    public function gettrackeditems ()
    {
        try
        {
            $body['trackedItems'] = $this->tracker->getTrackingItems();
        }
        catch (Exception $e)
        {
            $this->functions->sendStackTrace($e);
        }
    
        $this->load->view('tracker/gettrackingitems', $body);
    }

    /**
     * TODO: short description.
     *
     * @return TODO
     */
    public function details ($id)
    {
        if (empty($id))
        {
            header("Location: /tracker/landing?site-alert=" . urlencode("Tracking Item ID was not specified!"));
            exit;
        }

        $header['headscript'] = $this->functions->jsScript('tracker.js');

        $header['charts'] = true;

        $header['onload'] = "tracker.detailsInit();";

        $header['singleCol'] = true;

        $body['id'] = $id;

        try
        {
            $this->load->model('grabber_model', 'grabber', true);

            $body['info'] = $info = $this->grabber->getTrackingItemInfo($id);

            $header['bcText'] = $info->itemName;

        }
        catch (Exception $e)
        {
            $this->functions->sendStackTrace($e);
        }

        $this->load->view('template/header', $header);
        $this->load->view('tracker/details', $body);
        $this->load->view('template/footer');
    }

    /**
     * TODO: short description.
     *
     * @return TODO
     */
    public function pricexml ($id)
    {
        header("Content-type: text/xml");

        $body['id'] = $id;

        try
        {
            
        }
        catch (Exception $e)
        {
            $this->functions->sendStackTrace($e);
        }

        $this->load->view('tracker/pricexml', $body);
    }

    /**
     * TODO: short description.
     *
     * @return TODO
     */
    public function unassign ()
    {
        if ($_POST)
        {
            try
            {
                $this->tracker->unassignTrackingItem($_POST['id']);

                $this->functions->jsonReturn('SUCCESS', 'Item has been removed!');
            }
            catch (Exception $e)
            {
                $this->functions->sendStackTrace($e);
                $this->functions->jsonReturn('ERROR', $e->getMessage());
            }
        }
    }
}
