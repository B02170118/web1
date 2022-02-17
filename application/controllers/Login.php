<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
	public function __construct() 
	{
		parent::__construct();

		$this->load->library(array('test1111_lib','form_validation','user_agent','encryption'));
		$this->load->helper(array('url','captcha','security'));
		$this->load->model(array('Login_model'));

		//登入判斷
		$this->test1111_lib->check_login("login");
	}
	
	public function index()
	{	
		//載入當前語言文件
		// $this->lang->load('home');
		$encrypt_key = $this->config->item('encryption_key');
		//驗證碼
		$data['img'] = $this->test1111_lib->get_captcha();

		//csrf
		$data['csrfname'] = $this->security->get_csrf_token_name();
		$data['csrfhash'] = $this->security->get_csrf_hash();

		$this->load->view('login/index',$data);
	}
	//登入
	public function ajax_login()
	{
		 //限定POST,防機器人,來源
		 if ($this->input->server('REQUEST_METHOD') != 'POST' || $this->agent->is_robot() || $this->agent->is_referral()) {
            show_error('Access forbidden', 403);
            exit;
        }

		$msg = '';
		$code = 99;
		$berror = FALSE;
		$ip = $this->input->ip_address();
		$account = "";
		$password = "";
		$now = time();

        //XSS過濾
        $post_data =$this->security->xss_clean($this->input->post());
        if (empty($this->security->xss_clean($post_data))) {
			$msg = "非法提交參數";
			$berror = TRUE;
			$code = 1;
		}

		//驗證
		if (!$berror) {
			//account
			$this->form_validation->set_rules(
				'account', 'account','trim|required|min_length[4]|max_length[20]',
				array(
					'required'  => '非法提交參數',
					'min_length' => '帳號密碼錯誤',
					'max_length' => '帳號密碼錯誤'
				)
			);
			//password
			$this->form_validation->set_rules(
				'password', 'password','trim|required|min_length[8]|max_length[20]',
				array(
					'required'  => '非法提交參數',
					'min_length' => '帳號密碼錯誤',
					'max_length' => '帳號密碼錯誤'
				)
			);
			//captcha
			$this->form_validation->set_rules(
				'captcha', 'captcha','trim|required|min_length[0]|max_length[4]',
				array(
					'required'  => '非法提交參數',
					'min_length' => '非法提交參數',
					'max_length' => '非法提交參數'
				)
			);
			$account = $post_data['account'];
			$password = $post_data['password'];
			if ($this->form_validation->run() === FALSE){
				$text = trim(validation_errors());
				$msg =  $text;
				$berror = TRUE;
				$code = 1;
			}
		}
		
		if (!$berror) {
			//檢查驗證碼
			$captcha = $post_data['captcha'];
			$res = $this->Login_model->check_chptcha($ip,$captcha);
			if ($res <= 0) {
				$msg =  "驗證碼錯誤";
				$berror = TRUE;
				$code = 3;
			}
		}

		if (!$berror) {
			//驗證帳密
			$encrypt_key = $this->config->item('encryption_key');
			$encrypt_password = hash_hmac('sha256',$password,$encrypt_key);
			$res = $this->Login_model->check_account($account,$encrypt_password);
			$code = $res['code'];
			$msg = $res['msg'];
			//登入成功新增session
			if ($code == 0) {
				$user_data = array(
					"iss" => ISS, //簽發者
					"web" => WEB, //網站
					"ip" => $ip,//登入IP
					"account" => $account,//帳號
					"time" => $now + 1200//過期時間 
				);
				$encrypt_json = json_encode($user_data,320);
				$token = $this->test1111_lib->encrypt_code($encrypt_json);
				$session_data = array('token' => $token,'user' => $account);
				$this->session->set_userdata($session_data);
			}
		}

		//新增登入log
		$this->Login_model->insert_login_log($account,$password,$ip);

		$res_data = array("msg" => $msg,"code" => $code);
		$json = json_encode($res_data);
		echo $json;
	}
	//換驗證碼
	public function ajax_reload_img()
	{
		$img = $this->test1111_lib->get_captcha();
		echo $img;
	}
	//ajax修改密碼
	public function ajax_edit_pw()
	{
		 //限定POST,防機器人,來源
		 if ($this->input->server('REQUEST_METHOD') != 'POST' || $this->agent->is_robot() || $this->agent->is_referral()) {
            show_error('Access forbidden', 403);
            exit;
        }

		$msg = '';
		$code = 99;
		$berror = FALSE;
		$ip = $this->input->ip_address();
		$now = time();
		$account = "";
		$oldpassword = "";
		$newpassword = "";
		$checkpassword = "";
		

        //XSS過濾
        $post_data =$this->security->xss_clean($this->input->post());
        if (empty($this->security->xss_clean($post_data))) {
			$msg = "非法提交參數";
			$berror = TRUE;
			$code = 1;
		}

		//驗證
		if (!$berror) {
			//account
			$this->form_validation->set_rules(
				'account', 'account','trim|required|min_length[4]|max_length[20]',
				array(
					'required'  => '非法提交參數',
					'min_length' => '帳號密碼錯誤',
					'max_length' => '帳號密碼錯誤'
				)
			);
			//password
			$this->form_validation->set_rules(
				'password', 'password','trim|required|min_length[8]|max_length[20]',
				array(
					'required'  => '非法提交參數',
					'min_length' => '帳號密碼錯誤',
					'max_length' => '帳號密碼錯誤'
				)
			);
			//captcha
			$this->form_validation->set_rules(
				'captcha', 'captcha','trim|required|min_length[0]|max_length[4]',
				array(
					'required'  => '非法提交參數',
					'min_length' => '非法提交參數',
					'max_length' => '非法提交參數'
				)
			);
			$account = $post_data['account'];
			$password = $post_data['password'];
			if ($this->form_validation->run() === FALSE){
				$text = trim(validation_errors());
				$msg =  $text;
				$berror = TRUE;
				$code = 1;
			}
		}
		
		if (!$berror) {
			//檢查驗證碼
			$captcha = $post_data['captcha'];
			$res = $this->Login_model->check_chptcha($ip,$captcha);
			if ($res <= 0) {
				$msg =  "驗證碼錯誤";
				$berror = TRUE;
				$code = 3;
			}
		}

		if (!$berror) {
			//驗證帳密
			$encrypt_key = $this->config->item('encryption_key');
			$encrypt_password = hash_hmac('sha256',$password,$encrypt_key);
			$res = $this->Login_model->check_account($account,$encrypt_password);
			$code = $res['code'];
			$msg = $res['msg'];
			//登入成功新增session
			if ($code == 0) {
				$user_data = array(
					"iss" => ISS, //簽發者
					"web" => WEB, //網站
					"ip" => $ip,//登入IP
					"account" => $account,//帳號
					"time" => $now + 1200//過期時間 
				);
				$encrypt_json = json_encode($user_data,320);
				$token = $this->test1111_lib->encrypt_code($encrypt_json);
				$session_data = array('token' => $token,'user' => $account);
				$this->session->set_userdata($session_data);
			}
		}

		//新增登入log
		$this->Login_model->insert_login_log($account,$password,$ip);

		$res_data = array("msg" => $msg,"code" => $code);
		$json = json_encode($res_data);
		echo $json;
	}
}
/* End of file Login.php */