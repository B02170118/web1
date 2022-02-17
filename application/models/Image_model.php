<?php
class Image_model extends CI_Model
{
	function __construct() {
		parent::__construct();
		$this->load->database();
    }
    //取得相簿總覽
	function get_all_workphoto_catrgory()
    {
        $data = array();
        $sql = "SELECT t1.id, t1.name, t1.default_img, t1.status FROM workphoto_category AS t1 ORDER BY t1.seq";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }
    //更新相簿狀態
    function update_status_category($category_id,$status)
	{
		$sql = "UPDATE `workphoto_category` SET `status` = ? WHERE `id` = ?";
		$res = $this->db->query($sql, array($status, $category_id));
		return $res;
    }
    //取得相簿名稱
    function get_workphoto_catrgory_name($cid)
	{
		$sql = "SELECT id,name FROM `workphoto_category` WHERE id = ?";
		$data = $this->db->query($sql, array($cid))->row_array();
		return $data;
    }
    //取得全部相簿
    function get_workphoto_catrgory_list()
	{
		$sql = "SELECT id,name FROM `workphoto_category` ORDER BY seq";
		$data = $this->db->query($sql)->result_array();
		return $data;
    }
    //修改相簿順序
    function edit_category_seq($sort_array){
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
		$sql = "UPDATE workphoto_category 
        SET seq = CASE id $str
        END WHERE id IN ($id_list)";
		$res = $this->db->query($sql);
		return $res;
    }
    //修改相簿預設圖片
    function update_category($dir = NULL,$cateogry_id,$category_name = NULL,$type){
        if ($type == 1) { //上傳圖片
            $sql = "UPDATE `workphoto_category` SET `default_img`= ?,`name`= ? WHERE id = ?";
            $res = $this->db->query($sql, array($dir,$category_name,$cateogry_id));
        }else {
            $sql = "UPDATE `workphoto_category` SET `name`= ? WHERE id = ?";
            $res = $this->db->query($sql, array($category_name,$cateogry_id));
        }
        
        return $res;
    }
    //更新相片狀態
    function update_status_workphoto($category_id,$workphoto_id,$status)
    {
        $sql = "UPDATE `workphoto_item` SET `status` = ? WHERE `workphoto_id` = ? AND `workphoto_category_id` = ?";
		$res = $this->db->query($sql, array($status, $workphoto_id, $category_id));
		return $res;
    }
    //取得相簿內的相片
    function get_workphoto_catrgory_img($cid){
        $sql = "SELECT t1.id,t1.img,t2.seq,t2.status
        FROM workphoto AS t1
        LEFT JOIN workphoto_item AS t2 ON t1.id = t2.workphoto_id
        LEFT JOIN workphoto_category AS t3 ON t3.id = t2.workphoto_category_id
        WHERE t3.id = ?
        ORDER BY t2.seq";
        $data['photo'] = $this->db->query($sql, array($cid))->result_array();
        $sql2 = "SELECT * FROM `workphoto_category` WHERE id = ?";
		$data['category'] = $this->db->query($sql2, array($cid))->row_array();
		return $data;
    }
    //新增相片與綁定相簿
    function insert_new_img($img,$cateogry){
        $sql = "INSERT INTO `workphoto`(`img`) VALUES (?)";
        $this->db->trans_begin();
        $this->db->query($sql, array($img));
        $insert_id = $this->db->insert_id();
        if ($insert_id>0) {
            $sql2 = "INSERT INTO `workphoto_item`(`workphoto_id`, `workphoto_category_id`, `seq`, `status`) VALUES (?,?,?,?)";
            $this->db->query($sql2, array($insert_id,$cateogry,0,1));
        }
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return FALSE;
        }
        else{
            $this->db->trans_commit();
            return TRUE;
        }
    }
    //取得相片路徑
    function get_img($id){
        $where = "";
        foreach ($id as $value) {
            $where .= "id = $value OR ";
        }
        $where = substr($where,0,-3);
        $sql = "SELECT id,img FROM workphoto WHERE $where";
        $data = $this->db->query($sql)->result_array();
		return $data;
    }
    //取得原圖相簿像片路徑
    function get_category_img($id){
        $sql = "SELECT id,default_img FROM workphoto_category WHERE id = ?";
        $data = $this->db->query($sql,array($id))->row_array();
		return $data;
    }
    //刪除相片
    function del_img($id){
        $sql = "DELETE FROM workphoto WHERE id = ?";
        $res = $this->db->query($sql, array($id));
		return $res;
    }
    //新增相簿
    function insert_new_category($category_name,$dir){
        $sql = "INSERT INTO `workphoto_category`(`name`, `default_img`, `seq`, `status`) VALUES (?,?,?,?)";
        $res = $this->db->query($sql, array($category_name,$dir,0,1));
        return $res;
    }
    //取得刪除相簿的資料
    function get_del_category_data($id){
        //相簿圖片
        $where = "";
        foreach ($id as $value) {
            $where .= "id = $value OR ";
        }
        $where = substr($where,0,-3);
        $sql = "SELECT id,default_img FROM workphoto_category WHERE $where";
        $data['category_img'] = $this->db->query($sql)->result_array();
        //相片圖片
        $where2 = "";
        foreach ($id as $value) {
            $where2 .= "t2.workphoto_category_id = $value OR ";
        }
        $where2 = substr($where2,0,-3);
        $sql2 = "SELECT t1.id,t1.img FROM `workphoto` AS t1
        LEFT JOIN `workphoto_item` AS t2 ON t1.id = t2.workphoto_id
        WHERE $where2";
        $data['img'] = $this->db->query($sql2)->result_array();
		return $data;
    }
    //刪除相簿
    function del_category($id){
        $sql = "DELETE FROM workphoto_category WHERE id = ?";
        $res = $this->db->query($sql, array($id));
		return $res;
    }
    //修改相片順序
    function edit_img_seq($sort_array){
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
		$sql = "UPDATE workphoto_item 
        SET seq = CASE workphoto_id $str
        END WHERE workphoto_id IN ($id_list)";
		$res = $this->db->query($sql);
		return $res;
    }
}
