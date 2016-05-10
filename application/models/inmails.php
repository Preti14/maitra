<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Campaign Select
 * This model serves to fetch campaign data.
 */
class Inmails extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$ci =& get_instance();
	}
	
	function inmail_staging_rows()
	{
		$query = $this->db->query('SELECT * FROM mails WHERE (`type`=1 and `status`=0) ORDER BY `id` DESC ');
		$count = $query->num_rows();//`status`=0
		if($count)
			 return $count;
	}
	
	function staging_list($limit='', $start='')
	{
		if($limit!='')
	   		$limit = " LIMIT $start,$limit";
		$query = $this->db->query('SELECT * FROM mails WHERE (`type`=1 and `status`=0) ORDER BY `id` DESC '.$limit);
		$result = $query->result_array();//`subject` LIKE "%ICGS SAGAR%"
		if($result)
			 return $result;
	}
	
	
	function inmail_total_rows($dt='', $limit='',$start=''){
		$ms_session = $this->session->userdata('mail_status');
		$mail_status = ($ms_session!='')?$ms_session:1;//$mail_status = isset($ms_session)?$ms_session:1;
		if($mail_status == 2)
			$ms_qry = ' and m.status=2';
		else if($mail_status == 1)
			$ms_qry = ' and m.status=1';
		else if($mail_status == 0)
			$ms_qry = ' and m.status IN(1,2,3)';
		
		$div_data = $this->session->userdata('get_div');
		$div_qry  = "";
		if($div_data['division_id']!=''){
			$div_qry=" and m.division_id = ".$div_data['division_id']." ";
		}
		if(isset($div_data['subdivision_id']) && $div_data['subdivision_id']!=''){
			$div_qry.=" and m.subdivision_id = ".$div_data['subdivision_id']." ";
		}
			
		if($dt=='day')
			$dtqry = " and m.recieved_date ='".date("d-m-Y")."' ";
		else if($dt=='week'){
			$current_dayname = date("l");         
			$from_date = date("Y-m-d",strtotime('monday this week'));
			$to_date = date("Y-m-d",strtotime("sunday this week"));
			$dtqry = "  and ((substr(m.recieved_date, 7, 4) ||  '-' || substr(m.recieved_date, 4, 2) ||  '-' ||  substr(m.recieved_date, 1, 2)) BETWEEN '".$from_date."'  AND '".$to_date."')  ";
		}else if($dt=='month'){
			$current_dayname = date("l");         
			$from_date = date("Y-m-d",strtotime('first day of this month'));
			$to_date = date("Y-m-d",strtotime("$current_dayname last day of this month"));
			$dtqry = "  and ((substr(m.recieved_date, 7, 4) ||  '-' || substr(m.recieved_date, 4, 2) ||  '-' ||  substr(m.recieved_date, 1, 2)) BETWEEN '".$from_date."'  AND '".$to_date."')  ";
		}
		else
			$dtqry ="";
			
		if($mail_status == 3){
			$ms_qry = ' ';
			$dtqry.=  "  and (substr(m.action_date, 7, 4) ||  '-' || substr(m.action_date, 4, 2) ||  '-' ||  substr(m.action_date, 1, 2)) < date('now') and m.status=1 ";
		}
			
		if($limit!='')
	   		$limit = " LIMIT $start,$limit";
							   
		/*$query = $this->db->query('SELECT m.*,d.division,d.code as division,sd.code as subdivision  FROM mails m 
		                           LEFT JOIN division d on d.id =m.division_id
								   LEFT JOIN subdivision sd on sd.id =m.subdivision_id
								   WHERE m.type=1 '.$ms_qry.$div_qry.$dtqry.' ORDER BY m.id DESC'.$limit);*/
		$query = $this->db->query('SELECT m.id, m.remarks, m.date, m.mail_no, m.mail_ref, m.type, m.subject, m.language, m.recieved_date, m.owner, m.action_date, m.close_date, m.created_on, m.updated_on, m.created_by, m.updated_by, m.status, m.division_id, m.subdivision_id, m.validation, d.division, sd.code as subdivision FROM mails m 
			    LEFT JOIN division d on d.id =m.division_id
			    LEFT JOIN subdivision sd on sd.id =m.subdivision_id
			  		   
			   WHERE m.type=1 '.$ms_qry.$div_qry.$dtqry.' group by m.id order by substr(m.recieved_date, 7, 4) ||  "-" || substr(m.recieved_date, 4, 2) ||  "-" ||  substr(m.recieved_date, 1, 2) DESC,m.mail_no ASC '.$limit);					   
		$mails_count=$query->num_rows();
		return $mails_count;
	}
	
	
	function manage_inmail_total_rows($title,$dt='', $limit='',$start=''){
		$title = SQLite3::escapeString($title);
		$ms_session = $this->session->userdata('mail_status');
		$mail_status = ($ms_session!='')?$ms_session:1;//$mail_status = isset($ms_session)?$ms_session:1;
		if($mail_status == 2)
			$ms_qry = ' and m.status=2';
		else if($mail_status == 1)
			$ms_qry = ' and m.status=1';
		else if($mail_status == 0)
			$ms_qry = ' and m.status IN(1,2,3)';
		
		$div_data = $this->session->userdata('get_div');
		$div_qry  = "";
		if($div_data['division_id']!=''){
			$div_qry=" and m.division_id = ".$div_data['division_id']." ";
		}
		if($div_data['subdivision_id']!=''){
			$div_qry.=" and m.subdivision_id = ".$div_data['subdivision_id']." ";
		}
		
		$condition = "";
		$condition = " and (m.mail_no  LIKE  '".'%'.$title.'%'."') ";
			
		if($dt=='day')
			$dtqry = " and m.recieved_date ='".date("d-m-Y")."' ";
		else if($dt=='week'){
			$current_dayname = date("l");         
			$from_date = date("Y-m-d",strtotime('monday this week'));
			$to_date = date("Y-m-d",strtotime("sunday this week"));
			$dtqry = "  and ((substr(m.recieved_date, 7, 4) ||  '-' || substr(m.recieved_date, 4, 2) ||  '-' ||  substr(m.recieved_date, 1, 2)) BETWEEN '".$from_date."'  AND '".$to_date."')  ";
		}else if($dt=='month'){
			$current_dayname = date("l");         
			$from_date = date("Y-m-d",strtotime('first day of this month'));
			$to_date = date("Y-m-d",strtotime("$current_dayname last day of this month"));
			$dtqry = "  and ((substr(m.recieved_date, 7, 4) ||  '-' || substr(m.recieved_date, 4, 2) ||  '-' ||  substr(m.recieved_date, 1, 2)) BETWEEN '".$from_date."'  AND '".$to_date."')  ";
		}
		else
			$dtqry ="";
			
		if($mail_status == 3){
			$ms_qry = ' ';
			$dtqry.=  " and (substr(m.action_date, 7, 4) ||  '-' || substr(m.action_date, 4, 2) ||  '-' ||  substr(m.action_date, 1, 2)) < date('now') and m.status=1 ";
		}
			
		if($limit!='')
	   		$limit = " LIMIT $start,$limit";
							   
		$query = $this->db->query('SELECT m.*,d.division,d.code as division,sd.code as subdivision  FROM mails m 
		                           LEFT JOIN division d on d.id =m.division_id
								   LEFT JOIN subdivision sd on sd.id =m.subdivision_id
								   WHERE m.type=1 '.$ms_qry.$div_qry.$dtqry.$condition.' ORDER BY m.id DESC'.$limit);
							   
		$mails_count=$query->num_rows();
		return $mails_count;
	}
	function array_merge_recursive2($paArray1, $paArray2){
		if (!is_array($paArray1) or !is_array($paArray2)) { return $paArray2; }
		foreach ($paArray2 AS $sKey2 => $sValue2)
		{
			$paArray1[$sKey2] = $this->array_merge_recursive2(@$paArray1[$sKey2], $sValue2);
		}
		return $paArray1;
	}
	function distributed_list($dt='', $limit='',$start='')
	{	
		$ms_session = $this->session->userdata('mail_status');
		$mail_status = ($ms_session!='')?$ms_session:1;//$mail_status = isset($ms_session)?$ms_session:1;
		if($mail_status == 2)
			$ms_qry = ' and m.status=2';
		else if($mail_status == 1)
			$ms_qry = ' and m.status=1';
		else if($mail_status == 0)
			$ms_qry = ' and m.status IN(1,2,3)';
		
		$div_data = $this->session->userdata('get_div');
		$div_qry  = "";
		if($div_data['division_id']!=''){
			$div_qry=" and m.division_id = ".$div_data['division_id']." ";
		}
		if(isset($div_data['subdivision_id']) && $div_data['subdivision_id']!=''){
			$div_qry.=" and m.subdivision_id = ".$div_data['subdivision_id']." ";
		}
			
		if($dt=='day')
			$dtqry = " and m.recieved_date ='".date("d-m-Y")."'";
		else if($dt=='week'){
			$current_dayname = date("l");         
			$from_date = date("Y-m-d",strtotime('monday this week'));
			$to_date = date("Y-m-d",strtotime("sunday this week"));
			$dtqry = " and ((substr(m.recieved_date, 7, 4) ||  '-' || substr(m.recieved_date, 4, 2) ||  '-' ||  substr(m.recieved_date, 1, 2)) BETWEEN '".$from_date."'  AND '".$to_date."') ";
		}else if($dt=='month'){
			$current_dayname = date("l");         
			$from_date = date("Y-m-d",strtotime('first day of this month'));
			$to_date = date("Y-m-d",strtotime("$current_dayname last day of this month"));
			$dtqry = " and ((substr(m.recieved_date, 7, 4) ||  '-' || substr(m.recieved_date, 4, 2) ||  '-' ||  substr(m.recieved_date, 1, 2)) BETWEEN '".$from_date."'  AND '".$to_date."') ";
		}
		else
			$dtqry ="";
		
		if($mail_status == 3){
			$ms_qry = ' ';
			$dtqry.=  " and (substr(m.action_date, 7, 4) ||  '-' || substr(m.action_date, 4, 2) ||  '-' ||  substr(m.action_date, 1, 2)) < date('now') and m.status=1 ";
		}
			
		if($limit!='')
	   		$limit = " LIMIT $start,$limit";				
	   
			    
		$query = $this->db->query('SELECT m.id, m.remarks, m.date, m.mail_no, m.mail_ref, m.type, m.subject, m.language, m.recieved_date, m.owner, m.action_date, m.close_date, m.created_on, m.updated_on, m.created_by, m.updated_by, m.status, m.division_id, m.subdivision_id, m.validation, d.division, sd.code as subdivision FROM mails m 
			    LEFT JOIN division d on d.id =m.division_id
			    LEFT JOIN subdivision sd on sd.id =m.subdivision_id
			  		   
			   WHERE m.type=1 '.$ms_qry.$div_qry.$dtqry.' group by m.id order by substr(m.recieved_date, 7, 4) ||  "-" || substr(m.recieved_date, 4, 2) ||  "-" ||  substr(m.recieved_date, 1, 2) DESC,m.mail_no ASC '.$limit);//
		
		   
		$mails=$query->result_array();
		$result = array();
		foreach($mails as $mailsres){
			$result[$mailsres["id"]]["id"] = $mailsres["id"];
			
			$result[$mailsres["id"]]["remarks"] = $mailsres["remarks"];
			$result[$mailsres["id"]]["date"] = $mailsres["date"];
			$result[$mailsres["id"]]["mail_no"] = $mailsres["mail_no"];
			$result[$mailsres["id"]]["mail_ref"] = $mailsres["mail_ref"];
			$result[$mailsres["id"]]["type"] = $mailsres["type"];
			$result[$mailsres["id"]]["subject"] = $mailsres["subject"];
			$result[$mailsres["id"]]["language"] = $mailsres["language"];
			$result[$mailsres["id"]]["recieved_date"] = $mailsres["recieved_date"];
			$result[$mailsres["id"]]["owner"] = $mailsres["owner"];
			$result[$mailsres["id"]]["action_date"] = $mailsres["action_date"];
			$result[$mailsres["id"]]["close_date"] = $mailsres["close_date"];
			$result[$mailsres["id"]]["created_on"] = $mailsres["created_on"];
			$result[$mailsres["id"]]["updated_on"] = $mailsres["updated_on"];
			$result[$mailsres["id"]]["created_by"] = $mailsres["created_by"];
			$result[$mailsres["id"]]["updated_by"] = $mailsres["updated_by"];
			$result[$mailsres["id"]]["status"] = $mailsres["status"];
			$result[$mailsres["id"]]["division_id"] = $mailsres["division_id"];
			$result[$mailsres["id"]]["subdivision_id"] = $mailsres["subdivision_id"];
			$result[$mailsres["id"]]["validation"] = $mailsres["validation"];
			$result[$mailsres["id"]]["division"] = $mailsres["division"];
			$result[$mailsres["id"]]["subdivision"] = $mailsres["subdivision"];
			$result[$mailsres["id"]]["from"] = NULL;
			$result[$mailsres["id"]]["to"] = NULL;
			$result[$mailsres["id"]]["cc"] = NULL;
		}
		
		$sq_main_id = 	'SELECT m.id FROM mails m 
			    LEFT JOIN division d on d.id =m.division_id
			    LEFT JOIN subdivision sd on sd.id =m.subdivision_id
			  		   
			   WHERE m.type=1 '.$ms_qry.$div_qry.$dtqry.' group by m.id order by substr(m.recieved_date, 7, 4) ||  "-" || substr(m.recieved_date, 4, 2) ||  "-" ||  substr(m.recieved_date, 1, 2) DESC,m.mail_no ASC '.$limit;
		
		
		$mail_addr_q = $this->db->query('select * from (select sub.mail_id,GROUP_CONCAT(sub.`from`, ",") as`from` , GROUP_CONCAT(sub.`to`, ",") as `to`,GROUP_CONCAT(sub.`cc`, ",") as `cc`  from (SELECT mail_id, ( CASE type when 1 then GROUP_CONCAT(title, ",") END) AS `from` , 
( CASE type when 2 then GROUP_CONCAT(title, ",") END) AS `to` , ( CASE type when 3 then GROUP_CONCAT(title, ",") END) AS `cc` 
FROM mail_address   group by type,mail_id ) as sub group by sub.mail_id) as q where q.mail_id IN ('.$sq_main_id .')');
				
		$mail_address =$mail_addr_q->result_array();
		$result1 = array();
		foreach($mail_address as $mail_addr){
			$result1[$mail_addr["mail_id"]]["from"] = $mail_addr["from"];
			$result1[$mail_addr["mail_id"]]["to"] = $mail_addr["to"];
			$result1[$mail_addr["mail_id"]]["cc"] = $mail_addr["cc"];
		}
		
		$mails = $this->array_merge_recursive2($result,$result1);
		
		if(!empty($mails)){
			foreach($mails as &$row){				
				$row['comments'] = $this->listallcomments($row['id']);
     		}	
		}
		
		sort($mails);
        
		return $mails;
		
		
	}
	
	
	
	function manage_distributed_list($title,$dt='', $limit='',$start='')
	{	
		$title = SQLite3::escapeString($title);
		$ms_session = $this->session->userdata('mail_status');
		$mail_status = ($ms_session!='')?$ms_session:1;//$mail_status = isset($ms_session)?$ms_session:1;
		if($mail_status == 2)
			$ms_qry = ' and m.status=2';
		else if($mail_status == 1)
			$ms_qry = ' and m.status=1';
		else if($mail_status == 0)
			$ms_qry = ' and m.status IN(1,2,3)';
		
		$div_data = $this->session->userdata('get_div');
		$div_qry  = "";
		if($div_data['division_id']!=''){
			$div_qry=" and m.division_id = ".$div_data['division_id']." ";
		}
		if($div_data['subdivision_id']!=''){
			$div_qry.=" and m.subdivision_id = ".$div_data['subdivision_id']." ";
		}
		
		$condition = "";
		$condition = "and (m.mail_no  LIKE  '".'%'.$title.'%'."') ";
			
		if($dt=='day')
			$dtqry = " and m.recieved_date ='".date("d-m-Y")."'";
		else if($dt=='week'){
			$current_dayname = date("l");         
			$from_date = date("Y-m-d",strtotime('monday this week'));
			$to_date = date("Y-m-d",strtotime("sunday this week"));
			$dtqry = " and ((substr(m.recieved_date, 7, 4) ||  '-' || substr(m.recieved_date, 4, 2) ||  '-' ||  substr(m.recieved_date, 1, 2)) BETWEEN '".$from_date."'  AND '".$to_date."') ";
		}else if($dt=='month'){
			$current_dayname = date("l");         
			$from_date = date("Y-m-d",strtotime('first day of this month'));
			$to_date = date("Y-m-d",strtotime("$current_dayname last day of this month"));
			$dtqry = " and ((substr(m.recieved_date, 7, 4) ||  '-' || substr(m.recieved_date, 4, 2) ||  '-' ||  substr(m.recieved_date, 1, 2)) BETWEEN '".$from_date."'  AND '".$to_date."') ";
		}
		else
			$dtqry ="";
		
		if($mail_status == 3){
			$ms_qry = ' ';
			$dtqry.=  " and (substr(m.action_date, 7, 4) ||  '-' || substr(m.action_date, 4, 2) ||  '-' ||  substr(m.action_date, 1, 2)) < date('now') and m.status=1 ";
		}
			
		if($limit!='')
	   		$limit = " LIMIT $start,$limit";				
			   
		$query = $this->db->query('SELECT m.id, m.remarks, m.date, m.mail_no, m.mail_ref, m.type, m.subject, m.language, m.recieved_date, m.owner, m.action_date, m.close_date, m.created_on, m.updated_on, m.created_by, m.updated_by, m.status, m.division_id, m.subdivision_id, m.validation, d.division, sd.code as subdivision FROM mails m 
			   LEFT JOIN division d on d.id =m.division_id
			   LEFT JOIN subdivision sd on sd.id =m.subdivision_id
			   		   
			   WHERE m.type=1 '.$condition.$ms_qry.$div_qry.$dtqry.' group by m.id order by substr(m.recieved_date, 7, 4) ||  "-" || substr(m.recieved_date, 4, 2) ||  "-" ||  substr(m.recieved_date, 1, 2) DESC,m.mail_no ASC '.$limit);//
			   
			 
							   
		$mails=$query->result_array();
		$result = array();
		foreach($mails as $mailsres){
			$result[$mailsres["id"]]["id"] = $mailsres["id"];
			
			$result[$mailsres["id"]]["remarks"] = $mailsres["remarks"];
			$result[$mailsres["id"]]["date"] = $mailsres["date"];
			$result[$mailsres["id"]]["mail_no"] = $mailsres["mail_no"];
			$result[$mailsres["id"]]["mail_ref"] = $mailsres["mail_ref"];
			$result[$mailsres["id"]]["type"] = $mailsres["type"];
			$result[$mailsres["id"]]["subject"] = $mailsres["subject"];
			$result[$mailsres["id"]]["language"] = $mailsres["language"];
			$result[$mailsres["id"]]["recieved_date"] = $mailsres["recieved_date"];
			$result[$mailsres["id"]]["owner"] = $mailsres["owner"];
			$result[$mailsres["id"]]["action_date"] = $mailsres["action_date"];
			$result[$mailsres["id"]]["close_date"] = $mailsres["close_date"];
			$result[$mailsres["id"]]["created_on"] = $mailsres["created_on"];
			$result[$mailsres["id"]]["updated_on"] = $mailsres["updated_on"];
			$result[$mailsres["id"]]["created_by"] = $mailsres["created_by"];
			$result[$mailsres["id"]]["updated_by"] = $mailsres["updated_by"];
			$result[$mailsres["id"]]["status"] = $mailsres["status"];
			$result[$mailsres["id"]]["division_id"] = $mailsres["division_id"];
			$result[$mailsres["id"]]["subdivision_id"] = $mailsres["subdivision_id"];
			$result[$mailsres["id"]]["validation"] = $mailsres["validation"];
			$result[$mailsres["id"]]["division"] = $mailsres["division"];
			$result[$mailsres["id"]]["subdivision"] = $mailsres["subdivision"];
			$result[$mailsres["id"]]["from"] = NULL;
			$result[$mailsres["id"]]["to"] = NULL;
			$result[$mailsres["id"]]["cc"] = NULL;
		}
		//echo "<pre>";print_r($result);
		$sq_main_id = 	'SELECT m.id FROM mails m 
			   LEFT JOIN division d on d.id =m.division_id
			   LEFT JOIN subdivision sd on sd.id =m.subdivision_id
			   	   
			   WHERE m.type=1 '.$condition.$ms_qry.$div_qry.$dtqry.' group by m.id order by substr(m.recieved_date, 7, 4) ||  "-" || substr(m.recieved_date, 4, 2) ||  "-" ||  substr(m.recieved_date, 1, 2) DESC,m.mail_no ASC '.$limit;
		
		
		$mail_addr_q = $this->db->query('select * from (select sub.mail_id,GROUP_CONCAT(sub.`from`, ",") as`from` , GROUP_CONCAT(sub.`to`, ",") as `to`,GROUP_CONCAT(sub.`cc`, ",") as `cc`  from (SELECT mail_id, ( CASE type when 1 then GROUP_CONCAT(title, ",") END) AS `from` , 
( CASE type when 2 then GROUP_CONCAT(title, ",") END) AS `to` , ( CASE type when 3 then GROUP_CONCAT(title, ",") END) AS `cc` 
FROM mail_address   group by type,mail_id ) as sub group by sub.mail_id) as q where q.mail_id IN ('.$sq_main_id .')');
				
		$mail_address =$mail_addr_q->result_array();
		$result1 = array();
		foreach($mail_address as $mail_addr){
			$result1[$mail_addr["mail_id"]]["from"] = $mail_addr["from"];
			$result1[$mail_addr["mail_id"]]["to"] = $mail_addr["to"];
			$result1[$mail_addr["mail_id"]]["cc"] = $mail_addr["cc"];
		}
		
		$mails = $this->array_merge_recursive2($result,$result1);
		sort($mails);
        
		return $mails;
	}
	
	
	function division_list_rows($division_id='',$subdiv_id='')
	{
		$ms_session = $this->session->userdata('mail_status');
		$mail_status = ($ms_session!='')?$ms_session:1;//$mail_status = isset($ms_session)?$ms_session:1;
		if($mail_status == 2)
			$ms_qry = ' and m.status=2';
		else if($mail_status == 1)
			$ms_qry = ' and m.status=1';
		else if($mail_status == 0)
			$ms_qry = ' and m.status IN(1,2,3)';
			
		$condition = "m.type=1";
		if($subdiv_id)
			$condition = "m.type=1 and m.division_id=$division_id and m.subdivision_id=$subdiv_id";
		else if($division_id)
			$condition = "m.type=1 and m.division_id=$division_id";
		
		if($mail_status == 3){
			$ms_qry =  " and (substr(m.action_date, 7, 4) ||  '-' || substr(m.action_date, 4, 2) ||  '-' ||  substr(m.action_date, 1, 2)) < date('now') and m.status=1 ";
		}
		
		$query = $this->db->query('SELECT m.*,d.division  FROM mails m 
		                           LEFT JOIN division d on d.id =m.division_id WHERE '.$condition.$ms_qry);
		$mails_cnt = $query->num_rows();
		return $mails_cnt;
	}
	function division_list($division_id='',$subdiv_id='', $limit='', $start='')
	{
		$ms_session = $this->session->userdata('mail_status');
		$mail_status = ($ms_session!='')?$ms_session:1;//$mail_status = isset($ms_session)?$ms_session:1;
		if($mail_status == 2)
			$ms_qry = ' and m.status=2';
		else if($mail_status == 1)
			$ms_qry = ' and m.status=1';
		else if($mail_status == 0)
			$ms_qry = ' and m.status IN(1,2,3)';
			
		$condition = "m.type=1";
		if($subdiv_id)
			$condition = "m.type=1 and m.division_id=$division_id and m.subdivision_id=$subdiv_id";
		else if($division_id)
			$condition = "m.type=1 and m.division_id=$division_id";
		
		if($mail_status == 3){
			$ms_qry =  " and (substr(m.action_date, 7, 4) ||  '-' || substr(m.action_date, 4, 2) ||  '-' ||  substr(m.action_date, 1, 2)) < date('now') and m.status=1 ";
		}
		
		if($limit!='')
	   		$limit = " LIMIT $start,$limit";
			
		$query = $this->db->query('SELECT m.*,d.division, sub.mail_id,GROUP_CONCAT(sub.`from`, ",") as`from` , GROUP_CONCAT(sub.`to`, ",") as `to`,GROUP_CONCAT(sub.`cc`, ",") as `cc`  FROM mails m 
		                           LEFT JOIN division d on d.id =m.division_id 
								   LEFT JOIN (SELECT mail_id,
(  CASE type   when 1 then GROUP_CONCAT(title, ",") END)  AS `from` ,
           (  CASE type   when 2 then GROUP_CONCAT(title,  ",") END)  AS `to` ,
           (  CASE type   when 3 then GROUP_CONCAT(title,  ",") END)  AS `cc`  FROM mail_address 
             group by type,mail_id)as sub ON sub.mail_id = m.id 
								   WHERE '.$condition.$ms_qry.' group by m.id  order by substr(m.recieved_date, 7, 4) ||  "-" || substr(m.recieved_date, 4, 2) ||  "-" ||  substr(m.recieved_date, 1, 2)  DESC,m.mail_no ASC '.$limit);
		$mails = $query->result_array();
		
		return $mails;
		
		
	}
	
	function get_last_mailno($mail_type){
		$row = $this->db->query("SELECT mail_no FROM mails WHERE type=$mail_type and status!=4 ORDER BY id DESC LIMIT 1");
		$mail = $row->row_array();
		if(isset($mail['mail_no'])){
		
		$exploded = explode("-",$mail['mail_no']);
		$exact_mailno = (int)$exploded[1];
		return $exact_mailno;
		}
	}
	
	function inmail_insert($data,$edit_mailid='')
	{
		//echo "<pre>";print_r($data);die;
	
		if($edit_mailid=='')
		{
			
			$mail_no = $data['mailno'];
			$result = $this->db->query("INSERT INTO mails ('date', 'mail_no', 'mail_ref', 'type', 'subject', 'language', 'status', 'division_id', 'subdivision_id', 'validation', 'recieved_date', 'created_on', 'created_by', 'updated_on', 'updated_by') 
		VALUES ('{$data['date']}','".$mail_no."','{$data['mail_ref']}', '{$data['type']}', '{$data['subject']}', '{$data['language']}', '{$data['status']}', '{$data['division_id']}', '{$data['subdivision_id']}', '{$data['validation']}', '{$data['recieved_date']}', '{$data['created_on']}', '{$data['created_by']}', '{$data['updated_on']}', '{$data['updated_by']}')");
			$last_insert_id=$this->db->insert_id();
		
			if($data['comments']){
				$session=$this->session->userdata('check_login');
				$date=date('d-m-Y H:i:s');
				$active=1;
				$users_id=$session['login_id'];
				$res =$this->db->query("INSERT INTO comments ('comments', 'date', 'active', 'users_id', 'mails_id') VALUES ('{$data['comments']}', '$date', '$active', '$users_id', '$last_insert_id')");
			}
			if($data['from_new']!='|' and $data['from_new']!=''){
				$data['from_new']=rtrim($data['from_new'],"| ");
				$query = $this->db->query('SELECT * FROM address_book WHERE `id`='.$data['from_new']);
				$row = $query->row();
				$res =$this->db->query("INSERT INTO mail_address ('title','address1','address2','address3', 'city', 'state', 'country', 'pincode', 'mail_id','type') VALUES('$row->title', '$row->address1', '$row->address2', '$row->address3', '$row->city', '$row->state', '$row->country', '$row->pincode', $last_insert_id, 1)");
			}
			
			if($data['to_new']!='|' and $data['to_new']!=''){
				$data['to_new']=rtrim($data['to_new'],"| ");
				$to=explode("|",$data['to_new']);
				foreach($to as $to_id){
					$query = $this->db->query('SELECT * FROM address_book WHERE `id`='.$to_id);
					$row = $query->row();
					$res =$this->db->query("INSERT INTO mail_address ('title','address1','address2','address3', 'city', 'state', 'country', 'pincode', 'mail_id','type') VALUES('$row->title', '$row->address1', '$row->address2', '$row->address3', '$row->city', '$row->state', '$row->country', '$row->pincode', $last_insert_id, 2)");
				}
			}
			
			if($data['cc_new']!='|' and $data['cc_new']!=''){
				$data['cc_new']=rtrim($data['cc_new'],"| ");
				$cc=explode("|",$data['cc_new']);
				foreach($cc as $cc_id){
					$query = $this->db->query('SELECT * FROM address_book WHERE `id`='.$cc_id);
					$row = $query->row();
					$res =$this->db->query("INSERT INTO mail_address ('title','address1','address2','address3', 'city', 'state', 'country', 'pincode', 'mail_id','type') VALUES('$row->title', '$row->address1', '$row->address2', '$row->address3', '$row->city', '$row->state', '$row->country', '$row->pincode', $last_insert_id, 3)");
				}
			}
			
			if($result){
				$session=$this->session->userdata('check_login');
				$this->purge_log();
				$action="New Inmail with Mail No ".$mail_no." has been created by ".$session['login_name'];
				$date=date('d-m-Y H:i:s');
				$active=1;
				$users_id=$session['login_id'];
				$res =$this->db->query("INSERT INTO log ('action', 'date', 'active', 'users_id', 'mails_id') VALUES ('$action', '$date', '$active', '$users_id', '$last_insert_id')");
								
				$update_reset = $this->db->query("UPDATE settings SET mail_no_reset=0 where id  = (SELECT MAX(id)  FROM settings )");
			}
			
		}else{
			$result = $this->db->query("UPDATE mails SET date='{$data['date']}',
			 mail_ref='{$data['mail_ref']}',
			 type = '{$data['type']}',
			 subject = '{$data['subject']}',
			 language = '{$data['language']}',
			 status = '{$data['status']}',
			 division_id = '{$data['division_id']}',
			 subdivision_id = '{$data['subdivision_id']}',
			 validation = '{$data['validation']}',
			 recieved_date = '{$data['recieved_date']}',
			 created_on = '{$data['created_on']}',
			 created_by = '{$data['created_by']}', 
			 updated_on = '{$data['updated_on']}', 
			 updated_by = '{$data['updated_by']}'
			 WHERE id =".$edit_mailid);
			  
			  if($data['comments']){
				$session=$this->session->userdata('check_login');
				$date=date('d-m-Y H:i:s');
				$active=1;
				$users_id=$session['login_id'];
				/*$res =$this->db->query("UPDATE comments SET comments = '{$data['comments']}',
				date = '$date',
				active = '$active',
				users_id = '$users_id'
				WHERE mails_id =". $edit_mailid);*/
				
				$del = $this->db->query("DELETE FROM comments WHERE  mails_id =". $edit_mailid);
				
				$res =$this->db->query("INSERT INTO comments ('comments', 'date', 'active', 'users_id', 'mails_id') VALUES ('{$data['comments']}', '$date', '$active', '$users_id', '$edit_mailid')");				
			}
			
			  $delete=$this->db->query("DELETE FROM mail_address WHERE `mail_id`=$edit_mailid");
			  
			  if($data['from_new']!='|' and $data['from_new']!=''){
				$data['from_new']=rtrim($data['from_new'],"|");
					$query = $this->db->query('SELECT * FROM address_book WHERE `id`='.$data['from_new']);
					$row = $query->row();
					$res =$this->db->query("INSERT INTO mail_address ('title','address1','address2','address3', 'city', 'state', 'country', 'pincode', 'mail_id','type') VALUES('$row->title', '$row->address1', '$row->address2', '$row->address3', '$row->city', '$row->state', '$row->country', '$row->pincode', $edit_mailid, 1)");
			
			}
			
			if($data['to_new']!='|' and $data['to_new']!=''){
				$data['to_new']=rtrim($data['to_new'],"| ");
				$to=explode("|",$data['to_new']);
				foreach($to as $to_id){
					$query = $this->db->query('SELECT * FROM address_book WHERE `id`='.$to_id);
					$row = $query->row();
					$res =$this->db->query("INSERT INTO mail_address ('title','address1','address2','address3', 'city', 'state', 'country', 'pincode', 'mail_id','type') VALUES('$row->title', '$row->address1', '$row->address2', '$row->address3', '$row->city', '$row->state', '$row->country', '$row->pincode', $edit_mailid, 2)");
				}
			}
			
			if($data['cc_new']!='|' and $data['cc_new']!=''){
				$data['cc_new']=rtrim($data['cc_new'],"| ");
				$cc=explode("|",$data['cc_new']);
				foreach($cc as $cc_id){
					$query = $this->db->query('SELECT * FROM address_book WHERE `id`='.$cc_id);
					$row = $query->row();
					$res =$this->db->query("INSERT INTO mail_address ('title','address1','address2','address3', 'city', 'state', 'country', 'pincode', 'mail_id','type') VALUES('$row->title', '$row->address1', '$row->address2', '$row->address3', '$row->city', '$row->state', '$row->country', '$row->pincode', $edit_mailid, 3)");
				}
			}
			
			if($result){
				$session=$this->session->userdata('check_login');
				$this->purge_log();
				$action="Inmail with Mail No ".$data['mailno']." has been updated by ".$session['login_name'];
				$date=date('d-m-Y H:i:s');
				$active=1;
				$users_id=$session['login_id'];
				$res =$this->db->query("INSERT INTO log ('action', 'date', 'active', 'users_id', 'mails_id') VALUES ('$action', '$date', '$active', '$users_id', '$edit_mailid')");
			}
			
		}
		if($result)
			return true;
		else
			return false;
	}
	
	function edit_staging($mailid)
	{
		$query = $this->db->query('SELECT m.*, c.comments FROM mails m
		LEFT JOIN comments c ON m.id = c.mails_id 
		WHERE ( m.id = '.$mailid.')');
		$mail_row = $query->row_array();
		if($mail_row){
			
			$from_address_qry = $this->db->query('SELECT ab.id, ma.title FROM mail_address ma
			JOIN address_book ab ON ma.title=ab.title
			WHERE (ma.mail_id = "'.$mail_row['id'].'" and ma.type=1)');
			$from_mail_address =$from_address_qry->row_array();
			$mail_row['from']=$from_mail_address;
			
			$to_address_qry = $this->db->query('SELECT ab.id, ma.title FROM mail_address ma
			JOIN address_book ab ON ma.title=ab.title
			WHERE (ma.mail_id = "'.$mail_row['id'].'" and ma.type=2)');
			$to_mail_adress =$to_address_qry->result_array();
			$res_toadd ="";
			$res_toid="";
			foreach($to_mail_adress as $toadd){
				$res_toid .= $toadd['id']."|";
				$res_toadd .= $toadd['title']."~";
			}
			$mail_row['to'] = rtrim($res_toadd,"~");
			$mail_row['to_id'] = rtrim($res_toid,"|");
			
			$cc_address_qry = $this->db->query('SELECT ab.id, ma.title FROM mail_address ma
			JOIN address_book ab ON ma.title=ab.title
			WHERE (ma.mail_id = "'.$mail_row['id'].'" and ma.type=3)');
			$cc_mail_adress =$cc_address_qry->result_array();
			$res_ccadd ="";
			$res_ccid ="";
			foreach($cc_mail_adress as $ccadd){
				$res_ccid .= $ccadd['id']."|";
				$res_ccadd .= $ccadd['title']."~";
			}
			$mail_row['cc'] = rtrim($res_ccadd,"~");
			$mail_row['cc_id'] = rtrim($res_ccid,"|");//echo "<pre>";print_r($mail_row);
			return $mail_row;
		}

	}
	
	function create_distributed_list($mailids)
	{
		$mail_id=explode(",",$mailids);
		foreach($mail_id as $mailid){
			$qry = $this->db->query("SELECT validation FROM mails WHERE id=".$mailid );
			$res = $qry->row_array();
			if($res['validation']==1 or $res['validation']==2){
			$result = $this->db->query("UPDATE mails SET `status`=1 WHERE `id`=".$mailid);
			}
		}
		if($result)
			echo true;
		else
			echo false;
	}
	
	function fetch_mail()
	{
		$db = $this->db;
		$result = $db->query("SELECT id,title FROM address_book ");
		
		$data = array();
		foreach ($result->result() as $row)
		{
			$data[] = $row->title;
			//$data[] = array("value"=>$row->id,"label"=>$row->title);	
		}	
		return ($data);
	}
	
	function add_address($val)
	{	
		$qry = $this->db->query("SELECT * from address_book where type = 2 ORDER BY id DESC" );
		if($qry->num_rows() > TEMP_ADDRESS_COUNT ){
			$rows = $qry->num_rows() - TEMP_ADDRESS_COUNT;
			$result = $this->db->query("DELETE FROM address_book WHERE id in(SELECT id FROM address_book WHERE type=2  LIMIT 0,".$rows.")" );
		}
		
		$res = $this->db->query("INSERT INTO address_book ('name','address1','address2','address3','title','city','state','country','pincode','type','active') VALUES ('".$val['name']."','".$val['address1']."','".$val['address2']."','".$val['address3']."','".$val['title']."','".$val['city']."','".$val['state']."','".$val['country']."','".$val['pincode']."','".$val['type']."',1)");
		
		$result = $this->db->insert_id();
		
		$newresult = $this->db->query("SELECT id,title FROM address_book WHERE id =".$result);
		$data = array();
		foreach ($newresult->result() as $row)
		{
			$data['address_id'] = $row->id;
			$data['title'] = $row->title;	
		}
		return $data; 
		
	}
	
	function fetch_address_id($title)
	{
		$newresult = $this->db->query("SELECT `id`,`title` FROM `address_book` WHERE  title =  '".$title."'");		
		$data = array();
		foreach ($newresult->result() as $row)
		{
			$data[] = $row->id;
		}
		return $data; 
	}
	
	
	function  fetch_title($title)
	{
		$newresult = $this->db->query("SELECT `title` FROM `address_book` WHERE  title =  '".$title."'");		
		$data = array();
		foreach ($newresult->result() as $row)
		{
			$data[] = $row->title;
		}
		return $data; 
		
	}
	
	function inmailview($mailid)
	{
		$query = $this->db->query('SELECT m.*, d.division, s.subdivision, u.firstname FROM mails m 
		                           LEFT JOIN division d on d.id =m.division_id
								   LEFT JOIN subdivision s on s.id =m.subdivision_id
								   LEFT JOIN users u ON u.id = m.owner
								   WHERE m.type=1 and m.id='.$mailid);
		$mails=$query->row_array();
		if(!empty($mails)){
				$from_address_qry = $this->db->query('SELECT * FROM mail_address WHERE (mail_id = "'.$mails['id'].'" and type=1)');
				$from_mail_address =$from_address_qry->row_array();
				$mails['from']=$from_mail_address;
				
				$to_address_qry = $this->db->query('SELECT * FROM mail_address WHERE (mail_id = "'.$mails['id'].'" and type=2)');
				$to_mail_adress =$to_address_qry->result_array();
				$mails['to']=$to_mail_adress;
				 
				 $cc_address_qry = $this->db->query('SELECT * FROM mail_address WHERE (mail_id = "'.$mails['id'].'" and type=3)');
				$cc_mail_adress =$cc_address_qry->result_array();
				$mails['cc'] = $cc_mail_adress;		
			//echo "<pre>";print_r($mails);die;			
			return $mails;
		}
	}
	
	function email_details($id)
	{
		$qry = $this->db->query("SELECT * FROM mail_address WHERE id=".$id);
		$mail_address =$qry->row_array();
		return $mail_address;
	}
	
	
	function search_total_rows($title,$url= '')
	{	
	$title = SQLite3::escapeString($title);
		$ms_session = $this->session->userdata('mail_status');
		$mail_status = ($ms_session!='')?$ms_session:1;//$mail_status = isset($ms_session)?$ms_session:1;
		if($mail_status == 2)
			$ms_qry = ' ma.status=2';
		else if($mail_status == 1)
			$ms_qry = ' ma.status=1';
		else if($mail_status == 0)
			$ms_qry = ' (ma.status=1 or ma.status=2 or ma.status=3)';
		
		
		$div_data = $this->session->userdata('get_div');
		$div_qry  = "";
		if(isset($div_data['subdivision_id'])){
		if($div_data['division_id']!=''){
			$div_qry=" and ma.division_id = ".$div_data['division_id']." ";
		}
		}
		/*if(isset($div_data['subdivision_id']) && $div_data['subdivision_id']!=''){
			$div_qry.=" and ma.subdivision_id = ".$div_data['subdivision_id']." ";
		}*/
			
		$condition = "";
		$condition = "and (ma.subject  LIKE  '".'%'.$title.'%'."' or ma.mail_ref  LIKE  '".'%'.$title.'%'."' or c.comments LIKE  '".'%'.$title.'%'."' or ma.mail_no='".$title."') ";
		
		if($mail_status == 3){
			$ms_qry.=  " (substr(ma.action_date, 7, 4) ||  '-' || substr(ma.action_date, 4, 2) ||  '-' ||  substr(ma.action_date, 1, 2)) < date('now') and ma.status=1 ";
		}
		
		if($url == 'outmail'){	
	
		$query = $this->db->query("SELECT ma.*, c.comments FROM mails ma
			LEFT JOIN comments c ON c.mails_id = ma.id
			WHERE ".$ms_qry." and  ma.type ='2' ".$condition.$div_qry." GROUP BY ma.id ORDER BY ma.id DESC  ");
		}else{
		$query = $this->db->query("SELECT ma.*,c.comments,(SELECT divi.division FROM division divi  WHERE divi.id = ma.division_id and ma.type= '1') as division FROM mails ma 
			LEFT JOIN comments c ON c.mails_id = ma.id
			WHERE ".$ms_qry." and ma.type ='1' ".$condition.$div_qry."  GROUP BY ma.id ORDER BY ma.id DESC  ");	
		}
		$mails_count=$query->num_rows();
		return $mails_count;
	}
	
	
	function search_subject($title,$url= '',$limit='',$start='')
	{	
	$title = SQLite3::escapeString($title);
		$ms_session = $this->session->userdata('mail_status');
		$mail_status = ($ms_session!='')?$ms_session:1;//$mail_status = isset($ms_session)?$ms_session:1;
		if($mail_status == 2)
			$ms_qry = ' ma.status=2';
		else if($mail_status == 1)
			$ms_qry = ' ma.status=1';
		else if($mail_status == 0)
			$ms_qry = ' (ma.status=1 or ma.status=2 or ma.status=3)';
			
		$div_data = $this->session->userdata('get_div');
		$div_qry  = "";
		if(isset($div_data['subdivision_id'])){
		if($div_data['division_id']!=''){
			$div_qry=" and ma.division_id = ".$div_data['division_id']." ";
		}
		}
		/*if(isset($div_data['subdivision_id']) && $div_data['subdivision_id']!=''){
			$div_qry.=" and ma.subdivision_id = ".$div_data['subdivision_id']." ";
		}*/
		
		$condition = "";
		$condition = "and (ma.subject  LIKE  '".'%'.$title.'%'."' or ma.mail_ref  LIKE  '".'%'.$title.'%'."' or c.comments LIKE  '".'%'.$title.'%'."' or ma.mail_no='".$title."') ";
		
		if($mail_status == 3){
			$ms_qry.=  " (substr(ma.action_date, 7, 4) ||  '-' || substr(ma.action_date, 4, 2) ||  '-' ||  substr(ma.action_date, 1, 2)) < date('now') and ma.status=1 ";
		}
		
		if($limit!='')
	   		$limit = " LIMIT $start,$limit";
		if($url == 'outmail'){
			
			
			$search_q = "SELECT ma.*, c.comments,(SELECT divi.division FROM division divi  WHERE divi.id = ma.division_id ) as division,(SELECT sdiv.code FROM subdivision sdiv  WHERE sdiv.id = ma.subdivision_id ) as subdivision, sub.mail_id,GROUP_CONCAT(sub.`from`, ',') as`from` , GROUP_CONCAT(sub.`to`, ',') as `to`,GROUP_CONCAT(sub.`cc`, ',') as `cc` FROM mails ma
			LEFT JOIN comments c ON c.mails_id = ma.id
			LEFT JOIN (SELECT mail_id,
(  CASE type   when 1 then GROUP_CONCAT(title, ',') END)  AS `from` ,
           (  CASE type   when 2 then GROUP_CONCAT(title,  ',') END)  AS `to` ,
           (  CASE type   when 3 then GROUP_CONCAT(title,  ',') END)  AS `cc`           
            FROM mail_address 
             group by type,mail_id)as sub ON sub.mail_id = ma.id 			
			WHERE ".$ms_qry." and  ma.type ='2' ".$condition.$div_qry." 
			GROUP BY ma.id 
			order by substr(ma.recieved_date, 7, 4) ||  '-' || substr(ma.recieved_date, 4, 2) ||  '-' ||  substr(ma.recieved_date, 1, 2)  DESC,ma.mail_no ASC ".$limit;
		}else{
			$search_q = "SELECT ma.*,c.comments,(SELECT divi.division FROM division divi  WHERE divi.id = ma.division_id ) as division,(SELECT sdiv.code FROM subdivision sdiv  WHERE sdiv.id = ma.subdivision_id ) as subdivision, sub.mail_id,GROUP_CONCAT(sub.`from`, ',') as`from` , GROUP_CONCAT(sub.`to`, ',') as `to`,GROUP_CONCAT(sub.`cc`, ',') as `cc` FROM mails ma 
			LEFT JOIN comments c ON c.mails_id = ma.id
			LEFT JOIN (SELECT mail_id,
(  CASE type   when 1 then GROUP_CONCAT(title, ',') END)  AS `from` ,
           (  CASE type   when 2 then GROUP_CONCAT(title,  ',') END)  AS `to` ,
           (  CASE type   when 3 then GROUP_CONCAT(title,  ',') END)  AS `cc`           
            FROM mail_address 
             group by type,mail_id)as sub ON sub.mail_id = ma.id
			WHERE ".$ms_qry." and ma.type ='1' ".$condition.$div_qry."  
			GROUP BY ma.id 
			order by substr(ma.recieved_date, 7, 4) ||  '-' || substr(ma.recieved_date, 4, 2) ||  '-' ||  substr(ma.recieved_date, 1, 2)  DESC,ma.mail_no ASC  ".$limit;	
		}
		
		$query = $this->db->query($search_q);
		$mails=$query->result_array();
		
		if(!empty($mails)){
			foreach($mails as &$row){				
				$row['comments'] = $this->listallcomments($row['id']);
     		}	
		}
		
		return $mails;
		/*if(!empty($mails)){$i=0;
			foreach($mails as $row){
				
				$mail_addr_q = $this->db->query("select sub.mail_id,GROUP_CONCAT(sub.`from`, ',') as`from` , GROUP_CONCAT(sub.`to`, ',') as `to`,GROUP_CONCAT(sub.`cc`, ',') as `cc` from(SELECT 
  mail_id,
           (  CASE type when 1 then GROUP_CONCAT(title, ',') END)  AS `from` ,
           (  CASE type   when 2 then GROUP_CONCAT(title, ',') END)  AS `to` ,
           (  CASE type   when 3 then GROUP_CONCAT(title, ',') END)  AS `cc` 
          
            FROM mail_address 
            WHERE (mail_id = ".$row['id'].") and (type=1 or type=2 or type=3) group by type) as sub group by sub.mail_id");
			$mail_address =$mail_addr_q->row_array();
			$mails[$i]['from'] = isset($mail_address['from'])?$mail_address['from']:'';
			$mails[$i]['to'] =  isset($mail_address['to'])?$mail_address['to']:'';
			$mails[$i]['cc'] =  isset($mail_address['cc'])?$mail_address['cc']:'';
			$i++;
			}	
					
			return $mails;
		}*/
	}
	
	
	function search_mailaddress_total($title,$url= '')
	{	
	$title = SQLite3::escapeString($title);
		$ms_session = $this->session->userdata('mail_status');
		$mail_status = ($ms_session!='')?$ms_session:1;//$mail_status = isset($ms_session)?$ms_session:1;
		if($mail_status == 2)
			$ms_qry = ' ma.status=2';
		else if($mail_status == 1)
			$ms_qry = ' ma.status=1';
		else if($mail_status == 0)
			$ms_qry = ' (ma.status=1 or ma.status=2 or ma.status=3)';
		
		
		$div_data = $this->session->userdata('get_div');
		$div_qry  = "";
		if($div_data['division_id']!=''){
			$div_qry=" and ma.division_id = ".$div_data['division_id']." ";
		}
		if($div_data['subdivision_id']!=''){
			$div_qry.=" and ma.subdivision_id = ".$div_data['subdivision_id']." ";
		}
			
		$condition = "";
		$condition = "and ( ma.mail_no='".$title."') ";
		
		if($mail_status == 3){
			$ms_qry.=  " (substr(ma.action_date, 7, 4) ||  '-' || substr(ma.action_date, 4, 2) ||  '-' ||  substr(ma.action_date, 1, 2)) < date('now') and ma.status=1 ";
		}
		
		if($url == 'outmail'){	
	
		$query = $this->db->query("SELECT ma.*, c.comments FROM mails ma
			LEFT JOIN comments c ON c.mails_id = ma.id
			WHERE ".$ms_qry." and  ma.type ='2' ".$condition.$div_qry." GROUP BY ma.id ORDER BY ma.id DESC  ");
		}else{
		$query = $this->db->query("SELECT ma.*,c.comments,(SELECT divi.division FROM division divi  WHERE divi.id = ma.division_id and ma.type= '1') as division FROM mails ma 
			LEFT JOIN comments c ON c.mails_id = ma.id
			WHERE ".$ms_qry." and ma.type ='1' ".$condition.$div_qry."  GROUP BY ma.id ORDER BY ma.id DESC  ");	
		}
		$mails_count=$query->num_rows();
		return $mails_count;
	}
	
	
	
	function search_mailaddress($title,$url= '',$limit='',$start='')
	{	
		$title = SQLite3::escapeString($title);
		$ms_session = $this->session->userdata('mail_status');
		$mail_status = ($ms_session!='')?$ms_session:1;//$mail_status = isset($ms_session)?$ms_session:1;
		if($mail_status == 2)
			$ms_qry = ' ma.status=2';
		else if($mail_status == 1)
			$ms_qry = ' ma.status=1';
		else if($mail_status == 0)
			$ms_qry = ' (ma.status=1 or ma.status=2 or ma.status=3)';
			
		$div_data = $this->session->userdata('get_div');
		$div_qry  = "";
		if($div_data['division_id']!=''){
			$div_qry=" and ma.division_id = ".$div_data['division_id']." ";
		}
		if($div_data['subdivision_id']!=''){
			$div_qry.=" and ma.subdivision_id = ".$div_data['subdivision_id']." ";
		}
		
		$condition = "";
		$condition = "and (ma.mail_no  LIKE  '".'%'.$title.'%'."') ";
		
		  
		if($mail_status == 3){
			$ms_qry.=  " (substr(ma.action_date, 7, 4) ||  '-' || substr(ma.action_date, 4, 2) ||  '-' ||  substr(ma.action_date, 1, 2)) < date('now') and ma.status=1 ";
		}
		
		if($limit!='')
	   		$limit = " LIMIT $start,$limit";
		if($url == 'outmail'){
			
			
			$search_q = "SELECT ma.*, c.comments,(SELECT divi.division FROM division divi  WHERE divi.id = ma.division_id ) as division,(SELECT sdiv.code FROM subdivision sdiv  WHERE sdiv.id = ma.subdivision_id ) as subdivision, sub.mail_id,GROUP_CONCAT(sub.`from`, ',') as`from` , GROUP_CONCAT(sub.`to`, ',') as `to`,GROUP_CONCAT(sub.`cc`, ',') as `cc` FROM mails ma
			LEFT JOIN comments c ON c.mails_id = ma.id
			LEFT JOIN (SELECT mail_id,
(  CASE type   when 1 then GROUP_CONCAT(title, ',') END)  AS `from` ,
           (  CASE type   when 2 then GROUP_CONCAT(title,  ',') END)  AS `to` ,
           (  CASE type   when 3 then GROUP_CONCAT(title,  ',') END)  AS `cc`           
            FROM mail_address 
             group by type,mail_id)as sub ON sub.mail_id = ma.id 			
			WHERE ".$ms_qry." and  ma.type ='2' ".$condition.$div_qry." 
			GROUP BY ma.id 
			order by substr(ma.recieved_date, 7, 4) ||  '-' || substr(ma.recieved_date, 4, 2) ||  '-' ||  substr(ma.recieved_date, 1, 2)  DESC,ma.mail_no ASC ".$limit;
		}else{
			$search_q = "SELECT ma.*,c.comments,(SELECT divi.division FROM division divi  WHERE divi.id = ma.division_id ) as division,(SELECT sdiv.code FROM subdivision sdiv  WHERE sdiv.id = ma.subdivision_id ) as subdivision, sub.mail_id,GROUP_CONCAT(sub.`from`, ',') as`from` , GROUP_CONCAT(sub.`to`, ',') as `to`,GROUP_CONCAT(sub.`cc`, ',') as `cc` FROM mails ma 
			LEFT JOIN comments c ON c.mails_id = ma.id
			LEFT JOIN (SELECT mail_id,
(  CASE type   when 1 then GROUP_CONCAT(title, ',') END)  AS `from` ,
           (  CASE type   when 2 then GROUP_CONCAT(title,  ',') END)  AS `to` ,
           (  CASE type   when 3 then GROUP_CONCAT(title,  ',') END)  AS `cc`           
            FROM mail_address 
             group by type,mail_id)as sub ON sub.mail_id = ma.id
			WHERE ".$ms_qry." and ma.type ='1' ".$condition.$div_qry."  
			GROUP BY ma.id 
			order by substr(ma.recieved_date, 7, 4) ||  '-' || substr(ma.recieved_date, 4, 2) ||  '-' ||  substr(ma.recieved_date, 1, 2)  DESC,ma.mail_no ASC  ".$limit;	
		}
		$query = $this->db->query($search_q);
		$mails=$query->result_array();
		return $mails;
		
	}
	
	function search_mails_count($data)
	{	
		$subdivision = isset($data['subdivision'])?$data['subdivision']:'';
		$division = isset($data['division'])?$data['division']:'';
		$search_by = isset($data['search_by'])?$data['search_by']:'';
		$search_by_txt =isset($data['search_by_txt'])?SQLite3::escapeString($data['search_by_txt']):'';
		$to_datepicker = isset($data['to_datepicker'])?$data['to_datepicker']:'';
    	$from_datepicker = isset($data['from_datepicker'])?$data['from_datepicker']:'';
		$type = isset($data['type'])?$data['type']:'';
		$with_comm = isset($data['with_comm'])?$data['with_comm']:'';
		$mail_status = isset($data['status'])?$data['status']:0;		
		
		$with_out_condition ='';
		if($with_comm == '0'){
			$comment_condition = 'LEFT JOIN comments cm  ON cm.mails_id = ma.id  ';	
		}elseif($with_comm == '1'){
			$comment_condition = 'LEFT JOIN comments cm  ON cm.mails_id = ma.id  ';	
			$with_out_condition ='and cm.mails_id IS NULL';	
		}else{
			$comment_condition = 'JOIN comments cm  ON cm.mails_id = ma.id  ';		
		}
		
		$mail_status = isset($data['status'])?$data['status']:1;
		if($mail_status == 2)
			$ms_qry = ' ma.status=2 ';
		else if($mail_status == 1)
			$ms_qry = ' ma.status=1 ';
		else if($mail_status == 0)
			$ms_qry = ' (ma.status=1 or ma.status=2)';
		
		if($mail_status == 3){
			$ms_qry =  " (substr(ma.action_date, 7, 4) ||  '-' || substr(ma.action_date, 4, 2) ||  '-' ||  substr(ma.action_date, 1, 2)) < date('now') and ma.status=1 ";
		}
		
		$mail_typ ="";
		if($type)
		$mail_typ = " and ma.type =  '".$type."' ";
		$where_for_to ='';		 
		$where = $ms_qry.$mail_typ;
		
		
		if($division){
			$where .= " and ( ma.division_id LIKE '".$division."' ) ";			
		}
		if($subdivision){
			$where .= " and ( ma.division_id LIKE '".$division."' and ma.subdivision_id LIKE '".$subdivision."' ) ";			
		}
		
		if($search_by ==''){
			$where_for_to = "JOIN mail_address madd ON madd.mail_id = ma.id ";
			$where .= "and (ma.subject LIKE '%".$search_by_txt."%' OR ma.mail_ref LIKE '%".$search_by_txt."%' OR cm.comments LIKE '%".$search_by_txt."%' OR madd.title LIKE '%".$search_by_txt."%')";
		}
		
		if($search_by == '1'){
			$where_for_to = "JOIN mail_address madd ON madd.mail_id = ma.id ";
			$where .= "and madd.type = 1 and madd.title LIKE '%".$search_by_txt."%'";			
		}
		
		if($search_by == '2'){
			$where_for_to = "JOIN mail_address madd  ON madd.mail_id = ma.id "; 
			$where .= " and madd.type = 2 and madd.title LIKE '%".$search_by_txt."%' ";
		}
		
		if($search_by == '4'){
			$where .= "and cm.comments LIKE '%".$search_by_txt."%' ";
		}
		if($search_by == '6'){
			$where .= "and ma.mail_ref LIKE '%".$search_by_txt."%' ";
		}
		if($search_by == '5'){
			$where_for_to = "JOIN mail_address madd  ON madd.mail_id = ma.id ";
			$where .= "and madd.type = 3 and madd.title LIKE '%".$search_by_txt."%' ";
		}
		if(!empty($search_by_txt) && $search_by == '3'){				
				$where .= " and ma.subject LIKE '%".$search_by_txt."%'";
		}
		if($from_datepicker && $to_datepicker){
			$where.="and ((substr(ma.recieved_date, 7, 4) ||  '-' || substr(ma.recieved_date, 4, 2) ||  '-' ||  substr(ma.recieved_date, 1, 2)) BETWEEN '".date("Y-m-d",strtotime($from_datepicker))."'  AND '".date("Y-m-d",strtotime($to_datepicker))."') ";			
		}	
	
	$query_sql = "select ma.created_on,ma.mail_no,ma.type, ma.subject, ma.recieved_date, ma.mail_ref, ma.date, ma.status, ma.id, ma.remarks,ma.division_id, ma.subdivision_id, ma.validation, ma.action_date, ma.close_date, sub.code,d.division, sub.code as subdivision, sub.mail_id,GROUP_CONCAT(DISTINCT sub.`from`) as `from`, GROUP_CONCAT(DISTINCT sub.`to`) as `to`,GROUP_CONCAT(DISTINCT sub.`cc`) as `cc`   
FROM mails as ma LEFT join subdivision sub on sub.id = ma.subdivision_id LEFT JOIN division d on d.id =ma.division_id
$comment_condition
 ".$where_for_to." 
LEFT JOIN (SELECT mail_id, 
(  CASE type   when 1 then  GROUP_CONCAT( title, ',') END)  AS `from` ,
           (  CASE type   when 2 then GROUP_CONCAT(title,  ',') END)  AS `to` ,
           (  CASE type   when 3 then GROUP_CONCAT(title,  ',') END)  AS `cc`  FROM mail_address 
             group by type,mail_id)as sub  ON sub.mail_id = ma.id 
WHERE ".$where." $with_out_condition
group by ma.id order by substr(ma.recieved_date, 7, 4) ||  '-' || substr(ma.recieved_date, 4, 2) ||  '-' ||  substr(ma.recieved_date, 1, 2) DESC,ma.mail_no ASC "; //and ma.id NOT IN (SELECT comments FROM comments where mails_id = ma.id) 
		
		$query = $this->db->query($query_sql);
		$mails_count = $query->result_array();
		
		return count(array_filter($mails_count));
		/*$mails_count=$query->num_rows();
		return $mails_count;*/
	}
	
	function search_mails($data,$limit='',$start='')
	{	
		
		$subdivision = isset($data['subdivision'])?$data['subdivision']:'';
		$division = isset($data['division'])?$data['division']:'';
		$search_by = isset($data['search_by'])?$data['search_by']:'';
		$search_by_txt =isset($data['search_by_txt'])?SQLite3::escapeString($data['search_by_txt']):'';
		$to_datepicker = isset($data['to_datepicker'])?$data['to_datepicker']:'';
    	$from_datepicker = isset($data['from_datepicker'])?$data['from_datepicker']:'';
		$type = isset($data['type'])?$data['type']:'';
		
		$mail_status = isset($data['status'])?$data['status']:0;
		$with_comm = isset($data['with_comm'])?$data['with_comm']:'';
		
		$with_out_condition ='';
		if($with_comm == '0'){
			$comment_condition = 'LEFT JOIN comments cm  ON cm.mails_id = ma.id  ';	
		}elseif($with_comm == '1'){
			$comment_condition = 'LEFT JOIN comments cm  ON cm.mails_id = ma.id  ';	
			$with_out_condition ='and cm.mails_id IS NULL';	
		}else{
			$comment_condition = 'JOIN comments cm  ON cm.mails_id = ma.id  ';		
		}
		
		if($mail_status == 2)
			$ms_qry = ' ma.status=2 ';
		else if($mail_status == 1)
			$ms_qry = ' ma.status=1 ';
		else if($mail_status == 0)
			$ms_qry = ' ma.status IN (1,2,3) ';
		
		if($mail_status == 3){
			$ms_qry =  " (substr(ma.action_date, 7, 4) ||  '-' || substr(ma.action_date, 4, 2) ||  '-' ||  substr(ma.action_date, 1, 2)) < date('now') and ma.status=1 ";
		}
			
		$mail_typ ="";
		if($type)
		$mail_typ = " and ma.type =  '".$type."' ";
		$where_for_to ='';		 
		$where = $ms_qry.$mail_typ;
		
		if($division){
			$where .= " and ( ma.division_id LIKE '".$division."' ) ";			
		}
		if($subdivision){
			$where .= " and ( ma.division_id LIKE '".$division."' and ma.subdivision_id LIKE '".$subdivision."' ) ";			
		}
		if($search_by == ''){
			$where_for_to = "JOIN mail_address madd ON madd.mail_id = ma.id ";
			$where .= "and (ma.subject LIKE '%".$search_by_txt."%' OR ma.mail_ref LIKE '%".$search_by_txt."%' OR cm.comments LIKE '%".$search_by_txt."%' OR madd.title LIKE '%".$search_by_txt."%')";
		}
		
		if($search_by == '1'){
			$where_for_to = "JOIN mail_address madd ON madd.mail_id = ma.id ";
			$where .= "and madd.type = 1 and madd.title LIKE '%".$search_by_txt."%'";			
		}
		
		if($search_by == '2'){
			$where_for_to = "JOIN mail_address madd  ON madd.mail_id = ma.id "; 
			$where .= " and madd.type = 2 and madd.title LIKE '%".$search_by_txt."%' ";
		}
		
		if($search_by == '4'){
			$where .= "and cm.comments LIKE '%".$search_by_txt."%' ";
		}
		
		if($search_by == '6'){
			$where .= "and ma.mail_ref LIKE '%".$search_by_txt."%' ";
		}
		
		if($search_by == '5'){
			$where_for_to = "JOIN mail_address madd  ON madd.mail_id = ma.id ";
			$where .= "and madd.type = 3 and madd.title LIKE '%".$search_by_txt."%' ";
		}
		if(!empty($search_by_txt) && $search_by == '3'){				
				$where .= " and ma.subject LIKE '%".$search_by_txt."%'";
		}
		if($from_datepicker && $to_datepicker){
			$where.="and ((substr(ma.recieved_date, 7, 4) ||  '-' || substr(ma.recieved_date, 4, 2) ||  '-' ||  substr(ma.recieved_date, 1, 2)) BETWEEN '".date("Y-m-d",strtotime($from_datepicker))."'  AND '".date("Y-m-d",strtotime($to_datepicker))."') ";
			
		}	
		if($limit!='')
	   		$limit = " LIMIT $start,$limit";

			$query = $this->db->query("select ma.created_on,ma.mail_no,ma.type, ma.subject, ma.recieved_date, ma.mail_ref, ma.date, ma.status, ma.id, ma.remarks,ma.division_id, ma.subdivision_id, ma.validation, ma.action_date, ma.close_date, sub.code,d.division, sub.code as subdivision, sub.mail_id,GROUP_CONCAT(DISTINCT sub.`from`) as `from`, GROUP_CONCAT(DISTINCT sub.`to`) as `to`,GROUP_CONCAT(DISTINCT sub.`cc`) as `cc`   
FROM mails as ma LEFT join subdivision sub on sub.id = ma.subdivision_id LEFT JOIN division d on d.id =ma.division_id
$comment_condition
 ".$where_for_to." 
LEFT JOIN (SELECT mail_id, 
(  CASE type   when 1 then  GROUP_CONCAT( title, ',') END)  AS `from` ,
           (  CASE type   when 2 then GROUP_CONCAT(title,  ',') END)  AS `to` ,
           (  CASE type   when 3 then GROUP_CONCAT(title,  ',') END)  AS `cc`  FROM mail_address 
             group by type,mail_id)as sub  ON sub.mail_id = ma.id 
WHERE ".$where." $with_out_condition
group by ma.id order by substr(ma.recieved_date, 7, 4) ||  '-' || substr(ma.recieved_date, 4, 2) ||  '-' ||  substr(ma.recieved_date, 1, 2) DESC,ma.mail_no ASC ".$limit); //and ma.id NOT IN (SELECT comments FROM comments where mails_id = ma.id) 
		 
		 //sub.`from`

		$mails=$query->result_array();
		
		if(!empty($mails)){
			foreach($mails as &$row){				
				$row['comments'] = $this->listallcomments($row['id']);
     		}	
		}

		return $mails;
		
	}
	
	function listallcomments($id){

		$mail_addr_q = $this->db->query("select GROUP_CONCAT(comments ,'  ##') as cmt from comments where mails_id = ".$id."");
		$result = $mail_addr_q->result_array();

		return $result;
		
	}
	
	function insert_search_history($data)
	{	//echo "<pre>";print_r($data);die;
		$created_date=date('d-m-Y H:i:s');
		$data['type'] = ($data['type']!='')?$data['type']:0;
		$data['subject'] = '';
		$data['from_mails'] = '';
		$data['to'] = '';
		$data['comments'] = '';
		$data['copy_to'] = '';
		
		
		$data['search_by_txt'] = SQLite3::escapeString($data['search_by_txt']);
		if($data['search_by'] == 3)
			$data['subject'] = $data['search_by_txt'];
		else if($data['search_by'] == 1)
			$data['from_mails'] = $data['search_by_txt'];
		else if($data['search_by'] == 2)
			$data['to'] = $data['search_by_txt'];
		else  if($data['search_by'] == 4)
			$data['comments'] = $data['search_by_txt'];
		else  if($data['search_by'] == 5)
			$data['copy_to'] = $data['search_by_txt'];
		else  if($data['search_by'] == 6)
			$data['mail_ref'] = $data['search_by_txt'];
			
		if($data['template_id']==''){
			$res = $this->db->query("INSERT INTO `search_template` (`template_name`, `type`, `subject`, `division`, `subdivision`, `from_date`, `to_date`, `to`, `from_mails`, `created_on`, `user_id`, `active`,`comments`, `status`, `copy_to`, `mail_ref`, `with_comm`) VALUES('{$data['save_template_txt']}', {$data['type']}, '{$data['subject']}', '{$data['division']}', '{$data['subdivision']}', '{$data['from_datepicker']}', '{$data['to_datepicker']}', '{$data['to']}', '{$data['from_mails']}', '".$created_date."', {$data['user_id']}, 1, '{$data['comments']}', '{$data['status']}', '{$data['copy_to']}', '{$data['mail_ref']}', '{$data['with_comm']}')");
		}else{
			$res=$this->db->query("UPDATE search_template SET
			template_name = '{$data['save_template_txt']}',
			type = {$data['type']},
			subject = '{$data['subject']}', 
			division = '{$data['division']}',
			subdivision = '{$data['subdivision']}',
			from_date = '{$data['from_datepicker']}',
			to_date = '{$data['to_datepicker']}',
			`to` = '{$data['to']}',
			from_mails = '{$data['from_mails']}',
			created_on = '".$created_date."',
			user_id = {$data['user_id']},
			active = 1,
			comments = '{$data['comments']}',
			copy_to = '{$data['copy_to']}',
			mail_ref = '{$data['mail_ref']}',
			status = '{$data['status']}',
			with_comm = '{$data['with_comm']}'
			 WHERE id=".$data['template_id']);
		}
		if($res)
		return true;
		else 
		return false; 
	}
	
	
	function list_today_search()
	{
		$this->load->library("session");
		
		$login = $this->session->userdata('check_login');
		$where = " ";
		
		if(isset($login['login_type']) && $login['login_type'] != 1 ){
			$div_data = $this->session->userdata('get_div');				
			$where = 'WHERE u.division_id = '.$div_data['division_id'];
		}
			
		$insert_history = $this->db->query("SELECT DISTINCT s.id,s.template_name from search_template s 
		join users u on s.user_id = u.id $where ");	
		$data = array();
		$data =$insert_history->result_array();
		return $data;		
	}
	
	function get_searchterms($template_name)
	{
		$query = $this->db->query("SELECT * FROM search_template where template_name= '".$template_name."' ");
		$result = $query->row_array();
		return $result;
	}
	
	function get_templates()
	{
		$result = $this->db->query('SELECT DISTINCT id,template_name from "search_template" ');		
		$templates = $result->result_array();
		$json=array();
		$temp_id=array();
		foreach($templates as $row){
			$json[] = array("label"=>$row['template_name'],"value"=>$row['id']);
		}
		
		return json_encode($json);
	}
	
	function check_user($data)
	{
		$ses = $this->session->userdata('check_login');
		$cond = '';
		if($data['subdiv_id'])
			$cond = " and subdivision_id=".$data['subdiv_id'];
		
		$qry = $this->db->query("SELECT id FROM users WHERE username = '".$ses['login_name']."' and division_id=".$data['div_id'].$cond );
		$res = $qry->row_array();
		if($res){
			if($res['id'] == $ses['login_id'])//
				echo true;
		}else
			echo false;
	}
	
	function edit_division($data)
	{
		$session=$this->session->userdata('check_login');
		$ac = "";
		if($data['action_date']){
			$ac = ", action_date = '{$data['action_date']}'";
			$action="Inmail with Mail No ".$data['mail_no'].": Action date edited by ".$session['login_name'];
		}
		
		$cl = "";
		if($data['close_date']){
			$cl = ", close_date = '{$data['close_date']}' ";
			$action="Inmail with Mail No ".$data['mail_no'].": Close date edited by ".$session['login_name'];
		}	
		if($data['subdiv_id']) $s = $data['subdiv_id'];
		else $s = '';
			
		$result = $this->db->query("UPDATE mails SET division_id={$data['div_id']},
			 subdivision_id='".$s."' ".$ac."". $cl ."
			 WHERE id=".$data['mail_id']);
			 
			 
		if($data['close_date']!=''){
			$this->db->query("UPDATE mails SET status=2 WHERE id = ".$data['mail_id']);
		}
		
		if($result){
			if($data['action_date'] and $data['close_date'])
				$action="Inmail with Mail No ".$data['mail_no'].": Action date and Close date edited by ".$session['login_name'];
			else
				$action="Inmail with Mail No ".$data['mail_no'].": Division edited by ".$session['login_name'];
				$date=date('d-m-Y H:i:s');
				$active=1;
				$users_id=$session['login_id'];
				$this->purge_log();
				$res =$this->db->query("INSERT INTO log ('action', 'date', 'active', 'users_id', 'mails_id') VALUES ('$action', '$date', '$active', '$users_id', ".$data['mail_id'].")");
				if($res)
					echo true;
		}
		else
			echo false; 
	}
	
	function close_mail($data)
	{
		$result = $this->db->query("UPDATE mails SET status=2 WHERE id = ".$data['mail_id']);
		if($result)
			echo true;
		else 
			echo false;
	}
	
	function delete_staging_list($mailids)
	{
		$mail_id=explode(",",$mailids);
		foreach($mail_id as $mailid){
				$result = $this->db->query("DELETE FROM mails WHERE `id`=".$mailid);
		}
		if($result)
			echo true;
		else
			echo false;
	}
	
	function delete_template($template_id)
	{
		$result = $this->db->query("DELETE FROM search_template WHERE `id`=".$template_id);
		if($result)
			echo true;
		else
			echo false;
	}
	
	function subject_autocomp($term)
	{
		$query = $this->db->query("SELECT Distinct subject FROM mails WHERE subject LIKE '%".$term['subject']."%' ");
		$subject = $query->result_array();
		$json=array();
    	foreach($subject as $sub){
         	$json[]=$sub['subject'];
   		}
		return json_encode($json);
	}
	
	function from_autocomp($term)
	{
		$query = $this->db->query("SELECT Distinct title FROM address_book WHERE title LIKE '%".$term['from']."%' ");
		$subject = $query->result_array();
		$json=array();
    	foreach($subject as $sub){
         	$json[]=$sub['title'];
   		}
		return json_encode($json);
	}
	
	function check_temp_name($temp_name){
		$query    = $this->db->query("SELECT id from search_template WHERE template_name = '".$temp_name."'");
		$rowcount = $query->num_rows();
		if($rowcount>=1)
			return 1;
		else 
			return 0;
	}
	
	function purge_log(){
		
		$select_q = "Select id from log where active = 1";
		$select_r = $this->db->query($select_q);
		$count = $select_r->num_rows();
		if($count>=3000){
		
			$purge_q = "update log set active=0 where id in (select id from log order by id Asc limit 2000)";
			$purge_r = $this->db->query($purge_q);
			
			$del_q ="delete from log where active=0";
			$del_r = $this->db->query($del_q);
			if($del_r)
				return true;
			else
				return false;
		}
		return true;
	}
	
	function get_credits(){
		$query = $this->db->query("SELECT * FROM credit ");
		$result = $query->row_array();
		return $result;
	}
}
	