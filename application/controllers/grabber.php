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
        $this->load->model('users_model', 'users', true);
    }

    /**
     * TODO: short description.
     *
     * @return TODO
     */
    public function index ()
    {
        show_404();
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
                // gets item info
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
        try
        {

            $assigned = $this->tracker->checkTrackingItemAssigned(34, 1);

            print_r($assigned);

            // $users = $this->grabber->getUsersTrackingItems(array(1,34));

            // print_r($users);

            echo "Test is now complete" . PHP_EOL;


        }
        catch (Exception $e)
        {
            $this->functions->sendStackTrace($e);
            echo "<hr>" . $e->getMessage();
        }
    }
    /**
     * runs every hour and checks if items need to be downloaded and updated
     */
    public function cron ()
    {
        header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

        set_time_limit(0);

        // array that holds ID's of all items that were updated in this run
        $adjustedItems = array();

        try
        {
            echo PHP_EOL . 'Getting list of items' . PHP_EOL . PHP_EOL;

            $trackingItems = $this->grabber->getAllItemsToCheck();

            if (empty($trackingItems)) die("No items to check. Program will exit now");

            $failCount = 0;
            foreach ($trackingItems as $r)
            {
                echo "Checking Item: {$r->id}...";
                
                $reqDL = $this->scraper->checkRequireDownload($r->id);

                // if ($r->id == 1) $reqDL = true;

                // gets latest price before scrape
                $latestPrice = $this->tracker->getLatestPrice($r->id);

                $lpDoY = date("z", strtotime($latestPrice->priceDay));
                $todayDoY = date("z", strtotime(DATESTAMP));

                echo "LP-DOY: {$lpDoY} - Today-DOY: {$todayDoY}..";

                // once its a new day will force new download
                if ($lpDoY !== $todayDoY) $reqDL = true;

                if ($reqDL == true)
                {
                    echo "Downloading...";

                    $this->scraper->downloadHTML($r->id);

                    echo "100%! Updating item data...";

                    // sleeps
                    sleep(1);

                    try
                    {
                        $this->scraper->scrapeLatestData($r->id);

                        echo "Checking Prices...";

                        $newLatestPrice = $this->tracker->getLatestPrice($r->id);


                        if ($latestPrice->price !== $newLatestPrice->price)
                        {
                            echo "price has changed...";

                            $adjustedItems[] = $r->id;

                            echo "Added item ({$r->id}) to adjustedItems array...";

                            // there has been a price change
                            // $sentCnt = $this->_alertOfPriceChange($r->id);

                            // echo "{$sentCnt} users notified...";
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

            echo "\n\nAdjusted Items\n\n";

            print_r($adjustedItems);

            echo "\n\n***Item Updates complete. Will now send emails out to users***\n\n";

            // if there were items adjusted it will attempt to send them out
            if (empty($adjustedItems))
            {
                echo "No Items have been updated. No email to send!\n";
            }
            else
            {
                $this->_cronSendCompiledEmails($adjustedItems);
            }
        }
        catch (Exception $e)
        {
            $this->functions->sendStackTrace($e);
            echo "ERROR: " . $e->getMessage() . PHP_EOL;
        }

        echo "Finished!\n";

    }

    /**
     * sends a compiled email to all users who are tracking the items
     *
     * @param mixed $items 
     *
     * @return TODO
     */
    private function _cronSendCompiledEmails($items)
    {
        $users = $this->grabber->getUsersTrackingItems($items);

        if (!empty($users))
        {
            $subject = "Price Changes";

            // will now go through each user and compile the email to send to them
            foreach ($users as $k => $user)
            {
                $email = null;

                $totalItems = 0; // total items each user has updated in their email - wont send email if 0

                if (empty($user)) continue;

                $assigned = false;

                // $msg = "<h1>Price Changes</h1>";

                $msg = "<h1>Price Changes</h1> <p>Prices have changed for the following items:</p>" . PHP_EOL;

                if (!empty($items))
                {
                    foreach ($items as $item)
                    {
                        // will go through each item and check if they are assigned 
                        // to the item and will add it to their email;
                        $assigned = $this->tracker->checkTrackingItemAssigned($item, $user);

                        // user is assigned to this item - will add it to their e-mail
                        if ($assigned == true)
                        {
                            $itemInfo = $this->tracker->getTrackingItemInfo($item);

                            $msg .= "<p><a href='http://productpricetracker.com/tracker/details/{$item}'>{$itemInfo->itemName}</a></p>" . PHP_EOL;

                            $totalItems++;
                        }
                    }
                }

                // items were added to the email, so it is good to be sent
                if (!empty($totalItems))
                {
                    // attempts to send email
                    // if fails, continues with script
                    try
                    {
                        // get users e-mail address
                        $email = $this->users->getEmail($user);

                        echo "Sending Email to: {$email}" . PHP_EOL;

                        $this->functions->sendEmail($subject, $msg, $email);
                    }
                    catch (Exception $e)
                    {
                        $this->functions->sendStackTrace($e);
                        continue;
                    }
                }
            }
        }
    }

    /**
     * TODO: short description.
     *
     * @param mixed $trackingItemID 
     *
     * @return TODO
     */
    /*
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
     */
}
