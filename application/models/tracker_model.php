<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class tracker_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }


    /**
     * TODO: short description.
     *
     * @param mixed $trackingItemID 
     *
     * @return TODO
     */
    public function getTrackingItemInfo ($trackingItemID)
    {
        $trackingItemID = intval($trackingItemID);

        if (empty($trackingItemID)) throw new Exception("trackingItemID is empty!");

        $mtag = "trackingItemInfo-{$trackingItemID}";

        $data = $this->cache->memcached->get($mtag);

        if (!$data)
        {
            $this->db->from('trackingItems');
            $this->db->where('id', $trackingItemID);

            $query = $this->db->get();

            $results = $query->result();

            $data = $results[0];

            $this->cache->memcached->save($mtag, $data, $this->config->item('cache_timeout'));
        }

        return $data;
    }

    /**
     * TODO: short description.
     *
     * @param mixed $user Optional, defaults to 0. 
     *
     * @return TODO
     */
    public function getAssingedTrackingItems ($user = 0)
    {
        if (empty($user)) $user = $this->session->userdata('company');
        if (empty($user)) throw new Exception("User ID is empty!");

        $mtag = "userAssignedTrackingItems-{$user}";

        $data = $this->cache->memcached->get($mtag);

        if (!$data)
        {
            $this->db->select('trackingItemID');
            $this->db->from('trackingItemUserAssign');
            $this->db->where('userid', $user);

            $query = $this->db->get();

            $data = $query->result();

            $this->cache->memcached->save($mtag, $data, $this->config->item('cache_timeout'));
        }

        if (empty($data)) return false;

        $return = array();

        foreach ($data as $r)
        {
            $return[] = $r->trackingItemID;
        }

        return $return;
    }

    /**
     * TODO: short description.
     *
     * @param mixed $user Optional, defaults to 0. 
     *
     * @return TODO
     */
    public function getTrackingItems ($user = 0)
    {
        if (empty($user)) $user = $this->session->userdata('userid');

        $user = intval($user);

        if (empty($user)) throw new Exception("User ID is empty!");

        $mtag = "trackingItems-{$user}";

        $data = $this->cache->memcached->get($mtag);

        if (!$data)
        {
            $trackingItems = $this->getAssingedTrackingItems($user);

            $this->db->from('trackingItems');
            $this->db->where_in('id', $trackingItems);


            $query = $this->db->get();

            $data = $query->result();

            $this->cache->memcached->save($mtag, $data, $this->config->item('cache_timeout'));
        }

        return $data;
    }

    /**
     * TODO: short description.
     *
     * @param mixed $p 
     *
     * @return TODO
     */
    public function insertTrackingItem ($p)
    {
        $data = array
            (
                'datestamp' => DATESTAMP,
                'userid' => $this->session->userdata('userid'),
                'company' => $this->config->item('company'),
                'url' => $p['url'],
                'itemID' => $p['itemID'],
                'status' => 1
            );

        $this->db->insert('trackingItems', $data);

        return $this->db->insert_id();
    }

    /**
     * TODO: short description.
     *
     * @param mixed $trackingItemID 
     * @param mixed $user           
     *
     * @return TODO
     */
    public function insertTrackItemUserAssign ($trackingItemID, $user)
    {
        $trackingItemID = intval($trackingItemID);
        $user = intval($user);

        if (empty($trackingItemID)) throw new Exception("Tracking Item ID is empty!");
        if (empty($user)) throw new Exception("User ID is empty!");

            $data = array
                (
                    'trackingItemID' => $trackingItemID,
                    'userid' => $user
                );

        $this->db->insert('trackingItemUserAssign', $data);

        return $this->db->insert_id();
    }

    /**
     * TODO: short description.
     *
     * @param mixed $itemID 
     *
     * @return TODO
     */
    public function itemExists ($itemID)
    {
        // $itemID = intval($itemID);

        if (empty($itemID)) throw new Exception("Item ID is empty!");

        $mtag = "itemExists-{$itemID}";

        $data = $this->cache->memcached->get($mtag);

        if (!$data)
        {
            $this->db->select('id');
            $this->db->from('trackingItems');
            $this->db->where('itemID', $itemID);
            $this->db->limit(1);

            $query = $this->db->get();

            $results = $query->result();

            $data = $results[0]->id;

            $this->cache->memcached->save($mtag, $data, $this->config->item('cache_timeout'));
        }


        if (empty($data)) return false;

        return $data;
    }

    /**
     * TODO: short description.
     *
     * @param mixed $trackingItemID 
     * @param mixed $date           
     *
     * @return TODO
     */
    public function getDayPrice ($trackingItemID, $day)
    {
        $trackingItemID = intval($trackingItemID);

        if (empty($trackingItemID)) throw new Exception("Tracking Item ID is empty!");
        if (empty($day)) throw new Exception("Price Day is empty!");

        $mtag = "dayPrice-{$trackingItemID}-{$day}";

        $data = $this->cache->memcached->get($mtag);

        if (!$data)
        {
            $this->db->select('price');
            $this->db->from('trackingItemPrices');
            $this->db->where('trackingItemID', $trackingItemID);
            $this->db->where('priceDay', $day);
            $this->db->order_by('datestamp', 'desc');
            $this->db->limit('1');

            $query = $this->db->get();

            $results = $query->result();

            $data = $results[0]->price;

            $this->cache->memcached->save($mtag, $data, $this->config->item('cache_timeout'));
        }

        if (empty($data)) return false;

        return $data;
    }

    /**
     * TODO: short description.
     *
     * @param mixed $trackingItemID 
     *
     * @return TODO
     */
    public function unassignTrackingItem ($trackingItemID)
    {
        $trackingItemID = intval($trackingItemID);

        if (empty($trackingItemID)) throw new Exception("Tracking Item ID is empty!");

        $this->db->where('trackingItemID', $trackingItemID);
        $this->db->where('userid', $this->session->userdata('userid'));
        $this->db->delete('trackingItemUserAssign');

        return true;
    }

    /**
     * checks if a user is assigned to a trackign item
     *
     * @param int $trackingItemID
     * @param int $user 
     *
     * @return boolean - true if assigned, false otherwise
     */
    public function checkTrackingItemAssigned ($trackingItemID, $user = 0)
    {
        $trackingItemID = intval($trackingItemID);

        if (empty($trackingItemID)) throw new Exception("Tracking Item ID is empty!");

        if (empty($user)) $user = $this->session->userdata('userid');

        $user = intval($user);

        if (empty($user)) throw new Exception("User ID is empty!");

        $mtag = "checkTrackingItemAssigned-{$trackingItemID}-{$user}";

        $data = $this->cache->memcached->get($mtag);

        if (!$data)
        {
            $this->db->from('trackingItemUserAssign');
            $this->db->where('trackingItemID', $trackingItemID);
            $this->db->where('userid', $user);

            $data = $this->db->count_all_results();

            $this->cache->memcached->save($mtag, $data, $this->config->item('cache_timeout'));
        }

        if(!empty($data)) return true;

        return false;
    }

    /**
     * TODO: short description.
     *
     * @param mixed $trackingItemID 
     *
     * @return TODO
     */
    public function getLatestPrice ($trackingItemID, $orderCol = 'datestamp', $orderType = 'desc')
    {
        $trackingItemID = intval($trackingItemID);

        if (empty($trackingItemID)) throw new Exception("Tracking Item ID is empty!");

        $mtag = "latestPrice-{$trackingItemID}-{$orderCol}-{$orderType}";

        $data = $this->cache->memcached->get($mtag);

        if (!$data)
        {
            $this->db->from('trackingItemPrices');
            $this->db->where('trackingItemID', $trackingItemID);
            $this->db->order_by($orderCol, $orderType);
            $this->db->limit(1);

            $query = $this->db->get();

            $results = $query->result();

            $data = $results[0];

            $this->cache->memcached->save($mtag, $data, $this->config->item('cache_timeout'));
        }

        return $data;
    }
    
    /**
     * TODO: short description.
     *
     * @param mixed $limit Optional, defaults to 4. 
     *
     * @return TODO
     */
    public function getTopTrackedItems ($limit = 4)
    {

        $mtag = "topTrackItems-{$limit}";

        $data = $this->cache->memcached->get($mtag);

        if (!$data)
        {

            $this->db->select('trackingItemID, COUNT(*) as cnt');
            $this->db->from('trackingItemUserAssign');
            $this->db->group_by('trackingItemID');
            $this->db->order_by('cnt', 'desc');
            $this->db->limit(4);
            
            $query = $this->db->get();

            $data = $query->result();
            
            $this->cache->memcached->save($mtag, $data, $this->config->item('cache_timeout'));
        }

        return $data;
    }

    /**
     * Gets a percentage of increase from last price check
     *
     * @param mixed $item 
     *
     * @return TODO
     */
    public function calcPriceDiff ($item)
    {
        $item = intval($item);

        if (empty($item)) throw new Exception("Item ID is empty!");

        // first gets the current price
        $latestPrice = $this->tracker->getLatestPrice($item);

        if (empty($latestPrice)) return 0; // no prices at all to compare

        $prevPrice = $this->getSecondLatestPrice($item);

        // if no previous price, simply returns false;
        if (empty($prevPrice)) return 0;

        // echo "Latest: " . $latestPrice->price;
        // echo "Prev: " . $prevPrice->price;

        $diff = $latestPrice->price / $prevPrice->price;

        $diff = ($diff * 100) - 100;

        return number_format($diff, 2);
    }

    /**
     * Compares current price on chart, vs previous day on chart
     * for item details
     *
     * @param mixed $item 
     *
     * @return double
     */
    public function calcPriceDiffPrevDay ($item)
    {
        $item = intval($item);

        if (empty($item)) throw new Exception("Item ID is empty!");

        // first gets the current price
        $latestPrice = $this->tracker->getLatestPrice($item);

        if (empty($latestPrice)) return 0; // no prices at all to compare

        $prevDayPrice = $this->getPreviousDayPrice($item, $latestPrice->priceDay);

        // if no previous price, simply returns false;
        if (empty($prevDayPrice)) return 0;

        $diff = $latestPrice->price / $prevDayPrice->price;

        $diff = ($diff * 100) - 100;

        return number_format($diff, 2);
    }

    /**
     * TODO: short description.
     *
     * @param mixed $item       
     * @param mixed $currentDay (YYYY-MM-DDD) - the day you wish to get the price before
     *
     * @return TODO
     */
    public function getPreviousDayPrice ($trackingItemID, $currentDay)
    {
        $trackingItemID = intval($trackingItemID);

        if (empty($trackingItemID)) throw new Exception("trackingItemID is empty!");

        $mtag = "prevDayPrice-{$trackingItemID}-{$currentDay}";

        $data = $this->cache->memcached->get($mtag);

        if (!$data)
        {
            $this->db->from('trackingItemPrices');
            $this->db->where('trackingItemID', $trackingItemID);
            $this->db->where('priceDay <>', $currentDay);
            $this->db->order_by('datestamp', 'desc');
            $this->db->limit(1);

            $query = $this->db->get();

            $results = $query->result();

            // echo $this->db->last_query();

            $data = $results[0];

            $this->cache->memcached->save($mtag, $data, $this->config->item('cache_timeout'));
        }

        return $data;
    }

    /**
     * TODO: short description.
     *
     * @param mixed $trackingItemID
     *
     * @return object
     */
    public function getSecondLatestPrice ($trackingItemID)
    {
        $trackingItemID = intval($trackingItemID);

        if (empty($trackingItemID)) throw new Exception("trackingItemID is empty!");

        $mtag = "secondLatestPrice-{$trackingItemID}";

        $data = $this->cache->memcached->get($mtag);

        if (!$data)
        {
            $this->db->from('trackingItemPrices');
            $this->db->where('trackingItemID', $trackingItemID);
            $this->db->order_by('datestamp', 'desc');
            $this->db->limit(1, 1);

            $query = $this->db->get();

            $results = $query->result();

            $data = $results[0];

            $this->cache->memcached->save($mtag, $data, $this->config->item('cache_timeout'));
        }

        return $data;
    }

    /**
     * TODO: short description.
     *
     * @param mixed $trackingItemID 
     *
     * @return TODO
     */
    public function getLatestPriceDate ($trackingItemID)
    {
        $trackingItemID = intval($trackingItemID);

        if (empty($trackingItemID)) throw new Exception("trackingItemID is empty!");

        $mtag = "LatestPriceDate-{$trackingItemID}";

        $data = $this->cache->memcached->get($mtag);

        if (!$data)
        {
            $this->db->select('datestamp');
            $this->db->from('trackingItemPrices');
            $this->db->where('trackingItemID', $trackingItemID);
            $this->db->order_by('datestamp', 'desc');
            $this->db->limit(1);

            $query = $this->db->get();

            $results = $query->result();

            $data = $results[0]->datestamp;

            $this->cache->memcached->save($mtag, $data, $this->config->item('cache_timeout'));
        }

        return $data;
    }
}
