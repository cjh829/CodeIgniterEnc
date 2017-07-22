<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		if (!isset($this->session->user)) {
			redirect('//'.base_url('admin/home/login'));
		}

		$this->load->view('admin/home/index');
	}

	public function login() {

		if (isset($this->session->user)) {
			redirect('//'.base_url('admin/home/index'));
		}

		$data['errormsg'] = $this->session->flashdata('errormsg');

		$data['captcha_img'] = $this->_my_login_captcha();

		$this->load->view('admin/home/login',$data);
	}

	public function recaptcha(){
		echo json_encode(array('captcha'=>$this->_my_login_captcha()));
	}

	protected function _my_login_captcha(){
		$this->load->helper('captcha');
		$vals = array(
			'img_path'  => APPPATH.'/static/captcha/',
			'img_url'   => '//'.base_url('captcha'),
			'font_path' => APPPATH.'/static/captcha/calibrib.ttf',
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
		return '//'.base_url('captcha').'/'.$cap['filename'];
	}

	public function login_submit() {
		$userid = $this->input->post('userid');
		$passwd = $this->input->post('password');
		$captcha = $this->input->post('captcha');

		$this->load->model('adminuser');
		$hash = $this->adminuser->getPasswordHash($userid);
		$ok = password_verify($passwd,$hash);
		if (!$ok){
			$this->session->set_flashdata('errormsg','用戶名或密碼錯誤');
			redirect('//'.base_url('admin/home/login'));
		}

		if (trim($captcha) !== $this->session->login_captcha){
			$this->session->set_flashdata('errormsg','驗證碼不對！');
			redirect('//'.base_url('admin/home/login'));
		}

		$this->session->user = $userid;
		redirect('//'.base_url('admin/home'));
	}

	public function logout() {
		if (isset($this->session->user)) {
			$this->session->sess_destroy();
		}
		redirect('//'.base_url('admin/home/login'));
	}

	public function test(){
		return view('admin/home/test');
	}
}