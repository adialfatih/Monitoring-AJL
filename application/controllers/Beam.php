<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Beam extends CI_Controller
{
  function __construct()
  {
      parent::__construct();
      $this->load->model('data_model');
      if($this->session->userdata('login_form') != "rindangjati_weaving_sess"){
        redirect(base_url('login'));
      }
  }
   
  function index(){ 
      echo "Error..";
  } //en
  function mesin(){
      $mc = $this->db->query("SELECT * FROM table_mesin");
      foreach($mc->result() as $val){
            $_mc = $val->no_mesin;
            $kons = $this->db->query("SELECT * FROM produksi_mesin_ajl WHERE no_mesin='$_mc' ORDER BY id_produksi_mesin DESC LIMIT 1")->row("konstruksi");
            echo $kons."<br>";
      }
  }

  function warping(){
        $this->db->query("DELETE FROM produksi_warping WHERE jenis_mesin='null' AND tgl_produksi='0000-00-00'");
        if($ntf==""){ $notif = "null"; } else { $notif = "true"; }
        $data = array(
            'title' => 'Proses Warping',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id_login'),
            'sess_user' => $this->session->userdata('username'),
            'sess_pass' => $this->session->userdata('password'),
            'urli' => 'warping',
            'notif' => $notif,
            'kodeproses' => $this->uri->segment(3)
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('page/warping_view_addbeam', $data);
        $this->load->view('part/main_js', $data);
  } //end
  

    function sizing(){
        $ntf = $this->uri->segment(4);
        if($ntf==""){ $notif = "null"; } else { $notif = "true"; }
        $data = array(
            'title' => 'Proses Sizing',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id_login'),
            'sess_user' => $this->session->userdata('username'),
            'sess_pass' => $this->session->userdata('password'),
            'urli' => 'sizing',
            'notif' => $notif,
            'kodeproses' => $this->uri->segment(3)
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('page/sizing_view_addbeam', $data);
        $this->load->view('part/main_js', $data);
    } //end
    
}
?>