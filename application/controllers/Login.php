<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller{

	function __construct(){
		parent::__construct();
		$this->load->model(array('login_model'));
		date_default_timezone_set("Asia/Jakarta");
	}

	public function index(){
		$this->session->sess_destroy();
		$this->load->view('login_form');
	}
	public function action(){
		//echo "oke";
		//username = "direksi"
		//password = "Superadmin1"
		$optios = $this->input->post('options');
		$user = $this->login_model->filter($this->input->post('username'));
		$pass = $this->input->post('password');
		//echo "<br> $optios <br> $user <br> $pass";
		if($optios=="Manager"){
			//echo "anda adalah admin";
			if($user=="direksi1" AND $pass=="Direksi123"){
				//echo "you here";
				$data_session = array(
					'id' => '0001',
					'nama'  => 'Direksi Rindang Jati',
					'username'=> 'Direksi1',
					'password' => 'Direksi123',
					'hak'     => 'Manager',
					'departement' => 'RJS',
					'login_form'=> 'rindangjati_sess'
				);
				$this->session->set_userdata($data_session);
				redirect(base_url('pagedireksi'));
			} elseif($user=="direksi2" AND $pass=="adicanting"){
				$data_session = array(
					'id' => '0002',
					'nama'  => 'Direksi Rindang Jati',
					'username'=> 'Direksi2',
					'password' => 'adicanting',
					'hak'     => 'Manager',
					'departement' => 'RJS',
					'login_form'=> 'rindangjati_sess'
				);
				$this->session->set_userdata($data_session);
				redirect(base_url('pagedireksi'));
			} else {
				if($this->login_model->cek_username($user) == true){
					$cek = $this->login_model->cek_login('user', ['hak_akses' => 'Manager', 'username' => $user, 'password' => sha1($pass)]);
					if($cek->num_rows() == 1) {
						$dt = $cek->row_array();
						$id = $dt['id_user'];
						$data_session = array(
							'id' => $id,
							'nama'  => $dt['nama_user'],
							'username'=> $dt['username'],
							'password' => $dt['password'],
							'hak'     => $dt['hak_akses'],
							'departement' => $dt['departement'],
							'login_form'=> 'rindangjati_sess'
						);
						$this->session->set_userdata($data_session);
						redirect(base_url('beranda/managerdashboard'));
					} else {
						$this->session->set_flashdata('announce', 'Password anda salah');
						redirect(base_url('login'));
					}
				} else {
					$this->session->set_flashdata('announce', 'Username / Email login tidak terdaftar');
					redirect(base_url('login'));
				}
			}
		} elseif($optios=="Admin"){
			//echo "Anda adalah teller";
			if($this->login_model->cek_username($user) == true){
				$cek = $this->login_model->cek_login('user', ['hak_akses' => 'Admin', 'username' => $user, 'password' => sha1($pass)]);
				if($cek->num_rows() == 1) {
					$dt = $cek->row_array();
					$id = $dt['id_user'];
					$data_session = array(
						'id' => $id,
						'nama'  => $dt['nama_user'],
						'username'=> $dt['username'],
						'password' => $dt['password'],
						'hak'     => $dt['hak_akses'],
						'departement' => $dt['departement'],
						'login_form'=> 'rindangjati_sess'
					);
					$this->session->set_userdata($data_session);
					redirect(base_url('beranda'));
				} else {
					$this->session->set_flashdata('announce', 'Password anda salah');
		        	redirect(base_url('login'));
				}
			} else {
				$this->session->set_flashdata('announce', 'Username / Email tidak terdaftar');
		        redirect(base_url('login'));
			}
		} else {
			$this->session->set_flashdata('announce', 'Anda harus memilih salah satu akses anda');
		    redirect(base_url('login'));
		}

	}	
	
	
	public function logout(){
		$this->session->sess_destroy();
	}

}