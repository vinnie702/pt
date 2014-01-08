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

                // $reqDL  = $this->scraper->checkRequireDownload($_POST['id']);

                $reqDL = true;

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
        echo '<pre>';
        echo 'ID: ' . $id . PHP_EOL;
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
            $this->load->model('tracker_model', 'tracker', true);

            echo PHP_EOL . 'Getting list of items' . PHP_EOL . PHP_EOL;

            $trackingItems = $this->grabber->getAllItemsToCheck();

            if (empty($trackingItems)) die("No items to check. Program will exit now");

            $failCount = 0;

            foreach ($trackingItems as $r)
            {
                if ($failCount >= 3)
                {
                    echo "3 Failed scapes have happend! Please resolve!";
                    break;
                }

                echo "Checking Item: {$r->id}...";
                
                $reqDL = $this->scraper->checkRequireDownload($r->id);
                
                $latestPrice = $this->tracker->getLatestPrice($r->id);

                $lpDoY = date("z", strtotime($latestPrice->priceDay));
                $todayDoY = date("z", strtotime(DATESTAMP));

                // once its a new day will force new download
                if ($lpDoY !== $todayDoY) $reqDL = true;

                if ($reqDL == true)
                {
                    echo "Downloading...";

                    $this->scraper->downloadHTML($r->id);

                    echo "100%! Updating item data...";
                    
                    try
                    {
                        $this->scraper->scrapeLatestData($r->id);
                    }
                    catch (Exception $e)
                    {
                        $this->functions->sendStackTrace($e);
                        echo "ERROR: {$e->geMessage()}";
                        $failCount++;
                        continue;
                    }

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
