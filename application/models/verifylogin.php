<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Campaign Select
 * This model serves to fetch campaign data.
 */
class Verifylogin extends CI_Model {

    function __construct() {
        parent::__construct();
        $ci = & get_instance();
        //$this->load->database();
        //$db = $this->db;
    }

    function login($data) {
        $query = $this->db->query("SELECT id, username, user_type_id FROM users WHERE (username = '" . $data['username'] . "' OR email = '" . $data['username'] . "') AND password = '" . md5($data['password']) . "' LIMIT 1");
//		echo $this->db->last_query(); die;
        $result = $query->result();
        if (count($result) == 1) {
            //print_r($query->row());
            $res = $query->row();
            $newdata = array(
                'login_name' => $res->username,
                'login_id' => $res->id,
                'login_ad' => FALSE, //ad - Active Directory
                'login_type' => $res->user_type_id,
                'logged_in' => TRUE
            );

            $this->session->set_userdata('check_login', $newdata);
            return true;
        } else {
            return false;
        }
    }
    
    function adLogin($data) {
        $this->load->library('ad');
        if($this->ad->connect() && $this->ad->bind()) {
            $userData = $this->ad->searchAD($data);
            if(!empty($userData) && $userData['login_id']!='') {
                $this->load->model('users', 'us');
                $adDivision = $this->us->adUserDivision($userData['login_id']);
                $userData['login_type'] = $adDivision->user_type_id;
                
                $this->session->set_userdata('check_login', $userData);
                return true;
            } else {
                return false;
            }
        }
    }        

}
