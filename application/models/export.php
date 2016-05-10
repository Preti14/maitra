<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Campaign Select
 * This model serves to fetch campaign data.
 */
class Export extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$ci =& get_instance();
	}
	
	function inmails($dt='', $limit='',$start='')
	{	
		$ms_session = $this->session->userdata('mail_status');
		$mail_status = isset($ms_session)?$ms_session:0;
		if($mail_status == 2)
			$ms_qry = 'm.status=2';
		else if($mail_status == 1)
			$ms_qry = 'm.status=1';
		else if($mail_status == 0)
			$ms_qry = 'm.status=1 or m.status=2 or m.status=3';
			
		if($dt=='day')
			$dtqry = " and m.recieved_date ='".date("Y-m-d")."'";
		else if($dt=='week'){
			$current_dayname = date("l");         
			$from_date = date("Y-m-d",strtotime('monday this week'));
			$to_date = date("Y-m-d",strtotime("$current_dayname this week"));
			$dtqry = " and ((substr(m.recieved_date, 7, 4) ||  '-' || substr(m.recieved_date, 4, 2) ||  '-' ||  substr(m.recieved_date, 1, 2)) BETWEEN '".$from_date."'  AND '".$to_date."') ";
		}else if($dt=='month'){
			$current_dayname = date("l");         
			$from_date = date("Y-m-d",strtotime('first day of this month'));
			$to_date = date("Y-m-d",strtotime("$current_dayname last day of this month"));
			$dtqry = " and ((substr(m.recieved_date, 7, 4) ||  '-' || substr(m.recieved_date, 4, 2) ||  '-' ||  substr(m.recieved_date, 1, 2)) BETWEEN '".$from_date."'  AND '".$to_date."') ";
		}
		else
			$dtqry ="";
			
		if($limit!='')
	   		$limit = " LIMIT $start,$limit";
		
		$query = $this->db->query('SELECT m.id, m.remarks, m.date, m.mail_no, m.mail_ref, m.type, m.subject, m.language, m.recieved_date, m.owner, m.action_date, m.close_date, m.created_on, m.updated_on, m.created_by, m.updated_by, m.status, m.division_id, m.subdivision_id, m.validation, d.division, sd.code as subdivision  FROM mails m 
			   LEFT JOIN division d on d.id =m.division_id
			   LEFT JOIN subdivision sd on sd.id =m.subdivision_id
			   WHERE m.type=1 and ( '.$ms_qry.')'.$dtqry.' ORDER BY m.id DESC'.$limit);//					   
		$mails=$query->result_array();
		
		header('Content-Type: application/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=Inmails.csv');
		$fp = fopen('php://output', 'w+');		

		foreach($mails as $row){
			$row['from'] = $this->address($row['id'],1);
			$row['to'] = $this->address($row['id'],2);
			$row['cc'] = $this->address($row['id'],3);
			fputcsv($fp, $row);
		
		}
		//return $mails;

		fclose($fp);
	}
	
	function address($id,$type){
		$from_address_qry = $this->db->query('SELECT title FROM mail_address WHERE (mail_id = '.$id.' and type='.$type.')');
		$from_mail_address =$from_address_qry->result_array();
		$res_fromadd ="";
		foreach($from_mail_address as $fromadd){
			$res_fromadd .= $fromadd['title'].", ";
		}			
		return rtrim($res_fromadd,", ");
	}
}

