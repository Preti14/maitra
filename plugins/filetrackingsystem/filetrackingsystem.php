<?php

/**
 * Plugin Name: File Tracking System
 * Version: 1.0
 * Description: Manage offixe files and maintains history
 * Author: Jaiganesh
 */
define('PLUGIN_VIEW_PATH', 'plugins/filetrackingsystem/views/');
define('DB_NOW', 'CURRENT_TIMESTAMP');

add_action('test.plugin', 'test_file_tracking', 10);
add_action('render.plugin', 'file_tracking_setting', 10);
add_action('checkststus.plugin', 'file_tracking_check_status', 10);
add_action('activate.plugin', 'file_tracking_activate', 10);
add_action('deactivate.plugin', 'file_tracking_deactivate', 10);
add_action('add_file_data.plugin', 'add_file_data');
add_action('get_file_recordset.plugin', 'get_file_recordset');
add_action('get_file_recordset_count.plugin', 'get_file_recordset_count');
add_action('file_tracking.plugin', 'file_tracking');
add_action('file_cover_page.plugin', 'file_cover_page');
add_action('checkin_out.plugin', 'checkin_out');
add_action('get_file_details.plugin', 'get_file_details');
add_action('get_barcode.plugin', 'get_barcode');
add_action('advanced_search.plugin', 'advanced_search');
add_action('advanced_search_count.plugin', 'advanced_search_count');
add_action('global_search.plugin', 'global_search');
add_action('set_file_status.plugin', 'set_file_status');
add_action('list_all_files.plugin', 'list_all_files');
add_action('list_all_files_count.plugin', 'list_all_files_count');
add_action('file_detail.plugin', 'file_detail');
add_action('edit_file_data.plugin', 'edit_file_data');
add_action('delete_file.plugin', 'delete_file');
add_action('get_max_file_id.plugin', 'get_max_file_id');
add_action('update_file_record_status.plugin', 'update_file_record_status');
add_action('get_record_manager_status.plugin', 'get_record_manager_status');
add_action('ajax_file_tracking.plugin', 'ajax_file_tracking');
add_action('ajax_file_tracking_count.plugin', 'ajax_file_tracking_count');
add_action('soft_delete_file.plugin', 'soft_delete_file');
add_action('save_search_template.plugin', 'save_search_template');
add_action('list_all_search_template_name.plugin',
        'list_all_search_template_name');
add_action('load_template_options.plugin', 'load_template_options');
add_action('get_transit_purpose.plugin', 'get_transit_purpose');
add_action('delete_template.plugin', 'delete_template');

function test_file_tracking($data) {

    return plugin_template_view(PLUGIN_VIEW_PATH . 'settings', $data);
}

function get_max_file_id() {

    $ci = & get_instance();
    $ci->load->database();

    $max_q = "select max(id) as max_id from file";
    $query = $ci->db->query($max_q);

    $result = $query->result();

    return $result[0]->max_id;
}

function add_file_data($data) {
    $ci = & get_instance();
    $ci->load->database();

    if (isset($data['insert_input'])) {
        $inserData = $data['insert_input'];

        $ins_q = "INSERT INTO file (`file_name`,`file_number`,`file_owner`,`file_part_number`,`file_subject`,`file_date_opened`,";
        $ins_q .= "`file_unit`,`file_contact_email`,`file_contact_mobile`,";
        $ins_q .= "`file_contact_landline`,`file_category`,`file_tags`,`barcode_id`,";
        $ins_q .= "`file_created_date`,`file_updated_date`,`file_updated_by`, `parent_division_id`, `parent_sub_division_id`,";
        $ins_q .= "`volume`, `department`, `child_division_id`, `child_sub_division_id`, "
                . "`file_department_hindi`, `file_subject_hindi`, "
                . "`file_date_closed`, `record_manager_status_id`, `file_additional_contact_email`, `office_purpose`) values(";
        $ins_q .= "'{$inserData['file_name']}','{$inserData['file_number']}','{$inserData['file_owner']}','{$inserData['file_part_number']}',";
        $ins_q .= "'{$inserData['file_subject']}','{$inserData['file_date_opened']}','{$inserData['file_unit']}',";
        $ins_q .= "'{$inserData['file_contact_email']}',";
        $ins_q .= "'{$inserData['file_contact_mobile']}','{$inserData['file_contact_landline']}',";
        $ins_q .= "'{$inserData['file_category']}','{$inserData['file_tags']}','{$inserData['barcode_id']}',";
        $ins_q .= "DateTime('now'),DateTime('now'),'{$inserData['file_updated_by']}', ";
        $ins_q .= "'{$inserData['division_id']}', '{$inserData['sub_division_id']}', '{$inserData['volume']}',";
        $ins_q .= "'{$inserData['department']}', '{$inserData['division_id']}', '{$inserData['sub_division_id']}',"
                . " '{$inserData['file_department_hindi']}', '{$inserData['file_subject_hindi']}', "
                . " '{$inserData['file_date_closed']}', 1, "
                . " '{$inserData['file_additional_contact_email']}', '{$inserData['office_purpose']}')";

        $ci->db->query($ins_q);
        $insID = $ci->db->insert_id();
        $divID = $inserData['division_id'];
        $subDivID = $inserData['sub_division_id'];
        $userID = $inserData['file_owner'];
        $ins_q = "insert into file_tracking(file_id, parent_division, parent_sub_division, curent_division, current_sub_division,";
        $ins_q .= "create_by, create_date,status)";
        $ins_q .= " values('$insID','$divID','$subDivID','$divID',";
        $ins_q .= "'$subDivID','$userID',DateTime('now'),'1')";

        $ci->db->query($ins_q);

        return array('last_insert_file_id' => $insID);
    }

//    return $this->db->affected_rows() > 0;
    return plugin_template_view('plugins/filetrackingsystem/views/add_file_data',
            $data);
}

function get_file_recordset($data) {
    $ci = & get_instance();
    $ci->load->database();

    $limit = $data[0];
    $offset = $data[1];
    $file_no = $data[2];
    $sortby = $data[3];
    $order = $data[4];

    $ci->db->select('`file`.`id`, file_number, barcode_id, file_created_date, '
            . ' file_name, file_subject, comment, status_name, '
            . ' file.record_manager_status_id');
    $ci->db->from('file');
    $ci->db->join('users', 'users.id = file.file_owner', 'left');
    $ci->db->join('record_manager_status',
            'file.record_manager_status_id = record_manager_status.id', 'left');
    $ci->db->join('record_status_comment',
            'file_id = file.id 
		AND  
	record_status_comment.created_date IN 
        (SELECT max(created_date) FROM record_status_comment  GROUP BY file_id)',
            'left', FALSE);
    if ($file_no != '') {
        $ci->db->like('file_number', $file_no, 'after');
    }

    $ci->db->where('is_delete', '0');

    $ci->db->limit($limit, $offset);
    //$ci->db->order_by('file_created_date', 'desc');
    $ci->db->order_by($sortby, $order);
    $query = $ci->db->get();

    return $query->result();
}

function get_file_recordset_count($data) {
    $ci = & get_instance();
    $ci->load->database();

    $file_no = $data[0];

    $ci->db->from('file');
    $ci->db->join('users', 'users.id = file.file_owner', 'left');

    if ($file_no != '') {
        $ci->db->like('file_number', $file_no, 'after');
    }

    $ci->db->where('is_delete', '0');

    return array($ci->db->count_all_results());
}

function file_tracking($fileID) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->select('`file`.`file_name`, "VOL-" || `file`.`volume` as "volume",'
            . '`file`.`file_part_number` as "pc"');
    $ci->db->from('file');

    $ci->db->where('`file`.`id`', $fileID);

    $query = $ci->db->get();

    $fileHeading = $query->row_array();

    if (strlen($fileHeading['pc']) !== 0) {
        $fileHeading['pc'] = 'PC-' . $fileHeading['pc'];
    }

    $fileHeadingStr = 'File Name: ' . implode(' ', $fileHeading);

    $ci->db->select('division.division || ifnull("/" ||subdivision.subdivision, "") '
            . ' AS current_location, '
            . ' create_date AS created_date, '
            . ' CASE WHEN file_tracking.status = 1 '
            . ' THEN "Check In" '
            . ' WHEN file_tracking.status = 0 '
            . ' THEN "Check Out" END AS status');
    $ci->db->from('file_tracking');
    $ci->db->join('division', 'division.id = file_tracking.curent_division',
            'left');
    $ci->db->join('subdivision',
            'subdivision.id = file_tracking.current_sub_division', 'left');
    $ci->db->where('file_id', $fileID);
    $ci->db->order_by("created_date", "desc");
    $ci->db->order_by("file_tracking.id", "desc");
    $query1 = $ci->db->get();

    $trackingDetails = $query1->result();

    foreach ($trackingDetails as $key => $singleRow) {
        $trackingDetails[$key]->created_date = date('d-m-y',
                strtotime($singleRow->created_date));
    }

    $fileTrack = array('file_heading' => $fileHeadingStr,
        'file_tracking_details' => $trackingDetails);

    return $fileTrack;
}

function ajax_file_tracking($data) {
    $ci = & get_instance();
    $ci->load->database();

    $fileID = $data[0];
    $limit = $data[1];
    $offset = $data[2];

    $ci->db->select('`file`.`file_name`, "VOL-" || `file`.`volume` as "volume",'
            . '`file`.`file_part_number` as "pc"');
    $ci->db->from('file');

    $ci->db->where('`file`.`id`', $fileID);

    $query = $ci->db->get();

    $fileHeading = $query->row_array();

    if (strlen($fileHeading['pc']) !== 0) {
        $fileHeading['pc'] = 'PC-' . $fileHeading['pc'];
    }

    $fileHeadingStr = 'File Name: ' . implode(' ', $fileHeading);

    $ci->db->select('transit_purpose.purpose, division.division || ifnull("/" ||subdivision.subdivision, "") '
            . ' AS current_location, '
            . ' create_date AS created_date, '
            . ' CASE WHEN file_tracking.status = 1 '
            . ' THEN "Check In" '
            . ' WHEN file_tracking.status = 0 '
            . ' THEN "Check Out" END AS status');
    $ci->db->from('file_tracking');
    $ci->db->join('division', 'division.id = file_tracking.curent_division',
            'left');
    $ci->db->join('subdivision',
            'subdivision.id = file_tracking.current_sub_division', 'left');
    $ci->db->join('transit_purpose',
            'transit_purpose.id = file_tracking.transit_purpose_id', 'left');
    $ci->db->where('file_id', $fileID);
    $ci->db->limit($limit, $offset);
    $ci->db->order_by("created_date", "desc");
    $ci->db->order_by("file_tracking.id", "desc");
    $query1 = $ci->db->get();

    $trackingDetails = $query1->result();

    $fileTrack = array('file_heading' => $fileHeadingStr,
        'file_tracking_details' => $trackingDetails);

    return $fileTrack;
}

function ajax_file_tracking_count($fileId) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->select('*');
    $ci->db->from('file_tracking');

    $ci->db->where('file_id', $fileId);

    $query = $ci->db->get();

    return $query->num_rows();
}

function plugin_template_view($view, $data) {
    $ci = & get_instance();
    return $data["content"] = $ci->load->ext_view($view, $data, true);
}

function file_tracking_setting() {
    $ci = & get_instance();
    $ci->load->database();
    $query = $ci->db->query("select * from plugins");
    $result = $query->row_array();
    $data["plugins_list"] = $result;
    if ($query->num_rows() > 0) {
        return plugin_template_view('plugins/filetrackingsystem/views/settings',
                $data);
    }
}

function file_tracking_check_status() {
    $ci = & get_instance();
    $ci->load->database();
    $query = $ci->db->query("select * from plugins");
    $result = $query->result_array();
    if ($query->num_rows() > 0) {
        return $result[0]["active"];
    }
}

function file_tracking_activate() {
    $ci = & get_instance();
    $ci->load->database();
    $query = $ci->db->query("UPDATE plugins set active = 1");

    return true;
}

function file_tracking_deactivate() {
    $ci = & get_instance();
    $ci->load->database();
    $sql = "UPDATE plugins set active = 0";

    $query = $ci->db->query($sql);

    return true;
}

function file_cover_page($fileNumber) {
    $ci = & get_instance();
    $ci->load->database();

    //$ci->db->select('file.id as file_pk, *'  );//incase if you need file id uncomment it.
    $ci->db->from('file');
    $ci->db->join('division', 'division.id = file.child_division_id', 'left');
    $ci->db->join('subdivision', 'subdivision.id = file.child_sub_division_id',
            'left');
    $ci->db->where('file.id', $fileNumber);
    $query = $ci->db->get();

    return $result = $query->row_array();
//    return plugin_template_view('plugins/filetrackingsystem/views/file_cover_page',
//            $result);
}

function set_file_status($data) {
    $ci = & get_instance();
    $ci->load->database();

    $userID = $data['user_id'];
    $fileID = $data['file_id'];
    $transit_purpose_id = $data['transit_purpose_id'];

    $statusField = 1;
    $currentStatusField = 0;

    $status = ($data['status'] != 1) ? "0" : "1";

    if ($status == 1) {
        $statusField = 0;
        $currentStatusField = 1;
    }

    $curDiv = get_divisionDetails($userID);
    $update_q = "update file set child_division_id = '$curDiv->division_id', "
            . " child_sub_division_id = '$curDiv->subdivision_id', "
            . " current_status = '$currentStatusField' "
            . " where id = '$fileID'";

    $userLastTrackingStatusSql = "SELECT status, curent_division, current_sub_division 
        FROM (SELECT * FROM file_tracking  ft 
JOIN file  f ON f.id = ft.file_id
WHERE is_delete = '0' 
AND ft.file_id = '{$data['file_id']}' 
 ORDER BY ft.create_date DESC LIMIT 1) as temp WHERE status = 1;";
    $userLastTrackingStatusQuery = $ci->db->query($userLastTrackingStatusSql);
    $userLastTrackingStatusResult = $userLastTrackingStatusQuery->row();

    $insertCheckOutPrevFileCondition = $userLastTrackingStatusResult->status == 1 &&
            $userLastTrackingStatusResult->curent_division != $curDiv->division_id;
    if ($curDiv->subdivision_id != 0) {
        $insertCheckOutPrevFileCondition &=
                $userLastTrackingStatusResult->current_sub_division != $curDiv->subdivision_id;
    }

    $ci->db->query($update_q);

    $dateTimeNow = 'CURRENT_TIMESTAMP';
//    $fTracknID = getPrevFileTrackingID($fileID);
    $parDiv = get_parentDivisionDetails($fileID);
    if ($insertCheckOutPrevFileCondition) {
        $insertSQL = "INSERT INTO file_tracking (file_id, parent_division, "
                . " parent_sub_division, curent_division, current_sub_division, "
                . " create_by, status, create_date, transit_purpose_id) "
                . " SELECT file_id, parent_division, parent_sub_division, "
                . " curent_division, current_sub_division, create_by, "
                . " 0, $dateTimeNow " . ", transit_purpose_id "
                . " from file_tracking "
                . " where file_id = '$fileID' order by id desc limit 1 ";
        $ci->db->query($insertSQL);
    }

    $insertData = array(
        'file_id' => $fileID,
        'parent_division' => $parDiv->parent_division_id,
        'parent_sub_division' => $parDiv->parent_sub_division_id,
        'curent_division' => $curDiv->division_id,
        'current_sub_division' => $curDiv->subdivision_id,
        'create_by' => $userID,
        'status' => $currentStatusField,
        'transit_purpose_id' => $transit_purpose_id
    );
    $ci->db->set('create_date', $dateTimeNow, FALSE);


    $isClose = checkTrackingClose($curDiv->division_id, $curDiv->subdivision_id,
            $fileID);
    if ($status == 1 && $isClose == 1) {
        $ci->db->set('closed_date', $dateTimeNow, FALSE);
    }

    $ci->db->insert('file_tracking', $insertData);

    return true;
}

function getPrevFileTrackingID($fileID) {

    $ci = & get_instance();
    $ci->load->database();

    $sql = "select id, status from file_tracking where file_id = '$fileID' order by id desc limit 1";

    $query = $ci->db->query($sql);

    $result = $query->result();
    if (is_array($result) && count($result) > 0) {
        $resObj = $result[0];
        $retVal = array("status" => $resObj->status, "id" => $resObj->id);
    } else {

        $retVal = array("status" => "", "id" => "");
    }
    return $retVal;
}

function checkTrackingClose($div, $subDiv, $fileID) {

    $ci = & get_instance();
    $ci->load->database();

    $sql = "select id from file where parent_division_id = '$div' and parent_sub_division_id = '$subDiv' and id = '$fileID'";
    $query = $ci->db->query($sql);

    $result = $query->num_rows();

    if ($result >= 1) {
        return 1;
    }
}

function checkin_out() {
    $data = '';
    $data['transit_purposes'] = do_action('get_transit_purpose.plugin');

    return plugin_template_view('plugins/filetrackingsystem/views/checkin_out',
            $data);
}

function get_file_details($data) {

    $ci = & get_instance();
    $ci->load->database();

    $barcodeID = $data['barcode_id'];
    $userID = $data['user_id'];

    $sql = "SELECT f.id as file_id, * , d.division , u.username FROM file as f
	JOIN users as u ON u.id = f.file_owner
	JOIN division as d ON d.id = f.parent_division_id
        LEFT JOIN subdivision as s_div ON s_div.id = f.parent_sub_division_id
	 WHERE is_delete = '0' AND (f.barcode_id = '$barcodeID' OR f.barcode_id LIKE '$barcodeID-_%')";

    $query = $ci->db->query($sql);


    $result = $query->result();

    $userObj = get_divisionDetails($userID);

    $fileOutIn = "";
    $respVal = '';
    foreach ($result as $resObj) {

        $checkInOutFlipFlopSql = "SELECT ft.status FROM file_tracking  ft 
JOIN file  f ON f.id = ft.file_id
WHERE is_delete = '0' 
AND (barcode_id = '$barcodeID' OR barcode_id LIKE '$barcodeID-_%') 
AND ft.curent_division = $userObj->division_id 
AND CASE WHEN ft.current_sub_division = ''  THEN  ft.current_sub_division = ''
ELSE ft.current_sub_division = $userObj->subdivision_id END "
                . " ORDER BY ft.create_date DESC LIMIT 1;";

        $checkInOutFlipFlopQuery = $ci->db->query($checkInOutFlipFlopSql);
        $checkInOutFlipFlopResult = $checkInOutFlipFlopQuery->row();
        
        $fileOutIn = "1";
        if (isset($checkInOutFlipFlopResult->status)) {
            if ($checkInOutFlipFlopResult->status == 0) {
                $fileOutIn = "1";
            } else if ($checkInOutFlipFlopResult->status == 1) {
                $fileOutIn = "";
            }
        }
//        $fileOutIn = 1;
//        if ($resObj->current_status) {
//            $fileOutIn = 0;
//        }
//        if (empty($resObj->child_subdivision_id) && empty($userObj->subdivision_id)) {
//
//            if ($resObj->child_division_id == $userObj->division_id) {
//
//                $fileOutIn = "2";
//            } else {
//
//                $fileOutIn = "1";
//            }
//        } else {
//            if (($resObj->child_division_id == $userObj->division_id) && ($resObj->child_sub_division_id == $userObj->subdivision_id)) {
//
//                $fileOutIn = "2";
//            } else {
//
//                $fileOutIn = "1";
//            }
//        }
        $transitPurpose = 0;
        if ($resObj->parent_division_id == $userObj->division_id) {
            $transitPurpose = 1;
        }

        $respVal = array("file_id" => $resObj->file_id,
            "file_name" => $resObj->file_name,
            "file_no" => $resObj->file_number,
            "file_vol" => $resObj->volume,
            "check_status" => $fileOutIn,
            "transit_purpose" => $transitPurpose,
            "created_date" => date('d-m-y',
                    strtotime($resObj->file_created_date)),
            "file_user" => $resObj->username,
            "file_division" => $resObj->division,
            "file_sub_division" => $resObj->subdivision,
            "file_contact_email" => $resObj->file_contact_email);
    }
    return $respVal;
}

function get_divisionDetails($userID) {

    $ci = & get_instance();
    $ci->load->database();

    $sql = "select division_id, subdivision_id from users where id = '$userID'";

    $query = $ci->db->query($sql);

    $result = $query->result();

    return $result[0];
}

function get_parentDivisionDetails($fileID) {

    $ci = & get_instance();
    $ci->load->database();

    $sql = "select parent_division_id, parent_sub_division_id from file where id = '$fileID'";

    $query = $ci->db->query($sql);

    $result = $query->result();

    return $result[0];
}

function get_barcode($fileNumber) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->select('barcode_id');

    $query = $ci->db->get_where('file', array('id' => $fileNumber));

    return $query->row();
}

function advanced_search($data) {
    $searchInput = _format_advanced_search_input($data['search_input']);
    $limit = $data['limit'];
    $offset = $data['offset'];

    $ci = & get_instance();
    $ci->load->database();

    $ci->db->select('file.id as file_pk,record_manager_status.id as rms_id, *');
    $ci->db->from('file');
    $ci->db->join('division', 'parent_division_id = division.id', 'left');
    $ci->db->join('subdivision', 'parent_sub_division_id = subdivision.id',
            'left');
    $ci->db->join('record_manager_status',
            'file.record_manager_status_id = record_manager_status.id', 'left');
    $ci->db->join('record_status_comment',
            'file_id = file.id 
		AND  
	record_status_comment.created_date IN 
        (SELECT max(created_date) FROM record_status_comment  GROUP BY file_id)',
            'left', FALSE);

    if (!empty($searchInput['division'])) {
        $ci->db->like('parent_division_id', $searchInput['division']);
    }

    if (!empty($searchInput['subdivision'])) {
        $ci->db->like('parent_sub_division_id', $searchInput['subdivision']);
    }

    if (!empty($searchInput['search_by'])) {
        foreach ($searchInput['search_by'] as $key => $value) {
            foreach ($value as $scalarTxt) {
                $ci->db->like($key, $scalarTxt);
            }
        }
    }

    if (!empty($searchInput['from_datepicker']) && !empty($searchInput['to_datepicker'])) {
        $fromDBFormat = date('Y-m-d H:i:s',
                strtotime($searchInput['from_datepicker']));
        $toDBFormat = date('Y-m-d', strtotime($searchInput['to_datepicker'])) . ' 23:59:59';

        $ci->db->where('file_created_date >=', $fromDBFormat);
        $ci->db->where('file_created_date <=', $toDBFormat);
    } else if (!empty($searchInput['from_datepicker'])) {
        $fromDBFormat = date('Y-m-d H:i:s',
                strtotime($searchInput['from_datepicker']));

        $ci->db->where('file_created_date >=', $fromDBFormat);
    } else if (!empty($searchInput['to_datepicker'])) {
        $toDBFormat = date('Y-m-d', strtotime($searchInput['to_datepicker'])) . ' 23:59:59';

        $ci->db->where('file_created_date <=', $toDBFormat);
    }

    if (empty($searchInput['deleted_file'])) {
        $ci->db->where('file.is_delete', 0);
    }

    //$ci->db->order_by('file_created_date', 'desc');
    $ci->db->order_by($data['sortby'], $data['order']);
    $ci->db->limit($limit, $offset);
    $query = $ci->db->get();

    $data['result_records'] = $query->result();

    //RECORD MANAGER STATUS
    if ($data['logintype'] == USER_TYPE_RECORD_MANAGER) {
        $data['record_manager_status'] = get_record_manager_status();
    }

    return plugin_template_view('plugins/filetrackingsystem/views/advanced_search',
            $data);
}

function advanced_search_count($localData) {
    $searchInput = _format_advanced_search_input($localData['search_input']);

    $ci = & get_instance();
    $ci->load->database();

    $ci->db->from('file');
    $ci->db->join('division', 'parent_division_id = division.id', 'left');
    $ci->db->join('subdivision', 'parent_sub_division_id = subdivision.id',
            'left');

    if (!empty($searchInput['division'])) {
        $ci->db->like('parent_division_id', $searchInput['division']);
    }

    if (!empty($searchInput['subdivision'])) {
        $ci->db->like('parent_sub_division_id', $searchInput['subdivision']);
    }

    if (!empty($searchInput['search_by'])) {
        foreach ($searchInput['search_by'] as $key => $value) {
            foreach ($value as $scalarTxt) {
                $ci->db->like($key, $scalarTxt);
            }
        }
    }

    if (!empty($searchInput['from_datepicker']) && !empty($searchInput['to_datepicker'])) {
        $fromDBFormat = date('Y-m-d H:i:s',
                strtotime($searchInput['from_datepicker']));
        $toDBFormat = date('Y-m-d', strtotime($searchInput['to_datepicker'])) . ' 23:59:59';

        $ci->db->where('file_created_date >=', $fromDBFormat);
        $ci->db->where('file_created_date <=', $toDBFormat);
    } else if (!empty($searchInput['from_datepicker'])) {
        $fromDBFormat = date('Y-m-d H:i:s',
                strtotime($searchInput['from_datepicker']));

        $ci->db->where('file_created_date >=', $fromDBFormat);
    } else if (!empty($searchInput['to_datepicker'])) {
        $toDBFormat = date('Y-m-d', strtotime($searchInput['to_datepicker'])) . ' 23:59:59';

        $ci->db->where('file_created_date <=', $toDBFormat);
    }

    if (empty($searchInput['deleted_file'])) {
        $ci->db->where('file.is_delete', 0);
    }

    return array('count' => $ci->db->count_all_results());
}

function global_search($data) {
    $searchString = $data['search_input'];

    $tableName = 'file';
    $fileColumns = get_table_columns($tableName);
    $orWhereString = global_column_or_like($tableName, $fileColumns,
            $searchString);

    $limitAndOfsset = global_search_count($data);
    $data['offset'] = $limitAndOfsset['offset'];
    $data['limit'] = $limitAndOfsset['limit'];
    $data['count'] = $limitAndOfsset['count'];

    //START: DB WORKS
    $sql = "select file.id as file_pk,record_manager_status.id as rms_id, * from file "
            . " LEFT JOIN division ON parent_division_id = division.id "
            . " LEFT JOIN subdivision ON parent_sub_division_id = subdivision.id "
            . " LEFT JOIN record_manager_status "
            . "     ON file.record_manager_status_id = record_manager_status.id "
            . " LEFT JOIN record_status_comment ON file_id = file.id 
                    AND  
                record_status_comment.created_date IN 
                    (SELECT max(created_date) FROM record_status_comment  GROUP BY file_id) "
            . " WHERE ($orWhereString) AND is_delete = 0"
            . " ORDER BY {$data['sortby']} {$data['order']} "
            . " LIMIT {$data['offset']}, {$data['limit']}";

    $ci = & get_instance();
    $ci->load->database();
    $query = $ci->db->query($sql);
    $data['result_records'] = $query->result();

    //END: DB WORKS
    //RECORD MANAGER STATUS
    if ($data['logintype'] == USER_TYPE_RECORD_MANAGER) {
        $data['record_manager_status'] = get_record_manager_status();
    }

    return plugin_template_view('plugins/filetrackingsystem/views/advanced_search',
            $data);
}

function global_column_or_like($tableName, $columnArray, $searchString) {

    $orLikeString = '';
    foreach ($columnArray as $individualColumn) {
        $orLikeString .= " `$tableName`.$individualColumn LIKE '%$searchString%' OR ";
    }

    //TRUNCATE LAST OR
    $orLikeStringTruncated = substr($orLikeString, 0, -3);

    return $orLikeStringTruncated;
}

/**
 *
 * UTILITY
 */
function get_table_columns($tableName, $unwantedColumns = array('PRIMARY')) {
    $ci = & get_instance();
    $ci->load->database();
    $query = $ci->db->query("SELECT sql FROM sqlite_master WHERE tbl_name = '$tableName'");

    $result = $query->result();

    $sql = $result[0]->sql;
    $colnames = array();

    //GET COLUMN NAME OF A TABLE
    $r = preg_match("/\(\s*(\S+)[^,)]*/", $sql, $m, PREG_OFFSET_CAPTURE);
    while ($r) {
        array_push($colnames, $m[1][0]);
        $r = preg_match("/,\s*(\S+)[^,)]*/", $sql, $m, PREG_OFFSET_CAPTURE,
                $m[0][1] + strlen($m[0][0]));
    }
    //END: GET COLUMN NAME OF A TABLE
    //var_dump($colnames);//This is intentionally leaved to check what are the columns arrived.
    //REMOVE UNWANTED COLUMNS FROM COLUMN NAMES
    $necessaryColumns = array_diff($colnames, $unwantedColumns);

    return $necessaryColumns;
}

function global_search_count($data) {
    $searchString = $data['search_input'];

    $tableName = 'file';
    $fileColumns = get_table_columns($tableName);
    $orWhereString = global_column_or_like($tableName, $fileColumns,
            $searchString);

    //START: DB WORKS
    $sql = "select count(*) as count from file "
            . " LEFT JOIN division ON parent_division_id = division.id "
            . " LEFT JOIN subdivision ON parent_sub_division_id = subdivision.id "
            . " WHERE ($orWhereString) AND is_delete = 0";

    $ci = & get_instance();
    $ci->load->database();
    $query = $ci->db->query($sql);
    $result = $query->row();

    $count = $result->count;

    $sort = '&sortby=' . $data['sortby'] . '&order=' . $data['order'];

    //PAGINATION
    $limit = $data['limit'];
    $config['base_url'] = site_url() . '?c=' . $ci->router->class . '&m=' . $ci->router->method . $sort;
    $config['total_rows'] = $count;
    $config['per_page'] = $limit;

    $ci->pagination->initialize($config);

    //LIMIT OFFSET
    $pagination['count'] = $count;
    $pagination['limit'] = $limit;
    $pagination['offset'] = (int) $ci->input->get('per_page');

    return $pagination;
}

function list_all_files($data) {

    $limit = $data['limit'];
    $offset = $data['offset'];
    $division = $data['divisionid'];
    $subdivision = $data['subdivisionid'];
    $logintype = $data['logintype'];
    $fstatus = $data['fstatus'];
    $fstatusTwo = $data['fStatusTwo'];

    $ci = & get_instance();
    $ci->load->database();

    $ci->db->select('file.id as file_pk,record_manager_status.id as rms_id, *');
    $ci->db->from('file');
    $ci->db->join('division', 'parent_division_id = division.id', 'left');
    $ci->db->join('subdivision', 'parent_sub_division_id = subdivision.id',
            'left');
    $ci->db->join('record_manager_status',
            'file.record_manager_status_id = record_manager_status.id', 'left');
    $ci->db->join('record_status_comment',
            'file_id = file.id 
		AND  
	record_status_comment.created_date IN 
        (SELECT max(created_date) FROM record_status_comment  GROUP BY file_id)',
            'left', FALSE);
    if ($fstatus == "active") {
        $ci->db->where('file.file_date_closed ==', '');
    } else if ($fstatus == "archive") {
        $ci->db->where('file.file_date_closed !=', '');
    }

    if ($fstatusTwo == "transit") {
        $ci->db->where(
                "NOT(
                    file.parent_division_id = file.child_division_id 
                        AND  
                    (
                        file.parent_sub_division_id = file.child_sub_division_id 
                            OR  
                        (file.parent_sub_division_id = '' AND  file.child_sub_division_id = 0)
                    )
                )"
        );
    }

    if (($logintype == 3 || $logintype == 4) || !empty($data['get_flag'])) {
        //$array = array('file.parent_division_id' => $division, 'file.parent_sub_division_id' => $subdivision);
        $array['file.parent_division_id'] = $division;
        if ($subdivision != NULL) {
            $array['file.parent_sub_division_id'] = $subdivision;
        }

        $ci->db->where($array);
    }

    if ($logintype != USER_TYPE_RECORD_MANAGER && $logintype != USER_TYPE_ADMIN) {
        $ci->db->where('barcode_id !=', '');
    }
    $ci->db->where('is_delete', '0');

    //$ci->db->order_by('file_created_date', 'desc');
    $ci->db->order_by($data['sortby'], $data['order']);
    $ci->db->limit($limit, $offset);
    $query = $ci->db->get();

    $data['result_records'] = $query->result();

    //RECORD MANAGER STATUS
    if ($logintype == USER_TYPE_RECORD_MANAGER) {
        $data['record_manager_status'] = get_record_manager_status();
    }

    return plugin_template_view('plugins/filetrackingsystem/views/list_all_files',
            $data);
}

function list_all_files_count($data) {

    $division = $data['divisionid'];
    $subdivision = $data['subdivisionid'];
    $logintype = $data['logintype'];
    $fstatus = $data['fstatus'];
    $fstatusTwo = $data['fStatusTwo'];

    $ci = & get_instance();
    $ci->load->database();

    $ci->db->from('file');
    $ci->db->join('division', 'parent_division_id = division.id', 'left');
    $ci->db->join('subdivision', 'parent_sub_division_id = subdivision.id',
            'left');
    if ($fstatus == "active") {
        $ci->db->where('file.file_date_closed ==', '');
    } else if ($fstatus == "archive") {
        $ci->db->where('file.file_date_closed !=', '');
    }

    if ($fstatusTwo == "transit") {
        $ci->db->where(
                "NOT(
                    file.parent_division_id = file.child_division_id 
                        AND  
                    (
                        file.parent_sub_division_id = file.child_sub_division_id 
                            OR  
                        (file.parent_sub_division_id = '' AND  file.child_sub_division_id = 0)
                    )
                )"
        );
    } else if ($fstatus == "anomaly") {
        $ci->db->where("file.current_status = 0");
    }
    if (($logintype == 3 || $logintype == 4) || !empty($data['get_flag'])) {
        $array['file.parent_division_id'] = $division;
        if ($subdivision != NULL) {
            $array['file.parent_sub_division_id'] = $subdivision;
        }

        $ci->db->where($array);
    }
    if ($logintype != USER_TYPE_RECORD_MANAGER && $logintype != USER_TYPE_ADMIN) {
        $ci->db->where('barcode_id !=', '');
    }
    $ci->db->where('is_delete', '0');

    return array('count' => $ci->db->count_all_results());
}

function file_detail($fileID) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->select('file.id as file_pk, *');
    $ci->db->from('file');
    $ci->db->join('division', 'parent_division_id = division.id', 'left');
    $ci->db->join('subdivision', 'parent_sub_division_id = subdivision.id',
            'left');
    $ci->db->where('`file`.`id`', $fileID);

    $query = $ci->db->get();

    return $query->row();
}

function edit_file_data($fileData) {
    $ci = & get_instance();
    $ci->load->database();

    $updateData = $fileData['update_input'];
    $updateData['file_updated_date'] = "DateTime('now')";

    $ci->db->where('`file`.`id`', $fileData['condition']['id']);
    $ci->db->update('`file`', $updateData);

    return TRUE;
}

function delete_file($fileID) {
    $success = 'true';
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->trans_start();
    $ci->db->delete('file', array('id' => $fileID));
    $ci->db->delete('file_tracking', array('file_id' => $fileID));
    $ci->db->trans_complete();

    if ($ci->db->trans_status() === FALSE) {
        $success = 'false';
    }

    return array('success' => $success);
}

function soft_delete_file($data) {
    $success = 'true';
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->trans_start();
    $updateQuery = 'UPDATE file SET is_delete = 1, delete_message = ' .
            $ci->db->escape($data['comments']) . ' WHERE id = ' .
            $ci->db->escape($data['filePk']);
    $ci->db->query($updateQuery);
    $ci->db->trans_complete();

    if ($ci->db->trans_status() === FALSE) {
        $success = 'false';
    }

    return array('success' => $success);
}

/**
 * Record manager's status
 *
 * This function is used to fetch all the recordset from the table
 * record_manager_status.
 *
 * @author Vijayan G<vijayang@salzertechnologies.com>
 * @since 14-Jan-2016
 *
 *
 * @return array
 */
function get_record_manager_status($record_manager_status_id = array()) {
    $ci = & get_instance();
    $ci->load->database();

    if (!empty($record_manager_status_id)) {
        $ci->db->where('id', $record_manager_status_id['id']);
    }
    $query = $ci->db->get('record_manager_status');

    return $query->result();
}

/**
 * Update file record status.
 * 
 * This function is used to update the file's status in file table and insert 
 * the comment in the table record_status_comment with file_id and status_id.
 * 
 * @author Vijayan G <vijayang@salzertechnologies.com>
 * @since 18-Jan-2016
 * 
 * @param array $data Array variable with elements for db queries.
 * @return String true or false in string way since it is passed to ajax.
 */
function update_file_record_status($data) {
//    return $data;

    $ci = & get_instance();
    $ci->load->database();

    $updateQuery = 'UPDATE file SET record_manager_status_id = ' .
            $ci->db->escape($data['statusType']) . ' WHERE id = ' .
            $ci->db->escape($data['filePk']);
    $updateBarcodeQuery = 'UPDATE file SET barcode_id = ' .
            $ci->db->escape($data['barcodeNumber']) . ' WHERE id = ' .
            $ci->db->escape($data['filePk']) . ' AND (barcode_id IS NULL OR barcode_id = "")';
    $InsertQuery = "INSERT INTO record_status_comment(comment, file_id, "
            . " record_manager_status_id, created_date, created_by) "
            . " VALUES ({$ci->db->escape($data['statusComment'])}, "
            . " {$ci->db->escape($data['filePk'])}, "
            . " {$ci->db->escape($data['statusType'])}, "
            . " CURRENT_TIMESTAMP, "
            . " {$ci->db->escape($data['logged_in_user_id'])})";

    $ci->db->trans_start();
    $ci->db->query($updateQuery);
    if ($data['statusType'] == RECORD_MANAGER_STATUS_READY_FOR_COLLECTION OR $data['statusType'] == RECORD_MANAGER_STATUS_DELIVERED) {
        $ci->db->query($updateBarcodeQuery);
    }
    $ci->db->query($InsertQuery);
    $ci->db->trans_complete();

    $success = 'true';
    if ($ci->db->trans_status() === FALSE) {
        $success = 'false';
    }


    return array('success' => $success);
}

function save_search_template($data) {
    if ($data[0]['template_id'] === '') {
        _insert_search_template($data);
    } else if (is_int(intval($data[0]['template_id']))) {
        _update_search_template($data);
    }
}

function _insert_search_template($data) {
    $ci = & get_instance();
    $ci->load->database();

    $serializedFilter = serialize(_format_advanced_search_input(
                    $data[0]['search_input']));

    $insertData = array(
        'name' => $data[0]['template_name'],
        'filters' => $serializedFilter,
        'created_by' => $data[1]['login_id'],
        'updated_by' => $data[1]['login_id']
    );

    $ci->db->insert('fts_advanced_search_template', $insertData);
}

function _update_search_template($data) {
    $ci = & get_instance();
    $ci->load->database();

    $serializedFilter = serialize(_format_advanced_search_input(
                    $data[0]['search_input']));

    $updateData = array(
        'filters' => $serializedFilter,
        'updated_by' => $data[1]['login_id']
    );

    $ci->db->set('updated_on', DB_NOW, FALSE);

    $ci->db->where('id', $data[0]['template_id']);
    $ci->db->update('fts_advanced_search_template', $updateData);
}

function list_all_search_template_name($data) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->select('id, name');

    $query = $ci->db->get_where('fts_advanced_search_template',
            array('created_by' => $data['login_id']));

    $output['result'] = $query->result();
    return $output;
}

function load_template_options($data) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->select('filters');

    $query = $ci->db->get_where('fts_advanced_search_template',
            array('id' => $data['templateId']));

    $output['result'] = $query->row();

    return $output;
}

function get_transit_purpose($transit_purpose_id = array()) {
    $ci = & get_instance();
    $ci->load->database();

    if (!empty($transit_purpose_id)) {
        $ci->db->where('id', $transit_purpose_id['id']);
    }
    $query = $ci->db->get('transit_purpose');

    return $query->result();
}

function _format_advanced_search_input($searchInput) {
    foreach ($searchInput as $key => $value) {
        if (is_scalar($value)) {
            $searchInput[$key] = trim($value);
        } else if (is_array($value) && $key === 'search_by') {
            foreach ($value as $subKey => $subElement) {
                if ('' !== $searchTxt = trim($searchInput['search_by_txt'][$subKey])) {
                    $searchInput[$key][$subElement][] = $searchTxt;
                }
                unset($searchInput[$key][$subKey]);
            }
        }
    }
    unset($searchInput['search_by_txt']);

    return $searchInput;
}

function delete_template($data) {
    $ci = & get_instance();
    $ci->load->database();

    return $ci->db->delete('fts_advanced_search_template',
                    array('id' => $data['templateId']));
}
