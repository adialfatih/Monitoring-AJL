<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adm extends CI_Controller
{
  function __construct()
  {
      parent::__construct();
      $this->load->model('data_model');
      date_default_timezone_set("Asia/Jakarta");
      if($this->session->userdata('hak') != "Manager"){
        redirect(base_url('block'));
      }
      
  }
   
  function index(){ 
      $this->load->view('blok_view');
  } //end

  function rproduksi(){ 
    if($this->session->userdata('hak') == 'Manager'){
      $data = array(
          'title' => 'Report Produksi - PPC Weaving',
          'sess_nama' => $this->session->userdata('nama'),
          'sess_id' => $this->session->userdata('id'),
          'dt_kons' => $this->data_model->get_record('tb_konstruksi'),
          'daterange' => 'true'
      );
      $this->load->view('part/main_head', $data);
      $this->load->view('part/left_sidebar', $data);
      $this->load->view('page/report_view2', $data);
      $this->load->view('part/main_js_dttable');
    } else {
      $this->load->view('blok_view');
    }
  } //end

  function reportmesin(){
    if($this->session->userdata('hak') == 'Manager'){
      $data = array(
          'title' => 'Report Produksi - PPC Weaving',
          'sess_nama' => $this->session->userdata('nama'),
          'sess_id' => $this->session->userdata('id'),
          'dt_table' => $this->data_model->sort_record('id_produksi','tb_produksi'),
          'dt_kons' => $this->data_model->get_record('tb_konstruksi'),
          'daterange' => 'true'
      );
      $this->load->view('part/main_head', $data);
      $this->load->view('part/left_sidebar', $data);
      $this->load->view('page/report_view3', $data);
      $this->load->view('part/main_js_dttable');
    } else {
      $this->load->view('blok_view');
    }
  }

  function rstoklama(){ 
    if($this->session->userdata('hak') == 'Manager'){
      $data = array(
          'title' => 'Report Stok Lama - PPC Weaving',
          'sess_nama' => $this->session->userdata('nama'),
          'sess_id' => $this->session->userdata('id'),
          'dt_kons' => $this->data_model->get_record('tb_konstruksi')
      );
      $this->load->view('part/main_head', $data);
      $this->load->view('part/left_sidebar', $data);
      $this->load->view('page/report_view_sl', $data);
      $this->load->view('part/main_js_dttable');
    } else {
      $this->load->view('blok_view');
    }
  } //end

  function this_report(){
      if($this->session->userdata('hak') == 'Manager'){
        $data = array(
            'title' => 'Report Produksi - PPC Weaving',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'dt_table' => $this->data_model->sort_record('id_produksi','tb_produksi'),
            'dt_kons' => $this->data_model->get_record('tb_konstruksi')
        );
        $this->load->view('part/main_head_toprint', $data);
        //$this->load->view('part/left_sidebar', $data);
        $this->load->view('page/report_view_image', $data);
        $this->load->view('part/main_js_toprint');
      } else {
        $this->load->view('blok_view');
      }
  } //end

  function this_report2(){
      $my_img = imagecreate( 200, 80 );
      $background = imagecolorallocate( $my_img, 0, 0, 255 );
      $text_colour = imagecolorallocate( $my_img, 255, 255, 0 );
      $line_colour = imagecolorallocate( $my_img, 128, 255, 0 );
      imagestring( $my_img, 4, 30, 25, "text",
        $text_colour );
      imagesetthickness ( $my_img, 5 );
      imageline( $my_img, 30, 45, 165, 45, $line_colour );
      ob_start();
      imagepng( $my_img );
      $image_string = ob_get_flush();
      $imageb64 = base64_encode($image_string);
      imagecolordeallocate( $line_color );
      imagecolordeallocate( $text_color );
      imagecolordeallocate( $background );
      imagedestroy( $my_img );
      $url = "data:image/png;base64,".$imageb64;
      return $url;
      
  } //end

  function rstok(){ 
    $dep=$this->session->userdata('departement');
    $dt_kons = $this->data_model->get_record('tb_konstruksi');
    $kd_konstruksi = array();
    $dt_rjs = array(25, 30, 90, 85, 50, 45);
    $dt_pst = array(30, 30, 40, 65, 90, 80);
    $dt_smt = array(40, 90 ,90, 40, 45, 60);
    foreach($dt_kons->result() as $kons):
      $kd_konstruksi[]="'".$kons->kode_konstruksi."'";
    endforeach;
    $chart_kd_konstruksi = implode(", ", $kd_konstruksi);
    $chart_rjs = implode(", ", $dt_rjs);
    $chart_pst = implode(", ", $dt_pst);
    $chart_smt = implode(", ", $dt_smt);
    // echo $chart_kd_konstruksi."<br>";
    // echo $chart_rjs."<br>";
    // echo $chart_pst."<br>";
    // echo $chart_smt."<br>";
    if($this->session->userdata('hak') == 'Manager'){
      $data = array(
          'title' => 'Stok Barang - PPC Weaving',
          'sess_nama' => $this->session->userdata('nama'),
          'sess_id' => $this->session->userdata('id'),
          'sess_dep' => $this->session->userdata('departement'),
          'dt_table' => $dt_kons,
          'dtstok' => $this->data_model->get_byid('report_stok', ['departement'=>$dep]),
          'crt_kd' => $chart_kd_konstruksi,
          'crt_rjs' => $chart_rjs,
          'crt_pst' => $chart_pst,
          'crt_smt' => $chart_smt
      );
      $this->load->view('part/main_head', $data);
      $this->load->view('part/left_sidebar', $data);
      $this->load->view('page/report_stok', $data);
      $this->load->view('part/main_jss2');
    } else {
      $this->load->view('blok_view');
    }
  } //end

  function rpenjualan(){ 
    if($this->session->userdata('hak') == 'Manager'){
      $data = array(
          'title' => 'Manage Data User - PPC Weaving',
          'sess_nama' => $this->session->userdata('nama'),
          'sess_id' => $this->session->userdata('id'),
          'data_table' => $this->data_model->get_record('user'),
          'kode' => $this->data_model->get_record('tb_konstruksi')
      );
      $this->load->view('part/main_head', $data);
      $this->load->view('part/left_sidebar', $data);
      $this->load->view('page/report_penjualan_view', $data);
      $this->load->view('part/main_js_dttable');
    } else {
      $this->load->view('blok_view');
    }
  } //end

  function mnguser(){ 
    if($this->session->userdata('hak') == 'Manager'){
      $data = array(
          'title' => 'Manage Data User - PPC Weaving',
          'sess_nama' => $this->session->userdata('nama'),
          'sess_id' => $this->session->userdata('id'),
          'data_table' => $this->data_model->get_record('user')
      );
      $this->load->view('part/main_head', $data);
      $this->load->view('part/left_sidebar', $data);
      $this->load->view('page/user_view', $data);
      $this->load->view('part/main_js_dttable');
    } else {
      $this->load->view('blok_view');
    }
  } //end

  function logactv(){ 
    if($this->session->userdata('hak') == 'Manager'){
      $data = array(
          'title' => 'Aktivitas User - PPC Weaving',
          'sess_nama' => $this->session->userdata('nama'),
          'sess_id' => $this->session->userdata('id'),
          'data_table' => $this->data_model->sort_record('id_logprogram', 'log_program')
      );
      $this->load->view('part/main_head', $data);
      $this->load->view('part/left_sidebar', $data);
      $this->load->view('page/log_aktivitas', $data);
      $this->load->view('part/main_js_dttable');
    } else {
      $this->load->view('blok_view');
    }
  } //end

    
}
?>