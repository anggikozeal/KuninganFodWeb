<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Apps extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->logout_message = array(
			"severity" => "success",
			"message" => "Anda baru saja logout"
		);
		$this->login_message = array(
			"severity" => "success",
			"message" => "Selamat datang, silakan login"
		); 
		$this->login_danger = array(
			"severity" => "danger",
			"message" => "Anda tidak diperkenankan akses halaman ini tanpa login"
		); 
	}

	public function index(){
		if($this->session->userdata("session_appssystem_code")){
			$this->load->view('apps/header');
			$this->load->view('apps/body_request_pencairan');
		}else{
			$this->load->view('apps/login',$this->login_danger);
		}
	}
	
	public function riwayat(){
		if($this->session->userdata("session_appssystem_code")){
			$this->load->view('apps/header');
			$this->load->view('apps/body_riwayat_pencairan');
		}else{
			$this->load->view('apps/login',$this->login_danger);
		}
	}
	
	public function petunjuk(){
		if($this->session->userdata("session_appssystem_code")){
			$this->load->view('apps/header');
			$this->load->view('apps/body_petunjuk');
		}else{
			$this->load->view('apps/login',$this->login_danger);
		}
	}
	
	public function tentang_aplikasi(){
		if($this->session->userdata("session_appssystem_code")){
			$this->load->view('apps/header');
			$this->load->view('apps/body_tentang_aplikasi');
		}else{
			$this->load->view('apps/login',$this->login_danger);
		}
	}
	
	public function login(){
		if($this->session->userdata("session_appssystem_code")){
			redirect('apps/', 'refresh');
		}
		$this->load->view('apps/login',$this->login_message);
		
	}

	public function logout(){
		if($this->session->userdata("session_appssystem_code")){
			$this->session->sess_destroy();
		}
		$this->load->view('apps/login',$this->logout_message);
	}

}