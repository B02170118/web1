<?php
class Login_model extends CI_Model
{
	function __construct() {
		parent::__construct();
		$this->load->database();
    }
    //寫入新驗證碼
    function insert_captcha($data)
    {
        $time = $data['captcha_time'];
        $ip = $data['ip_address'];
        $key = $data['word'];
        $sql = "INSERT INTO `captcha`(`captcha_time`, `ip_address`, `word`) VALUES (?,?,?)";
        $res = $this->db->query($sql, array($time,$ip,$key));
        return $res;
    }
    //刪除舊的
    function del_captcha($ip,$time)
    {
        $last_time = $time - 86400;
        $sql = "DELETE FROM `captcha` WHERE captcha_time < ? || ip_address = ?";
        $this->db->query($sql, array($last_time,$ip));
        return;
    }
    //確認驗證碼
    function check_chptcha($ip,$word)
    {
        $sql = "SELECT * FROM `captcha` WHERE ip_address = ? AND word = ?";
        $res = $this->db->query($sql, array($ip,$word))->num_rows();
        return $res;
    }
    //
    //檢查帳密
    function check_account($ac,$pw)
    {
        $data = array('code' => 0,'msg' => '登入成功' );
        $berror = FALSE;
        $ip = $this->input->ip_address();
        $now = date('Y-m-d H:i:s');
        $last_time =  date('Y-m-d H:i:s',strtotime('-1 hour'));
        
        //檢查帳密
        $sql = "SELECT * FROM `admin_user` WHERE account = ? AND password = ?";
        $rows = $this->db->query($sql, array($ac,$pw))->num_rows();
        if ($rows <= 0) {
            $berror = TRUE;
            $data = array('code' => 1,'msg' => '帳號密碼錯誤' );
        }
        if (!$berror) {
            $account = $this->db->query($sql, array($ac,$pw))->row_array();
            $sql = "SELECT * FROM `admin_login_log` WHERE ip = ? AND time > ? AND time < ?";
            $rows2 = $this->db->query($sql, array($ip,$last_time,$now))->num_rows();
            //檢查狀態
            if ($account['status'] == LOGIN_STATUS_LOCK) {
                $data = array('code' => 2,'msg' => '帳號已遭鎖定' );
            }elseif ($account['status'] == LOGIN_STATUS_STOP) {
                $data = array('code' => 3,'msg' => '帳號已停用' );
            }elseif ($rows2 >= LOGIN_ERROR_NUM) {
                $data = array('code' => 4,'msg' => '登入次數過多請稍後再試' );
            }
        }
        return $data;
    }
    //寫入登入log
    function insert_login_log($ac,$pw,$ip)
    {
        $sql = "INSERT INTO `admin_login_log`(`input_account`, `input_password`, `ip`) VALUES (?,?,?)";
        $this->db->query($sql, array($ac,$pw,$ip));
        return;
    }
}