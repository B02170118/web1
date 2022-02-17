<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Staff extends CI_Controller
{
	public function __construct() 
	{
		parent::__construct();

		$this->load->library(array('test1111_lib','form_validation','user_agent'));
        $this->load->helper(array('url'));
		$this->load->model(array('Staff_model'));

		//選單
		// $this->HeaderMenu = $this->test1111_lib->get_menu();
		
		//登入判斷
		$this->test1111_lib->check_login();
	}
	
	public function index()
	{	
		$data = array();
		$query = array();

        $data['title'] = "關於我們管理";

		//data
        $query = $this->Staff_model->get_all_data();
        if (!empty($query)) {
            $data['staff'] = $query;
        }

		$this->load->view('templates/head',$data);
		$this->load->view('templates/header');
		$this->load->view('templates/title');
		$this->load->view('staff/index');
		$this->load->view('templates/footer');
		$this->load->view('staff/app');
    }
    //新增
    public function add_staff()
    {
		$data = array();
		$query = array();

        $data['title'] = "關於我們管理 - 新增";

		$this->load->view('templates/head',$data);
		$this->load->view('templates/header');
		$this->load->view('templates/title');
		$this->load->view('staff/add_staff');
		$this->load->view('templates/footer');
		$this->load->view('staff/add_staff_app');
    }
    //新增表單
    public function form_add_staff()
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
        if (!$berror) {
            //title
            $this->form_validation->set_rules(
                'title', 'title','trim|required|max_length[100]',
                array(
                    'required'  => '非法提交參數',
                    'max_length' => '標題長度錯誤'
                )
            );
            //subtitle
            $this->form_validation->set_rules(
                'subtitle', 'subtitle','trim|required|max_length[100]',
                array(
                    'required'  => '非法提交參數',
                    'max_length' => '副標題長度錯誤'
                )
            );
            //content
            $this->form_validation->set_rules(
                'content', 'content','trim|required|max_length[500]',
                array(
                    'required'  => '非法提交參數',
                    'max_length' => '文字長度錯誤'
                )
            );
            //href
            $this->form_validation->set_rules(
                'href', 'href','trim|max_length[1000]',
                array(
                    'max_length' => '連結長度錯誤'
                )
            );

            if ($this->form_validation->run() === FALSE){
                $msg = trim(validation_errors());
                $berror = TRUE;
            }
        }
        
        
        //上傳
        if (!$berror) {
            $type = $post_data['type'];
            $file = $_FILES['userfile'];
            foreach ($file as $key => $row) {
                foreach ($row as $key2 => $val) { //刪除第二張空的圖的資料
                    if (empty($file['name'][$key2])) {
                        unset($file['name'][$key2]);
                        unset($file['type'][$key2]);
                        unset($file['ntmp_nameame'][$key2]);
                        unset($file['error'][$key2]);
                        unset($file['size'][$key2]);
                    }
                }
            }
            $res = $this->test1111_lib->upload_img($file,$type);
            if (empty($res)) {
                $msg = "系統錯誤";
                $berror = TRUE;
            }
        }

        //update資料庫
        if (!$berror) {
            $title = $post_data['title'];
            $subtitle = $post_data['subtitle'];
            $content = $post_data['content'];
            $href = $_POST['href'];
            $i = 0;
            foreach ($res as $row) {
                if ($row['code'] == 0) {
                    $dir[$i] = trim($row['dir']);
                    $dir1 = !empty($dir[0]) ? $dir[0] : NULL;
                    $dir2 = !empty($dir[1]) ? $dir[1] : NULL;
                    $msg .= $row['msg'];
                    $msg .= $end_sign;
                }else{
                    $msg .= $row['msg'];
                    $msg .= $end_sign;
                }
                $i++;;
            }
            if (!empty($dir1)) {
                $update_res = $this->Staff_model->add_staff($title,$subtitle,$dir1,$dir2,$content,$href);
                if ($update_res === FALSE) {
                    $msg .= "圖片新增失敗 圖片1: $dir1, 圖片2: $dir2";
                    $msg .= $end_sign;

                    //刪除圖片
                    if (!empty($dir1)) {
                        $delete_dir = WWW_test1111_COM_IMG_LOCATION.$dir1;
                        @unlink($delete_dir);
                    }
                    if (!empty($dir2)) {
                        $delete_dir = WWW_test1111_COM_IMG_LOCATION.$dir2;
                        @unlink($delete_dir);
                    }
                }else{
                    $msg .= " 資料新增成功";
                    $msg .= $end_sign;
                }
            }
        }

        //html
        $data = array();

        $data['title'] = "結果";
        $data['msg'] = $msg;
        $data['url'] = 'staff';

        $this->load->view('templates/head',$data);
		$this->load->view('templates/header');
		$this->load->view('templates/title');
		$this->load->view('templates/result');
		$this->load->view('templates/footer');
    }
    //刪除
    public function ajax_del_staff()
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
            $row = $this->Staff_model->get_img($id);
            if (!empty($row)) {
                $img = $row['img'];
                $img2 = $row['img2'];
                $id = $row['id'];
                $res = $this->Staff_model->del_staff($id);
                //刪資料庫
                if ($res === FALSE) {
                    $msg .= "刪除資料失敗 ID:$id,";
                    $code = 4;
                    $berror = TRUE;
                }
                //刪檔案
                if (!empty($img)) {
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
                if (!empty($img2)) {
                    $delete_dir = WWW_test1111_COM_IMG_LOCATION.$img2;
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
                $msg .= "刪除失敗,找不到資料";
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
    //順序變更
    public function ajax_edit_staff_seq()
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
            $res = $this->Staff_model->edit_staff_seq($sort_array);
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
    //編輯頁
    public function view($id)
    {
        $data = array();
		$query = array();

        $data['title'] = "關於我們管理-";
        $data['url'] = $this->uri->uri_string();
        
        //data
        if (intval($id)>0) {
            $query = $this->Staff_model->get_staff_view($id);
            if (!empty($query)) {
                $data['staff'] = $query;
                $data['title'] .= $query['title'];
            }
        }

		$this->load->view('templates/head',$data);
		$this->load->view('templates/header');
		$this->load->view('templates/title');
		$this->load->view('staff/view');
		$this->load->view('templates/footer');
		$this->load->view('staff/staff_view_app');
    }
    //編輯表單
    public function form_edit_staff()
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
            //title
            $this->form_validation->set_rules(
                'title', 'title','trim|required|max_length[100]',
                array(
                    'required'  => '非法提交參數',
                    'max_length' => '標題長度錯誤'
                )
            );
            //subtitle
            $this->form_validation->set_rules(
                'subtitle', 'subtitle','trim|required|max_length[100]',
                array(
                    'required'  => '非法提交參數',
                    'max_length' => '副標題長度錯誤'
                )
            );
            //content
            $this->form_validation->set_rules(
                'content', 'content','trim|required|max_length[500]',
                array(
                    'required'  => '非法提交參數',
                    'max_length' => '文字長度錯誤'
                )
            );
            //href
            $this->form_validation->set_rules(
                'href', 'href','trim|max_length[1000]',
                array(
                    'max_length' => '連結長度錯誤'
                )
            );

            if ($this->form_validation->run() === FALSE){
                $msg = trim(validation_errors());
                $berror = TRUE;
            }
        }
        
        //上傳
        if (!$berror) {
            $type = $post_data['type'];
            $id = $post_data['id'];
            $title = $post_data['title'];
            $subtitle = $post_data['subtitle'];
            $content = $post_data['content'];
            $href = $_POST['href'];
            if (empty($_FILES['userfile']['name'][0]) && empty($_FILES['userfile2']['name'][0])) { //只更新資料庫
                $update_res = $this->Staff_model->edit_staff($id,$title,$subtitle,NULL,NULL,$content,$href);
                if ($update_res === FALSE) {
                    $msg .= "更新失敗";
                }else{
                    $msg .= "更新成功";
                }
            }else {
                //上傳圖
                if (!empty($_FILES['userfile']['name'][0])) {
                    $file = $_FILES['userfile'];
                    $res = $this->test1111_lib->upload_img($file,$type);
                    if (empty($res)) {
                        $msg = "系統錯誤";
                        $berror = TRUE;
                    }
                }
                if (!empty($_FILES['userfile2']['name'][0])) {
                    $file = $_FILES['userfile2'];
                    $res2 = $this->test1111_lib->upload_img($file,$type);
                    if (empty($res2)) {
                        $msg = "系統錯誤";
                        $berror = TRUE;
                    }
                }
                //上傳圖
                //update資料庫
                if (!$berror) {
                    //找原圖
                    $query = $this->Staff_model->get_img($id);
                    $original_img = !empty($query) ? $query['img'] : NULL;
                    $original_img2 = !empty($query) ? $query['img2'] : NULL;
                    if (!empty($_FILES['userfile']['name'][0])) {
                        foreach ($res as $row) {
                            if ($row['code'] == 0) {
                                $dir = trim($row['dir']);
                            }else{
                                $msg .= $row['msg'];
                            }
                            $msg .= $end_sign;
                        }
                    }
                    if (!empty($_FILES['userfile2']['name'][0])) {
                        foreach ($res2 as $row) {
                            if ($row['code'] == 0) {
                                $dir2 = trim($row['dir']);
                            }else{
                                $msg .= $row['msg'];
                            }
                            $msg .= $end_sign;
                        }
                    }
                    if (!empty($dir) && empty($dir2)) { //上傳第一張
                        $update_res = $this->Staff_model->edit_staff($id,$title,$subtitle,$dir,NULL,$content,$href,2);
                    }elseif (empty($dir) && !empty($dir2)) {  //上傳第二張
                        $update_res = $this->Staff_model->edit_staff($id,$title,$subtitle,NULL,$dir2,$content,$href,3);
                    }else { //上傳兩張
                        $update_res = $this->Staff_model->edit_staff($id,$title,$subtitle,$dir,$dir2,$content,$href,1);
                    }
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
                        if (!empty($original_img) && !empty($dir)) {
                            $delete_dir = WWW_test1111_COM_IMG_LOCATION.$original_img;
                            @unlink($delete_dir);
                        }
                        //刪除原圖2
                        if (!empty($original_img2) && !empty($dir2)) {
                            $delete_dir = WWW_test1111_COM_IMG_LOCATION.$original_img2;
                            @unlink($delete_dir);
                        }
                    }
                }
            }
        }

        //html
        $data = array();

        $data['title'] = "結果";
        $data['msg'] = $msg;
        $data['url'] = 'staff';

        $this->load->view('templates/head',$data);
		$this->load->view('templates/header');
		$this->load->view('templates/title');
		$this->load->view('templates/result');
		$this->load->view('templates/footer');
    }
}
/* End of file Staff.php */