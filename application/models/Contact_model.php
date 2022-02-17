<?php
class Contact_model extends CI_Model
{
	function __construct() {
		parent::__construct();
		$this->load->database();
    }
    //取得聯絡我們資料
	function get_all_contact()
    {
        $data = array();
        $sql = "SELECT t1.id,t1.name,t1.phone,t1.email,t3.name AS type,t2.time_name AS contact_time,t1.engagement_date,t1.marriage_date,t4.time_name AS reserved_time,t1.remark,t1.ip,t1.question_time,t1.status 
        FROM `contact_main` AS t1 
        LEFT JOIN `contact_time` AS t2 ON t1.contact_time = t2.id 
        LEFT JOIN `contact_time` AS t4 ON t4.id = t1.reserved_time 
        LEFT JOIN `contact_type` AS t3 ON t1.type = t3.id
        ORDER BY question_time DESC";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }
    //取得指定資料
    function get_contact($id)
    {
        $sql = "SELECT t1.id,t1.name,t1.phone,t1.email,t3.name AS type,t2.time_name AS contact_time,t1.engagement_date,t1.marriage_date,t4.time_name AS reserved_time,t1.remark,t1.ip,t1.question_time 
        FROM `contact_main` AS t1 
        LEFT JOIN `contact_time` AS t2 ON t1.contact_time = t2.id 
        LEFT JOIN `contact_time` AS t4 ON t4.id = t1.reserved_time 
        LEFT JOIN `contact_type` AS t3 ON t1.type = t3.id
        WHERE t1.id = ?
        ORDER BY question_time DESC";
        $data = $this->db->query($sql,array($id))->row_array();
        return $data;
    }
    //變更狀態
    function update_status($id,$status)
    {
        $sql = "UPDATE `contact_main` SET `status` = ? WHERE `id` = ?";
		$res = $this->db->query($sql, array($status, $id));
		return $res;
    }
}
