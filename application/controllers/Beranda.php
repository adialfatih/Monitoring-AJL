<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Beranda extends CI_Controller
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
      //echo "anda berhasil login";
      $data = array(
          'title' => 'Beranda Aplikasi',
          'sess_nama' => $this->session->userdata('nama'),
          'sess_id' => $this->session->userdata('id_login'),
          'sess_user' => $this->session->userdata('username'),
          'sess_pass' => $this->session->userdata('password'),
          'urli' => 'beranda'
      );
      $this->load->view('part/main_head', $data);
      $this->load->view('beranda_view', $data);
      $this->load->view('part/main_js', $data);
  } //en

  function belibenang(){
    //echo "anda berhasil login";
      $ntf = $this->uri->segment(2);
      if($ntf==""){ $notif = "null"; } else { $notif = "true"; }
      $data = array(
          'title' => 'Input Data Pembelian Benang',
          'sess_nama' => $this->session->userdata('nama'),
          'sess_id' => $this->session->userdata('id_login'),
          'sess_user' => $this->session->userdata('username'),
          'sess_pass' => $this->session->userdata('password'),
          'urli' => 'benang',
          'notif' => $notif
      );
      $this->load->view('part/main_head', $data);
      $this->load->view('page/pembelian_benang', $data);
      $this->load->view('part/main_js', $data);
  }

  function belibenangdata(){
      $ntf = $this->uri->segment(2);
      if($ntf==""){ $notif = "null"; } else { $notif = "true"; }
      $data = array(
          'title' => 'Data Pembelian Benang',
          'sess_nama' => $this->session->userdata('nama'),
          'sess_id' => $this->session->userdata('id_login'),
          'sess_user' => $this->session->userdata('username'),
          'sess_pass' => $this->session->userdata('password'),
          'urli' => 'benang',
          'notif' => $notif
      );
      $this->load->view('part/main_head', $data);
      $this->load->view('page/data_pembelian_benang', $data);
      $this->load->view('part/main_js', $data);
  } //end

  function stokbenang(){
        if($ntf==""){ $notif = "null"; } else { $notif = "true"; }
        $data = array(
            'title' => 'Stok Benang',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id_login'),
            'sess_user' => $this->session->userdata('username'),
            'sess_pass' => $this->session->userdata('password'),
            'urli' => 'stokbenang',
            'notif' => $notif
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('page/stok_benang', $data);
        $this->load->view('part/main_js', $data);
  } //end

  function stokbeamwarping(){
        if($ntf==""){ $notif = "null"; } else { $notif = "true"; }
        $data = array(
            'title' => 'Data Beam Warping',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id_login'),
            'sess_user' => $this->session->userdata('username'),
            'sess_pass' => $this->session->userdata('password'),
            'urli' => 'beamwarping',
            'notif' => $notif
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('page/stok_beam_warping', $data);
        $this->load->view('part/main_js', $data);
  } //end

  function stokbeamsizing(){
        if($ntf==""){ $notif = "null"; } else { $notif = "true"; }
        $data = array(
            'title' => 'Data Beam Sizing',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id_login'),
            'sess_user' => $this->session->userdata('username'),
            'sess_pass' => $this->session->userdata('password'),
            'urli' => 'beamsizing',
            'notif' => $notif
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('page/stok_beam_sizing', $data);
        $this->load->view('part/main_js', $data);
  } //end  

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
            'kodeproses' => $this->data_model->acakKode2(15)
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('page/warping_view', $data);
        $this->load->view('part/main_js2', $data);
  } //end/  

  function sizingpershift(){
        //$this->db->query("DELETE FROM produksi_warping WHERE jenis_mesin='null' AND tgl_produksi='0000-00-00'");
        if($ntf==""){ $notif = "null"; } else { $notif = "true"; }
        $data = array(
            'title' => 'Proses Sizing Pershift',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id_login'),
            'sess_user' => $this->session->userdata('username'),
            'sess_pass' => $this->session->userdata('password'),
            'urli' => 'sizing',
            'notif' => $notif,
            'kodeproses' => $this->data_model->acakKode2(15)
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('page/sizing_view_pershift', $data);
        $this->load->view('part/main_js', $data);
  } //end/

  function warpingpershift(){
        //$this->db->query("DELETE FROM produksi_warping WHERE jenis_mesin='null' AND tgl_produksi='0000-00-00'");
        if($ntf==""){ $notif = "null"; } else { $notif = "true"; }
        $data = array(
            'title' => 'Proses Warping Pershift',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id_login'),
            'sess_user' => $this->session->userdata('username'),
            'sess_pass' => $this->session->userdata('password'),
            'urli' => 'warping',
            'notif' => $notif,
            'kodeproses' => $this->data_model->acakKode2(15)
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('page/warping_view_pershift', $data);
        $this->load->view('part/main_js', $data);
  } //end/

  function sizing(){
        //$this->db->query("DELETE FROM produksi_warping WHERE jenis_mesin='null' AND tgl_produksi='0000-00-00'");
        if($ntf==""){ $notif = "null"; } else { $notif = "true"; }
        $data = array(
            'title' => 'Proses Sizing',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id_login'),
            'sess_user' => $this->session->userdata('username'),
            'sess_pass' => $this->session->userdata('password'),
            'urli' => 'sizing',
            'notif' => $notif,
            'kodeproses' => $this->data_model->acakKode2(17)
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('page/sizing_view', $data);
        $this->load->view('part/main_js3', $data);
  } //end/sizing

  function ajl(){
        //$this->db->query("DELETE FROM produksi_warping WHERE jenis_mesin='null' AND tgl_produksi='0000-00-00'");
        if($ntf==""){ $notif = "null"; } else { $notif = "true"; }
        $data = array(
            'title' => 'Proses Produksi AJL',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id_login'),
            'sess_user' => $this->session->userdata('username'),
            'sess_pass' => $this->session->userdata('password'),
            'urli' => 'ajl',
            'notif' => $notif,
            'kodeproses' => $this->data_model->acakKode2(19)
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('page/input_ajl', $data);
        $this->load->view('part/main_js_ajl', $data);
  } //end/sizing

  function ajldata(){
        //$this->db->query("DELETE FROM produksi_warping WHERE jenis_mesin='null' AND tgl_produksi='0000-00-00'");
        if($ntf==""){ $notif = "null"; } else { $notif = "true"; }
        $data = array(
            'title' => 'Data Produksi AJL',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id_login'),
            'sess_user' => $this->session->userdata('username'),
            'sess_pass' => $this->session->userdata('password'),
            'urli' => 'ajl',
            'notif' => $notif,
            'kodeproses' => $this->data_model->acakKode2(19)
        );
        //$this->load->view('part/main_head', $data);
        $this->load->view('page/data_mesin_ajl2', $data);
        //$this->load->view('part/main_js_ajl', $data);
  } //end/sizing
  function ajldata2(){
        //$this->db->query("DELETE FROM produksi_warping WHERE jenis_mesin='null' AND tgl_produksi='0000-00-00'");
        if($ntf==""){ $notif = "null"; } else { $notif = "true"; }
        $data = array(
            'title' => 'Data Produksi AJL',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id_login'),
            'sess_user' => $this->session->userdata('username'),
            'sess_pass' => $this->session->userdata('password'),
            'urli' => 'ajl',
            'notif' => $notif,
            'kodeproses' => $this->data_model->acakKode2(19)
        );
        $this->load->view('page/data_mesin_ajl2', $data);
  } //end/sizing
  function dtwarping(){
        if($ntf==""){ $notif = "null"; } else { $notif = "true"; }
        $data = array(
            'title' => 'Data Produksi Warping',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id_login'),
            'sess_user' => $this->session->userdata('username'),
            'sess_pass' => $this->session->userdata('password'),
            'urli' => 'warping',
            'notif' => $notif,
            'kodeproses' => $this->data_model->acakKode2(15)
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('page/data_produksi_warping', $data);
        $this->load->view('part/main_js2', $data);
  } //end

  function dtsizing(){
        if($ntf==""){ $notif = "null"; } else { $notif = "true"; }
        $tes = $this->db->query("SELECT * FROM beam_warping_used ");
        foreach($tes->result() as $ty){
            $idbeamwar = $ty->id_beamwar;
            $idpem = $ty->id_pemakaian;
            $kode_proses = $ty->kode_produksi_sizing;
            $cek_sizing = $this->data_model->get_byid('produksi_sizing', ['kode_proses'=>$kode_proses]);
            if($cek_sizing->num_rows() == 0){
                $this->data_model->updatedata('id_beamwar',$idbeamwar,'beam_warping',['status_beam'=>'masih']);
                $this->db->query("DELETE FROM beam_warping_used WHERE id_pemakaian='$idpem'");
            }
        }
       
        $data = array(
            'title' => 'Data Produksi Sizing',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id_login'),
            'sess_user' => $this->session->userdata('username'),
            'sess_pass' => $this->session->userdata('password'),
            'urli' => 'sizing',
            'notif' => $notif,
            'kodeproses' => $this->data_model->acakKode2(17)
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('page/data_produksi_sizing', $data);
        $this->load->view('part/main_js3', $data);
  } //end-

  function operatorp(){
        if($ntf==""){ $notif = "null"; } else { $notif = "true"; }
        $data = array(
            'title' => 'Data Operator Produksi',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id_login'),
            'sess_user' => $this->session->userdata('username'),
            'sess_pass' => $this->session->userdata('password'),
            'urli' => 'opr',
            'notif' => $notif,
            'kodeproses' => $this->data_model->acakKode2(17)
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('page/data_op_produksi', $data);
        $this->load->view('part/main_js3', $data);
  } //end-operatorp
    
}
?>