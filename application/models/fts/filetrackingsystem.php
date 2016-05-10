<?php //

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * User Model
 * This model serves to manage user data.
 */
//class FileTrackingSystem extends CI_Model {
//
//    public function __construct() {
//        parent::__construct();
//    }
//
//    public function add_file_data($data) {
//
//        $ins_q = "INSERT INTO file (`file_name`,`file_number`,`file_owner`,`file_part_number`,`file_subject`,`file_date_opened`,";
//        $ins_q .= "`file_date_closed`,`file_unit`,`file_classification`,`file_submitted_by`,`file_contact_email`,`file_contact_mobile`,";
//        $ins_q .= "`file_contact_landline`,`file_retention_in_months`,`file_reminder`,`file_category`,`file_tags`,`barcode_id`,";
//        $ins_q .= "`file_created_date`,`file_updated_date`,`file_updated_by`, `parent_division_id`, `parent_sub_division_id`,";
//        $ins_q .= "`volume`, `department`, `child_division_id`, `child_sub_division_id`) values(";
//        $ins_q .= "'{$data['file_name']}','{$data['file_number']}','{$data['file_owner']}','{$data['file_part_number']}',";
//        $ins_q .= "'{$data['file_subject']}','{$data['file_date_opened']}','{$data['file_date_closed']}','{$data['file_unit']}',";
//        $ins_q .= "'{$data['file_classification']}','{$data['file_submitted_by']}','{$data['file_contact_email']}',";
//        $ins_q .= "'{$data['file_contact_mobile']}','{$data['file_contact_landline']}','{$data['file_retention_in_months']}',";
//        $ins_q .= "'{$data['file_reminder']}','{$data['file_category']}','{$data['file_tags']}','{$data['barcode_id']}',";
//        $ins_q .= "DateTime('now'),DateTime('now'),'{$data['file_updated_by']}', ";
//        $ins_q .= "'{$data['division_id']}', '{$data['sub_division_id']}', '{$data['volume']}',";
//        $ins_q .= "'{$data['department']}', '{$data['division_id']}', '{$data['sub_division_id']}')";
//
//        $this->db->query($ins_q);
//
//        return $this->db->affected_rows() > 0;
//    }
//
//    public function check_file_number_exists($fileNumber) {
//        $this->db->get_where('file', array('file_number' => $fileNumber));
//
//        $count = $this->db->affected_rows();
//
//        if ($count === 0) {
//            return 'true';
//        } else if ($count > 0) {
//            return 'false';
//        }
//    }
//
//    public function get_file_recordset($limit, $offset) {
//
//        $this->db->select('file_number, barcode_id, file_created_date, '
//                . 'file_name, file_subject');
//        $this->db->from('file');
//        $this->db->join('users', 'users.id = file.file_owner');
//        $this->db->limit($limit, $offset);
//        $this->db->order_by('file_created_date', 'desc');
//        $query = $this->db->get();
//
//        return $query->result();
//    }
//
//    public function get_file_recordset_count() {
//
//        $this->db->from('file');
//        $this->db->join('users', 'users.id = file.file_owner');
//
//        return $this->db->count_all_results();
//    }
//
//    public function file_tracking($fileNumber) {
//        $this->db->select('division.division || "/" || subdivision.subdivision '
//                . 'AS parrent_location');
//        $this->db->from('file');
//        $this->db->join('division', 'division.id = file.parent_division_id');
//        $this->db->join('subdivision',
//                'subdivision.id = file.parent_sub_division_id');
//        $this->db->where('file.file_number', $fileNumber);
//        $query = $this->db->get();
//        
//        $resultParrent = $query->row_array();
//
//        $this->db->select('division.division || "/" || subdivision.subdivision '
//                . 'AS current_location');
//        $this->db->from('file');
//        $this->db->join('division', 'division.id = file.child_division_id');
//        $this->db->join('subdivision',
//                'subdivision.id = file.child_sub_division_id');
//        $this->db->where('file.file_number', $fileNumber);
//        $query1 = $this->db->get();
//        
//        $resultChild = $query1->row_array();
//                        
//        return $resultParrent + $resultChild;
//    }
//
//}
