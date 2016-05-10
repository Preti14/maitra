<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends MY_Controller {
	
	public function __construct(){
		parent::__construct();
		//$this->output->enable_profiler(TRUE);
		$this->load->library('pagination');
		if ( ! $this->session->userdata('check_login'))
    	{   
            redirect(base_url());
    	}
	}
	
	
	function fetch_division()
	{
		$this->load->model('division','div');
		$result = $this->div->fetch_division_list();
		$response = json_encode($result);
		print_r($response);
	}
	
	function fetch_all_temp()
	{
		$this->load->model('division','div');
		
		if(isset($_REQUEST['tid']))
		{
			$tid = $_REQUEST['tid'];
			//alert($tid);
			$result = $this->div->fetch_division_template($tid);
		}  
		$response = json_encode($result);
		print_r($response);
	}
	
	function fetch_subdivision()
	{
		$this->load->model('division','div');
		$result = $this->div->fetch_subdivision_list();
		$response = json_encode($result);
		print_r($response);
	}
	
	function select_rel_div()
	{		
		$id = $_GET['id'];
		$this->load->model('division','div');
		$result = $this->div->select_rel_div($id);
		$response = json_encode($result);
		print_r($response);	
		
	}
	
	function todays_listpage()
	{	
	
		
		$this->load->model('inmails','inmails');
		
		$template_name = urldecode($_REQUEST['temp_name']);	
		$arr = $this->inmails->get_searchterms($template_name);//print_r($arr);die;
		$data['type']           = $arr['type'];
		$data['division']       = $arr['division'];
		$data['with_comm']       = $arr['with_comm'];
		$data['subdivision']    = $arr['subdivision'];
		$login = $this->session->userdata('check_login');
		if($login['login_type']!=1){
			$div_data = $this->session->userdata('div_data');
			//$data['division']       = $div_data['division_id'];	
			
		}
		if($arr['subject']!=''){
			$data['search_by']=3;
			$data['search_by_txt'] = $arr['subject'];
		}else if($arr['from_mails']!=''){
			$data['search_by']=1;
			$data['search_by_txt'] = $arr['from_mails'];
		}else if($arr['to']!=''){
			$data['search_by']=2;
			$data['search_by_txt'] = $arr['to'];
		}elseif($arr['comments']!=''){
			$data['search_by']=4;
			$data['search_by_txt'] = $arr['comments'];
		}elseif($arr['copy_to']!=''){
			$data['search_by']=5;
			$data['search_by_txt'] = $arr['copy_to'];
		}elseif($arr['mail_ref']!=''){
			$data['search_by']=6;
			$data['search_by_txt'] = $arr['mail_ref'];
		}
		
		$data['to_datepicker']     = $arr['to_date'];
		$data['from_datepicker']   = $arr['from_date'];
		$data['status']            = $arr['status'];
		$data['template_id']       = $arr['id'];
		$data['user_id']           = $arr['user_id'];
		$data['save_template_txt'] = $template_name;
	
		$this->session->set_userdata('temp_data',$data);
		$this->template_name = $template_name;	
		
		$suffix="";
		if(isset($template_name))
		$suffix = '&temp_name='.$template_name;
		$temp_data = $this->session->userdata('temp_data');
		
		$config['total_rows'] = $this->inmails->search_mails_count($temp_data);
		$config['per_page'] =20;
		$config['base_url'] = site_url().'?c=search&m=todays_listpage'.$suffix;
		$config['uri_segment'] = 3;
		$page = 0;
		if(isset($_GET['per_page']) && $_GET['per_page']!=0){
			$page = $_GET['per_page']; 
		}
		$this->pagination->initialize($config);
		$result = $this->inmails->search_mails($temp_data,$config["per_page"], $page);
		$this->total_rows = $config['total_rows']; 
		$this->response = ($result);		
		$this->template_view('todays_list');
	}	
	
	function search_mails(){	
		
		if(isset($_REQUEST['division_id']))	{			
			$division_url = "&division_id=".$_REQUEST['division_id']; 
			} else { $division_url =""; }
			
		if(isset($_REQUEST['subdivision_id']))	{			
			$subdivision_url = "&subdivision_id=".$_REQUEST['subdivision_id']; 
			} else { $subdivision_url=""; }
		
		if($_POST){
			$session_values = $this->session->userdata('check_login');
			$user_id = $session_values['login_id'];
			$data['type'] = $_POST['inmail_outmail'];			
			$data['division'] = $_POST['division'];				
			$data['subdivision'] = $_POST['subdivision'];				
			$data['search_by'] = $_POST['search_by']; 					
			$data['search_by_txt'] = $_POST['search_by_txt'];				 						
			$data['to_datepicker'] = $_POST['to_datepicker'];
			$data['from_datepicker'] = $_POST['from_datepicker'];
			$data['status'] = isset($_POST['m_status'])? $_POST['m_status']: 0;
			$data['with_comm'] = isset($_POST['with_comm'])? $_POST['with_comm']: 0;
			
			if(isset($_POST['template_id'])){
				$data['template_id'] = $_POST['template_id'];
			}
			$data['user_id'] = $user_id ;
			$data['save_template_txt'] = $_POST['save_template_txt'];
			$this->session->set_userdata('search_mails',$data);
		
		}
			
		
		$this->load->model('inmails','inm');
		if(isset($data['save_template_txt']) && $data['save_template_txt']!=''){
			$create_temp      = $this->inm->insert_search_history($data);	
		}else{
			$data['save_template_txt']= '';	
		}
		
		$search_mails = $this->session->userdata('search_mails');		
		
		$config['total_rows'] = $this->inm->search_mails_count($search_mails);	
		
		$config['per_page'] =20;
		$config['base_url'] = site_url().'?c=search&m=search_mails'.$division_url.$subdivision_url;
		$config['uri_segment'] = 3;
		$page = 0;
		if(isset($_GET['per_page']) && $_GET['per_page']!=0){
			//$config['base_url'] = site_url().'?c=search&m=search_mails'.$division_url.$subdivision_url.'&per_page=';
			$page = $_GET['per_page']; 
		}
		$this->pagination->initialize($config);
		//echo "<pre>";print_r($_REQUEST); die;	
		$result           = $this->inm->search_mails($search_mails,$config["per_page"], $page);
		$this->total_rows = $config['total_rows']; 
		$this->response   = ($result);
		$this->template_name = $data['save_template_txt'];	
		if($this->template_name != "")
		{
			redirect('c=search&m=todays_listpage&temp_name='.$this->template_name);	
		} 
		 
		$this->template_view('todays_list');
		
	}
	
	/*function insert_search_history()
	{	//echo "<pre>"; print_r($_POST); die;
		$session_values = $this->session->userdata('check_login');
		$user_id = $session_values['login_id']; 
		$data['type'] = $_POST['inmail_outmail'];
		$data['division'] = $_POST['division'];
		$data['subdivision'] = $_POST['subdivision'];
		$data['save_template_txt'] =$_POST['save_template_txt'];
		$data['search_by'] = $_POST['search_by'];
		$data['search_by_txt'] = $_POST['search_by_txt'];
	    $data['to_datepicker'] = $_POST['to_datepicker'];
		$data['from_datepicker'] = $_POST['from_datepicker'];
		$data['template_id'] = $_POST['template_id'];
		$data['user_id'] = $user_id ;
		$data['status'] = $_POST['m_status'];
		$this->load->model('inmails','inmails');
		
		$result = $this->inmails->insert_search_history($data);
		if($result){

			
			$this->search_mails($data,$data['save_template_txt']);
		}
	}*/
	
	function search_subject()
	{	
		$arr['txt'] =  isset($_REQUEST['query'])?$_REQUEST['query']:'';
		$arr['url'] =  isset($_REQUEST['uri_link'])?$_REQUEST['uri_link']:'';
		$suffix     =  "";
		$this->load->model('inmails','inm');
	
		if($arr['txt']!='' && $arr['url']!=''){
			$this->session->set_userdata('search_subj',$arr);
		}
		$search_subj          = $this->session->userdata('search_subj');
		if($search_subj){
		$suffix               = '&'.http_build_query($search_subj, '', "&");}
		$config['page_query_string'] = TRUE;
		$config['total_rows'] = $this->inm->search_total_rows($search_subj['txt'],$search_subj['url']);
		$config['per_page']   = 20;	
		$config['uri_segment'] = 3;
		 
		if(isset($_GET['per_page']) && $_GET['per_page']!=0){
			 $config['base_url']= site_url().'?c=search&m=search_subject'.$suffix.'&per_page=';
			$page = $_GET['per_page']; 
		}else{
			 $config['base_url']= site_url().'?c=search&m=search_subject'.$suffix;			
			 $page = 0;
		}
		
		$this->pagination->initialize($config);
		
		$this->distributed_list = $this->inm->search_subject($search_subj['txt'],$search_subj['url'],$config["per_page"], $page);		
		$this->search = 1;
		$this->total_rows=$config['total_rows'];
		$this->template_view('dashboard');
		
	}
	
	
	
	
	
	function search_mailaddress()
	{	
		$arr['txt'] =  isset($_REQUEST['query'])? trim($_REQUEST['query']):'';
		$arr['url'] =  isset($_REQUEST['uri_link'])?$_REQUEST['uri_link']:'';
		$suffix     =  "";
		$this->load->model('inmails','inm');
		
		//print_r($this->session->userdata);		
		//echo $arr['url'];
	
		if($arr['txt']!='' && $arr['url']!=''){
			$this->session->set_userdata('search_subj',$arr);
		}
		$search_subj          = $this->session->userdata('search_subj');
		if($search_subj){
		$suffix               = '&'.http_build_query($search_subj, '', "&");}
		$config['page_query_string'] = TRUE;
		$config['total_rows'] = $this->inm->search_mailaddress_total($search_subj['txt'],$search_subj['url']);
		$config['per_page']   = 20;	
		$config['uri_segment'] = 3;
		 
		if(isset($_GET['per_page']) && $_GET['per_page']!=0){
			 $config['base_url']= site_url().'?c=search&m=search_mailaddress'.$suffix.'&per_page=';
			$page = $_GET['per_page']; 
		}else{
			 $config['base_url']= site_url().'?c=search&m=search_mailaddress'.$suffix;			
			 $page = 0;
		}
		
		$this->pagination->initialize($config);
		
		$this->distributed_list = $this->inm->search_mailaddress($search_subj['txt'],$search_subj['url'],$config["per_page"], $page);		
		$this->search = 1;
		$this->total_rows=$config['total_rows'];
		$this->template_view('dashboard');
		
	}
	
	
	
	function search_list_download()
	{
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment;filename=search_results.xls");
		header("Content-Transfer-Encoding: binary ");
		
		$file = 'uploads/search_results.xls';
		echo file_get_contents($file, true);
		
	}
	
	function get_templates()
	{
		$this->load->model('inmails','inm');
		$response = $this->inm->get_templates();
		print_r($response);
		return $response;
	}
	
	function delete_template()
	{
		$template_id = $_POST['template_id'];
		$this->load->model('inmails','inm');
		$response = $this->inm->delete_template($template_id);
		return $response;
	}
	
	function generate_xls(){
		$search_mails = $this->session->userdata('search_mails');
		$this->load->model('inmails','inm');
		$result = $this->inm->search_mails($search_mails);
		
		$mailsfilename = "Search mails".time().".xls";
		header("Content-Disposition: attachment; filename=\"$mailsfilename\"");
		header("Content-Type: application/vnd.ms-excel");
		
		
		$header = array( "Mail No", "Received Date", "Mail Reference", "Date", "Subject", "From", "To","Copy To", "Division/Subdivision","Action Date","Close Date", "Comments");
		echo implode("\t", str_replace(',', ' ', $header)) . " \n";
		
		foreach($result as $inm){	
			$subdiv= ($inm['subdivision'])?" / ".$inm['subdivision']:'';
			
			if(!empty($inm['comments'][0]['cmt'])){
					$inm['comments'][0]['cmt'] = "## ".$inm['comments'][0]['cmt'];	
			}
		
			$row = array($inm["mail_no"], date('Y/m/d',strtotime($inm["recieved_date"])), $inm["mail_ref"],date('Y/m/d',strtotime($inm["date"])),$inm["subject"],$inm["from"], $inm["to"], $inm["cc"], $inm["division"].$subdiv,$inm['action_date'],$inm['close_date'],$inm['comments'][0]['cmt']);		
			echo implode("\t", str_replace(',', ' ', $row)) . " \n";
			
		}
		
	}
	
	function generate_csv(){
		
		$search_mails = $this->session->userdata('search_mails');
		$this->load->model('inmails','inm');
		$result = $this->inm->search_mails($search_mails);
	
		$mailsfilename = "Search mails".time().".csv";
		header('Content-Type: application/csv; charset=utf-8');
		header("Content-Disposition: attachment; filename=\"$mailsfilename\"");

		$fp = fopen('php://output', 'w+');
		
		$header = array( "Mail No", "Received Date", "Mail Reference", "Date", "Subject", "From", "To", "Division/Subdivision", "Receiver Signature");
		fputcsv($fp, $header);
		
		foreach($result as $inm){	
			$subdiv= ($inm['subdivision'])?" / ".$inm['subdivision']:'';
		
			$row = array($inm["mail_no"], date('Y/m/d',strtotime($inm["recieved_date"])), $inm["mail_ref"],date('Y/m/d',strtotime($inm["date"])),$inm["subject"],$inm["from"], $inm["to"], $inm["division"].$subdiv, "");		
			fputcsv($fp, $row);
			
		}
		fclose($fp);
	}
	
	function printpdf()
	{
		require('mc_table.php');
		$pdf=new PDF_MC_Table();
		$pdf->SetRightMargin(0);
		$pdf->SetLeftMargin(10);
		$pdf->AddPage();
		$pdf->SetFont('Arial','',7);
		$pdf->SetDisplayMode(100);
		 
		//Table with 10 rows
		$pdf->SetWidths(array(63,15,15,15,15,25,15,25));
		srand(microtime()*1000000);
		
		$login = $this->session->userdata('check_login');
		
		if($login['login_type']==2){			
			$sig_comm = "Receiver Signature";			
		}else{
			$sig_comm = "Comments";
		}
		
		$pdf->Row(array("Mail Details","Date","Received Date","From","To","Division/Subdivision","Action Date",$sig_comm));	
		//$search_mails = $this->session->userdata('temp_data');		
		
		$search_mails = $this->session->userdata('search_mails');
				
		$this->load->model('inmails','inm');
		$mails = $this->inm->search_mails($search_mails);		
		
			for($i=0;$i<count($mails);$i++)
			{
				$subdiv = "";
				if($mails[$i]['subdivision'])
					$subdiv = " / ".$mails[$i]['subdivision'];
					
				if(!empty($mails[$i]['comments'][0]['cmt'])){
					$mails[$i]['comments'][0]['cmt'] = "## ".$mails[$i]['comments'][0]['cmt'];	
				}
				
				$same_field = "ID : ".$mails[$i]['mail_no']."\nMail Ref : ".$mails[$i]['mail_ref']."\nSubject : ".$mails[$i]['subject'];		
					
				if($login['login_type']==2){			
					$comm_sig = "";			
				}else{
					$comm_sig = $mails[$i]['comments'][0]['cmt'];
				}	
					
				$pdf->Row(array($same_field, date('d/m/Y',strtotime($mails[$i]['date'])),  date('d/m/Y',strtotime($mails[$i]['recieved_date'])),$mails[$i]['from'],$mails[$i]['to'],$mails[$i]['division'].$subdiv,$mails[$i]['action_date'],$comm_sig));
				
			}
			$pdf->Output();
			
		}
		
	function mail_xls(){
		$search_subj = $this->session->userdata('search_subj');
		$this->load->model('inmails','inm');
		$result = $this->inm->search_subject($search_subj['txt'],$search_subj['url']);
	
		$mailsfilename = "Search mails".time().".xls";
		header("Content-Disposition: attachment; filename=\"$mailsfilename\"");
		header("Content-Type: application/vnd.ms-excel");
		
		$header = array( "Mail No", "Received Date", "Mail Reference", "Date", "Subject", "From", "To", "Copy To", "Division/Subdivision", "Action Date", "Close Date", "Comments");
		echo implode("\t", str_replace(',', ' ', $header)) . " \n";
		
		foreach($result as $i => $inm){
			
			$subdiv= ($inm['subdivision'])?" / ".$inm['subdivision']:'';
			
			if(!empty($inm['comments'][0]['cmt'])){
					$inm['comments'][0]['cmt'] = "## ".$inm['comments'][0]['cmt'];	
			}
		
			$row = array($inm["mail_no"], date('Y/m/d',strtotime($inm["recieved_date"])), $inm["mail_ref"],date('Y/m/d',strtotime($inm["date"])),$inm["subject"],$inm["from"], $inm["to"], $inm["cc"], $inm["division"].$subdiv, date('Y/m/d',strtotime($inm["action_date"])), date('Y/m/d',strtotime($inm["close_date"])),$inm['comments'][0]['cmt']);		
			echo implode("\t", str_replace(',', ' ', $row)) . " \n";
			
		}
	}
	
	function mail_csv(){
		
		$search_subj = $this->session->userdata('search_subj');
		$this->load->model('inmails','inm');
		$result = $this->inm->search_subject($search_subj['txt'],$search_subj['url']);
	
		$mailsfilename = "Search mails".time().".csv";
		header('Content-Type: application/csv; charset=utf-8');
		header("Content-Disposition: attachment; filename=\"$mailsfilename\"");

		$fp = fopen('php://output', 'w+');
		
		$header = array( "Mail No", "Received Date", "Mail Reference", "Date", "Subject", "From", "To", "Division/Subdivision", "Receiver Signature");
		fputcsv($fp, $header);
		
		foreach($result as $inm){	
		
			$subdiv= ($inm['subdivision'])?" / ".$inm['subdivision']:'';
			
			if(!empty($inm['comments'][0]['cmt'])){
					$inm['comments'][0]['cmt'] = "## ".$inm['comments'][0]['cmt'];	
			}
		
			$row = array($inm["mail_no"], date('Y/m/d',strtotime($inm["recieved_date"])), $inm["mail_ref"],date('Y/m/d',strtotime($inm["date"])),$inm["subject"],$inm["from"], $inm["to"], $inm["division"].$subdiv,$inm['comments'][0]['cmt']);		
			fputcsv($fp, $row);
			
		}
		fclose($fp);
	}
	
	function mail_print()
	{
		require('mc_table.php');
		$pdf=new PDF_MC_Table();
		$pdf->SetRightMargin(0);
		$pdf->SetLeftMargin(10);
		$pdf->AddPage();
		$pdf->SetFont('Arial','',7);
		$pdf->SetDisplayMode(100);
		//Table with 10 rows
		$pdf->SetWidths(array(63,15,15,15,15,15,22,25));
		srand(microtime()*1000000);
		
		$login = $this->session->userdata('check_login');
		
		if($login['login_type']==2){			
			$sig_comm = "Receiver Signature";			
		}else{
			$sig_comm = "Comments";
		}
		
		$pdf->Row(array("Mail Details","Received Date","Date","From","To","Action Date","Division/Subdivision",$sig_comm));
		
		$search_subj = $this->session->userdata('search_subj');
		$this->load->model('inmails','inm');
		$mails = $this->inm->search_subject($search_subj['txt'],$search_subj['url']);
			for($i=0;$i<count($mails);$i++)
			{
				$subdiv = "";
				if($mails[$i]['subdivision'])
					$subdiv = " / ".$mails[$i]['subdivision'];
					
						
				if(!empty($mails[$i]['comments'][0]['cmt'])){
					$mails[$i]['comments'][0]['cmt'] = "## ".$mails[$i]['comments'][0]['cmt'];	
				}
				
					
					$same_field = "ID : ".$mails[$i]['mail_no']."\nMail Ref : ".$mails[$i]['mail_ref']."\nDivision/Subdivision : ".$mails[$i]['division'].$subdiv."\nSubject : ".$mails[$i]['subject'];		
					
				if($login['login_type']==2){			
					$comm_sig = "";			
				}else{
					$comm_sig = $mails[$i]['comments'][0]['cmt'];
				}	
					
				$pdf->Row(array($same_field,  date('d/m/Y',strtotime($mails[$i]['recieved_date'])), date('d/m/Y',strtotime($mails[$i]['date'])),$mails[$i]['from'],$mails[$i]['to'],$mails[$i]['action_date'],$mails[$i]['division'].$subdiv,$comm_sig));
					
				
			}
			$pdf->Output();
			
		}
		
		function check_temp_name()
		{
			$temp_name = $_POST['temp_name'];
			$this->load->model('inmails','inm');
			$result = $this->inm->check_temp_name($temp_name);
			echo $result;
		}
}