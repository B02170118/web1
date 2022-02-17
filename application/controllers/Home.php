<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
	public function __construct() 
	{
		parent::__construct();

		$this->load->library('test1111_lib');
		$this->load->helper(array('url'));
		
		//選單
		// $this->HeaderMenu = $this->test1111_lib->get_menu();
		
		//登入判斷
		$this->test1111_lib->check_login();
	}
	
	public function index()
	{	
		//header選單
		// $data['headermenu'] = $this->HeaderMenu;

		//載入當前語言文件
		// $this->lang->load('home');

		$this->load->view('templates/head');
		$this->load->view('templates/header');
		$this->load->view('templates/title');
		$this->load->view('home/index');
		$this->load->view('templates/footer');
		// $this->load->view('home/app');
    }
}
/* End of file Ｈome.php */