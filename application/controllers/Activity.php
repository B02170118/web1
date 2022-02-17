<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Activity extends CI_Controller
{
	public function __construct() 
	{
		parent::__construct();

		$this->load->library(array('test1111_lib','form_validation','user_agent'));
        $this->load->helper(array('url'));
		$this->load->model(array('Activity_model'));

		//選單
		// $this->HeaderMenu = $this->test1111_lib->get_menu();
		
		//登入判斷
		$this->test1111_lib->check_login();
	}
	public function index()
	{	
		$data = array();
		$query = array();

        $data['title'] = "首頁活動管理";

		//data
        $query = $this->Activity_model->get_all_data();
        if (!empty($query)) {
            $data['activity'] = $query;
        }

		$this->load->view('templates/head',$data);
		$this->load->view('templates/header');
		$this->load->view('templates/title');
		$this->load->view('activity/index');
		$this->load->view('templates/footer');
		$this->load->view('activity/app');
	}
	//新增
	public function add_activity()
	{
		$data = array();

        $data['title'] = "首頁活動管理-新增";

		$this->load->view('templates/head',$data);
		$this->load->view('templates/header');
		$this->load->view('templates/title');
		$this->load->view('activity/add_activity');
		$this->load->view('templates/footer');
		$this->load->view('activity/add_activity_app');
	}
	//新增表單
	public function form_add_activity()
	{
		//限定POST,防機器人,來源
		if ($this->input->server('REQUEST_METHOD') != 'POST' || $this->agent->is_robot() || $this->agent->is_referral()) {
			show_error('Access forbidden', 403);
			exit;
		}

        $berror = FALSE;
        $msg = "";
        $end_sign = "<br>";

		//XSS過濾
		$post_data =$this->security->xss_clean($this->input->post());
        if (empty($this->security->xss_clean($post_data))) {
            $msg ='非法提交參數';
            $berror = TRUE;
        }
       
        //驗證
        //href
        $this->form_validation->set_rules(
            'href', 'href','trim|min_length[1]|max_length[1000]',
            array(
                'min_length' => '字元長度不合法',
                'max_length' => '字元長度不合法'
            )
        );
        //start_time
        $this->form_validation->set_rules(
            'start_time', 'start_time','trim|required|min_length[1]|max_length[255]',
            array(
                'required'  => '時間錯誤',
                'min_length' => '時間錯誤',
                'max_length' => '時間錯誤'
            )
		);
		//end_time
        $this->form_validation->set_rules(
            'end_time', 'end_time','trim|required|min_length[1]|max_length[255]',
            array(
                'required'  => '時間錯誤',
                'min_length' => '時間錯誤',
                'max_length' => '時間錯誤'
            )
        );
        if ($this->form_validation->run() === FALSE){
            $msg = trim(validation_errors());
            $berror = TRUE;
        }
        
        //上傳
        if (!$berror) {
            $type = $post_data['type'];
            $file = $_FILES['userfile'];
            $res = $this->test1111_lib->upload_img($file,$type);
            if (empty($res)) {
                $msg = "系統錯誤";
                $berror = TRUE;
            }
        }
        
        //資料庫
        if (!$berror) {
            foreach ($res as $row) {
                if ($row['code'] == 0) {
                    $dir = trim($row['dir']);
					$href = $post_data['href'];
					$start_time = $post_data['start_time'];
					$end_time = $post_data['end_time'];
                    $insert_res = $this->Activity_model->add_activity($dir,$href,$start_time,$end_time);
                    if ($insert_res === FALSE) {
                        $msg .= "新增失敗";
                        //刪除圖片
                        if (!empty($dir)) {
                            $delete_dir = WWW_test1111_COM_IMG_LOCATION.$dir;
                            @unlink($delete_dir);
                        }
                    }else{
                        $msg .= "新增成功";
                    }
                }else{
                    $msg .= $row['msg'];
                }
                $msg .= $end_sign;
            }
        }

        //html
        $data = array();

        $data['title'] = "新增結果";
        $data['msg'] = $msg;
        $data['url'] = 'activity';

        $this->load->view('templates/head',$data);
		$this->load->view('templates/header');
		$this->load->view('templates/title');
		$this->load->view('templates/result');
		$this->load->view('templates/footer');
	}
	//順序變更
	public function ajax_edit_activity_seq()
	{
        //限定POST,防機器人,來源
		if ($this->input->server('REQUEST_METHOD') != 'POST' || $this->agent->is_robot() || $this->agent->is_referral()) {
			show_error('Access forbidden', 403);
			exit;
        }
        
        $berror = FALSE;
        $msg = "刪除失敗";
        $code = 1;

        //XSS過濾
		$post_data = $this->security->xss_clean($this->input->post());
        if (empty($this->security->xss_clean($post_data))) {
            $msg ='非法提交參數';
            $berror = TRUE;
        }

        //驗證
        $sort = $post_data['sort'];
        $sort_array = explode(",",$sort);
        foreach ($sort_array as $value) {
            if(is_numeric($value) === FALSE){
                $berror = TRUE;
                $msg = "非法提交參數";
                $code = 2;
                break;
            }
        }

        //排序
        if (!$berror) {
            $res = $this->Activity_model->edit_activity_seq($sort_array);
            //刪資料庫
            if ($res === FALSE) {
                $msg = "調整順序失敗";
                $code = 3;
                $berror = TRUE;
            }
        }

        //成功
        if (!$berror) {
            $msg = "調整順序成功";
            $code = 0;
        }

        $msg_array = array(
            "msg" => $msg,
            "code" => $code
        );
        $msg = json_encode($msg_array,320);
        echo $msg;		
	}
	//刪除
	public function ajax_del_activity()
	{
		//限定POST,防機器人,來源
		if ($this->input->server('REQUEST_METHOD') != 'POST' || $this->agent->is_robot() || $this->agent->is_referral()) {
			show_error('Access forbidden', 403);
			exit;
        }

        $berror = FALSE;
        $code = 5;
        $msg = "失敗";
        $end_sign = "<br>";
        //XSS過濾
		$post_data = $this->security->xss_clean($this->input->post());
        if (empty($this->security->xss_clean($post_data))) {
            $msg ='非法提交參數';
            $code = 1;
            $berror = TRUE;
        }
        
        //驗證
        //id
		$this->form_validation->set_rules(
            'id', 'id','trim|required|greater_than_equal_to[1]|less_than_equal_to[999999999]',
            array(
                'required'  => '非法提交參數',
                'greater_than_equal_to' => '非法提交參數',
                'less_than_equal_to' => '非法提交參數'
            )
        );

        //刪除
        if (!$berror) {
            $id = $post_data['id'];
            $query = $this->Activity_model->get_img($id);
            if (!empty($query)) {
				//刪資料&圖片
				$img = !empty($query['img'])? $query['img'] : "";;
				$res = $this->Activity_model->del_activity($id);
				//刪資料庫
				if ($res === FALSE) {
					$msg .= "刪除失敗";
					$code = 2;
				}else {
					$msg = "刪除成功, ";
					$code = 0;
					//刪除圖片
					if (!empty($img)) {
						$delete_dir = WWW_test1111_COM_IMG_LOCATION.$img;
						if(is_file($delete_dir)){ //路徑檢查
							if (unlink($delete_dir) == FALSE) { //刪除結果
								$msg .= "檔案刪除失敗 : $delete_dir.$end_sign";
								$code = 3;
							}else {
								$msg .= "檔案刪除成功";
								$code = 0;
							}
						}else {
							$msg .= "找不到檔案路徑 : $delete_dir.$end_sign";
							$code = 4;
						}
					}
				}
            }else {
                $msg = "找不到資料";
                $code = 6;
            }
        }

        $msg_array = array(
            "msg" => $msg,
            "code" => $code
        );
        $msg = json_encode($msg_array,320);
        echo $msg;
	}
	//編輯
	public function edit_activity($id)
	{
		$data = array();

		$data['title'] = "首頁活動管理-編輯";
        $data['url'] = $this->uri->uri_string();

		//data
        if (intval($id)>0) {
            $query = $this->Activity_model->get_data($id);
            if (!empty($query)) {
                $data['activity'] = $query;
            }
		}
		
		$this->load->view('templates/head',$data);
		$this->load->view('templates/header');
		$this->load->view('templates/title');
		$this->load->view('activity/edit_activity');
		$this->load->view('templates/footer');
		$this->load->view('activity/edit_activity_app');
	}
	//編輯表單
	public function form_edit_activity()
	{
		//限定POST,防機器人,來源
		if ($this->input->server('REQUEST_METHOD') != 'POST' || $this->agent->is_robot() || $this->agent->is_referral()) {
			show_error('Access forbidden', 403);
			exit;
		}

        $berror = FALSE;
        $msg = "";
        $end_sign = "<br>";

		//XSS過濾
		$post_data =$this->security->xss_clean($this->input->post());
        if (empty($this->security->xss_clean($post_data))) {
            $msg ='非法提交參數';
            $berror = TRUE;
        }
       
		//驗證
		//id
		$this->form_validation->set_rules(
			'id', 'id','trim|required|greater_than_equal_to[1]|less_than_equal_to[999999999]',
			array(
				'required'  => '非法提交參數',
				'greater_than_equal_to' => '非法提交參數',
				'less_than_equal_to' => '非法提交參數'
			)
		);
        //href
        $this->form_validation->set_rules(
            'href', 'href','trim|min_length[1]|max_length[1000]',
            array(
                'min_length' => '字元長度不合法',
                'max_length' => '字元長度不合法'
            )
        );
        //start_time
        $this->form_validation->set_rules(
            'start_time', 'start_time','trim|required|min_length[1]|max_length[255]',
            array(
                'required'  => '時間錯誤',
                'min_length' => '時間錯誤',
                'max_length' => '時間錯誤'
            )
		);
		//end_time
        $this->form_validation->set_rules(
            'end_time', 'end_time','trim|required|min_length[1]|max_length[255]',
            array(
                'required'  => '時間錯誤',
                'min_length' => '時間錯誤',
                'max_length' => '時間錯誤'
            )
        );
        if ($this->form_validation->run() === FALSE){
            $msg = trim(validation_errors());
            $berror = TRUE;
        }
        
        //上傳
        if (!$berror) {
			$id = $post_data['id'];
			$type = $post_data['type'];
			$href = $post_data['href'];
			$start_time = $post_data['start_time'];
			$end_time = $post_data['end_time'];
			if (empty($_FILES['userfile']['name'][0])) { //只更新資料庫
				$update_res = $this->Activity_model->edit_activity($id,NULL,$href,$start_time,$end_time,0);
                if ($update_res === FALSE) {
                    $msg .= "更新失敗";
                }else{
                    $msg .= "更新成功";
                }
			}else {
				$file = $_FILES['userfile'];
				$res = $this->test1111_lib->upload_img($file,$type);
				if (empty($res)) {
					$msg = "系統錯誤";
					$berror = TRUE;
				}
				//資料庫
				if (!$berror) {
					foreach ($res as $row) {
						if ($row['code'] == 0) {
							//原圖
							$query = $this->Activity_model->get_img($id);
							$original = !empty($query['img'])? $query['img'] : "";
							$dir = trim($row['dir']);
							$update_res = $this->Activity_model->edit_activity($id,$dir,$href,$start_time,$end_time,1);
							if ($update_res === FALSE) {
								$msg .= "更新失敗";
								//刪除圖片
								if (!empty($dir)) {
									$delete_dir = WWW_test1111_COM_IMG_LOCATION.$dir;
									@unlink($delete_dir);
								}
							}else{
								$msg .= "更新成功";
								//刪除圖片
								if (!empty($original)) {
									$delete_dir = WWW_test1111_COM_IMG_LOCATION.$original;
									@unlink($delete_dir);
								}
							}
						}else{
							$msg .= $row['msg'];
						}
						$msg .= $end_sign;
					}
				}
			}
        }
        
        

        //html
        $data = array();

        $data['title'] = "編輯結果";
        $data['msg'] = $msg;
        $data['url'] = 'activity';

        $this->load->view('templates/head',$data);
		$this->load->view('templates/header');
		$this->load->view('templates/title');
		$this->load->view('templates/result');
		$this->load->view('templates/footer');
	}
}
/* End of file Activity.php */