<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends CI_Controller
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
      echo 'Error';
  } //en

  function pembelian(){
        $ntf = $this->uri->segment(4);
        $id = $this->uri->segment(3);
        $cek = $this->data_model->get_byid('penerimaan_benang', ['sha1(id_penerimaan)'=>$id]);
        if($cek->num_rows() == 1){ $cekhasil = $cek->row_array(); } else { $cekhasil="null"; }
        if($ntf==""){ $notif = "null"; } else { $notif = "true"; }
        $data = array(
            'title' => 'Update Pembelian Benang',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id_login'),
            'sess_user' => $this->session->userdata('username'),
            'sess_pass' => $this->session->userdata('password'),
            'urli' => 'benang',
            'notif' => $notif,
            'cekhasil' => $cekhasil
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('page/update_pembelian_benang', $data);
        $this->load->view('part/main_js', $data);
  } //end

  function warping(){
        $ntf = $this->uri->segment(4);
        $id = $this->uri->segment(3);
        $cek = $this->data_model->get_byid('produksi_warping', ['kode_proses'=>$id]);
        if($cek->num_rows() == 1){ $cekhasil = $cek->row_array(); } else { $cekhasil="null"; }
        if($ntf==""){ $notif = "null"; } else { $notif = "true"; }
        $data = array(
            'title' => 'Update Produksi Warping',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id_login'),
            'sess_user' => $this->session->userdata('username'),
            'sess_pass' => $this->session->userdata('password'),
            'urli' => 'warping',
            'notif' => $notif,
            'cekhasil' => $cekhasil,
            'kodeproses' => $id
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('page/update_warping', $data);
        $this->load->view('part/main_js', $data);
  } //end

  function sizing(){
        $ntf = $this->uri->segment(4);
        $id = $this->uri->segment(3);
        $cek = $this->data_model->get_byid('produksi_sizing', ['sha1(id_sizing)'=>$id]);
        if($cek->num_rows() == 1){ $cekhasil = $cek->row_array(); } else { $cekhasil="null"; }
        if($ntf==""){ $notif = "null"; } else { $notif = "true"; }
        $data = array(
            'title' => 'Update Produksi Sizing',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id_login'),
            'sess_user' => $this->session->userdata('username'),
            'sess_pass' => $this->session->userdata('password'),
            'urli' => 'sizing',
            'notif' => $notif,
            'cekhasil' => $cekhasil,
            'idsizing' => $id
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('page/update_sizing', $data);
        $this->load->view('part/main_js3', $data);
  } //end

  function bahanbaku(){
      $u = $this->uri->segment(3);
      $token = $this->uri->segment(4);
      if($u == "warping"){
        $data = array(
            'title' => 'Update Bahan Baku Warping',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id_login'),
            'sess_user' => $this->session->userdata('username'),
            'sess_pass' => $this->session->userdata('password'),
            'urli' => 'warping',
            'dataid' => $token
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('page/update_warping_baku', $data);
        $this->load->view('part/main_js4', $data);
        //echo "warping";
      } else {
          if($u == "sizing"){

          } else {
              if($u == "ajl"){

              } else {
                echo "Error Token..";
              }
          }
      }
  } //end

  function produksi(){
        $url = $this->uri->segment(3);
        $id = $this->uri->segment(4);
        if($url == "mesin"){
            $cek_id = $this->data_model->get_byid('produksi_mesin_ajl', ['id_produksi_mesin'=>$id]);
            if($cek_id->num_rows() == 1){
                $kode_proses = $cek_id->row("kode_proses");
                if($ntf==""){ $notif = "null"; } else { $notif = "true"; }
                $data = array(
                    'title' => 'Proses Produksi AJL',
                    'sess_nama' => $this->session->userdata('nama'),
                    'sess_id' => $this->session->userdata('id_login'),
                    'sess_user' => $this->session->userdata('username'),
                    'sess_pass' => $this->session->userdata('password'),
                    'urli' => 'ajl',
                    'notif' => $notif,
                    'id_produksi_mesin' => $id,
                    'kodeproses' => $kode_proses,
                    'val' => $cek_id->row_array()
                );
                $this->load->view('part/main_head', $data);
                $this->load->view('page/update_ajl', $data);
                $this->load->view('part/main_js_ajl', $data);
            } else {
                echo "Token Error..";
            }
        } else {
            echo "Token Error..";
        }
  } //end

  function baku(){
      $ntf = $this->uri->segment(2);
      if($ntf==""){ $notif = "null"; } else { $notif = "true"; }
      $data = array(
          'title' => 'Data Pembelian Bahan Baku',
          'sess_nama' => $this->session->userdata('nama'),
          'sess_id' => $this->session->userdata('id_login'),
          'sess_user' => $this->session->userdata('username'),
          'sess_pass' => $this->session->userdata('password'),
          'urli' => 'baku',
          'notif' => $notif
      );
      $this->load->view('part/main_head', $data);
      $this->load->view('newpage/pembelian_baku', $data);
      $this->load->view('part/main_js_baku', $data);
  } //end

  function inputbaku(){
      $ntf = $this->uri->segment(2);
      if($ntf==""){ $notif = "null"; } else { $notif = "true"; }
      $data = array(
          'title' => 'Input Data Pembelian Bahan Baku',
          'sess_nama' => $this->session->userdata('nama'),
          'sess_id' => $this->session->userdata('id_login'),
          'sess_user' => $this->session->userdata('username'),
          'sess_pass' => $this->session->userdata('password'),
          'urli' => 'baku',
          'notif' => $notif
      );
      $this->load->view('part/main_head', $data);
      $this->load->view('newpage/input_pembelian_baku', $data);
      $this->load->view('part/main_js_baku', $data);
  } //end

  function savedbaku(){
        $baku = $this->input->post('namabaku');
        $jns = $this->input->post('jns');
        $tgl = $this->input->post('tgl');
        $jmlbeli = $this->input->post('jmlbeli');
        $satuan = $this->input->post('satuan');
        $supp = $this->input->post('supp');
        $sj = $this->input->post('sj');
        $ket = $this->input->post('ket');
        if($baku!="" AND $jns!="" AND $tgl!="" AND $jmlbeli!="" AND $satuan!="" AND $supp!="" AND $sj!=""){
            $dtlist = [
                'tgl_beli' => $tgl,
                'sj' => $sj,
                'jenis_baku' => $jns,
                'nama_baku' => $baku,
                'satuan' => $satuan,
                'jumlah_beli' => $jmlbeli,
                'keter' => $ket,
                'supplier' => $supp,
                'harga_satuan' => 0,
                'yg_input' => $this->session->userdata('username')
            ];
            $this->data_model->saved('data_baku', $dtlist);
            echo json_encode(array("statusCode"=>200, "psn"=>"Berhasil menyimpan data pembelian $jns"));
        } else {
            echo json_encode(array("statusCode"=>400, "psn"=>"Data yang di kirim tidak sesuai"));
        }
  } //end

  function deltbaku(){
        $id = $this->input->post('id');
        $this->db->query("DELETE FROM data_baku WHERE id_pembaku='$id'");
        echo "success";
  }
    
}
?>