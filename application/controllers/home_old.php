<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends MY_Controller {

    public function __construct() {
        parent::__construct();
        //$this->output->enable_profiler(TRUE);
        $this->load->library('pagination');
        $this->load->library('session');
    }

    public function index() {
        $data['logo'] = $this->settings['site_logo'];
        $data['site_title'] = $this->settings['site_title'];
        $data['footer_txt'] = $this->settings['footer_text'];
        $this->load->view('login', $data);
    }

    function login() {
        $data['logo'] = $this->settings['site_logo'];
        if ($_POST) {
            $this->form_validation->set_rules('username', 'User Name',
                    'required');
            $this->form_validation->set_rules('password', 'Password', 'required');
            if ($this->form_validation->run() == true) {
                $data['username'] = $this->input->post('username');
                $data['password'] = $this->input->post('password');
                $data['remember_me'] = $this->input->post('remember_me');

                if ($data['remember_me'] == "on") {    // if user check the remember me checkbox        
                    setcookie('remember_me', $data['username'],
                            time() + 60 * 60 * 24 * 100, "/");
                } else {   // if user not check the remember me checkbox
                    setcookie('remember_me', 'gone',
                            time() - 60 * 60 * 24 * 100, "/");
                }

                $lquery = $this->db->query('SELECT * FROM log');
                $logcount = $lquery->num_rows();
                /* $setresults = $this->db->query('SELECT * FROM settings ORDER BY id DESC LIMIT 1');
                  $setting_row = $setresults->row_array();
                  echo $purge_val = $setting_row['purge_records']; exit; */

                if ($logcount > 2000) {
                    //$this->db->query('DELETE FROM log where id in (SELECT id FROM log ORDER BY `id` ASC LIMIT 0,1000)');
                    //echo "SELECT id FROM log ORDER BY `id` ASC LIMIT 0,".$purge_val; exit;
                    $lquery = $this->db->query("SELECT id FROM log ORDER BY `id` ASC LIMIT 0,1000 ");
                    $lresult = $lquery->row_array();
                    //echo "<pre>";print_r($lquery->result_array); exit;	
                    $res_arr = $lquery->result_array;
                    $i = 0;

                    foreach ($res_arr as $subArray) {
                        foreach ($subArray as $val) {
                            $newArray[] = $val;
                        }
                    }
                    //echo "<pre>"; print_r($newArray); exit;				
                    $limplode_val = implode(',', $newArray); //echo $limplode_val;  exit;					
                    //echo "DELETE FROM log where id IN(".$limplode_val.")"; exit;
                    $this->db->query("DELETE FROM log where id IN(" . $limplode_val . ")");
                }


                $this->load->model('verifylogin', 'lg');
                $result = $this->lg->login($data);                

                //echo $result; die;
                if ($result == 1) {
                    $login = $this->session->userdata('check_login');
                    if ($login['login_type'] == 3) {
                        $this->load->model('users', 'us');
                        $user = $this->us->user_division();
                        $division_id = $user->division_id;
                        $subdiv_id = $user->subdivision_id;
                        $div_data = array('division' => $user->division, 'subdivision' => $user->subdivision, 'division_id' => $division_id);
                        $this->session->set_userdata('get_div', $div_data);
                        //redirect('c=home&m=division_list&division_id=' . $division_id);
                        redirect('c=dashboard&division_id=' . $division_id);
                    } else if ($login['login_type'] == 4) {
                        $this->load->model('users', 'us');
                        $user = $this->us->user_division();
                        $division_id = $user->division_id;
                        $subdiv_id = $user->subdivision_id;
                        $div_data = array('division' => $user->division, 'subdivision' => $user->subdivision, 'division_id' => $division_id, 'subdivision_id' => $subdiv_id);
                        $this->session->set_userdata('get_div', $div_data);
                        //redirect('c=home&m=division_list&division_id=' . $division_id . '&subdivision_id=' . $subdiv_id);
                        redirect('c=dashboard&division_id=' . $division_id . '&subdivision_id=' . $subdiv_id);
                    } else if ($login['login_type'] == 5) {
                        //redirect('c=fts&m=list_all_files');
                        redirect('c=dashboard');
                    } else {
                        //redirect('c=home&m=dashboard');
                        redirect('c=dashboard');
                    }
                } else {
                    $adresult = $this->lg->adLogin($data);
                    if($adresult == 1) {             
                        $this->load->model('users', 'us');
                        $login = $this->session->userdata('check_login');
                        $user = $this->us->adUserDivision($login['login_id']);

                        $division_id = $user->division_id;
                        $subdiv_id = $user->subdivision_id;
                        $div_data = array('division' => $user->division, 'subdivision' => $user->subdivision, 'division_id' => $division_id, 'subdivision_id' => $subdiv_id);
                        $this->session->set_userdata('get_div', $div_data);
                        if ($login['login_type'] == 3) {
                            //redirect('c=home&m=division_list&division_id=' . $division_id);
                            redirect('c=dashboard&division_id=' . $division_id);
                        } else if ($login['login_type'] == 4) {                       
                            //redirect('c=home&m=division_list&division_id=' . $division_id . '&subdivision_id=' . $subdiv_id);
                            redirect('c=dashboard&division_id=' . $division_id . '&subdivision_id=' . $subdiv_id);
                        } else if ($login['login_type'] == 5) {
                            //redirect('c=fts&m=list_all_files');
                            redirect('c=dashboard');
                        } else {
                            //redirect('c=home&m=dashboard');
                           // redirect('c=dashboard');
                        }
                    } else {
                        $data['error'] = "Username or Password or both are incorrect.";
                        $data['site_title'] = $this->settings['site_title'];
                        $data['footer_txt'] = $this->settings['footer_text'];
                        $this->load->view('login', $data);
                    }
                }
            } else {
                $data['error'] = "Invalid Login Details";
                $data['site_title'] = $this->settings['site_title'];
                $data['footer_txt'] = $this->settings['footer_text'];
                $this->load->view('login', $data);
            }
        }
    }

    function dashboard() {
        if (!$this->session->userdata('check_login')) {
            redirect(base_url());
        }
        $v = isset($_GET['in']) ? $_GET['in'] : '';
        $link = "";
        if ($v) {
            $link = "&in=" . $_GET['in'];
        }

        $this->load->model('inmails', 'inm');
        $config['total_rows'] = $this->inm->inmail_total_rows($v);
        $config['per_page'] = $this->settings['no_of_records'];
        $config['base_url'] = site_url() . '?c=home&m=dashboard' . $link;
        $config['uri_segment'] = 3;
        $config['num_links'] = 10;
        //$config['use_page_numbers'] = TRUE;	
        $page = 0;
        if (isset($_GET['per_page']) && $_GET['per_page'] != 0) {
            //$config['base_url'] = site_url().'?c=home&m=dashboard';
            $page = $_GET['per_page'];
        }
        $this->pagination->initialize($config);

        $this->distributed_list = $this->inm->distributed_list($v,
                $config["per_page"], $page);
        $this->template_view('dashboard');
    }

    function plugins() {
        $session = $this->session->userdata('check_login');
        if (!$this->session->userdata('check_login') && $session['login_type'] != 1) {
            redirect(base_url());
        }

        $this->content = do_action('render.plugin');

        $this->template_view('fts/index');
    }

    function activate() {
        $session = $this->session->userdata('check_login');
        if (!$this->session->userdata('check_login') && $session['login_type'] != 1) {
            redirect(base_url());
        }

        do_action('activate.plugin');
        redirect(base_url() . "?c=home&m=plugins");
    }

    function deactivate() {
        $session = $this->session->userdata('check_login');
        if (!$this->session->userdata('check_login') && $session['login_type'] != 1) {
            redirect(base_url());
        }

        do_action('deactivate.plugin');
        redirect(base_url() . "?c=home&m=plugins");
    }

    function division_list() {   //print_r($_GET);
        if (!$this->session->userdata('check_login')) {
            redirect(base_url());
        }
        $arr['division_id'] = isset($_GET['division_id']) ? $_GET['division_id'] : '';
        $arr['subdivision_id'] = isset($_GET['subdivision_id']) ? $_GET['subdivision_id'] : '';
        if ($arr['division_id'] != '') {
            $this->session->set_userdata('div_data', $arr);
        }
        $div_data = $this->session->userdata('div_data');

        $suffix = "";
        if ($div_data) {
            $suffix = '&' . http_build_query($div_data, '', "&");
        }

        $this->load->model('inmails', 'inm');
        $config1['total_rows'] = $this->inm->division_list_rows($div_data['division_id'],
                $div_data['subdivision_id']);
        $config1['per_page'] = $this->settings['no_of_records'];
        $config1['base_url'] = site_url() . '?c=home&m=division_list' . $suffix . '&type=inmail';
        $config1['uri_segment'] = 4;
        $page1 = 0;
        if (isset($_GET['per_page']) && $_GET['per_page'] != 0 && $_GET['type'] == 'inmail') {
            //$config1['base_url']  = site_url().'?c=home&m=division_list&type=inmail&per_page=';
            $page1 = $_GET['per_page'];
        }
        $this->pagination->initialize($config1);
        $this->pagination1 = $this->pagination->create_links();
        $this->distributed_list = $this->inm->division_list($div_data['division_id'],
                $div_data['subdivision_id'], $config1["per_page"], $page1);

        $this->template_view('division_list');
    }

    function outmail_division_list() {   //print_r($_GET);
        if (!$this->session->userdata('check_login')) {
            redirect(base_url());
        }
        $arr['division_id'] = isset($_GET['division_id']) ? $_GET['division_id'] : '';
        $arr['subdivision_id'] = isset($_GET['subdivision_id']) ? $_GET['subdivision_id'] : '';
        if ($arr['division_id'] != '') {
            $this->session->set_userdata('div_data', $arr);
        }
        $div_data = $this->session->userdata('div_data');

        $suffix = "";
        if ($div_data) {
            $suffix = '&' . http_build_query($div_data, '', "&");
        }

        $this->load->model('outmails', 'otm');
        $config['total_rows'] = $this->otm->outmail_division_list_rows($div_data['division_id'],
                $div_data['subdivision_id']);
        $config['per_page'] = $this->settings['no_of_records'];
        $config['base_url'] = site_url() . '?c=home&m=outmail_division_list' . $suffix . '&type=outmail';
        $config['uri_segment'] = 4;
        $page = 0;
        if (isset($_GET['per_page']) && $_GET['per_page'] != 0 && $_GET['type'] == 'outmail') {
            //$config['base_url']   = site_url().'?c=home&m=outmail_division_list&type=outmail&per_page=';
            $page = $_GET['per_page'];
        }
        $this->pagination->initialize($config);
        $this->pagination2 = $this->pagination->create_links();
        $this->outmail_list = $this->otm->outmail_division_list($div_data['division_id'],
                $div_data['subdivision_id'], $config["per_page"], $page);

        $this->template_view('outmail_division_list');
    }

    function logout() {
        $this->session->sess_destroy();
        //redirect('c=admin&m=login');
        redirect('');
    }

    function mail_status() {
        $mail_status = $_POST['status'];
        $this->session->set_userdata('mail_status', $mail_status);
    }

    function credits() {
        if (!$this->session->userdata('check_login')) {
            redirect(base_url());
        }
        $this->template_view('developed_by');
    }

    function gnu() {
        if (!$this->session->userdata('check_login')) {
            redirect(base_url());
        }
        $this->template_view('gnu_license');
    }

    function inmail_xls() {

        if (!$this->session->userdata('check_login')) {
            redirect(base_url());
        }
        $v = isset($_GET['in']) ? $_GET['in'] : '';

        $this->load->model('inmails', 'inm');
        $result = $this->inm->distributed_list($v);

        $mailsfilename = "Search inmails" . time() . ".xls";
        header("Content-Disposition: attachment; filename=\"$mailsfilename\"");
        header("Content-Type: application/vnd.ms-excel");

        $header = array("Mail No", "Received Date", "Mail Reference", "Date", "Subject", "From", "To", "Copy To", "Division/Subdivision", "Receiver Signature");
        echo implode("\t", str_replace(',', ' ', $header)) . " \n";

        foreach ($result as $inm) {

            $subdiv = ($inm['subdivision']) ? " / " . $inm['subdivision'] : '';

            $row = array($inm["mail_no"], date('m/d/Y',
                        strtotime($inm["recieved_date"])), $inm["mail_ref"], date('m/d/Y',
                        strtotime($inm["date"])), $inm["subject"], $inm["from"], $inm["to"], $inm["cc"], $inm["division"] . $subdiv, "");
            echo implode("\t", str_replace(',', ' ', $row)) . " \n";
        }
    }

    function inmail_csv() {

        if (!$this->session->userdata('check_login')) {
            redirect(base_url());
        }
        $v = isset($_GET['in']) ? $_GET['in'] : '';

        $this->load->model('inmails', 'inm');

        $result = $this->inm->distributed_list($v);
        $mailsfilename = "Search inmails" . time() . ".csv";
        header('Content-Type: application/csv; charset=utf-8');
        header("Content-Disposition: attachment; filename=\"$mailsfilename\"");

        $fp = fopen('php://output', 'w+');
        $header = array("Mail No", "Received Date", "Mail Reference", "Date", "Subject", "From", "To", "Copy To", "Division/Subdivision", "Receiver Signature");
        fputcsv($fp, $header);

        foreach ($result as $inm) {

            $subdiv = ($inm['subdivision']) ? " / " . $inm['subdivision'] : '';

            $row = array($inm["mail_no"], date('m/d/Y',
                        strtotime($inm["recieved_date"])), $inm["mail_ref"], date('m/d/Y',
                        strtotime($inm["date"])), $inm["subject"], $inm["from"], $inm["to"], $inm["cc"], $inm["division"] . $subdiv, "");
            fputcsv($fp, $row);
        }
        fclose($fp);
    }

    function inmail_print() {

        require('mc_table.php');
        $pdf = new PDF_MC_Table();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 6);
        //Table with 20 rows and 4 columns
        $pdf->SetWidths(array(12, 14, 15, 14, 30, 16, 16, 16, 16, 14, 14, 15));
        srand(microtime() * 1000000);
        $pdf->Row(array("ID", "Received Date", "Mail Ref", "Date", "Subject", "From", "To", "Copy To", "Division/Subdivision", "Receiver Signature"));

        $v = isset($_GET['in']) ? $_GET['in'] : '';
        $this->load->model('inmails', 'inm');
        $mails = $this->inm->distributed_list($v);

        for ($i = 0; $i < count($mails); $i++) {
            $subdiv = "";
            if ($mails[$i]['subdivision'])
                $subdiv = " / " . $mails[$i]['subdivision'];
            $pdf->Row(array($mails[$i]['mail_no'], $mails[$i]['recieved_date'], $mails[$i]['mail_ref'], $mails[$i]['date'], $mails[$i]['subject'], $mails[$i]['from'], $mails[$i]['to'], $mails[$i]['cc'], $mails[$i]['division'] . $subdiv, ""));
        }
        $pdf->Output();
    }

    function update_password() {
        //echo "<pre>";print_r($this->session->userdata); exit;		
        $login = $this->session->userdata('check_login');

        if (isset($_GET['division_id'])) {
            $division_id = "&division_id=" . $_GET['division_id'];
        } else {
            $division_id = "";
        }

        if (isset($_GET['subdivision_id'])) {
            $subdivision_id = "&subdivision_id=" . $_GET['subdivision_id'];
        } else {
            $subdivision_id = "";
        }

        if ($_POST) {
            $this->load->model('users', 'us');
            $user = $this->us->user_division();
            $user_id = $user->id;
            $old_password = $_REQUEST['old_password'];
            $new_password = $_REQUEST['password'];
            $succmsg = $this->us->change_password($user_id, $old_password,
                    $new_password);
            if ($succmsg == true) {
                $this->session->set_flashdata('message',
                        "Password changed successfully");
                redirect('c=home&m=division_list' . $division_id . $subdivision_id);
            } else {
                $this->session->set_flashdata('message',
                        "Failed to change password");
                redirect('c=home&m=division_list' . $division_id . $subdivision_id);
            }
        }
    }

}
