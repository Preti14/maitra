<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Campaign Select
 * This model serves to fetch campaign data.
 */
class Logs extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$ci =& get_instance();
	}
	
	function fetch_logs()
	{
		$query = $this->db->query('SELECT * FROM log WHERE active = 1 ORDER BY `id` DESC ');
		$result = $query->result_array();
		if($result)
			 return $result;
	}
}