<?php

/**
 * @author Vijayan G <vijayang@salzertechnologies.com>
 * 
 * This file consists the functions which are related to the access control list 
 * for the application users.
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @author Vijayan G <vijayang@salzertechnologies.com>
 * @since 1-dec-2015
 * @return boolean If they have access return true or else return false
 * @param int $filePK primary key of the file.
 *  
 * This function is used to check the file edit, clone, delete access for the 
 * logged in user. If admin there is no restriction at all.
 */
function checkFileModifyPermission($filePK = NULL) {
    $allow = FALSE;
    $ci = & get_instance();
    $ci->load->database();

    $ownerLoginDetails = $ci->session->userdata('check_login');
    $loginType = $ownerLoginDetails['login_type'];

    $ownerDivisionDetails = $ci->session->userdata('get_div');

    /* User type 2 is not needed since mail user is not able access any file 
     * tracking functionalities.
     */
    switch ($loginType) {
        case 1: //ADMIN
        case USER_TYPE_RECORD_MANAGER:
            $allow = TRUE;
            break;

        case 3: //DIVISION USER
            $divisionId = $ownerDivisionDetails['division_id'];

            $sql = "SELECT EXISTS (SELECT 1 FROM `file` WHERE `id` = '$filePK' AND "
                    . "`parent_division_id` = '$divisionId') as exist";

            $query = $ci->db->query($sql);

            $row = $query->row();

            $allow = (bool) $row->exist;
            break;

        case 4: //SUB DIVISION USER
            $divisionId = $ownerDivisionDetails['division_id'];
            $subDivisionId = $ownerDivisionDetails['subdivision_id'];

            $sql = "SELECT EXISTS (SELECT 1 FROM `file` WHERE `id` = '$filePK' AND "
                    . "`parent_division_id` = '$divisionId' AND "
                    . "`parent_sub_division_id` = '$subDivisionId') as exist";

            $query = $ci->db->query($sql);

            $row = $query->row();

            $allow = (bool) $row->exist;
            break;
    }

    return $allow;
}

function record_manager_status_color($recordManagerStatusID) {
    $colorClass = '';
    switch ($recordManagerStatusID) {
        case 1:
            $colorClass = 'pending';
            break;
        case 2:
            $colorClass = 'in_progress';
            break;
        case 3:
            $colorClass = 'hold';
            break;
        case 4:
            $colorClass = 'ready_for_collection';
            break;
        case 5:
            $colorClass = 'cancelled';
            break;
        default:
            $colorClass = 'no_status';
            break;
    }

    return $colorClass;
}

/**
 * @author Kannan P <kannanp@salzertechnologies.com>
 * @since 8-feb-2016
 * @return boolean If they have access return true or else return false
 * @param int $filePK primary key of the file.
 *  
 * This function is used to check the file edit, clone, delete access for the 
 * logged in user. If admin there is no restriction at all.
 */
function checkFileStatusPermission($filePK = NULL) {
    $allow = FALSE;
    $csi = & get_instance();
    $csi->load->database();

    $ownerLoginDetails = $csi->session->userdata('check_login');
    $loginType = $ownerLoginDetails['login_type'];

    $ownerDivisionDetails = $csi->session->userdata('get_div');

    /* User type 2 is not needed since mail user is not able access any file 
     * tracking functionalities.
     */
    switch ($loginType) {
        case 1: //ADMIN
        case USER_TYPE_RECORD_MANAGER:
            $allow = TRUE;
            break;

        case 3: //DIVISION USER
        case 4: //SUB DIVISION USER
            $sql = "SELECT EXISTS (SELECT 0 FROM `file` WHERE `id` = '$filePK' AND "
                    . "`record_manager_status_id` NOT IN ('" . RECORD_MANAGER_STATUS_PROGRESS . "', '"
                    . RECORD_MANAGER_STATUS_HOLD . "', '" . RECORD_MANAGER_STATUS_READY_FOR_COLLECTION
                    . "')) as exist";

            $query = $csi->db->query($sql);

            $row = $query->row();

            $allow = (bool) $row->exist;
            break;
    }

    return $allow;
}
