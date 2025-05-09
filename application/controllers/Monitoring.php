<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring extends CI_Controller
{
  function __construct()
  {
      parent::__construct();
      $this->load->model('data_model');
      date_default_timezone_set("Asia/Jakarta");
      if($this->session->userdata('login_form') != "rindangjati_weaving_sess"){
        redirect(base_url('login'));
      }
  }
   
  function index(){ 
    echo "Error token";
    //   $data = array(
    //       'title' => 'Beranda Aplikasi',
    //       'sess_nama' => $this->session->userdata('nama'),
    //       'sess_id' => $this->session->userdata('id_login'),
    //       'sess_user' => $this->session->userdata('username'),
    //       'sess_pass' => $this->session->userdata('password'),
    //       'urli' => 'beranda'
    //   );
    //   $this->load->view('part/main_head', $data);
    //   $this->load->view('beranda_view', $data);
    //   $this->load->view('part/main_js', $data);
  } //en
   
  function loom(){ 
      $url = $this->uri->segment(3);
      if($url==""){
          $this->load->view('page/monitoring_loom_mesin');
      } elseif(empty($url)){
          $this->load->view('page/monitoring_loom_mesin');
      } elseif($url=="month"){
          $this->load->view('page/report_monitoring_loom_mesin');
      } elseif($url=="konstruksi"){
        $this->load->view('page/report_monitoring_loom_mesin_konstruksi');
      } else {
          $this->load->view('page/edit_loom_mesin');
      }
      
  } //en


}