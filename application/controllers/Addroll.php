<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Addroll extends CI_Controller
{
  function __construct()
  {
      parent::__construct();
      $this->load->model('data_model');
      date_default_timezone_set("Asia/Jakarta");
      if($this->session->userdata('login_form') != "rindangjati_sess"){
        redirect(base_url('block'));
      }
      
  }
   
  function index(){ 
      $this->load->view('blok_view');
  } //end

  function topackage(){
     $kdlist = $this->input->post('kdlistId');
     $txt = $this->input->post('txt');
     $siap_jual = $this->input->post('siap_dol');
     if($kdlist!="" AND $txt!="" AND $siap_jual!=""){
        $data = array(
            'title' => 'Tambah Roll ke Packing list ',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'sess_hak' => $this->session->userdata('hak'),
            'sess_hak' => $this->session->userdata('hak'),
            'sess_dep' =>$this->session->userdata('departement'),
            'kdlist' => $kdlist,
            'txt' => $txt,
            'siap_jual' => $siap_jual
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('new_page/edit_pkg_list2', $data);
        $this->load->view('part/main_js_dttable');
     } else {

     }
  } //end-

  function topackage2(){
     $kdlist = $this->input->post('kdlistId');
     $txt = $this->input->post('txt');
     $siap_jual = $this->input->post('siap_dol');
     if($kdlist!="" AND $txt!="" AND $siap_jual!=""){
        $data = array(
            'title' => 'Tambah Roll ke Packing list ',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'sess_hak' => $this->session->userdata('hak'),
            'sess_hak' => $this->session->userdata('hak'),
            'sess_dep' =>$this->session->userdata('departement'),
            'kdlist' => $kdlist,
            'txt' => $txt,
            'siap_jual' => $siap_jual
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('new_page/edit_pkg_list3', $data);
        $this->load->view('part/main_js_dttable');
     } else {

     }
  } //end-topackage2

  function topackage45(){
     $kdlist = $this->input->post('kdlistId');
     $txt = $this->input->post('kons2a');
     $siap_jual = $this->input->post('siap_dol');
     if($kdlist!="" AND $txt!="" AND $siap_jual!=""){
        $data = array(
            'title' => 'Tambah Roll ke Packing list dari excel',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'sess_hak' => $this->session->userdata('hak'),
            'sess_hak' => $this->session->userdata('hak'),
            'sess_dep' =>$this->session->userdata('departement'),
            'kdlist' => $kdlist,
            'txt' => $txt,
            'siap_jual' => $siap_jual
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('new_page/edit_pkg_list3', $data);
        $this->load->view('part/main_js_dttable');
     } else {

     }
  } //end-topackage45

  function proses(){
        $kdlist = $this->input->post('kodelist');
        $kode = $this->input->post('kdroll');
        $fr = $this->input->post('from');
        // echo "<pre>";
        // print_r($kode);
        // echo "</pre>";
        // echo "<pre>";
        // print_r($fr);
        // echo "</pre>";
        for ($i=0; $i < count($kode); $i++) { 
            if($fr[$i] == "1"){
                $this->data_model->updatedata('kode_roll',$kode[$i],'data_fol', ['posisi'=>$kdlist]);
                $pjg_roll = $this->data_model->get_byid('data_fol',['kode_roll'=>$kode[$i]])->row("ukuran");
                $jml_roll = $this->data_model->get_byid('new_tb_packinglist',['kd'=>$kdlist])->row("jumlah_roll");
                $panjang = $this->data_model->get_byid('new_tb_packinglist',['kd'=>$kdlist])->row("ttl_panjang");
                $new_roll = intval($jml_roll) + 1;
                $new_panjang = floatval($panjang) + floatval($pjg_roll);
                $this->data_model->updatedata('kd',$kdlist,'new_tb_packinglist',['jumlah_roll'=>$new_roll, 'ttl_panjang'=>round($new_panjang,2)]);
            }
            if($fr[$i] == "2"){
                $this->data_model->updatedata('kode_roll',$kode[$i],'data_fol_lama', ['lokasi'=>$kdlist]);
                $pjg_roll = $this->data_model->get_byid('data_fol_lama',['kode_roll'=>$kode[$i]])->row("ukuran_asli");
                $jml_roll = $this->data_model->get_byid('new_tb_packinglist',['kd'=>$kdlist])->row("jumlah_roll");
                $panjang = $this->data_model->get_byid('new_tb_packinglist',['kd'=>$kdlist])->row("ttl_panjang");
                $new_roll = intval($jml_roll) + 1;
                $new_panjang = floatval($panjang) + floatval($pjg_roll);
                $this->data_model->updatedata('kd',$kdlist,'new_tb_packinglist',['jumlah_roll'=>$new_roll, 'ttl_panjang'=>round($new_panjang,2)]);
            }
        }
        $this->session->set_flashdata('announce', 'Berhasil menyimpan');
        redirect(base_url('data/kode/'.$kdlist));
  } //end

  function proses2(){
        $cekidn = $this->input->post('cekidn');
        $idasli = $this->input->post('idasli');
        $kdlist = $this->input->post('kodelist');
        $ukurans = $this->input->post('ukurans');
        $jumlah_roll = 0; $panjang = 0;
        for ($i=0; $i <count($cekidn) ; $i++) { 
            if($cekidn[$i]==0){
                $this->data_model->updatedata('id_sl',$idasli[$i],'data_stok_lama',['posisi'=>'Samatex']);
            }
            if($cekidn[$i]==1){
                $jumlah_roll+=1;
                $panjang+=$ukurans[$i];
            }
        }
        $dtold = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$kdlist]);
        $jumlah_rollnew = floatval($dtold->row("jumlah_roll")) + $jumlah_roll;
        $total_panjang = floatval($dtold->row("ttl_panjang")) + $panjang;
        $this->data_model->updatedata('kd',$kdlist,'new_tb_packinglist',['jumlah_roll'=>$jumlah_rollnew,'ttl_panjang'=>round($total_panjang,2)]);
        $this->session->set_flashdata('announce', 'Berhasil menyimpan');
        redirect(base_url('data/kode/'.$kdlist));
  }//end
}