<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Logout extends CI_Controller
{
	public function __construct() 
	{
		parent::__construct();

		$this->load->library(array('test1111_lib','user_agent'));
		$this->load->helper(array('url','captcha','security'));
	}
	
	public function index()
	{	
		if ($this->session->has_userdata('user') === TRUE) {
            $this->session->unset_userdata('user');
        }
        if ($this->session->has_userdata('token') === TRUE) {
            $this->session->unset_userdata('token');
        }
		redirect('/');
	}
}
/* End of file Logout.php */