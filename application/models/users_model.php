<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class users_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }


    /**
     * TODO: short description.
     *
     * @return TODO
     */
    public function getCompanyUsers ()
    {
        $company = $this->config->item('company');

        $company = intval($company);

        if (empty($company)) throw new Exception("Company ID is empty!");

        $mtag = "companyUsers-{$company}";

        $data = $this->cache->memcached->get($mtag);

        if (!$data)
        {
            $this->db->select('id, userid, homeCompany');
            $this->db->from('userCompanies');
            $this->db->where('company', $company);

            $query = $this->db->get();

            $data = $query->result();

            $this->cache->memcached->save($mtag, $data, $this->config->item('cache_timeout'));
        }

        if (empty($data)) return false;

        return $data;
    }


    /**
     * TODO: short description.
     *
     * @param mixed $userid 
     *
     * @return TODO
     */
    public function getName ($user)
    {
        $user = intval($user);

        if (empty($user)) throw new Exception("User ID is empty!");

        $mtag = "userName-{$user}";

        $data = $this->cache->memcached->get($mtag);

        if (!$data)
        {
            $this->db->select('firstName, lastName');
            $this->db->from('users');
            $this->db->where('id', $user);

            $query = $this->db->get();

            $results = $query->result();

            $data = "{$results[0]->firstName} {$results[0]->lastName}";

            $this->cache->memcached->save($mtag, $data, $this->config->item('cache_timeout'));
        }

        return $data;
    }

    public function getEmail ($user)
    {
        $user = intval($user);

        if (empty($user)) throw new Exception("User ID is empty!");

        $mtag = "userName-{$user}";

        $data = $this->cache->memcached->get($mtag);

        if (!$data)
        {
            $this->db->select('email');
            $this->db->from('users');
            $this->db->where('id', $user);

            $query = $this->db->get();

            $results = $query->result();

            $data = $results[0]->email;

            $this->cache->memcached->save($mtag, $data, $this->config->item('cache_timeout'));
        }

        return $data;
    }



    /**
     * TODO: short description.
     *
     * @param mixed $user 
     *
     * @return TODO
     */
    public function getUserCompanyPosition ($user)
    {
        $user = $this->session->userdata('userid');

        $user = intval($user);

        if (empty($user)) throw new Exception("User ID is empty!");

        $company = $this->config->item('company');

        $company = intval($company);

        if (empty($company)) throw new Exception("Company ID is empty!");

        $mtag = "userCompanyPosition-{$user}-{$company}";

        $data = $this->cache->memcached->get($mtag);

        if (!$data)
        {

            $this->db->select('position');
            $this->db->from('userCompanyPositions');
            $this->db->where('userid', $user);
            $this->db->where('company', $company);

            $query = $this->db->get();

            $results = $query->result();

            $data = $results[0]->position;

            $this->cache->memcached->save($mtag, $data, $this->config->item('cache_timeout'));
        }

        if (empty($data)) return false;

        return $data;

    }

    /**
     * TODO: short description.
     *
     * @param mixed $position 
     *
     * @return TODO
     */
    public function getPositionName ($position)
    {
        $position = intval($position);

        if (empty($position)) throw new Exception("Position ID is empty");

        $mtag = "userCompanyPosition-{$user}-{$company}";

        $data = $this->cache->memcached->get($mtag);

        if (!$data)
        {

            $this->cache->memcached->save($mtag, $data, $this->config->item('cache_timeout'));
        }



    }
}