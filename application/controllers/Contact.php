<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Contact extends CI_Controller
{
	public function __construct() 
	{
		parent::__construct();

		$this->load->library(array('test1111_lib','form_validation','user_agent'));
        $this->load->helper(array('url'));
		$this->load->model(array('Contact_model'));
		
		//登入判斷
		$this->test1111_lib->check_login();
	}
	
	public function index()
	{	
		$data = array();
		$query = array();

        $data['title'] = "預約紀錄";

		//data
        $query = $this->Contact_model->get_all_contact();
        if (!empty($query)) {
            $data['contact'] = $query;
        }

		$this->load->view('templates/head',$data);
		$this->load->view('templates/header');
		$this->load->view('templates/title');
		$this->load->view('contact/index');
		$this->load->view('templates/footer');
		$this->load->view('contact/app');
	}
	//取得資料
	public function ajax_get_data()
	{
		//限定POST,防機器人,來源
		if ($this->input->server('REQUEST_METHOD') != 'POST' || $this->agent->is_robot() || $this->agent->is_referral()) {
            show_error('Access forbidden', 403);
            exit;
        }

		$msg = '';
		$code = 99;
		$berror = FALSE;
		$data = NULL;
		
        //XSS過濾
        $post_data =$this->security->xss_clean($this->input->post());
        if (empty($this->security->xss_clean($post_data))) {
			$msg = "非法提交參數";
			$berror = TRUE;
			$code = 1;
		}

		//驗證
		if (!$berror) {
			 //contact_id
			$this->form_validation->set_rules(
				'contact_id', 'contact_id','trim|required|greater_than_equal_to[1]|less_than_equal_to[999999999]',
				array(
					'required'  => '非法提交參數',
					'greater_than_equal_to' => '非法提交參數',
					'less_than_equal_to' => '非法提交參數'
				)
			);
			if ($this->form_validation->run() === FALSE){
				$text = trim(validation_errors());
				$msg =  $text;
				$berror = TRUE;
				$code = 2;
			}
		}

		if (!$berror) {
			$id = $post_data['contact_id'];
			$res = $this->Contact_model->get_contact($id);
			if (!empty($res)) {
				$msg =  "success";
				$code = 0;
				$data = $res;
			}else {
				$msg =  "無資料";
				$code = 3;
			}
		}
		$res_data = array('msg' => $msg,'code' => $code,'data' => $data,);
		$json = json_encode($res_data,320);
		echo $json;
	}
	 //改分類狀態
	 public function ajax_change_status()
	 {
		 //限定POST,防機器人,來源
		 if ($this->input->server('REQUEST_METHOD') != 'POST' || $this->agent->is_robot() || $this->agent->is_referral()) {
			 show_error('Access forbidden', 403);
			 exit;
		 }
 
		 //XSS過濾
		 $post_data =$this->security->xss_clean($this->input->post());
		 if (empty($this->security->xss_clean($post_data))) {
			 $msg_array = array(
				 "msg" => '非法提交參數',
				 "code" => 2
			 );
			 $msg = json_encode($msg_array,320);
			 echo $msg;
			 exit;
		 }
 
		 //contact_id
		 $this->form_validation->set_rules(
			 'contact_id', 'contact_id','trim|required|greater_than_equal_to[1]|less_than_equal_to[999999999]',
			 array(
				 'required'  => '非法提交參數',
				 'greater_than_equal_to' => '非法提交參數',
				 'less_than_equal_to' => '非法提交參數'
			 )
		 );
		 //status
		 $this->form_validation->set_rules(
			 'status', 'status','trim|required|greater_than_equal_to[0]|less_than_equal_to[1]',
			 array(
				 'required'  => '非法提交參數',
				 'greater_than_equal_to' => '非法提交參數',
				 'less_than_equal_to' => '非法提交參數'
			 )
		 );
 
		 //驗證數據
		 $msg_array = array();
		 if ($this->form_validation->run() === FALSE){
			 $text = trim(validation_errors());
			 $code = 1;
			 $msg_array = array(
				 "msg" => $text,
				 "code" => $code
			 );
			 $msg = json_encode($msg_array,320);
			 echo $msg;
			 exit;
		 }
		 
		 //更新狀態
		 $status = $post_data['status'];
		 $id = $post_data['contact_id'];
		 $res = $this->Contact_model->update_status($id,$status);
		 if ($res === TRUE) {
			 $text = '修改成功';
			 $code = 0;
		 }else {
			 $text = '修改失敗';
			 $code = 3;
		 }
		 $msg_array = array(
			 "msg" => $text,
			 "code" => $code
		 );
		 $msg = json_encode($msg_array,320);
		 echo $msg;
	 }
}
/* End of file Contact.php */