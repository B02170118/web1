<?php
class Video_model extends CI_Model
{
	function __construct() {
		parent::__construct();
		$this->load->database();
    }
    //取得影片全資料
	function get_all_data($type = NULL)
    {
        if($type === NULL){
            $sql = "SELECT * FROM `video` ORDER BY seq";
        }else{
            $sql = "SELECT * FROM `video` WHERE type = ? ORDER BY seq";
        }
        $data = $this->db->query($sql,$type)->result_array();
        return $data;
    }
    //更新影片狀態
    function update_status_video($id,$status)
	{
		$sql = "UPDATE `video` SET `status` = ? WHERE `id` = ?";
		$res = $this->db->query($sql, array($status, $id));
		return $res;
    }
    //修改影片順序
    function edit_video_seq($sort_array)
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
		$sql = "UPDATE video 
        SET seq = CASE id $str
        END WHERE id IN ($id_list)";
		$res = $this->db->query($sql);
		return $res;
    }
    //刪除影片
    function del_video($id_array)
    {
        $str = "(";
        foreach ($id_array as $value) {
            $str .= $value.",";
        }
        $str = substr($str,0,-1).")";
        $sql = "DELETE FROM video WHERE id IN $str";
        $res = $this->db->query($sql);
        return $res;
    }
    //新增影片
    function add_video($title,$content,$link,$type,$status)
    {
        $sql = "INSERT INTO `video`(`title`, `content`, `link`, `type`, `seq`, `status`) VALUES (?,?,?,?,?,?)";
        $res = $this->db->query($sql,array($title,$content,$link,$type,0,$status));
        return $res;
    }
    //取得影片內容
    function get_video_view($id)
    {
        $sql = "SELECT * FROM `video` WHERE id = ?";
        $data = $this->db->query($sql,array($id))->row_array();
        return $data;
    }
    //編輯影片
    function edit_video($title,$content,$link,$type,$id)
    {
        $sql = "UPDATE `video` SET `title`=?,`content`=?,`link`=?,`type`=? WHERE id=?";
        $res = $this->db->query($sql,array($title,$content,$link,$type,$id));
        return $res;
    }
}
