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
        set_time_limit(0);

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

                $reqDL  = $this->scraper->checkRequireDownload($_POST['id']);

                if ($reqDL == true)
                {
                    $this->scraper->downloadHTML($_POST['id']);

                }

                $this->scraper->scrapeLatestData($_POST['id']);

                // $this->grabber->updateProductData($_POST['id']);

                $this->functions->jsonReturn('SUCCESS', 'Product information has been updated!');
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
        // echo '<pre>';
        // echo 'ID: ' . $id . PHP_EOL;
        try
        {
            $this->scraper->scrapeLatestData($id);
        }
        catch (Exception $e)
        {
            $this->functions->sendStackTrace($e);
        }
    }

    /**
     * runs every hour and checks if items need to be downloaded and updated
     */
    public function cron ()
    {

        try
        {
            echo PHP_EOL . 'Getting list of items' . PHP_EOL . PHP_EOL;

            $trackingItems = $this->grabber->getAllItemsToCheck();

            if (empty($trackingItems)) die("No items to check. Program will exit now");

            foreach ($trackingItems as $r)
            {
                echo "Checking Item: {$r->id}...";
                
                $reqDL = $this->scraper->checkRequireDownload($r->id);

                if ($reqDL == true)
                {
                    echo "Downloading...";

                    $this->scraper->downloadHTML($r->id);

                    echo "100%! Updating item data...";

                    $this->scraper->scrapeLatestData($r->id);

                    echo "Done!" . PHP_EOL;
                }
                else
                {
                    echo "No download required." . PHP_EOL;
                }
            }

        }
        catch (Exception $e)
        {
            $this->functions->sendStackTrace($e);
            echo "ERROR: " . $e->getMessage();;
        }

        echo "Finished!\n";

    }
}
