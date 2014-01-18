<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class grabber_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * TODO: short description.
     *
     * @param mixed $id 
     *
     * @return TODO
     */
    public function getTrackingItemInfo ($id)
    {
        $id = intval($id);

        if (empty($id)) throw new Exception("Tracking Item ID is empty!");

        $mtag = "trackingItemsInfo-{$id}";

        $data = $this->cache->memcached->get($mtag);

        if (!$data)
        {
            $this->db->from('trackingItems');
            $this->db->where('id', $id);

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
     * @param mixed $id - rowid
     * @param mixed $itemID 
     *
     * @return TODO
     */
    public function updateItemID($id, $itemID)
    {
        $id = intval($id);

        if (empty($id)) throw new Exception("tracking Item Row ID is emptY!");

        $data = array('itemID' => $itemID);

        $this->db->where('id', $id);
        $this->db->update('trackingItems', $data);

        return true;
    }

    /**
     * TODO: short description.
     *
     * @param mixed $p 
     *
     * @return TODO
     */
    public function updateProductData ($id)
    {
        // gets latest HTML
        $file = $this->scraper->getLatestHtml($id);

    }
    
    /**
     * TODO: short description.
     *
     * @return TODO
     */
    public function getAllItemsToCheck ()
    {
        $mtag = "allTrackingItems";

        $data = $this->cache->memcached->get($mtag);

        if (!$data)
        {
            $this->db->select('id');
            $this->db->from('trackingItems');
            $this->db->where('deleted', 0);

            $query = $this->db->get();

            $data = $query->result();

            $this->cache->memcached->save($mtag, $data, $this->config->item('cache_timeout'));
        }

        return $data;
    }

    /**
     * gets all user id's that are assigned to an item
     *
     * @param mixed $trackingItemID 
     *
     * @return array
     */
    public function getUsersAssignedToItem ($trackingItemID)
    {
        $trackingItemID = intval($trackingItemID);

        if (empty($trackingItemID)) throw new Exception("Tracking Item ID is empty!");

        $mtag = "usersAssignedToItem-{$trackingItemID}";

        $data = $this->cache->memcached->get($mtag);

        if (!$data)
        {
            $this->db->select('userid');
            $this->db->from('trackingItemUserAssign');
            $this->db->where('trackingItemID', $trackingItemID);

            $query = $this->db->get();

            $data = $query->result();

            $this->cache->memcached->save($mtag, $data, $this->config->item('cache_timeout'));
        }

        if (empty($data)) return false;

        $userArray = array();

        foreach ($data as $r)
        {
            $userArray[] = $r->userid;
        }

        return $userArray;
    }

    /**
     * returns array of distinct userid's tracking any item ID's passed
     *
     * @param mixed $items 
     *
     * @return array - false if empty
     */
    public function getUsersTrackingItems ($items)
    {
        if (empty($items)) throw new Exception("No Items to check");

        $users = array();

        $this->db->distinct('userid');
        $this->db->select('userid');
        $this->db->from('trackingItemUserAssign');

        if (is_array($items))
        {
            $this->db->where_in('trackingItemID', $items);
        }
        else
        {
            $this->db->where('trackingItemID', $items);
        }

        $query = $this->db->get();

        $results = $query->result();

        if (empty($results)) return false;

        foreach ($results as $r)
        {
            $users[] = $r->userid;
        }

        return $users;
    }

}
