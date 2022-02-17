<?php
class Activity_model extends CI_Model
{
	function __construct() {
		parent::__construct();
		$this->load->database();
    }
    //取得全資料
	function get_all_data()
    {
        $sql = "SELECT * FROM `activity` ORDER BY seq";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }
    //新增
    function add_activity($dir,$href,$start_time,$end_time)
    {
        $sql = "INSERT INTO `activity`(`img`, `href`, `start_time`, `end_time`) VALUES (?,?,?,?)";
        $res = $this->db->query($sql, array($dir, $href, $start_time, $end_time));
		return $res;
    }
    //修改順序
    function edit_activity_seq($sort_array)
    {
        $seq = 0;
        $str = "";
        $id_list = "";
        foreach ($sort_array as $value) {
            $id = $value;
            $str .= "WHEN $id THEN $seq ";
            $id_list .= "$id,";
            $seq++;
        }
        $id_list = substr($id_list,0,-1);
		$sql = "UPDATE activity
        SET seq = CASE id $str
        END WHERE id IN ($id_list)";
		$res = $this->db->query($sql);
		return $res;
    }
    //取得縮圖
    function get_img($id)
    {
        $sql = "SELECT id,img FROM activity WHERE id = ?";
        $res = $this->db->query($sql, array($id))->row_array();
		return $res;
    }
    //刪除
    function del_activity($id)
    {
        $sql = "DELETE FROM activity WHERE id =?";
        $res = $this->db->query($sql,array($id));
        return $res;
    }
    //取得指定活動資料
    function get_data($id)
    {
        $sql = "SELECT * FROM `activity` WHERE id = ? ORDER BY seq";
        $data = $this->db->query($sql,array($id))->row_array();
        return $data;
    }
    //編輯
    function edit_activity($id,$dir=NULL,$href,$start_time,$end_time,$type=0){
        if ($type == 1) { //上傳圖
            $sql = "UPDATE `activity` SET `img`=?,`href`= ?,`start_time`=?,`end_time`= ? WHERE id = ?";
            $res = $this->db->query($sql, array($dir,$href,$start_time,$end_time,$id));
        }else {
            $sql = "UPDATE `activity` SET `href`= ?,`start_time`=?,`end_time`= ? WHERE id = ?";
            $res = $this->db->query($sql, array($href,$start_time,$end_time,$id));
        }
        return $res;
    }
}
