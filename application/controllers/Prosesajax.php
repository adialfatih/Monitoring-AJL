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
                        'tgl_pakai' => date('Y-m-d H:i:s'),
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
        $siz = $this->input->post('siz');
        $this->db->query("DELETE FROM beam_warping_used WHERE id_beamwar='$kode' AND kode_produksi_sizing='$siz' ");
        $this->data_model->updatedata('id_beamwar', $kode, 'beam_warping', ['status_beam'=>'masih']);
        //$this->data_model->updatedata('id_beamwar',$kode,'beam_warping',['kode_proses_sizing'=>'null']);
        echo "Success";
  }

  function showBeamWarpingOnSizing(){
        $kode = $this->input->post('kode');
        $cek = $this->data_model->get_byid('beam_warping_used', ['kode_produksi_sizing'=>$kode]);
        if($cek->num_rows() > 0){
            ?>
            <label for="okaid">&nbsp;</label>
            <table class="table" border="1" style="border-collapse:collapse;margin-top:20px;border:2px solid #ccc;">
                <thead>
                    <tr style="background:#dae0eb;">
                        <th style="padding:5px;">No</th>
                        <th style="padding:5px;">Kode/No Beam tes</th>
                        <th style="padding:5px;">Ukuran Beam</th>
                        <th style="padding:5px;">Pemakaian</th>
                        <th style="padding:5px;">Sisa</th>
                        <th style="padding:5px;">Mesin</th>
                        <th style="padding:5px;">Produksi</th>
                        <th style="padding:5px;">Hapus</th>
                    </tr>
                </thead>
                <tbody>
            <?php $no=1;
                foreach($cek->result() as $val){
                    $id_beamwar = $val->id_beamwar;
                    $siz = $val->kode_produksi_sizing;
                    $cek = $this->data_model->get_byid('v_beam_warping2', ['id_beamwar'=>$id_beamwar])->row_array();
                    $ex = explode('-', $cek['tgl_produksi']);
                    $print = $ex[2]."-".$this->data_model->printBln2($ex[1])."-".$ex[0];
                    echo "<tr>";
                    echo "<td style='padding:5px;text-align:center;'>".$no."</td>";
                    ?>
                    <td style="padding:5px;text-align:center;"><a href="javascript:;" onclick="chngsue('<?=$id_beamwar;?>','<?=$val->sisa;?>','<?=$siz;?>','<?=$cek['kode_beam'];?>')" style="text-decoration:none;color:blue;"><?=$cek['kode_beam'];?></a></td>
                    <?php
                    //echo "<td style='padding:5px;text-align:center;'>".$cek['kode_beam']."</td>";
                    echo "<td style='padding:5px;text-align:center;'>".$cek['ukuran_panjang']."</td>";
                    echo "<td style='padding:5px;text-align:center;'>".$val->pemakaian."</td>";
                    echo "<td style='padding:5px;text-align:center;'>".$val->sisa."</td>";
                    echo "<td style='padding:5px;text-align:center;'>".$cek['jenis_mesin']."</td>";
                    echo "<td style='padding:5px;text-align:center;'>".$print."</td>";
                    ?>
                        <td style="padding:5px;text-align:center;font-size:12px;">
                            <a href="javascript:void(0);" style="color:red;text-decoration:none;" onclick="delbeam('<?=$val->id_beamwar;?>', '<?=$siz;?>')">Hapus</a>
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
        $ukuran = $cek->row("ukuran_panjang");
        $status = $cek->row("status_beam");
        if($status != "habis"){
            $this->data_model->saved('beam_warping_used', [
                'id_beamwar' => $id_beamwar,
                'sisa' => 0,
                'pemakaian' => $ukuran,
                'kode_produksi_sizing' => $kode_proses
            ]);
            $this->data_model->updatedata('id_beamwar', $id_beamwar, 'beam_warping', ['status_beam'=>'habis']);
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
                                'tm_stmp' => date('Y-m-d H:i:s'),
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
                    'tm_stmp' => date('Y-m-d H:i:s'),
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
            ?>
        <div class="kol25">
            <?php
            $line_a = $this->db->query("SELECT * FROM table_mesin WHERE no_mesin LIKE 'A%' ORDER BY no_mesin");
            foreach ($line_a->result() as $val_a) {
                //$nmsna = $val_a->no_mesin;
                $nmsna = str_replace([' ', ',', '.', '-'], '', strtoupper($val_a->no_mesin));
                $cek_dta = $this->db->query("SELECT * FROM produksi_mesin_ajl WHERE no_mesin='$nmsna' ORDER BY id_produksi_mesin DESC LIMIT 1");
                if($cek_dta->row("proses") == "onproses"){
                    $mesin_nyala = "on";
                } else {
                    $mesin_nyala = "off";
                }
                if($cek_dta->num_rows() == 1){
                    $id_produksi_mesin = $cek_dta->row("id_produksi_mesin");
                    //$kons1 = strtoupper($cek_dta->row("konstruksi"));
                    $kons1 = str_replace([' ', ',', '.', '-'], '', strtoupper($cek_dta->row("konstruksi")));
                    $this->db->query("UPDATE produksi_mesin_ajl SET konstruksi='$kons1' WHERE id_produksi_mesin='$id_produksi_mesin'");
                } else {
                    $kons1 = "-";
                    $id_produksi_mesin = "null";
                }
                $idaa = $cek_dta->row("id_produksi_mesin");
                $idprod=sha1($cek_dta->row("id_produksi_mesin"));
                
            ?>
            <div class="mesinkotak" onclick="showmodals('<?=$nmsna;?>','<?=$idprod;?>','<?=$idaa;?>')">
                <span><?=$kons1;?></span>
                <span class="<?=$mesin_nyala;?>"><?=strtoupper($nmsna);?></span>
            </div>
            <?php 
            } //end foreach
            ?>
        </div>
        <div class="kol25">
            <?php
            $line_b = $this->db->query("SELECT * FROM table_mesin WHERE no_mesin LIKE 'B%' ORDER BY no_mesin");
            foreach ($line_b->result() as $val_b) {
                //$nmsnb = $val_b->no_mesin;
                $nmsnb = str_replace([' ', ',', '.', '-'], '', strtoupper($val_b->no_mesin));
                $cek_dtb = $this->db->query("SELECT * FROM produksi_mesin_ajl WHERE no_mesin='$nmsnb' ORDER BY id_produksi_mesin DESC LIMIT 1");
                if($cek_dtb->row("proses") == "onproses"){
                    $mesin_nyala2 = "on";
                } else {
                    $mesin_nyala2 = "off";
                }
                if($cek_dtb->num_rows() == 1){
                    $id_produksi_mesin = $cek_dtb->row("id_produksi_mesin");
                    $this->db->query("UPDATE produksi_mesin_ajl SET no_mesin='$nmsnb' WHERE id_produksi_mesin='$id_produksi_mesin'");
                    //$kons2 = strtoupper($cek_dtb->row("konstruksi"));
                    $kons2 = str_replace([' ', ',', '.', '-'], '', strtoupper($cek_dtb->row("konstruksi")));
                    $this->db->query("UPDATE produksi_mesin_ajl SET konstruksi='$kons2' WHERE id_produksi_mesin='$id_produksi_mesin'");
                } else {
                    $kons2 = "-";
                    $id_produksi_mesin = "null";
                }
                $idprodb=sha1($cek_dtb->row("id_produksi_mesin"));
            ?>
            <div class="mesinkotak" onclick="showmodals('<?=$nmsnb;?>','<?=$idprodb;?>','<?=$id_produksi_mesin;?>')">
                <span><?=$kons2;?></span>
                <span class="<?=$mesin_nyala2;?>"><?=strtoupper($nmsnb);?></span>
            </div>
            <?php } ?>
        </div>
        <div class="kol25">
            <?php
            $line_c = $this->db->query("SELECT * FROM table_mesin WHERE no_mesin LIKE 'C%' ORDER BY no_mesin");
            foreach ($line_c->result() as $val_c) {
                //$nmsnc = $val_c->no_mesin;
                $nmsnc = str_replace([' ', ',', '.', '-'], '', strtoupper($val_c->no_mesin));
                $cek_dtc = $this->db->query("SELECT * FROM produksi_mesin_ajl WHERE no_mesin='$nmsnc' ORDER BY id_produksi_mesin DESC LIMIT 1");
                if($cek_dtc->row("proses") == "onproses"){
                    $mesin_nyala3 = "on";
                } else {
                    $mesin_nyala3 = "off";
                }
                if($cek_dtc->num_rows() == 1){
                    $id_produksi_mesin = $cek_dtc->row("id_produksi_mesin");
                    $this->db->query("UPDATE produksi_mesin_ajl SET no_mesin='$nmsnc' WHERE id_produksi_mesin='$id_produksi_mesin'");
                    //$kons3 = strtoupper($cek_dtc->row("konstruksi"));
                    $kons3 = str_replace([' ', ',', '.', '-'], '', strtoupper($cek_dtc->row("konstruksi")));
                    $this->db->query("UPDATE produksi_mesin_ajl SET konstruksi='$kons3' WHERE id_produksi_mesin='$id_produksi_mesin'");
                } else {
                    $kons3 = "-";
                    $id_produksi_mesin = "null";
                }
                $idprodc=sha1($cek_dtc->row("id_produksi_mesin"));
            ?>
            <div class="mesinkotak" onclick="showmodals('<?=$nmsnc;?>','<?=$idprodc;?>','<?=$id_produksi_mesin;?>')">
                <span><?=$kons3;?></span>
                <span class="<?=$mesin_nyala3;?>"><?=strtoupper($nmsnc);?></span>
            </div>
            <?php } ?>
        </div>
        <div class="kol50">
        <?php
            $line_d = $this->db->query("SELECT * FROM table_mesin WHERE no_mesin LIKE 'D%' ORDER BY no_mesin");
            foreach ($line_d->result() as $val_d) {
                //$nmsnd = $val_d->no_mesin;
                $nmsnd = str_replace([' ', ',', '.', '-'], '', strtoupper($val_d->no_mesin));
                $cek_dtd = $this->db->query("SELECT * FROM produksi_mesin_ajl WHERE no_mesin='$nmsnd' ORDER BY id_produksi_mesin DESC LIMIT 1");
                if($cek_dtd->row("proses") == "onproses"){
                    $mesin_nyala4 = "on";
                } else {
                    $mesin_nyala4 = "off";
                }
                if($cek_dtd->num_rows() == 1){
                    $id_produksi_mesin = $cek_dtd->row("id_produksi_mesin");
                    $this->db->query("UPDATE produksi_mesin_ajl SET no_mesin='$nmsnd' WHERE id_produksi_mesin='$id_produksi_mesin'");
                    //$kons4 = strtoupper($cek_dtd->row("konstruksi"));
                    $kons4 = str_replace([' ', ',', '.', '-'], '', strtoupper($cek_dtd->row("konstruksi")));
                    $this->db->query("UPDATE produksi_mesin_ajl SET konstruksi='$kons4' WHERE id_produksi_mesin='$id_produksi_mesin'");
                } else {
                    $kons4 = "-";
                    $id_produksi_mesin = "null";
                }
                $idprodd=sha1($cek_dtd->row("id_produksi_mesin"));
            ?>
            <div class="mesinkotak full" onclick="showmodals('<?=$nmsnd;?>','<?=$idprodd;?>','<?=$id_produksi_mesin;?>')">
                <span><?=$kons4;?></span>
                <span class="<?=$mesin_nyala4;?>"><?=strtoupper($nmsnd);?></span>
            </div>
            <?php } ?>
        </div>
            <?php
        } else {
            $mesin = $this->db->query("SELECT * FROM table_mesin WHERE no_mesin LIKE '%$kode%'");
            if($mesin->num_rows() > 0 ){
                echo "<div style='width:100%; display:flex; flex-wrap:wrap;justify-content:center;'>";
                foreach($mesin->result() as $val){
                    
                    $msnh = $val->no_mesin;
                    $cek_dth = $this->db->query("SELECT * FROM produksi_mesin_ajl WHERE no_mesin='$msnh' ORDER BY id_produksi_mesin DESC LIMIT 1");
                    if($cek_dth->row("proses") == "onproses"){
                        $mesin_nyala4 = "on";
                    } else {
                        $mesin_nyala4 = "off";
                    }
                    if($cek_dth->num_rows() == 1){
                        $kons4 = strtoupper($cek_dth->row("konstruksi"));
                    } else {
                        $kons4 = "-";
                    }
                    $idprodh=sha1($cek_dth->row("id_produksi_mesin"));
                    $id_produksi_mesin=$cek_dth->row("id_produksi_mesin");
                ?>
                
                    <div class="mesinkotak pch" onclick="showmodals('<?=$msnh;?>','<?=$idprodb;?>','<?=$id_produksi_mesin;?>')">
                        <span><?=$kons4;?></span>
                        <span class="<?=$mesin_nyala4;?>"><?=$msnh;?></span>
                    </div>
                    <?php
                    
                }
                echo "</div>";
            } else {
                echo "<p>Tidak ada data mesin</p>";
            }
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
        $waktu = date('Y-m-d H:i:s');
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
            'presentase_teoritis' => $pres
        ];
        $this->data_model->updatedata('id_loom',$idid,'loom_counter', $dtlist);
        if(count($txt) > 0){
            $new_txt = implode(', ', $txt);
            $this->data_model->saved('loom_counter_rwytupdt', [
                'yg_edit' => $operator,
                'tm_stmp' => date('Y-m-d H:i:s'),
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
        $matiornot = $this->input->post('matiornot');
        $ketx = $this->input->post('ketxs');
        $ketnekmati = $this->input->post('ketx');
        
        $cek = $this->data_model->get_byid('loom_counter', ['tgl'=>$tgl, 'shift_mesin'=>$shift, 'no_mesin'=>$nomesin]);
        if($cek->num_rows() == 0){
            if($matiornot==1){
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
                'presentase_teoritis' => $pres,
                'yg_input' => $operator,
                'op_sebelum' => 'null',
                'xket' => $ketx=='' ? 'null':$xket,
                'mati_or_not' => 1
            ];
            } else {
                $waktu = date('Y-m-d H:i:s');
                $dtlist = [
                    'tm_input' => $waktu,
                    'tgl' => $tgl,
                    'shift_mesin' => $shift,
                    'group_shift' => $groupid,
                    'no_mesin' => $nomesin,
                    'konstruksi' => 'null',
                    'rpm' => 0,
                    'pick' => 0,
                    'ls' => 0,
                    'ls_mnt' => 0,
                    'rt_lost_ls' => 0,
                    'pkn' => 0,
                    'mnt' => 0,
                    'rt_lost_pkn' => 0,
                    'eff' => 0,
                    'produksi' => 0,
                    'produksi_teoritis' => 0,
                    'presentase_teoritis' => 0,
                    'yg_input' => $operator,
                    'op_sebelum' => 'null',
                    'xket' => $ketnekmati,
                    'mati_or_not' => 0
                ];
            }
            $this->data_model->saved('loom_counter', $dtlist);

            echo "<div style='width:100%;height:100vh;display:flex;flex-direction:column;justify-content:center;align-items:center;font-size:2em;padding:0 20px;box-sizing:border-box;'>";
            echo "<span style='font-size:3em;color:green;'>Berhasil</span>";
            echo "<span style='width:100%;text-align:center;'>Menyimpan Loom Counter Mesin ".$nomesin." Tanggal ".$tgl." Shift ".$shift."</span>";
            echo "<a href='".base_url('input-loom')."' style='text-decoration:none;padding:20px 30px;border-radius:5px;background:#0280f5;margin-top:50px;color:#fff;'>Kembali</a>";
            echo "</div>";
            //echo json_encode(array("statusCode"=>200, "psn"=>"Ok"));
            $cekmesin = $this->data_model->get_byid('table_mesin', ['no_mesin'=>$nomesin])->num_rows();
            if($cekmesin == 0){
                //$this->data_model->saved('table_mesin', ['no_mesin'=>$nomesin]);
                //#stop input mesin
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
  function savedLoomCounter_cabang(){
    $id_loom_asli = $this->input->post('id_loom_asli');
    $operator = $this->input->post('operator');
    $groupid = $this->input->post('groupid');
    $tgl = $this->input->post('tgl');
    $shift = $this->input->post('shift');
    $nomesin = $this->input->post('nomc');
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
    
    //$cek = $this->data_model->get_byid('loom_counter', ['tgl'=>$tgl, 'shift_mesin'=>$shift, 'no_mesin'=>$nomesin]);
    //if($cek->num_rows() == 0){
        $dtlist = [
            'id_loom_asli' => $id_loom_asli,
            'tm_input' => date('Y-m-d H:i:s'),
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
        $this->data_model->saved('loom_counter2', $dtlist);
        $cek_cabang = $this->data_model->get_byid('loom_counter2', ['id_loom_asli'=>$id_loom_asli]);
        $num = $cek_cabang->num_rows();
        $produksi_ke = $num + 1;
        echo "<div style='width:100%;height:100vh;display:flex;flex-direction:column;justify-content:center;align-items:center;font-size:2em;padding:0 20px;box-sizing:border-box;'>";
        echo "<span style='font-size:3em;color:green;'>Berhasil</span>";
        echo "<span style='width:100%;text-align:center;'>Menyimpan Loom Counter Mesin ".$nomesin." Tanggal ".$tgl." Shift ".$shift." Produksi ke-".$produksi_ke."</span>";
        echo "<a href='".base_url('data-loom')."' style='text-decoration:none;padding:20px 30px;border-radius:5px;background:#0280f5;margin-top:50px;color:#fff;'>Kembali</a>";
        echo "</div>";
        //echo json_encode(array("statusCode"=>200, "psn"=>"Ok"));
        // $cekmesin = $this->data_model->get_byid('table_mesin', ['no_mesin'=>$nomesin])->num_rows();
        // if($cekmesin == 0){
        //     $this->data_model->saved('table_mesin', ['no_mesin'=>$nomesin]);
        // }
    // } else {
    //     $txt = "Nomor mesin ".$nomesin." shift ".$shift." sudah memiliki data loom pada tanggal tersebut";
    //     echo "<div style='width:100%;height:100vh;display:flex;flex-direction:column;justify-content:center;align-items:center;font-size:2em;padding:0 20px;box-sizing:border-box;'>";
    //     echo "<span style='font-size:3em;color:red;'>Error!!</span>";
    //     echo "<span style='width:100%;text-align:center;'>".$txt."</span>";
    //     echo "<a href='".base_url('input-loom')."' style='text-decoration:none;padding:20px 30px;border-radius:5px;background:#0280f5;margin-top:50px;color:#fff;'>Kembali</a>";
    //     echo "</div>";
    //     //echo json_encode(array("statusCode"=>400, "psn"=>"Mesin sudah ada"));
    // }
}//ne

    function showTableText3(){
        $ar = array(
            '00' => 'NaN', '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        );
        $tgl_cetak = $this->input->post('ch');
        $sesuai = $this->input->post('sif');
        $ex = explode('-', $tgl);
        $printTgl = $ex[2]." ".$ar[$ex[1]]." ".$ex[0];
        for ($i=1; $i <4 ; $i++) { 
            $cek_a = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND shift_mesin='$i' AND no_mesin LIKE 'A%' AND mati_or_not='1' ");
            $cek_b = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND shift_mesin='$i' AND no_mesin LIKE 'B%' AND mati_or_not='1' ");
            $cek_c = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND shift_mesin='$i' AND no_mesin LIKE 'C%' AND mati_or_not='1' ");
            $cek_d = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND shift_mesin='$i' AND no_mesin LIKE 'D%' AND mati_or_not='1' ");
            $cek_all = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND shift_mesin='$i' AND mati_or_not='1' ");
            $mc_jalan = intval($cek_a->row("jml_mesin")) + intval($cek_b->row("jml_mesin")) + intval($cek_c->row("jml_mesin")) + intval($cek_d->row("jml_mesin"));
            $mc_mati = $this->db->query("SELECT * FROM loom_counter WHERE tgl='$tgl_cetak' AND shift_mesin='$i' AND mati_or_not!='1' ORDER BY no_mesin");
            $jml_mcmati = $mc_mati->num_rows();
            $cek_msna = $cek_a->row("jml_mesin");
            $cek_msnb = $cek_b->row("jml_mesin");
            $cek_msnc = $cek_c->row("jml_mesin");
            $cek_msnd = $cek_d->row("jml_mesin");
            $eff_a = round($cek_a->row("eff"),2) / intval($cek_a->row("jml_mesin"));
            $eff_b = round($cek_b->row("eff"),2) / intval($cek_b->row("jml_mesin"));
            $eff_c = round($cek_c->row("eff"),2) / intval($cek_c->row("jml_mesin"));
            $eff_d = round($cek_d->row("eff"),2) / intval($cek_d->row("jml_mesin"));
            if(is_nan($eff_a)){ $eff_a="0"; } else { $eff_a = round($eff_a,1); }
            if(is_nan($eff_b)){ $eff_b="0"; } else { $eff_b = round($eff_b,1); }
            if(is_nan($eff_c)){ $eff_c="0"; } else { $eff_c = round($eff_c,1); }
            if(is_nan($eff_d)){ $eff_d="0"; } else { $eff_d = round($eff_d,1); }
            $_rata_eff = ($eff_a + $eff_b + $eff_c + $eff_d) / 4;
            $_rata_eff2 = $cek_all->row("eff") / $cek_all->row("jml_mesin");
            $_total_produksi = round($cek_a->row("prod"),2) + round($cek_b->row("prod"),2) + round($cek_c->row("prod"),2) + round($cek_d->row("prod"),2);
            $_total_prod = round($_total_produksi,2);
            echo '<div style="width:25%;padding:10px;border-radius:2px;background:#ccc;font-size:14px;margin-right:10px;" id="textID'.$i.'">';
            echo "<strong>Shift ".$i."</strong><br>";
            echo "<div style='width:100%;height:1px;background:#000;margin-top:5px;'>&nbsp;</div><br>";
            $grub = $this->db->query("SELECT * FROM loom_counter WHERE tgl='$tgl_cetak' AND shift_mesin='$i' ORDER BY id_loom DESC LIMIT 1 ")->row("group_shift");
        echo "Group : <strong>".$grub."</strong><br>";
        if($grub=="A"){
            $kasif = $this->db->query("SELECT kasif_a FROM data_kabag WHERE id_kabag='1'")->row("kasif_a");
            $karu = $this->db->query("SELECT karu_a FROM data_kabag WHERE id_kabag='1'")->row("karu_a");
        }
        if($grub=="B"){
            $kasif = $this->db->query("SELECT kasif_b FROM data_kabag WHERE id_kabag='1'")->row("kasif_b");
            $karu = $this->db->query("SELECT karu_b FROM data_kabag WHERE id_kabag='1'")->row("karu_b");
        }
        if($grub=="C"){
            $kasif = $this->db->query("SELECT kasif_c FROM data_kabag WHERE id_kabag='1'")->row("kasif_c");
            $karu = $this->db->query("SELECT karu_c FROM data_kabag WHERE id_kabag='1'")->row("karu_c");
        }
        echo "Kashift : <strong>".$kasif."</strong><br>";
        echo "Karu : <strong>".$karu."</strong><br><br>";
            ?>
            MC Jalan = <strong><?=$mc_jalan;?></strong><br>
            <?php if($jml_mcmati>0){ ?>
            MC Mati = <strong><?=$jml_mcmati;?></strong><br>
            <?php 
            
            $mc_mati_txt = "";
                foreach($mc_mati->result() as $tul){
                    echo "".$tul->no_mesin." = ". ucwords( $tul->xket )."<br>";
                    $mc_mati_txt .= "".$tul->no_mesin." = ". ucwords( $tul->xket )."<br>";
                }
            } 
            ?>
            <br>
            RAYON<br>
            Total Produksi Rayon<br>
            Line A (<strong><?=round($cek_msna);?></strong> MC)<br>
            Produksi = <strong><?=round($cek_a->row("prod"),2);?></strong> | Effisiensi = <strong><?php if(is_nan($eff_a)){ echo "-"; } else { echo $eff_a; };?></strong><br>
            Line B (<strong><?=round($cek_msnb);?></strong> MC)<br>
            Produksi = <strong><?=round($cek_b->row("prod"),2);?></strong> | Effisiensi = <strong><?php if(is_nan($eff_b)){ echo "-"; } else { echo $eff_b; };?></strong><br>
            Line C (<strong><?=round($cek_msnc);?></strong> MC)<br>
            Produksi = <strong><?=round($cek_c->row("prod"),2);?></strong> | Effisiensi = <strong><?php if(is_nan($eff_c)){ echo "-"; } else { echo $eff_c; };?></strong><br>
            Line D (<strong><?=round($cek_msnd);?></strong> MC)<br>
            Produksi = <strong><?=round($cek_d->row("prod"),2);?></strong> | Effisiensi = <strong><?php if(is_nan($eff_d)){ echo "-"; } else { echo $eff_d; };?></strong><br>
            --------------------------------------------<br>
            <strong>Total  <?=$mc_jalan;?> MC : </strong><br>
            Produksi = <strong><?=number_format($_total_prod,1,',','.');?></strong> Meter, Effisiensi = <strong><?=round($_rata_eff2,1);?></strong>
            <?php
            
            echo "</div>";
        }
            $cek_a = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND no_mesin LIKE 'A%' AND mati_or_not='1' ");
            $cek_b = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND no_mesin LIKE 'B%' AND mati_or_not='1' ");
            $cek_c = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND no_mesin LIKE 'C%' AND mati_or_not='1' ");
            $cek_d = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND no_mesin LIKE 'D%' AND mati_or_not='1' ");
            $cek_msna = $cek_a->row("jml_mesin") / 3;
            $cek_msnb = $cek_b->row("jml_mesin") / 3;
            $cek_msnc = $cek_c->row("jml_mesin") / 3;
            $cek_msnd = $cek_d->row("jml_mesin") / 3;
            $cek_all = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND mati_or_not='1' ");
            $mc_jalan2 = intval($cek_a->row("jml_mesin")) + intval($cek_b->row("jml_mesin")) + intval($cek_c->row("jml_mesin")) + intval($cek_d->row("jml_mesin"));
            $mc_jalan3 = $mc_jalan2 / 3; 
            $mc_jalan = round($mc_jalan3);
            $mc_mati = $this->db->query("SELECT * FROM loom_counter WHERE tgl='$tgl_cetak' AND mati_or_not!='1' ORDER BY no_mesin");
            $jml_mcmati2 = $mc_mati->num_rows() / 3;
            $jml_mcmati = round($jml_mcmati2);
            $eff_a = round($cek_a->row("eff"),2) / intval($cek_a->row("jml_mesin"));
            $eff_b = round($cek_b->row("eff"),2) / intval($cek_b->row("jml_mesin"));
            $eff_c = round($cek_c->row("eff"),2) / intval($cek_c->row("jml_mesin"));
            $eff_d = round($cek_d->row("eff"),2) / intval($cek_d->row("jml_mesin"));
            if(is_nan($eff_a)){ $eff_a="0"; } else { $eff_a = round($eff_a,1); }
            if(is_nan($eff_b)){ $eff_b="0"; } else { $eff_b = round($eff_b,1); }
            if(is_nan($eff_c)){ $eff_c="0"; } else { $eff_c = round($eff_c,1); }
            if(is_nan($eff_d)){ $eff_d="0"; } else { $eff_d = round($eff_d,1); }
            $_rata_eff = ($eff_a + $eff_b + $eff_c + $eff_d) / 4;
            $_rata_eff2 = $cek_all->row("eff") / $cek_all->row("jml_mesin");
            $_total_produksi = round($cek_a->row("prod"),2) + round($cek_b->row("prod"),2) + round($cek_c->row("prod"),2) + round($cek_d->row("prod"),2);
            $_total_prod = round($_total_produksi,2);
        echo '<div style="width:25%;padding:10px;border-radius:2px;background:#ccc;font-size:14px;margin-right:10px;" id="textID'.$i.'">';
        echo "<strong>Akumulasi 3 Shift</strong><br>";
        echo "<div style='width:100%;height:1px;background:#000;margin-top:5px;'>&nbsp;</div><br>";
        ?>
        MC Jalan = <strong><?=$mc_jalan;?></strong><br>
        MC Mati = <strong><?=$jml_mcmati;?></strong><br>
        <?php 
        // $mc_mati_txt = "";
        //     foreach($mc_mati->result() as $tul){
        //         echo "".$tul->no_mesin." = ". ucwords( $tul->xket )."<br>";
        //         $mc_mati_txt .= "".$tul->no_mesin." = ". ucwords( $tul->xket )."<br>";
        //     }
        //  }
        ?>
        <br>
        RAYON<br>
        Total Produksi Rayon<br>
        Line A (<strong><?=round($cek_msna);?></strong> MC)<br>
        Produksi = <strong><?=round($cek_a->row("prod"),2);?></strong> | Effisiensi = <strong><?php if(is_nan($eff_a)){ echo "-"; } else { echo $eff_a; };?></strong><br>
        Line B (<strong><?=round($cek_msnb);?></strong> MC)<br>
        Produksi = <strong><?=round($cek_b->row("prod"),2);?></strong> | Effisiensi = <strong><?php if(is_nan($eff_b)){ echo "-"; } else { echo $eff_b; };?></strong><br>
        Line C (<strong><?=round($cek_msnc);?></strong> MC)<br>
        Produksi = <strong><?=round($cek_c->row("prod"),2);?></strong> | Effisiensi = <strong><?php if(is_nan($eff_c)){ echo "-"; } else { echo $eff_c; };?></strong><br>
        Line D (<strong><?=round($cek_msnd);?></strong> MC)<br>
        Produksi = <strong><?=round($cek_d->row("prod"),2);?></strong> | Effisiensi = <strong><?php if(is_nan($eff_d)){ echo "-"; } else { echo $eff_d; };?></strong><br>
        --------------------------------------------<br>
        <strong>Total  <?=$mc_jalan;?> MC : </strong><br>
        Produksi = <strong><?=number_format($_total_prod,1,',','.');?></strong> Meter, Effisiensi = <strong><?=round($_rata_eff2,1);?></strong>
        <?php
        echo "</div>";
    }
    function showTableText2(){
        echo '<div style="min-width:300px;max-width:350px;padding:20px;border-radius:2px;background:#ccc;font-size:14px;" id="textID3">';
        $ar = array(
            '00' => 'NaN', '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        );
        $tgl_cetak = $this->input->post('ch');
        $sesuai = $this->input->post('sif');
        $ex = explode('-', $tgl);
        $printTgl = $ex[2]." ".$ar[$ex[1]]." ".$ex[0];
        if($sesuai!=0){
            $cek_a = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND shift_mesin='$sesuai' AND no_mesin LIKE 'A%' AND mati_or_not='1' ");
            $cek_b = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND shift_mesin='$sesuai' AND no_mesin LIKE 'B%' AND mati_or_not='1' ");
            $cek_c = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND shift_mesin='$sesuai' AND no_mesin LIKE 'C%' AND mati_or_not='1' ");
            $cek_d = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND shift_mesin='$sesuai' AND no_mesin LIKE 'D%' AND mati_or_not='1' ");
            $cek_all = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND shift_mesin='$sesuai' AND mati_or_not='1' ");
            $mc_jalan = intval($cek_a->row("jml_mesin")) + intval($cek_b->row("jml_mesin")) + intval($cek_c->row("jml_mesin")) + intval($cek_d->row("jml_mesin"));
            $mc_mati = $this->db->query("SELECT * FROM loom_counter WHERE tgl='$tgl_cetak' AND shift_mesin='$sesuai' AND mati_or_not!='1' ORDER BY no_mesin");
            $jml_mcmati = $mc_mati->num_rows();
            $cek_msna = $cek_a->row("jml_mesin");
            $cek_msnb = $cek_b->row("jml_mesin");
            $cek_msnc = $cek_c->row("jml_mesin");
            $cek_msnd = $cek_d->row("jml_mesin");
            $grub = $this->db->query("SELECT * FROM loom_counter WHERE tgl='$tgl_cetak' AND shift_mesin='$sesuai' ORDER BY id_loom DESC LIMIT 1 ")->row("group_shift");
            echo "Group : <strong>".$grub."</strong><br>";
            if($grub=="A"){
                $kasif = $this->db->query("SELECT kasif_a FROM data_kabag WHERE id_kabag='1'")->row("kasif_a");
                $karu = $this->db->query("SELECT karu_a FROM data_kabag WHERE id_kabag='1'")->row("karu_a");
            }
            if($grub=="B"){
                $kasif = $this->db->query("SELECT kasif_b FROM data_kabag WHERE id_kabag='1'")->row("kasif_b");
                $karu = $this->db->query("SELECT karu_b FROM data_kabag WHERE id_kabag='1'")->row("karu_b");
            }
            if($grub=="C"){
                $kasif = $this->db->query("SELECT kasif_c FROM data_kabag WHERE id_kabag='1'")->row("kasif_c");
                $karu = $this->db->query("SELECT karu_c FROM data_kabag WHERE id_kabag='1'")->row("karu_c");
            }
            echo "Kashift : <strong>".$kasif."</strong><br>";
            echo "Karu : <strong>".$karu."</strong><br><br>";
        } else {
            $cek_a = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND no_mesin LIKE 'A%' AND mati_or_not='1' ");
            $cek_b = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND no_mesin LIKE 'B%' AND mati_or_not='1' ");
            $cek_c = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND no_mesin LIKE 'C%' AND mati_or_not='1' ");
            $cek_d = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND no_mesin LIKE 'D%' AND mati_or_not='1' ");
            $cek_msna = $cek_a->row("jml_mesin") / 3;
            $cek_msnb = $cek_b->row("jml_mesin") / 3;
            $cek_msnc = $cek_c->row("jml_mesin") / 3;
            $cek_msnd = $cek_d->row("jml_mesin") / 3;
            $cek_all = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND mati_or_not='1' ");
            $mc_jalan2 = intval($cek_a->row("jml_mesin")) + intval($cek_b->row("jml_mesin")) + intval($cek_c->row("jml_mesin")) + intval($cek_d->row("jml_mesin"));
            $mc_jalan3 = $mc_jalan2 / 3; 
            $mc_jalan = round($mc_jalan3);
            $mc_mati = $this->db->query("SELECT * FROM loom_counter WHERE tgl='$tgl_cetak' AND mati_or_not!='1' ORDER BY no_mesin");
            $jml_mcmati2 = $mc_mati->num_rows() / 3;
            $jml_mcmati = round($jml_mcmati2);
        }

        $eff_a = round($cek_a->row("eff"),2) / intval($cek_a->row("jml_mesin"));
        $eff_b = round($cek_b->row("eff"),2) / intval($cek_b->row("jml_mesin"));
        $eff_c = round($cek_c->row("eff"),2) / intval($cek_c->row("jml_mesin"));
        $eff_d = round($cek_d->row("eff"),2) / intval($cek_d->row("jml_mesin"));
        if(is_nan($eff_a)){ $eff_a="0"; } else { $eff_a = round($eff_a,1); }
        if(is_nan($eff_b)){ $eff_b="0"; } else { $eff_b = round($eff_b,1); }
        if(is_nan($eff_c)){ $eff_c="0"; } else { $eff_c = round($eff_c,1); }
        if(is_nan($eff_d)){ $eff_d="0"; } else { $eff_d = round($eff_d,1); }
        $_rata_eff = ($eff_a + $eff_b + $eff_c + $eff_d) / 4;
        $_rata_eff2 = $cek_all->row("eff") / $cek_all->row("jml_mesin");
        $_total_produksi = round($cek_a->row("prod"),2) + round($cek_b->row("prod"),2) + round($cek_c->row("prod"),2) + round($cek_d->row("prod"),2);
        $_total_prod = round($_total_produksi,2);
        $to_copy = "Laporan Hasil Shift : <strong>".$sesuai."</strong><br><br>MC Jalan = <strong>".$mc_jalan."</strong><br>RAYON<br>Total Produksi Rayon<br>Line A (<strong>".round($cek_a->row("jml_mesin"), 2)."</strong> MC)<br>Produksi = <strong>".round($cek_a->row("prod"),2)."</strong> | Effisiensi = <strong>".$eff_a."</strong><br>Line B (<strong>".round($cek_b->row("jml_mesin"),2)."</strong> MC)<br>Produksi = <strong>".round($cek_b->row("prod"),2)."</strong> | Effisiensi = <strong>".$eff_b."</strong><br>Line C (<strong>".$cek_c->row("jml_mesin")."</strong> MC)<br>Produksi = <strong>".round($cek_c->row("prod"),2)."</strong> | Effisiensi = <strong>".$eff_c."</strong><br>Line D (<strong>".$cek_d->row("jml_mesin")."</strong> MC)<br>Produksi = <strong>".round($cek_d->row("prod"),2)."</strong> | Effisiensi = <strong>".$eff_d."</strong><br>--------------------------------------------<br><strong>Total  ".$mc_jalan." MC : </strong><br>Produksi = <strong>".$_total_prod."</strong> Meter, Effisiensi = <strong>".round($_rata_eff,1)."</strong>";
        ?>
        MC Jalan = <strong><?=$mc_jalan;?></strong><br>
        <?php if($jml_mcmati>0){ ?>
        MC Mati = <strong><?=$jml_mcmati;?></strong><br>
        <?php 
        if($sesuai!=0){
        $mc_mati_txt = "";
            foreach($mc_mati->result() as $tul){
                echo "".$tul->no_mesin." = ". ucwords( $tul->xket )."<br>";
                $mc_mati_txt .= "".$tul->no_mesin." = ". ucwords( $tul->xket )."<br>";
            }
        } }
        ?>
        <br>
        RAYON<br>
        Total Produksi Rayon<br>
        Line A (<strong><?=round($cek_msna);?></strong> MC)<br>
        Produksi = <strong><?=round($cek_a->row("prod"),2);?></strong> | Effisiensi = <strong><?php if(is_nan($eff_a)){ echo "-"; } else { echo $eff_a; };?></strong><br>
        Line B (<strong><?=round($cek_msnb);?></strong> MC)<br>
        Produksi = <strong><?=round($cek_b->row("prod"),2);?></strong> | Effisiensi = <strong><?php if(is_nan($eff_b)){ echo "-"; } else { echo $eff_b; };?></strong><br>
        Line C (<strong><?=round($cek_msnc);?></strong> MC)<br>
        Produksi = <strong><?=round($cek_c->row("prod"),2);?></strong> | Effisiensi = <strong><?php if(is_nan($eff_c)){ echo "-"; } else { echo $eff_c; };?></strong><br>
        Line D (<strong><?=round($cek_msnd);?></strong> MC)<br>
        Produksi = <strong><?=round($cek_d->row("prod"),2);?></strong> | Effisiensi = <strong><?php if(is_nan($eff_d)){ echo "-"; } else { echo $eff_d; };?></strong><br>
        --------------------------------------------<br>
        <strong>Total  <?=$mc_jalan;?> MC : </strong><br>
        Produksi = <strong><?=number_format($_total_prod,1,',','.');?></strong> Meter, Effisiensi = <strong><?=round($_rata_eff2,1);?></strong>
        <?php
        echo "</div>";
    } //end

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
                    if($cekshif1->num_rows()>0){
                        $_idsf1 = $cekshif1->row("id_loom");
                        $cek_cbng1 = $this->db->query("SELECT * FROM loom_counter2 WHERE id_loom_asli='$_idsf1'");
                        if($cek_cbng1->num_rows() > 0){
                            $bagi1 = $cek_cbng1->num_rows();
                            $_ls_cbg1 = $this->db->query("SELECT SUM(ls) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf1'")->row("lsc1");
                            $lsc_mnt1 = $this->db->query("SELECT SUM(ls_mnt) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf1'")->row("lsc1");
                            $pkn_cb1 = $this->db->query("SELECT SUM(pkn) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf1'")->row("lsc1");
                            $mnt_cb1 = $this->db->query("SELECT SUM(mnt) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf1'")->row("lsc1");
                            $eff_cb1 = $this->db->query("SELECT SUM(eff) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf1'")->row("lsc1");
                            $rpm_cb1 = $this->db->query("SELECT SUM(rpm) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf1'")->row("lsc1");
                            $pick_cb1 = $this->db->query("SELECT SUM(pick) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf1'")->row("lsc1");
                            $prod_cb1 = $this->db->query("SELECT SUM(produksi) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf1'")->row("lsc1");
                        } else {
                            $bagi1 = 0;
                        }
                    }
                    $cekshif2 = $this->db->query("SELECT * FROM loom_counter WHERE tgl='$_tgl' AND shift_mesin='2' AND no_mesin='$_mc'");
                    if($cekshif2->num_rows()>0){
                        $_idsf2 = $cekshif2->row("id_loom");
                        $cek_cbng2 = $this->db->query("SELECT * FROM loom_counter2 WHERE id_loom_asli='$_idsf2'");
                        if($cek_cbng2->num_rows() > 0){
                            $bagi2 = $cek_cbng2->num_rows();
                            $_ls_cbg2 = $this->db->query("SELECT SUM(ls) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf2'")->row("lsc1");
                            $lsc_mnt2 = $this->db->query("SELECT SUM(ls_mnt) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf2'")->row("lsc1");
                            $pkn_cb2 = $this->db->query("SELECT SUM(pkn) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf2'")->row("lsc1");
                            $mnt_cb2 = $this->db->query("SELECT SUM(mnt) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf2'")->row("lsc1");
                            $eff_cb2 = $this->db->query("SELECT SUM(eff) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf2'")->row("lsc1");
                            $rpm_cb2 = $this->db->query("SELECT SUM(rpm) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf2'")->row("lsc1");
                            $pick_cb2 = $this->db->query("SELECT SUM(pick) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf2'")->row("lsc1");
                            $prod_cb2 = $this->db->query("SELECT SUM(produksi) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf2'")->row("lsc1");
                        } else {
                            $bagi2 = 0;
                        }
                    }
                    $cekshif3 = $this->db->query("SELECT * FROM loom_counter WHERE tgl='$_tgl' AND shift_mesin='3' AND no_mesin='$_mc'");
                    if($cekshif3->num_rows()>0){
                        $_idsf3 = $cekshif3->row("id_loom");
                        $cek_cbng3 = $this->db->query("SELECT * FROM loom_counter2 WHERE id_loom_asli='$_idsf3'");
                        if($cek_cbng3->num_rows() > 0){
                            $bagi3 = $cek_cbng3->num_rows();
                            $_ls_cbg3 = $this->db->query("SELECT SUM(ls) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf3'")->row("lsc1");
                            $lsc_mnt3 = $this->db->query("SELECT SUM(ls_mnt) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf3'")->row("lsc1");
                            $pkn_cb3 = $this->db->query("SELECT SUM(pkn) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf3'")->row("lsc1");
                            $mnt_cb3 = $this->db->query("SELECT SUM(mnt) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf3'")->row("lsc1");
                            $eff_cb3 = $this->db->query("SELECT SUM(eff) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf3'")->row("lsc1");
                            $rpm_cb3 = $this->db->query("SELECT SUM(rpm) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf3'")->row("lsc1");
                            $pick_cb3 = $this->db->query("SELECT SUM(pick) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf3'")->row("lsc1");
                            $prod_cb3 = $this->db->query("SELECT SUM(produksi) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf3'")->row("lsc1");
                        } else {
                            $bagi3 = 0;
                        }
                    }

                    if($cekshif1->num_rows() == 1 AND $cekshif2->num_rows() == 1 AND $cekshif3->num_rows() == 1){
                        $_status = "oke";
                    } else {
                        $_status = "erorr";
                    }
                    if($cekshif1->row("ls")==""){ $ls1_all = 0; } else { 
                        $ls1=$cekshif1->row("ls"); 
                        if($bagi1!=0){
                            $pembagi1 = 1 + $bagi1;
                            $ls1_all = ($ls1 + $_ls_cbg1) / $pembagi1;
                        } else {
                            $ls1_all = $cekshif1->row("ls"); 
                        }
                    }
                    if($cekshif2->row("ls")==""){ $ls2_all = 0; } else { 
                        $ls2=$cekshif2->row("ls"); 
                        if($bagi2!=0){
                            $pembagi2 = 1 + $bagi2;
                            $ls2_all = ($ls2 + $_ls_cbg2) / $pembagi2;
                        } else {
                            $ls2_all = $cekshif2->row("ls"); 
                        }
                    }
                    if($cekshif3->row("ls")==""){ $ls3_all = 0; } else { 
                        $ls3=$cekshif3->row("ls"); 
                        if($bagi3!=0){
                            $pembagi3 = 1 + $bagi3;
                            $ls3_all = ($ls3 + $_ls_cbg3) / $pembagi3;
                        } else {
                            $ls3_all = $cekshif3->row("ls"); 
                        }
                    }
                    if($cekshif1->row("ls_mnt")==""){ $ls_mnt1_all = 0; } else { 
                        $ls_mnt1=$cekshif1->row("ls_mnt"); 
                        if($bagi1!=0){
                            $pembagi1 = 1 + $bagi1;
                            $ls_mnt1_all = ($ls_mnt1 + $lsc_mnt1) / $pembagi1;
                        } else {
                            $ls_mnt1_all = $cekshif1->row("ls_mnt"); 
                        }
                    }
                    if($cekshif2->row("ls_mnt")==""){ $ls_mnt2_all = 0; } else { 
                        $ls_mnt2=$cekshif2->row("ls_mnt"); 
                        if($bagi2!=0){
                            $pembagi2 = 1 + $bagi2;
                            $ls_mnt2_all = ($ls_mnt2 + $lsc_mnt2) / $pembagi2;
                        } else {
                            $ls_mnt2_all = $cekshif2->row("ls_mnt"); 
                        }
                    }
                    if($cekshif3->row("ls_mnt")==""){ $ls_mnt3_all = 0; } else { 
                        $ls_mnt3=$cekshif3->row("ls_mnt");
                        if($bagi3!=0){
                            $pembagi3 = 1 + $bagi3;
                            $ls_mnt3_all = ($ls_mnt3 + $lsc_mnt3) / $pembagi3;
                        } else {
                            $ls_mnt3_all = $cekshif3->row("ls_mnt"); 
                        }
                    }
                    $all_LS = intval($ls1_all) + intval($ls2_all) + intval($ls3_all);
                    $all_mntLS = intval($ls_mnt1_all) + intval($ls_mnt2_all) + intval($ls_mnt3_all);
                    $rtlost1 = $all_mntLS / $all_LS;
                    if(is_nan($rtlost1)){
                        $rtlost1 = "0";
                    }
                    if($cekshif1->row("pkn")==""){ $pkn1_all = 0; } else { 
                        $pkn1=$cekshif1->row("pkn"); 
                        if($bagi1!=0){
                            $pembagi1 = 1 + $bagi1;
                            $pkn1_all = ($pkn1 + $pkn_cb1) / $pembagi1;
                        } else {
                            $pkn1_all = $cekshif1->row("pkn"); 
                        }
                    }
                    if($cekshif2->row("pkn")==""){ $pkn2_all = 0; } else { 
                        $pkn2=$cekshif2->row("pkn"); 
                        if($bagi2!=0){
                            $pembagi2 = 1 + $bagi2;
                            $pkn2_all = ($pkn2 + $pkn_cb2) / $pembagi2;
                        } else {
                            $pkn2_all = $cekshif2->row("pkn"); 
                        }
                    }
                    if($cekshif3->row("pkn")==""){ $pkn3_all = 0; } else { 
                        $pkn3=$cekshif3->row("pkn"); 
                        if($bagi3!=0){
                            $pembagi3 = 1 + $bagi3;
                            $pkn3_all = ($pkn3 + $pkn_cb3) / $pembagi3;
                        } else {
                            $pkn3_all = $cekshif3->row("pkn"); 
                        }
                    }
                    if($cekshif1->row("mnt")==""){ $mnt1_all = 0; } else { 
                        $mnt1=$cekshif1->row("mnt"); 
                        if($bagi1!=0){
                            $pembagi1 = 1 + $bagi1;
                            $mnt1_all = ($mnt1 + $mnt_cb1) / $pembagi1;
                        } else {
                            $mnt1_all = $cekshif1->row("mnt"); 
                        }
                    }
                    if($cekshif2->row("mnt")==""){ $mnt2_all = 0; } else { 
                        $mnt2=$cekshif2->row("mnt"); 
                        if($bagi2!=0){
                            $pembagi2 = 1 + $bagi2;
                            $mnt2_all = ($mnt2 + $mnt_cb2) / $pembagi2;
                        } else {
                            $mnt2_all = $cekshif2->row("mnt"); 
                        }
                    }
                    if($cekshif3->row("mnt")==""){ $mnt3_all = 0; } else { 
                        $mnt3=$cekshif3->row("mnt"); 
                        if($bagi3!=0){
                            $pembagi3 = 1 + $bagi3;
                            $mnt3_all = ($mnt3 + $mnt_cb3) / $pembagi3;
                        } else {
                            $mnt3_all = $cekshif3->row("mnt"); 
                        }
                    }
                    $all_Pkn = intval($pkn1_all) + intval($pkn2_all) + intval($pkn3_all);
                    $all_mntPkn = intval($mnt1_all) + intval($mnt2_all) + intval($mnt3_all);
                    //$rtlost2 = $all_mntPkn / $all_Pkn;
                    if($all_Pkn>0){
                        $rtlost2 = $all_mntPkn / $all_Pkn;
                    } else {
                        $rtlost2 = 0;
                    }
                    if(is_nan($rtlost2)){
                        $rtlost2 = 0;
                    }
                    if($cekshif1->row("eff")==""){ $eff_sif1_all = 0; } else { 
                        $eff_sif1=$cekshif1->row("eff"); 
                        if($bagi1!=0){
                            $pembagi1 = 1 + $bagi1;
                            $eff_sif1_all = ($eff_sif1 + $eff_cb1) / $pembagi1;
                        } else {
                            $eff_sif1_all = $cekshif1->row("eff"); 
                        }
                    }
                    if($cekshif2->row("eff")==""){ $eff_sif2_all = 0; } else { 
                        $eff_sif2=$cekshif2->row("eff"); 
                        if($bagi2!=0){
                            $pembagi2 = 1 + $bagi2;
                            $eff_sif2_all = ($eff_sif2 + $eff_cb2) / $pembagi2;
                        } else {
                            $eff_sif2_all = $cekshif2->row("eff"); 
                        }
                    }
                    if($cekshif3->row("eff")==""){ $eff_sif3_all = 0; } else { 
                        $eff_sif3=$cekshif3->row("eff"); 
                        if($bagi3!=0){
                            $pembagi3 = 1 + $bagi3;
                            $eff_sif3_all = ($eff_sif3 + $eff_cb3) / $pembagi3;
                        } else {
                            $eff_sif3_all = $cekshif3->row("eff"); 
                        }
                    }
                    if($cekshif1->row("produksi")==""){ $prod1_all = 0; } else { 
                        $prodsif1=$cekshif1->row("produksi"); 
                        if($bagi1!=0){
                            $pembagi1 = 1 + $bagi1;
                            $prod1_all = ($prodsif1 + $prod_cb1) / $pembagi1;
                        } else {
                            $prod1_all = $cekshif1->row("produksi"); 
                        }
                    }
                    if($cekshif2->row("produksi")==""){ $prod2_all = 0; } else { 
                        $prodsif2=$cekshif2->row("produksi"); 
                        if($bagi2!=0){
                            $pembagi2 = 1 + $bagi2;
                            $prod2_all = ($prodsif2 + $prod_cb2) / $pembagi2;
                        } else {
                            $prod2_all = $cekshif2->row("produksi"); 
                        }
                    }
                    if($cekshif3->row("produksi")==""){ $prod3_all = 0; } else { 
                        $prodsif3=$cekshif3->row("produksi"); 
                        if($bagi3!=0){
                            $pembagi3 = 1 + $bagi3;
                            $prod3_all = ($prodsif3 + $prod_cb3) / $pembagi3;
                        } else {
                            $prod3_all = $cekshif3->row("produksi"); 
                        }
                    }

                    $eff = $eff_sif1_all + $eff_sif2_all + $eff_sif3_all;
                    $produksi = $prod1_all + $prod2_all + $prod3_all;
                    $jml_eff = round($eff,1);
                    $jml_effbagi3 = $jml_eff / 3;
                    $jml_effbagi3_koma1 = round($jml_effbagi3,1);
                    $eff_percen = $jml_effbagi3_koma1 / 100;

                    if($cekshif1->row("rpm")==""){ $rpm1_all = 0; } else { 
                        $rpm1=$cekshif1->row("rpm"); 
                        if($bagi1!=0){
                            $pembagi1 = 1 + $bagi1;
                            $rpm1_all = ($rpm1 + $rpm_cb1) / $pembagi1;
                        } else {
                            $rpm1_all = $cekshif1->row("rpm"); 
                        }
                    }
                    if($cekshif2->row("rpm")==""){ $rpm2_all = 0; } else { 
                        $rpm2=$cekshif2->row("rpm"); 
                        if($bagi2!=0){
                            $pembagi2 = 1 + $bagi2;
                            $rpm2_all = ($rpm2 + $rpm_cb2) / $pembagi2;
                        } else {
                            $rpm2_all = $cekshif2->row("rpm"); 
                        }
                    }
                    if($cekshif3->row("rpm")==""){ $rpm3_all = 0; } else { 
                        $rpm3=$cekshif3->row("rpm"); 
                        if($bagi3!=0){
                            $pembagi3 = 1 + $bagi3;
                            $rpm3_all = ($rpm3 + $rpm_cb3) / $pembagi3;
                        } else {
                            $rpm3_all = $cekshif3->row("rpm"); 
                        }
                    }
                    
                    $rpm4 = ($rpm1_all + $rpm2_all + $rpm3_all) / 3;
                    $rpm = round($rpm4);

                    if($cekshif1->row("pick")==""){ $pick1_all = 0; } else { 
                        $pick1=$cekshif1->row("pick"); 
                        if($bagi1!=0){
                            $pembagi1 = 1 + $bagi1;
                            $pick1_all = ($pick1 + $pick_cb1) / $pembagi1;
                        } else {
                            $pick1_all = $cekshif1->row("pick"); 
                        }
                    }
                    if($cekshif2->row("pick")==""){ $pick2_all = 0; } else { 
                        $pick2=$cekshif2->row("pick"); 
                        if($bagi2!=0){
                            $pembagi2 = 1 + $bagi2;
                            $pick2_all = ($pick2 + $pick_cb2) / $pembagi2;
                        } else {
                            $pick2_all = $cekshif2->row("pick"); 
                        }
                    }
                    if($cekshif3->row("pick")==""){ $pick3 = 0; } else { 
                        $pick3=$cekshif3->row("pick");
                        if($bagi3!=0){
                            $pembagi3 = 1 + $bagi3;
                            $pick3_all = ($pick3 + $pick_cb3) / $pembagi3;
                        } else {
                            $pick3_all = $cekshif3->row("pick"); 
                        } 
                    }
                    $pick4 = ($pick1_all + $pick2_all + $pick3_all) / 3;
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
                    <td style="text-align:center;<?=$rtlost2<1?'background:red;color:#fff;':'';?>"><?=$rtlost2<1 ?0:round($rtlost2,1);?></td>
                    <td style="text-align:center;<?=$jml_effbagi3<1 ?'background:red;color:#000;':'';?><?=$jml_effbagi3>0 && $jml_effbagi3<75 ?'background:orange;color:#000;':'';?>"><?=round($jml_effbagi3,1);?> %</td>
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
                <?php //mulai perhitungan akumulasi 3 shift
                    $_tripelCek = $this->data_model->get_byid('akumulasitigashift', ['tgl_akumulasi' => $tgl, 'nomesin' => $val->no_mesin]);
                    if($_tripelCek->num_rows() == 0){
                        $this->data_model->saved('akumulasitigashift', [
                            'tgl_akumulasi' => $tgl,
                            'nomesin' => $val->no_mesin,
                            'rata_rpm' => $rpm,
                            'ls' => $all_LS,
                            'ls_mnt' => $all_mntLS,
                            'rtlost1' => round($rtlost1,1),
                            'pkn' => $all_Pkn,
                            'pkn_mnt' => $all_mntPkn,
                            'rtlost2' => round($rtlost2,1),
                            'eff' => round($jml_effbagi3,1),
                            'produksi' => $produksi,
                            'prod_teo' => round($protis3,1),
                            'eff_rill' => $percen_kan2,
                            'prod_100' => round($produksi_100,2),
                            'selisih_prod' => round($selisih1,2),
                            'eff_counter' => round($selisih2,2),
                            'status_f' => $_status,
                        ]);
                    }
                    if($_tripelCek->num_rows() == 1){
                        $id_akm = $_tripelCek->row("id_akm");
                        $this->data_model->updatedata('id_akm',$id_akm,'akumulasitigashift', [
                            'rata_rpm' => $rpm,
                            'ls' => $all_LS,
                            'ls_mnt' => $all_mntLS,
                            'rtlost1' => round($rtlost1,1),
                            'pkn' => $all_Pkn,
                            'pkn_mnt' => $all_mntPkn,
                            'rtlost2' => round($rtlost2,1),
                            'eff' => round($jml_effbagi3,1),
                            'produksi' => $produksi,
                            'prod_teo' => round($protis3,1),
                            'eff_rill' => $percen_kan2,
                            'prod_100' => round($produksi_100,2),
                            'selisih_prod' => round($selisih1,2),
                            'eff_counter' => round($selisih2,2),
                            'status_f' => $_status,
                        ]);
                    }
                    //end simpan perhitungan
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
                    if($cekshif1->row("mati_or_not") == 0){
                        $xket = $cekshif1->row("xket");
                        ?><tr class="trbgbg" onmouseover="showTooltip(this, '<?=$xket;?>')" onmouseout="hideTooltip()"><?php
                        echo "<th style='text-align:center;'>".$n."</th>";
                        echo "<td style='text-align:center;'>".$_mc."</td>";
                        if($cekshif1->num_rows() == 1){
                        echo "<td colspan='15' style='color:red'>Mesin Mati, Ket : ".$cekshif1->row("xket")."</td>";
                        } else {
                            echo "<td colspan='15' style='color:red'>Tidak di input</td>";
                        }
                        echo "</tr>";
                    } else {
                    $_id = sha1($cekshif1->row("id_loom"));
                    $_id2 = $cekshif1->row("id_loom");
                    $_kons = $cekshif1->row("konstruksi");
                    
                    $cek_produksi_lain = $this->data_model->get_byid('loom_counter2', ['id_loom_asli'=>$_id2]);
                    if($cek_produksi_lain->num_rows() == 0){
                        $rowspan = 0;
                        echo "0";
                    } else {
                        $rowspan = 1 + intval($cek_produksi_lain->num_rows());
                        echo "1";
                    }
                    $sesuai = "ya";
                    $xket = $cekshif1->row("xket");
                ?>
                <tr class="trbgbg" onmouseover="showTooltip(this, '<?=$xket;?>')" onmouseout="hideTooltip()">
                    <th <?=$rowspan==0 ? '':'rowspan="'.$rowspan.'"';?>><?=$n;?></th>
                    <td style="text-align:center;" <?=$rowspan==0 ? '':'rowspan="'.$rowspan.'"';?>><?=$_mc;?></td>
                    <?php
                        if($cekshif1->num_rows() == 0){
                            echo "<td colspan='14' style='font-size:12px;color:red;'>Belum ada data</td>";
                        } else {
                        if($cekshif1->row("rpm")==0){ 
                            $rpm=0; 
                            $cek_rata = $this->db->query("SELECT * FROM loom_counter WHERE tgl='$_tgl' AND shift_mesin='$sif' AND konstruksi='$_kons' AND rpm!=0");
                            $jumlah_mesin_jalan_perkons1 = $cek_rata->num_rows();
                            $_rata_cekrpm = $this->db->query("SELECT SUM(rpm) AS ukr FROM loom_counter WHERE tgl='$_tgl' AND shift_mesin='$sif' AND konstruksi='$_kons'")->row("ukr");
                            $_nilai_rata_rpm = intval($_rata_cekrpm) / $jumlah_mesin_jalan_perkons1;
                        } else { $rpm=$cekshif1->row("rpm"); }
                        if($cekshif1->row("pick")==0){ 
                            $pick=0; 
                            $cek_rata = $this->db->query("SELECT * FROM loom_counter WHERE tgl='$_tgl' AND shift_mesin='$sif' AND konstruksi='$_kons' AND pick!=0");
                            $jumlah_mesin_jalan_perkons2 = $cek_rata->num_rows();
                            $_rata_cekpick = $this->db->query("SELECT SUM(pick) AS ukr FROM loom_counter WHERE tgl='$_tgl' AND shift_mesin='$sif' AND konstruksi='$_kons'")->row("ukr");
                            $_nilai_rata_pick = intval($_rata_cekpick) / $jumlah_mesin_jalan_perkons2;
                        } else { $pick=$cekshif1->row("pick"); }
                        if($cekshif1->row("ls")==0){ 
                            $ls=0; 
                            $cek_rata = $this->db->query("SELECT * FROM loom_counter WHERE tgl='$_tgl' AND shift_mesin='$sif' AND konstruksi='$_kons' AND ls!=0");
                            $jumlah_mesin_jalan_perkons3 = $cek_rata->num_rows();
                            $_rata_cekls = $this->db->query("SELECT SUM(ls) AS ukr FROM loom_counter WHERE tgl='$_tgl' AND shift_mesin='$sif' AND konstruksi='$_kons'")->row("ukr");
                            $_nilai_rata_ls = intval($_rata_cekls) / $jumlah_mesin_jalan_perkons3;
                        } else { $ls=$cekshif1->row("ls"); }
                        if($cekshif1->row("ls_mnt")==0){ 
                            $ls_mnt=0; 
                            $cek_rata = $this->db->query("SELECT * FROM loom_counter WHERE tgl='$_tgl' AND shift_mesin='$sif' AND konstruksi='$_kons' AND ls_mnt!=0");
                            $jumlah_mesin_jalan_perkons4 = $cek_rata->num_rows();
                            $_rata_ceklsmnt = $this->db->query("SELECT SUM(ls_mnt) AS ukr FROM loom_counter WHERE tgl='$_tgl' AND shift_mesin='$sif' AND konstruksi='$_kons'")->row("ukr");
                            $_nilai_rata_lsmnt = intval($_rata_ceklsmnt) / $jumlah_mesin_jalan_perkons4;
                        } else { $ls_mnt=$cekshif1->row("ls_mnt"); }
                        if($cekshif1->row("rt_lost_ls")==0){ 
                            $rt_lost_ls_mnt_real=0; 
                            // if($ls==0 AND $ls_mnt==0){
                            //     $rt_lost_ls_mnt = $_nilai_rata_lsmnt / $_nilai_rata_ls;
                            //     $rt_lost_ls_mnt_real = round($rt_lost_ls_mnt,1);
                            // } elseif($ls==0 AND $ls_mnt!=0){
                            //     $rt_lost_ls_mnt = $ls_mnt / $_nilai_rata_ls;
                            //     $rt_lost_ls_mnt_real = round($rt_lost_ls_mnt,1);
                            // } elseif($ls!=0 AND $ls_mnt==0){
                            //     $rt_lost_ls_mnt = $_nilai_rata_lsmnt / $ls;
                            //     $rt_lost_ls_mnt_real = round($rt_lost_ls_mnt,1);
                            // }
                        } else { 
                            $rt_lost_ls=$cekshif1->row("rt_lost_ls"); 
                            $rt_lost_ls_mnt = $rt_lost_ls / 60;
                            $rt_lost_ls_mnt_real = round($rt_lost_ls_mnt,1);
                        }
                        if($cekshif1->row("pkn")==0){ 
                            $pkn=0;
                            $cek_rata = $this->db->query("SELECT * FROM loom_counter WHERE tgl='$_tgl' AND shift_mesin='$sif' AND konstruksi='$_kons' AND pkn!=0");
                            $jumlah_mesin_jalan_perkons5 = $cek_rata->num_rows();
                            $_rata_cekpkn = $this->db->query("SELECT SUM(pkn) AS ukr FROM loom_counter WHERE tgl='$_tgl' AND shift_mesin='$sif' AND konstruksi='$_kons'")->row("ukr");
                            $_nilai_rata_pkn = intval($_rata_cekpkn) / $jumlah_mesin_jalan_perkons5;
                        } else { $pkn=$cekshif1->row("pkn"); }
                        if($cekshif1->row("mnt")==0){ 
                            $mnt=0; 
                            $cek_rata = $this->db->query("SELECT * FROM loom_counter WHERE tgl='$_tgl' AND shift_mesin='$sif' AND konstruksi='$_kons' AND pkn!=0");
                            $jumlah_mesin_jalan_perkons6 = $cek_rata->num_rows();
                            $_rata_cekmnt = $this->db->query("SELECT SUM(pkn) AS ukr FROM loom_counter WHERE tgl='$_tgl' AND shift_mesin='$sif' AND konstruksi='$_kons'")->row("ukr");
                            $_nilai_rata_mnt = intval($_rata_cekmnt) / $jumlah_mesin_jalan_perkons6;
                        } else { $mnt=$cekshif1->row("mnt"); }
                        if($cekshif1->row("rt_lost_pkn")==0){ 
                            $rt_lost_pkn_mnt_real=0;
                            // if($pkn==0 AND $mnt==0){
                            //     $rt_lost_pkn_mnt = $_nilai_rata_mnt / $_nilai_rata_pkn;
                            //     $rt_lost_pkn_mnt_real = round($rt_lost_pkn_mnt,1);
                            // } elseif($pkn==0 AND $mnt!=0){
                            //     $rt_lost_pkn_mnt = $mnt / $_nilai_rata_pkn;
                            //     $rt_lost_pkn_mnt_real = round($rt_lost_pkn_mnt,1);
                            // } elseif($pkn!=0 AND $mnt==0){
                            //     $rt_lost_pkn_mnt = $_nilai_rata_mnt / $pkn;
                            //     $rt_lost_pkn_mnt_real = round($rt_lost_pkn_mnt,1);
                            // }
                        } else { 
                            $rt_lost_pkn=$cekshif1->row("rt_lost_pkn"); 
                            $rt_lost_pkn_mnt = $rt_lost_pkn / 60;
                            $rt_lost_pkn_mnt_real = round($rt_lost_pkn_mnt,1);
                        }
                        if($cekshif1->row("eff")==0){ 
                            $eff=0; 
                            $cek_rata = $this->db->query("SELECT * FROM loom_counter WHERE tgl='$_tgl' AND shift_mesin='$sif' AND konstruksi='$_kons' AND eff!=0");
                            $jumlah_mesin_jalan_perkons7 = $cek_rata->num_rows();
                            $_rata_cekeff = $this->db->query("SELECT SUM(eff) AS ukr FROM loom_counter WHERE tgl='$_tgl' AND shift_mesin='$sif' AND konstruksi='$_kons'")->row("ukr");
                            $_nilai_rata_eff = intval($_rata_cekeff) / $jumlah_mesin_jalan_perkons7;
                        } else { $eff=$cekshif1->row("eff"); }
                        if($cekshif1->row("produksi")==0){ 
                            $produksi=0; 
                            $cek_rata = $this->db->query("SELECT * FROM loom_counter WHERE tgl='$_tgl' AND shift_mesin='$sif' AND konstruksi='$_kons' AND produksi!=0");
                            $jumlah_mesin_jalan_perkons8 = $cek_rata->num_rows();
                            $_rata_cekprd = $this->db->query("SELECT SUM(produksi) AS ukr FROM loom_counter WHERE tgl='$_tgl' AND shift_mesin='$sif' AND konstruksi='$_kons'")->row("ukr");
                            $_nilai_rata_prd = intval($_rata_cekprd) / $jumlah_mesin_jalan_perkons8;
                        } else { $produksi=$cekshif1->row("produksi"); }
                        if($cekshif1->row("produksi_teoritis")==0){ 
                            $produksi_teoritis=0;
                            if($eff=="0"){ $_rms_eff= round($_nilai_rata_eff,1); } else { $_rms_eff = round($eff,1); }
                            if($rpm=="0"){ $_rms_rpm= round($_nilai_rata_rpm); } else { $_rms_rpm = round($rpm); }
                            if($pick=="0"){ $_rms_pick= round($_nilai_rata_pick); } else { $_rms_pick = round($pick); }
                            $_rms_eff2 = $_rms_eff / 100;
                            $_rms_eff3 = round($_rms_eff2,2);
                            $_rms_prodteo = $_rms_eff3 * $_rms_rpm * 60 * 0.0254 * 8 / $_rms_pick;
                        } else { $produksi_teoritis=$cekshif1->row("produksi_teoritis"); }
                        if($cekshif1->row("presentase_teoritis")==0){ $presentase_teoritis=0; } else { $presentase_teoritis=$cekshif1->row("presentase_teoritis"); }
                        if($rpm=="0"){ $_rms_rpm1= round($_nilai_rata_rpm); } else { $_rms_rpm1 = round($rpm); }
                        if($pick=="0"){ $_rms_pick1= round($_nilai_rata_pick); } else { $_rms_pick1 = round($pick); }
                        $new_presen_teo = (intval($_rms_rpm1) / intval($_rms_pick1)) * 60 * 8 * 0.0254 * 0.80;
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
                    <td style="text-align:center;"><?=strtoupper($_kons);?></td>
                    <td style="text-align:center;<?=$rpm==0?'background:red;color:#fff;cursor:help;':'';?>">
                        <?php 
                            // if($rpm==0){
                            //    <a style="text-decoration:none;color:#fff;" onclick="auto_rata('<?=$jumlah_mesin_jalan_perkons1;','<?=$_kons;')"><?=round($_nilai_rata_rpm);</a>; <?php
                            // } else {
                                echo $rpm;
                            //}
                        ?>
                    </td>
                    <td style="text-align:center;<?=$pick==0?'background:red;color:#fff;cursor:help;':'';?>">
                        <?php 
                            // if($pick==0){
                            //     <a style="text-decoration:none;color:#fff;" onclick="auto_rata('<?=$jumlah_mesin_jalan_perkons2;','<?=$_kons;')"><?=round($_nilai_rata_pick);</a>; <?php
                            // } else {
                                echo $pick;
                            //}
                        ?>
                    </td>
                    <td style="text-align:center;<?=$ls==0?'background:red;color:#fff;cursor:help;':'';?>">
                        <?php 
                            // if($ls==0){
                            //     <a style="text-decoration:none;color:#fff;" onclick="auto_rata('<?=$jumlah_mesin_jalan_perkons3;','<?=$_kons;')"><?=round($_nilai_rata_ls);</a><?php
                            // } else {
                                echo $ls;
                            //}
                        ?>
                    </td>
                    <td style="text-align:center;<?=$ls_mnt==0?'background:red;color:#fff;cursor:help;':'';?>">
                    <?php 
                            // if($ls_mnt==0){
                            //     <a style="text-decoration:none;color:#fff;" onclick="auto_rata('<?=$jumlah_mesin_jalan_perkons4;','<?=$_kons;')"><?=round($_nilai_rata_lsmnt,1);</a><?php
                            // } else {
                                echo $ls_mnt;
                            //}
                        ?>
                    </td>
                    <td style="text-align:center;<?=$rt_lost_ls_mnt_real==0?'background:red;color:#fff;':'';?>"><?=$rt_lost_ls_mnt_real;?></td>
                    <td style="text-align:center;<?=$pkn==0?'background:red;color:#fff;cursor:help;':'';?>">
                        <?php 
                            // if($pkn==0){
                            //     <a style="text-decoration:none;color:#fff;" onclick="auto_rata('<?=$jumlah_mesin_jalan_perkons5;','<?=$_kons;')"><?=round($_nilai_rata_pkn);</a>
                            // } else {
                                echo $pkn;
                            //}
                        ?>
                    </td>
                    <td style="text-align:center;<?=$mnt==0?'background:red;color:#fff;cursor:help;':'';?>">
                        <?php 
                            // if($mnt==0){
                            //     <a style="text-decoration:none;color:#fff;" onclick="auto_rata('<?=$jumlah_mesin_jalan_perkons6;','<?=$_kons;')"><?=round($_nilai_rata_mnt);</a><?php
                            // } else {
                                echo $mnt;
                            //}
                        ?>
                    </td>
                    <td style="text-align:center;<?=$rt_lost_pkn_mnt_real==0?'background:red;color:#fff;':'';?>"><?=$rt_lost_pkn_mnt_real;?></td>
                    <td style="text-align:center;<?=$eff<1?'background:red;color:#fff;':'';?><?=$eff>0 && $eff<75?'background:orange;color:#000;':'';?>">
                        <?php 
                            // if($eff==0){
                            //     <a style="text-decoration:none;color:#fff;cursor:help;" onclick="auto_rata('<?=$jumlah_mesin_jalan_perkons7;','<?=$_kons;')"><?=round($_nilai_rata_eff,1);%</a><?php
                            // } else {
                                echo $eff;
                                if($eff!=0){ echo "%"; }
                            //}
                        ?>
                    </td>
                    <td style="text-align:center;<?=$produksi==0?'background:red;color:#fff;':'';?>">
                        <?php 
                            // if($produksi==0){
                            //     <a style="text-decoration:none;color:#fff;cursor:help;" onclick="auto_rata('<?=$jumlah_mesin_jalan_perkons8;','<?=$_kons;')"><?=round($_nilai_rata_prd,1);</a><?php
                            // } else {
                                echo $produksi;
                            //}
                        ?>
                    </td>
                    <td style="text-align:center;<?=$produksi_teoritis==0?'background:red;color:#fff;':'';?>">
                        <?php 
                            // if($produksi_teoritis==0){
                            //     <a style="text-decoration:none;color:#fff;cursor:help;" onclick="auto_rata2()"><?=round($_rms_prodteo,1);</a><?php
                            // } else {
                                echo $produksi_teoritis;
                            //}
                        ?>
                    </td>
                    <td style="text-align:center;<?=$new_presen_teo_1==0?'background:red;color:#fff;':'';?>"><?=$new_presen_teo_1;?></td>

                    <?php if($sesuai=="ya") { ?>
                        <td style="text-align:center;"><?=$cekshif1->row("yg_input");?></td>
                    <?php } else { ?>
                    <td style="text-align:center;background:red;color:#fff;cursor:pointer;'" onclick="notsesuai()"><?=$cekshif1->row("yg_input");?></td>
                    <?php } ?>

                    <td style="text-align:center;"><a href="<?=base_url('monitoring/loom/'.$_id);?>"><span class="material-symbols-outlined" style="color:green;font-size:18px;cursor:pointer;" title="Edit Data">edit</span></a></td>
                    <?php } ?>
                </tr>
                <?php $sesuai = "ya";
                    if($cek_produksi_lain->num_rows() > 0){ 
                        foreach($cek_produksi_lain->result() as $hol){
                            if($hol->rpm==0){ $rpm=0; } else { $rpm=$hol->rpm; }
                            if($hol->pick==0){ $pick=0; } else { $pick=$hol->pick; }
                            if($hol->ls==0){ $ls=0; } else { $ls=$hol->ls; }
                            if($hol->ls_mnt==0){ $ls_mnt=0; } else { $ls_mnt=$hol->ls_mnt; }
                            if($hol->rt_lost_ls==0){ $rt_lost_ls_mnt_real=0; } else { 
                                $rt_lost_ls=$hol->rt_lost_ls; 
                                $rt_lost_ls_mnt = $rt_lost_ls / 60;
                                $rt_lost_ls_mnt_real = round($rt_lost_ls_mnt,1);
                            }
                            if($hol->pkn==0){ $pkn=0; } else { $pkn=$hol->pkn; }
                            if($hol->mnt==0){ $mnt=0; } else { $mnt=$hol->mnt; }
                            if($hol->rt_lost_pkn==0){ $rt_lost_pkn_mnt_real=0; } else { 
                                $rt_lost_pkn=$hol->rt_lost_pkn; 
                                $rt_lost_pkn_mnt = $rt_lost_pkn / 60;
                                $rt_lost_pkn_mnt_real = round($rt_lost_pkn_mnt,1);
                            }
                            if($hol->eff==0){ $eff=0; } else { $eff=$hol->eff; }
                            if($hol->produksi==0){ $produksi=0; } else { $produksi=$hol->produksi; }
                            if($hol->produksi_teoritis==0){ $produksi_teoritis=0; } else { $produksi_teoritis=$hol->produksi_teoritis; }
                            if($hol->presentase_teoritis==0){ $presentase_teoritis=0; } else { $presentase_teoritis=$hol->presentase_teoritis; }
                            $new_presen_teo = (intval($rpm) / intval($pick)) * 60 * 8 * 0.0254 * 0.80;
                            $new_presen_teo_1 = round($new_presen_teo,1);
                            $_exk = explode(' ', $hol->tm_input);
                            $_tgl_input = $_exk[0];
                            $_jam_input = $_exk[1];
                            $_jam_pecah = explode(':', $_jam_input);
                            $currentTime = $_jam_pecah[0].":".$_jam_pecah[1];
                            $_tgl_shift = $hol->tgl;
                            $_shift = $hol->shift_mesin;
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
                <!-- proses produksi kedua -->
                <tr class="trbgbg">
                    <td style="text-align:center;"><?=strtoupper($hol->konstruksi);?></td>
                    <td style="text-align:center;<?=$rpm==0?'background:red;color:#fff;':'';?>"><?=$rpm;?></td>
                    <td style="text-align:center;<?=$pick==0?'background:red;color:#fff;':'';?>"><?=$pick;?></td>
                    <td style="text-align:center;<?=$ls==0?'background:red;color:#fff;':'';?>"><?=$ls;?></td>
                    <td style="text-align:center;<?=$ls_mnt==0?'background:red;color:#fff;':'';?>"><?=$ls_mnt;?></td>
                    <td style="text-align:center;<?=$rt_lost_ls_mnt_real==0?'background:red;color:#fff;':'';?>"><?=$rt_lost_ls_mnt_real;?></td>
                    <td style="text-align:center;<?=$pkn==0?'background:red;color:#fff;':'';?>"><?=$pkn;?></td>
                    <td style="text-align:center;<?=$mnt==0?'background:red;color:#fff;':'';?>"><?=$mnt;?></td>
                    <td style="text-align:center;<?=$rt_lost_pkn_mnt_real==0?'background:red;color:#fff;':'';?>"><?=$rt_lost_pkn_mnt_real;?></td>
                    <td style="text-align:center;<?=$eff<1?'background:red;color:#fff;':'';?><?=$eff>0 && $eff<75?'background:orange;color:#000;':'';?>"><?=$eff;?> <?=$eff==0?'':'%';?></td>
                    <td style="text-align:center;<?=$produksi==0?'background:red;color:#fff;':'';?>"><?=$produksi;?></td>
                    <td style="text-align:center;<?=$produksi_teoritis==0?'background:red;color:#fff;':'';?>"><?=$produksi_teoritis;?></td>
                    <td style="text-align:center;<?=$new_presen_teo_1==0?'background:red;color:#fff;':'';?>"><?=$new_presen_teo_1;?></td>

                    <?php if($sesuai=="ya") { ?>
                        <td style="text-align:center;"><?=$hol->yg_input;?></td>
                    <?php } else { ?>
                    <td style="text-align:center;background:red;color:#fff;cursor:pointer;'" onclick="notsesuai()"><?=$hol->yg_input;?></td>
                    <?php } ?>

                    <td style="text-align:center;"><a href="<?=base_url('monitoring/loom2/'.sha1($hol->id_loom2));?>"><span class="material-symbols-outlined" style="color:green;font-size:18px;cursor:pointer;" title="Edit Data">edit</span></a></td>
                </tr>
                <!-- end proses produksi kedua -->
                <?php   }
                    }
                } $n++; 
                    endforeach;
                    } else {
                        echo "<tr style='background:#ebebeb;border:1px solid #000;'><td colspan='16' style='padding:5px;'>Tidak ada data</td></tr>";
                    }
                ?>
            </tbody>
            <?php
        }
  } //end
  function showLoomCounter3(){
      echo "owek";
  }

  function showLoomCounter(){
        $op = $this->input->post('namas');
        $ar = array(
            '00' => 'NaN', '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'
        );
        //$tgl = date('Y-m-d');
        $currentTime = date('H:i');
        if (strtotime($currentTime) >= strtotime('14:00') && strtotime($currentTime) < strtotime('22:01')) { 
            $sesuai = "1";
            $tgl_cetak = date('Y-m-d');
        } elseif(strtotime($currentTime) >= strtotime('22:01') && strtotime($currentTime) < strtotime('23:59')) { 
            $sesuai = "2"; 
            $tgl_cetak = date('Y-m-d');
        } elseif(strtotime($currentTime) >= strtotime('00:00') && strtotime($currentTime) < strtotime('05:59')){
            $sesuai = "2"; 
            $tgl_now = date('Y-m-d');
            $tgl_cetak = date('Y-m-d', strtotime('-1 day', strtotime($tgl_now)));
        } elseif(strtotime($currentTime) >= strtotime('06:00') && strtotime($currentTime) < strtotime('13:59')){
            $sesuai = "3";
            $tgl_now = date('Y-m-d');
            $tgl_cetak = date('Y-m-d', strtotime('-1 day', strtotime($tgl_now)));
        }
        // $tgl_sekarang = date('Y-m-d');
        // $tgl_sebelumnya = date('Y-m-d', strtotime('-1 day', strtotime($tgl_sekarang)));
        if($op=="Septi Diah" OR $op =="Admin AJL" OR $op =="Arrum"){
            $cek = $this->db->query("SELECT * FROM loom_counter WHERE tgl='$tgl_cetak' AND shift_mesin='$sesuai' ORDER BY no_mesin");
        } else {
        $cek = $this->db->query("SELECT * FROM loom_counter WHERE yg_input='$op' AND tgl='$tgl_cetak' AND shift_mesin='$sesuai' ORDER BY no_mesin"); }
        if($cek->num_rows()>0){
            $n=1;
            $dt_fix = 0;
            foreach($cek->result() as $val):
                $idloom = $val->id_loom;
                $onoff = $val->mati_or_not;
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
                if($val->konstruksi == "null"){
                    echo "<td style='padding:3px;font-size:12px;text-align:center;'>-</td>";    
                 } else {
                echo "<td style='padding:3px;font-size:12px;text-align:center;'>".$val->konstruksi."</td>"; }
                
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
                if($onoff == 0){
                    if($op=="Septi Diah" OR $op =="Admin AJL" OR $op =="Arrum"){
                        echo "<td style='padding:3px;font-size:12px;text-align:center;'>";
                        echo "<a href='".base_url('operator/updateloom/'.sha1($val->id_loom))."' style='text-decoration:none;'>";
                        echo "<span style='padding:1px 5px;background:red;color:#fff;font-size:9px;border-radius:3px;'>OFF</span></a>";
                        echo "</td>";
                    } else {
                        echo "<td style='padding:3px;font-size:12px;text-align:center;'>";
                        echo "<span style='padding:1px 5px;background:red;color:#fff;font-size:9px;border-radius:3px;'>OFF</span>";
                        echo "</td>";
                    }
                } else {
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
                }
                echo "</tr>";
                $n++;
                $dt_fix=0;
                $cek_cabang = $this->data_model->get_byid('loom_counter2', ['id_loom_asli'=>$idloom]);
                if($cek_cabang->num_rows() > 0){
                    foreach($cek_cabang->result() as $gal){
                    echo "<tr class='trbgbg'>";
                    echo "<td style='padding:3px;font-size:12px;text-align:center;background:#f3f3f3;' colspan='4'>&nbsp;</td>";
                    echo "<td style='padding:3px;font-size:12px;text-align:center;'>".$gal->konstruksi."</td>";
                    echo "<td style='padding:3px;font-size:12px;text-align:center;'>";
                    echo "<a href='".base_url('operator/updateloom2/'.sha1($gal->id_loom2))."' style='text-decoration:none;'>";
                    echo "<span style='padding:1px 5px;background:green;color:#fff;font-size:9px;border-radius:3px;'>Done</span>";
                    echo "</a>";
                    echo "</td>";
                    echo "</tr>";
                    }
                }
            endforeach;
        } else {
            echo '<tr>
                <td colspan="5" style="padding:3px;color:red;font-size:12px;">Hari ini anda belum input...</td>
            </tr>';
        }
  } //end
function showAkumulasi2(){
      $bln = $this->input->post('daterange');
      $_query = $this->db->query("SELECT tgl,no_mesin,konstruksi,produksi FROM loom_counter WHERE DATE_FORMAT(tgl, '%Y-%m') = '$bln'");
      $kons = $this->db->query("SELECT DISTINCT konstruksi FROM loom_counter WHERE DATE_FORMAT(tgl, '%Y-%m') = '$bln'");
      if($_query->num_rows() > 0){
        $no=1;
        foreach($kons->result() as $val){
            if($val->konstruksi == "null"){} else {
            $_kons = $val->konstruksi;
            $_sum = $this->db->query("SELECT SUM(produksi) AS total FROM loom_counter WHERE DATE_FORMAT(tgl, '%Y-%m') = '$bln' AND konstruksi = '$_kons'")->row("total");
            $_mc = $this->db->query("SELECT COUNT(no_mesin) AS total FROM loom_counter WHERE DATE_FORMAT(tgl, '%Y-%m') = '$bln' AND konstruksi = '$_kons'")->row("total");
            echo "<tr class='trbgbg'>";
            echo "<td style='text-align:center;'>".$no."</td>";
            echo "<td style='text-align:center;'>".$_kons."</td>";
            echo "<td style='text-align:center;'>".$_mc."</td>";
            echo "<td style='text-align:center;'>".number_format($_sum,2,',','.')."</td>";
            echo "</tr>";
            $no++;
            }
        }
      } else {
        echo "<tr><td colspan='4'>Tidak ada data</td></tr>";
      }
  }

  function showAkumulasi(){
      $bln = $this->input->post('bln');
      $thn = $this->input->post('thn');
      $_query = $this->db->query("SELECT tgl_akumulasi,nomesin FROM akumulasitigashift WHERE MONTH(tgl_akumulasi) = '$bln' AND YEAR(tgl_akumulasi) = '$thn' GROUP BY nomesin ORDER BY nomesin ASC");
      if($_query->num_rows() > 0 ){
        $no=1;
        foreach($_query->result() as $dt):
            $_mc = $dt->nomesin;
            $jml_data2 = $this->db->query("SELECT tgl_akumulasi,nomesin FROM akumulasitigashift WHERE MONTH(tgl_akumulasi) = '$bln' AND YEAR(tgl_akumulasi) = '$thn' AND nomesin='$_mc'");
            $jml_data = $jml_data2->num_rows();

            echo "<tr class='trbgbg'>";
            echo "<td style='text-align:center;'>".$no."</td>";
            echo "<td style='text-align:center;'>".$dt->nomesin."</td>";
            //--rpm
            $rpm = $this->db->query("SELECT SUM(rata_rpm) AS jmlls FROM akumulasitigashift WHERE MONTH(tgl_akumulasi) = '$bln' AND YEAR(tgl_akumulasi) = '$thn' AND nomesin='$_mc'")->row("jmlls");
            $rpm2 = $rpm / $jml_data;
            $rpm3 = round($rpm2,2);
            echo "<td style='text-align:center;'>".round($rpm3)."</td>";
            //--end
            //--ls
            $ls = $this->db->query("SELECT SUM(ls) AS jmlls FROM akumulasitigashift WHERE MONTH(tgl_akumulasi) = '$bln' AND YEAR(tgl_akumulasi) = '$thn' AND nomesin='$_mc'")->row("jmlls");
            $ls2 = $ls / $jml_data;
            $ls3 = round($ls2,2);
            echo "<td style='text-align:center;'>$ls3</td>";
            //--end
            //--ls_mnt
            $ls_mnt = $this->db->query("SELECT SUM(ls_mnt) AS jmlls FROM akumulasitigashift WHERE MONTH(tgl_akumulasi) = '$bln' AND YEAR(tgl_akumulasi) = '$thn' AND nomesin='$_mc'")->row("jmlls");
            $ls_mnt2 = $ls_mnt / $jml_data;
            $ls_mnt3 = round($ls_mnt2,2);
            echo "<td style='text-align:center;'>$ls_mnt3</td>";
            //--end
            //--rtlost
            $rtlost1 = $this->db->query("SELECT SUM(rtlost1) AS jmlls FROM akumulasitigashift WHERE MONTH(tgl_akumulasi) = '$bln' AND YEAR(tgl_akumulasi) = '$thn' AND nomesin='$_mc'")->row("jmlls");
            $rtlost12 = $rtlost1 / $jml_data;
            $rtlost13 = round($rtlost12,2);
            echo "<td style='text-align:center;'>$rtlost13</td>";
            //--end
            //--pkn
            $pkn = $this->db->query("SELECT SUM(pkn) AS jmlls FROM akumulasitigashift WHERE MONTH(tgl_akumulasi) = '$bln' AND YEAR(tgl_akumulasi) = '$thn' AND nomesin='$_mc'")->row("jmlls");
            $pkn2 = $pkn / $jml_data;
            $pkn3 = round($pkn2,2);
            echo "<td style='text-align:center;'>$pkn3</td>";
            //--end
            //--pkn_mnt
            $pkn_mnt = $this->db->query("SELECT SUM(pkn_mnt) AS jmlls FROM akumulasitigashift WHERE MONTH(tgl_akumulasi) = '$bln' AND YEAR(tgl_akumulasi) = '$thn' AND nomesin='$_mc'")->row("jmlls");
            $pkn_mnt2 = $pkn_mnt / $jml_data;
            $pkn_mnt3 = round($pkn_mnt2,2);
            echo "<td style='text-align:center;'>$pkn_mnt3</td>";
            //--end
            //--rtlost2
            $rtlost2 = $this->db->query("SELECT SUM(rtlost2) AS jmlls FROM akumulasitigashift WHERE MONTH(tgl_akumulasi) = '$bln' AND YEAR(tgl_akumulasi) = '$thn' AND nomesin='$_mc'")->row("jmlls");
            $rtlost22 = $rtlost2 / $jml_data;
            $rtlost23 = round($rtlost22,2);
            echo "<td style='text-align:center;'>$rtlost23</td>";
            //--end
            //--eff
            $eff = $this->db->query("SELECT SUM(eff) AS jmlls FROM akumulasitigashift WHERE MONTH(tgl_akumulasi) = '$bln' AND YEAR(tgl_akumulasi) = '$thn' AND nomesin='$_mc'")->row("jmlls");
            $eff2 = $eff / $jml_data;
            $eff3 = round($eff2,2);
            echo "<td style='text-align:center;'>$eff3</td>";
            //--end
            //--produksi
            $produksi = $this->db->query("SELECT SUM(produksi) AS jmlls FROM akumulasitigashift WHERE MONTH(tgl_akumulasi) = '$bln' AND YEAR(tgl_akumulasi) = '$thn' AND nomesin='$_mc'")->row("jmlls");
            $produksi2 = $produksi / $jml_data;
            $produksi3 = round($produksi2,2);
            echo "<td style='text-align:center;'>$produksi3</td>";
            //--end
            //--produksi teo
            $produksiteo = $this->db->query("SELECT SUM(prod_teo) AS jmlls FROM akumulasitigashift WHERE MONTH(tgl_akumulasi) = '$bln' AND YEAR(tgl_akumulasi) = '$thn' AND nomesin='$_mc'")->row("jmlls");
            $produksiteo2 = $produksiteo / $jml_data;
            $produksiteo3 = round($produksiteo2,2);
            echo "<td style='text-align:center;'>$produksiteo3</td>";
            //--end
            //eff_rill
            $effrill = $this->db->query("SELECT SUM(eff_rill) AS jmlls FROM akumulasitigashift WHERE MONTH(tgl_akumulasi) = '$bln' AND YEAR(tgl_akumulasi) = '$thn' AND nomesin='$_mc'")->row("jmlls");
            $effrill2 = $effrill / $jml_data;
            $effrill3 = round($effrill2,2);
            echo "<td style='text-align:center;'>$effrill3</td>";
            //--end
            //prod_100
            $prod_100 = $this->db->query("SELECT SUM(prod_100) AS jmlls FROM akumulasitigashift WHERE MONTH(tgl_akumulasi) = '$bln' AND YEAR(tgl_akumulasi) = '$thn' AND nomesin='$_mc'")->row("jmlls");
            $prod_1002 = $prod_100 / $jml_data;
            $prod_1003 = round($prod_1002,2);
            echo "<td style='text-align:center;'>$prod_1003</td>";
            //--end
            //selisih_prod
            $selisih_prod = $this->db->query("SELECT SUM(selisih_prod) AS jmlls FROM akumulasitigashift WHERE MONTH(tgl_akumulasi) = '$bln' AND YEAR(tgl_akumulasi) = '$thn' AND nomesin='$_mc'")->row("jmlls");
            $selisih_prod2 = $selisih_prod / $jml_data;
            $selisih_prod3 = round($selisih_prod2,2);
            echo "<td style='text-align:center;'>$selisih_prod3</td>";
            //--end
            //eff_counter
            $eff_counter = $this->db->query("SELECT SUM(eff_counter) AS jmlls FROM akumulasitigashift WHERE MONTH(tgl_akumulasi) = '$bln' AND YEAR(tgl_akumulasi) = '$thn' AND nomesin='$_mc'")->row("jmlls");
            $eff_counter2 = $eff_counter / $jml_data;
            $eff_counter3 = round($eff_counter2,2);
            echo "<td style='text-align:center;'>$eff_counter3</td>";
            //--end
            echo "<td style='text-align:center;'>$jml_data</td>";
            echo "</tr>";
            $no++;
        endforeach;
      } else {
        echo "<tr>";
        echo "<td colspan='16'>Belum memiliki data Akumulasi $bln $thn</td>";
        echo "</tr>";
      }
      
  }

}
?>