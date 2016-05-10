<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MY_Controller {
	public function __construct(){
		parent::__construct();
		//$this->output->enable_profiler(TRUE);
		$this->load->helper('export');
		$this->load->library('pagination');
		$data_user = $this->session->userdata('check_login');
		if ( ! $this->session->userdata('check_login') || $data_user['login_type']!=1)
    	{   
            redirect(base_url());
    	}
	}

	function users()
	{ 	
		$user_id=isset($_GET['userid'])?$_GET['userid']:'';
		$this->load->model('users','us');
		if($user_id){
			$this->row=$this->us->edit_user($user_id);
		}
		$this->user_types=$this->us->get_usertype();
		$this->users_list = $this->us->users_list();
		$this->template_view('userform');
	}
	
	function user_insert()
	{
		$login_data=$this->session->userdata('check_login');
		$this->load->model('users','us');
		if($_POST)
		{  
			$data['edit_userid']= $this->input->post('edit_userid')?$this->input->post('edit_userid'):'';
			$this->form_validation->set_rules('firstname','First name','required');
			$this->form_validation->set_rules('email','Email','required');
			$this->form_validation->set_rules('username','User name','required');
			if($data['edit_userid']==''){
				$this->form_validation->set_rules('input_password','Password','required|matches[confirm_password]');
				$this->form_validation->set_rules('confirm_password','Confirm Password','required');
			}
			$this->form_validation->set_rules('user_type','User Type','required');
			if($this->form_validation->run() == true)
			{
				$data['firstname']= $this->input->post('firstname');
				$data['lastname']= $this->input->post('lastname');
				$data['email']= $this->input->post('email');
				$data['mobile_no']= $this->input->post('mobile_no');
				$data['username']= $this->input->post('username');
				if($this->input->post('input_password')!='')
					$data['password'] = md5($this->input->post('input_password'));
				else 
					$data['password'] = $this->input->post('exist_password');
				$data['user_type_id']= $this->input->post('user_type');
				$data['division_id']= $this->input->post('division_id')?$this->input->post('division_id'):0;
				$data['subdivision_id']= $this->input->post('subdivision_id')?$this->input->post('subdivision_id'):0; 
				
				if($data['user_type_id']==2){
					$data['division_id']=0;
					$data['subdivision_id']=0;
				}
				$result = $this->us->user_insert($data);
				if($result)
					redirect('c=admin&m=users');
			}else{
				$this->user_types=$this->us->get_usertype();
				$this->template_view('userform');
			}
		}
	}
	
	function deleteuser()
	{
		$userid = $_POST['user_id'];
		$this->load->model('users','us');
		$result = $this->us->delete_user($userid);
		return $result;
	}
	
	function division()
	{
		$div_id=isset($_GET['division_id'])?$_GET['division_id']:'';
		$this->load->model('division','div');
		if($div_id)
			$this->row=$this->div->division_details($div_id);
		$this->template_view('division');
	}
	
	function division_add()
	{
		$this->load->model('division','div');
		if($_POST)
		{ 
			$this->form_validation->set_rules('txt_division','Division name','required');
			$this->form_validation->set_rules('div_code','Division code','required');
			
			if($this->form_validation->run() == true)
			{
				$data['divid']= $this->input->post('divid');
				$data['division']= $this->input->post('txt_division');
				$data['code']= $this->input->post('div_code');
				$result = $this->div->division_add($data);
				if($result)
					redirect('c=admin&m=division');
			}else{
				$this->template_view('division');
			}
		}
	}
	
	function deletedivision()
	{
		$divid = $_POST['div_id'];
		$this->load->model('division','div');
		$result = $this->div->delete_division($divid);
		return $result;
	}
	
	function subdivision()
	{
		$subdiv_id=isset($_GET['subdivision_id'])?$_GET['subdivision_id']:'';
		$this->load->model('division','div');
		if($subdiv_id)
			$this->row=$this->div->subdivision_details($subdiv_id);
		$this->subdivisions = $this->div->get_allsubdiv();
		$this->template_view('subdivision');
	}
	
	
	//have to change
	function subdivision_add()
	{
		$this->load->model('division','div');
		if($_POST)
		{
			$this->form_validation->set_rules('subdivision','Subdivision name','required');
			$this->form_validation->set_rules('subdiv_code','Subdivision code','required');
			$this->form_validation->set_rules('division_id','Division','required');
			if($this->form_validation->run() == true)
			{
				$data['subdivid']= $this->input->post('subdivid');
				$data['subdivision']= $this->input->post('subdivision');
				$data['code']= $this->input->post('subdiv_code');
				$data['division_id']= $this->input->post('division_id');
				$result = $this->div->subdivision_add($data);
				if($result)
					redirect('c=admin&m=subdivision');
			}else{
				$this->template_view('subdivision');
			}
		}
	}
	
	function deletesubdivision()
	{
		$subdivid = $_POST['subdiv_id'];
		$this->load->model('division','div');
		$result = $this->div->delete_subdivision($subdivid);
		return $result;
	}
	
	function settings()
	{ 
		$success=isset($_GET['success'])?$_GET['success']:'';
		if($_FILES or $_POST){
			$data['site_title']= $this->input->post('site_title');
			$data['copyright_text']= $this->input->post('copyright_text');
			$data['from_address']= $this->input->post('from_address');
			$data['from_address_id']= $this->input->post('from_address_id');
			$data['temporary_addr_count']= $this->input->post('temporary_addr_count');
			$data['no_of_records']= $this->input->post('no_of_records');
			$data['footer_text']= $this->input->post('footer_text');
			$data['mail_no_prefix']= $this->input->post('mail_no_prefix');
			$mail_no_reset = $this->input->post('mail_no_reset');
			if($mail_no_reset =="on")
			$data['mail_no_reset']= 1;
			else
			$data['mail_no_reset']= 0;
			
			if($_FILES['site_logo']['name']!=''){
				$site_logo = explode('.', $_FILES['site_logo']['name']);
				$site_logo[0] = $site_logo[0].time();
				$data['site_logo']= implode('.',$site_logo);
				$target_path = "img/logo/".$data['site_logo'];
				move_uploaded_file($_FILES["site_logo"]["tmp_name"],$target_path);
			}else{
				$data['site_logo']= $this->settings['site_logo'];
			}
			$this->load->model('settings','set');  
			$result = $this->set->add_settings($data);
			if($result){
				redirect('c=admin&m=settings&success=1');
			}
		}else{
			if($success!='')
				$this->success = $success;
			
			$this->template_view('general_settings');
		}
	}
	
	
	function check_mail_no(){
		$mail_no_prefix = $_POST['mail_no_prefix'];
		$this->load->model('settings','set');  
		$result = $this->set->check_mail_no_prefix($mail_no_prefix);
		return $result;
	}
	
	
	function inmailexport(){
		
		
		require 'php-export-data.class.php';
		$exporter = new ExportDataExcel('browser', 'test.xls');
		$exporter->initialize(); 	
		$exporter->addRow(array("Id", "Received Date", "Mail Ref", "Date", "Subject", "From", "To", "Division/Subdivision", "Reciever Sign")); 
		
		$this->load->model('inmails', 'inm');
		$mails = $this->inm->distributed_list('',100,0);
		for($i=0;$i<count($mails);$i++){
			$subdiv = "";
			if($mails[$i]['subdivision'])
				$subdiv = " / ".$mails[$i]['subdivision'];
			$exporter->addRow(array($mails[$i]['mail_no'],$mails[$i]['recieved_date'],$mails[$i]['mail_ref'],$mails[$i]['date'],$mails[$i]['subject'],$mails[$i]['from'],$mails[$i]['to'],$mails[$i]['division'].$subdiv));
		}
		$exporter->finalize(); 		
		exit();
		
			
		}
		
	function outmailexport(){
		$filename = 'Outmails_List.xls'; // The file name you want any resulting file to be called.
		#create an instance of the class
		$xls = new ExportXLS($filename);
		
		$header[] = "Mail No";
		$header[] = "Received Date";
		$header[] = "Mail Reference";
		$header[] = "Date";
		$header[] = "Subject";	
		$header[] = "From";
		$header[] = "To";	
		$header[] = "Division/Subdivision";
		$header[] = "Receiver Signature";
		$xls->addHeader($header);
				
		$this->load->model('outmails','otm');
		$outmails_list = $this->otm->outmail_list();

		$row = array();
		
		foreach($outmails_list as $outmail) {
			
			$row[] = $outmail["mail_no"];
			$row[] = date('m/d/Y',strtotime($outmail["recieved_date"]));
			$row[] = $outmail["mail_ref"];
			$row[] = date('m/d/Y',strtotime($outmail["date"]));
			$row[] = $outmail["subject"];
			$row[] = $outmail["from"]["title"];
			$row[] = $outmail["to"];
			$subdiv= ($outmail['subdivision'])?" / ".$outmail['subdivision']:'';			
			$row[] = $outmail["division"].$subdiv;
			$row[] = "";
			$xls->addRow($row);	
			unset($row);
		}
		$xls->sendFile();			
	}
	
	function todayslistexport($temp_title='',$div){
		
		if($temp_title!='search')
			$filename = 'Search_List_'.$temp_title.'.xls';
		else
			$filename = 'Search_List.xls';
			
		
		$xls = new ExportXLS($filename);
		$header[] = "Mail No";
		$header[] = "Date";
		$header[] = "Subject";
		$header[] = "Mail Reference";
		if($div==1)
			$header[] = "Division";
		$header[] = "From";
		$header[] = "To";
		$xls->addHeader($header);
		
			$this->load->model('inmails','inmails');
			$todays_list = $this->inmails->list_search_page($temp_title);
		
		$row = array();
		
		foreach($todays_list as $search) {
			
			$row[] = $search["mail_no"];
			$row[] = $search["date"];
			$row[] = $search["subject"];
			$row[] = $search["mail_ref"];
			if($div==1)
				$row[] = $search["division"];
			$row[] = $search["from_mails"];
			$row[] = $search["to"];
			$xls->addRow($row);	
			unset($row);
		}
		$xls->sendFile();			
	}
	
	function searchlistexport($name,$div){
		
		$filename ='Search_List_'.$name.'.xls';// The file name you want any resulting file to be called.
		#create an instance of the class
		$xls = new ExportXLS($filename);
		#lets set some headers for top of the spreadsheet
		#
		//$header = "Inmail List"; // single first col text
		//$xls->addHeader($header);
		$header[] = "Mail No";
		$header[] = "Date";
		$header[] = "Subject";
		$header[] = "Mail Reference";
		if($div==1)
			$header[] = "Division";
		$header[] = "From";
		$header[] = "To";
		$xls->addHeader($header);
		
		$this->load->model('inmails','inmails');
		$searchmails_list = $this->inmails->list_search_page($name);
		$row = array();
		
		foreach($searchmails_list as $searchmail) {
			
			$row[] = $searchmail["mail_no"];
			$row[] = $searchmail["date"];
			$row[] = $searchmail["subject"];
			$row[] = $searchmail["mail_ref"];
			if($div==1)
				$row[] = $searchmail["division"];
			$row[] = $searchmail["from_mails"];
			$row[] = $searchmail["to"];
			$xls->addRow($row);	
			unset($row);
		}
		$xls->sendFile();			
	}
	
	
	function database_download()
	{	
		$CI = &get_instance();
		$CI->load->database();
		$db_host = $this->db->hostname;
		$db_name  = explode(":",$db_host);
		 
		$files = $db_name[1];
		//$files = 'application/db/maitra.sqlite';
		$zipname = 'Maitra_db_'.date('Ymd His').'_1935.zip';
		$zip = new ZipArchive;
		$zip->open($zipname, ZipArchive::CREATE);
		
		$zip->addFile($files);
		
		$zip->close();
		
		header('Content-Type: application/zip');
		header('Content-disposition: attachment; filename='.$zipname);
		header('Content-Length: ' . filesize($zipname));
		readfile($zipname);
		exit;
	}
	
	function activities()
	{
		$this->load->model('logs','lg');
		$this->activities = $this->lg->fetch_logs();
		$this->template_view('activity_log');
	}
	
	function manage_address_book()
	{
		$this->load->model('settings','set');
		$search_addr = $this->input->post('address');//SQLite3::escapeString
		$suffix = "";
		if($search_addr!='')
		$suffix = "&address=".$search_addr;
		
		$config['total_rows'] = $this->set->address_total_rows();
		$config['per_page'] =$this->settings['no_of_records'];
		$config['base_url'] = site_url().'?c=admin&m=manage_address_book'.$suffix;
		$config['uri_segment'] = 3;
		$page = 0;
		if(isset($_GET['per_page']) && $_GET['per_page']!=0){
			$config['base_url'] = site_url().'?c=admin&m=manage_address_book'.$suffix;
			$page = $_GET['per_page']; 
		}
		
		$this->pagination->initialize($config);
		
		$this->address = $this->set->address_book($config["per_page"], $page);
		$this->template_view('admin/address_book');	
	}
	
	function delete_address()
	{
		$addr_id = $_POST['addr_id'];
		$this->load->model('settings','set');
		$result = $this->set->delete_address($addr_id);
		return $result;
	}
	
	function address_form()
	{
		$addr_id=isset($_REQUEST['addr_id'])?$_REQUEST['addr_id']:'';
		if($addr_id){
			$this->load->model('settings','set');
			$this->address = $this->set->get_address($addr_id);
		}
		$this->template_view('admin/address_form');	
	}
	
	function add_address()
	{
		if($_POST)
		{
			//$this->form_validation->set_rules('name','Name','required');
			$this->form_validation->set_rules('alias','Alias','required');
			$this->form_validation->set_rules('city','City','required');
			$this->form_validation->set_rules('pincode','Pincode','required');
			$this->form_validation->set_rules('address_type','Address Type','required');
			
			if($this->form_validation->run() == true)
			{
				$data['addr_id']=$this->input->post('addr_id');
				$data['name']=$this->input->post('name');	
				//$data['address1']=SQLite3::escapeString($this->input->post('address1'));
				//$data['address2']=SQLite3::escapeString($this->input->post('address2'));
				//$data['address3']=SQLite3::escapeString($this->input->post('address3'));
				$data['address1']=$this->input->post('address1');
				$data['address2']=$this->input->post('address2');
				$data['address3']=$this->input->post('address3');

				$data['title']=$this->input->post('alias');
				$data['city']=$this->input->post('city');
				$data['state']=$this->input->post('state');
				$data['country']=$this->input->post('country');
				$data['pincode']=$this->input->post('pincode');
				$data['type']=$this->input->post('address_type');
				
				$search_addr=$this->input->post('search_addr');
				
				$this->load->model('settings','set');
				$result = $this->set->add_address($data);
				if($result){
					$suffix = "";
					if($search_addr!="")
					$suffix = "&address=".$search_addr;
					redirect('c=admin&m=manage_address_book'.$suffix);
				}
			}else{
				$this->template_view('admin/address_form');
			}
		}
	}
	
	function delete_multiaddress()
	{
		$addrids=$_POST['addrids'];
		$this->load->model('settings','set');
		$result = $this->set->delete_multiaddress($addrids);
		return $result;
	}
	
	function manage_inmails()
	{
		//print_r($_REQUEST);
		$this->load->model('inmails','inm');
		if(isset($_REQUEST['mail_number'])){ 
			$title = trim($_REQUEST['mail_number']); 
			$suffix= "&mail_number=".$title; 
		}else{ 
			$title="";
			$suffix="";
		}
		$config['total_rows'] = $this->inm->manage_inmail_total_rows($title,'','','');
		$config['per_page'] =$this->settings['no_of_records'];
		$config['base_url'] = site_url().'?c=admin&m=manage_inmails'.$suffix;
		$config['uri_segment'] = 3;
		
		$page = 0;
		if(isset($_GET['per_page']) && $_GET['per_page']!=0){
			$config['base_url'] = site_url().'?c=admin&m=manage_inmails'.$suffix;
			$page = $_GET['per_page']; 
		}		
		$this->pagination->initialize($config);
		$this->distributed_list = $this->inm->manage_distributed_list($title,'',$config["per_page"], $page);	
		$this->template_view('admin/inmails');
	}
	
	function manage_outmails()
	{
		$this->load->model('outmails','otm');
		if(isset($_REQUEST['mail_number'])){
			$title = trim($_REQUEST['mail_number']);
			$suffix= "&mail_number=".$title;
		}else{
			$title="";
			$suffix="";
		}
		$config['total_rows'] = $this->otm->manage_outmail_total_rows($title,'','','');
		$config['per_page'] =$this->settings['no_of_records'];
		$config['base_url'] = site_url().'?c=admin&m=manage_outmails'.$suffix;
		$config['uri_segment'] = 3;
		
		$page = 0;
		if(isset($_GET['per_page']) && $_GET['per_page']!=0){
			$config['base_url'] = site_url().'?c=admin&m=manage_outmails'.$suffix;
			$page = $_GET['per_page']; 
		}
		
		$this->pagination->initialize($config);
		
		$this->outmail_list = $this->otm->manage_outmail_list($title,'',$config["per_page"], $page);
		$this->template_view('admin/outmails');
	}
	
	function delete_single_mail()
	{
		$mail_id = $_POST['mail_id'];
		$this->load->model('settings','set');
		$result = $this->set->delete_single_mail($mail_id);
		return $result;
	}
	
	function delete_multiplemails()
	{
		$mailids=$_POST['mailids'];
		$this->load->model('settings','set');
		$result = $this->set->delete_multiplemails($mailids);
		return $result;
	}
	
	function delete_logs()
	{
		$this->load->model('settings','set');
		$result = $this->set->delete_logs();
		return $result;
	}
	
	function check_username()
	{
		$username = $_POST['username'];
		$this->load->model('users','us');
		$result = $this->us->check_username($username);
		echo $result;
	}
	
	function purge_records()
	{
		$success=isset($_GET['success'])?$_GET['success']:'';
		$purgesuccess=isset($_GET['purgesuccess'])?$_GET['purgesuccess']:'';
		if($_POST){
			$data['purge_records']= $this->input->post('purge_records');
			
			$this->load->model('settings','set');  
			$result = $this->set->purge_settings($data);
			if($result){
				redirect('c=admin&m=purge_records&success=1');
			}
		}else{
			if($success!='')
				$this->success = $success;
				
			if($purgesuccess!='')
				$this->purgesuccess = $purgesuccess;		
			}
			$this->template_view('purge_records');
	}
	
		
	function purging()
	{ 		
		$purge_records = $_POST['purge_records'];		
		$query = $this->db->query('SELECT * FROM mails where status=2 ORDER BY id ASC');
		$count = $query->num_rows();
		if($purge_records>$count){
			echo "Not able to purge, Less than ".$count." records only available";			
		} 
		
		if($purge_records<$count)
		{	
			//echo "SELECT id FROM mails where status=2 ORDER BY id ASC LIMIT 0,".$purge_records.""; exit;		
			$query = $this->db->query("SELECT id FROM mails where status=2 ORDER BY id ASC LIMIT 0,".$purge_records.""); 
			$result = $query->row_array();	
			$res_arr = $query->result_array;	
			//print_r($res_arr); 	exit;		
			foreach($res_arr as $subArray){
				foreach($subArray as $val){
					$newArray[] = $val;
				}
			}				
			$implode_val = implode(',',$newArray); //echo $implode_val; 
			//echo "DELETE FROM mails where id IN (".$implode_val.")"; exit;			
			$this->db->query("DELETE FROM mails where id IN (".$implode_val.")");// exit;			
			echo "Records purged successfully";				
		}
			 
	}	
	
}
