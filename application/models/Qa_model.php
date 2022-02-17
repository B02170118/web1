<?php
class Qa_model extends CI_Model
{
	function __construct() {
		parent::__construct();
		$this->load->database();
    }
    //取得分類與分類的資料總覽
    function get_all_qa()
    {
        $sql = "SELECT * FROM `qa_category` ORDER BY seq";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }
    //修改分類順序
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
		$sql = "UPDATE qa_category
        SET seq = CASE id $str
        END WHERE id IN ($id_list)";
		$res = $this->db->query($sql);
		return $res;
    }
    //編輯分類
    function edit_category($id,$name)
    {
        $sql ="UPDATE `qa_category` SET `name`= ? WHERE `id` = ?";
        $res = $this->db->query($sql,array($name,$id));
        return $res;
    }
    //新增分類
    function add_category($name)
    {
        $sql = "INSERT INTO `qa_category`(`name`, `seq`) VALUES (?,?)";
        $res = $this->db->query($sql, array($name,0));
        return $res;
    }
    //刪除分類
    function del_category($id)
    {
        $sql = "DELETE FROM `qa_category` WHERE id = ?";
        $res = $this->db->query($sql, array($id));
        return $res;
    }
    //取得分類下的內容
    function qa_view($cid)
    {
        $sql = "SELECT * FROM `qa_category` WHERE id = ? ORDER BY seq";
        $data['category'] = $this->db->query($sql,array($cid))->row_array();
        $sql = "SELECT t1.id AS cid,t1.name AS c_name,t2.id,t2.content
        FROM `qa_category` AS t1
        LEFT JOIN `qa_text` AS t2 ON t2.category_id = t1.id
        WHERE t2.category_id = ?
        ORDER BY t2.seq";
        $data['text'] = $this->db->query($sql,array($cid))->result_array();
        return $data;
    }
    //新增文字
    function add_text($cid,$content)
    {
        $sql = "INSERT INTO `qa_text`(`category_id`,`content`,`seq`) VALUES (?,?,?)";
        $res = $this->db->query($sql, array($cid,$content,0));
        return $res;
    }
    //編輯文字
    function edit_text($cid,$id,$content)
    {
        $sql ="UPDATE `qa_text` SET `content`= ? WHERE `category_id` = ? AND `id` = ?";
        $res = $this->db->query($sql,array($content,$cid,$id));
        return $res;
    }
    //修改文字順序
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
		$sql = "UPDATE qa_text
        SET seq = CASE id $str
        END WHERE id IN ($id_list)";
		$res = $this->db->query($sql);
		return $res;
    }
    //刪除分類
    function del_text($id)
    {
        $sql = "DELETE FROM `qa_text` WHERE id = ?";
        $res = $this->db->query($sql, array($id));
        return $res;
    }
}
