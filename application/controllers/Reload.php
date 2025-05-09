<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reload extends CI_Controller
{
  function __construct()
  {
      parent::__construct();
      $this->load->model('data_model');
      date_default_timezone_set("Asia/Jakarta");
  }
   
  function index(){
      echo "Not Index...";
  }
  function cekupload(){
        $dep = $this->session->userdata('departement');
        $data = array(
            'title' => 'Upload untuk cek data',
            'sess_nama' => $this->session->userdata('nama'),
            'sess_id' => $this->session->userdata('id'),
            'dep_user' => $dep
        );
        $this->load->view('part/main_head', $data);
        $this->load->view('part/left_sidebar', $data);
        $this->load->view('baru/upload_andcek', $data);
        $this->load->view('part/main_js');
  }

  function smt(){
    $url = $this->uri->segment(3);
    if(empty($url) OR $url==""){
        $tgl = date('Y-m-d');
    } else {
        $tgl = $url;
    }
    echo "Inspect Grey Tanggal -- $tgl<br>";
    $ig = $this->db->query("SELECT SUM(ukuran_ori) as ori, SUM(ukuran_bs) as bs, SUM(ukuran_bp) as bp, tanggal FROM data_ig WHERE tanggal='$tgl'");
    echo "ukuran ori (".$ig->row('ori').") -- ukuran bs (".$ig->row('bs').") -- ukuran bp (".$ig->row('bp').")";
    echo "<hr>";

    echo "Folding Grey<br>";
    $fg = $this->db->query("SELECT SUM(ukuran) as ori,jns_fold,tgl FROM data_fol WHERE jns_fold='Grey' AND tgl='$tgl'");
    echo "ukuran ori (".$fg->row('ori').")";
    echo "<hr>";

    echo "Inspect Finish<br>";
    $iff = $this->db->query("SELECT tgl_potong, SUM(ukuran_ori) as ori, SUM(ukuran_bs) as bs, SUM(ukuran_crt) as crt, SUM(ukuran_bp) as bp FROM data_if WHERE tgl_potong='$tgl'");
    echo "ukuran ori (".round($iff->row('ori'),2).") -- ukuran bs (".round($iff->row('bs'),2).")-- ukuran crt (".round($iff->row('crt'),2).") -- ukuran bp (".round($iff->row('bp'),2).")";
    echo "<hr>";

    echo "Folding Finish<br>";
    $ff = $this->db->query("SELECT SUM(ukuran) as ori,jns_fold,tgl FROM data_fol WHERE jns_fold='Finish' AND tgl='$tgl'");
    echo "ukuran ori (".$ff->row('ori').")";
    echo "<hr>";


    echo "<strong>Stok Lama</strong><br>";


    echo "SL Folding Grey<br>";
    $fg1 = $this->db->query("SELECT SUM(ukuran_asli) as ori,folding,tanggal FROM data_fol_lama WHERE folding='Grey' AND tanggal='$tgl'");
    echo "ukuran ori (".$fg1->row('ori').")";
    echo "<hr>";

    echo "SL Inspect Finish<br>";
    $iff2 = $this->db->query("SELECT tgl, SUM(panjang) as ori, SUM(bs) as bs, SUM(crt) as crt, SUM(bp) as bp FROM data_if_lama WHERE tgl='$tgl'");
    echo "ukuran ori (".round($iff2->row('ori'),2).") -- ukuran bs (".round($iff2->row('bs'),2).")-- ukuran crt (".round($iff2->row('crt'),2).") -- ukuran bp (".round($iff2->row('bp'),2).")";
    echo "<hr>";

    echo "SL Folding Finish<br>";
    $ff1 = $this->db->query("SELECT SUM(ukuran_asli) as ori,folding,tanggal FROM data_fol_lama WHERE folding='Finish' AND tanggal='$tgl'");
    echo "ukuran ori (".$ff1->row('ori').")";
    echo "<hr>";

    echo "<strong>TOTAL PRODUKSI PADA TANGGAL ".$tgl."</strong>";
    echo "<div style='display:flex;'>";

    echo '<div style="width:200px;display:flex;flex-direction:column;border:1px solid #000;justify-content:center;align-items:center;">';
    echo "<span>Inspect Grey</span>";
    echo "<span>".$ig->row('ori')."</span>";
    echo "</div>";

    echo '<div style="width:200px;display:flex;flex-direction:column;border:1px solid #000;justify-content:center;align-items:center;">';
    echo "<span>Folding Grey</span>";
    $ttl_fg = $fg1->row('ori') + $fg->row('ori');
    echo "<span>".$ttl_fg."</span>";
    echo "</div>";

    echo '<div style="width:200px;display:flex;flex-direction:column;border:1px solid #000;justify-content:center;align-items:center;">';
    echo "<span>Inspect Finish</span>";
    $ttl_if = $iff->row('ori') + $iff2->row('ori');
    echo "<span>".$ttl_if."</span>";
    echo "</div>";

    echo '<div style="width:200px;display:flex;flex-direction:column;border:1px solid #000;justify-content:center;align-items:center;">';
    echo "<span>Folding Finish</span>";
    $ttl_ff = $ff->row('ori') + $ff1->row('ori');
    echo "<span>".$ttl_ff."</span>";
    echo "</div>";

    echo "</div>";

  } //
    
}
?>