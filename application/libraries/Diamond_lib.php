<?php
(defined('BASEPATH') || defined('SYSPATH')) or die('No direct access allowed.');

class test1111_lib {

	protected $ci;

	public function __construct() {
        $this->ci =& get_instance();
        $this->ip = $this->ci->input->ip_address();
        $this->time = time();
	}
    //遞迴刪除路徑底下目錄跟檔案
    public function deldir($path)
    {
		$sign = "<br>";
		$berror = FALSE;
		$data = array("msg" => "","code" => 0);
		//如果是目錄則繼續
		if(is_dir($path)){
			//掃描一個資料夾內的所有資料夾和檔案並返回陣列
			$p = scandir($path);
			foreach($p as $val){
				//排除目錄中的.和..
				if($val !="." && $val !=".."){
					//如果是目錄則遞迴子目錄，繼續操作
					if(is_dir($path.$val)){
						//子目錄中操作刪除資料夾和檔案
						deldir($path.$val.'/');
						//目錄清空後刪除空資料夾
						if (@rmdir($path.$val.'/') === FALSE) {
							$data['msg'] .="刪除目錄失敗: ".$path.$val.'/'.$sign;
							$data['code'] = 1;
							$berror = TRUE;
						}
					}else{
						//如果是檔案直接刪除
						if (@unlink($path.$val) === FALSE){
							$data['msg'] .="刪除檔案失敗: ".$path.$val.$sign;
							$data['code'] = 2;
							$berror = TRUE;
						}
					}
				}
			}
			//都刪除成功刪目錄
			if ($berror === FALSE) {
                if (@rmdir($path) === FALSE) {
                    $data['msg'] .="刪除目錄失敗: ".$path.$sign;
                    $data['code'] = 7;
                    $berror = TRUE;
                }
            }
            //都刪除成功
            if ($berror === FALSE) {
                $data['msg'] .="刪除所有目錄和檔案成功";
				$data['code'] = 0;
            }
		}else {
			$data['msg'] .="無此資料夾: ".$path;
			$data['code'] = 3;
		}
		return $data;
	}
    //上傳圖片lib
    public function upload_img($file,$type,$id = NULL) 
    {
		//驗證數據
        $res_array = array();//返回訊息
        $dir = "";//檔案路徑
        
        //上傳資料夾選擇
        switch ($type) {
            case 'activity':
                $dir = UPLOAD_DIR_ACTIVITY;
                break;
            case 'workphoto':
                $dir = UPLOAD_DIR_WORKPHOTO;
                break;
            case 'add_workphoto_category':
                $dir = UPLOAD_DIR_WORKPHOTO;
                break;
            case 'combowe':
                $dir = UPLOAD_DIR_COMBOWE;
                break;
            case 'makeup':
                $dir = UPLOAD_DIR_MAKEUP;
                break;
            case 'add_make_category':
                $dir = UPLOAD_DIR_MAKEUP;
                break;
            case 'dress':
                $dir = UPLOAD_DIR_DRESS;
                break;    
            case 'add_dress_category':
                $dir = UPLOAD_DIR_DRESS;
                break;
            case 'blog':
                $dir = UPLOAD_DIR_BLOG;
                break;
            case 'blog_content':
                $dir = UPLOAD_DIR_BLOG.$id."/";
                break;    
            case 'staff':
                $dir = UPLOAD_DIR_STAFF;
                break;   
            default:
                # code...
                break;
        }

        # 檢查有無路徑
        if ($dir == "") {
            return $res_array;
            exit;
        }
        //是否編輯器的上傳
        if (empty($id)) {
            # 取得上傳檔案數量
            $fileCount = count($file['name']);
            $count = 1;
            for ($i = 0; $i < $fileCount; $i++) {
                $text = "";//返回訊息
                $code = 1;//返回碼
                $img_dir = "";//圖片路徑
                $end_dir = "";//檔案路徑+檔案
                # 檢查檔案是否上傳成功
                if (!empty($file['name'][$i]) && $file['error'][$i] === UPLOAD_ERR_OK){
                    $text = "第".$count."張 : ";//依序上傳成功顯示
                    $imageFileType = pathinfo(basename($file["name"][$i]), PATHINFO_EXTENSION); //上傳的檔案類型
                    $allowed_types = array('gif','jpg','png','jpeg'); //圖片類型
                    $upload_path = WWW_test1111_COM_IMG_LOCATION.$dir; //上傳位置
                    
                    //檢查檔案
                    if (@!getimagesize($file["tmp_name"][$i])) { //是否為圖檔
                        $text .= "不是圖檔";
                        $code = 2;
                    }elseif ($file["size"][$i] <= 0 || $file['size'][$i] / 1024 > 20000) { //檔案大小
                        $text .= "檔案大小不符";
                        $code = 2;
                    }elseif (!in_array(strtolower($imageFileType), $allowed_types)) {
                        $text .= "檔案類型不符";
                        $code = 2;
                    }else{
                        $encrypt_name = date("YmdHis").rand(10000,99999); //上傳名稱
                        # 檢查檔案是否已經存在
                        if (file_exists($upload_path.$encrypt_name)){
                            $text .= '檔案已存在請重試';
                            $code = 3;
                        } else {
                            $filetmp = $file['tmp_name'][$i];//暫存檔案位置
                            $end_dir = $upload_path.$encrypt_name.".".$imageFileType;//檔案路徑+檔案+副檔名
                            $img_dir = $dir.$encrypt_name.".".$imageFileType;//圖片路徑+檔案+副檔名
                            # 將檔案移至指定位置
                            if (!is_dir($upload_path)) { //檢查資料夾是否存在
                                $text .= "上傳目錄不存在 : " . $upload_path;
                                $code = 2;
                            }else{
                                if (move_uploaded_file($filetmp, $end_dir)) {
                                    $text .= "圖片上傳成功";
                                    $code = 0;
                                }else{
                                    $text .= "圖片上傳失敗";
                                    $code = 1;
                                }
                            }
                        }
                    }
                }else {
                    $text .= '上傳錯誤,錯誤代碼：' . $file['error'][$i];
                    $code = 4;
                }
                $res_array[$i]['msg'] = $text;
                $res_array[$i]['code'] = $code;
                $res_array[$i]['dir'] = $img_dir;
                $count++;
            }
        }else {
            # 取得上傳檔案數量
            $text = "";//返回訊息
            $code = 1;//返回碼
            $img_dir = "";//圖片路徑
            $end_dir = "";//檔案路徑+檔案
            # 檢查檔案是否上傳成功
            if ($_FILES['upload']['error'] === UPLOAD_ERR_OK){
                $imageFileType = pathinfo(basename($_FILES['upload']["name"]), PATHINFO_EXTENSION); //上傳的檔案類型
                $allowed_types = array('gif','jpg','png','jpeg'); //圖片類型
                $upload_path = WWW_test1111_COM_IMG_LOCATION.$dir; //上傳位置
                
                //檢查檔案
                if (@!getimagesize($_FILES['upload']["tmp_name"])) { //是否為圖檔
                    $text .= "不是圖檔";
                    $code = 2;
                }elseif ($_FILES['upload']["size"] <= 0 || $_FILES['upload']['size'] / 1024 > 20000) { //檔案大小
                    $text .= "檔案大小不符";
                    $code = 2;
                }elseif (!in_array(strtolower($imageFileType), $allowed_types)) {
                    $text .= "檔案類型不符";
                    $code = 2;
                }else{
                    $encrypt_name = date("YmdHis").rand(10000,99999); //上傳名稱
                    # 檢查檔案是否已經存在
                    if (file_exists($upload_path.$encrypt_name)){
                        $text .= '檔案已存在請重試';
                        $code = 3;
                    } else {
                        $filetmp = $_FILES['upload']['tmp_name'];//暫存檔案位置
                        $end_dir = $upload_path.$encrypt_name.".".$imageFileType;//檔案路徑+檔案+副檔名
                        $img_dir = $dir.$encrypt_name.".".$imageFileType;//圖片路徑+檔案+副檔名
                        # 將檔案移至指定位置
                        if (!is_dir($upload_path)) { //檢查資料夾是否存在
                            if(mkdir($upload_path, 0755)){
                                if (move_uploaded_file($filetmp, $end_dir)) {
                                    $text .= "上傳成功";
                                    $code = 0;
                                }else{
                                    $text .= "上傳失敗";
                                    $code = 1;
                                }
                            }else {
                                $text .= "建立目錄失敗";
                                $code = 2;
                            }
                        }else{
                            if (move_uploaded_file($filetmp, $end_dir)) {
                                $text .= "上傳成功";
                                $code = 0;
                            }else{
                                $text .= "上傳失敗";
                                $code = 1;
                            }
                        }
                    }
                }
            }else {
                $text .= '錯誤代碼：' . $_FILES['upload']['error'];
                $code = 4;
            }
            $res_array['msg'] = $text;
            $res_array['filename'] = $encrypt_name;
            $res_array['code'] = $code;
            $res_array['dir'] = $img_dir;
        }
        
		return $res_array;
    }
    //登入判斷
    public function check_login($str = NULL) 
    {
        if ($str != 'login') {
            #
            $this->ci->load->model(array('Login_model'));

            //有session檢查 token
            if ($this->ci->session->has_userdata('user') === TRUE && $this->ci->session->has_userdata('token') === TRUE) {
                $user = $this->ci->session->userdata('user');
                $token = $this->ci->session->userdata('token');
                $json = $this->decrypt_code($token);
                $data = json_decode(json_decode($json,true),true);
                if ($data['iss'] != ISS || $data['web'] != WEB || $data['ip'] != $this->ip || $data['account'] != $user || $data['time'] <= $this->time) {
                    $this->ci->session->unset_userdata('user');
                    $this->ci->session->unset_userdata('token');
                    redirect('login');
                }else {
                    $user_data = array(
                        "iss" => ISS, //簽發者
                        "web" => WEB, //網站
                        "ip" => $this->ip,//登入IP
                        "account" => $user,//帳號
                        "time" => $this->time + 1800//過期時間 
                    );
                    $encrypt_json = json_encode($user_data,320);
                    $token = $this->encrypt_code($encrypt_json);
                    $session_data = array('token' => $token,'user' => $user);
                    $this->ci->session->set_userdata($session_data);
                }
            }else {
                if ($this->ci->session->has_userdata('user') === TRUE) {
                    $this->ci->session->unset_userdata('user');
                }
                if ($this->ci->session->has_userdata('token') === TRUE) {
                    $this->ci->session->unset_userdata('token');
                }
                redirect('login');
            }
        }else {
            if ($this->ci->session->has_userdata('user') === TRUE && $this->ci->session->has_userdata('token') === TRUE) {
                redirect('activity');
            }
        }
        return;
    }
    //加密
    public function encrypt_code($data)
    {
        $this->ci->load->library('encryption');
        $key = $this->ci->config->item('encryption_key');
        //加密方法
        $this->ci->encryption->initialize(
            array(
                'cipher' => 'aes-128',
                'mode' => 'CBC',
                'key' => $key
            )
        );
        $json= json_encode($data,320);
        $token = $this->ci->encryption->encrypt($json) ;//加密後
        return $token;
    }
    //解密
    public function decrypt_code($data)
    {
        $this->ci->load->library('encryption');
        $key = $this->ci->config->item('encryption_key');
        //加密方法
        $this->ci->encryption->initialize(
            array(
                'cipher' => 'aes-128',
                'mode' => 'CBC',
                'key' => $key
            )
        );
        $json = $this->ci->encryption->decrypt($data);
        return $json;
    }
    //獲取驗證碼圖片
    public function get_captcha()
    {
        
        $this->ci->Login_model->del_captcha($this->ip, $this->time);//刪除舊的

        //產生驗證碼
        $vals = array(
            'word'          => '',
            'img_path'      => CAPTCHA_LOCATION,
            'img_url'       => ADMIN_test1111_COM_CAPTCHA,
            'font_path'     => CAPTCHA_FONT_LOCATION.'YuNaFont.ttf',
            'img_width'     => '150',
            'img_height'    => 50,
            'expiration'    => 3600,
            'word_length'   => 4,
            'font_size'     => 24,
            'img_id'        => 'captchaid',
            'pool'          => '23456789ABCDEFGHJKLMNPQRSTUVWXYZ',
    
            // White background and border, black text and red grid
            'colors'        => array(
                    'background' => array(255, 255, 255),
                    'border' => array(255, 255, 255),
                    'text' => array(0, 0, 0),
                    'grid' => array(255, 40, 40)
            )
        );
        $cap = create_captcha($vals);

        //寫入資料
        $data = array(
            'captcha_time'  => substr($cap['time'],0,10),
            'ip_address'    => $this->ip,
            'word'          => $cap['word']
        );
        $query = $this->ci->Login_model->insert_captcha($data);
        if ($query === TRUE) {
            return $cap['image'];
        }else {
            return NULL;
        }
    }
}
?>
