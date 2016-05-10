<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Fts extends MY_Controller {

    const FILE_UNIT = 'HQ Coast Guard Region (East)';

    private $data_user;

    public function __construct() {
        parent::__construct();
        //$this->output->enable_profiler(TRUE);
        $this->load->helper(array('export', 'salzer_acl'));
        $this->load->library('pagination');
        $this->load->library('Ajax_pagination');
        $this->load->library('email');
        $this->data_user = $this->session->userdata('check_login');

        if (!$this->session->userdata('check_login')) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(array('base_url' => base_url()));
            } else {
                redirect(base_url());
            }
        }

        if ($this->session->userdata('check_login') && $this->data_user['login_type'] == 2 && do_action('checkststus.plugin') == 1) {
            redirect(base_url() . '?c=home&m=dashboard');
        }
        
        $searchTemplateNameArray = 
                do_action('list_all_search_template_name.plugin', $this->data_user);
        $this->fts_advanced_search_template_name = $searchTemplateNameArray['result'];
    }

    public function add_file_data() {
        $checkLogin = $this->session->userdata('check_login');

        /* This is blocked due to printer whose application generate QR code 
         * and that itself only in Internet Explorer.
          $this->load->helper('salzer_barcode_helper');
         */

        $this->load->model('division', 'div');
        $this->load->model('users');

        $settings = $this->settings;
        $limit = $settings['no_of_records'];

        $tmp = $this->session->userdata('div_data');

        if ($this->input->get('div_id')) {
            $data['divisionid'] = $this->input->get('div_id');
        } else {
            $data['divisionid'] = $tmp['division_id'];
        }

        if ($this->input->get('filter_file_no')) {
            $data['filter_file_no'] = $this->input->get('filter_file_no');
        } else if ($this->input->post('filter_file_no')) {
            $data['filter_file_no'] = $this->input->post('filter_file_no');
        } else {
            $data['filter_file_no'] = '';
        }

        $data['file_unit_cost'] = self::FILE_UNIT;

        $data['div_data'] = $this->session->userdata('div_data');
        $data['login_type'] = $checkLogin['login_type'];

        //RECORD MANAGER STATUS
        if ($checkLogin['login_type'] == USER_TYPE_RECORD_MANAGER) {
            $data['record_manager_status'] = do_action('get_record_manager_status.plugin');
        }
        
        $data['sortby'] = 'file_created_date';
        $data['order'] = 'desc';       
        if ($this->input->get('sortby')) {
            $data['sortby'] = $this->input->get('sortby');
        }
        if ($this->input->get('order')) {
            $data['order'] = $this->input->get('order');
        }
        $sort = '&sortby=' . $data['sortby'] . '&order=' . $data['order'];
        
        $data['divisions'] = $this->div->fetch_division_list();
        $data['fileRecordSet'] = do_action('get_file_recordset.plugin',
                array($limit, $this->input->get('per_page'), $data['filter_file_no'], $data['sortby'], $data['order']));

        $config['base_url'] = site_url() . '?c=' . $this->router->class . '&m=' . $this->router->method;
        if ($data['filter_file_no'] != '') {
            $config['base_url'] .= '&filter_file_no=' . $data['filter_file_no'];
        }
        $num_rows = do_action('get_file_recordset_count.plugin', array($data['filter_file_no']));
        $config['total_rows'] = $num_rows[0];

        $config['per_page'] = $limit;

        $data['current_url'] = $config['base_url'] . '&per_page=' . $this->input->get('per_page');
        $config['base_url'] .= $sort;
        
        $this->pagination->initialize($config);        
        
        $this->content = do_action('add_file_data.plugin', $data);
        $this->template_view('fts/index', 'template_fts');
//        $this->template_view('fts/add_file_data');

        $checkLoginArray = $this->session->userdata('check_login');

        $userArray = $this->users->edit_user($checkLoginArray['login_id']);

        $userID = $this->userDetails = $userArray['id'];

        $maxFileID = do_action('get_max_file_id.plugin');

        $maxFileID++;

        //$this->barCodeFileNumber = $this->getBase2Base($maxFileID);
        $this->barCodeFileNumber = '';


        $data['insert_input']['file_name'] = $this->input->post('file_name');
        $data['insert_input']['file_number'] = $this->input->post('file_no');
        $data['insert_input']['file_owner'] = $userID;
        $data['insert_input']['file_part_number'] = $this->input->post('part_case');
        $data['insert_input']['file_subject'] = $this->input->post('file_subject');
        $data['insert_input']['file_date_opened'] = $this->input->post('file_date_opened');
        $data['insert_input']['file_date_closed'] = $this->input->post('file_date_closed');
        $data['insert_input']['file_unit'] = $this->input->post('file_unit');
        $data['insert_input']['file_contact_email'] = $this->input->post('file_contact_email');
        $data['insert_input']['file_contact_mobile'] = $this->input->post('file_contact_mobile');
        $data['insert_input']['file_contact_landline'] = $this->input->post('file_contact_landline');
        $data['insert_input']['file_category'] = $this->input->post('file_category');
        $data['insert_input']['file_tags'] = $this->input->post('file_tags');
        $data['insert_input']['barcode_id'] = $this->barCodeFileNumber;
        $data['insert_input']['file_updated_by'] = $userID;
        $data['insert_input']['division_id'] = $this->input->post('division');
        $data['insert_input']['sub_division_id'] = $this->input->post('sub_division');
        $data['insert_input']['volume'] = $this->input->post('volume');
        $data['insert_input']['department'] = $this->input->post('department');
        $data['insert_input']['file_subject_hindi'] = $this->input->post('file_subject_hindi');
        $data['insert_input']['file_department_hindi'] = $this->input->post('file_department_hindi');

        if ($this->input->server('REQUEST_METHOD') == 'POST' && $this->input->post('entry_submit')) {
            if(is_array($this->input->post('file_additional_contact_email'))) {
                $data['insert_input']['file_additional_contact_email'] = implode(",", array_filter($this->input->post('file_additional_contact_email')));
            } else {
                $data['insert_input']['file_additional_contact_email'] = '';
            }
            $data['insert_input']['office_purpose'] = $this->input->post('office_purpose');
            
            //$this->fts->add_file_data($data);
            $lastInsertId = do_action('add_file_data.plugin', $data);

            //QRCODE GENERATION
            /* This is blocked due to printer whose application generate QR code 
             * and that itself only in Internet Explorer.
              $params = array('code' => $this->barCodeFileNumber, 'type' => 'QRCODE,H');
              $this->load->library('Tcpdfqrcode', $params);
              file_put_contents(FCPATH . 'bar_codes' . DIRECTORY_SEPARATOR . $this->barCodeFileNumber . '.png',
              $this->tcpdfqrcode->getBarcodePngData(3.3, 3.3,
              array(0, 0, 0)));
             *
             */

            $fileDetail = new stdClass();
            $fileDetail->file_number = $data['insert_input']['file_number'];
            $fileDetail->barcode_id = $data['insert_input']['barcode_id'];
            $statusDetails['statusName'] = 'PENDING';
            $statusDetails['statusComment'] = 'Comments: No Comments.';
            $this->file_record_status_trigger_email($data['insert_input']['file_contact_email'],
                    $data['insert_input']['file_additional_contact_email'],
                    $fileDetail, $statusDetails);

            $submitButtonName = isset($_POST['entry_submit']) ? 'entry_submit' : 'entry_submit_barcode';
            $ajaxData = array('barcode' => $this->barCodeFileNumber,
                'submit_button_name' => $submitButtonName, 'id' => $lastInsertId['last_insert_file_id']);
            echo json_encode($ajaxData);
            die; //Intentionally die given to get proper data in ajax since for barcode no event listener after post data
        }
    }

    public function get_subdivision() {
        $divisionId = $this->input->post('division_id');

        $this->load->model('division', 'div');

        echo $this->div->fetchsubdivision($divisionId);
    }

    public function file_tracking() {
        $fileNumber = $this->input->post('file_number');
        echo json_encode(do_action('file_tracking.plugin', $fileNumber));
    }

    public function my_file_tracking() {
        $settings = $this->settings;
        $limit = $settings['no_of_records'];
        $data = array();
        if ($this->input->get('file_number')) {
            $fileNumber = $this->input->get('file_number');
        } else if ($this->input->post('file_number')) {
            $fileNumber = $this->input->post('file_number');
        } else {
            $fileNumber = '';
        }
        $page = $this->input->post('page');
        if (!$page) {
            $offset = 0;
        } else {
            $offset = $page;
        }
        //total rows count
        $totalRec = do_action('ajax_file_tracking_count.plugin', $fileNumber);

        //pagination configuration
        $config['first_link'] = 'First';
        $config['div'] = 'fileList'; //parent div tag id
        $config['base_url'] = site_url() . '?c=' . $this->router->class . '&m=ajaxFileTracking';
        if ($fileNumber != '') {
            $config['base_url'].= '&file_number=' . $fileNumber;
        }
        $config['total_rows'] = $totalRec;
        $config['per_page'] = $limit;
        $config['show_count'] = FALSE;

        $this->ajax_pagination->initialize($config);

        //get the file tracking data
        $records = do_action('ajax_file_tracking.plugin',
                array($fileNumber, $limit, $offset));
        $data['files'] = $records['file_tracking_details'];
        $data['file_heading'] = $records['file_heading'];

        //load the view
        $this->load->view('filetracking/index', $data);
    }

    public function ajaxFileTracking() {
        $settings = $this->settings;
        $limit = $settings['no_of_records'];
        $data = array();
        if ($this->input->get('file_number')) {
            $fileNumber = $this->input->get('file_number');
        } else if ($this->input->post('file_number')) {
            $fileNumber = $this->input->post('file_number');
        } else {
            $fileNumber = '';
        }
        $page = $this->input->post('page');
        if (!$page) {
            $offset = 0;
        } else {
            $offset = $page;
        }
        //total rows count
        $totalRec = do_action('ajax_file_tracking_count.plugin', $fileNumber);

        //pagination configuration
        $config['first_link'] = 'First';
        $config['div'] = 'fileList'; //parent div tag id
        $config['base_url'] = site_url() . '?c=' . $this->router->class . '&m=ajaxFileTracking';
        if ($fileNumber != '') {
            $config['base_url'].= '&file_number=' . $fileNumber;
        }
        $config['total_rows'] = $totalRec;
        $config['per_page'] = $limit;
        $config["cur_page"] = $page;
        $config['show_count'] = FALSE;

        $this->ajax_pagination->initialize($config);

        //get the file tracking data
        $records = do_action('ajax_file_tracking.plugin',
                array($fileNumber, $limit, $offset));
        $data['files'] = $records['file_tracking_details'];
        $data['file_heading'] = $records['file_heading'];

        //load the view
        $this->load->view('filetracking/ajax_file_tracking', $data);
    }

    public function file_cover_page() {
        $fileNumber = $this->input->post('file_number');
        echo json_encode(do_action('file_cover_page.plugin', $fileNumber));
        //$this->template_view('fts/index', 'template_fts_label');
    }

    function clone_file_data() {

        $this->load->model('division', 'div');
        $this->load->model('users');

        $checkLoginArray = $this->session->userdata('check_login');

        $userArray = $this->users->edit_user($checkLoginArray['login_id']);

        $userID = $this->userDetails = $userArray['id'];
        $maxFileID = do_action('get_max_file_id.plugin');

        $maxFileID++;

        //$this->barCodeFileNumber = $this->getBase2Base($maxFileID);
        $this->barCodeFileNumber = '';

        $data['insert_input']['file_name'] = $this->input->post('clone_file_name');
        $data['insert_input']['file_number'] = $this->input->post('clone_file_no');
        $data['insert_input']['file_owner'] = $userID;
        $data['insert_input']['file_part_number'] = $this->input->post('clone_file_part_case');
        $data['insert_input']['file_subject'] = $this->input->post('clone_file_subject');
        $data['insert_input']['file_date_opened'] = $this->input->post('clone_file_date_opened');
        $data['insert_input']['file_date_closed'] = $this->input->post('clone_file_date_closed');
        $data['insert_input']['file_unit'] = $this->input->post('clone_file_unit');
        $data['insert_input']['file_contact_email'] = $this->input->post('clone_file_contact_email');
        $data['insert_input']['file_contact_mobile'] = $this->input->post('clone_file_contact_mobile');
        $data['insert_input']['file_contact_landline'] = $this->input->post('clone_file_contact_landline');
        $data['insert_input']['file_category'] = $this->input->post('clone_file_category');
        $data['insert_input']['file_tags'] = $this->input->post('clone_file_tags');
        $data['insert_input']['barcode_id'] = $this->barCodeFileNumber;
        $data['insert_input']['file_updated_by'] = $userID;
        $data['insert_input']['division_id'] = $this->input->post('clone_file_division');
        $data['insert_input']['sub_division_id'] = $this->input->post('clone_file_sub_division');
        $data['insert_input']['volume'] = $this->input->post('clone_file_volume');
        $data['insert_input']['department'] = $this->input->post('clone_file_department');
        $data['insert_input']['file_subject_hindi'] = $this->input->post('clone_file_subject_hindi');
        $data['insert_input']['file_department_hindi'] = $this->input->post('clone_file_department_hindi');
        $data['insert_input']['file_additional_contact_email'] = implode(",",
                array_filter($this->input->post('clone_file_additional_contact_email')));
        
        $data['insert_input']['office_purpose'] = $this->input->post('clone_office_purpose');

        //$this->fts->add_file_data($data);
        $this->content = do_action('add_file_data.plugin', $data);
        $this->template_view('fts/index');

        $fileDetail = new stdClass();
        $fileDetail->file_number = $data['insert_input']['file_number'];
        $fileDetail->barcode_id = $data['insert_input']['barcode_id'];
        $statusDetails['statusName'] = 'PENDING';
        $statusDetails['statusComment'] = 'Comments: No Comments.';
        $this->file_record_status_trigger_email($data['insert_input']['file_contact_email'],
                $data['insert_input']['file_additional_contact_email'],
                $fileDetail, $statusDetails);

        $uriForm = explode("c=fts&m=", $_SERVER['HTTP_REFERER']);
        redirect('c=fts&m=' . $uriForm[1]);
        exit;
    }

    public function file_check() {
        $sess = $this->session->userdata('check_login');

        $userType = $sess['login_type'];
        if ($userType == 1) {

            redirect('c=fts&m=list_all_files');
            exit;
        } else if ($userType == 5) {

            redirect('c=fts&m=add_file_data');
            exit;
        } else if ($userType == 2) {

            redirect('c=home&m=dashboard');
            exit;
        }
        $this->content = do_action('checkin_out.plugin');
        $this->template_view('fts/index', "template_fts");
    }

    public function get_file_details() {
        $barcode = $this->input->post('barcode');
        $sess = $this->session->userdata('check_login');

        $userID = $sess['login_id'];
        $data = array("user_id" => $userID, "barcode_id" => $barcode);
        $jsonresp = do_action('get_file_details.plugin', $data);

        $jsonresp = json_encode($jsonresp);

        echo $jsonresp;
    }

    public function file_check_submit() {

        $barcode = $this->input->post('file_barcode');
        $checkStatus = $this->input->post('check_status');
        $fileID = $this->input->post('file_id');
        $transit_purpose_id = $this->input->post('transit_purpose_id');

        $sess = $this->session->userdata('check_login');

        $userID = $sess['login_id'];

        $data = array("transit_purpose_id" => $transit_purpose_id, "barcode" => $barcode, "status" => $checkStatus, "file_id" => $fileID, "user_id" => $userID);

        $resp = do_action('set_file_status.plugin', $data);

        if ($resp) {
            $fileDetail = do_action('file_detail.plugin', $fileID);

            $cc = $fileDetail->file_additional_contact_email;

            $to = $this->input->post('file_owner_email');

            $trackingData = do_action('file_tracking.plugin', $fileID);
            $this->file_tracking_email($to, $cc, $trackingData);

            redirect('c=fts&m=file_check');
        }
    }

    public function unique_id() {

        $character_set_array = array();
        $character_set_array[] = array('count' => 1, 'characters' => 'ABCDEFGHJKLMNPQRSTUVWXYZ');
        $character_set_array[] = array('count' => 3, 'characters' => '0123456789'); //you can change count
        $temp_array = array();
        foreach ($character_set_array as $character_set) {
            for ($i = 0; $i < $character_set['count']; $i++) {
                $temp_array[] = $character_set['characters'][rand(0,
                                strlen($character_set['characters']) - 1)];
            }
        }
        shuffle($temp_array);
        $serialString = implode('', $temp_array);
        $suffix = $this->suffix_encode();
        return $serialString . "-" . $suffix;
    }

    function suffix_encode() {

        $encodeVal = time();
        $aplhaVars = array("A", "B", "C", "D", "E", "F", "G", "H", "J", "K", "L", "M", "N", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
        $index_r = $encodeVal % 24;
        return $aplhaVars[$index_r];
    }

    public function test() {

        $ldaphost = "23.102.168.168";  // your ldap servers
        $ldapport = 389;                 // your ldap server's port number
// Connecting to LDAP
        $ldapconn = ldap_connect($ldaphost, $ldapport)
                or die("Could not connect to $ldaphost");

        if ($ldapconn) {
            $ldapbind = ldap_bind($ldapconn);

            if ($ldapbind) {
                echo "LDAP bind successful...";
            } else {
                echo "LDAP bind failed...";
            }
        }
    }

    public function get_barcode() {
        $fileNumber = $this->input->post('file_number');
        echo json_encode(do_action('get_barcode.plugin', $fileNumber));
    }

    public function getBase2Base($inputNum) {

        $base1 = 10;
        $base2 = 34;
        $temp = "";
        $x = "";
        $i = "";
        $nextChar = "";
        $convertedNumber = "";
        $chars = "";
        $validPos = "";
        $index = "";
        $convert = "";
        $chars = "0123456789ABCDEFGHJKLMNPQRSTUVWXYZ";
        $sufixVal = $this->getSuffix($inputNum);


        $powerVal = "";

        $validation1HasError = false;

        if ($base2 > strlen($chars)) {
            $convert = "error - invalid data detected";
            $validation1HasError = true;
        }

        if ($validation1HasError) {
            return -1;
        }

        /* '--oldNum characters should exist in base dictionary and within Base2 size-- */
        for ($i = 0; $i < strlen($inputNum); $i++) {

            $midval = "";
            $midval = substr($inputNum, $i, 1);
            // echo "</br> mymidval ".$midval ;
            $validPos = strpos($chars, ($midval));

            if ($validPos === false) {
                $convert = "error - invalid data detected";
                // echo "Valid Pos".$validPos."</br>" ;
                $validation1HasError = true;
            } else {
                $ActualvalidPos = $validPos + 1;
                // echo "Act Valid Pos". $ActualvalidPos."</br>" ;
            }
            if ($ActualvalidPos > $base1) {
                $convert = "error - invalid data detected";

                //  echo " I am breaking at".$validPos ;
                $validation1HasError = true;
            }

            if ($validation1HasError) {
                break;
            }
        }

        if ($validation1HasError) {
            // echo " I am breaking";
            return -1;
        }


        /* --Convert to Base-10 Decimal-- */

        $x = 0;
        for ($i = strlen($inputNum); $i >= 1; $i--) {
            $nextChar = "";
            $nextChar = substr($inputNum, $x, 1);
            //  echo "nextChar".$nextChar."</br>" ;
            $x = $x + 1;
            //   echo " x". $x."</br>" ;
            $stratPos = strpos($chars, $nextChar);

            // echo " stratPos". $stratPos."</br>" ;
            $stratPos = $stratPos;
            $powtoAns = pow($base1, ($i - 1));
            $temp = $temp + $stratPos * $powtoAns;
            //	echo " temp". $temp."</br>" ;
        }


        $x = 0;
        $powtobase2 = pow($base2, $x);
        while ($temp >= $powtobase2) {
            $x++;
            $powtobase2 = pow($base2, $x);
        }

        for ($i = 1; $i <= $x; $i++) {

            $powtobaseforX = pow($base2, ($x - $i));
            //  echo "$powtobaseforX". $powtobaseforX."</br>" ;
            $index = intval($temp / $powtobaseforX);

            //  echo "index ". $index ."</br>" ;
            $sVal = substr($chars, $index, 1);

            // echo "sVal  ". $sVal  ."</br>" ;

            $convertedNumber = $convertedNumber . $sVal;
            $temp = $temp - $index * $powtobaseforX;
        }
        $convertedNumber = trim($convertedNumber);
        $convertedNumber = $convertedNumber . "-" . $sufixVal;
        $convertedNumber = str_pad($convertedNumber, 6, "0", STR_PAD_LEFT);

        return $convertedNumber;
    }

    function getSuffix($val) {

        $aplhaVars = array("A", "B", "C", "D", "E", "F", "G", "H", "J", "K", "L", "M", "N", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "1", "2", "3", "4", "5", "6", "7", "8", "9");
        $index_r = $val % 33;
        return $aplhaVars[$index_r];
    }

    public function advanced_search() {
        $data = array();
        $userDetail = $this->session->userdata('check_login');
        $data['logintype'] = $userDetail['login_type'];

        $tmp = $this->session->userdata('div_data');
        
        //Load Quick View Template Data - BOF
        if ($this->input->get('temp_id')) {
            $template_id = $this->input->get('temp_id');
            $template_options = do_action('load_template_options.plugin', array('templateId' => $template_id));
            if(!empty($template_options)) {
                $filters = unserialize($template_options['result']->filters);
                foreach($filters as $filter => $val) {
                    switch($filter) {
                        case 'search_by':
                            foreach($val as $key => $v) {
                                $postData['search_input']['search_by'][] = $key;
                                $postData['search_input']['search_by_txt'][] = $v[0];
                            }
                            break;
                        default:
                            $postData['search_input'][$filter] = $val;
                            break;
                    }                    
                }
                $this->session->set_userdata('search_input',
                    $postData['search_input']);
            }
        }
        //Load Quick View Template Data - EOF

        if ($this->input->get('div_id')) {
            $data['divisionid'] = $this->input->get('div_id');
        } else {
            $data['divisionid'] = $tmp['division_id'];
        }

        if ($this->input->post() !== FALSE) {
            $postData['search_input']['division'] = $this->input->post('division');
            $postData['search_input']['subdivision'] = $this->input->post('subdivision');
            $postData['search_input']['search_by'] = $this->input->post('search_by');
            $postData['search_input']['search_by_txt'] = $this->input->post('search_by_txt');
            $postData['search_input']['from_datepicker'] = $this->input->post('from_datepicker');
            $postData['search_input']['to_datepicker'] = $this->input->post('to_datepicker');
            $postData['search_input']['deleted_file'] = $this->input->post('deleted_file');

            $this->session->set_userdata('search_input',
                    $postData['search_input']);

            if ($this->input->post('save_template_btn') !== FALSE) {
                $postData['template_name'] = $this->input->post('save_template_txt');
                $postData['template_id'] = $this->input->post('template_id');
                do_action('save_search_template.plugin', array($postData,
                    $userDetail));
                header("Location: " . site_url() . '?c=' . $this->router->class . '&m=' . $this->router->method);
            }
        }
        $data['search_input'] = $this->session->userdata('search_input');
        $this->load->model('division', 'div');
        $data['divisions'] = $this->div->fetch_division_list();

        $data['sortby'] = 'file_created_date';
        $data['order'] = 'desc';       
        if ($this->input->get('sortby')) {
            $data['sortby'] = $this->input->get('sortby');
        }
        if ($this->input->get('order')) {
            $data['order'] = $this->input->get('order');
        }
        $sort = '&sortby=' . $data['sortby'] . '&order=' . $data['order'];
        
        //PAGINATION
        $settings = $this->settings;
        $limit = $settings['no_of_records'];

        $config['base_url'] = site_url() . '?c=' . $this->router->class . '&m=' . $this->router->method . $sort;
        $countArray = do_action('advanced_search_count.plugin', $data);
        $config['total_rows'] = $countArray['count'];
        $config['per_page'] = $limit;

        $this->pagination->initialize($config);

        //LIMIT OFFSET
        $data['limit'] = $limit;
        $data['offset'] = $this->input->get('per_page');

        //NO OF RECORDS
        $data['count'] = $config['total_rows'];
        
        $data['current_url'] = site_url() . '?c=' . $this->router->class .
                '&m=' . $this->router->method . '&per_page=' . $this->input->get('per_page');

        $this->content = do_action('advanced_search.plugin', $data);
        $this->template_view('fts/index', 'template_fts');
    }

    public function global_search() {
        $userDetail = $this->session->userdata('check_login');
        $data['logintype'] = $userDetail['login_type'];

        $postQuery = $this->input->post('query');

        if ($postQuery !== FALSE) {
            $this->session->set_userdata(array('search_input' => $postQuery));
        }
        
        $data['sortby'] = 'file_created_date';
        $data['order'] = 'desc';       
        if ($this->input->get('sortby')) {
            $data['sortby'] = $this->input->get('sortby');
        }
        if ($this->input->get('order')) {
            $data['order'] = $this->input->get('order');
        }
        $sort = '&sortby=' . $data['sortby'] . '&order=' . $data['order'];

        $data['search_input'] = $this->session->userdata('search_input');
        $this->load->model('division', 'div');
        $data['divisions'] = $this->div->fetch_division_list();

        $settings = $this->settings;
        $limit = $settings['no_of_records'];
        $data['limit'] = $limit;
        
        $data['current_url'] = site_url() . '?c=' . $this->router->class .
                '&m=' . $this->router->method . '&per_page=' . $this->input->get('per_page');
        
        $this->content = do_action('global_search.plugin', $data);
        $this->template_view('fts/index', 'template_fts');
    }

    public function list_all_files() {
        //echo '<pre>'; 
        //print_r($this->session->all_userdata());
        $this->session->all_userdata();
        $tmp = $this->session->userdata('check_login');
        $data['logintype'] = $tmp['login_type'];
        //echo $data['logintype'];
        $tmp = $this->session->userdata('div_data');
        $paginationBaseUrlAppend = '';

        if ($this->input->get('div_id')) {
            $data['divisionid'] = $this->input->get('div_id');
            $data['get_flag'] = TRUE;
            $paginationBaseUrlAppend .= '&div_id=' . $this->input->get('div_id');
        } else {
            $data['divisionid'] = $tmp['division_id'];
        }
        if ($this->input->get('div_id') && $this->input->get('subdivision_id')) {
            $data['subdivisionid'] = $this->input->get('subdivision_id');
            $paginationBaseUrlAppend .= '&subdivision_id=' . $this->input->get('subdivision_id');
        } else {
            $data['subdivisionid'] = $tmp['subdivision_id'];
        }

        if ($this->input->get('fstatus')) {
            $data['fstatus'] = $this->input->get('fstatus');
            $paginationBaseUrlAppend .= '&fstatus=' . $this->input->get('fstatus');
        } else {
            $data['fstatus'] = 'all';
        }

        if ($this->input->get('f_status_two')) {
            $data['fStatusTwo'] = $this->input->get('f_status_two');
            $paginationBaseUrlAppend .= '&f_status_two=' . $data['fStatusTwo'];
        } else {
            $data['fStatusTwo'] = 'all';
        }
        
        $data['sortby'] = 'file_created_date';
        $data['order'] = 'desc';       
        if ($this->input->get('sortby')) {
            $data['sortby'] = $this->input->get('sortby');
        }
        if ($this->input->get('order')) {
            $data['order'] = $this->input->get('order');
        }
        $sort = '&sortby=' . $data['sortby'] . '&order=' . $data['order'];
        
        //echo $data['divisionid'];
//echo $divisionid;
        //exit;
        //PAGINATION
        $settings = $this->settings;
        $limit = $settings['no_of_records'];
        $config['base_url'] = site_url() . '?c=' . $this->router->class .
                '&m=' . $this->router->method . $paginationBaseUrlAppend . $sort;

        $countArray = do_action('list_all_files_count.plugin', $data);
        $config['total_rows'] = $countArray['count'];
        $config['per_page'] = $limit;

        $this->pagination->initialize($config);
        $this->load->model('division', 'div');

        $data['divisions'] = $this->div->fetch_division_list();
        //LIMIT OFFSET
        $data['limit'] = $limit;
        $data['offset'] = $this->input->get('per_page');

        //NO OF RECORDS
        $data['count'] = $config['total_rows'];

        $data['current_url'] = site_url() . '?c=' . $this->router->class .
                '&m=' . $this->router->method . '&per_page=' . $this->input->get('per_page') . $paginationBaseUrlAppend;
        
        $this->content = do_action('list_all_files.plugin', $data);
        $this->template_view('fts/index', 'template_fts');
    }

    public function edit_file_data() {
        $this->load->model('users');

        $checkLoginArray = $this->session->userdata('check_login');
        $userArray = $this->users->edit_user($checkLoginArray['login_id']);
        $userID = $userArray['id'];

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $data['condition']['id'] = $this->input->post('edit_file_pk');

            $data['update_input']['file_subject'] = $this->input->post('edit_file_subject');
            $data['update_input']['department'] = $this->input->post('edit_file_department');
            $data['update_input']['file_subject_hindi'] = $this->input->post('edit_file_subject_hindi');
            $data['update_input']['file_department_hindi'] = $this->input->post('edit_file_department_hindi');
            $data['update_input']['file_contact_email'] = $this->input->post('edit_file_contact_email');
            $data['update_input']['file_contact_mobile'] = $this->input->post('edit_file_contact_mobile');
            $data['update_input']['file_contact_landline'] = $this->input->post('edit_file_contact_landline');
            $data['update_input']['file_category'] = $this->input->post('edit_file_category');
            $data['update_input']['file_tags'] = $this->input->post('edit_file_tags');
            $data['update_input']['file_updated_by'] = $userID;

            $data['update_input']['parent_division_id'] = $this->input->post('edit_file_division');
            $data['update_input']['parent_sub_division_id'] = $this->input->post('edit_file_sub_division');
            $data['update_input']['file_part_number'] = $this->input->post('edit_file_part_case');
            $data['update_input']['volume'] = $this->input->post('edit_file_volume');
            $data['update_input']['file_number'] = $this->input->post('edit_file_number');
            $data['update_input']['file_date_opened'] = $this->input->post('edit_file_date_opened');
            $data['update_input']['file_date_closed'] = $this->input->post('edit_file_date_closed');
            $data['update_input']['file_additional_contact_email'] = implode(",",
                    array_filter($this->input->post('edit_file_additional_contact_email')));

            $data['update_input']['office_purpose'] = $this->input->post('edit_office_purpose');
            do_action('edit_file_data.plugin', $data);

            $this->load->library('user_agent');
            if ($this->agent->is_referral()) {
                redirect($this->agent->referrer());
            }
        }
    }

    public function file_detail_ajax() {
        $fileID = $this->input->post('fileID');
        echo json_encode(do_action('file_detail.plugin', $fileID));
    }

    public function delete_file_ajax() {
        $fileID = $this->input->post('fileID');
        echo json_encode(do_action('delete_file.plugin', $fileID));
    }
    
    public function soft_delete_file_ajax() {
        $fileID = $this->input->post('fileID');
        $comments = $this->input->post('comments');
        echo json_encode(do_action('soft_delete_file.plugin', array('filePk' => $fileID, 'comments' => $comments)));
    }

    /**
     * wrapper method for functionality documentation 
     * please see in update_file_record_status.plugin
     * 
     * @author Vijayan G<vijayang@salzertechnologies.com>
     * @since 14-Jan-2016
     */
    public function update_file_record_status_ajax() {
        $userDetail = $this->session->userdata('check_login');
        $data['logged_in_user_id'] = $userDetail['login_id'];

        $data['filePk'] = $this->input->post('filePK');
        $data['statusType'] = $this->input->post('statusType');
        $data['statusComment'] = $this->input->post('statusComment');

        $maxFileID = $this->input->post('filePK');

        $maxFileID++;

        $data['barcodeNumber'] = $this->getBase2Base($maxFileID);

        echo json_encode(do_action('update_file_record_status.plugin', $data));

        //TRIGGER EMAIL
        //START
        $fileDetail = do_action('file_detail.plugin', $data['filePk']);
        $recordStatus = do_action('get_record_manager_status.plugin',
                array('id' => $fileDetail->record_manager_status_id));

        $to = $fileDetail->file_contact_email;
        $cc = $fileDetail->file_additional_contact_email;
        $statusDetails['statusName'] = strtoupper($recordStatus[0]->status_name);
        $statusDetails['statusComment'] = (!empty($data['statusComment'])) ?
                'Comments: ' . $data['statusComment'] : 'Comments: No Comments.';

        $this->file_record_status_trigger_email($to, $cc, $fileDetail,
                $statusDetails);
        //TRIGGER EMAIL
        //END
    }

    /**
     * This method is used send email to the file created user regarding the 
     * status of file record updated by the record manager.
     * 
     * @author Vijayan G<vijayang@salzertechnologies.com>
     * @since 20-Jan-2016
     * @access private
     */
    private function file_record_status_trigger_email($to, $cc, $fileDetail,
            $statusDetails) {
        $this->email->set_mailtype("html");
        $this->email->from('no-reply@maitra.com', 'Record Manager');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->bcc('vijayang@salzertechnologies.com');
        //$this->email->bcc('gvgvgvijayan@gmail.com');

        $this->email->subject('File Record Status');
        $this->email->message("<html><head></head><body>"
                . "<div>For the file: <b>$fileDetail->file_number</b> "
                . " with code: <b>$fileDetail->barcode_id</b> "
                . " the status was updated to "
                . "<b>{$statusDetails['statusName']}</b>.</div>"
                . "<div>{$statusDetails['statusComment']}</div>"
                . " </body></html>");

        $this->email->send();

        //echo $this->email->print_debugger();//enable it only for debugging
    }

    public function file_tracking_email($to, $cc, $trackingData) {

        $this->email->set_mailtype("html");
        $this->email->from('no-reply@maitra.com', 'File Tracking');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->bcc('vijayang@salzertechnologies.com');
        $this->email->subject('File Tracking Status');

        $html = "<html><head></head><body>
                <h2>{$trackingData['file_heading']}
                 <table>
                    <tr>
                      <th>Current Location</th>
                      <th>Date</th>
                      <th>Status</th>
                    </tr>";

        if (!empty($trackingData['file_tracking_details'])) {
            foreach ($trackingData['file_tracking_details'] as $key => $record) {
                $html .= "<tr>
                      <td>{$trackingData['file_tracking_details'][$key]->current_location}</td>
                      <td>{$trackingData['file_tracking_details'][$key]->created_date}</td>
                      <td>{$trackingData['file_tracking_details'][$key]->status}</td>
                    </tr>";
            }
        } else {
            $html .= '<tr><td style="text-align:center;" colspan="3">No Records!!!</td></tr>';
        }

        $html .= '</table></body></html>';

        $this->email->message($html);

        $this->email->send();
    }
    
    public function ajax_load_template_options() {
        $data['templateId'] = $this->input->post('id');
        $outPut = do_action('load_template_options.plugin', $data);

        echo json_encode(unserialize($outPut['result']->filters));
    }
    
    public function ajax_delete_template() {
        $data['templateId'] = $this->input->post('id');
        $outPut = do_action('delete_template.plugin', $data);
        
        echo json_encode($outPut);
    }

}
