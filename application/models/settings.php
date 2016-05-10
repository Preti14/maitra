<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Campaign Select
 * This model serves to fetch campaign data.
 */
class Settings extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$ci =& get_instance();
		
	}
	
	function add_settings($data)
	{
		//echo "<pre>"; print_r($data); die;
		$result = $this->db->query("INSERT INTO settings ('site_title', 'site_logo', 'copyright_text', 'temporary_addr_count', 'from_address','mail_no_prefix','mail_no_reset', 'from_address_id','no_of_records','footer_text', 'active') VALUES ('{$data['site_title']}', '{$data['site_logo']}',  '{$data['copyright_text']}', {$data['temporary_addr_count']},'{$data['from_address']}','{$data['mail_no_prefix']}','{$data['mail_no_reset']}','{$data['from_address_id']}','{$data['no_of_records']}','{$data['footer_text']}',1)");
		if($result)
			return true;
		else
			return false;
	}
	
	function current_settings()
	{
		$query = $this->db->query('SELECT * FROM settings ORDER BY id DESC LIMIT 1');
		$settings = $query->row_array();
		return $settings;
	}
	
	function address_total_rows()
	{
		$cond= "";
		if(isset($_GET['address']))
			$cond = " and name like '%".SQLite3::escapeString($_GET['address'])."%' ";
		
		$query = $this->db->query('SELECT * FROM address_book WHERE active = 1 '.$cond.' ORDER BY id DESC');
		$result = $query->num_rows();
		return $result;
	}
	
	function address_book($limit='', $start='')
	{
		if($limit!='')
	   		$limit = " LIMIT $start,$limit";
			
		$cond= "";
		if(isset($_GET['address'])){
			$txt = SQLite3::escapeString($_GET['address']);
			$cond = " and (name like '%".$txt."%' or title like '%".$txt."%') ";
		}
		$query = $this->db->query('SELECT * FROM address_book WHERE active = 1 '.$cond.' ORDER BY id DESC '.$limit);
		$result = $query->result_array();
		return $result;
	}
	
	function delete_address($addr_id)
	{
		$delete = $this->db->query('UPDATE address_book SET active = 0 WHERE id='.$addr_id );
		if($delete)
			echo true;
		else
			echo false;
	}
	
	function get_address($addr_id)
	{
		$query = $this->db->query('SELECT * FROM address_book WHERE id ='.$addr_id);
		$result = $query->row_array();
		return $result;
	}
	
	function add_address($val)
	{ 
		
		if(!$val['addr_id']){
			
			if( $val['type'] == 2 ){
			$qry = $this->db->query("SELECT * from address_book where type = 2 ORDER BY id DESC" );
			
			//echo TEMP_ADDRESS_COUNT; die;
			if($qry->num_rows() >= TEMP_ADDRESS_COUNT ){
				$qry = $this->db->query("SELECT * from address_book where type = 2 ORDER BY id ASC LIMIT 1" );
				$result = $qry->row_array();				
				$upd_id = $result['id'];
				
				$res = $this->db->query("UPDATE address_book SET active = 0  WHERE id = ".$upd_id);
				/*$res = $this->db->query("UPDATE address_book SET
						name = '".$val['name']."',
						address1 = '".$val['address1']."',
						address2 = '".$val['address2']."',
						address3 = '".$val['address3']."',
						title = '".$val['title']."',
						city = '".$val['city']."',
						state = '".$val['state']."',
						country = '".$val['country']."',
						pincode = '".$val['pincode']."',
						type = '".$val['type']."',
						active = 1
						WHERE id = ".$upd_id);
						
						if($res)
							return true;
						else
							return false;*/			
			
			}//else {
		
		$res = $this->db->query("INSERT INTO address_book ('name','address1','address2','address3','title','city','state','country','pincode','type','active') VALUES ('".$val['name']."','".$val['address1']."','".$val['address2']."','".$val['address3']."','".$val['title']."','".$val['city']."','".$val['state']."','".$val['country']."','".$val['pincode']."','".$val['type']."',1)");
			//} 
		} else {
		
		$res = $this->db->query("INSERT INTO address_book ('name','address1','address2','address3','title','city','state','country','pincode','type','active') VALUES ('".$val['name']."','".$val['address1']."','".$val['address2']."','".$val['address3']."','".$val['title']."','".$val['city']."','".$val['state']."','".$val['country']."','".$val['pincode']."','".$val['type']."',1)");
			}
		}else{
			$res = $this->db->query("UPDATE address_book SET
			name = '".$val['name']."',
			address1 = '".$val['address1']."',
			address2 = '".$val['address2']."',
			address3 = '".$val['address3']."',
			title = '".$val['title']."',
			city = '".$val['city']."',
			state = '".$val['state']."',
			country = '".$val['country']."',
			pincode = '".$val['pincode']."',
			type = '".$val['type']."',
			active = 1
			WHERE id = ".$val['addr_id']);
		}
		if($res)
			return true;
		else
			return false;
	}
	
	function delete_multiaddress($addrids)
	{
		$addr_id=explode(",",$addrids);
		foreach($addr_id as $addrid){
				$result = $this->db->query("UPDATE address_book SET active = 0 WHERE `id`=".$addrid);
		}
		if($result)
			echo true;
		else
			echo false;
	}
	
	function delete_single_mail($mail_id)
	{
		$delete = $this->db->query('UPDATE mails SET status = 4 WHERE id='.$mail_id );
		if($delete)
			echo true;
		else
			echo false;
	}
	
	function delete_multiplemails($mailids)
	{
		$mail_id=explode(",",$mailids);
		foreach($mail_id as $mailid){
				$result = $this->db->query("UPDATE maila SET status = 4 WHERE `id`=".$mailid);
		}
		if($result)
			echo true;
		else
			echo false;
	}
	
	function delete_logs()
	{
		$delete = $this->db->query('DELETE FROM log' );
		if($delete)
			echo true;
		else
			echo false;
	}
	
	function check_mail_no_prefix($prefix){
		$query = $this->db->query('SELECT id FROM settings WHERE mail_no_prefix ="'.$prefix.'" ');
		$result = $query->row_array();
		if($result)
			echo true;			
		else 
			echo false;
	}
	
	function get_address_count(){
		$results = $this->db->query('SELECT * FROM settings ORDER BY id DESC LIMIT 1');
		$row = $results->row_array();
		return $row;
	}
	
	
	function purge_settings($data)
	{
		//echo "<pre>"; print_r($data); die;		
		$results = $this->db->query('SELECT * FROM settings ORDER BY id DESC LIMIT 1');
		$row = $results->row_array();		
		//echo $row['id']; die;
		$purge_records = $data['purge_records'];		
		$result = $this->db->query("UPDATE settings SET purge_records='".$purge_records."'");
		if($result)
			return true;
		else
			return false;
	}
	
}