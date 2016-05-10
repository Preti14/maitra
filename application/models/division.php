<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Campaign Select
 * This model serves to fetch campaign data.
 */
class Division extends CI_Model {

    function __construct() {
        parent::__construct();
        $ci = & get_instance();
    }

    function fetchdivision() {
        $query = $this->db->query('SELECT * FROM division WHERE active = 1 ORDER BY `order` ASC');
        $all_divs = $query->result_array();
        if ($query) {
            $i = 0;
            foreach ($all_divs as $row) {
                $query1 = $this->db->query('SELECT * FROM subdivision WHERE active = 1 and division_id = "' . $row['id'] . '" ORDER BY `order` ASC');
                $sub_division = $query1->result_array();
                $all_divs[$i]['subdivisions'] = $sub_division;
                $i++;
            }//echo "<pre>";print_r($all_divs); die;
            return $all_divs;
        } else {
            return false;
        }
    }

    function fetchsubdivision($div_id) {
        $query = $this->db->query('SELECT * FROM subdivision WHERE active = 1 and division_id = "' . $div_id . '" ORDER BY `order` ASC');
        $subdiv = $query->result();
        return json_encode($subdiv);
    }

    function subdiv_for_editform($subdiv_id) {
        $query = $this->db->query('SELECT * FROM subdivision WHERE active = 1 and id=' . $subdiv_id);
        $subdiv = $query->result();
        return json_encode($subdiv);
    }

    function fetch_division_list() {
        $newresult = $this->db->query("SELECT `id`,`division` FROM division WHERE active = 1 ORDER BY `order` ASC");
        $data = array();
        foreach ($newresult->result() as $row) {
            $data[] = array('id' => $row->id, 'division' => $row->division);
        }
        return $data;
    }

    function fetch_division_template($tempid) {
        $newresult = $this->db->query("SELECT * FROM search_template WHERE  id='" . $tempid . "' ORDER BY id ASC");
        $data = array();
        foreach ($newresult->result() as $row) {
            $data[] = array('id' => $row->id, 'type' => $row->type, 'subject' => $row->subject, 'from_date' => $row->from_date, 'to_date' => $row->to_date, 'division' => $row->division, 'template_name' => $row->template_name, 'subdivision' => $row->subdivision, 'comments' => $row->comments, 'status' => $row->status, 'copy_to' => $row->copy_to, 'mail_ref' => $row->mail_ref, 'from_mails' => $row->from_mails, 'to' => $row->to, 'with_comm' => $row->with_comm);
        }//echo "<pre>"; print_r($data); die;
        return $data;
    }

    function fetch_subdivision_list() {
        $newresult = $this->db->query("SELECT `id`,`subdivision` FROM subdivision WHERE active = 1 ORDER BY `order` ASC");
        $data = array();
        foreach ($newresult->result() as $row) {
            $data[] = array('id' => $row->id, 'subdivision' => $row->subdivision);
        }
        return $data;
    }

    function select_rel_div($div_id) {
        $result = $this->db->query('SELECT `id`,`subdivision`,`code` FROM subdivision WHERE `active` = 1 and `division_id` = ' . $div_id);
        $data = array();
        foreach ($result->result() as $row) {
            $data[] = array('id' => $row->id, 'subdivision' => $row->subdivision, 'code' => $row->code);
        }
        return $data;
    }

    function division_add($data) {
        if ($data['divid'] == '') {
            $result = $this->db->query("INSERT INTO division ('division', 'code', 'active') VALUES ('{$data['division']}', '{$data['code']}', 1)");
        } else {
            $result = $this->db->query("UPDATE division SET division = '{$data['division']}',
			code = '{$data['code']}',
			active=1 WHERE id=" . $data['divid']);
        }
        if ($result)
            return true;
        else
            return false;
    }

    function division_details($div_id) {
        $query = $this->db->query('SELECT * FROM division WHERE id=' . $div_id);
        $div = $query->row_array();
        return $div;
    }

    function delete_division($divid) {
        $delete = $this->db->query('UPDATE division SET active = 0 WHERE id=' . $divid);
        if ($delete)
            echo true;
        else
            echo false;
    }

    function get_allsubdiv() {
        $query = $this->db->query('SELECT sd.*, d.division FROM subdivision sd 
		JOIN division d ON d.id = sd.division_id
		WHERE sd.active = 1');
        $all_subdivs = $query->result_array();
        return $all_subdivs;
    }

    function subdivision_details($subdiv_id) {
        $query = $this->db->query('SELECT sd.*, d.division FROM subdivision sd
		LEFT JOIN division d ON d.id = sd.division_id
		WHERE sd.id=' . $subdiv_id);
        $subdiv = $query->row_array();
        return $subdiv;
    }

    function subdivision_add($data) {
        //echo "<pre>";print_r($data); die;
        if ($data['subdivid'] == '') {
            $result = $this->db->query("INSERT INTO subdivision ('subdivision', 'code', 'active', 'division_id') VALUES ('{$data['subdivision']}', '{$data['code']}', 1, {$data['division_id']})");
        } else {
            $result = $this->db->query("UPDATE subdivision SET subdivision = '{$data['subdivision']}',
			code = '{$data['code']}',
			division_id = {$data['division_id']},
			active=1 WHERE id=" . $data['subdivid']);
        }
        if ($result)
            return true;
        else
            return false;
    }

    function delete_subdivision($subdivid) {
        $delete = $this->db->query('UPDATE subdivision SET active = 0 WHERE id=' . $subdivid);
        if ($delete)
            echo true;
        else
            echo false;
    }

}
