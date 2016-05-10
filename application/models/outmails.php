<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Campaign Select
 * This model serves to fetch campaign data.
 */
class Outmails extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$ci =& get_instance();
	}
	
	function outmail_total_rows($dt='', $limit='',$start='')
	{
		$ms_session = $this->session->userdata('mail_status');
		$mail_status = ($ms_session!='')?$ms_session:1;//$mail_status = isset($ms_session)?$ms_session:1;
		if($mail_status == 2)
			$ms_qry = ' and m.status=2';
		else if($mail_status == 1)
			$ms_qry = ' and m.status=1';
		else if($mail_status == 0)
			$ms_qry = ' and m.status IN(1,2)';
		
		$div_data = $this->session->userdata('get_div');
		$div_qry  = "";
		if($div_data['division_id']!=''){
			$div_qry=" and m.division_id = ".$div_data['division_id']." ";
		}
		if(isset($div_data['subdivision_id']) && $div_data['subdivision_id']!=''){
			$div_qry.=" and m.subdivision_id = ".$div_data['subdivision_id']." ";
		}
			
		if($dt=='day')
			$dtqry = " and m.recieved_date = '".date("d-m-Y")."'";
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
		$ms_qry = '';
			$dtqry.=  " and (substr(m.action_date, 7, 4) ||  '-' || substr(m.action_date, 4, 2) ||  '-' ||  substr(m.action_date, 1, 2)) < date('now') and m.status=1 ";
		}
			
		if($limit!='')
	   		$limit = " LIMIT $start,$limit";
	
		$query = $this->db->query('SELECT m.*,d.division,d.code as division,sd.code as subdivision  FROM mails m 
		                           LEFT JOIN division d on d.id =m.division_id
								   LEFT JOIN subdivision sd on sd.id =m.subdivision_id
								   WHERE m.type=2 '.$ms_qry.$div_qry.$dtqry.' ORDER BY m.id DESC'.$limit);
		$mails_count=$query->num_rows();
		return $mails_count;
	}
	
	
	function manage_outmail_total_rows($title,$dt='', $limit='',$start='')
	{
		$title = SQLite3::escapeString($title);
		$ms_session = $this->session->userdata('mail_status');
		$mail_status = ($ms_session!='')?$ms_session:1;//$mail_status = isset($ms_session)?$ms_session:1;
		if($mail_status == 2)
			$ms_qry = ' and m.status=2';
		else if($mail_status == 1)
			$ms_qry = ' and m.status=1';
		else if($mail_status == 0)
			$ms_qry = ' and m.status IN(1,2)';
		
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
			$dtqry = " and m.recieved_date = '".date("d-m-Y")."'";
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
		$ms_qry = '';
			$dtqry.=  " and (substr(m.action_date, 7, 4) ||  '-' || substr(m.action_date, 4, 2) ||  '-' ||  substr(m.action_date, 1, 2)) < date('now') and m.status=1 ";
		}
			
		if($limit!='')
	   		$limit = " LIMIT $start,$limit";
	
		$query = $this->db->query('SELECT m.*,d.division,d.code as division,sd.code as subdivision  FROM mails m 
		                           LEFT JOIN division d on d.id =m.division_id
								   LEFT JOIN subdivision sd on sd.id =m.subdivision_id
								   WHERE m.type=2 '.$ms_qry.$div_qry.$dtqry.$condition.' ORDER BY m.id DESC'.$limit);
		$mails_count=$query->num_rows();
		return $mails_count;
	}
	
	
	function outmail_list($dt='', $limit='',$start='')
	{
		$ms_session = $this->session->userdata('mail_status');
		$mail_status = ($ms_session!='')?$ms_session:1;//$mail_status = isset($ms_session)?$ms_session:1;
		if($mail_status == 2)
			$ms_qry = ' and m.status=2';
		else if($mail_status == 1)
			$ms_qry = ' and m.status=1';
		else if($mail_status == 0)
			$ms_qry = ' and m.status IN(1,2)';
		
		$div_data = $this->session->userdata('get_div');
		$div_qry  = "";
		if($div_data['division_id']!=''){
			$div_qry=" and m.division_id = ".$div_data['division_id']." ";
		}
		if(isset($div_data['subdivision_id']) && $div_data['subdivision_id']!=''){
			$div_qry.=" and m.subdivision_id = ".$div_data['subdivision_id']." ";
		}
			
		if($dt=='day')
			$dtqry = " and m.recieved_date = '".date("d-m-Y")."'";
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
		$ms_qry = '';
			$dtqry.=  " and (substr(m.action_date, 7, 4) ||  '-' || substr(m.action_date, 4, 2) ||  '-' ||  substr(m.action_date, 1, 2)) < date('now') and m.status=1 ";
		}
			
		if($limit!='')
	   		$limit = " LIMIT $start,$limit";
			
		$query = $this->db->query('SELECT m.*,d.division,d.code as division,sd.code as subdivision  FROM mails m 
		                           LEFT JOIN division d on d.id =m.division_id
								   LEFT JOIN subdivision sd on sd.id =m.subdivision_id
								   WHERE m.type=2 '.$ms_qry.$div_qry.$dtqry.' 
								   order by substr(m.recieved_date, 7, 4) ||  "-" || substr(m.recieved_date, 4, 2) ||  "-" ||  substr(m.recieved_date, 1, 2)  DESC,m.mail_no ASC'.$limit);
		$mails=$query->result_array();
		if(!empty($mails)){$i=0;
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
			}//echo "<pre>";print_r($mails);
			
			if(!empty($mails)){
				foreach($mails as &$row){				
					$row['comments'] = $this->listallcomments($row['id']);
				}	
			}
			
			return $mails;
		}
	}
	
	function listallcomments($id){

		$mail_addr_q = $this->db->query("select GROUP_CONCAT(comments ,'  ##') as cmt from comments where mails_id = ".$id."");
		$result = $mail_addr_q->result_array();

		return $result;
		
	}
	
	function manage_outmail_list($title,$dt='', $limit='',$start='')
	{
		$title = SQLite3::escapeString($title);
		$ms_session = $this->session->userdata('mail_status');
		$mail_status = ($ms_session!='')?$ms_session:1;//$mail_status = isset($ms_session)?$ms_session:1;
		if($mail_status == 2)
			$ms_qry = ' and m.status=2';
		else if($mail_status == 1)
			$ms_qry = ' and m.status=1';
		else if($mail_status == 0)
			$ms_qry = ' and m.status IN(1,2)';
		
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
			$dtqry = " and m.recieved_date = '".date("d-m-Y")."'";
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
		$ms_qry = '';
			$dtqry.=  " and (substr(m.action_date, 7, 4) ||  '-' || substr(m.action_date, 4, 2) ||  '-' ||  substr(m.action_date, 1, 2)) < date('now') and m.status=1 ";
		}
			
		if($limit!='')
	   		$limit = " LIMIT $start,$limit";
			
		$query = $this->db->query('SELECT m.*,d.division,d.code as division,sd.code as subdivision  FROM mails m 
		                           LEFT JOIN division d on d.id =m.division_id
								   LEFT JOIN subdivision sd on sd.id =m.subdivision_id
								   WHERE m.type=2 '.$ms_qry.$div_qry.$dtqry.$condition.' 
								   order by substr(m.recieved_date, 7, 4) ||  "-" || substr(m.recieved_date, 4, 2) ||  "-" ||  substr(m.recieved_date, 1, 2)  DESC,m.mail_no ASC'.$limit);
		$mails=$query->result_array();
		if(!empty($mails)){$i=0;
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
			}//echo "<pre>";print_r($mails);
			return $mails;
		}
	}
	
	function outmail_division_list_rows($division_id='',$subdiv_id='')
	{
		$ms_session = $this->session->userdata('mail_status');
		$mail_status = ($ms_session!='')?$ms_session:1;//$mail_status = isset($ms_session)?$ms_session:1;
		if($mail_status == 2)
			$ms_qry = ' and m.status=2';
		else if($mail_status == 1)
			$ms_qry = ' and m.status=1';
		else if($mail_status == 0)
			$ms_qry = ' and m.status IN(1,2)';
			
		$condition = "m.type=2";
		if($subdiv_id)
			$condition = "m.type=2 and m.division_id=$division_id and m.subdivision_id=$subdiv_id";
		else if($division_id)
			$condition = "m.type=2 and m.division_id=$division_id";
		
		if($mail_status == 3){
			$ms_qry =  " and (substr(m.action_date, 7, 4) ||  '-' || substr(m.action_date, 4, 2) ||  '-' ||  substr(m.action_date, 1, 2)) < date('now') and m.status=1 ";
		}
		
		$query = $this->db->query('SELECT m.*,d.division  FROM mails m 
		                           LEFT JOIN division d on d.id =m.division_id WHERE '.$condition.$ms_qry);
		$mails_cnt = $query->num_rows();
		return $mails_cnt;
	}
	function outmail_division_list($division_id='',$subdiv_id='', $limit='', $start='')
	{
		$ms_session = $this->session->userdata('mail_status');
		$mail_status = ($ms_session!='')?$ms_session:1;//$mail_status = isset($ms_session)?$ms_session:1;
		if($mail_status == 2)
			$ms_qry = ' and m.status=2';
		else if($mail_status == 1)
			$ms_qry = ' and m.status=1';
		else if($mail_status == 0)
			$ms_qry = ' and (m.status=1 or m.status=2)';
			
		$condition = "m.type=2";
		if($subdiv_id)
			$condition = "m.type=2 and m.division_id=$division_id and m.subdivision_id=$subdiv_id";
		else if($division_id)
			$condition = "m.type=2 and m.division_id=$division_id";
		
		if($mail_status == 3){
			$ms_qry =  " and (substr(m.action_date, 7, 4) ||  '-' || substr(m.action_date, 4, 2) ||  '-' ||  substr(m.action_date, 1, 2)) < date('now') and m.status=1 ";
		}
		
		if($limit!='')
	   		$limit = " LIMIT $start,$limit";
			
		$query = $this->db->query('SELECT m.*,d.division  FROM mails m 
		                           LEFT JOIN division d on d.id =m.division_id WHERE '.$condition.$ms_qry.'  order by substr(m.recieved_date, 7, 4) ||  "-" || substr(m.recieved_date, 4, 2) ||  "-" ||  substr(m.recieved_date, 1, 2)  DESC,m.mail_no ASC '.$limit);
		$mails = $query->result_array();
		if(!empty($mails)){$i=0;
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
		}
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
			
			$to_address_qry = $this->db->query('SELECT ab.id, ma.title,ma.attention FROM mail_address ma
			JOIN address_book ab ON ma.title=ab.title
			WHERE (ma.mail_id = "'.$mail_row['id'].'" and ma.type=2)');
			$to_mail_adress =$to_address_qry->result_array();
			$res_toadd ="";
			$res_toid="";
			$res_toatt="";
			foreach($to_mail_adress as $toadd){
				$res_toid .= $toadd['id']."|";
				$res_toadd .= $toadd['title']."~";
				$res_toatt .= $toadd['attention']."~";
			}
			$mail_row['to'] = rtrim($res_toadd,"~");
			$mail_row['to_id'] = rtrim($res_toid,"| ");
			$mail_row['to_attention'] = rtrim($res_toatt,"~");
			
			$cc_address_qry = $this->db->query('SELECT ab.id, ma.title,ma.attention FROM mail_address ma 
			JOIN address_book ab ON ma.title=ab.title
			WHERE (ma.mail_id = "'.$mail_row['id'].'" and ma.type=3)');
			$cc_mail_adress =$cc_address_qry->result_array();
			$res_ccadd ="";
			$res_ccid ="";
			$res_ccatt="";
			foreach($cc_mail_adress as $ccadd){
				$res_ccid .= $ccadd['id']."|";
				$res_ccadd .= $ccadd['title']."~";
				$res_ccatt .= $ccadd['attention']."~";
			}
			$mail_row['cc'] = rtrim($res_ccadd,"~");
			$mail_row['cc_id'] = rtrim($res_ccid,"|");
			$mail_row['cc_attention'] = rtrim($res_ccatt,"~");//echo "<pre>";print_r($mail_row);
			 return $mail_row;
		}

	}
	
	function outmail_staging_rows()
	{
		$query = $this->db->query('SELECT * FROM mails WHERE (`type`=2 and `status`=0) ORDER BY `id` DESC ');
		$count = $query->num_rows();
		if($count)
			 return $count;
	}
	
	function staging_list($limit='', $start='')
	{
		if($limit!='')
	   		$limit = " LIMIT $start,$limit";
		$query = $this->db->query('SELECT * FROM mails WHERE (`type`=2 and `status`=0) ORDER BY `id` DESC '.$limit);
		$result = $query->result_array();
		if($result)
			 return $result;
	}
	
	function outmail_insert($data,$edit_mailid='')
	{
		//echo "<pre>";print_r($data);die;
		
		if($edit_mailid=='')
		{
			$mail_no = $data['mailno'];
		
			$result = $this->db->query("INSERT INTO mails ('date', 'mail_no', 'mail_ref', 'type', 'subject', 'language', 'status', 'division_id', 'subdivision_id', 'validation', 'recieved_date', 'created_on', 'created_by', 'updated_on', 'updated_by') 
		VALUES ('{$data['date']}', '".$mail_no."', '{$data['mail_ref']}', '{$data['type']}', '{$data['subject']}', '{$data['language']}', '{$data['status']}', '{$data['division_id']}', '{$data['subdivision_id']}', '{$data['validation']}', '{$data['recieved_date']}', '{$data['created_on']}', '{$data['created_by']}', '{$data['updated_on']}', '{$data['updated_by']}')");
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
				$to_attention=explode("|",$data['to_attention']);
				$i = 0;
				foreach($to as $to_id){
					$query = $this->db->query('SELECT * FROM address_book WHERE `id`='.$to_id);
					$row = $query->row();
					$res =$this->db->query("INSERT INTO mail_address ('title','address1','address2','address3', 'city', 'state', 'country', 'pincode', 'mail_id','type', 'attention') VALUES('$row->title', '$row->address1', '$row->address2', '$row->address3', '$row->city', '$row->state', '$row->country', '$row->pincode', $last_insert_id, 2, '".$to_attention[$i]."')");
					$i++;
				}
			}
			
			if($data['cc_new']!='|' and $data['cc_new']!=''){
				$data['cc_new']=rtrim($data['cc_new'],"| ");
				$cc=explode("|",$data['cc_new']);
				$cc_attention=explode("|",$data['cc_attention']);
				$i = 0;
				foreach($cc as $cc_id){
					$query = $this->db->query('SELECT * FROM address_book WHERE `id`='.$cc_id);
					$row = $query->row();
					$res =$this->db->query("INSERT INTO mail_address ('title','address1','address2','address3', 'city', 'state', 'country', 'pincode', 'mail_id','type','attention') VALUES('$row->title', '$row->address1', '$row->address2', '$row->address3', '$row->city', '$row->state', '$row->country', '$row->pincode', $last_insert_id, 3, '".$cc_attention[$i]."')");
				}
			}
			
			if($result){
				$session=$this->session->userdata('check_login');
				$this->purge_log();
				$action="New Outmail with Mail No ".$mail_no." has been created by ".$session['login_name']; 
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
				$res =$this->db->query("UPDATE comments SET comments = '{$data['comments']}',
				date = '$date',
				active = '$active',
				users_id = '$users_id'
				WHERE mails_id =". $edit_mailid);
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
				$to_attention=explode("|",$data['to_attention']);
				$c = 0;
				foreach($to as $to_id){
					$query = $this->db->query('SELECT * FROM address_book WHERE `id`='.$to_id);
					$row = $query->row();
					
					$res =$this->db->query("INSERT INTO mail_address ('title','address1','address2','address3', 'city', 'state', 'country', 'pincode', 'mail_id','type','attention') VALUES('$row->title', '$row->address1', '$row->address2', '$row->address3', '$row->city', '$row->state', '$row->country', '$row->pincode', $edit_mailid, 2,'".$to_attention[$c]."')");
					$c++;
				}
			}
			
			if($data['cc_new']!='|' and $data['cc_new']!=''){
				$data['cc_new']=rtrim($data['cc_new'],"| ");
				$cc=explode("|",$data['cc_new']);
				$cc_attention=explode("|",$data['cc_attention']);
				$c = 0;
				foreach($cc as $cc_id){
					$query = $this->db->query('SELECT * FROM address_book WHERE `id`='.$cc_id);
					$row = $query->row();
					$res =$this->db->query("INSERT INTO mail_address ('title','address1','address2','address3', 'city', 'state', 'country', 'pincode', 'mail_id','type','attention') VALUES('$row->title', '$row->address1', '$row->address2', '$row->address3', '$row->city', '$row->state', '$row->country', '$row->pincode', $edit_mailid, 3,'".$cc_attention[$c]."')");
					$c++;
				}
			}
			
			if($result){
				$session=$this->session->userdata('check_login');
				$this->purge_log();
				$action="Outmail with Mail No ".$data['mailno']." has been updated by ".$session['login_name'];
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
	
	function outmailview($mailid)
	{
		$query = $this->db->query('SELECT m.*, d.division, s.subdivision, u.firstname FROM mails m 
		                           LEFT JOIN division d on d.id =m.division_id
								   LEFT JOIN subdivision s on s.id =m.subdivision_id
								   LEFT JOIN users u ON u.id = m.owner
								   WHERE m.type=2 and m.id='.$mailid);
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
					
			return $mails;
		}
	}
	
	function to_address($data)
	{
		$condition ="";
		
		if($data['alias']){
			$condition = " ab.title = '".$data['alias']."' ";
		}
	
		/*$query = $this->db->query("SELECT ma.title, ma.attention, ma.address1, ma.address2, ma.address3, ma.city, ma.state, ma.pincode, ab.name FROM mail_address ma  
		join address_book ab on ab.title = ma.title
		WHERE ma.type=2 ". $condition." limit 0,1");*/
		
		$query = $this->db->query("Select ab.title, ma.attention, ab.address1, ab.address2, ab.address3, ab.city, ab.state, ab.pincode, ab.name from address_book ab
		join mail_address ma on ab.title = ma.title
		WHERE ". $condition." limit 0,1");//ma.type=2 
		$to_address = $query->row_array();
		if(isset($to_address['title']))
			return $to_address;
		else
			return false; 
	}
	
	function search_alias($term)
	{
		/*$query = $this->db->query("SELECT Distinct ma.title FROM mail_address ma 
		LEFT JOIN mails m ON ma.mail_id = m.id
		join address_book ab on ab.title = ma.title
		WHERE m.type =2 and ma.type=2 and ma.title LIKE '%".$term['alias']."%' ");*/
		
		$query = $this->db->query("SELECT Distinct ab.title FROM address_book ab
		join mail_address ma on ab.title = ma.title 
		LEFT JOIN mails m ON ma.mail_id = m.id
		WHERE ab.title LIKE '%".$term['alias']."%' ");//m.type =2 and ma.type=2 and 
		$to_alias = $query->result_array();
		$json=array();
    	foreach($to_alias as $to){
         	$json[]=$to['title'];
   		}
		return json_encode($json);
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
}
	