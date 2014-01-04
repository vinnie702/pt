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
}
