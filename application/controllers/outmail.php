<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Outmail extends MY_Controller {
	
	public function __construct(){
		parent::__construct();
		//$this->output->enable_profiler(TRUE);
		$this->load->library('pagination');
		$data_user = $this->session->userdata('check_login');
		if ( ! $this->session->userdata('check_login'))
    	{   
            redirect(base_url());
    	}
	}
	
	function outmaillist()
	{
		$d = isset($_GET['out'])?$_GET['out']:'';
		$this->load->model('outmails','otm');
		
		$link = "";
		if($d){
			$link = "&out=".$_GET['out'];
		}
		
		$this->load->model('outmails','otm');		
		$config['total_rows'] = $this->otm->outmail_total_rows($d);
		$config['per_page'] =$this->settings['no_of_records'];		
		$config['base_url'] = site_url().'?c=outmail&m=outmaillist'. $link;
		$config['uri_segment'] = 3;	
		$config['num_links'] = 10;
		//$config['use_page_numbers'] = TRUE;		
		$page = 0;
		if(isset($_GET['per_page']) && $_GET['per_page']!=0){
			//$config['base_url'] = site_url().'?c=outmail&m=outmaillist';
			$page = $_GET['per_page']; 
		}
		$this->pagination->initialize($config);
		
		$this->outmail_list = $this->otm->outmail_list($d, $config["per_page"], $page);
		$this->template_view('outmaillist');
	}
	
	function outmailentry()
	{
		$mailid = isset($_GET['mailid'])?$_GET['mailid']:'';
		$ref=isset($_GET['ref'])?$_GET['ref']:'';
		
		$this->load->model('outmails','otm');
		if($mailid){				
			$this->row=$this->otm->edit_staging($mailid);
		}/* Mail number Prefix*/
		$current_settings  = $this->settings;
		$prefix            = $current_settings['mail_no_prefix'];
		if(!$prefix)
		$prefix            = date('y');
		if($current_settings['mail_no_reset'] == 0){
			$mail_no       = $this->inm->get_last_mailno(2);
			$mail_no       = $mail_no + 1;
		}else{
			$mail_no       = 1;
		}
		$this->mail_number =  $prefix."-".str_pad($mail_no, 5, '0', STR_PAD_LEFT);
		/* Mail number Prefix*/
		$config['total_rows'] = $this->otm->outmail_staging_rows();
		$config['per_page'] =$this->settings['no_of_records'];
		$config['base_url'] = site_url().'?c=outmail&m=outmailentry';
		$config['uri_segment'] = 3;

		$page = 0;
		if(isset($_GET['per_page']) && $_GET['per_page']!=0){
			$page = $_GET['per_page']; 
		}
		
		$this->pagination->initialize($config);
		
		$this->staging_list = $this->otm->staging_list($config["per_page"], $page);
		
		$this->ref = $ref;
		$this->template_view('outmailentry');
	}
	
	function outmail_staging_list($per_page, $start)
	{
		$this->load->model('outmails','otm');
		$this->staging_list = $this->otm->staging_list($per_page, $start);
	}
	
	function outmail_staging(){
		$this->load->model('outmails','otm');
		$config['total_rows'] = $this->otm->outmail_staging_rows();
		$config['per_page'] =10;
		$config['base_url'] = site_url().'?c=outmail&m=outmail_staging';
		$config['uri_segment'] = 3;

		$page = 0;
		if(isset($_GET['per_page']) && $_GET['per_page']!=0){
			$config['base_url'] = site_url().'?c=outmail&m=outmail_staging&per_page=';
			$page = $_GET['per_page']; 
		}
		
		$this->pagination->initialize($config);
		
		$this->staging_list = $this->otm->staging_list($config["per_page"], $page);
		$this->template_view('outmail_staging');
	}
	
	function mailaddr_check($str=''){
		if(isset($str) && $str!='|')
			return true;
		else
			return false;
	}
	
	function outmailentry_insert($mailid='')
	{
		$login_data=$this->session->userdata('check_login');	//print_r($_POST);die;	
		if($_POST)
		{
			$edit_mailid=$this->input->post('edit_mailid');			
			$this->form_validation->set_rules('mailref','Mail Ref','required');
			$this->form_validation->set_rules('from_new','From','required','callback_mailaddr_check');
			$this->form_validation->set_rules('to_new','To','required','callback_mailaddr_check');
			$this->form_validation->set_rules('subject','Subject','required');
			$this->form_validation->set_rules('date','Date','required');
			$this->form_validation->set_rules('recieved_date','Dispatched Date','required');
			
			if($this->form_validation->run() == true)
				$data['validation']=($this->input->post('division_id'))?2:1;
			else
				$data['validation']=0;
			
			$data['mailno']= $this->input->post('mailno');	
			$data['mail_ref']= $this->input->post('mailref');
			$data['subject']= SQLite3::escapeString($this->input->post('subject'));
			$data['date']= $this->input->post('date');
			$data['recieved_date']= $this->input->post('recieved_date');
			$data['division_id']=$this->input->post('division_id');
			$data['subdivision_id']=$this->input->post('subdivision_id');
			$data['comments']= $this->input->post('comments');				
			$language=$this->input->post('hindi');
			if($language=='on')
				$data['language']="Hindi";
			else
				$data['language']="English";
			
			$data['status']=($this->input->post('mail_status'))?$this->input->post('mail_status'):0;
			$data['type']=2;	//inmail
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
			$data['to_attention']= $this->input->post('to_attention_values');
			$data['cc_attention']= $this->input->post('cc_attention_values');
			$data['search_mail_no']= $this->input->post('search_mail_no');	
			
			$this->load->model('outmails','otm');		
			$result = $this->otm->outmail_insert($data,$edit_mailid);	
			if($result){
				if(isset($_REQUEST['ref'])){					
					if($_REQUEST['ref']!='') {
						$search = "";
						if($data['search_mail_no']!='')
						$search = "&mail_number=".$data['search_mail_no'];
						
						redirect('c=admin&m=manage_outmails'.$search);
						} else {
							redirect('c=outmail&m=outmailentry');
						}
				}
				//redirect('c=outmail&m=outmail_staging');
			}
		}
	}
	
	function outmailview()
	{
		$mailid = $_GET['mailid'];
		$this->load->model('outmails','otm');
		$this->outmail_details= $this->otm->outmailview($mailid);
		
		$this->load->model('comments','cm');
		$this->comments= $this->cm->fetch_comments($mailid);
		
		$this->load->model('users','us');
		$this->users= $this->us->fetch_users();
		$this->template_view('outmailview');
	}
	
	function print_address_form()
	{
		$this->template_view('print_address_form');
	}
	
	function print_address()
	{
		$data['alias'] = $_POST['alias'];
		$data['for_alias'] = $_POST['for_alias'];
		
		$this->session->set_userdata('print_alias','');
		$div_data = $_POST['alias'];	
		
		if($this->session->userdata('print_alias')!='')
		{
			$this->session->unset_userdata('print_alias');
		} else {
			$this->session->set_userdata('print_alias',$div_data);	
		}
		
		$this->load->model('outmails','otm');
		$data['to_address'] = $this->otm->to_address($data);
		$this->load->view('print_address', $data);
	}
	
	function search_alias()
	{
		$data['alias'] = $_POST['alias'];
		$this->load->model('outmails','otm');
		$result = $this->otm->search_alias($data);
		print_r($result);
	}
	
	function outmail_xls(){
		
		$d = isset($_GET['out'])?$_GET['out']:'';
		$this->load->model('outmails','otm');
		$result = $this->otm->outmail_list($d);
	
		$mailsfilename = "Search outmails".time().".xls";
		header("Content-Disposition: attachment; filename=\"$mailsfilename\"");
		header("Content-Type: application/vnd.ms-excel");
		
		$header = array( "Mail No", "Despatched Date", "Mail Reference", "Date", "Subject", "From", "To","Copy To", "Division/Subdivision", "Receiver Signature");
		echo implode("\t", str_replace(',', ' ', $header)) . " \n";
		
		foreach($result as $inm){
			
			$subdiv= ($inm['subdivision'])?" / ".$inm['subdivision']:'';
		
			$row = array($inm["mail_no"], date('m/d/Y',strtotime($inm["recieved_date"])), $inm["mail_ref"],date('m/d/Y',strtotime($inm["date"])),$inm["subject"],$inm["from"], $inm["to"],$inm['cc'], $inm["division"].$subdiv, "");		
			echo implode("\t", str_replace(',', ' ', $row)) . " \n";
			
		}
	}
	
	
	function outmail_csv(){
		
		$d = isset($_GET['out'])?$_GET['out']:'';
		$this->load->model('outmails','otm');
		$result = $this->otm->outmail_list($d);
	
		$mailsfilename = "Search outmails".time().".csv";
		header('Content-Type: application/csv; charset=utf-8');
		header("Content-Disposition: attachment; filename=\"$mailsfilename\"");

		$fp = fopen('php://output', 'w+');		
		$header = array( "Mail No", "Despatched Date", "Mail Reference", "Date", "Subject", "From", "To","Copy To", "Division/Subdivision", "Receiver Signature");
		fputcsv($fp, $header);
		
		foreach($result as $inm){	
		
			$subdiv= ($inm['subdivision'])?" / ".$inm['subdivision']:'';
		
			$row = array($inm["mail_no"], date('m/d/Y',strtotime($inm["recieved_date"])), $inm["mail_ref"],date('m/d/Y',strtotime($inm["date"])),$inm["subject"],$inm["from"], $inm["to"],$inm['cc'], $inm["division"].$subdiv, "");		
			fputcsv($fp, $row);
			
		}
		fclose($fp);
	}
	
	
	function outmail_print(){
		
		require('mc_table.php');
		$pdf=new PDF_MC_Table();
		$pdf->AddPage();
		$pdf->SetFont('Arial','',6);
		//Table with 20 rows and 4 columns
		$pdf->SetWidths(array(12,14,15,14,30,16,16,16,16,14,14,15));
		srand(microtime()*1000000);
		$pdf->Row(array("ID","Despatched Date","Mail Ref","Date","Subject","From","To","Copy To","Division/Subdivision","Receiver Signature"));	
		
		$d = isset($_GET['out'])?$_GET['out']:'';
		$this->load->model('outmails','otm');
		$mails = $this->otm->outmail_list($d);
	
		for($i=0;$i<count($mails);$i++)
			{
				$subdiv = "";
				if($mails[$i]['subdivision'])
					$subdiv = " / ".$mails[$i]['subdivision'];
				$pdf->Row(array($mails[$i]['mail_no'],$mails[$i]['recieved_date'],$mails[$i]['mail_ref'],$mails[$i]['date'],$mails[$i]['subject'],$mails[$i]['from'],$mails[$i]['to'],$mails[$i]['cc'],$mails[$i]['division'].$subdiv, ""));
				
			}
			$pdf->Output();
	}
	
	
	function outmail_division_list()
	{   //print_r($_GET);
		if ( ! $this->session->userdata('check_login'))
    	{   
            redirect(base_url());
    	}
		$arr['division_id']    = isset($_GET['division_id'])?$_GET['division_id']:'';
		$arr['subdivision_id']   = isset($_GET['subdivision_id'])?$_GET['subdivision_id']:'';
		if($arr['division_id']!=''){
			$this->session->set_userdata('div_data',$arr);
		}
		$div_data      = $this->session->userdata('div_data');
		
		$suffix = "";
		if($div_data){
		$suffix               = '&'.http_build_query($div_data, '', "&");}
		
		$this->load->model('outmails','otm');
		$config['total_rows']     = $this->otm->outmail_division_list_rows($div_data['division_id'],$div_data['subdivision_id']);
		$config['per_page']       = $this->settings['no_of_records'];
		$config['base_url']       = site_url().'?c=outmail&m=outmail_division_list'.$suffix.'&type=outmail';
		$config['uri_segment']    = 4;
		$page = 0;
		if(isset($_GET['per_page']) && $_GET['per_page']!=0 && $_GET['type'] == 'outmail'){ 
			//$config['base_url']   = site_url().'?c=home&m=outmail_division_list&type=outmail&per_page=';
			$page                 = $_GET['per_page']; 
		}
		$this->pagination->initialize($config);
		$this->pagination2        = $this->pagination->create_links();	
		$this->outmail_list       = $this->otm->outmail_division_list($div_data['division_id'],$div_data['subdivision_id'],$config["per_page"], $page);
		
		$this->template_view('outmail_division_list');
	}
	
}
