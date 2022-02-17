<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Qa extends CI_Controller
{
	public function __construct() 
	{
		parent::__construct();

		$this->load->library(array('test1111_lib','form_validation','user_agent','upload'));
        $this->load->helper(array('url','file'));
		$this->load->model(array('Qa_model'));

		//選單
		// $this->HeaderMenu = $this->test1111_lib->get_menu();
		
		//登入判斷
		$this->test1111_lib->check_login();
	}
	
	public function index()
	{	
		$data = array();
		$query = array();

        $data['title'] = "FAQ管理";

        //相簿data
        $query = $this->Qa_model->get_all_qa();
        if (!empty($query)) {
            $data['qa'] = $query;
        }

		$this->load->view('templates/head',$data);
		$this->load->view('templates/header');
		$this->load->view('templates/title');
		$this->load->view('qa/index');
		$this->load->view('templates/footer');
		$this->load->view('qa/app');
    }
    //分類順序變更
    public function ajax_edit_category_seq()
    {
        //限定POST,防機器人,來源
        if ($this->input->server('REQUEST_METHOD') != 'POST' || $this->agent->is_robot() || $this->agent->is_referral()) {
            show_error('Access forbidden', 403);
            exit;
        }

        $msg = '調整順序失敗';
        $code = 1;
        $berror = FALSE;

        //XSS過濾
        $post_data = $this->security->xss_clean($this->input->post());
        if (empty($this->security->xss_clean($post_data))) {
            $msg ='非法提交參數';
            $berror = TRUE;
        }

        //驗證
        if (!$berror) {
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
        }

        //排序
        if (!$berror) {
            $res = $this->Qa_model->edit_category_seq($sort_array);
            if ($res === FALSE) {
                $msg = "調整順序失敗";
                $code = 3;
            }else {
                $msg = "調整順序成功";
                $code = 0;
            }
        }

        $msg_array = array(
            "msg" => $msg,
            "code" => $code
        );
        $msg = json_encode($msg_array,320);
        echo $msg;
    }
    //編輯分類
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

        //驗證數據
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
            //name
            $this->form_validation->set_rules(
                'name', 'name','trim|required|min_length[1]|max_length[255]',
                array(
                    'required'  => '名稱未輸入',
                    'min_length' => '名稱輸入錯誤',
                    'max_length' => '名稱輸入長度超過'
                )
            );
            if ($this->form_validation->run() === FALSE){
                $msg_array['msg'] = trim(validation_errors());
                $msg_array['code'] = '3';
                $berror = TRUE;
            }
        }

        //更新
        if (!$berror) {
            $id = $post_data['id'];
            $name = $post_data['name'];
            $res = $this->Qa_model->edit_category($id,$name);
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
    //新增分類
    public function ajax_add_category()
    {
        //限定POST,防機器人,來源
        if ($this->input->server('REQUEST_METHOD') != 'POST' || $this->agent->is_robot() || $this->agent->is_referral()) {
            show_error('Access forbidden', 403);
            exit;
        }

        $berror = FALSE;
        $msg = "新增失敗";
        $code = 1;

        //XSS過濾
        $post_data =$this->security->xss_clean($this->input->post());
        if (empty($this->security->xss_clean($post_data))) {
            $msg ='非法提交參數';
            $berror = TRUE;
        }
        
        //驗證
        //name
        $this->form_validation->set_rules(
            'name', 'name','trim|required|min_length[1]|max_length[255]',
            array(
                'required'  => '名稱未輸入',
                'min_length' => '名稱輸入錯誤',
                'max_length' => '名稱輸入長度超過'
            )
        );

        if ($this->form_validation->run() === FALSE){
            $msg = trim(validation_errors());
            $berror = TRUE;
        }
        
        //資料庫
        if (!$berror) {
            $name = $post_data['name'];
            $res = $this->Qa_model->add_category($name);
            if ($res === FALSE) {
                $msg = "新增失敗";
                $code = 3;
                $berror = TRUE;
            }else {
                $msg = "新增成功";
                $code = 0;
            }
        }
        $msg_array = array(
            "msg" => $msg,
            "code" => $code
        );
        $msg = json_encode($msg_array,320);
        echo $msg;
    }
    //刪除分類
    public function ajax_del_category()
    {
        //限定POST,防機器人,來源
        if ($this->input->server('REQUEST_METHOD') != 'POST' || $this->agent->is_robot() || $this->agent->is_referral()) {
            show_error('Access forbidden', 403);
            exit;
        }

        $berror = FALSE;
        $code = 1;
        $msg = "刪除失敗";

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
        
        //資料庫
        if (!$berror) {
            $id = $post_data['id'];
            $res = $this->Qa_model->del_category($id);
            if ($res === FALSE) {
                $msg = "刪除失敗";
                $code = 3;
                $berror = TRUE;
            }else {
                $msg = "刪除成功";
                $code = 0;
            }
        }

        $msg_array = array(
            "msg" => $msg,
            "code" => $code
        );
        $msg = json_encode($msg_array,320);
        echo $msg;
    }
    //分類裡面的相簿
    public function view($id = NULL)
    {
        $data = array();
        $query = array();
        $title = "FAQ管理-";
        $data['category_name'] = "";

        //分類裡面的相簿
        if (intval($id) > 0) {
            $cid = intval($id);
            $query = $this->Qa_model->qa_view($cid);
            if (!empty($query)) {
                $data['category_name'] = $query['category']['name'];
                $data['cid'] = $query['category']['id'];
                $title = 'FAQ管理-'.$query['category']['name'];
                $data['qa'] = $query['text'];
            }
        }
        
        $data['title'] = $title;

        $this->load->view('templates/head',$data);
        $this->load->view('templates/header');
        $this->load->view('templates/title');
        $this->load->view('qa/view');
        $this->load->view('templates/footer');
        $this->load->view('qa/view_app');
    }
    //編輯文字
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

        //驗證數據
        if (!$berror) {
            //cid
            $this->form_validation->set_rules(
                'cid', 'cid','trim|required|greater_than_equal_to[1]|less_than_equal_to[999999999]',
                array(
                    'required'  => '非法提交參數',
                    'greater_than_equal_to' => '非法提交參數',
                    'less_than_equal_to' => '非法提交參數'
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
            //content
            $this->form_validation->set_rules(
                'content', 'content','trim|required|min_length[1]',
                // |max_length[255]',
                array(
                    'required'  => '內容未輸入',
                    'min_length' => '內容輸入錯誤',
                    // 'max_length' => '內容輸入長度超過'
                )
            );
            if ($this->form_validation->run() === FALSE){
                $msg_array['msg'] = trim(validation_errors());
                $msg_array['code'] = '3';
                $berror = TRUE;
            }
        }

        //更新
        if (!$berror) {
            $cid = $post_data['cid'];
            $id = $post_data['id'];
            $content = $_POST['content'];
            $res = $this->Qa_model->edit_text($cid,$id,$content);
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
    //新增文字
    public function ajax_add_text()
    {
        //限定POST,防機器人,來源
        if ($this->input->server('REQUEST_METHOD') != 'POST' || $this->agent->is_robot() || $this->agent->is_referral()) {
            show_error('Access forbidden', 403);
            exit;
        }

        $berror = FALSE;
        $msg = "新增失敗";
        $code = 1;

        //XSS過濾
        $post_data =$this->security->xss_clean($this->input->post());
        if (empty($this->security->xss_clean($post_data))) {
            $msg ='非法提交參數';
            $berror = TRUE;
        }
        
        //驗證數據
        if (!$berror) {
            //cid
            $this->form_validation->set_rules(
                'cid', 'cid','trim|required|greater_than_equal_to[1]|less_than_equal_to[999999999]',
                array(
                    'required'  => '非法提交參數',
                    'greater_than_equal_to' => '非法提交參數',
                    'less_than_equal_to' => '非法提交參數'
                )
            );
            //content
            $this->form_validation->set_rules(
                'content', 'content','trim|required|min_length[1]',
                // |max_length[255]',
                array(
                    'required'  => '內容未輸入',
                    'min_length' => '內容輸入錯誤',
                    // 'max_length' => '內容輸入長度超過'
                )
            );
            if ($this->form_validation->run() === FALSE){
                $msg_array['msg'] = trim(validation_errors());
                $msg_array['code'] = '3';
                $berror = TRUE;
            }
        }

        if ($this->form_validation->run() === FALSE){
            $msg = trim(validation_errors());
            $berror = TRUE;
        }
        
        //資料庫
        if (!$berror) {
            $cid = $post_data['cid'];
            $content = $_POST['content'];
            $res = $this->Qa_model->add_text($cid,$content);
            if ($res === FALSE) {
                $msg = "新增失敗";
                $code = 3;
                $berror = TRUE;
            }else {
                $msg = "新增成功";
                $code = 0;
            }
        }
        $msg_array = array(
            "msg" => $msg,
            "code" => $code
        );
        $msg = json_encode($msg_array,320);
        echo $msg;
    }
    //文字順序變更
    public function ajax_edit_text_seq()
    {
        //限定POST,防機器人,來源
        if ($this->input->server('REQUEST_METHOD') != 'POST' || $this->agent->is_robot() || $this->agent->is_referral()) {
            show_error('Access forbidden', 403);
            exit;
        }

        $msg = '調整順序失敗';
        $code = 1;
        $berror = FALSE;

        //XSS過濾
        $post_data = $this->security->xss_clean($this->input->post());
        if (empty($this->security->xss_clean($post_data))) {
            $msg ='非法提交參數';
            $berror = TRUE;
        }

        //驗證
        if (!$berror) {
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
        }

        //排序
        if (!$berror) {
            $res = $this->Qa_model->edit_text_seq($sort_array);
            if ($res === FALSE) {
                $msg = "調整順序失敗";
                $code = 3;
            }else {
                $msg = "調整順序成功";
                $code = 0;
            }
        }

        $msg_array = array(
            "msg" => $msg,
            "code" => $code
        );
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
        $code = 1;
        $msg = "刪除失敗";

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
        
        //資料庫
        if (!$berror) {
            $id = $post_data['id'];
            $res = $this->Qa_model->del_text($id);
            if ($res === FALSE) {
                $msg = "刪除失敗";
                $code = 3;
                $berror = TRUE;
            }else {
                $msg = "刪除成功";
                $code = 0;
            }
        }

        $msg_array = array(
            "msg" => $msg,
            "code" => $code
        );
        $msg = json_encode($msg_array,320);
        echo $msg;
    }
}
/* End of file Qa.php */