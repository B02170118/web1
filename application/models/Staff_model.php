<?php
class Staff_model extends CI_Model
{
	function __construct() {
		parent::__construct();
		$this->load->database();
    }
    //取得全資料
	function get_all_data()
    {
        $sql = "SELECT * FROM `staff` ORDER BY seq";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }
    //新增
    function add_staff($title,$subtitle,$img,$img2,$content,$href)
    {
        $sql = "INSERT INTO `staff`(`title`, `subtitle`, `img`, `img2`, `content`, `href`) VALUES (?,?,?,?,?,?)";
        $res = $this->db->query($sql, array($title, $subtitle, $img, $img2, $content, $href));
		return $res;
    }
    //修改順序
    function edit_staff_seq($sort_array)
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
		$sql = "UPDATE staff
        SET seq = CASE id $str
        END WHERE id IN ($id_list)";
		$res = $this->db->query($sql);
		return $res;
    }
    //取得縮圖
    function get_img($id)
    {
        $sql = "SELECT id,img,img2 FROM staff WHERE id = ?";
        $res = $this->db->query($sql, array($id))->row_array();
		return $res;
    }
    //刪除
    function del_staff($id)
    {
        $sql = "DELETE FROM staff WHERE id =?";
        $res = $this->db->query($sql,array($id));
        return $res;
    }
    
    //取得文章的詳細資料
    function get_staff_view($id)
    {
        $sql = "SELECT * FROM staff WHERE id =?";
        $data = $this->db->query($sql, array($id))->row_array();
		return $data;
    }
    //編輯
    function edit_staff($id,$title,$subtitle,$dir1=NULL,$dir2=NULL,$content,$href,$type = 0){
        if ($type == 1) { //上傳兩張
            $sql = "UPDATE `staff` SET `title`= ?,`subtitle`=?,`img`= ?,`img2`= ?,`content`= ?,`href`= ? WHERE id = ?";
            $res = $this->db->query($sql, array($title,$subtitle,$dir1,$dir2,$content,$href,$id));
        }elseif ($type == 2) { //上傳第一張
            $sql = "UPDATE `staff` SET `title`= ?,`subtitle`=?,`img`= ?,`content`= ?,`href`= ? WHERE id = ?";
            $res = $this->db->query($sql, array($title,$subtitle,$dir1,$content,$href,$id));
        }elseif ($type == 3) { //上傳第二張
            $sql = "UPDATE `staff` SET `title`= ?,`subtitle`=?,`img2`= ?,`content`= ?,`href`= ? WHERE id = ?";
            $res = $this->db->query($sql, array($title,$subtitle,$dir2,$content,$href,$id));
        }else {
            $sql = "UPDATE `staff` SET `title`= ?,`subtitle`=?,`content`= ?,`href`= ? WHERE id = ?";
            $res = $this->db->query($sql, array($title,$subtitle,$content,$href,$id));
        }
        return $res;
    }
}
