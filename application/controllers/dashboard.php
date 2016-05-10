<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct() {
        parent::__construct();
        //$this->output->enable_profiler(TRUE);
        $this->load->library('pagination');
        $this->load->library('session');
    }

    public function index() {
        if (!$this->session->userdata('check_login')) {
            redirect(base_url());
        }
        
        $arr['division_id'] = isset($_GET['division_id']) ? $_GET['division_id'] : '';
        $arr['subdivision_id'] = isset($_GET['subdivision_id']) ? $_GET['subdivision_id'] : '';
        if ($arr['division_id'] != '') {
            $this->session->set_userdata('div_data', $arr);
        }

        $this->template_view('dashboard/index', 'template_dashboard');
    }

    public function getFileChart() {
        $months = array(
                    "01" => "Jan",
                    "02" => "Feb",
                    "03" => "Mar",
                    "04" => "Apr",
                    "05" => "May",
                    "06" => "Jun",
                    "07" => "Jul",
                    "08" => "Aug",
                    "09" => "Sep",
                    "10" => "Oct",
                    "11" => "Nov",
                    "12" => "Dec"
            );
        $data_tmp['01'] = array("y"=>"Jan", "a" => 0);
        $data_tmp['02'] = array("y"=>"Feb", "a" => 0);
        $data_tmp['03'] = array("y"=>"Mar", "a" => 0);
        $data_tmp['04'] = array("y"=>"Apr", "a" => 0);
        $data_tmp['05'] = array("y"=>"May", "a" => 0);
        $data_tmp['06'] = array("y"=>"Jun", "a" => 0);
        $data_tmp['07'] = array("y"=>"Jul", "a" => 0);
        $data_tmp['08'] = array("y"=>"Aug", "a" => 0);
        $data_tmp['09'] = array("y"=>"Sep", "a" => 0);
        $data_tmp['10'] = array("y"=>"Oct", "a" => 0);
        $data_tmp['11'] = array("y"=>"Nov", "a" => 0);
        $data_tmp['12'] = array("y"=>"Dec", "a" => 0);
        
        $data = array();
        $ci = & get_instance();
        $ci->load->database();

        $ci->db->select('strftime("%Y", file_created_date) AS y, strftime("%m", file_created_date) AS m, COUNT(DISTINCT id) as total');
        $ci->db->from('file');
        $ci->db->group_by('y, m');
        //$ci->db->having("y = strftime('%Y', 'now')");
        $ci->db->order_by('y', 'desc');
        $ci->db->order_by('m', 'desc');
        $ci->db->limit(6);
        
        $query = $ci->db->get();
        $records = $query->result();
        //echo $ci->db->last_query();
        foreach ($records as $record):
            //echo $months[$record->m] . ' - ' . $record->total;
            //echo date("M", mktime(0, 0, 0, $record->m, 10)) . $record->m;
            $data[] = array("y" => date("M", mktime(0, 0, 0, $record->m, 10)) . '-' . $record->y, "a" => $record->total);
            $data_tmp[$record->m]['a'] = $record->total;
        endforeach;
        foreach ($data_tmp as $tmp):
            $dt[] = $tmp;
        endforeach;
        //print_r($dt);
        echo json_encode($data);
    }
    
    public function getMailChart() {        
        $data = array();
        $ci = & get_instance();
        $ci->load->database();

        $ci->db->select('strftime("%Y", (SUBSTR(date, 7) || "-"  || SUBSTR(date, 4, 2) || "-" || SUBSTR(date, 1,2))) AS y, strftime("%m", (SUBSTR(date, 7) || "-"  || SUBSTR(date, 4, 2) || "-" || SUBSTR(date, 1,2))) AS m, COUNT(DISTINCT id) as total');
        $ci->db->from('mails');
        $ci->db->group_by('y, m');
        //$ci->db->having("y = strftime('%Y', 'now')");
        $ci->db->order_by('y', 'desc');
        $ci->db->order_by('m', 'desc');
        $ci->db->limit(6);
        
        $query = $ci->db->get();
        $records = $query->result();
        //echo $ci->db->last_query();
        foreach ($records as $record):
            //echo $months[$record->m] . ' - ' . $record->total;
            $data[] = array("y" => date("M", mktime(0, 0, 0, $record->m, 10)) . '-' . $record->y, "a" => $record->total);
        endforeach;
        echo json_encode($data);
    }

}
