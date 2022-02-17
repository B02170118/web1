<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Image extends CI_Controller
{
	public function __construct() 
	{
		parent::__construct();

		$this->load->library(array('test1111_lib','form_validation','user_agent','upload'));
        $this->load->helper(array('url','file'));
		$this->load->model(array('Image_model'));

		//選單
		// $this->HeaderMenu = $this->test1111_lib->get_menu();
		
		//登入判斷
		$this->test1111_lib->check_login();
	}
	
	public function index()
	{	
		$data = array();
		$query = array();

        $data['title'] = "攝影作品-相簿管理";

		//相簿data
        $query = $this->Image_model->get_all_workphoto_catrgory();
        if (!empty($query)) {
            $data['photo'] = $query;
        }

		$this->load->view('templates/head',$data);
		$this->load->view('templates/header');
		$this->load->view('templates/title');
		$this->load->view('image/index');
		$this->load->view('templates/footer');
		$this->load->view('image/app');
    }
    //改相簿狀態
    //改相簿狀態
    public function ajax_change_status_category(){
        //限定POST,防機器人,來源
        if ($this->input->server('REQUEST_METHOD') != 'POST' || $this->agent->is_robot() || $this->agent->is_referral() || $this->agent->is_referral()) {
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

        //category_id
		$this->form_validation->set_rules(
            'category_id', 'category_id','trim|required|greater_than_equal_to[1]|less_than_equal_to[999999999]',
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
        $category_id = $post_data['category_id'];
        $res = $this->Image_model->update_status_category($category_id,$status);
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
    //新增相簿
    public function add_category()
    {
        $data = array();
        
		$data['title'] = "攝影作品-新增相簿";

		$this->load->view('templates/head',$data);
		$this->load->view('templates/header');
		$this->load->view('templates/title');
		$this->load->view('image/add_category');
		$this->load->view('templates/footer');
		$this->load->view('image/upload_img_app');
    }
    //新增相簿表單
    public function form_add_category()
    {
        //限定POST,防機器人,來源
		if ($this->input->server('REQUEST_METHOD') != 'POST' || $this->agent->is_robot() || $this->agent->is_referral() || $this->agent->is_referral()) {
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
        //category_name
        $this->form_validation->set_rules(
            'category_name', 'category_name','trim|required|min_length[1]|max_length[255]',
            array(
                'required'  => '非法提交參數',
                'min_length' => '字元長度不合法',
                'max_length' => '字元長度不合法'
            )
        );
        //type
        $this->form_validation->set_rules(
            'type', 'type','trim|required|min_length[1]|max_length[255]',
            array(
                'required'  => '非法提交參數',
                'min_length' => '非法上傳類型',
                'max_length' => '非法上傳類型'
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
                    $category_name = $post_data['category_name'];
                    $insert_res = $this->Image_model->insert_new_category($category_name,$dir);
                    if ($insert_res === FALSE) {
                        $msg .= "新增相簿失敗";
                        //刪除圖片
                        if (!empty($dir)) {
                            $delete_dir = WWW_test1111_COM_IMG_LOCATION.$dir;
                            @unlink($delete_dir);
                        }
                    }else{
                        $msg .= "新增相簿成功";
                    }
                }else{
                    $msg .= $row['msg'];
                }
                $msg .= $end_sign;
            }
        }

        //html
        $data = array();

        $data['title'] = "新增相簿結果";
        $data['msg'] = $msg;
        $data['url'] = 'image';

        $this->load->view('templates/head',$data);
		$this->load->view('templates/header');
		$this->load->view('templates/title');
		$this->load->view('templates/result');
		$this->load->view('templates/footer');
    }
    //編輯相簿表單
    public function form_edit_category()
    {
        //限定POST,防機器人,來源
		if ($this->input->server('REQUEST_METHOD') != 'POST' || $this->agent->is_robot() || $this->agent->is_referral() || $this->agent->is_referral()) {
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
        //cid
        $this->form_validation->set_rules(
            'cid', 'cid','trim|required|greater_than_equal_to[1]|less_than_equal_to[999999999]',
            array(
                'required'  => '非法提交參數',
                'greater_than_equal_to' => '非法提交參數',
                'less_than_equal_to' => '非法提交參數'
            )
        );
        //category_name
        $this->form_validation->set_rules(
            'category_name', 'category_name','trim|required|min_length[0]|max_length[255]',
            array(
                'required'  => '非法提交參數',
                'min_length' => '非法提交參數',
                'max_length' => '非法提交參數'
            )
        );
        //type
        $this->form_validation->set_rules(
            'type', 'type','trim|required|min_length[0]|max_length[255]',
            array(
                'required'  => '非法提交參數',
                'min_length' => '非法提交參數',
                'max_length' => '非法提交參數'
            )
        );

        if ($this->form_validation->run() === FALSE){
            $msg = trim(validation_errors());
            $berror = TRUE;
        }
        
        $cateogry_id = $post_data['cid'];
        $category_name = $post_data['category_name'];
        
        //上傳
        if (empty($_FILES['userfile']['name'][0])) { //只更新資料
            $update_res = $this->Image_model->update_category(NULL,$cateogry_id,$category_name,0);
            if ($update_res === FALSE) {
                $msg .= "更新失敗";
            }else{
                $msg .= "更新成功";
            }
        }else {
            if (!$berror) {
                $type = $post_data['type'];
                $file = $_FILES['userfile'];
                $res = $this->test1111_lib->upload_img($file,$type);
                if (empty($res)) {
                    $msg = "系統錯誤";
                    $berror = TRUE;
                }
            }
            //update資料庫
            if (!$berror) {
                foreach ($res as $row) {
                    if ($row['code'] == 0) {
                        $dir = trim($row['dir']);
                        //找相簿原圖
                        $query = $this->Image_model->get_category_img($cateogry_id);
                        $original_img = !empty($query) ? $query['default_img'] : NULL;
                        $update_res = $this->Image_model->update_category($dir,$cateogry_id,$category_name,1);
                        if ($update_res === FALSE) {
                            $msg .= "更新失敗";
                            //刪除圖片
                            if (!empty($dir)) {
                                $delete_dir = WWW_test1111_COM_IMG_LOCATION.$dir;
                                @unlink($delete_dir);
                            }
                        }else{
                            $msg .= "更新成功";
                            //刪除原圖
                            if (!empty($original_img)) {
                                $delete_dir = WWW_test1111_COM_IMG_LOCATION.$original_img;
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

        //html
        $data = array();

        $data['title'] = "結果";
        $data['msg'] = $msg;
        $data['url'] = !empty($this->agent->referrer())? $this->agent->referrer() : 'image';

        $this->load->view('templates/head',$data);
		$this->load->view('templates/header');
		$this->load->view('templates/title');
		$this->load->view('templates/result');
		$this->load->view('templates/footer');
    }
    //相簿順序變更
    public function ajax_edit_category_seq()
    {
        //限定POST,防機器人,來源
		if ($this->input->server('REQUEST_METHOD') != 'POST' || $this->agent->is_robot() || $this->agent->is_referral() || $this->agent->is_referral()) {
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
            $res = $this->Image_model->edit_category_seq($sort_array);
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
    //刪除相簿
    public function ajax_del_category()
    {
        //限定POST,防機器人,來源
		if ($this->input->server('REQUEST_METHOD') != 'POST' || $this->agent->is_robot() || $this->agent->is_referral() || $this->agent->is_referral()) {
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
        $id_list = $post_data['id'];
        $id_array = explode(",",$id_list);
        foreach ($id_array as $value) {
            if(is_numeric($value) === FALSE){
                $berror = TRUE;
                $msg = "非法提交參數";
                $code = 2;
                break;
            }
        }
        //刪除
        if (!$berror) {
            $query = $this->Image_model->get_del_category_data($id_array);
            if (!empty($query)) {
                //刪相簿資料&相簿圖片
                if (!empty($query['category_img'])) {
                    foreach ($query['category_img'] as $row) {
                        $img = $row['default_img'];
                        $id = $row['id'];
                        $res = $this->Image_model->del_category($id);
                        //刪資料庫
                        if ($res === FALSE) {
                            $msg .= "刪除相簿資料失敗 ID:$id.$end_sign";
                            $code = 4;
                            $berror = TRUE;
                            continue;
                        }
                        //刪檔案
                        $delete_dir = WWW_test1111_COM_IMG_LOCATION.$img;
                        if(is_file($delete_dir)){ //路徑檢查
                            if (unlink($delete_dir) == FALSE) { //刪除結果
                                $msg .= "刪除失敗 : $delete_dir.$end_sign";
                                $code = 5;
                                $berror = TRUE;
                            }
                        }else {
                            $msg .= "找不到檔案路徑 : $delete_dir.$end_sign";
                            $code = 4;
                            $berror = TRUE;
                        }
                    }
                }
                //刪相簿下的圖片資料
                if (!empty($query['img'])) {
                    foreach ($query['img'] as $row) {
                        $img = $row['img'];
                        $id = $row['id'];
                        $res = $this->Image_model->del_img($id);
                        //刪資料庫
                        if ($res === FALSE) {
                            $msg .= "刪除相片資料失敗 ID:$id.$end_sign";
                            $code = 4;
                            $berror = TRUE;
                            continue;
                        }
                        //刪檔案
                        $delete_dir = WWW_test1111_COM_IMG_LOCATION.$img;
                        if(is_file($delete_dir)){ //路徑檢查
                            if (unlink($delete_dir) == FALSE) { //刪除結果
                                $msg .= "刪除失敗 : $delete_dir.$end_sign";
                                $code = 5;
                                $berror = TRUE;
                            }
                        }else {
                            $msg .= "找不到檔案路徑 : $delete_dir.$end_sign";
                            $code = 4;
                            $berror = TRUE;
                        }
                    }
                }
            }else {
                $msg = "找不到資料";
                $code = 3;
                $berror = TRUE;
            }
        }

        if (!$berror) {
            $msg = "刪除成功";
            $code = 0;
        }

        $msg_array = array(
            "msg" => $msg,
            "code" => $code
        );
        $msg = json_encode($msg_array,320);
        echo $msg;
    }
    //相簿裡面的相片
    public function workphoto_category_view($id = NULL)
    {
        if (intval($id) > 0) {
            $cateogry_id = intval($id);
        }

        $data = array();
        $query = array();
        
        //相簿裡的相片
        $query = $this->Image_model->get_workphoto_catrgory_img($cateogry_id);
        if (!empty($query)) {
            $data['photo'] = $query['photo'];
            $data['category'] = $query['category'];
        }

        $data['title'] = '攝影作品-'.$query['category']['name'];

        $this->load->view('templates/head',$data);
        $this->load->view('templates/header');
        $this->load->view('templates/title');
        $this->load->view('image/workphoto_category_view');
        $this->load->view('templates/footer');
        $this->load->view('image/workphoto_category_view_app');
    }
    //改相片狀態
    public function ajax_change_status_workphoto(){
        //限定POST,防機器人,來源
        if ($this->input->server('REQUEST_METHOD') != 'POST' || $this->agent->is_robot() || $this->agent->is_referral() || $this->agent->is_referral()) {
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

        //category_id
		$this->form_validation->set_rules(
            'category_id', 'category_id','trim|required|greater_than_equal_to[1]|less_than_equal_to[999999999]',
            array(
                'required'  => '非法提交參數',
                'greater_than_equal_to' => '非法提交參數',
                'less_than_equal_to' => '非法提交參數'
            )
        );
        //workphoto_id
		$this->form_validation->set_rules(
            'workphoto_id', 'workphoto_id','trim|required|greater_than_equal_to[1]|less_than_equal_to[999999999]',
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
        $category_id = $post_data['category_id'];
        $workphoto_id = $post_data['workphoto_id'];
        $res = $this->Image_model->update_status_workphoto($category_id,$workphoto_id,$status);
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
    //上傳相片
	public function upload_img($cid = NULL)
	{
        $data = array();

        $data['url'] = urlencode($this->agent->referrer());
        $data['title'] = "攝影作品-上傳圖片";

        if (intval($cid) > 0) {
            $query = $this->Image_model->get_workphoto_catrgory_name($cid);
            if (!empty($query)) {
                $data['category_id'] = $query['id'];
                $data['category_name'] = $query['name'];
            }
            $this->load->view('templates/head',$data);
            $this->load->view('templates/header');
            $this->load->view('templates/title');
            $this->load->view('image/upload_img');
            $this->load->view('templates/footer');
            $this->load->view('image/upload_img_app');
        }else {
            //html
            $data['msg'] = "參數錯誤";

            $this->load->view('templates/head',$data);
            $this->load->view('templates/header');
            $this->load->view('templates/title');
            $this->load->view('templates/result');
            $this->load->view('templates/footer');
        }
    }
    //刪除相片
    public function ajax_del_img()
    {
        //限定POST,防機器人,來源
		if ($this->input->server('REQUEST_METHOD') != 'POST' || $this->agent->is_robot() || $this->agent->is_referral() || $this->agent->is_referral()) {
			show_error('Access forbidden', 403);
			exit;
        }

        $berror = FALSE;
        $code = 5;
        $msg = "刪除失敗";

        //XSS過濾
		$post_data = $this->security->xss_clean($this->input->post());
        if (empty($this->security->xss_clean($post_data))) {
            $msg ='非法提交參數';
            $code = 1;
            $berror = TRUE;
        }
        
        //驗證
        $id_list = $post_data['id'];
        $id_array = explode(",",$id_list);
        foreach ($id_array as $value) {
            if(is_numeric($value) === FALSE){
                $berror = TRUE;
                $msg = "非法提交參數";
                $code = 2;
                break;
            }
        }
        //刪除
        if (!$berror) {
            $query = $this->Image_model->get_img($id_array);
            if (!empty($query)) {
                foreach ($query as $row) {
                    $img = $row['img'];
                    $id = $row['id'];
                    $res = $this->Image_model->del_img($id);
                    //刪資料庫
                    if ($res === FALSE) {
                        $msg .= "刪除資料失敗 ID:$id,";
                        $code = 4;
                        $berror = TRUE;
                        continue;
                    }
                    //刪檔案
                    $delete_dir = WWW_test1111_COM_IMG_LOCATION.$img;
                    if(is_file($delete_dir)){ //路徑檢查
                        if (unlink($delete_dir) == FALSE) { //刪除結果
                            $msg .= "刪除失敗 : $delete_dir";
                            $code = 5;
                            $berror = TRUE;
                        }
                    }else {
                        $msg .= "找不到檔案路徑 : $delete_dir";
                        $code = 4;
                        $berror = TRUE;
                    }
                }
            }else {
                $msg = "找不到資料";
                $code = 3;
                $berror = TRUE;
            }
        }

        if (!$berror) {
            $msg = "刪除成功";
            $code = 0;
        }

        $msg_array = array(
            "msg" => $msg,
            "code" => $code
        );
        $msg = json_encode($msg_array,320);
        echo $msg;
    }
    //上傳相片表單
    public function form_upload_img()
    {
		//限定POST,防機器人,來源
		if ($this->input->server('REQUEST_METHOD') != 'POST' || $this->agent->is_robot() || $this->agent->is_referral() || $this->agent->is_referral()) {
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
        //category_id
        $this->form_validation->set_rules(
            'cateogry', 'cateogry','trim|required|greater_than_equal_to[1]|less_than_equal_to[999999999]',
            array(
                'required'  => '非法提交參數',
                'greater_than_equal_to' => '非法提交參數',
                'less_than_equal_to' => '非法提交參數'
            )
        );
        //type
        $this->form_validation->set_rules(
            'type', 'type','trim|required|min_length[0]|max_length[255]',
            array(
                'required'  => '非法提交參數',
                'min_length' => '非法提交參數',
                'max_length' => '非法提交參數'
            )
        );
        //url
        $this->form_validation->set_rules(
            'url', 'url','trim|required|min_length[0]|max_length[500]',
            array(
                'required'  => '非法提交參數',
                'min_length' => '非法提交參數',
                'max_length' => '非法提交參數'
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
        
        //update資料庫
        if (!$berror) {
            $cateogry = $post_data['cateogry'];
            foreach ($res as $row) {
                if ($row['code'] == 0) {
                    $dir = trim($row['dir']);
                    $update_res = $this->Image_model->insert_new_img($dir,$cateogry);
                    if ($update_res === FALSE) {
                        $msg .= "新增相片失敗";
                        //刪除圖片
                        if (!empty($dir)) {
                            $delete_dir = WWW_test1111_COM_IMG_LOCATION.$dir;
                            @unlink($delete_dir);
                        }
                    }else{
                        $msg .= "新增相片成功";
                    }
                }else{
                    $msg .= $row['msg'];
                }
                $msg .= $end_sign;
            }
        }

        //html
        $data = array();

        $data['title'] = "上傳結果";
        $data['msg'] = $msg;
        $data['url'] = !empty($post_data['url'])? $post_data['url'] : 'image';

        $this->load->view('templates/head',$data);
		$this->load->view('templates/header');
		$this->load->view('templates/title');
		$this->load->view('templates/result');
		$this->load->view('templates/footer');
    }
    //相片順序變更
    public function ajax_edit_img_seq()
    {
        //限定POST,防機器人,來源
		if ($this->input->server('REQUEST_METHOD') != 'POST' || $this->agent->is_robot() || $this->agent->is_referral() || $this->agent->is_referral()) {
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
            $res = $this->Image_model->edit_img_seq($sort_array);
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
}
/* End of file Image.php */