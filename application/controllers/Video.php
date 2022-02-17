<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Video extends CI_Controller
{
	public function __construct() 
	{
		parent::__construct();

		$this->load->library(array('test1111_lib','form_validation','user_agent'));
        $this->load->helper(array('url'));
		$this->load->model(array('Video_model'));

		//選單
		// $this->HeaderMenu = $this->test1111_lib->get_menu();
		
		//登入判斷
		$this->test1111_lib->check_login();
	}
	
	public function index()
	{	
		$data = array();
		$query = array();

        $data['title'] = "影片管理";

		//置頂影片
        $query = $this->Video_model->get_all_data(1);
        if (!empty($query)) {
            $data['top_video'] = $query;
        }

        //影片
        $query = $this->Video_model->get_all_data(0);
        if (!empty($query)) {
            $data['video'] = $query;
        }

		$this->load->view('templates/head',$data);
		$this->load->view('templates/header');
		$this->load->view('templates/title');
		$this->load->view('video/index');
		$this->load->view('templates/footer');
		$this->load->view('video/app');
    }
    //新增影片
    public function ajax_add_video()
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

        //title
		$this->form_validation->set_rules(
            'title', 'title','trim|max_length[255]',
            array(
                'max_length' => '字數超出限制'
            )
        );
      
        //content
        $this->form_validation->set_rules(
            'content', 'content','trim|max_length[1000]',
            array(
                'max_length' => '字數超出限制'
            )
        );

        //link
        $this->form_validation->set_rules(
            'link', 'link','trim|required|max_length[500]',
            array(
                'required'  => '未輸入影片ID',
                'max_length' => '字數超出限制'
            )
        );

        //type
        $this->form_validation->set_rules(
            'type', 'type','trim|required|greater_than_equal_to[0]|less_than_equal_to[1]',
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
        
        //insert
        $title = $post_data['title'];
        $content = $post_data['content'];
        $link = $post_data['link'];
        $type = $post_data['type'];
        $status = $post_data['status'];
        $res = $this->Video_model->add_video($title,$content,$link,$type,$status);
        if ($res === TRUE) {
            $text = '新增成功';
            $code = 0;
        }else {
            $text = '新增失敗';
            $code = 3;
        }
        $msg_array = array(
            "msg" => $text,
            "code" => $code
        );
        $msg = json_encode($msg_array,320);
        echo $msg;
    }
    //刪除影片
    public function ajax_del_video()
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
            $res = $this->Video_model->del_video($id_array);
            if ($res === FALSE) {
                $msg = "刪除失敗";
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
    //變更影片狀態
    public function ajax_change_status_video()
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

        //id
		$this->form_validation->set_rules(
            'id', 'id','trim|required|greater_than_equal_to[1]|less_than_equal_to[999999999]',
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
        $id = $post_data['id'];
        $res = $this->Video_model->update_status_video($id,$status);
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
    //影片順序變更
    public function ajax_edit_video_seq()
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
            $res = $this->Video_model->edit_video_seq($sort_array);
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
    //影片內頁設定
	public function video_view($id)
	{
		$data = array();
		$query = array();

        $data['title'] = "影片管理 - ";

        //影片data
        if (intval($id) > 0) {
            $query = $this->Video_model->get_video_view($id);
            if (!empty($query)) {
                $data['title'] = "影片管理 - ".$query['title'];
                $data['video'] = $query;
            }
        }

		$this->load->view('templates/head',$data);
		$this->load->view('templates/header');
		$this->load->view('templates/title');
		$this->load->view('video/video_view');
		$this->load->view('templates/footer');
		$this->load->view('video/video_app');
    }
    //編輯影片
    public function ajax_edit_video()
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

        //video_id
		$this->form_validation->set_rules(
            'video_id', 'video_id','trim|required|greater_than_equal_to[1]|less_than_equal_to[999999999]',
            array(
                'required'  => '非法提交參數',
                'greater_than_equal_to' => '非法提交參數',
                'less_than_equal_to' => '非法提交參數'
            )
        );

        //type
        $this->form_validation->set_rules(
            'type', 'type','trim|required|greater_than_equal_to[0]|less_than_equal_to[1]',
            array(
                'required'  => '非法提交參數',
                'greater_than_equal_to' => '非法提交參數',
                'less_than_equal_to' => '非法提交參數'
            )
        );
      
        //content
        $this->form_validation->set_rules(
            'content', 'content','trim|max_length[1000]',
            array(
                'max_length' => '字數超出限制'
            )
        );

        //link
        $this->form_validation->set_rules(
            'link', 'link','trim|required|max_length[500]',
            array(
                'required'  => '未輸入影片ID',
                'max_length' => '字數超出限制'
            )
        );

        //title
        $this->form_validation->set_rules(
            'title', 'title','trim|required|max_length[255]',
            array(
                'required'  => '未輸入影片ID',
                'max_length' => '字數超出限制'
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
        
        //insert
        $title = $post_data['title'];
        $content = $post_data['content'];
        $link = $post_data['link'];
        $type = $post_data['type'];
        $id = $post_data['video_id'];
        $res = $this->Video_model->edit_video($title,$content,$link,$type,$id);
        if ($res === TRUE) {
            $text = '更新成功';
            $code = 0;
        }else {
            $text = '更新失敗';
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
/* End of file Video.php */