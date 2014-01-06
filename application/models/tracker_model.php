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

        $mtag = "checkTrackingItemAssigned-{$user}";

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
}
