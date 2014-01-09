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



}
