<?php
class Combowe_model extends CI_Model
{
	function __construct() {
		parent::__construct();
		$this->load->database();
    }
    //取得包套項目
	function get_all_combowe_main()
    {
        $data = array();
        $sql = "SELECT * FROM `combowe_main` ORDER BY seq";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }
    //新增項目
    function insert_new_combowe_main($title,$dir)
    {
        $sql = "INSERT INTO `combowe_main`(`title`, `img`, `seq`) VALUES (?,?,?)";
        $res = $this->db->query($sql, array($title,$dir,0));
        return $res;
    }
    //編輯項目順序
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
		$sql = "UPDATE combowe_main 
        SET seq = CASE id $str
        END WHERE id IN ($id_list)";
		$res = $this->db->query($sql);
		return $res;
    }
    //編輯項目預設圖片
    function edit_category_img($dir = NULL,$main_id,$main_title = NULL,$type){
        if ($type == 1) { //上傳圖片
            $sql = "UPDATE `combowe_main` SET `img`= ?,`title`= ? WHERE id = ?";
            $res = $this->db->query($sql, array($dir,$main_title,$main_id));
        }else {
            $sql = "UPDATE `combowe_main` SET `title`= ? WHERE id = ?";
            $res = $this->db->query($sql, array($main_title,$main_id));
        }
        return $res;
    }
    //取得圖片
    function get_img($id)
    {
        //項目圖片
        $sql = "SELECT id,img FROM combowe_main WHERE id = ?";
        $data = $this->db->query($sql,array($id))->result_array();
		return $data;
    }
    //刪除項目
    function del_main($id)
    {
        $sql = "DELETE FROM combowe_main WHERE id = ?";
        $res = $this->db->query($sql, array($id));
		return $res;
    }
    //取得項目裡面的資料
    function get_combowe_category($id)
    {
        // 項目資料
        $sql = "SELECT * FROM `combowe_main` WHERE id = ? ORDER BY seq";
        $data['main'] = $this->db->query($sql, array($id))->row_array();
        // 分類資料
        $sql = "SELECT * FROM `combowe_category` WHERE combowe_main_id = ? ORDER BY seq";
        $data['category'] = $this->db->query($sql, array($id))->result_array();
        return $data;
    }
    //新增分類
    function add_category($main_id,$category_title,$old_price,$price)
    {
        $sql = "INSERT INTO `combowe_category`(`combowe_main_id`,`title`, `old_price`, `price`) VALUES (?,?,?,?)";
        $res = $this->db->query($sql, array($main_id,$category_title,$old_price,$price));
        return $res;
    }
    //編輯分類
    function edit_category($id,$category_title,$old_price,$price)
    {
        $sql = "UPDATE `combowe_category` SET `title`=?,`old_price`=?,`price`=? WHERE `id`=?";
        $res = $this->db->query($sql, array($category_title,$old_price,$price,$id));
        return $res;
    }
    //刪除分類
    function del_category($id)
    {
        $sql = "DELETE FROM combowe_category WHERE id = ?";
        $res = $this->db->query($sql, array($id));
		return $res;
    }
    //編輯分類順序
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
		$sql = "UPDATE combowe_category 
        SET seq = CASE id $str
        END WHERE id IN ($id_list)";
		$res = $this->db->query($sql);
		return $res;
    }
    //取項目跟分類的名稱跟id
    function get_combowe_category_name($id)
    {
        // 項目資料
        $sql = "SELECT t2.id AS main_id, t2.title AS main_title,t1.title FROM `combowe_category` AS t1
        LEFT JOIN `combowe_main` AS t2 ON t2.id = t1.combowe_main_id
        WHERE t1.id = ?";
        $data = $this->db->query($sql, array($id))->row_array();
        return $data;
    }
    //取分類下面的文字
    function get_combowe_category_text($id)
    {
        // 項目資料
        $sql = "SELECT * FROM `combowe_text`
        WHERE combowe_category_id = ?
        ORDER BY seq";
        $data = $this->db->query($sql, array($id))->result_array();
        return $data;
    }
    //新增文字
    function add_text($category_id,$content)
    {
        $sql = "INSERT INTO `combowe_text`(`combowe_category_id`, `content`) VALUES (?,?)";
        $res = $this->db->query($sql, array($category_id,$content));
        return $res;
    }
    //編輯文字
    function edit_text($id,$content)
    {
        $sql = "UPDATE `combowe_text` SET `content`=? WHERE `id`=?";
        $res = $this->db->query($sql, array($content,$id));
        return $res;
    }
    //刪除文字
    function del_text($id)
    {
        $sql = "DELETE FROM combowe_text WHERE id = ?";
        $res = $this->db->query($sql, array($id));
		return $res;
    }
    //編輯分類順序
    function edit_text_seq($sort_array)
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
		$sql = "UPDATE combowe_text
        SET seq = CASE id $str
        END WHERE id IN ($id_list)";
		$res = $this->db->query($sql);
		return $res;
    }
}
