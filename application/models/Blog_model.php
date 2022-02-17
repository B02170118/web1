<?php
class Blog_model extends CI_Model
{
	function __construct() {
		parent::__construct();
		$this->load->database();
    }
    //取得全資料
	function get_all_data()
    {
        $sql = "SELECT * FROM `blog` ORDER BY seq,date";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }
    //更新文章狀態
    function update_status_blog($id,$status)
	{
		$sql = "UPDATE `blog` SET `status` = ? WHERE `id` = ?";
		$res = $this->db->query($sql, array($status, $id));
		return $res;
    }
    //取得下一個ID
    function get_next_id()
    {
        $sql = "SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?";
        $data = $this->db->query($sql, array('test1111_studio', 'blog'))->row_array();
		return $data;
    }
    //新增文章
    function add_blog($dir,$title,$status,$date,$content)
    {
        $sql = "INSERT INTO `blog`(`title`, `img`, `content`, `date`, `status`) VALUES (?,?,?,?,?)";
        $res = $this->db->query($sql, array($title, $dir, $content, $date, $status));
		return $res;
    }
    //取得縮圖
    function get_img($id)
    {
        $sql = "SELECT id,img FROM blog WHERE id = ?";
        $res = $this->db->query($sql, array($id))->row_array();
		return $res;
    }
    //刪除文章
    function del_blog($id)
    {
        $sql = "DELETE FROM blog WHERE id =?";
        $res = $this->db->query($sql,array($id));
        return $res;
    }
    //修改文章順序
    function edit_blog_seq($sort_array)
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
		$sql = "UPDATE blog
        SET seq = CASE id $str
        END WHERE id IN ($id_list)";
		$res = $this->db->query($sql);
		return $res;
    }
    //取得文章的詳細資料
    function get_blog_view($id)
    {
        $sql = "SELECT * FROM blog WHERE id =?";
        $data = $this->db->query($sql, array($id))->row_array();
		return $data;
    }
    //編輯文章
    function edit_blog($id,$title,$date,$status,$content,$dir = NULL,$type = 0){
        if ($type == 1) { //上傳圖片
            $sql = "UPDATE `blog` SET `title`= ?,`img`= ?,`date`= ?,`status`= ?,`content`= ? WHERE id = ?";
            $res = $this->db->query($sql, array($title,$dir,$date,$status,$content,$id));
        }else {
            $sql = "UPDATE `blog` SET `title`= ?, `date`= ?,`status`= ?,`content`= ? WHERE id = ?";
            $res = $this->db->query($sql, array($title,$date,$status,$content,$id));
        }
        return $res;
    }
}
