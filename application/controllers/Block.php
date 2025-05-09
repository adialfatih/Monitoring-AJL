<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Block extends CI_Controller
{
  function __construct()
  {
      parent::__construct();
      $this->load->model('data_model');
   //    if($this->session->userdata('status') != "login"){
			// redirect(base_url("auth_login"));
	    //}
  }
   
  function index(){
      $this->load->view('blok_view');
  }
   
  function tes(){
      $query = $this->data_model->get_byid('data_fol', ['konstruksi'=>'SM05B', 'jns_fold'=>'Finish', 'posisi'=>'Samatex']);
      echo "Jumlah data ".$query->num_rows() ." Roll<br>";
      foreach($query->result() as $val){
          $kdrol = $val->kode_roll;
          $cek = $this->data_model->get_byid('new_tb_isi', ['kode'=>$kdrol]);
          if($cek->num_rows() > 0){
              $pkg = $cek->row("kd");
              $ke = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$pkg])->row("kepada");
              $sj = $this->data_model->get_byid('new_tb_packinglist', ['kd'=>$pkg])->row("no_sj");
              echo "".$cek->row('kd')." -- : Kode Roll : ".$kdrol." Terjual ke  : ".$ke." -- SJ -- (".$sj.")<br>";
          }
          
      }
  }

  function owek(){
    echo "tes oke <hr>";
      $query = $this->db->query("SELECT kode_roll, COUNT(*) as jumlah_duplikat
      FROM new_roll_onpst
      GROUP BY kode_roll
      HAVING COUNT(*) > 1");
      foreach($query->result() as $val){
          $kd = $val->kode_roll;
          //echo $val->kode_roll." -- ".$val->jumlah_duplikat."<br>";
          if($val->jumlah_duplikat > 1){
              $qeq = $this->data_model->get_byid('new_roll_onpst', ['kode_roll'=>$kd]);
              foreach($qeq->result() as $ns => $val2){
                echo "-------<br>";
                  if($ns==0){

                  } else {
                      $iid = $val2->id_auto25;
                      $this->db->query("DELETE FROM new_roll_onpst WHERE id_auto25='$iid'");
                  }
                echo $val2->kode_roll."--$iid<br>";
                echo "-------<br>";
              }
          }
      }
  } //end

}
?>