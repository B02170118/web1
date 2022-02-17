<?php
class Dress_model extends CI_Model
{
	function __construct() {
		parent::__construct();
		$this->load->database();
    }
    //取得主分類總覽
    function get_all_dress_main()
    {
        $sql = "SELECT * FROM dress_main ORDER BY seq";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }
    //修改分類順序
    function edit_main_seq($sort_array)
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
		$sql = "UPDATE dress_main
        SET seq = CASE id $str
        END WHERE id IN ($id_list)";
		$res = $this->db->query($sql);
		return $res;
    }
    //更新分類狀態
    function update_status_main($main_id,$status)
	{
        $sql = "UPDATE `dress_main` SET `status` = ? WHERE `id` = ?";
		$res = $this->db->query($sql, array($status, $main_id));
		return $res;
    }
    //新增分類
    function add_main($name,$status)
    {
        $sql = "INSERT INTO `dress_main`(`name`, `status`, `seq`) VALUES (?,?,?)";
        $res = $this->db->query($sql, array($name,$status,0));
        return $res;
    }
    //刪除分類
    function del_main($id)
    {
        $sql = "DELETE FROM `dress_main` WHERE id = ?";
        $res = $this->db->query($sql, array($id));
        return $res;
    }
    //編輯分類
    function edit_main($id,$name)
    {
        $sql ="UPDATE `dress_main` SET `name`= ? WHERE `id` = ?";
        $res = $this->db->query($sql,array($name,$id));
        return $res;
    }
    //取得分類下的相簿
    function get_dress_main_view($id)
    {
        $sql = "SELECT t1.*,t2.name AS main_name FROM dress_category AS t1 
        LEFT JOIN dress_main AS t2 ON t2.id = t1.dress_main_id
        WHERE dress_main_id = ? ORDER BY seq";
        $data = $this->db->query($sql,array($id))->result_array();
        return $data;
    }
    //取得分類名稱
    function get_dress_main_name($id)
    {
        $sql = "SELECT name FROM dress_main WHERE id=?";
        $data = $this->db->query($sql,array($id))->row_array();
        return $data;
    }
    //新增相簿
    function add_category($category_name,$dir,$main_id)
    {
        $sql = "INSERT INTO `dress_category`(`name`, `dress_main_id`, `default_img`, `status`, `seq`) VALUES (?,?,?,?,?)";
        $res = $this->db->query($sql, array($category_name,$main_id,$dir,1,0));
        return $res;
    }
    //更新相簿狀態
    function update_status_category($category_id,$status)
	{
        $sql = "UPDATE `dress_category` SET `status` = ? WHERE `id` = ?";
		$res = $this->db->query($sql, array($status, $category_id));
		return $res;
    }
    //修改相簿順序
    function edit_category_seq($sort_array)
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
		$sql = "UPDATE dress_category 
        SET seq = CASE id $str
        END WHERE id IN ($id_list)";
		$res = $this->db->query($sql);
		return $res;
    }
    //取得相簿名稱
    function get_dress_catrgory_name($cid)
	{
		$sql = "SELECT t1.*,t2.name AS main_name FROM `dress_category` AS t1
        LEFT JOIN `dress_main` AS t2 ON t2.id = t1.dress_main_id
        WHERE t1.id = ?";
		$data = $this->db->query($sql, array($cid))->row_array();
		return $data;
    }
    //修改相簿
    function update_category($dir = NULL,$cateogry_id,$category_name = NULL,$type)
    {
        if ($type == 1) { //上傳圖片
            $sql = "UPDATE `dress_category` SET `default_img`= ?,`name`= ? WHERE id = ?";
            $res = $this->db->query($sql, array($dir,$category_name,$cateogry_id));
        }else {
            $sql = "UPDATE `dress_category` SET `name`= ? WHERE id = ?";
            $res = $this->db->query($sql, array($category_name,$cateogry_id));
        }
        return $res;
    }
    //取得更新時原本的圖片
    function get_category_default_img($category_id)
    {
        $sql = "SELECT id,default_img FROM `dress_category` WHERE id = ?";
        $data = $this->db->query($sql,array($category_id))->row_array();
		return $data;
    }
    //更新相片狀態
    function update_status_dress($id,$status)
    {
        $sql = "UPDATE `dress` SET `status` = ? WHERE `id` = ?";
		$res = $this->db->query($sql, array($status, $id));
		return $res;
    }
    //取得相簿內的相片
    function get_dress_category_img($cid){
        $sql = "SELECT t1.* FROM dress AS t1
        LEFT JOIN dress_category AS t2 ON t2.id = t1.dress_category_id
        WHERE t2.id = ?
        ORDER BY t1.seq";
        $data['photo'] = $this->db->query($sql, array($cid))->result_array();
        $sql2 = "SELECT t1.*,t2.name AS main_name FROM `dress_category` AS t1
        LEFT JOIN `dress_main` AS t2 ON t2.id = t1.dress_main_id
        WHERE t1.id = ?";
		$data['category'] = $this->db->query($sql2, array($cid))->row_array();
		return $data;
    }
    //新增相片與綁定相簿
    function insert_new_img($img,$cateogry){
        $sql = "INSERT INTO `dress`(`dress_category_id`,`img`) VALUES (?,?)";
        $this->db->trans_begin();
        $this->db->query($sql, array($cateogry,$img));
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
        $sql = "SELECT id,img FROM dress WHERE $where";
        $data = $this->db->query($sql)->result_array();
		return $data;
    }
    //刪除相片
    function del_img($id){
        $sql = "DELETE FROM dress WHERE id = ?";
        $res = $this->db->query($sql, array($id));
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
        $sql = "SELECT id,default_img FROM dress_category WHERE $where";
        $data['category_img'] = $this->db->query($sql)->result_array();
        //相片圖片
        $where2 = "";
        foreach ($id as $value) {
            $where2 .= "dress_category_id = $value OR ";
        }
        $where2 = substr($where2,0,-3);
        $sql2 = "SELECT id,img FROM `dress`
        WHERE $where2";
        $data['img'] = $this->db->query($sql2)->result_array();
		return $data;
    }
    //刪除相簿
    function del_category($id){
        $sql = "DELETE FROM dress_category WHERE id = ?";
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
		$sql = "UPDATE dress
        SET seq = CASE id $str
        END WHERE id IN ($id_list)";
		$res = $this->db->query($sql);
		return $res;
    }
}
