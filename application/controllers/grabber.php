<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Grabber extends CI_Controller
{

    function Grabber ()
    {
        parent::__construct();

        $this->load->driver('cache');

        $this->load->library('scraper');

        $this->load->model('grabber_model', 'grabber', true);
    }

    /**
     * TODO: short description.
     *
     * @return TODO
     */
    public function index ()
    {
        // echo 'hey';
    }

    public function grabinfo ()
    {

        $this->functions->checkLoggedIn();

        if ($_POST)
        {
            try
            {
                $info = $this->grabber->getTrackingItemInfo($_POST['id']);

                // if the item id is empty gets it from URL
                if (empty($info->itemID))
                {
                    // get product ID from URL
                    $itemID = $this->scraper->getIDFromURL($info->url);

                    $this->grabber->updateItemID($_POST['id'], $itemID);

                    $info->itemID = $itemID;
                }

                $this->functions->jsonReturn('SUCCESS', 'Production information has been updated!');
            }
            catch (Exception $e)
            {
                $this->functions->sendStackTrace($e);
                $this->functions->jsonReturn('ERROR', $e->getMessage());
            }
        }

        $this->functions->jsonReturn('ERROR', 'GET is not supported!');
    }

    public function test ($id)
    {
        echo '<pre>';

        echo 'ID: ' . $id . PHP_EOL;
        try
        {
            $info = $this->grabber->getTrackingItemInfo($id);

            print_r($info);

            echo "<hr>" . PHP_EOL;

            $id = $this->scraper->getIDFromURL($info->url);

            echo "ITEM ID: " . $id . PHP_EOL;
        }
        catch (Exception $e)
        {
            $this->functions->sendStackTrace($e);
        }
    }
}
