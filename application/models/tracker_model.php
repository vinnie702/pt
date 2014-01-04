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
    public function getTrackingItems ($user = 0)
    {
        if (empty($user)) $user = $this->session->userdata('userid');

        $user = intval($user);

        if (empty($user)) throw new Exception("User ID is empty!");

        $mtag = "trackingItems-{$user}";

        $data = $this->cache->memcached->get($mtag);

        if (!$data)
        {
            $this->db->from('trackingItems');
            $this->db->where('userid', $user);

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
                'status' => 1
            );

        $this->db->insert('trackingItems', $data);

        return $this->db->insert_id();
    }
}
