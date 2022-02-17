<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Blog extends CI_Controller
{
	public function __construct() 
	{
		parent::__construct();

		$this->load->library(array('test1111_lib','form_validation','user_agent'));
        $this->load->helper(array('url'));
		$this->load->model(array('Blog_model'));

		//選單
		// $this->HeaderMenu = $this->test1111_lib->get_menu();
		
		//登入判斷
		$this->test1111_lib->check_login();
	}
	
	public function index()
	{	
		$data = array();
		$query = array();

        $data['title'] = "Blog管理";

		//data
        $query = $this->Blog_model->get_all_data();
        if (!empty($query)) {
            $data['blog'] = $query;
        }

		$this->load->view('templates/head',$data);
		$this->load->view('templates/header');
		$this->load->view('templates/title');
		$this->load->view('blog/index');
		$this->load->view('templates/footer');
		$this->load->view('blog/app');
    }
    //新增文章
    public function add_blog()
    {
		$data = array();
		$query = array();

        $data['title'] = "Blog管理 - 新增文章";

        //data
        $query = $this->Blog_model->get_next_id();
        if (!empty($query)) {
            $data['next_id'] = $query['AUTO_INCREMENT'];
        }

		$this->load->view('templates/head',$data);
		$this->load->view('templates/header');
		$this->load->view('templates/title');
		$this->load->view('blog/add_blog');
		$this->load->view('templates/footer');
		$this->load->view('blog/add_blog_app');
    }
    //新增文章表單
    public function form_add_blog()
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
            'title', 'title','trim|required|min_length[0]|max_length[255]',
            array(
                'required'  => '標題未輸入',
                'min_length' => '標題輸入錯誤',
                'max_length' => '標題輸入長度超過'
            )
        );
        //date
        $this->form_validation->set_rules(
            'date', 'date','trim|required|min_length[0]|max_length[255]',
            array(
                'required'  => '日期未輸入',
                'min_length' => '日期輸入錯誤',
                'max_length' => '日期輸入錯誤'
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
        //status
        $this->form_validation->set_rules(
            'status', 'status','trim|required|greater_than_equal_to[0]|less_than_equal_to[1]',
            array(
                'required'  => '非法提交參數',
                'greater_than_equal_to' => '非法提交參數',
                'less_than_equal_to' => '非法提交參數'
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
            $title = $post_data['title'];
            $status = $post_data['status'];
            $date = $post_data['date'];
            $content = $_POST['content'];
            foreach ($res as $row) {
                if ($row['code'] == 0) {
                    $dir = trim($row['dir']);
                    $update_res = $this->Blog_model->add_blog($dir,$title,$status,$date,$content);
                    if ($update_res === FALSE) {
                        $msg .= "新增文章失敗";
                        //刪除圖片
                        if (!empty($dir)) {
                            $delete_dir = WWW_test1111_COM_IMG_LOCATION.$dir;
                            @unlink($delete_dir);
                        }
                    }else{
                        $msg .= $row['msg'].", 新增文章成功";
                    }
                }else{
                    $msg .= $row['msg'];
                }
                $msg .= $end_sign;
            }
        }

        //html
        $data = array();

        $data['title'] = "結果";
        $data['msg'] = $msg;
        $data['url'] = 'blog';

        $this->load->view('templates/head',$data);
		$this->load->view('templates/header');
		$this->load->view('templates/title');
		$this->load->view('templates/result');
		$this->load->view('templates/footer');
    }
    //刪除文章
    public function ajax_del_blog()
    {
        //限定POST,防機器人,來源
		if ($this->input->server('REQUEST_METHOD') != 'POST' || $this->agent->is_robot() || $this->agent->is_referral()) {
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
        if (!$berror) {
             //id
            $this->form_validation->set_rules(
                'id', 'id','trim|required|greater_than_equal_to[1]|less_than_equal_to[999999999]',
                array(
                    'required'  => '非法提交參數',
                    'greater_than_equal_to' => '非法提交參數',
                    'less_than_equal_to' => '非法提交參數'
                )
            );

            //驗證數據
            if ($this->form_validation->run() === FALSE){
                $text = trim(validation_errors());
                $code = 2;
                $berror = TRUE;
            }
        }
       
        //刪除
        if (!$berror) {
            $id = $post_data['id'];
            $row = $this->Blog_model->get_img($id);
            if (!empty($row)) {
                $img = $row['img'];
                $id = $row['id'];
                $res = $this->Blog_model->del_blog($id);
                //刪資料庫
                if ($res === FALSE) {
                    $msg .= "刪除資料失敗 ID:$id,";
                    $code = 4;
                    $berror = TRUE;
                }
                //刪檔案(縮圖)
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
                //文章圖
                $delete_dir = WWW_test1111_COM_IMG_LOCATION.UPLOAD_DIR_BLOG.$id."/";
                $res = $this->test1111_lib->deldir($delete_dir);
                if ($res['code'] != 0) {
                    $msg .= $res['msg'];
                    $code = 6;
                    $berror = TRUE;
                }
            }else {
                $msg = "刪除失敗,找不到資料";
                $code = 3;
                $berror = TRUE;
            }
        }
        //都沒問題
        if (!$berror) {
            $msg = "刪除成功";
            $code = 0;
        }

        $msg_array = array("msg" => $msg,"code" => $code);
        $msg = json_encode($msg_array,320);
        echo $msg;
    }
    //文章順序變更
    public function ajax_edit_blog_seq()
    {
        //限定POST,防機器人,來源
		if ($this->input->server('REQUEST_METHOD') != 'POST' || $this->agent->is_robot() || $this->agent->is_referral()) {
			show_error('Access forbidden', 403);
			exit;
        }
        
        $berror = FALSE;
        $msg = "調整失敗";
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
            $res = $this->Blog_model->edit_blog_seq($sort_array);
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
    //文章編輯頁
    public function view($id)
    {
        $data = array();
		$query = array();

        $data['title'] = "Blog管理-";
        $data['url'] = $this->uri->uri_string();
        
        //data
        if (intval($id)>0) {
            $query = $this->Blog_model->get_blog_view($id);
            if (!empty($query)) {
                $data['blog'] = $query;
                $data['title'] .= $query['title'];
            }
        }

		$this->load->view('templates/head',$data);
		$this->load->view('templates/header');
		$this->load->view('templates/title');
		$this->load->view('blog/view');
		$this->load->view('templates/footer');
		$this->load->view('blog/blog_view_app');
    }
    //編輯文章
    public function form_edit_blog()
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
        //url
        $this->form_validation->set_rules(
            'url', 'url','trim|required|min_length[0]|max_length[10000]',
            array(
                'required'  => '非法提交參數',
                'min_length' => '非法提交參數',
                'max_length' => '非法提交參數'
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
        //title
        $this->form_validation->set_rules(
            'title', 'title','trim|required|min_length[0]|max_length[255]',
            array(
                'required'  => '標題未輸入',
                'min_length' => '標題輸入錯誤',
                'max_length' => '標題輸入長度超過'
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
        //date
        $this->form_validation->set_rules(
            'date', 'date','trim|required|min_length[0]|max_length[255]',
            array(
                'required'  => '日期未輸入',
                'min_length' => '日期輸入錯誤',
                'max_length' => '日期輸入錯誤'
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

        if ($this->form_validation->run() === FALSE){
            $msg = trim(validation_errors());
            $berror = TRUE;
        }
        
        $id = $post_data['id'];
        $title = $post_data['title'];
        $date = $post_data['date'];
        $status = $post_data['status'];
        $content = $_POST['content'];
        
        if (empty($_FILES['userfile']['name'][0])) { //只更新資料
            $update_res = $this->Blog_model->edit_blog($id,$title,$date,$status,$content);
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
                    $msg .= $row['msg'];
                    if ($row['code'] == 0) {
                        $dir = trim($row['dir']);
                        //找原圖
                        $query = $this->Blog_model->get_img($id);
                        $original_img = !empty($query) ? $query['img'] : NULL;
                        $update_res = $this->Blog_model->edit_blog($id,$title,$date,$status,$content,$dir,1);
                        if ($update_res === FALSE) {
                            $msg .= ", 更新文章失敗";
                            //刪除圖片
                            if (!empty($dir)) {
                                $delete_dir = WWW_test1111_COM_IMG_LOCATION.$dir;
                                @unlink($delete_dir);
                            }
                        }else{
                            $msg .= ", 更新文章成功";
                            //刪除原圖
                            if (!empty($original_img)) {
                                $delete_dir = WWW_test1111_COM_IMG_LOCATION.$original_img;
                                @unlink($delete_dir);
                            }
                        }
                    }else{
                        $msg .= ", 更新文章失敗";
                    }
                    $msg .= $end_sign;
                }
            }
        }

        //html
        $data = array();

        $data['title'] = "結果";
        $data['msg'] = $msg;
        $data['url'] = $post_data['url'];

        $this->load->view('templates/head',$data);
		$this->load->view('templates/header');
		$this->load->view('templates/title');
		$this->load->view('templates/result');
		$this->load->view('templates/footer');
    }
    //上傳相片(編輯器內建)
    public function content_upload_img()
    {
		//限定POST,防機器人,來源
		if ($this->input->server('REQUEST_METHOD') != 'POST' || $this->agent->is_robot() || $this->agent->is_referral()) {
			show_error('Access forbidden', 403);
			exit;
		}

        $berror = FALSE;
        $msg = "";
        $dir = "";

		//XSS過濾
		$get_data = $this->security->xss_clean($this->input->get());
        if (empty($this->security->xss_clean($get_data))) {
            $msg ='非法提交參數';
            $berror = TRUE;
        }
       
        //驗證
        if (!$berror) {
            $id = $get_data['id'];
            if(is_numeric($id) === FALSE){
                $berror = TRUE;
                $msg = "非法提交參數";
            }
        }
        //上傳
        if (!$berror) {
            $type = $get_data['type'];
            $file = $_FILES['upload'];
            $res = $this->test1111_lib->upload_img($file,$type,$id);
            if (empty($res)) {
                $msg = "系統錯誤";
                $berror = TRUE;
            }
        }
        
        //返回
        if (!$berror) {
            $msg = $res['msg'];
            $filename = $res['filename'];
            $dir = WWW_test1111_COM.$res['dir'];
        }
        $data = array("uploaded" => 1,"fileName" => $msg,"url" => $dir,"error" => array("message" => $msg));
        $json = json_encode($data,320);
        echo $json;
    }
}
/* End of file Blog.php */