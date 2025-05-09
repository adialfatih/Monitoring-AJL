<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Apldtcs extends CI_Controller
{
  function __construct()
  {
      parent::__construct();
      $this->load->model('data_model');
      date_default_timezone_set("Asia/Jakarta");
      
      
  }
   
  function index(){ 
        $this->load->view('Apldtcs_view');
  } //end
  
    function dtview(){
		$tgl = $this->input->post('proses');
		?>
		<thead>
        <tr>
            <th>No.</th>
            <th>Tanggal</th>
            <th>Operator</th>
            <th>Grub</th>
            <th>Mesin</th>
            <th>Total Produksi</th>
        </tr></thead>
		<tbody id="idbodyoke">
		<?php
		$dtop = $this->db->query("SELECT * FROM operator_produksi");
            $n=1;
            foreach($dtop->result() as $tes):
                $idopt = $tes->id_opprod;
                echo "<tr>";
                echo "<td>".$n."</td>";
                echo "<td>".$tgl."</td>";
                if($tes->reliver == "n"){
                echo "<td>".$tes->nama_operator."</td>";
                } else {
                echo "<td style='color:red;'>".$tes->nama_operator." (Reliver)</td>";
                }
                echo "<td>".$tes->grub."</td>";

                $querys = $this->db->query("SELECT * FROM v_prodmesin WHERE tgl_produksi = '$tgl' AND id_opprod = '$idopt'");
                if($querys->num_rows() > 0){
                    echo "<td>".$querys->row('no_mesin')."</td>";
                    echo "<td>".$querys->row('produksi')."</td>";
                } else {
                echo "<td>Tidak ditemukan Data</td><td></td>"; }
                echo "</tr>";
                $n++;
            endforeach;
		echo "</tbody>";
	}

    function lockajl(){
            $cek_mesin = $this->db->query("SELECT * FROM table_mesin ORDER BY no_mesin ");
            $cek_data = $this->data_model->get_byid('produksi_mesin_ajl',['proses'=>'onproses']);
            $ar = ["01"=>"Jan", "02"=>"Feb","03"=>"Mar","04"=>"Apr","05"=>"Mei","06"=>"Jun","07"=>"Jul","08"=>"Ags","09"=>"Sep","10"=>"Okt","11"=>"Nov","12"=>"Des",];
            foreach ($cek_mesin->result() as $no => $value):
                $mc = $value->no_mesin;
                $ajl = $this->db->query("SELECT * FROM produksi_mesin_ajl WHERE no_mesin='$mc' ORDER BY id_produksi_mesin DESC LIMIT 1")->row_array();
                $id_ajl = $ajl['id_produksi_mesin'];
                $idbeam = $ajl['id_beam_sizing'];
                $siz = $this->data_model->get_byid('v_beam_sizing2', ['id_beam_sizing'=>$idbeam])->row_array();
                $beamsizing = $siz['kode_beam'];
                $oka = $siz['oka'];
                $status = $ajl['proses'];
                $start_mesin = $this->data_model->get_byid('produksi_mesin_ajl_rwyt', ['id_produksi_mesin'=>$id_ajl, 'jenis_riwayat'=>'Start Mesin']);
                    if($start_mesin->num_rows() == 1){
                        $k = explode(' ', $start_mesin->row("timestamprwyt"));
                        $t = explode('-', $k[0]);
                        $printTgl = $t[2]." ".$ar[$t[1]]." ".$t[0];
                        $w = explode(':', $k[1]);
                        $prinJam = $w[0].":".$w[1];
                        $naik_beam = $printTgl.", ".$prinJam;
                    } else {
                        $naik_beam = "<span style='font-size:12px;color:red;'>Tidak Terdeteksi</span>";
                    }
                $potongan_kain = $this->data_model->get_byid('produksi_mesin_ajl_potongan', ['id_produksi_mesin'=>$id_ajl]);
                $jumlah_potongan = $potongan_kain->num_rows();
                if($jumlah_potongan > 0){
                    $_nilaipot = 0;
                    foreach($potongan_kain->result() as $vp){
                        $_nilaipot+= $vp->ukuran_meter;
                    }
                    $potn = "ada";
                    $sisa_lusi = $ajl['pjg_lusi'] - $_nilaipot;
                } else {
                    $_nilaipot = 0;
                    $potn = "tidak ada";
                    $sisa_lusi = $ajl['pjg_lusi'];
                }
                $produksi_hari_itu = $this->db->query("SELECT id_loom,no_mesin,produksi FROM loom_counter WHERE no_mesin='$mc' ORDER BY id_loom DESC LIMIT 1")->row("produksi");
                $pjg_lusi_10persen = ($sisa_lusi * 10) / 100;
                $rumus1 = $sisa_lusi - $pjg_lusi_10persen;
                $rumus2 = $rumus1 - $produksi_hari_itu;
                $rumus3 = $rumus2 / 245;
                if($status == "onproses"){
                    $status_mesin = "ON";
                } else {
                    $status_mesin = "OFF";
                }
                $number = $no+1;
                echo $number." - ".$mc." - ".$ajl['konstruksi']." - ".$oka." - ".$beamsizing." - ".$ajl['pjg_lusi']." - ".$jumlah_potongan." - ".$sisa_lusi." - ".$produksi_hari_itu."<br>";
                $this->data_model->saved('a_lock_mesin',[
                    'status_mesin'          => $status_mesin,
                    'nomesin'               => $mc,
                    'konstruksi'            => $ajl['konstruksi'],
                    'oka'                   => $oka,
                    'beam'                  => $beamsizing,
                    'lusi'                  => $ajl['pjg_lusi'],
                    'jumlah_potongan'       => $jumlah_potongan,
                    'total_pjg_potongan'    => $_nilaipot,
                    'sisalusi'              => $sisa_lusi,
                    'ket'                   => '',
                    'tgl_lock'              => date('Y-m-d H:i:s')
                ]);
            endforeach;
    } //end
    function view_lock(){
        $this->load->view('page/data_mesin_ajl3', $data);
    } //edn

    function getoka(){
        $uri = $this->uri->segment(3);
        echo "OKA : ". $uri."<br>";
        $sizing = $this->data_model->get_byid('produksi_sizing',['oka'=>$uri]);
        echo "Total Pemakaian Pakan : <span id='tesPakan'>0</span><br>";
        $totalPakan=0;
        if($sizing->num_rows() == 1){
            $id_sizing = $sizing->row("id_sizing");
            $beam = $this->data_model->get_byid('beam_sizing',['id_sizing'=>$id_sizing]);
            echo "Beam dari OKA $uri<br>";
            $no=1;
            foreach($beam->result() as $val){
                $id_beam_sizing = $val->id_beam_sizing;
                echo "--".$no.".-- Kode_Beam (".$val->kode_beam.") Konstruksi (".$val->konstruksi.") Ukuran (".$val->ukuran_panjang.")<br>";
                $mc = $this->data_model->get_byid('produksi_mesin_ajl',['id_beam_sizing'=>$id_beam_sizing]);
                if($mc->num_rows() == 1){
                    $idmesinjalan = $mc->row("id_produksi_mesin");
                    $jumlahPakan = $this->db->query("SELECT SUM(berat_cones) AS kg FROM baku_ajl WHERE id_produksi_mesin='$idmesinjalan'")->row("kg");
                    $jumlahPakan = round($jumlahPakan,2);
                    $totalPakan+=$jumlahPakan;
                    if($jumlahPakan == floor($jumlahPakan)){
                        $jumlahPakan2 = number_format($jumlahPakan,0,'.',',');
                    } else {
                        $jumlahPakan2 = number_format($jumlahPakan,2,'.',',');
                    }
                    echo "--------Di proses mesin : ".$mc->row('no_mesin')."- Pemakaian pakan (".$jumlahPakan2." Kg)<br>";
                } else {
                    echo "--------Tidak ditemukan proses------<br>";
                }
                echo "<br>";
                echo "---------------------------------------------------------------<br>";
                $no++;
            }
            //echo "Pemakaian Pakan Total : $totalPakan";
            ?>
            <script>document.getElementById('tesPakan').innerHTML = '<?=$totalPakan;?> KG';</script>
            <?php
        } else {
            echo "OKA TIDAK DITEMUKAN";
        }
    } //end
}