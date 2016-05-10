<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Campaign Select
 * This model serves to fetch campaign data.
 */
class Users extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$ci =& get_instance();
	}
	
	function fetch_users()
	{
		$session=$this->session->userdata('check_login');
		$query = $this->db->query('SELECT * FROM users WHERE user_type_id=3 ORDER BY `id` ASC ');
		$result = $query->result_array();
		if($result)
			 return $result;
	}
	
	function users_list()
	{
		$session=$this->session->userdata('check_login');
		
		$query = $this->db->query('SELECT u.*, ut.*,u.id as id, d.division, sd.subdivision FROM users u
		LEFT JOIN user_type ut ON ut.id=u.user_type_id
		LEFT JOIN division d ON d.id=u.division_id
		LEFT JOIN subdivision sd ON sd.id=u.subdivision_id
		WHERE ( u.id!=1 and u.active=1) ORDER BY u.id DESC ');
		$result = $query->result_array();
		if($result)
			 return $result;
	}
	
	function get_usertype()
	{
		$query = $this->db->query('SELECT * FROM user_type WHERE id!=1 ORDER BY `id` ASC ');
		$result = $query->result_array();
		if($result)
			 return $result;
	}
	
	function user_insert($data)
	{   //echo "<pre>";print_r($data);die;
		if($data['edit_userid']=='')
		{
			$result = $this->db->query("INSERT INTO users ('firstname', 'lastname', 'email', 'username', 'password', 'user_type_id', 'active', 'division_id', 'subdivision_id', 'mobile_no' ) VALUES ('{$data['firstname']}', '{$data['lastname']}', '{$data['email']}', '{$data['username']}', '{$data['password']}', {$data['user_type_id']},1, {$data['division_id']}, {$data['subdivision_id']}, '{$data['mobile_no']}')"); 
		}else{
			$result = $this->db->query("UPDATE users SET firstname='{$data['firstname']}',
			lastname='{$data['lastname']}',
			email='{$data['email']}',
			username='{$data['username']}',
			password='{$data['password']}',
			user_type_id={$data['user_type_id']},
			active = 1,
			division_id={$data['division_id']},
			subdivision_id={$data['subdivision_id']},
			mobile_no = '{$data['mobile_no']}'
			WHERE id =".$data['edit_userid']);
		}
		if($result)
			return true;
		else
			return false;
	}
	
	function edit_user($userid)
	{
		$query = $this->db->query('SELECT * FROM users WHERE id='.$userid );
		$result = $query->row_array();
		return $result;
	}
	
	function delete_user($userid)
	{
		$q_user = $this->db->query('SELECT * FROM users WHERE id='.$userid );
		$r_user = $q_user->row_array();
		if($r_user['user_type_id'] ==3 || $r_user['user_type_id']==4){
			$q_mails = $this->db->query('SELECT m.id FROM mails m
			LEFT JOIN users us ON us.division_id = m.division_id 
			WHERE us.division_id='.$r_user['division_id']);		
			$r_mails = $q_mails->num_rows();
			if($r_mails>=1){
				echo "error";
				die;
			}
		}
		
		$delete = $this->db->query('UPDATE users SET active = 0 WHERE id='.$userid );
		if($delete)
			echo true;
		else
			echo false;
	}
	
	function user_division()
	{
		$login = $this->session->userdata('check_login');
		$user_id = $login['login_id'];
		$user_type = $login['login_type'];
		$qry = $this->db->query('SELECT u.*, d.division, s.code as subdivision FROM users u
		LEFT JOIN division d ON d.id = u.division_id
		LEFT JOIN subdivision s ON s.id = u.subdivision_id WHERE u.id = '.$user_id);
		$result = $qry->row();
		return $result;
	}
	
	function change_password($uid,$old_password,$new_password)
	{
		$query = $this->db->query("SELECT * from users WHERE id = ".$uid." AND password='".md5($old_password)."'");
		$rowcount = $query->num_rows();		
		if($rowcount==0)
		{
			echo false;
		} else {
			$delete = $this->db->query('UPDATE users SET password="'.md5($new_password).'" WHERE id='.$uid );		
			return $delete;
		}		
	}
	
	function check_username($username){
		$sql = "SELECT id from users WHERE username = '".$username."' and active = 1";
		//echo $sql; die;
		$query    = $this->db->query($sql);
		
		$result = $query->result();
		$rowcount = count($result);
		//var_dump($result); die;
		if($rowcount==0)
			return 1;
		else 
			return 0;
	}
        
        function adUserDivision($empno) {
            $query = $this->db->query('SELECT u.*, d.division, s.code as subdivision FROM adusers u
                    LEFT JOIN division d ON d.id = u.division_id
                    LEFT JOIN subdivision s ON s.id = u.subdivision_id WHERE u.empno = "'. $empno . '" LIMIT 1');

            $result = $query->row();
            return $result;
        }
}