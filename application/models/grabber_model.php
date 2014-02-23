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
        if (empty($items))
        {
            error_log('**** ITEMS: ' . $items);
            throw new Exception("No Items to check");
        }

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

    /**
     * TODO: short description.
     *
     * @param mixed $d 
     *
     * @return TODO
     */
    public function createMasterItem ($d)
    {
        $data = array
            (
                'datestamp' => DATESTAMP,
                'userid' => $this->session->userdata('userid'),
                'company' => $this->config->item('company'),
                'name' => $d['title'],
                'description' => $d['details'],
                'retailPrice' => $d['price'],
                'brand' => $d['brand'],
                'model' => $d['model'],
                'sku' => $d['sku']
            );

        $this->db->insert('items', $data);

        $id = $this->db->insert_id();

        // saves item to root folder

        $data = array
            (
                'itemId' => $id,
                'folderId' => 0
            );


        $this->db->insert('itemFolderAssign', $data);

        return $id;
    }



    /**
     * TODO: short description.
     *
     * @param mixed $masterItemID 
     * @param mixed $data         
     *
     * @return TODO
     */
    public function downloadMasterItemImage ($masterItemID, $data)
    {
        $url = $data['img'];

        $masterItemID = intval($masterItemID);

        if (empty($masterItemID)) throw new Exception("Master Item ID is empty!");

        $path = $this->config->item('BMSpath') . 'public/uploads/uploader/' . $this->config->item('company') . '/';

        // ensures path exists
        $this->functions->createDir($path, true);

        $ext = $this->functions->getFileExt($url);

        $filename = uniqid() . '_' . date("YmdGis") . '.' . $ext;

        $getContents = file_get_contents($url);

        // if ($getContents === false) throw new Exception("Unable to get file content from: {$url}");

        $put = file_put_contents(($path.$filename), $getContents);

        // if ($put === false) throw new Exception("Unable to save contents of ({$url}) to: {$path}{$filename}");

        $data = array
            (
                'userid' => $this->session->userdata('userid'),
                'datestamp' => DATESTAMP,
                'itemID' => $masterItemID,
                'imageName' => $filename,
                'imgOrder' => 0
            );

        $this->db->insert('itemImages', $data);

        $id = $this->db->insert_id();

        return $id;
    }

    /**
     * TODO: short description.
     *
     * @param mixed $trackingItemID 
     * @param mixed $masterItemID   
     *
     * @return TODO
     */
    public function saveBMSItemID($trackingItemID, $masterItemID)
    {
        $trackingItemID = intval($trackingItemID);
        $masterItemID = intval($masterItemID);

        if (empty($trackingItemID)) throw new Exception("Tracking Item ID is empty!");
        if (empty($masterItemID)) throw new Exception("Master Item ID is empty!");

        $data = array('bmsItemID' => $masterItemID);

        $this->db->where('id', $trackingItemID);
        $this->db->update('trackingItems', $data);

        return true;
    }
}
