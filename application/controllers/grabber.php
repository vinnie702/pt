<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Grabber extends CI_Controller
{

    function Grabber ()
    {
        parent::__construct();

        $this->load->driver('cache');

        $this->load->library('scraper');

        $this->load->model('grabber_model', 'grabber', true);
        $this->load->model('tracker_model', 'tracker', true);
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

                    $this->scraper->scrapeLatestData($_POST['id']);
                }

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

    public function test ()
    {

        echo "LIVE: {$this->config->item('live')}" . PHP_EOL;

        $subject = "Test";
        // $email = "brandonvinall@gmail.com";
        $email = "wgallios@cgisolution.com";

        $msg = "<h1>Test</h1> This is a test email.";


        echo "Sending Email to {$email}" . PHP_EOL;
        $this->functions->sendEmail($subject, $msg, $email);

        echo "Test is now complete" . PHP_EOL;


        /*
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
         */
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

                // gets latest price before scrape
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

                        echo "Checking Prices...";

                        $newLatestPrice = $this->tracker->getLatestPrice($r->id);


                        if ($latestPrice->price !== $newLatestPrice->price)
                        {
                            echo "price has changed...";

                            // there has been a price change
                            $sentCnt = $this->_alertOfPriceChange($r->id);

                            echo "{$sentCnt} users notified...";
                        }
                        else
                        {
                            echo "No Price Change...";
                        }

                    }
                    catch (Exception $e)
                    {
                        $this->functions->sendStackTrace($e);
                        echo "ERROR: {$e->getMessage()}";
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

    /**
     * TODO: short description.
     *
     * @param mixed $trackingItemID 
     *
     * @return TODO
     */
    private function _alertOfPriceChange ($trackingItemID)
    {
        // gets everyone assigned to item
        $users = $this->grabber->getUsersAssignedToItem($trackingItemID);

        if (empty($users)) return false;

        $info = $this->tracker->getTrackingItemInfo($trackingItemID);

        $subject = "Price Changed: {$info->itemName}";

        $msg = "<h2>Price Changed</h2>
            <p>Price for {$info->itemName} has changed. <a href='http://productpricetracker.com/tracker/details/{$trackingItemID}'>Click Here</a> to view the price difference.</p>
            ";

        $sentCnt = 0;
        foreach ($users as $user)
        {
            // get the users email
            $email = $this->functions->getUsersEmail($user);

            if (empty($email)) continue;

            $this->functions->sendEmail($subject, $msg, $email);

            $sentCnt++;
        }

        return $sentCnt;
    }
}
