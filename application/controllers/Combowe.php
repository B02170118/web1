<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Combowe extends CI_Controller
{
	public function __construct() 
	{
		parent::__construct();

		$this->load->library(array('test1111_lib','form_validation','user_agent','upload'));
        $this->load->helper(array('url','file'));
		$this->load->model(array('Combowe_model'));

		//選單
		// $this->HeaderMenu = $this->test1111_lib->get_menu();
		
		//登入判斷
		$this->test1111_lib->check_login();
	}
	
	public function index()
	{	
		$data = array();
		$query = array();

        $data['title'] = "包套內容管理";

		//包套data
        $query = $this->Combowe_model->get_all_combowe_main();
        if (!empty($query)) {
            $data['combowe'] = $query;
        }

		$this->load->view('templates/head',$data);
		$this->load->view('templates/header');
		$this->load->view('templates/title');
		$this->load->view('combowe/index');
		$this->load->view('templates/footer');
		$this->load->view('combowe/app');
    }
    //新增包套項目
    public function add_main()
    {
        $data = array();
        
		$data['title'] = "新增包套項目";

		$this->load->view('templates/head',$data);
		$this->load->view('templates/header');
		$this->load->view('templates/title');
		$this->load->view('combowe/add_main');
		$this->load->view('templates/footer');
		$this->load->view('combowe/add_main_app');
    }
    //新增包套表單
    public function form_add_main()
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
        //title
        $this->form_validation->set_rules(
            'title', 'title','trim|required|min_length[1]|max_length[255]',
            array(
                'required'  => '名稱未輸入',
                'min_length' => '名稱輸入錯誤',
                'max_length' => '名稱長度超過'
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
                $msg = var_dump($type);
                $berror = TRUE;
            }
        }
        
        //資料庫
        if (!$berror) {
            foreach ($res as $row) {
                if ($row['code'] == 0) {
                    $dir = trim($row['dir']);
                    $title = $post_data['title'];
                    $insert_res = $this->Combowe_model->insert_new_combowe_main($title,$dir);
                    if ($insert_res === FALSE) {
                        $msg .= "新增失敗";
                        //刪除圖片
                        if (!empty($dir)) {
                            $delete_dir = WWW_test1111_COM_IMG_LOCATION.$dir;
                            @unlink($delete_dir);
                        }
                    }else{
                        $msg .= $row['msg'];
                        $msg .= "新增成功 : $dir";
                    }
                }else{
                    $msg .= $row['msg'];
                }
                $msg .= $end_sign;
            }
        }

        //html
        $data = array();

        $data['title'] = "新增包套結果";
        $data['msg'] = $msg;
        $data['url'] = 'combowe';

        $this->load->view('templates/head',$data);
		$this->load->view('templates/header');
		$this->load->view('templates/title');
		$this->load->view('templates/result');
		$this->load->view('templates/footer');
    }
    //編輯包套項目表單
    public function form_edit_main()
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
        //main_id
        $this->form_validation->set_rules(
            'main_id', 'main_id','trim|required|greater_than_equal_to[1]|less_than_equal_to[999999999]',
            array(
                'required'  => '非法提交參數',
                'greater_than_equal_to' => '非法提交參數',
                'less_than_equal_to' => '非法提交參數'
            )
        );
        //main_title
        $this->form_validation->set_rules(
            'main_title', 'main_title','trim|required|min_length[0]|max_length[255]',
            array(
                'required'  => '名稱未輸入',
                'min_length' => '名稱輸入錯誤',
                'max_length' => '名稱長度超過'
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
        
        $main_id = $post_data['main_id'];
        $main_title = $post_data['main_title'];
        
        if (!$berror) {
            if (empty($_FILES['userfile']['name'][0])) { //只更新資料
                $update_res = $this->Combowe_model->edit_category_img(NULL,$main_id,$main_title,0);
                if ($update_res === FALSE) {
                    $msg .= "更新失敗";
                }else{
                    $msg .= "更新成功";
                }
            }else {
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
                    foreach ($res as $row) {
                        if ($row['code'] == 0) {
                            $dir = trim($row['dir']);
                            //找原圖
                            $query = $this->Combowe_model->get_img($main_id);
                            $original_img = !empty($query) ? $query['img'] : NULL;
                            $update_res = $this->Combowe_model->edit_category_img($dir,$main_id,$main_title,1);
                            if ($update_res === FALSE) {
                                $msg .= "修改失敗";
                                //刪除圖片
                                if (!empty($original_img)) {
                                    $delete_dir = WWW_test1111_COM_IMG_LOCATION.$original_img;
                                    @unlink($delete_dir);
                                }
                            }else{
                                $msg .= "修改成功";
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
        }
        

        //html
        $data = array();

        $data['title'] = "結果";
        $data['msg'] = $msg;
        $data['url'] = !empty($this->agent->referrer())? $this->agent->referrer() : 'combowe';

        $this->load->view('templates/head',$data);
		$this->load->view('templates/header');
		$this->load->view('templates/title');
		$this->load->view('templates/result');
		$this->load->view('templates/footer');
    }
    //包套項目順序變更
    public function ajax_edit_main_seq()
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
            $res = $this->Combowe_model->edit_main_seq($sort_array);
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
    //刪除包套項目
    public function ajax_del_main()
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
            $main_id = $post_data['id'];
            $query = $this->Combowe_model->get_img($main_id);
            if (!empty($query)) {
                //刪包套資料&包套圖片
                if (!empty($query)) {
                    foreach ($query as $row) {
                        $img = $row['img'];
                        $id = $row['id'];
                        $res = $this->Combowe_model->del_main($id);
                        //刪資料庫
                        if ($res === FALSE) {
                            $msg .= "刪除項目資料失敗 ID:$id.$end_sign";
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
    //包套項目裡面的分類
    public function combowe_category($id = NULL)
    {
        $main_id = 0;
        if (intval($id) > 0) {
            $main_id = intval($id);
        }

        $data = array();
        $query = array();
        $data['title'] = "錯誤";

        //包套裡分類
        if ($main_id > 0) {
            $query = $this->Combowe_model->get_combowe_category($main_id);
            if (!empty($query)) {
                $data['title'] = '包套內容 -' . $query['main']['title'];
                $data['main'] = $query['main'];
                $data['category'] = $query['category'];
            }
        }

        $this->load->view('templates/head',$data);
        $this->load->view('templates/header');
        $this->load->view('templates/title');
        $this->load->view('combowe/combowe_category');
        $this->load->view('templates/footer');
        $this->load->view('combowe/combowe_category_app');
    }
    //新增項目的分類
    public function ajax_add_category($id = NULL)
    {
        //限定POST,防機器人,來源
        if ($this->input->server('REQUEST_METHOD') != 'POST' || $this->agent->is_robot() || $this->agent->is_referral()) {
            show_error('Access forbidden', 403);
            exit;
        }

        $berror = FALSE;
        $msg_array = array("msg" => '新增失敗',"code" => 1);

        //XSS過濾
        $post_data =$this->security->xss_clean($this->input->post());
        if (empty($this->security->xss_clean($post_data))) {
            $msg_array['msg'] = '非法提交參數';
            $msg_array['code'] = '2';
            $berror = TRUE;
        }
    
        //驗證
        //main_id
		$this->form_validation->set_rules(
            'main_id', 'main_id','trim|required|greater_than_equal_to[1]|less_than_equal_to[999999999]',
            array(
                'required'  => '非法提交參數',
                'greater_than_equal_to' => '非法提交參數',
                'less_than_equal_to' => '非法提交參數'
            )
		);
        //category_title
        $this->form_validation->set_rules(
            'category_title', 'category_title','trim|required|min_length[1]|max_length[255]',
            array(
                'required'  => '名稱未輸入',
                'min_length' => '名稱輸入錯誤',
                'max_length' => '名稱輸入長度超過'
            )
        );
        //old_price
        $this->form_validation->set_rules(
            'old_price', 'old_price','trim|required|min_length[1]|max_length[100]',
            array(
                'required'  => '舊金額未輸入',
                'min_length' => '舊金額輸入錯誤',
                'max_length' => '舊金額輸入長度超過'
            )
        );
        //price
        $this->form_validation->set_rules(
            'price', 'price','trim|required|min_length[1]|max_length[100]',
            array(
                'required'  => '金額未輸入',
                'min_length' => '金額輸入錯誤',
                'max_length' => '金額輸入長度超過'
            )
        );
        if ($this->form_validation->run() === FALSE){
            $msg_array['msg'] = trim(validation_errors());
            $msg_array['code'] = '3';
            $berror = TRUE;
        }
        
        //資料庫
        if (!$berror) {
            $main_id = $post_data['main_id'];
            $category_title = $post_data['category_title'];
            $old_price = $post_data['old_price'];
            $price = $post_data['price'];
            $res = $this->Combowe_model->add_category($main_id,$category_title,$old_price,$price);
            if ($res === TRUE) {
                $msg_array['msg'] = '修改成功';
                $msg_array['code'] = '0';
            }else {
                $msg_array['msg'] = '修改失敗';
                $msg_array['code'] = '4';
            }
        }
        
        $msg = json_encode($msg_array,320);
        echo $msg;
    }
    //編輯項目的分類表單
    public function ajax_edit_category()
    {
        //限定POST,防機器人,來源
        if ($this->input->server('REQUEST_METHOD') != 'POST' || $this->agent->is_robot() || $this->agent->is_referral()) {
            show_error('Access forbidden', 403);
            exit;
        }

        $berror = FALSE;
        $msg_array = array("msg" => '修改失敗',"code" => 1);

        //XSS過濾
        $post_data =$this->security->xss_clean($this->input->post());
        if (empty($this->security->xss_clean($post_data))) {
            $msg_array['msg'] = '非法提交參數';
            $msg_array['code'] = '2';
            $berror = TRUE;
        }
    
        //驗證
        //category_title
        $this->form_validation->set_rules(
            'category_title', 'category_title','trim|required|min_length[1]|max_length[255]',
            array(
                'required'  => '名稱未輸入',
                'min_length' => '名稱輸入錯誤',
                'max_length' => '名稱輸入長度超過'
            )
        );
        //old_price
        $this->form_validation->set_rules(
            'old_price', 'old_price','trim|required|min_length[1]|max_length[100]',
            array(
                'required'  => '舊金額未輸入',
                'min_length' => '舊金額輸入錯誤',
                'max_length' => '舊金額輸入長度超過'
            )
        );
        //price
        $this->form_validation->set_rules(
            'price', 'price','trim|required|min_length[1]|max_length[100]',
            array(
                'required'  => '金額未輸入',
                'min_length' => '金額輸入錯誤',
                'max_length' => '金額輸入長度超過'
            )
        );
        //id
		$this->form_validation->set_rules(
            'id', 'id','trim|required|greater_than_equal_to[1]|less_than_equal_to[999999999]',
            array(
                'required'  => '非法提交參數',
                'greater_than_equal_to' => '非法提交參數',
                'less_than_equal_to' => '非法提交參數'
            )
		);
        if ($this->form_validation->run() === FALSE){
            $msg_array['msg'] = trim(validation_errors());
            $msg_array['code'] = '3';
            $berror = TRUE;
        }
        
        //資料庫
        if (!$berror) {
            $id = $post_data['id'];
            $category_title = $post_data['category_title'];
            $old_price = $post_data['old_price'];
            $price = $post_data['price'];
            $res = $this->Combowe_model->edit_category($id,$category_title,$old_price,$price);
            if ($res === TRUE) {
                $msg_array['msg'] = '修改成功';
                $msg_array['code'] = '0';
            }else {
                $msg_array['msg'] = '修改失敗';
                $msg_array['code'] = '4';
            }
        }
        
        $msg = json_encode($msg_array,320);
        echo $msg;
    }
    //刪除項目的分類表單
    public function ajax_del_category()
    {
        //限定POST,防機器人,來源
        if ($this->input->server('REQUEST_METHOD') != 'POST' || $this->agent->is_robot() || $this->agent->is_referral()) {
            show_error('Access forbidden', 403);
            exit;
        }

        $berror = FALSE;
        $msg_array = array("msg" => '刪除失敗',"code" => 1);

        //XSS過濾
        $post_data =$this->security->xss_clean($this->input->post());
        if (empty($this->security->xss_clean($post_data))) {
            $msg_array['msg'] = '非法提交參數';
            $msg_array['code'] = '2';
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
        if ($this->form_validation->run() === FALSE){
            $msg_array['msg'] = trim(validation_errors());
            $msg_array['code'] = '3';
            $berror = TRUE;
        }
        
        //資料庫
        if (!$berror) {
            $id = $post_data['id'];
            $res = $this->Combowe_model->del_category($id);
            if ($res === TRUE) {
                $msg_array['msg'] = '刪除成功';
                $msg_array['code'] = '0';
            }else {
                $msg_array['msg'] = '刪除失敗';
                $msg_array['code'] = '4';
            }
        }
        
        $msg = json_encode($msg_array,320);
        echo $msg;
    }
    //包套項目的分類順序變更
    public function ajax_edit_category_seq()
    {
        //限定POST,防機器人,來源
		if ($this->input->server('REQUEST_METHOD') != 'POST' || $this->agent->is_robot() || $this->agent->is_referral()) {
			show_error('Access forbidden', 403);
			exit;
        }
        
        $berror = FALSE;
        $msg = "調整順序失敗";
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
            $res = $this->Combowe_model->edit_category_seq($sort_array);
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
    //包套項目分類下詳細項目
    public function combowe_category_view($id = NULL)
    {
        $data = array();
        $query = array();
        $data['title'] = "錯誤";
        $data['main_id'] = NULL;

        if (intval($id) > 0) {
            $category_id = intval($id);
        }

        //包套裡分類
        if (intval($category_id) > 0) {
            $query = $this->Combowe_model->get_combowe_category_name($category_id);
            //類別資料
            if (!empty($query)) {
                $data['title'] = '包套內容 -' . $query['main_title'] .' - '. $query['title'];
                $data['category_name'] = $query['title'];
                $data['main_id'] = $query['main_id'];
                $data['category_id'] = $category_id;
            }
            //文字內容資料
            $query = $this->Combowe_model->get_combowe_category_text($category_id);
            if (!empty($query)) {
                $data['combowe'] = $query;
            }
        }

        $this->load->view('templates/head',$data);
        $this->load->view('templates/header');
        $this->load->view('templates/title');
        $this->load->view('combowe/combowe_category_view');
        $this->load->view('templates/footer');
        $this->load->view('combowe/combowe_category_view_app');
    }
    //新增分類下的詳細項目
    public function ajax_add_text()
    {
        //限定POST,防機器人,來源
        if ($this->input->server('REQUEST_METHOD') != 'POST' || $this->agent->is_robot() || $this->agent->is_referral()) {
            show_error('Access forbidden', 403);
            exit;
        }

        $berror = FALSE;
        $msg_array = array("msg" => '新增失敗',"code" => 1);

        //XSS過濾
        $post_data =$this->security->xss_clean($this->input->post());
        if (empty($this->security->xss_clean($post_data))) {
            $msg_array['msg'] = '非法提交參數';
            $msg_array['code'] = '2';
            $berror = TRUE;
        }
    
        //驗證
        //category_id
		$this->form_validation->set_rules(
            'category_id', 'category_id','trim|required|greater_than_equal_to[1]|less_than_equal_to[999999999]',
            array(
                'required'  => '非法提交參數',
                'greater_than_equal_to' => '非法提交參數',
                'less_than_equal_to' => '非法提交參數'
            )
		);
        //add_content
        $this->form_validation->set_rules(
            'content', 'content','trim|required|min_length[1]',
            array(
                'required'  => '內容未輸入',
                'min_length' => '內容輸入錯誤',
            )
        );
        if ($this->form_validation->run() === FALSE){
            $msg_array['msg'] = trim(validation_errors());
            $msg_array['code'] = '3';
            $berror = TRUE;
        }
        
        //資料庫
        if (!$berror) {
            $category_id = $post_data['category_id'];
            $content = $_POST['content'];
            $res = $this->Combowe_model->add_text($category_id,$content);
            if ($res === TRUE) {
                $msg_array['msg'] = '新增成功';
                $msg_array['code'] = '0';
            }else {
                $msg_array['msg'] = '新增失敗';
                $msg_array['code'] = '4';
            }
        }
        
        $msg = json_encode($msg_array,320);
        echo $msg;
    }
    //編輯分類下的詳細項目
    public function ajax_edit_text()
    {
        //限定POST,防機器人,來源
        if ($this->input->server('REQUEST_METHOD') != 'POST' || $this->agent->is_robot() || $this->agent->is_referral()) {
            show_error('Access forbidden', 403);
            exit;
        }

        $berror = FALSE;
        $msg_array = array("msg" => '修改失敗',"code" => 1);

        //XSS過濾
        $post_data =$this->security->xss_clean($this->input->post());
        if (empty($this->security->xss_clean($post_data))) {
            $msg_array['msg'] = '非法提交參數';
            $msg_array['code'] = '2';
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
        //content
        $this->form_validation->set_rules(
            'content', 'content','trim|required|min_length[1]',
            array(
                'required'  => '內容未輸入',
                'min_length' => '內容輸入錯誤',
            )
        );
        if ($this->form_validation->run() === FALSE){
            $msg_array['msg'] = trim(validation_errors());
            $msg_array['code'] = '3';
            $berror = TRUE;
        }
        
        //資料庫
        if (!$berror) {
            $id = $post_data['id'];
            $content = $_POST['content'];
            $res = $this->Combowe_model->edit_text($id,$content);
            if ($res === TRUE) {
                $msg_array['msg'] = '修改成功';
                $msg_array['code'] = '0';
            }else {
                $msg_array['msg'] = '修改失敗';
                $msg_array['code'] = '4';
            }
        }
        
        $msg = json_encode($msg_array,320);
        echo $msg;
    }
    //刪除文字
    public function ajax_del_text()
    {
        //限定POST,防機器人,來源
        if ($this->input->server('REQUEST_METHOD') != 'POST' || $this->agent->is_robot() || $this->agent->is_referral()) {
            show_error('Access forbidden', 403);
            exit;
        }

        $berror = FALSE;
        $msg_array = array("msg" => '刪除失敗',"code" => 1);

        //XSS過濾
        $post_data =$this->security->xss_clean($this->input->post());
        if (empty($this->security->xss_clean($post_data))) {
            $msg_array['msg'] = '非法提交參數';
            $msg_array['code'] = '2';
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
        if ($this->form_validation->run() === FALSE){
            $msg_array['msg'] = trim(validation_errors());
            $msg_array['code'] = '3';
            $berror = TRUE;
        }
        
        //資料庫
        if (!$berror) {
            $id = $post_data['id'];
            $res = $this->Combowe_model->del_text($id);
            if ($res === TRUE) {
                $msg_array['msg'] = '刪除成功';
                $msg_array['code'] = '0';
            }else {
                $msg_array['msg'] = '刪除失敗';
                $msg_array['code'] = '4';
            }
        }
        
        $msg = json_encode($msg_array,320);
        echo $msg;
    }
    //包套項目的分類順序變更
    public function ajax_edit_text_seq()
    {
        //限定POST,防機器人,來源
		if ($this->input->server('REQUEST_METHOD') != 'POST' || $this->agent->is_robot() || $this->agent->is_referral()) {
			show_error('Access forbidden', 403);
			exit;
        }
        
        $berror = FALSE;
        $msg = "調整順序失敗";
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
            $res = $this->Combowe_model->edit_text_seq($sort_array);
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
}
/* End of file Combowe.php */