<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inmail extends MY_Controller {
	
	public function __construct(){
		parent::__construct();
		//$this->output->enable_profiler(TRUE);
		$data_user = $this->session->userdata('check_login');
		if ( ! $this->session->userdata('check_login'))
    	{   
            redirect(base_url());
    	}
		$this->load->library('pagination');
	}
	
	function inmailentry()
	{
		$mailid=isset($_GET['mailid'])?$_GET['mailid']:'';
		$ref=isset($_GET['ref'])?$_GET['ref']:'';
		
		$data_user = $this->session->userdata('check_login');
		$this->load->model('inmails','inm');
		if ( $data_user['login_type']==3)
    	{   
            redirect(base_url());
    	}
		if($mailid){
				$this->row=$this->inm->edit_staging($mailid);
			}
		
		/* Mail number Prefix*/
		$current_settings  = $this->settings;
		$prefix            = $current_settings['mail_no_prefix'];
		if(!$prefix) {
//                    $prefix            = date('y');
                    $prefix = 14; //Default value
                }
		
		if($current_settings['mail_no_reset'] == 0){
			$mail_no       = $this->inm->get_last_mailno(1);
			$mail_no       = $mail_no + 1;
		}else{
			$mail_no       = 1;
		}
		$this->mail_number =  $prefix."-".str_pad($mail_no, 5, '0', STR_PAD_LEFT);
		/* Mail number Prefix*/
		
		$config['total_rows'] = $this->inm->inmail_staging_rows();
		$config['per_page'] =$this->settings['no_of_records'];	
		$config['base_url'] = site_url().'?c=inmail&m=inmailentry';
		$config['uri_segment'] = 3;
		$page = 0;
		if(isset($_GET['per_page']) && $_GET['per_page']!=0){
			$page = $_GET['per_page']; 
		}
		
		$this->pagination->initialize($config);
		
		$this->load->model('inmails','inm');
		$this->staging_list = $this->inm->staging_list($config["per_page"], $page);
		$this->ref = $ref;
		$this->template_view('inmailentry');
	}
	
	function mailaddr_check($str=''){
		if(isset($str) && $str!='|')
			return true;
		else
			return false;
	}
	
	function inmailentry_insert()
	{
		$data_user = $this->session->userdata('check_login');
		if ( $data_user['login_type']==3)
    	{   
            redirect(base_url());
    	}
		$login_data=$this->session->userdata('check_login');		
		if($_POST)
		{	
			$edit_mailid=$this->input->post('edit_mailid');			
			$this->form_validation->set_rules('mailref','Mail Ref','required');
			$this->form_validation->set_rules('from_new','From','required','callback_mailaddr_check');
			$this->form_validation->set_rules('to_new','To','required','callback_mailaddr_check');
			$this->form_validation->set_rules('subject','Subject','required');
			$this->form_validation->set_rules('date','Date','required');
			$this->form_validation->set_rules('recieved_date','Received Date','required');
			
			if($this->form_validation->run() == true)
				$data['validation']=($this->input->post('assigned_division'))?2:1;
			else
				$data['validation']=0;
			//echo $data['validation']; die;
			$data['mailno']= $this->input->post('mailno');
			$data['mail_ref']= $this->input->post('mailref');
			$data['subject']= SQLite3::escapeString($this->input->post('subject'));
			$data['date']= $this->input->post('date');
			$data['recieved_date']=$this->input->post('recieved_date');	
			$data['division_id']=$this->input->post('assigned_division');
			$data['subdivision_id']=$this->input->post('assigned_subdivision');	
			$data['comments']= $this->input->post('comments');		
			$language=$this->input->post('hindi');
			if($language=='on')
				$data['language']="Hindi";
			else
				$data['language']="English";
			
			$data['status']=($this->input->post('mail_status'))?$this->input->post('mail_status'):0;      //staging
			$data['type']=1;	//inmail
			$data['created_on']=($this->input->post('created_on'))?$this->input->post('created_on'):date('d-m-Y H:i:s');			
			$data['created_by']=($this->input->post('created_by'))?$this->input->post('created_by'):$login_data['login_id'];
			$data['updated_on']=($edit_mailid!='')?date('d-m-Y H:i:s'):$this->input->post('updated_on');
			$data['updated_by']=($edit_mailid!='')?$login_data['login_id']:$this->input->post('updated_by');
			
			$data['from']= $this->input->post('from');
			$data['from_new']= $this->input->post('from_new');
			$data['to']= $this->input->post('to');
			$data['to_new']= $this->input->post('to_new');
			$data['cc']= $this->input->post('cc');
			$data['cc_new']= $this->input->post('cc_new');	
			$data['search_mail_no']= $this->input->post('search_mail_no');			
			
			$this->load->model('inmails','inm');		
			$result = $this->inm->inmail_insert($data,$edit_mailid);	
			if($result){
				if(isset($_REQUEST['ref'])){					
					if($_REQUEST['ref']!='') {
						$search = "";
						if($data['search_mail_no']!='')
						$search = "&mail_number=".$data['search_mail_no'];
						
						redirect('c=admin&m=manage_inmails'.$search);
						} else {
							redirect('c=inmail&m=inmailentry');
						}
				}
				//redirect('c=inmail&m=inmail_staging');
			}
		}
	}
	
	function inmail_staging_list($per_page, $start)
	{
		$this->load->model('inmails','inm');
		$this->staging_list = $this->inm->staging_list($per_page, $start);
	}
	
	function inmail_staging(){
		$config['total_rows'] = $this->inm->inmail_staging_rows();
		$config['per_page'] =10;
		$config['base_url'] = site_url().'?c=inmail&m=inmail_staging';
		$config['uri_segment'] = 3;
		$page = 0;
		if(isset($_GET['per_page']) && $_GET['per_page']!=0){
			$config['base_url'] = site_url().'?c=inmail&m=inmail_staging&per_page=';
			$page = $_GET['per_page']; 
		}
		
		$this->pagination->initialize($config);
		
		$this->load->model('inmails','inm');
		$this->staging_list = $this->inm->staging_list($config["per_page"], $page);
		$this->template_view('inmail_staging');
	}
	
	function create_distributed_list()
	{
		$mailids=$_POST['mailids'];
		$this->load->model('inmails','inm');
		$result = $this->inm->create_distributed_list($mailids);
		return $result;
	}
	
	function insert_address()
	{
		
		if($_POST)
		{
				$data['name']=$this->input->post('name');	
				$data['address1']=SQLite3::escapeString($this->input->post('address1'));
				$data['address2']=SQLite3::escapeString($this->input->post('address2'));
				$data['address3']=SQLite3::escapeString($this->input->post('address3'));
				$data['title']=$this->input->post('title');
				$data['city']=$this->input->post('city');
				$data['state']=$this->input->post('state');
				$data['country']=$this->input->post('country');
				$data['pincode']=$this->input->post('pincode');
				$data['type']= 2;
				$this->load->model('inmails','cm',TRUE);
				$res = $this->cm->add_address($data);
				$res = json_encode($res);
				print_r($res);
		}
		
	}
	
	function fetch_title()
	{
		$title = $_POST['title'];		
		$this->load->model('inmails','auto');
		$result = $this->auto->fetch_title($title);
		$response = json_encode($result);
		//print_r($response);
		if($result)
		{
			echo true;
		}
		echo false;
		
	}
	
	function fetch_address_id()
	{
		$title = $_POST['title'];		
		$this->load->model('inmails','auto');
		$result = $this->auto->fetch_address_id($title);
		$response = json_encode($result);
		print_r($response);
			
	}
	
	function fetch_email()
	{		
		$this->load->model('inmails','auto');
		$result = $this->auto->fetch_mail();
		$response = json_encode($result);
		print_r($response);	
	}
	
	function fetchsubdivision()
	{
		$div_id=$_GET['div_id'];
		$this->load->model('division','div');
		$result = $this->div->fetchsubdivision($div_id);
		print_r($result);		
	}
	
	function subdiv_for_editform()
	{
		$subdiv_id=$_GET['subdiv_id'];
		$this->load->model('division','div');
		$result = $this->div->subdiv_for_editform($subdiv_id);
		print_r($result);		
	}
	
	function inmailview()
	{
		$mailid = $_GET['mailid'];
		$this->load->model('inmails','inm');
		$this->inmail_details= $this->inm->inmailview($mailid);
		
		$this->load->model('comments','cm');
		$this->comments= $this->cm->fetch_comments($mailid);
		
		$this->load->model('users','us');
		$this->users= $this->us->fetch_users();
		$this->template_view('inmailview');
	}
	
	function insert_comments()
	{
		$comments=$_POST['comments'];
		$mailid=$_POST['mailid'];
		$this->load->model('comments','cm');
		$result = $this->cm->insert_comments($comments,$mailid);
		return $result;
	}
	
	function email_details()
	{
		$id = isset($_GET['mailid'])?$_GET['mailid']:'';
		$this->load->model('inmails','inm');
		$data['email_details'] = $this->inm->email_details($id);
		$this->load->view('email_details',$data);
	}
	
	function check_user()
	{
		$data['div_id']=$_POST['div_id'];
		$data['subdiv_id']=$_POST['subdiv_id'];
		//$data['login_id']=$_POST['login_id'];
		$this->load->model('inmails','inm');
		$result = $this->inm->check_user($data);
		return $result;
		
	}
	
	function edit_division()
	{	
		$data['mail_id']=$_POST['mail_id'];
		$data['mail_no']=$_POST['mail_no'];
		$data['div_id']=$_POST['div_id'];
		$data['subdiv_id']=$_POST['subdiv_id'];
		$data['action_date']=$_POST['action_date'];
		$data['close_date']=$_POST['close_date'];
		$this->load->model('inmails','inm');
		$result = $this->inm->edit_division($data);
		return $result;
	}
	
	function close_mail()
	{
		$data['mail_id']=$_POST['mail_id'];
		$this->load->model('inmails','inm');
		$result = $this->inm->close_mail($data);
		return $result;
	}
	
	function delete_staging_list()
	{
		$mailids=$_POST['mailids'];
		$this->load->model('inmails','inm');
		$result = $this->inm->delete_staging_list($mailids);
		return $result;
	}
	
	function subject_autocomp()
	{
		$data['subject'] = $_POST['subject'];
		$this->load->model('inmails','inm');
		$result = $this->inm->subject_autocomp($data);
		print_r($result);
	}
	
	function from_autocomp()
	{
		$data['from'] = $_POST['from'];
		$this->load->model('inmails','inm');
		$result = $this->inm->from_autocomp($data);
		print_r($result);
	}
	
	function print_mails(){
		$this->load->model('inmails','inm');
		$data['distributed_list'] = $this->inm->distributed_list('',20,0);
		$this->load->view('print_mails',$data);
	}
	
	function inmail_xls(){
		$v = isset($_GET['in'])?$_GET['in']:'';
		$this->load->model('export','expo');		
		$inmails = $this->expo->inmails($v,1000,0);

		$mailsfilename = "Inmails.xls";
		header("Content-Disposition: attachment; filename=\"$mailsfilename\"");
		header("Content-Type: application/vnd.ms-excel");
		
		$header = array( "Mail No", "Received Date", "Mail Reference", "Date", "Subject", "From", "To", "Division/Subdivision", "Receiver Signature");
		echo implode("\t", str_replace(',', ' ', $header)) . ", \n";
		
		foreach($inmails as $inm){	
			$subdiv= ($inm['subdivision'])?" / ".$inm['subdivision']:'';
		
			$row = array($inm["mail_no"], date('m/d/Y',strtotime($inm["recieved_date"])), $inm["mail_ref"],date('m/d/Y',strtotime($inm["date"])),$inm["subject"],$inm["from"], $inm["to"], $inm["division"].$subdiv, "");		
			echo implode("\t", str_replace(',', ' ', $row)) . ", \n";
			
		}
	}
	
	function inmail_csv(){
		/*$v = isset($_GET['in'])?$_GET['in']:'';
		$this->load->model('export','expo');		
		$inmails = $this->expo->inmails($v);
		
		//header('Location: ../Inmails.csv');
		header('Content-Type: application/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=Inmails.csv');

		$fp = fopen('php://output', 'w+');
		$header = array( "Mail No", "Received Date", "Mail Reference", "Date", "Subject", "From", "To", "Division/Subdivision", "Receiver Signature");
		fputcsv($fp, $header);
		
		foreach($inmails as $inm){
			$subdiv= ($inm['subdivision'])?" / ".$inm['subdivision']:'';
			$row = array($inm["mail_no"], date('m/d/Y',strtotime($inm["recieved_date"])), $inm["mail_ref"],date('m/d/Y',strtotime($inm["date"])),$inm["subject"],$inm["from"], $inm["to"], $inm["division"].$subdiv, "");
			fputcsv($fp, $row);
         	//fputcsv($fp, array_values($sub));
   		}
		fclose($fp);*/
		
		$v = isset($_GET['in'])?$_GET['in']:'';
		$this->load->model('export','expo');		
		$inmails = $this->expo->inmails($v,2000,0); 
	}
	
}
