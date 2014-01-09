<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class welcome_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * TODO: short description.
     *
     * @param mixed $p 
     *
     * @return TODO
     */
    public function insertContactus ($p)
    {

        $data = array
            (
                'datestamp' => DATESTAMP,
                'name' => $p['name'],
                'email' => $p['email'],
                'phone' => $p['phone'],
                'message' => $p['message'],
                'IP' => $_SERVER['REMOTE_ADDR']
            );

        // if (!empty($p[''])) $data[''] = $p[''];

        $this->db->insert('contactus', $data);

        return $this->db->insert_id();
    }

    public function insertPasswordResetRequest ($user, $company)
    {
        if (empty($user)) throw new Exception("User ID is empty!");
        if (empty($company)) throw new Exception("Company ID is empty!");

        $requestID = uniqid(null, true);

        $data = array
            (
                'datestamp' => DATESTAMP,
                'userid' => $user,
                'company' => $company,
                'requestID' => $requestID,
            );

        $this->db->insert('passwordResets', $data);

        return $requestID;
    }

    public function getPasswordResetUser ($requestID)
    {
        if (empty($requestID)) throw new Exception("Request ID is empty!");

        $mtag = "passwdResetUser-{$requestID}";

        $data = $this->cache->memcached->get($mtag);

        if (!$data)
        {
            $this->db->select('userid');
            $this->db->from('passwordResets');
            $this->db->where('requestID', $requestID);
            $this->db->where('active', 1);

            $query = $this->db->get();

            $results = $query->result();

            $data = $results[0]->userid;

            $this->cache->memcached->save($mtag, $data, $this->config->item('cache_timeout'));
        }

        return $data;
    }

    public function updateUserPassword ($user, $password)
    {
        if (empty($user)) throw new Exception("User ID is empty!");
        if (empty($password)) throw new Exception("Password is empty!");

        $data = array
            (
                'passwd' => sha1($password)
            );

        $this->db->where('id', $user);
        $this->db->update('users', $data);

        return true;
    }

    public function deactivatePasswordRequests ($user)
    {
        if (empty($user)) throw new Exception("User ID is empty!");

        $data = array
            (
                'active' => 0,
                'processed' => DATESTAMP
            );


        $this->db->where('userid', $user);
        $this->db->update('passwordResets', $data);

        return true;
    }

}
