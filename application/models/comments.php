<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Campaign Select
 * This model serves to fetch campaign data.
 */
class Comments extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$ci =& get_instance();
	}
	
	function insert_comments($comments, $mailid)
	{
		$div_data = $this->session->userdata('get_div');
	
		$session=$this->session->userdata('check_login');
		$date=date('d-m-Y H:i:s');
		$active=1;
		$users_id=$session['login_id'];
		$res =$this->db->query("INSERT INTO comments ('comments', 'date', 'active', 'users_id', 'mails_id') VALUES ('$comments', '$date', '$active', '$users_id', '$mailid')");
		$last_insert_id=$this->db->insert_id();
		
		if($res){	
			$row = $this->db->query("SELECT mail_no,type FROM mails WHERE id = ".$mailid." LIMIT 1");
			$mail = $row->row_array();	
			$session = $this->session->userdata('check_login');
			
		
			
			$div_qry  = "";
			if($div_data['division_id']!=''){
				$division_id = $div_data['division_id'] ;
			}else{
				$division_id  = 0 ;
			}
			if(isset($div_data['subdivision_id']) && $div_data['subdivision_id']!=''){
				$subdivision_id  = $div_data['subdivision_id'] ;
			}else{
				$subdivision_id  = 0 ;
			}
			
			
			$date=date('d-m-Y H:i:s');
			if($mail['type'] == 1){
				$mail_type = "Inmail";
			}else{
				$mail_type = "Outmail";
			}
			$action= $mail_type." with Mail No ".$mail['mail_no']." has been commented by ".$session['login_name'];
			$active=1;
			$users_id= $session['login_id'];
			
			$this->load->model('users','us');
			$user = $this->us->user_division();
			//$division_id = $user->division_id;
			//$subdivision_id = $user->subdivision_id;
			
			$newres =$this->db->query("INSERT INTO log ('action', 'date', 'active', 'users_id', 'mails_id','division_id','subdivision_id') VALUES ('$action', '$date', '$active', '$users_id', '$mailid', '$division_id', '$subdivision_id')");	
			echo true;			
		}else{
			echo false;
                }
	}
	
	function fetch_comments($mailid)
	{
		$query = $this->db->query('SELECT c.*,u.* FROM comments c 
		JOIN users u ON u.id=c.users_id
		WHERE c.mails_id='.$mailid.' ORDER BY c.id DESC');
		$result = $query->result_array();
		return $result;
	}
}