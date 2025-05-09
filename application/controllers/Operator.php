<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operator extends CI_Controller
{
  function __construct()
  {
      parent::__construct();
      $this->load->model('data_model');
      $this->load->model('Dbserver_model');
      date_default_timezone_set("Asia/Jakarta");
    //   if($this->session->userdata('login_form') != "rindangjati_weaving_sess"){
    //     redirect(base_url('login'));
    //   }
  }
   
  function index(){ 
        $this->load->view('operator/login_page');
  } //en
  public function get_data() {
        $list = $this->Dbserver_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $user) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $user->id_user;
            $row[] = $user->nama_user;
            $row[] = $user->email_user;
            $row[] = $user->jns_kel;
            $row[] = $user->st_verif;

            // Tambahkan kolom aksi jika diperlukan

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Dbserver_model->count_all(),
            "recordsFiltered" => $this->Dbserver_model->count_filtered(),
            "data" => $data,
        );

        // output to json format
        echo json_encode($output);
  }
  function login(){ 
        $this->load->view('operator/login_page');
  } //en
  function mesin(){ 
        if($this->session->userdata('login_form_op') != "rindangjati_sess"){
            redirect(base_url('operator/login'));
        }
            $uri = $this->uri->segment(3);
            if($uri==""){
                $this->load->view('operator/monitorin_mesin');
            } elseif($uri=="naikbeam"){
                $this->load->view('operator/start_mesin');
            } else  {
                $cek = $this->data_model->get_byid('produksi_mesin_ajl', ['sha1(id_produksi_mesin)'=>$uri]);
                if($cek->num_rows() == 1){
                    $this->load->view('operator/update_mesin_data');
                } else {
                    $this->load->view('operator/monitorin_mesin');
                }
            }
  } //en

  function produksimesin(){
        if($this->session->userdata('login_form_op') != "rindangjati_sess"){
            redirect(base_url('operator/login'));
        }
        $this->load->view('operator/produksi_mesin');
  }
  function showproduksimesin(){
        if($this->session->userdata('login_form_op') != "rindangjati_sess"){
            redirect(base_url('operator/login'));
        }
        $this->load->view('operator/produksi_mesin_show');
  }
  function oploom(){
        if($this->session->userdata('login_form_op') != "rindangjati_sess"){
            redirect(base_url('operator/login'));
        }
        $this->load->view('operator/operator_mesin_loom');
  }
  function addoploom(){
        $this->load->view('operator/addoperator_mesin_loom');
  }
  function sizing(){
        if($this->session->userdata('login_form_op') != "rindangjati_sess"){
            redirect(base_url('operator/login'));
        }
        $this->load->view('operator/sizing_view_opt');
  } //end
  function inputsizing(){
        if($this->session->userdata('login_form_op') != "rindangjati_sess"){
            redirect(base_url('operator/login'));
        }
        $this->load->view('operator/sizing_input_opt');
  } //edn
  function produksi(){
        $uri = $this->uri->segment(3);
        $id = $this->uri->segment(4);
        if($uri == "sizing"){
            $cekId = $this->data_model->get_byid('produksi_sizing', ['id_sizing'=>$id]);
            if($cekId->num_rows() == 1){
                $this->load->view('operator/sizing_vdata_opt');
            } else {
                redirect(base_url('input-sizing-opt'));
            }
        }
  }

  function proseslogin(){
        $username = $this->data_model->clean($this->input->post('user'));
        $pass = $this->input->post('pass');
        $cek = $this->data_model->get_byid('operator_mesin', ['username'=>$username, 'password'=>sha1($pass)]);
        if($cek->num_rows() == 1){
            $data_session = array(
                'id' => $cek->row('id_op'),
                'nama'  => $cek->row('nama_lengkap'),
                'username'=> $cek->row('username'),
                'password' => $cek->row('password'),
                'akses' => $cek->row('akses_user'),
                'login_form_op'=> 'rindangjati_sess'
            );
            $this->session->set_userdata($data_session);
            redirect(base_url('operator/mesin'));
        } else {
            echo "<div style='width:100%;height:100vh;display:flex;justify-content:center;align-items:center;font-size:2em;flex-direction:column;'>
            Login Gagal
            <p>&nbsp;</p>
            <a href='".base_url('operator')."' style='text-decoration:none;'>Login Ulang</a>
            </div>";
        }
  } //end

  function inputloom(){
        if($this->session->userdata('login_form_op') != "rindangjati_sess"){
            redirect(base_url('operator/login'));
        }
        $this->load->view('operator/input_loom');
  } //end
  function inputloom2(){
        if($this->session->userdata('login_form_op') != "rindangjati_sess"){
            redirect(base_url('operator/login'));
        }
        $this->load->view('operator/input_loom2');
  } //end

  function dataloom(){
        if($this->session->userdata('login_form_op') != "rindangjati_sess"){
            redirect(base_url('operator/login'));
        }
        $this->load->view('operator/data_loom');
  }

  function updateloom(){
        if($this->session->userdata('login_form_op') != "rindangjati_sess"){
            redirect(base_url('operator/login'));
        }
        $uri = $this->uri->segment(3);
        if(!empty($uri) AND $uri!=""){
            $cek = $this->data_model->get_byid('loom_counter', ['sha1(id_loom)'=>$uri]);
            if($cek->num_rows() == 1){
            $this->load->view('operator/update_loom');
            } else {
                redirect(base_url('data-loom'));
            }
        } else {
            redirect(base_url('data-loom'));
        }
  }
  function updateloom2(){
        if($this->session->userdata('login_form_op') != "rindangjati_sess"){
            redirect(base_url('operator/login'));
        }
        $uri = $this->uri->segment(3);
        if(!empty($uri) AND $uri!=""){
            $cek = $this->data_model->get_byid('loom_counter2', ['sha1(id_loom2)'=>$uri]);
            if($cek->num_rows() == 1){
            $this->load->view('operator/update_loom2');
            } else {
                redirect(base_url('data-loom'));
            }
        } else {
            redirect(base_url('data-loom'));
        }
  } //end

  function uploas(){
        echo form_open_multipart('phpsp/tes',array('name' => 'spreadsheet'));
        ?>
        <input class="form-control" style="width:350px;" placeholder="Upload List" type="file" name="upload_file" required>
        <input type="submit" value="Submit">
        <?php
        echo form_close();
  } //end


}