<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prosesajax extends CI_Controller
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
  
  function loadPemakaianBakuWarping(){
        $id = $this->input->post('id');
        $cek = $this->data_model->get_byid('baku_warping', ['id_produksi_warping'=>$id]);
        if($cek->num_rows() > 0 ){
            $no=1;
            foreach($cek->result() as $val):
                $kd = $val->kode_karung;
                $nama_benang = $this->data_model->get_byid('karung_benang', ['kode_karung'=>$kd])->row("nama_benang");
                echo "<tr>";
                echo "<td style='padding:5px;text-align:center;'>".$no."</td>";
                echo "<td style='padding:5px;'>".$nama_benang."</td>";
                echo "<td style='padding:5px;text-align:center;'>".$val->kode_karung."</td>";
                echo "<td style='padding:5px;text-align:center;'>".$val->jumlah_cones."</td>";
                echo "<td style='padding:5px;text-align:center;'>".$val->berat_cones." Kg</td>";
                ?>
                    <td style="padding:5px;text-align:center;">
                        <span style="font-size:10px;padding:3px 10px;background:red;color:#fff;border-radius:5px;cursor:pointer;" onclick="deletKarung('<?=$val->id_bakuwarping;?>','<?=$val->kode_karung;?>','<?=$val->jumlah_cones;?>')">Hapus</span>
                    </td>
                <?php
                echo "</tr>";
                $no++;
            endforeach;
            $total_cones = $this->db->query("SELECT SUM(jumlah_cones) AS jml FROM baku_warping WHERE id_produksi_warping='$id'")->row("jml");
            $berat_cones = $this->db->query("SELECT SUM(berat_cones) AS jml FROM baku_warping WHERE id_produksi_warping='$id'")->row("jml");
            echo "<tr>";
            echo "<th colspan='3' style='padding:5px;'>Total</th>";
            echo "<th style='padding:5px;'>".$total_cones."</th>";
            echo "<th style='padding:5px;'>".round($berat_cones,2)." Kg</th>";
            echo "<th style='padding:5px;'></th>";
            echo "</tr>";
        } else {
            echo "<tr>";
            echo "<td colspan='5' style='padding:5px;color:red;'>Tidak Memiliki Data Bahan Baku / Belum Terdata</td>";
            echo "</tr>";
        }
  } //end

  function cariKarung(){ 
        $kode = $this->input->post('kode');
        $ex = explode(' - ', $kode);
        $cek = $this->data_model->get_byid('karung_benang', ['kode_karung'=>$ex[1]]);
        if($cek->num_rows() == 1){
            $jumlah_cones = $cek->row("jumlah_cones");
            $jumlah_terpakai = $cek->row("cones_terpakai");
            $sisa_cones = intval($jumlah_cones) - intval($jumlah_terpakai);
            if($sisa_cones > 0) {
                echo json_encode(array("statusCode"=>200, "fix"=>"yes", "sisaCones"=>$sisa_cones, "kodeKarung"=>$ex[1]));
            } else {
                echo json_encode(array("statusCode"=>200, "fix"=>"no", "psn"=>"Cones Telah Terpakai Semua"));
            }
            
        } else {
            echo json_encode(array("statusCode"=>400, "fix"=>"error", "psn"=>"erorr"));
        }
  } //end 

  function submitCones(){
        $total_baku_cones = $this->db->query("SELECT SUM(jumlah_cones) AS jml FROM baku_warping WHERE id_produksi_warping='$id'")->row("jml");
        $id = $this->input->post('idwarping');
        $jmlCreel = $this->input->post('jmlCreel');
        $kodeKarung = $this->input->post('kodeKarung');
        $jumlahCones = $this->input->post('jumlahCones');
        $cones_akan_pakai = $total_baku_cones + $jumlahCones;
        if($cones_akan_pakai > $jmlCreel){
            echo json_encode(array("statusCode"=>400, "fix"=>"error", "psn"=>"Jumlah pemakaian cones lebih dari jumlah creel"));
        } else {
        $cekKarung = $this->data_model->get_byid('karung_benang', ['kode_karung'=>$kodeKarung]);
        if($cekKarung->num_rows() == 1){
            $id_karung = $cekKarung->row("id_karung");
            $tb_jmlCones = $cekKarung->row("jumlah_cones");
            $tb_beratKarung = $cekKarung->row("berat_karung");
            $tb_beratPerCones = floatval($tb_beratKarung) / intval($tb_jmlCones);
            $tb_beratPerCones2 = round($tb_beratPerCones, 2);
            $tb_pakaiCones = $cekKarung->row("cones_terpakai");
            $sisa_cones = intval($tb_jmlCones) - intval($tb_pakaiCones);
            if($sisa_cones > 0){
                if($sisa_cones > $tb_jmlCones OR $jumlahCones > $sisa_cones){
                    $txt = "Cones hanya tersisa sejumlah ".$sisa_cones."";
                    echo json_encode(array("statusCode"=>400, "fix"=>"error", "psn"=>$txt));
                } else {
                    $total_berat_cones = floatval($tb_beratPerCones2) * $jumlahCones;
                    $this->data_model->saved('baku_warping', [
                        'id_produksi_warping' => $id,
                        'kode_karung' => $kodeKarung,
                        'berat_cones' => round($total_berat_cones,2),
                        'jumlah_cones' => $jumlahCones
                    ]);
                    $sisa_pakai_cones = intval($tb_jmlCones) - intval($jumlahCones);
                    if($sisa_pakai_cones > 0){
                        $upcones = intval($tb_pakaiCones) + intval($jumlahCones);
                        if($upcones >= $tb_jmlCones){
                            $this->data_model->updatedata('id_karung',$id_karung,'karung_benang', [
                                'status_karung' => 'habis', 'cones_terpakai' => $upcones]);
                        } else {
                        $this->data_model->updatedata('id_karung',$id_karung,'karung_benang', [
                             'cones_terpakai' => $upcones
                        ]); }
                    } else {
                        //$upcones = intval($tb_jmlCones) + intval($jumlahCones);
                        $this->data_model->updatedata('id_karung',$id_karung,'karung_benang', [
                            'status_karung' => 'habis', 'cones_terpakai' => $jumlahCones
                        ]);
                    }
                    $txt = "Menambahkan ".$jumlahCones." Cones";
                    echo json_encode(array("statusCode"=>200, "fix"=>"error", "psn"=>$txt));
                }
            } else {
                $this->data_model->updatedata('kode_karung',$kodeKarung,'karung_benang',['status_karung'=>'habis']);
                $txt = "Cones di dalam karung ".$kodeKarung." Habis";
                echo json_encode(array("statusCode"=>400, "fix"=>"error", "psn"=>$txt));
            }
        } else {
            echo json_encode(array("statusCode"=>400, "fix"=>"error", "psn"=>"Kode Karung Tidak Ditemukan"));
        }
    } 
  } //end

  function kembalikancones(){
        $id = $this->input->post('id');
        $kode = $this->input->post('kode');
        $cones = $this->input->post('cones');
        $this->db->query("DELETE FROM baku_warping WHERE id_bakuwarping = '$id'");
        $karung = $this->data_model->get_byid('karung_benang', ['kode_karung'=>$kode]);
        $cones_terpakai = $karung->row("cones_terpakai");
        $new_cones = intval($cones_terpakai) - intval($cones);
        $this->data_model->updatedata('kode_karung',$kode,'karung_benang',['status_karung'=>'masih','cones_terpakai'=>$new_cones]);
        echo "success";
  } //end 

  function delBeamWarpingOnSizing(){
        $kode = $this->input->post('kode');
        $this->data_model->updatedata('id_beamwar',$kode,'beam_warping',['kode_proses_sizing'=>'null']);
        echo "Success";
  }

  function showBeamWarpingOnSizing(){
        $kode = $this->input->post('kode');
        $cek = $this->data_model->get_byid('v_beam_warping2', ['kode_proses_sizing'=>$kode]);
        if($cek->num_rows() > 0){
            ?>
            <label for="okaid">&nbsp;</label>
            <table class="table" border="1" style="width:600px;border-collapse:collapse;margin-top:20px;border:2px solid #ccc;">
                <thead>
                    <tr style="background:#dae0eb;">
                        <th style="padding:5px;">No</th>
                        <th style="padding:5px;">Kode/No Beam</th>
                        <th style="padding:5px;">Ukuran</th>
                        <th style="padding:5px;">Mesin</th>
                        <th style="padding:5px;">Produksi</th>
                        <th style="padding:5px;">#</th>
                    </tr>
                </thead>
                <tbody>
            <?php $no=1;
                foreach($cek->result() as $val){
                    $ex = explode('-', $val->tgl_produksi);
                    $print = $ex[2]."-".$this->data_model->printBln2($ex[1])."-".$ex[0];
                    echo "<tr>";
                    echo "<td style='padding:5px;'>".$no."</td>";
                    echo "<td style='padding:5px;'>".$val->kode_beam."</td>";
                    echo "<td style='padding:5px;'>".$val->ukuran_panjang."</td>";
                    echo "<td style='padding:5px;'>".$val->jenis_mesin."</td>";
                    echo "<td style='padding:5px;'>".$print."</td>";
                    ?>
                        <td style="padding:5px;text-align:center;font-size:12px;">
                            <a href="javascript:void(0);" style="color:red;text-decoration:none;" onclick="delbeam('<?=$val->id_beamwar;?>')">Hapus</a>
                        </td>
                    <?php
                    //echo "<td style='padding:5px;text-align:center;font-size:12px;color:red;'>Hapus</td>";
                    echo "</tr>";
                    $no++;
                }
            ?>
                </tbody>
            </table>
            <?php
        } else {
            echo "";
        }
  } //end

  function changeBeamWarping(){
        $kode = $this->input->post('kode');
        $kode_proses = $this->input->post('kode2');
        $ex = explode(' - ', $kode);
        $kode_beam = $ex[1];
        $idwarping = $ex[2];
        $cek = $this->data_model->get_byid('beam_warping', ['id_produksi_warping'=>$idwarping, 'kode_beam'=>$kode_beam]);
        $id_beamwar = $cek->row("id_beamwar");
        $kode_proses_sizing = $cek->row("kode_proses_sizing");
        if($kode_proses_sizing == "null"){
            $this->data_model->updatedata('id_beamwar',$id_beamwar,'beam_warping',['kode_proses_sizing'=>$kode_proses]);
            echo json_encode(array("statusCode"=>200, "fix"=>"success", "psn"=>"success"));
        } else {
            echo json_encode(array("statusCode"=>400, "fix"=>"error", "psn"=>"Beam Warping Sudah di Proses"));
        }
  } //end

  function changeBeamSizing(){
        $kode = $this->input->post('kode');
        $ex = explode(' - ', $kode);
        $kons = $ex[2];
        $kode_beam = $ex[1];
        $ex2 = explode('/', $ex[0]);
        $tgl = $ex2[2]."-".$ex2[1]."-".$ex2[0];
        $cek = $this->data_model->get_byid('v_beam_sizing2', ['tgl_produksi'=>$tgl, 'konstruksi'=>$kons, 'kode_beam'=>$kode_beam]);
        if($cek->num_rows() == 1){
            $id = $cek->row("id_beam_sizing");
            $pjglus = $cek->row("ukuran_panjang");
            echo json_encode(array("statusCode"=>200, "fix"=>"success", "psn"=>$id, "kons"=>$kons, "pjgLusi"=>$pjglus));
        } else {
            echo json_encode(array("statusCode"=>400, "fix"=>"erorr", "psn"=>$ex[0]));
        }
  } //end
  function ceknomormesin(){
        $kode = $this->input->post('kode');
        $cek_mesin = $this->data_model->get_byid('produksi_mesin_ajl', ['no_mesin'=>$kode,'proses'=>'onproses']);
        if($cek_mesin->num_rows() == 0){
            echo json_encode(array("statusCode"=>200, "fix"=>"oke"));
        } else {
            echo json_encode(array("statusCode"=>400, "fix"=>"erorr"));
        }
  }//end
  function cekpotongan(){
        $kode = $this->input->post('kode');
        $data = $this->data_model->get_byid('produksi_mesin_ajl_potongan', ['id_produksi_mesin'=>$kode]);
        if($data->num_rows() > 0){
            $no=1;
            foreach($data->result() as $val):
                $ex = explode('-', $val->tgl_potong);
                $printBlun = $ex[2]."-".$this->data_model->printBln2($ex[1])."-".$ex[0];
                echo "<tr>";
                echo '<td style="padding:5px;">'.$no.'</td>';
                echo '<td style="padding:5px;">'.$val->no_beam.'</td>';
                echo '<td style="padding:5px;">'.$val->konstruksi.'</td>';
                echo '<td style="padding:5px;text-align:center;">'.$printBlun.'</td>';
                echo '<td style="padding:5px;text-align:center;">'.$val->shift.'</td>';
                echo '<td style="padding:5px;text-align:center;">'.$val->ukuran_meter.'</td>';
                echo '<td style="padding:5px;">'.$val->operator.'</td>';
                ?>
                <td style="padding:5px;text-align:center;font-size:13px;">
                    <a href="javascript:;" onclick="delpot('<?=$val->id_potongan;?>')" style="color:red;text-decoration:none;">Hapus</a>
                </td>
                <?php
                //echo '<td style="padding:5px;text-align:center;color:red;font-size:13px;cursor:pointer;">Hapus</td>';
                echo "</tr>";
                $no++;
            endforeach;
        } else {
            echo "<tr><td colspan='7' style='padding:5px;color:red;'>Belum Ada Potongan</td></tr>";
        }
  } //end

  function addpotongan(){
        $id = $this->input->post('id');
        $id_beam_sizing = $this->input->post('id_beam_sizing');
        $beams = $this->input->post('beams');
        $kons = $this->input->post('kons');
        $ukr = $this->input->post('ukr');
        $tgl = $this->input->post('tgl');
        $sift = $this->input->post('sift');
        $waktu = date('Y-m-d H:i:s');
        $opt2 = $this->input->post('opt');
        $opt = strtolower($opt2);
        
        if($id!="" AND $kons!="" AND $ukr!="" AND $tgl!="" AND $sift!="" AND $opt!="" AND $beams!=""){
            $this->data_model->saved('produksi_mesin_ajl_potongan', [
                'id_produksi_mesin' => $id,
                'id_beam_sizing' => $id_beam_sizing,
                'tgl_potong' => $tgl,
                'waktu_potong' => $waktu,
                'ukuran_meter' => $ukr,
                'shift' => $sift,
                'operator' => ucfirst($opt),
                'konstruksi' => strtoupper($kons),
                'no_beam' => $beams
            ]);
            echo json_encode(array("statusCode"=>200, "fix"=>"success"));
        } else {
            echo json_encode(array("statusCode"=>400, "fix"=>"erorr"));
        }
  } //edn

  function potongandel(){
        $id = $this->input->post('id');
        $this->db->query("DELETE FROM produksi_mesin_ajl_potongan WHERE id_potongan='$id'");
        echo "success";
  } //end

  function cekBakuAjl(){
        $id = $this->input->post('kode');
        $cek = $this->data_model->get_byid('baku_ajl', ['id_produksi_mesin'=>$id]);
        if($cek->num_rows() > 0){
            $no=1;
            foreach($cek->result() as $mk){
                echo "<tr>";
                echo "<td style='padding:5px;text-align:center;'>".$no."</td>";
                if($mk->jns_cones == "new"){
                    echo "<td style='padding:5px;text-align:center;'>Cones Baru</td>";
                } else {
                    echo "<td style='padding:5px;text-align:center;'>Cones Sisa Warping</td>";
                }
                echo "<td style='padding:5px;text-align:center;'>".$mk->kode."</td>";
                echo "<td style='padding:5px;text-align:center;'>".$mk->jumlah_cones."</td>";
                echo "<td style='padding:5px;text-align:center;'>".$mk->berat_cones."</td>";
                ?>
                <td style="padding:5px;text-align:center;">
                    <a href="javascript:;" style="text-decoration:none;color:red;" onclick="delbakupakan('<?=$mk->id_bakuajl;?>')">Del</a>
                </td>
                <?php
                //echo "<td style='padding:5px;text-align:center;color:red;'>Del</td>";
                echo "</tr>";
                $no++;
            }
        } else {
            echo "<tr>";
            echo "<td style='padding:5px;' colspan='5'>Belum ada data</td>";
            echo "</tr>";
        }
  } //end 

  function savedBakuAjl(){
        $id = $this->input->post('idprod');
        $kode = $this->input->post('codeAll');
        $jnsCones = $this->input->post('setCones');
        $jml = $this->input->post('jmlNewCones');
        $brt = $this->input->post('brtCones');
        if($kode!="" AND $jnsCones!="" AND $jml!="" AND $brt!=""){
            
            if($jnsCones == "new"){
                $cekKarung = $this->data_model->get_byid('karung_benang', ['kode_karung'=>$kode]);
                if($cekKarung->num_rows() == 1){
                    $jumlah_cones = $cekKarung->row("jumlah_cones");
                    $cones_terpakai = $cekKarung->row("cones_terpakai");
                    $sisa_cones = $jumlah_cones - $cones_terpakai;
                    if($sisa_cones > 0) {
                        $cones_akan_pakai = $jml + $cones_terpakai;
                        if($cones_akan_pakai <= $jumlah_cones){
                            $this->data_model->saved('baku_ajl', [
                                'kode' => $kode,
                                'jumlah_cones' => $jml,
                                'berat_cones' => $brt,
                                'jns_cones' => $jnsCones,
                                'operator' => $this->session->userdata('nama'),
                                'id_produksi_mesin' => $id
                            ]);
                            if($cones_akan_pakai == $jumlah_cones){
                                $this->data_model->updatedata('kode_karung',$kode,'karung_benang',['cones_terpakai'=>$cones_akan_pakai, 'status_karung'=>'habis']);
                            } else {
                                $this->data_model->updatedata('kode_karung',$kode,'karung_benang',['cones_terpakai'=>$cones_akan_pakai]);
                            }
                            
                            echo json_encode(array("statusCode"=>200, "fix"=>"success"));
                        } else {
                            echo json_encode(array("statusCode"=>400, "fix"=>"Jumlah Cones tidak sesuai"));
                        }
                    } else {
                        echo json_encode(array("statusCode"=>400, "fix"=>"Tidak ada cones tersisa"));
                    }
                } else {
                    echo json_encode(array("statusCode"=>400, "fix"=>"Kode Karung Tidak ditemukan"));
                }
            } 
            if($jnsCones == "old") {
                $this->data_model->saved('baku_ajl', [
                    'kode' => $kode,
                    'jumlah_cones' => $jml,
                    'berat_cones' => $brt,
                    'jns_cones' => $jnsCones,
                    'operator' => $this->session->userdata('nama'),
                    'id_produksi_mesin' => $id
                ]);
                echo json_encode(array("statusCode"=>200, "fix"=>"success"));
            }
            //echo json_encode(array("statusCode"=>200, "fix"=>"success"));
        } else {
            echo json_encode(array("statusCode"=>400, "fix"=>"Data tidak sesuai"));
        }
  } //end

  function hapusBakuAjl(){
     $id = $this->input->post('id');
     $this->db->query("DELETE FROM baku_ajl WHERE id_bakuajl='$id'");
     echo "success";
  } //edn
   
  function deloper(){
        $id = $this->input->post('id');
        $this->db->query("DELETE FROM operator_mesin WHERE id_op='$id'");
        echo "success";
  } //end
  function showmesin(){
        $kode = $this->input->post('kode');
        if($kode=="null" OR $kode=="all"){
            $mesin = $this->db->query("SELECT * FROM table_mesin ORDER BY no_mesin");
        } else {
            $mesin = $this->db->query("SELECT * FROM table_mesin WHERE no_mesin LIKE '%$kode%'");
        }
        if($mesin->num_rows() > 0 ){
            foreach($mesin->result() as $val){
                $nomesin = $val->no_mesin;
                $produksi_mesin = $this->db->query("SELECT * FROM produksi_mesin_ajl WHERE no_mesin='$nomesin' ORDER BY id_produksi_mesin DESC LIMIT 1");
                $pjg_awal = number_format($produksi_mesin->row('pjg_lusi'));
                //cek potongan beam
                $id_prod_mesin = $produksi_mesin->row("id_produksi_mesin");
                $id_beam_sizing = $produksi_mesin->row("id_beam_sizing");
                $nobeam = $this->data_model->get_byid('beam_sizing',['id_beam_sizing'=>$id_beam_sizing])->row("kode_beam");
                $cek_potongan = $this->data_model->get_byid('produksi_mesin_ajl_potongan', ['id_beam_sizing'=>$id_beam_sizing]);
                if($cek_potongan->num_rows() == 0){
                    $total_potongan = "-";
                } else {
                    //ini tempat untuk menghitung potongan dan sisa kain
                    $jml_potongan = $cek_potongan->num_rows();
                    $meter_potongan = $this->db->query("SELECT SUM(ukuran_meter) AS uk FROM produksi_mesin_ajl_potongan WHERE id_beam_sizing='$id_beam_sizing'")->row("uk");
                    $total_potongan = $jml_potongan." - ".number_format($meter_potongan)." M";
                }
                //end cek potongan beam
                if($produksi_mesin->num_rows() == 0){
                    $st_jalan = "null";
                } else {
                    if($produksi_mesin->row("proses") == "onproses"){
                        $st_jalan = "jalan";
                    } else {
                        $st_jalan = "null";
                    }
                }
                ?>
                <a href="<?=base_url('operator/mesin/'.sha1($id_prod_mesin));?>" style="text-decoration:none;">
                <div class="kode-mesin">
                    <span class="bullet <?=$st_jalan=='null'?'red':'green';?>"></span>
                    <h2 style="color:#0b68c6;">Mc : <?=$val->no_mesin;?></h2>
                    <?php if($st_jalan=="jalan"){ ?>
                    <span class="labl mt-5">Beam : <strong><?=$nobeam;?></strong></span>
                    <span class="labl mt-5">Panjang Awal :</span>
                    <span class="labl-dt"><?=$pjg_awal;?></span>
                    <span class="labl mt-5">Total Potongan : </span>
                    <span class="labl-dt"><?=$total_potongan;?></span>
                    <?php } else { ?>
                    <span class="labl mt-5">Beam : -</span>
                    <span class="labl mt-5">Panjang Awal :</span>
                    <span class="labl-dt">-</span>
                    <span class="labl mt-5">Total Potongan : </span>
                    <span class="labl-dt">-</span>
                    <?php } ?>
                </div>
                </a>
                <?php
            }
        } else {
            echo "<p>Tidak ada data mesin</p>";
        }
        

  }//end

  function savedLoomCounter23(){
        $tgl_old = $this->input->post('tgl_old');
        $shift_old = $this->input->post('shift_old');
        $nomesin_old = $this->input->post('nomesin_old');
        $groupid_old = $this->input->post('groupid_old');
        $kons_old = $this->input->post('kons_old');
        $rpm_old = $this->input->post('rpm_old');
        $pick_old = $this->input->post('pick_old');
        $lusi_old = $this->input->post('lusi_old');
        $mnt_lusi_old = $this->input->post('mnt_lusi_old');
        $pakan_old = $this->input->post('pakan_old');
        $mnt_pakan_old = $this->input->post('mnt_pakan_old');
        $eff_old = $this->input->post('eff_old');
        $prod_old = $this->input->post('prod_old');


        $idid = $this->input->post('id_loom');
        $groupid = $this->input->post('groupid');
        $operator = $this->input->post('operator');
        $tgl = $this->input->post('tgl');
        $shift = $this->input->post('shift');
        $nomesin = $this->input->post('nomesin');
        $kons = $this->input->post('kons');
        $rpm = $this->input->post('rpm');
        $pick = $this->input->post('pick');
        $lusi = $this->input->post('lusi');
        $mnt_lusi = $this->input->post('mnt_lusi');
        $rtrt_lusi = $this->input->post('rtrt_lusi');
        $pakan = $this->input->post('pakan');
        $mnt_pakan = $this->input->post('mnt_pakan');
        $rtrt_pakan = $this->input->post('rtrt_pakan');
        $eff = $this->input->post('eff');
        $prod = $this->input->post('prod');
        $protis = $this->input->post('protis');
        $pres = $this->input->post('pres');
        $txt = array();
        if($groupid != $groupid_old){ $txt[]="Group ".$groupid_old." ke Group ".$groupid.""; }
        if($tgl != $tgl_old){ $txt[]="Tanggal ".$tgl_olg." menjadi ".$tgl.""; }
        if($shift != $shift_old){ $txt[]="Shift ".$shift_old." ke Shift ".$shift.""; }
        if($kons != $kons_old){ $txt[]="Konstruksi ".$kons_old." menjadi ".$kons.""; }
        if($rpm != $rpm_old){ $txt[]="RPM ".$rpm_old." menjadi ".$rpm.""; }
        if($pick != $pick_old){ $txt[]="Pick ".$pick_old." menjadi ".$pick.""; }
        if($lusi != $lusi_old){ $txt[]="Lusi Putus ".$lusi_old." menjadi ".$lusi.""; }
        if($mnt_lusi != $mnt_lusi_old){ $txt[]="Menit Lusi Mati ".$mnt_lusi_old." menjadi ".$mnt_lusi.""; }
        if($pakan != $pakan_old){ $txt[]="Pakan Putus ".$pakan_old." menjadi ".$pakan.""; }
        if($mnt_pakan != $mnt_pakan_old){ $txt[]="Menit Pakan Mati ".$mnt_pakan_old." menjadi ".$mnt_pakan.""; }
        if($eff != $eff_old){ $txt[]="EFF ".$eff_old." menjadi ".$eff.""; }
        if($prod != $prod_old){ $txt[]="Produksi ".$prod_old." menjadi ".$prod.""; }
        $dtlist = [
            'tgl' => $tgl,
            'shift_mesin' => $shift,
            'group_shift' => $groupid,
            'no_mesin' => $nomesin,
            'konstruksi' => $kons,
            'rpm' => $rpm,
            'pick' => $pick,
            'ls' => $lusi,
            'ls_mnt' => $mnt_lusi,
            'rt_lost_ls' => $rtrt_lusi,
            'pkn' => $pakan,
            'mnt' => $mnt_pakan,
            'rt_lost_pkn' => $rtrt_pakan,
            'eff' => $eff,
            'produksi' => $prod,
            'produksi_teoritis' => $protis,
            'presentase_teoritis' => $pres
        ];
        $this->data_model->updatedata('id_loom',$idid,'loom_counter', $dtlist);
        if(count($txt) > 0){
            $new_txt = implode(', ', $txt);
            $this->data_model->saved('loom_counter_rwytupdt', [
                'yg_edit' => $operator,
                'txt' => $new_txt,
                'id_loom'=>$idid
            ]); 
            // echo "<pre>";
            // print_r($txt);
            // echo "</pre>";
        } else {
            
        }
        

        echo "<div style='width:100%;height:100vh;display:flex;flex-direction:column;justify-content:center;align-items:center;font-size:2em;padding:0 20px;box-sizing:border-box;'>";
        echo "<span style='font-size:3em;color:green;'>Berhasil</span>";
        echo "<span style='width:100%;text-align:center;'>Update Loom Counter Mesin ".$nomesin." Tanggal ".$tgl." Shift ".$shift."</span>";
        echo "<a href='".base_url('data-loom')."' onclick='' style='text-decoration:none;padding:20px 30px;border-radius:5px;background:#0280f5;margin-top:50px;color:#fff;'>Kembali</a>";
        echo "</div>";
  }

  function savedLoomCounter2(){
        $groupid = $this->input->post('groupid');
        $operator = $this->input->post('operator');
        $tgl = $this->input->post('tgl');
        $shift = $this->input->post('shift');
        $nomesin = $this->input->post('nomesin');
        $kons = $this->input->post('kons');
        $rpm = $this->input->post('rpm');
        $pick = $this->input->post('pick');
        $lusi = $this->input->post('lusi');
        $mnt_lusi = $this->input->post('mnt_lusi');
        $rtrt_lusi = $this->input->post('rtrt_lusi');
        $pakan = $this->input->post('pakan');
        $mnt_pakan = $this->input->post('mnt_pakan');
        $rtrt_pakan = $this->input->post('rtrt_pakan');
        $eff = $this->input->post('eff');
        $prod = $this->input->post('prod');
        $protis = $this->input->post('protis');
        $pres = $this->input->post('pres');
        // echo "<pre>";
        // print_r($this->input->post());
        // echo "</pre>";
        $waktu = date('Y-m-d H:i:s');
        $dtlist = [
            'tm_input' => $waktu,
            'tgl' => $tgl,
            'shift_mesin' => $shift,
            'no_mesin' => $nomesin,
            'konstruksi' => $kons,
            'rpm' => $rpm,
            'pick' => $pick,
            'ls' => $lusi,
            'ls_mnt' => $mnt_lusi,
            'rt_lost_ls' => $rtrt_lusi,
            'pkn' => $pakan,
            'mnt' => $mnt_pakan,
            'rt_lost_pkn' => $rtrt_pakan,
            'eff' => $eff,
            'produksi' => $prod,
            'produksi_teoritis' => $protis,
            'presentase_teoritis' => $pres,
            'yg_input' => $operator,
            'op_sebelum' => 'null'
        ];
        $this->data_model->saved('loom_counter', $dtlist);
        echo json_encode(array("statusCode"=>200, "psn"=>"Ok"));
  }


  function savedLoomCounter(){
        $operator = $this->input->post('operator');
        $groupid = $this->input->post('groupid');
        $tgl = $this->input->post('tgl');
        $shift = $this->input->post('shift');
        $nomesin = $this->input->post('nomesin');
        $kons = $this->input->post('kons');
        $rpm = $this->input->post('rpm');
        $pick = $this->input->post('pick');
        $lusi = $this->input->post('lusi');
        $mnt_lusi = $this->input->post('mnt_lusi');
        $rtrt_lusi = $this->input->post('rtrt_lusi');
        $pakan = $this->input->post('pakan');
        $mnt_pakan = $this->input->post('mnt_pakan');
        $rtrt_pakan = $this->input->post('rtrt_pakan');
        $eff = $this->input->post('eff');
        $prod = $this->input->post('prod');
        $protis = $this->input->post('protis');
        $pres = $this->input->post('pres');
        $waktu = date('Y-m-d H:i:s');
        $cek = $this->data_model->get_byid('loom_counter', ['tgl'=>$tgl, 'shift_mesin'=>$shift, 'no_mesin'=>$nomesin]);
        if($cek->num_rows() == 0){
            $dtlist = [
                'tm_input' => $waktu,
                'tgl' => $tgl,
                'shift_mesin' => $shift,
                'group_shift' => $groupid,
                'no_mesin' => $nomesin,
                'konstruksi' => $kons,
                'rpm' => $rpm,
                'pick' => $pick,
                'ls' => $lusi,
                'ls_mnt' => $mnt_lusi,
                'rt_lost_ls' => $rtrt_lusi,
                'pkn' => $pakan,
                'mnt' => $mnt_pakan,
                'rt_lost_pkn' => $rtrt_pakan,
                'eff' => $eff,
                'produksi' => $prod,
                'produksi_teoritis' => $protis,
                'presentase_teoritis' => $pres,
                'yg_input' => $operator,
                'op_sebelum' => 'null'
            ];
            $this->data_model->saved('loom_counter', $dtlist);

            echo "<div style='width:100%;height:100vh;display:flex;flex-direction:column;justify-content:center;align-items:center;font-size:2em;padding:0 20px;box-sizing:border-box;'>";
            echo "<span style='font-size:3em;color:green;'>Berhasil</span>";
            echo "<span style='width:100%;text-align:center;'>Menyimpan Loom Counter Mesin ".$nomesin." Tanggal ".$tgl." Shift ".$shift."</span>";
            echo "<a href='".base_url('input-loom')."' style='text-decoration:none;padding:20px 30px;border-radius:5px;background:#0280f5;margin-top:50px;color:#fff;'>Kembali</a>";
            echo "</div>";
            //echo json_encode(array("statusCode"=>200, "psn"=>"Ok"));
            $cekmesin = $this->data_model->get_byid('table_mesin', ['no_mesin'=>$nomesin])->num_rows();
            if($cekmesin == 0){
                $this->data_model->saved('table_mesin', ['no_mesin'=>$nomesin]);
            }
        } else {
            $txt = "Nomor mesin ".$nomesin." shift ".$shift." sudah memiliki data loom pada tanggal tersebut";
            echo "<div style='width:100%;height:100vh;display:flex;flex-direction:column;justify-content:center;align-items:center;font-size:2em;padding:0 20px;box-sizing:border-box;'>";
            echo "<span style='font-size:3em;color:red;'>Error!!</span>";
            echo "<span style='width:100%;text-align:center;'>".$txt."</span>";
            echo "<a href='".base_url('input-loom')."' style='text-decoration:none;padding:20px 30px;border-radius:5px;background:#0280f5;margin-top:50px;color:#fff;'>Kembali</a>";
            echo "</div>";
            //echo json_encode(array("statusCode"=>400, "psn"=>"Mesin sudah ada"));
        }
  }//ne

  function showTableText(){
        $ar = array(
            '00' => 'NaN', '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        );
        $tgl = $this->input->post('ch');
        $sif = $this->input->post('sif');
        $ex = explode('-', $tgl);
        $printTgl = $ex[2]." ".$ar[$ex[1]]." ".$ex[0];
        if($sif == 0){
            $_txt = "Menampilkan data Akumulasi loom counter mesin tanggal <strong>".$printTgl."</strong>";
        } else {
            $_txt = "Menampilkan data loom counter mesin tanggal <strong>".$printTgl."</strong> Shift <strong>".$sif."</strong>";
        }
        echo $_txt;
  }

  function showTable(){
        $ar = array(
            '00' => 'NaN', '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        );
        $tgl = $this->input->post('ch');
        $_tgl = $this->input->post('ch');
        $sif = $this->input->post('sif');
        $ex = explode('-', $tgl);
        $printTgl = $ex[2]." ".$ar[$ex[1]]." ".$ex[0];
        if($sif == 0){
            $_txt = "Menampilkan data Akumulasi loom counter mesin tanggal ".$printTgl."";
            $_query = $this->db->query("SELECT * FROM loom_counter WHERE tgl = '$tgl' GROUP BY no_mesin");
            ?>
            <thead>
                <tr style="background:#aeb1b5;color:#000;border:1px solid #000;">
                    <th rowspan="2">No.</th>
                    <th rowspan="2">No Mesin</th>
                    <th colspan="6">Putus Benang 3 Shift</th>
                    <th rowspan="2">EFF %</th>
                    <th rowspan="2">Produksi</th>
                    <th rowspan="2">Prod Teoritis 3 Shift</th>
                    <th rowspan="2">EFF % Rill 3 Shift</th>
                    <th rowspan="2">Prod 100%</th>
                    <th colspan="2">Selisih</th>
                    <th rowspan="2">#</th>
                </tr>
                <tr style="background:#aeb1b5;color:#000;border:1px solid #000;">
                    <th>LS</th>
                    <th>Mnt</th>
                    <th>Rata<sup>2</sup> Lost</th>
                    <th>PKN</th>
                    <th>Mnt</th>
                    <th>Rata<sup>2</sup> Lost</th>
                    <th>Produksi 3 Shift</th>
                    <th>EFF% counter VS EFF% Rill</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $n=1;
                    if($_query->num_rows() > 0){
                    foreach($_query->result() as $val):
                    //$_tgl = $val->tgl;
                    $_mc = $val->no_mesin;
                    $cekshif1 = $this->db->query("SELECT * FROM loom_counter WHERE tgl='$_tgl' AND shift_mesin='1' AND no_mesin='$_mc'");
                    $cekshif2 = $this->db->query("SELECT * FROM loom_counter WHERE tgl='$_tgl' AND shift_mesin='2' AND no_mesin='$_mc'");
                    $cekshif3 = $this->db->query("SELECT * FROM loom_counter WHERE tgl='$_tgl' AND shift_mesin='3' AND no_mesin='$_mc'");
                    
                    if($cekshif1->num_rows() == 1 AND $cekshif2->num_rows() == 1 AND $cekshif3->num_rows() == 1){
                        $_status = "oke";
                    } else {
                        $_status = "erorr";
                    }
                    if($cekshif1->row("ls")==""){ $ls1 = 0; } else { $ls1=$cekshif1->row("ls"); }
                    if($cekshif2->row("ls")==""){ $ls2 = 0; } else { $ls2=$cekshif2->row("ls"); }
                    if($cekshif3->row("ls")==""){ $ls3 = 0; } else { $ls3=$cekshif3->row("ls"); }
                    if($cekshif1->row("ls_mnt")==""){ $ls_mnt1 = 0; } else { $ls_mnt1=$cekshif1->row("ls_mnt"); }
                    if($cekshif2->row("ls_mnt")==""){ $ls_mnt2 = 0; } else { $ls_mnt2=$cekshif2->row("ls_mnt"); }
                    if($cekshif3->row("ls_mnt")==""){ $ls_mnt3 = 0; } else { $ls_mnt3=$cekshif3->row("ls_mnt"); }
                    $all_LS = intval($ls1) + intval($ls2) + intval($ls3);
                    $all_mntLS = intval($ls_mnt1) + intval($ls_mnt2) + intval($ls_mnt3);
                    $rtlost1 = $all_mntLS / $all_LS;
                    if(is_nan($rtlost1)){
                        $rtlost1 = "0";
                    }
                    if($cekshif1->row("pkn")==""){ $pkn1 = 0; } else { $pkn1=$cekshif1->row("pkn"); }
                    if($cekshif2->row("pkn")==""){ $pkn2 = 0; } else { $pkn2=$cekshif2->row("pkn"); }
                    if($cekshif3->row("pkn")==""){ $pkn3 = 0; } else { $pkn3=$cekshif3->row("pkn"); }
                    if($cekshif1->row("mnt")==""){ $mnt1 = 0; } else { $mnt1=$cekshif1->row("mnt"); }
                    if($cekshif2->row("mnt")==""){ $mnt2 = 0; } else { $mnt2=$cekshif2->row("mnt"); }
                    if($cekshif3->row("mnt")==""){ $mnt3 = 0; } else { $mnt3=$cekshif3->row("mnt"); }
                    $all_Pkn = intval($pkn1) + intval($pkn2) + intval($pkn3);
                    $all_mntPkn = intval($mnt1) + intval($mnt2) + intval($mnt3);
                    $rtlost2 = $all_mntPkn / $all_Pkn;
                    if(is_nan($rtlost2)){
                        $rtlost2 = "0";
                    }
                    if($cekshif1->row("eff")==""){ $eff_sif1 = 0; } else { $eff_sif1=$cekshif1->row("eff"); }
                    if($cekshif2->row("eff")==""){ $eff_sif2 = 0; } else { $eff_sif2=$cekshif2->row("eff"); }
                    if($cekshif3->row("eff")==""){ $eff_sif3 = 0; } else { $eff_sif3=$cekshif3->row("eff"); }

                    $eff = $eff_sif1 + $eff_sif2 + $eff_sif3;
                    $produksi = $cekshif1->row("produksi") + $cekshif2->row("produksi") + $cekshif3->row("produksi");
                    $jml_eff = round($eff,1);
                    $jml_effbagi3 = $jml_eff / 3;
                    $jml_effbagi3_koma1 = round($jml_effbagi3,1);
                    $eff_percen = $jml_effbagi3_koma1 / 100;

                    if($cekshif1->row("rpm")==""){ $rpm1 = 0; } else { $rpm1=$cekshif1->row("rpm"); }
                    if($cekshif2->row("rpm")==""){ $rpm2 = 0; } else { $rpm2=$cekshif2->row("rpm"); }
                    if($cekshif3->row("rpm")==""){ $rpm3 = 0; } else { $rpm3=$cekshif3->row("rpm"); }
                    $rpm4 = ($rpm1 + $rpm2 + $rpm3) / 3;
                    $rpm = round($rpm4);

                    if($cekshif1->row("pick")==""){ $pick1 = 0; } else { $pick1=$cekshif1->row("pick"); }
                    if($cekshif2->row("pick")==""){ $pick2 = 0; } else { $pick2=$cekshif2->row("pick"); }
                    if($cekshif3->row("pick")==""){ $pick3 = 0; } else { $pick3=$cekshif3->row("pick"); }
                    $pick4 = ($pick1 + $pick2 + $pick3) / 3;
                    $pick = round($pick4);
                    
                    
                    $protis3 = ($rpm / $pick) * 60 * 24 * 0.0254 * $eff_percen;
                    if(is_nan($protis3)){
                        $protis3 = "0";
                    }
                    $eff_percen_riil = ($produksi * $pick * 39.37) / $rpm / 60 / 24;
                    $percen_kan = $eff_percen_riil * 100;
                    if(is_nan($percen_kan)){
                        $percen_kan2 = 0;
                    } else {
                        $percen_kan2 = round($percen_kan,2); 
                    }
                    $produksi_100 = ($rpm / $pick) * 60 * 24 * 0.0254;
                    if(is_nan($produksi_100)){ $produksi_100=0; }
                    $selisih1 = $produksi - round($protis3,1);
                    $selisih2 = round($jml_effbagi3,2) - $percen_kan2;
                ?>
                <tr class="trbgbg">
                    <th><?=$n;?></th>
                    <td style="text-align:center;">
                        <?=$val->no_mesin;?>
                        <!-- <sup style="background:#f7a520;color:#f20707;width:15px;height:15px;display:flex;align-items:center;justify-content:center;border-radius:50%;">i</sup> -->
                    </td>
                    <td style="text-align:center;<?=$all_LS==0?'background:red;color:#fff;':'';?>"><?=$all_LS;?></td>
                    <td style="text-align:center;<?=$all_mntLS==0?'background:red;color:#fff;':'';?>"><?=$all_mntLS;?></td>
                    <td style="text-align:center;<?=$rtlost1==0?'background:red;color:#fff;':'';?>"><?=round($rtlost1,1);?></td>
                    <td style="text-align:center;<?=$all_Pkn==0?'background:red;color:#fff;':'';?>"><?=$all_Pkn;?></td>
                    <td style="text-align:center;<?=$all_mntPkn==0?'background:red;color:#fff;':'';?>"><?=$all_mntPkn;?></td>
                    <td style="text-align:center;<?=$rtlost2==0?'background:red;color:#fff;':'';?>"><?=round($rtlost2,1);?></td>
                    <td style="text-align:center;<?=$jml_effbagi3<1 ?'background:red;color:#000;':'';?><?=$jml_effbagi3>0 && $jml_effbagi3<80 ?'background:orange;color:#000;':'';?>"><?=round($jml_effbagi3,1);?> %</td>
                    <td style="text-align:center;<?=$produksi==0?'background:red;color:#fff;':'';?>"><?=$produksi;?></td>
                    <td style="text-align:center;<?=$protis3==0?'background:red;color:#fff;':'';?>"><?=round($protis3,1);?></td>
                    <td style="text-align:center;<?=$percen_kan2==0?'background:red;color:#fff;':'';?>"><?=$percen_kan2;?> %</td>
                    
                    <td style="text-align:center;<?=$produksi_100==0?'background:red;color:#fff;':'';?>"><?=round($produksi_100,2);?></td>
                    <td style="text-align:center;<?=$selisih1>5 ?'background:orange;color:#000;':'';?><?=$selisih1 < -5 ?'background:orange;color:#000;':'';?>"><?=round($selisih1,2);?></td>
                    <td style="text-align:center;<?=$selisih2>5?'background:orange;color:#000;':'';?>"><?=round($selisih2,2);?> %</td>
                    <td style="text-align:center;">
                        <?php if($_status == "oke"){ ?>
                        <span class="material-symbols-outlined" style="color:green;font-size:18px;">done_all</span>
                        <?php } else { ?>
                        <span class="material-symbols-outlined" style="color:red;font-size:18px;cursor:pointer;" title="Data belum lengkap 3 shift" onclick="infonot3shift()">info</span>
                        <?php } ?>
                    </td>
                </tr>
                <?php
                        $n++;
                    endforeach;
                } else {
                    echo "<tr style='background:#ebebeb;border:1px solid #000;'><td colspan='16' style='padding:5px;'>Tidak ada data</td></tr>";
                }
                ?>
            </tbody>
            <?php //end tampil show table akumulasi
        } else {
            $_txt = "Menampilkan data loom counter mesin tanggal ".$printTgl." Shift ".$sif."";
            $_query = $this->db->query("SELECT * FROM loom_counter WHERE tgl = '$tgl' GROUP BY no_mesin");
            ?>
            <thead>
                <tr style="background:#aeb1b5;color:#000;border:1px solid #000;">
                    <th rowspan="2">No.</th>
                    <th rowspan="2">No Mesin</th>
                    <th rowspan="2">Kode Proses</th>
                    <th rowspan="2">RPM</th>
                    <th rowspan="2">PICK</th>
                    <th colspan="6">Putus Benang Shift <?=$sif;?></th>
                    <th rowspan="2">EFF %</th>
                    <th rowspan="2">Produksi</th>
                    <th rowspan="2">Prod Teoritis</th>
                    <th rowspan="2">Target</th>
                    <th rowspan="2">Operator</th>
                    <th rowspan="2">#</th>
                </tr>
                <tr style="background:#aeb1b5;color:#000;border:1px solid #000;">
                    <th>LS</th>
                    <th>Mnt</th>
                    <th>Rata<sup>2</sup> Lost</th>
                    <th>PKN</th>
                    <th>Mnt</th>
                    <th>Rata<sup>2</sup> Lost</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $n=1;
                    $nilai_kosong = 0;
                    if($_query->num_rows() > 0){
                    foreach($_query->result() as $val):
                    $_mc = $val->no_mesin;
                    $cekshif1 = $this->db->query("SELECT * FROM loom_counter WHERE tgl='$_tgl' AND shift_mesin='$sif' AND no_mesin='$_mc'");
                    $_id = sha1($cekshif1->row("id_loom"));
                    $sesuai = "ya";
                ?>
                <tr class="trbgbg">
                    <th><?=$n;?></th>
                    <td style="text-align:center;"><?=$_mc;?></td>
                    <?php
                        if($cekshif1->num_rows() == 0){
                            echo "<td colspan='14' style='font-size:12px;color:red;'>Belum ada data</td>";
                        } else {
                        if($cekshif1->row("rpm")==0){ $rpm=0; } else { $rpm=$cekshif1->row("rpm"); }
                        if($cekshif1->row("pick")==0){ $pick=0; } else { $pick=$cekshif1->row("pick"); }
                        if($cekshif1->row("ls")==0){ $ls=0; } else { $ls=$cekshif1->row("ls"); }
                        if($cekshif1->row("ls_mnt")==0){ $ls_mnt=0; } else { $ls_mnt=$cekshif1->row("ls_mnt"); }
                        if($cekshif1->row("rt_lost_ls")==0){ $rt_lost_ls_mnt_real=0; } else { 
                            $rt_lost_ls=$cekshif1->row("rt_lost_ls"); 
                            $rt_lost_ls_mnt = $rt_lost_ls / 60;
                            $rt_lost_ls_mnt_real = round($rt_lost_ls_mnt,1);
                        }
                        if($cekshif1->row("pkn")==0){ $pkn=0; } else { $pkn=$cekshif1->row("pkn"); }
                        if($cekshif1->row("mnt")==0){ $mnt=0; } else { $mnt=$cekshif1->row("mnt"); }
                        if($cekshif1->row("rt_lost_pkn")==0){ $rt_lost_pkn_mnt_real=0; } else { 
                            $rt_lost_pkn=$cekshif1->row("rt_lost_pkn"); 
                            $rt_lost_pkn_mnt = $rt_lost_pkn / 60;
                            $rt_lost_pkn_mnt_real = round($rt_lost_pkn_mnt,1);
                        }
                        if($cekshif1->row("eff")==0){ $eff=0; } else { $eff=$cekshif1->row("eff"); }
                        if($cekshif1->row("produksi")==0){ $produksi=0; } else { $produksi=$cekshif1->row("produksi"); }
                        if($cekshif1->row("produksi_teoritis")==0){ $produksi_teoritis=0; } else { $produksi_teoritis=$cekshif1->row("produksi_teoritis"); }
                        if($cekshif1->row("presentase_teoritis")==0){ $presentase_teoritis=0; } else { $presentase_teoritis=$cekshif1->row("presentase_teoritis"); }
                        $new_presen_teo = (intval($rpm) / intval($pick)) * 60 * 8 * 0.0254 * 0.80;
                        $new_presen_teo_1 = round($new_presen_teo,1);
                        $_exk = explode(' ', $cekshif1->row("tm_input"));
                        $_tgl_input = $_exk[0];
                        $_jam_input = $_exk[1];
                        $_jam_pecah = explode(':', $_jam_input);
                        $currentTime = $_jam_pecah[0].":".$_jam_pecah[1];
                        $_tgl_shift = $cekshif1->row("tgl");
                        $_shift = $cekshif1->row("shift_mesin");
                        if($_shift == "1"){
                            if($_tgl_shift == $_tgl_input){
                                if (strtotime($currentTime) >= strtotime('14:00') && strtotime($currentTime) < strtotime('22:01')) { $sesuai = "ya"; } else { $sesuai = "tidak"; }
                            } else {
                                $sesuai = "tidak";
                            }
                        }
                        if($_shift == "2"){
                            if($_tgl_shift == $_tgl_input){
                                if (strtotime($currentTime) >= strtotime('22:00') && strtotime($currentTime) < strtotime('23:59')) { $sesuai = "ya"; } else { $sesuai = "tidak"; }
                            } else {
                                $_tgl_setelah = date('Y-m-d', strtotime('+1 day', strtotime($_tgl_shift)));
                                if($_tgl_input == $_tgl_setelah){
                                    if (strtotime($currentTime) >= strtotime('00:00') && strtotime($currentTime) < strtotime('06:00')) { $sesuai = "ya"; } else { $sesuai = "tidak"; }
                                } else {
                                    $sesuai = "tidak";
                                }
                            }
                        }
                        if($_shift == "3"){
                            $_tgl_setelah = date('Y-m-d', strtotime('+1 day', strtotime($_tgl_shift)));
                            if($_tgl_input == $_tgl_setelah){
                                if (strtotime($currentTime) >= strtotime('06:00') && strtotime($currentTime) < strtotime('14:00')) { $sesuai = "ya"; } else { $sesuai = "tidak"; }
                            } else {
                                $sesuai = "tidak";
                            }
                        }
                    ?>
                    <td style="text-align:center;"><?=strtoupper($cekshif1->row("konstruksi"));?></td>
                    <td style="text-align:center;<?=$rpm==0?'background:red;color:#fff;':'';?>"><?=$rpm;?></td>
                    <td style="text-align:center;<?=$pick==0?'background:red;color:#fff;':'';?>"><?=$pick;?></td>
                    <td style="text-align:center;<?=$ls==0?'background:red;color:#fff;':'';?>"><?=$ls;?></td>
                    <td style="text-align:center;<?=$ls_mnt==0?'background:red;color:#fff;':'';?>"><?=$ls_mnt;?></td>
                    <td style="text-align:center;<?=$rt_lost_ls_mnt_real==0?'background:red;color:#fff;':'';?>"><?=$rt_lost_ls_mnt_real;?></td>
                    <td style="text-align:center;<?=$pkn==0?'background:red;color:#fff;':'';?>"><?=$pkn;?></td>
                    <td style="text-align:center;<?=$mnt==0?'background:red;color:#fff;':'';?>"><?=$mnt;?></td>
                    <td style="text-align:center;<?=$rt_lost_pkn_mnt_real==0?'background:red;color:#fff;':'';?>"><?=$rt_lost_pkn_mnt_real;?></td>
                    <td style="text-align:center;<?=$eff<1?'background:red;color:#fff;':'';?><?=$eff>0 && $eff<80?'background:orange;color:#000;':'';?>"><?=$eff;?> <?=$eff==0?'':'%';?></td>
                    <td style="text-align:center;<?=$produksi==0?'background:red;color:#fff;':'';?>"><?=$produksi;?></td>
                    <td style="text-align:center;<?=$produksi_teoritis==0?'background:red;color:#fff;':'';?>"><?=$produksi_teoritis;?></td>
                    <td style="text-align:center;<?=$new_presen_teo_1==0?'background:red;color:#fff;':'';?>"><?=$new_presen_teo_1;?></td>

                    <?php if($sesuai=="ya") { ?>
                        <td style="text-align:center;"><?=$cekshif1->row("yg_input");?></td>
                    <?php } else { ?>
                    <td style="text-align:center;background:red;color:#fff;cursor:pointer;'" onclick="notsesuai()"><?=$cekshif1->row("yg_input");?></td>
                    <?php } ?>

                    <td style="text-align:center;"><a href="<?=base_url('monitoring/loom/'.$_id);?>"><span class="material-symbols-outlined" style="color:green;font-size:18px;cursor:pointer;" title="Edit Data">edit</span></a></td>
                    <?php } ?>
                </tr>
                <?php $n++; $sesuai = "ya";
                    endforeach;
                    } else {
                        echo "<tr style='background:#ebebeb;border:1px solid #000;'><td colspan='16' style='padding:5px;'>Tidak ada data</td></tr>";
                    }
                ?>
            </tbody>
            <?php
        }
  } //end

  function showLoomCounter(){
        $op = $this->input->post('namas');
        $ar = array(
            '00' => 'NaN', '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'
        );
        //$tgl = date('Y-m-d');
        $tgl_sekarang = date('Y-m-d');
        $tgl_sebelumnya = date('Y-m-d', strtotime('-1 day', strtotime($tgl_sekarang)));
        $cek = $this->db->query("SELECT * FROM loom_counter WHERE yg_input='$op' AND tgl BETWEEN '$tgl_sebelumnya' AND '$tgl_sekarang'");
        if($cek->num_rows()>0){
            $n=1;
            $dt_fix = 0;
            foreach($cek->result() as $val):
                $dt_fix = 0;
                $ex = explode('-', $val->tgl);
                $shoTgl = $ex[2]." ".$ar[$ex[1]];
                echo "<tr class='trbgbg'>";
                echo "<td style='padding:3px;font-size:12px;text-align:center;'>".$n."</td>";
                echo "<td style='padding:3px;font-size:12px;text-align:center;'>".$shoTgl."</td>";
                echo "<td style='padding:3px;font-size:12px;text-align:center;'>".$val->no_mesin."</td>";
                if($val->group_shift == "A" OR $val->group_shift == "B" OR $val->group_shift == "C"){
                    echo "<td style='padding:3px;font-size:12px;text-align:center;'>".$val->shift_mesin."/".$val->group_shift."</td>";
                } else {
                    echo "<td style='padding:3px;font-size:12px;text-align:center;'>".$val->shift_mesin."/<span style='color:red;'>".$val->group_shift."</span></td>";
                    $dt_fix+=1;
                }
                echo "<td style='padding:3px;font-size:12px;text-align:center;'>".$val->konstruksi."</td>";
                
                $rpm = $val->rpm;
                $pick = $val->pick;
                $ls = $val->ls;
                $ls_mnt = $val->ls_mnt;
                $pkn = $val->pkn;
                $mnt = $val->mnt;
                $eff = $val->eff;
                $produksi = $val->produksi;
                if($rpm==0){ $dt_fix+=1; }
                if($pick==0){ $dt_fix+=1; }
                if($ls==0){ $dt_fix+=1; }
                if($ls_mnt==0){ $dt_fix+=1; }
                if($pkn==0){ $dt_fix+=1; }
                if($mnt==0){ $dt_fix+=1; }
                if($eff==0){ $dt_fix+=1; }
                if($produksi==0){ $dt_fix+=1; }
                if($dt_fix==0){
                    echo "<td style='padding:3px;font-size:12px;text-align:center;'>";
                    echo "<a href='".base_url('operator/updateloom/'.sha1($val->id_loom))."' style='text-decoration:none;'>";
                    echo "<span style='padding:1px 5px;background:green;color:#fff;font-size:9px;border-radius:3px;'>Done</span>";
                    echo "</a>";
                    echo "</td>";
                } else {
                    echo "<td style='padding:3px;font-size:12px;text-align:center;'>";
                    echo "<a href='".base_url('operator/updateloom/'.sha1($val->id_loom))."' style='text-decoration:none;'>";
                    echo "<span style='padding:1px 5px;background:orange;color:#000;font-size:9px;border-radius:3px;'>Warn</span>";
                    echo "</a>";
                    echo "</td>";
                }
                echo "</tr>";
                $n++;
                $dt_fix=0;
            endforeach;
        } else {
            echo '<tr>
                <td colspan="5" style="padding:3px;color:red;font-size:12px;">Hari ini anda belum input...</td>
            </tr>';
        }
  }

}
?>