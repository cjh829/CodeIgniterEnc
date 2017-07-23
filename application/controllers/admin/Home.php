<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	public function index()
	{
		return view('index');
	}

	public function login() {

		if (isset($this->session->user)) {
			smart_redirect('index');
		}

		$data['errormsg'] = $this->session->flashdata('errormsg');

		$data['captcha_img'] = $this->_my_login_captcha();

		return view('login',$data);
	}

	public function recaptcha(){
		return jsonview( ['captcha'=>$this->_my_login_captcha()] );
	}

	protected function _my_login_captcha(){
		$this->load->helper('captcha');
		$vals = array(
			'img_path'  => APPPATH.'/public/captcha/',
			'img_url'   => '//'.SITE_URL.'/captcha',
			'font_path' => APPPATH.'/public/captcha/calibrib.ttf',
			'img_width' => 200,
			'img_height'    => 50,
			'expiration'    => 180,
			'word_length'   => 4,
			'font_size' => 25,
			'img_id'    => 'captcha',
			'pool'      => '0123456789',
			// White background and border, black text and red grid
			'colors'    => array(
				'background' => array(255, 255, 255),
				'border' => array(255, 255, 255),
				'text' => array(0, 0, 0),
				'grid' => array(rand(1,255),rand(1,255), rand(1,255))
			)
		);

		$cap = create_captcha($vals);
		$this->session->login_captcha = $cap['word']; 
		return '//'.SITE_URL.'/captcha/'.$cap['filename'];
	}

	public function login_submit() {
		$userid = $this->input->post('userid');
		$passwd = $this->input->post('password');
		$captcha = $this->input->post('captcha');

		$this->load->model('adminuser');
		$hash = $this->adminuser->getPasswordHash($userid);
		$ok = password_verify($passwd,$hash);
		if (!$ok){
			$this->session->set_flashdata('errormsg','incorrect user or password!');
			smart_redirect('login');
		}

		if (trim($captcha) !== $this->session->login_captcha){
			$this->session->set_flashdata('errormsg','incorrect captcha!');
			smart_redirect('login');
		}

		$this->session->user = $userid;
		$this->session->acls = $this->adminuser->getACLs($userid);
		$menuData = $this->adminuser->getMenus($userid);
		$this->session->menus = $menuData['tree'];
		$this->session->aclmenumap = $menuData['ACLmap'];
		smart_redirect('index');
	}

	public function logout() {
		if (isset($this->session->user)) {
			$this->session->sess_destroy();
		}
		smart_redirect('login');
	}

	public function test() {
	}
}