<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proses extends CI_Controller
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
  
  function login(){
      $username = $this->data_model->clean($this->input->post('username'));
      $password = $this->input->post('pass');
      if($username!="" AND $password!=""){
          $cek = $this->data_model->get_byid('login_data', ['username'=>$username, 'pass'=>sha1($password)]);
          if($cek->num_rows() == 1){
              $data_session = array(
                'id' => $cek->row("id_login"),
                'nama'  => $cek->row("nama"),
                'username'=> $cek->row("username"),
                'password' => $cek->row("password"),
                'login_form'=> 'rindangjati_weaving_sess'
              );
              $this->session->set_userdata($data_session);
              echo json_encode(array("statusCode"=>200, "fix"=>"fix", "psn"=>"oke"));
          } else {
              echo json_encode(array("statusCode"=>200, "fix"=>"no", "psn"=>"User dan Password tidak sesuai"));
          }
      } else {
          echo json_encode(array("statusCode"=>404, "psn"=>"failde"));
      }
  } //end

  function inputbenang(){
        $kodetes = $this->data_model->acakKode(2);
        $nama = $this->input->post('nama');
        $bale = $this->input->post('bale');
        $netto = $this->input->post('netto');
        $karung = $this->input->post('karung');
        $ne = $this->input->post('ne');
        $tgl = $this->input->post('tgl');
        if($nama!="" AND $bale!="" AND $netto!="" AND $karung!="" AND $ne!="" AND $tgl!=""){
            do {
                $kode = $this->data_model->acakKode(2);
            } while ($this->data_model->isCodeExist($kode));
            $result = $this->data_model->saved('penerimaan_benang', [
                'nama_benang' => $nama,
                'jumlah_bale' => $bale,
                'netto' => $netto,
                'jumlah_karung' => $karung,
                'en_ee' => $ne,
                'kode_karung' => $kode,
                'tanggal_penerimaan' => $tgl,
                'tm_stamp' => date('Y-m-d H:i:s'),
                'admin' => $this->session->userdata('nama')
            ]);    
            if ($result) {
                echo "<span style='color:red;'>Gagal Menyimpan Pembelian Benang. Kode karung telah di gunakan</span> ".$result."";
                echo "<pre>";
                print_r($result);
                echo "</pre>";
            } else {
                $action = base_url('proses/savedkarung');
                echo '<form method="post" name="fr1" action="'.$action.'" style="width:100%;">';
                echo '<div class="karungan">';
                
                    for ($i=0; $i <$karung ; $i++) { 
                        $no = $i + 1;
                        echo '<div class="box-karung">';
                        echo '<span>Kode '.$kode.'-'.$no.'</span>
                        <label for="beratkarung'.$no.'">Berat Karung (Kg)</label>
                        <input type="text" id="beratkarung'.$no.'" name="beratkarung[]" class="jumlah_berat">
                        <label for="jumlahcones'.$no.'">Jumlah Cones</label>
                        <input type="text" name="cones[]" id="jumlahcones'.$no.'">';
                        if($i == 0){} else {
                        ?>
                        <div><input type="checkbox" id="cekbox<?=$no;?>" onchange="autofillUkuran('<?=$no;?>')"><label for="ckbox<?=$no;?>">Sama dengan sebelumnya</label></div><?php } ?>
                        </div>                        
                        <input type="hidden" value="<?=$kode;?>-<?=$no;?>" name="kodekarung[]">
                        <?php
                        $kodekarung = $kode."-".$no;
                        $this->data_model->saved('karung_benang', ['kode_pembelian'=>$kode,'kode_karung'=>$kodekarung,'berat_karung'=>'0','jumlah_cones'=>'0', 'nama_benang'=>$nama, 'status_karung'=>'masih', 'berat_karung_gram'=>'0']);
                    }
                    echo "<div class='box-karung'>";
                    echo '<p>Total Berat Karung <span id="total_berat">0</span></p>';
                    echo "</div>";
                echo '</div>';
                ?>
                <p>&nbsp;</p>
                <p>
                    <strong>Formula :</strong><br>
                    Masukan formula dengan format #<strong>Jumlah karung</strong>-<strong>jumlah cones</strong>-<strong>berat karung</strong><br>
                    <br>
                    Contoh Formula :<br>
                    #20-12-10#10-13-13.45<br>
                    Arti : <br>
                    #20 Karung - 12 Jumlah cones - Berat 10 Kg<br>
                    #10 Karung - 13 Jumlah cones - Berat 13.45 Kg
                </p>
                <p>&nbsp;</p>
                <textarea name="formula" id="formula" cols="30" rows="5" placeholder="Input Formula" style="padding:10px;border:1px solid #ccc;outline:none;width:300px;border-radius:10px;"></textarea>
                <p>&nbsp;</p>
                <hr>
                <?php
                echo '<button class="sbmit" type="submit">Simpan</button>';
                echo '</form>';
                ?>
                <script>
                    function autofillUkuran(index) {
                        var jum = "<?=$karung;?>";
                        //console.log('tes'+index);
                        var indexSebelum = parseInt(index) - 1;
                        var currentCheckbox = document.getElementById('cekbox'+index+'');
                        var beratsebelum = document.getElementById('beratkarung'+indexSebelum+'').value;
                        var jumlahsebelum = document.getElementById('jumlahcones'+indexSebelum+'').value;
                        if (currentCheckbox.checked){
                            document.getElementById('beratkarung'+index+'').value = ''+beratsebelum;
                            document.getElementById('jumlahcones'+index+'').value = ''+jumlahsebelum;
                        } else {
                            document.getElementById('beratkarung'+index+'').value = '';
                            document.getElementById('jumlahcones'+index+'').value = '';
                        }
                    }
                    const jumlahBeratInputs = document.querySelectorAll('.jumlah_berat');
                    const totalBeratElement = document.getElementById('total_berat');

                    jumlahBeratInputs.forEach(input => {
                    input.addEventListener('input', updateTotalBerat);
                    });

                    function updateTotalBerat() {
                    let totalBerat = 0;

                    // Menghitung total dari semua nilai textbox
                    jumlahBeratInputs.forEach(input => {
                        const inputValue = parseFloat(input.value) || 0; // Menghindari NaN jika input bukan angka
                        totalBerat += inputValue;
                    });
                    var netto = document.getElementById('netto').value;
                    if(netto == totalBerat.toFixed(2)){
                        totalBeratElement.innerHTML = '<font style="color:green;">'+totalBerat.toFixed(2)+'</font>';
                    } else {
                        totalBeratElement.innerHTML = '<font style="color:red;">'+totalBerat.toFixed(2)+'</font>';
                    }
                    // Memperbarui nilai total pada elemen HTML
                    //totalBeratElement.textContent = totalBerat;
                    }
                </script>
                <?php
            }
        } else {
            echo "<span style='color:red;'>Gagal Menyimpan Pembelian Benang. Anda tidak mengisi data dengan benar.!!</span>";
        }
  } //end

  function savedkarung(){
        $kodekarung = $this->input->post('kodekarung');
        $jmlcones = $this->input->post('cones');
        $beratkarung = $this->input->post('beratkarung');
        $formula = $this->input->post('formula');
        $kode_huruf = explode('-', $kodekarung[0]);
        $kode_huruf_real = $kode_huruf[0];
        if($formula == ""){
            for ($z=0; $z < count($kodekarung); $z++) {
                if($jmlcones[$z]!="" AND $beratkarung[$z]!=""){
                $this->data_model->updatedata('kode_karung',$kodekarung[$z], 'karung_benang', ['berat_karung'=>$beratkarung[$z], 'jumlah_cones'=>$jmlcones[$z]]); }
            }
            redirect(base_url('pembelian-benang/success'));
        } else {
            $ex = explode('#', $formula);
            // echo "<pre>";
            // print_r($ex);
            // echo "</pre>";
            // echo count($ex);
            if(count($ex) > 1){
                $great_formula = 0;
                $wrong_formula = 0;
                for ($i=0; $i < count($ex); $i++) { 
                    $nilai = explode('-', $ex[$i]);
                    if($ex[$i]!=""){
                        if(count($nilai) == 3){
                            $great_formula+=1;
                        } else {
                            $wrong_formula+=1;
                        }
                    }
                }
                if($wrong_formula==0){
                    for ($z=0; $z < count($kodekarung); $z++) { 
                        if($jmlcones[$z]!="" AND $beratkarung[$z]!=""){
                        $this->data_model->updatedata('kode_karung',$kodekarung[$z], 'karung_benang', ['berat_karung'=>$beratkarung[$z], 'jumlah_cones'=>$jmlcones[$z]]); }
                    }
                    for ($a=0; $a < count($ex); $a++){
                        if($ex[$a]!=""){
                            $nilai = explode('-', $ex[$a]);
                            if(count($nilai) == 3){
                                //echo "tes<br>";
                                $query = $this->db->query("SELECT * FROM karung_benang WHERE kode_pembelian='$kode_huruf_real' AND berat_karung='0' AND jumlah_cones='0' ORDER BY id_karung LIMIT ".$nilai[0]." ");
                                foreach ($query->result() as $key => $value) {
                                    //echo $value->id_karung."<br>";
                                    $this->data_model->updatedata('id_karung', $value->id_karung, 'karung_benang', ['berat_karung'=>$nilai[2], 'jumlah_cones'=>$nilai[1]]);
                                }
                            } else {
                                //echo "teskoprot<br>";
                            }
                        }
                    }
                    // echo "$kode_huruf_real";
                    // echo 'oke';
                    redirect(base_url('pembelian-benang/success'));
                } else {
                    echo "<div style='display:flex;justify-content:center;align-items:center'>";
                    echo "Anda tidak memasukan formula dengan benar #1.";
                    echo "</div>";
                }
                
            } else {
                echo "<div style='display:flex;justify-content:center;align-items:center'>";
                echo "Anda tidak memasukan formula dengan benar #2.";
                echo "</div>";
            }
        }
  } //end

  function update_pembelian(){
        $id_penerimaan = $this->input->post('id_penerimaan');
        $nama_benang = $this->input->post('nama_benang');
        $bale = $this->input->post('bale');
        $netto = $this->input->post('netto');
        $karung = $this->input->post('karung');
        $ne = $this->input->post('ne');
        $tgl = $this->input->post('tgl');
        $kodekarung = $this->input->post('kodekarung');
        $cones = $this->input->post('cones');
        $beratkarung = $this->input->post('beratkarung');
        if($id_penerimaan!="" AND $nama_benang!="" AND $bale!="" AND $netto!="" AND $karung!="" AND $ne!="" AND $tgl!=""){
            $this->data_model->updatedata('id_penerimaan',$id_penerimaan, 'penerimaan_benang', [
                'nama_benang' => $nama_benang,
                'jumlah_bale' => $bale,
                'netto' => $netto,
                'jumlah_karung' => $karung,
                'en_ee' => $ne,
                'tanggal_penerimaan' => $tgl
            ]);
            for ($i=0; $i < count($kodekarung); $i++) { 
                $this->data_model->updatedata('kode_karung',$kodekarung[$i], 'karung_benang', [
                    'berat_karung' => $beratkarung[$i],
                    'jumlah_cones' => $cones[$i]
                ]);
                //redirect(base_url('data/pembelian/'.sha1($id_penerimaan).'/success'));
            }
        } else {
            echo "<div style='display:flex;justify-content:center;align-items:center'>";
            echo "Anda tidak input data dengan benar #1.";
            echo "</div>";
        }
  } //end

  function prosesProduksiWarping(){
        $kode = $this->input->post('kode_proses');
        $tgl = $this->input->post('tgl');
        $this->data_model->saved('produksi_warping', [
            'kode_proses' => $kode,
            'jenis_mesin' => 'null',
            'jml_beam' => 0,
            'jml_creel' => 0,
            'meter_perbeam' => 0,
            'tgl_produksi' => $tgl,
            'tgl_input' => date('Y-m-d H:i:s'),
            'admin' => $this->session->userdata('nama'),
            'savedd' => 'n'
        ]);
        echo "Success";
  } //end

  function updateTanggal(){
        $kode = $this->input->post('kode_proses');
        $tgl = $this->input->post('tgl');
        $this->data_model->updatedata('kode_proses', $kode, 'produksi_warping', ['tgl_produksi'=>$tgl]);
  } //

  function updateMesin(){
        $kode = $this->input->post('kode_proses');
        $nm = $this->input->post('nm');
        $this->data_model->updatedata('kode_proses', $kode, 'produksi_warping', ['jenis_mesin'=>$nm]);
  } //

  function updateCreel(){
        $kode = $this->input->post('kode_proses');
        $nm = $this->input->post('nm');
        $this->data_model->updatedata('kode_proses', $kode, 'produksi_warping', ['jml_creel'=>$nm]);
  } //

  function updateJmlBeam(){
        $kode = $this->input->post('kode_proses');
        $nm = $this->input->post('nm');
        $this->data_model->updatedata('kode_proses', $kode, 'produksi_warping', ['jml_beam'=>$nm]);
  } //

  function updateJmlBeam2(){
        $kode = $this->input->post('kode_proses');
        $nm = $this->input->post('dt');
        $this->data_model->updatedata('kode_proses', $kode, 'produksi_warping', ['jml_beam'=>$nm]);
  } //edn

  function savedbeamwarping(){
        $kode = $this->input->post('kode_proses');
        $cek_kode = $this->data_model->get_byid('produksi_warping', ['kode_proses'=>$kode]);
        if($cek_kode->num_rows() == 1){
            $pjg = $this->input->post('pjgbeam');
            $kodebeam = $this->input->post('kodebeam');
            $id = $cek_kode->row("id_produksi_warping");
            $this->db->query("DELETE FROM beam_warping WHERE id_produksi_warping='$id'");
            for ($i=0; $i < count($pjg); $i++) { 
                if($pjg[$i]=="" AND $kodebeam[$i]==""){
                    $this->data_model->saved('beam_warping', [
                        'id_produksi_warping' => $id, 
                        'kode_beam' => 'null', 
                        'ukuran_panjang' => 'null',
                        'kode_proses_sizing' => 'null',
                        'status_beam' => 'masih'
                    ]);
                } else {
                    $this->data_model->saved('beam_warping', [
                        'id_produksi_warping' => $id, 
                        'kode_beam' => $kodebeam[$i], 
                        'ukuran_panjang' => $pjg[$i],
                        'kode_proses_sizing' => 'null',
                        'status_beam' => 'masih'
                    ]);
                }
            }
            echo "<div style='width:100%;height:100vh;display:flex;flex-direction:column;justify-content:center;align-items:center;'>";
            echo "<span style='font-size:2em;color:#348ceb;'>Berhasil Menyimpan Data</span>";
            echo "<div style='display:flex;gap:10px;'>";
            echo "<a href='".base_url()."data/warping/".$kode."' style='text-decoration:none;background:green;color:#fff;padding:5px 10px;border-radius:4px;'>Masukan Bahan Baku</a>";
            echo "<a href='".base_url()."warping' style='text-decoration:none;background:red;color:#fff;padding:5px 10px;border-radius:4px;'>Input Produksi Lain</a>";
            echo "</div>";
            echo "</div>";
        } else {
            echo "Token Error";
        }
        
  } //end//

  function savedbeamsizing(){
        $id_sizing = $this->input->post('id_sizing');
        $kode = $this->input->post('kode_proses');
        $cek_kode = $this->data_model->get_byid('produksi_sizing', ['kode_proses'=>$kode]);
        if($cek_kode->num_rows() == 1){
            $pjg = $this->input->post('pjgbeam');
            $kodebeam = $this->input->post('kodebeam');
            $konstr = $this->input->post('konstr');
            $draft = $this->input->post('draft');
            $this->db->query("DELETE FROM beam_sizing WHERE id_sizing='$id_sizing'");
            for ($i=0; $i < count($pjg); $i++) { 
                if($pjg[$i]=="" AND $kodebeam[$i]==""){
                    $this->data_model->saved('beam_sizing', [
                        'id_sizing' => $id_sizing, 
                        'kode_beam' => 'null', 
                        'ukuran_panjang' => 'null',
                        'draft' => 'null',
                        'kode_proses_ajl' => 'null',
                        'konstruksi' => 'null'
                    ]);
                } else {
                    $this->data_model->saved('beam_sizing', [
                        'id_sizing' => $id_sizing, 
                        'kode_beam' => $kodebeam[$i], 
                        'ukuran_panjang' => $pjg[$i],
                        'draft' => $draft[$i],
                        'kode_proses_ajl' => 'null',
                        'konstruksi' => strtoupper($konstr[$i])
                    ]);
                }
            }
            $this->data_model->updatedata('id_sizing',$id_sizing,'produksi_sizing', ['saved_yes'=>'saved']);
            redirect(base_url('beam/sizing/'.$kode.'/success'));
        } else {
            echo "Token Error";
        }
        
  } //end//savedbeamsizing

  function updatesizing(){
        $tgl_prod = $this->input->post('tgl_prod');
        $oka = $this->input->post('oka');
        $kons = $this->input->post('konsid');
        $kanji = $this->input->post('kanji');
        $batubara = $this->input->post('batubara');
        $waste_awal = $this->input->post('wasteaw');
        $waste_akhir = $this->input->post('wasteak');
        $jml_beam_sizing = $this->input->post('jmlbeam');
        $kode_proses = $this->input->post('kode_proses');
        $idsizing = $this->input->post('idsizing');
        $kodebeam = $this->input->post('kodebeam');
        $konstr = $this->input->post('konstr');
        $pjg = $this->input->post('pjgbeam');
        $draft = $this->input->post('draft');
        $typeofbeam = $this->input->post('typeofbeam');
        $idbeamsz = $this->input->post('idbeamsz');
        if($oka!="" AND $kons!="" AND $kanji!="" AND $batubara!="" AND $waste_awal!="" AND $waste_akhir!="" AND $jml_beam_sizing!="" AND $kode_proses!=""){ 
            $this->data_model->updatedata('id_sizing',$idsizing,'produksi_sizing', [
                'id_beamwar' => '0',
                'tgl_produksi' => $tgl_prod,
                'oka' => $oka,
                'konstruksi' => strtoupper($kons),
                'jml_kanji' => $kanji,
                'batubara' => $batubara,
                'waste_awal' => $waste_awal,
                'waste_akhir' => $waste_akhir,
                'jumlah_beam_sizing' => $jml_beam_sizing
            ]);
            //$this->db->query("DELETE FROM beam_sizing WHERE id_sizing='$idsizing'");
            if($typeofbeam == 1){
                for ($i=0; $i < count($kodebeam); $i++) {
                    $this->data_model->updatedata('id_beam_sizing',$idbeamsz[$i], 'beam_sizing', [
                        'kode_beam' => $kodebeam[$i],
                        'ukuran_panjang' => $pjg[$i],
                        'draft' => $draft[$i],
                        'konstruksi'=>strtoupper($konstr[$i])
                    ]);
                }
            } else {
                for ($i=0; $i < count($kodebeam); $i++) { 
                    if($kodebeam!="" AND $pjg!=""){
                        $this->data_model->saved('beam_sizing', [
                            'id_sizing' => $idsizing, 
                            'kode_beam' => $kodebeam[$i], 
                            'ukuran_panjang' => $pjg[$i],
                            'draft' => $draft[$i],
                            'kode_proses_ajl' => 'null',
                            'konstruksi' => strtoupper($konstr[$i])
                        ]);
                    } else {
                        $this->data_model->saved('beam_sizing', [
                            'id_sizing' => $idsizing, 
                            'kode_beam' => 'null', 
                            'ukuran_panjang' => '0',
                            'draft' => '0',
                            'kode_proses_ajl' => 'null',
                            'konstruksi' => 'null'
                        ]);
                    }
                }
            }
            
            redirect(base_url('data/sizing/'.sha1($idsizing)));
            
        } else {
            echo "Anda tidak mengisi data dengan benar!!";
        }
  }

  function savesizing(){
        // $kode_beam_warping = $this->input->post('beamwarping');
        // $ex = explode(' - ', $kode_beam_warping);
        //if(count($ex) == 3){
            // $_kode_beam = $ex[1];
            // $_id_produksi_war = $ex[2];
            // $ex2 = explode('/', $ex[0]);
            // $_tgl_warping = $ex2[2]."-".$ex2[1]."-".$ex2[0];
            //$kode_beam_warping_real = $this->data_model->get_byid('v_beam_warping', ['id_produksi_warping'=>$_id_produksi_war, 'tgl_produksi'=>$_tgl_warping, 'kode_beam'=>$_kode_beam])->row("id_beamwar");
            $tgl_prod = $this->input->post('tgl_prod');
            $oka = $this->input->post('oka');
            $kons = $this->input->post('konsid');
            $kanji = $this->input->post('kanji');
            $batubara = $this->input->post('batubara');
            $waste_awal = $this->input->post('wasteaw');
            $waste_akhir = $this->input->post('wasteak');
            $jml_beam_sizing = $this->input->post('jmlbeam');
            $kode_proses = $this->input->post('kode_proses');
            $beamfrom = $this->input->post('beamfrom');
            if($oka!="" AND $kons!="" AND $kanji!="" AND $batubara!="" AND $waste_awal!="" AND $waste_akhir!="" AND $jml_beam_sizing!="" AND $kode_proses!=""){
                $this->data_model->updatedata('kode_proses',$kode_proses,'produksi_sizing', [
                    'id_beamwar' => '0',
                    'tgl_produksi' => $tgl_prod,
                    'oka' => $oka,
                    'konstruksi' => strtoupper($kons),
                    'jml_kanji' => $kanji,
                    'batubara' => $batubara,
                    'waste_awal' => $waste_awal,
                    'waste_akhir' => $waste_akhir,
                    'jumlah_beam_sizing' => $jml_beam_sizing,
                    'beam_from' => $beamfrom
                ]);
                redirect(base_url('beam/sizing/'.$kode_proses));
            } else {
                echo "Anda tidak mengisi semua data dengan benar.!!";
            }
        // } else {
        //     echo "ID Produksi Warping Salah";
        // }
  } //end

  function startmesinajl(){
        $kode_proses = $this->input->post('kode_proses');
        $tgl = $this->input->post('tgl_produksi');
        $id_beam = $this->input->post('id_beamsizing');
        $konstruksi = $this->input->post('konstruksi');
        $lusi = $this->input->post('lusi');
        $pakan = $this->input->post('pakan');
        $sisir = $this->input->post('sisir');
        $pick = $this->input->post('pick');
        $pjg = $this->input->post('pjg_lusi');
        $sizing = $this->input->post('sizing');
        $jml_helai = $this->input->post('jml_helai');
        $no_mesin = $this->input->post('no_mesin');
        $cek_nomesin = $this->data_model->get_byid('table_mesin', ['no_mesin'=>$no_mesin])->num_rows();
        $_kons = strtoupper($konstruksi); 
        $cekKonstruksi = $this->data_model->get_byid('list_konstruksi', ['konstruksi'=>$_kons])->num_rows();
        if($cek_nomesin == 0){
            $this->data_model->saved('table_mesin', ['no_mesin'=>$no_mesin]);
        }
        if($id_beam!="" AND $konstruksi!="" AND $lusi!="" AND $pakan!="" AND $sisir!="" AND $pick!="" AND $pjg!="" AND $sizing!="" AND $jml_helai!="" AND $no_mesin!=""){
            if($cekKonstruksi==1){
            $this->data_model->saved('produksi_mesin_ajl', [
                'tgl_produksi' => $tgl,
                'waktu_awal_produksi' => date('Y-m-d H:i:s'),
                'id_beam_sizing' => $id_beam,
                'konstruksi' => strtoupper($konstruksi),
                'lusi' => $lusi,
                'pakan' => $pakan,
                'sisir' => $sisir,
                'pick' => $pick,
                'pjg_lusi' => $pjg,
                'sizing' => $sizing,
                'jml_helai' => $jml_helai,
                'no_mesin' => $no_mesin,
                'proses' => 'onproses', 
                'end_proses' => 'null',
                'kode_proses' => $kode_proses
            ]);
            //$this->data_model->updatedata('id_beam_sizing',$id_beam,'beam_sizing',['kode_proses_ajl'=>$kode_proses]);
            redirect(base_url('produksi-ajl'));
            } else {
                echo "Anda tidak menuliskan konstruksi dengan benar..!!";
            }
        } else {
            echo "Anda tidak mengisi semua data dengan benar.!!";
        }
  } //end

  function savedLoomCounter(){
    $users_edit = $this->input->post('users_edit');
    $nomesin = $this->input->post('nomesin');
    $rtrt_lusi = $this->input->post('rtrt_lusi');
    $rtrt_pakan = $this->input->post('rtrt_pakan');
    $protis = $this->input->post('protis');
    $pres = $this->input->post('pres');
    $idid = $this->input->post('idid');

    $groupid = $this->input->post('groupid');
    $tgl = $this->input->post('tgl');
    $shift = $this->input->post('shift');
    $kons = $this->input->post('kons');
    $rpm = $this->input->post('rpm');
    $pick = $this->input->post('pick');
    $lusi = $this->input->post('lusi');
    $mnt_lusi = $this->input->post('mnt_lusi');
    $pakan = $this->input->post('pakan');
    $mnt_pakan = $this->input->post('mnt_pakan');
    $eff = $this->input->post('eff');
    $prod = $this->input->post('prod');

    $groupid_old = $this->input->post('groupid_old');
    $tgl_old = $this->input->post('tgl_old');
    $shift_old = $this->input->post('shift_old');
    $kons_old = $this->input->post('kons_old');
    $rpm_old = $this->input->post('rpm_old');
    $pick_old = $this->input->post('pick_old');
    $lusi_old = $this->input->post('lusi_old');
    $mnt_lusi_old = $this->input->post('mnt_lusi_old');
    $pakan_old = $this->input->post('pakan_old');
    $mnt_pakan_old = $this->input->post('mnt_pakan_old');
    $eff_old = $this->input->post('eff_old');
    $prod_old = $this->input->post('prod_old');
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
    
    
    
    $cek = $this->data_model->get_byid('loom_counter', ['id_loom'=>$idid]);
    if($cek->num_rows() == 1){
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
                'yg_edit' => $users_edit,
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
        echo "<a href='https://ajl.rdgjt.com/monitoring/loom' onclick='' style='text-decoration:none;padding:20px 30px;border-radius:5px;background:#0280f5;margin-top:50px;color:#fff;'>Kembali</a>";
        echo "</div>";
        //echo json_encode(array("statusCode"=>200, "psn"=>"Ok"));
        
    } else {
        $txt = "Gagal Menyimpan Perubahan Pada Nomor mesin ".$nomesin." shift ".$shift."";
        echo "<div style='width:100%;height:100vh;display:flex;flex-direction:column;justify-content:center;align-items:center;font-size:2em;padding:0 20px;box-sizing:border-box;'>";
        echo "<span style='font-size:3em;color:red;'>Error!!</span>";
        echo "<span style='width:100%;text-align:center;'>".$txt."</span>";
        echo "<a href='#' onclick='https://ajl.rdgjt.com/monitoring/loom' style='text-decoration:none;padding:20px 30px;border-radius:5px;background:#0280f5;margin-top:50px;color:#fff;'>Kembali</a>";
        echo "</div>";
        //echo json_encode(array("statusCode"=>400, "psn"=>"Mesin sudah ada"));
    }
}//ne

  function updatestartmesinajl(){
        $id2 = $this->input->post('id_prod_mesin');
        $kode_proses = $this->input->post('kode_proses');
        $tgl = $this->input->post('tgl_produksi');
        $id_beam = $this->input->post('id_beamsizing');
        $konstruksi = $this->input->post('konstruksi');
        $lusi = $this->input->post('lusi');
        $pakan = $this->input->post('pakan');
        $sisir = $this->input->post('sisir');
        $pick = $this->input->post('pick');
        $pjg = $this->input->post('pjg_lusi');
        $sizing = $this->input->post('sizing');
        $jml_helai = $this->input->post('jml_helai');
        $no_mesin = $this->input->post('no_mesin');
        if($id_beam!="" AND $konstruksi!="" AND $lusi!="" AND $pakan!="" AND $sisir!="" AND $pick!="" AND $pjg!="" AND $sizing!="" AND $jml_helai!="" AND $no_mesin!=""){
            $this->data_model->updatedata('kode_proses',$kode_proses,'produksi_mesin_ajl', [
                'tgl_produksi' => $tgl,
                'id_beam_sizing' => $id_beam,
                'konstruksi' => strtoupper($konstruksi),
                'lusi' => $lusi,
                'pakan' => $pakan,
                'sisir' => $sisir,
                'pick' => $pick,
                'pjg_lusi' => $pjg,
                'sizing' => $sizing,
                'jml_helai' => $jml_helai,
                'no_mesin' => $no_mesin,
            ]);
            //$this->data_model->updatedata('id_beam_sizing',$id_beam,'beam_sizing',['kode_proses_ajl'=>$kode_proses]);
            redirect(base_url('data/produksi/mesin/'.$id2));
        } else {
            echo "Anda tidak mengisi semua data dengan benar.!!";
        }
  } //end

  function addoper(){
        $nama = $this->input->post("namal");
        $idop = $this->input->post("idop");
        $nama2 = strtolower($nama);
        $nama3 = ucwords($nama2);
        $user = $this->input->post("user");
        $pass = $this->input->post("pass");
        if($nama!="" AND $user!="" AND $pass!=""){
            $cek = $this->data_model->get_byid('operator_mesin', ['username'=>$user]);
            if($cek->num_rows() == 0){
                if($idop==0){
                $this->data_model->saved('operator_mesin', [
                    'username' => $user,
                    'password' => sha1($pass),
                    'nama_lengkap' => $nama3
                ]);
                } else {
                    $this->data_model->updatedata('id_op',$idop,'operator_mesin', [
                        'username' => $user,
                        'password' => sha1($pass),
                        'nama_lengkap' => $nama3
                    ]);
                }
                redirect(base_url('data-operator'));
            } else {
                echo "Username sudah di gunakan";
            }
        } 
  } //end

  function deleteLoom(){
        $uri = $this->uri->segment(3);
        $this->db->query("DELETE FROM loom_counter WHERE id_loom='$uri'");
        redirect(base_url('data-loom'));
  }
  function inputwarpershift(){
        $tgl = $this->input->post('tgl');
        $hcb1 = $this->input->post('hcb1');
        $nhcb1 = preg_replace("/[^0-9]/", "", $hcb1);
        $hcb2 = $this->input->post('hcb2');
        $nhcb2 = preg_replace("/[^0-9]/", "", $hcb2);
        $hcb3 = $this->input->post('hcb3');
        $nhcb3 = preg_replace("/[^0-9]/", "", $hcb3);

        $kwt1 = $this->input->post('kwt1');
        $nkwt1 = preg_replace("/[^0-9]/", "", $kwt1);
        $kwt2 = $this->input->post('kwt2');
        $nkwt2 = preg_replace("/[^0-9]/", "", $kwt2);
        $kwt3 = $this->input->post('kwt3');
        $nkwt3 = preg_replace("/[^0-9]/", "", $kwt3);
        
        $ket = $this->input->post('ket');
        if($tgl!="" AND $hcb1!="" AND  $hcb2!="" AND $hcb3!="" AND $kwt1!="" AND $kwt2!="" AND $kwt3!=""){
            $cek = $this->data_model->get_byid('produksi_warping_persif', ['tgl'=>$tgl]);
            if($cek->num_rows() == 0){
                $this->data_model->saved('produksi_warping_persif', [
                    'tgl'=>$tgl, 
                    'hcb1' => $nhcb1,
                    'hcb2' => $nhcb2,
                    'hcb3' => $nhcb3,
                    'kwt1' => $nkwt1,
                    'kwt2' => $nkwt2,
                    'kwt3' => $nkwt3,
                    'tgl_input' => date('Y-m-d H:i:s'),
                    'yg_input'=>$this->session->userdata('nama'),
                    'ket'=>$ket
                ]);
                echo json_encode(array("statusCode"=>200, "fix"=>"error", "psn"=>"Berhasil Menyimpan Produksi Warping"));
            } else {
                $id = $cek->row("id_pwsif");
                $this->data_model->updatedata('id_pwsif',$id,'produksi_warping_persif', [
                    'tgl'=>$tgl,
                    'hcb1' => $nhcb1,
                    'hcb2' => $nhcb2,
                    'hcb3' => $nhcb3,
                    'kwt1' => $nkwt1,
                    'kwt2' => $nkwt2,
                    'kwt3' => $nkwt3,
                    'yg_input'=>$this->session->userdata('nama'),
                    'ket'=>$ket
                ]);
                echo json_encode(array("statusCode"=>200, "fix"=>"error", "psn"=>"Berhasil Update Data"));
            }
            
        } else {
            echo json_encode(array("statusCode"=>400, "fix"=>"error", "psn"=>"Token Error"));
        }
  } //edn
  function inputsizpershift(){
        $tgl = $this->input->post('tgl');
        $hcb1 = $this->input->post('hcb1');
        $nhcb1 = preg_replace("/[^0-9]/", "", $hcb1);
        $hcb2 = $this->input->post('hcb2');
        $nhcb2 = preg_replace("/[^0-9]/", "", $hcb2);
        $hcb3 = $this->input->post('hcb3');
        $nhcb3 = preg_replace("/[^0-9]/", "", $hcb3);
        
        $ket = $this->input->post('ket');
        if($tgl!="" AND $hcb1!="" AND  $hcb2!="" AND $hcb3!=""){
            $cek = $this->data_model->get_byid('produksi_sizing_perif', ['tgl'=>$tgl]);
            if($cek->num_rows() == 0){
                $this->data_model->saved('produksi_sizing_perif', [
                    'tgl'=>$tgl, 
                    'tgl_input' => date('Y-m-d H:i:s'),
                    'yg_input'=>$this->session->userdata('nama'),
                    'siz1' => $nhcb1,
                    'siz2' => $nhcb2,
                    'siz3' => $nhcb3,
                    'ket'=>$ket
                ]);
                echo json_encode(array("statusCode"=>200, "fix"=>"error", "psn"=>"Berhasil Menyimpan Produksi Sizing"));
            } else {
                $id = $cek->row("id_pszsif");
                $this->data_model->updatedata('id_pszsif',$id,'produksi_sizing_perif', [
                    'tgl'=>$tgl, 
                    'yg_input'=>$this->session->userdata('nama'),
                    'siz1' => $nhcb1,
                    'siz2' => $nhcb2,
                    'siz3' => $nhcb3,
                    'ket'=>$ket
                ]);
                echo json_encode(array("statusCode"=>200, "fix"=>"error", "psn"=>"Berhasil Update Data"));
            }
            
        } else {
            echo json_encode(array("statusCode"=>400, "fix"=>"error", "psn"=>"Token Error"));
        }
  } //edn

  function updatesisa(){
        $kodebeam = $this->input->post('kodebeam');
        $kodepros = $this->input->post('kodepros');
        $idbeamwar = $this->input->post('idbeamwar');
        $sisa = $this->input->post('sisa');
        if($kodebeam!="" AND $kodepros!="" AND $sisa!="" AND $idbeamwar!=""){
            $cek = $this->data_model->get_byid('beam_warping_used', ['id_beamwar'=>$idbeamwar, 'kode_produksi_sizing'=>$kodepros]);
            if($cek->num_rows() == 1){
                $ukuran_asli = $this->data_model->get_byid('beam_warping', ['id_beamwar'=>$idbeamwar])->row("ukuran_panjang");
                $pemakaian = $ukuran_asli - $sisa;
                $idup = $cek->row("id_pemakaian");
                $this->data_model->updatedata('id_pemakaian',$idup,'beam_warping_used',['sisa'=>$sisa,'pemakaian'=>$pemakaian]);
                $this->data_model->updatedata('id_beamwar',$idbeamwar,'beam_warping',['status_beam'=>'masih']);
                echo json_encode(array("statusCode"=>200, "fix"=>"error", "psn"=>"Berhasil menyimpan"));
            } else {
                echo json_encode(array("statusCode"=>400, "fix"=>"error", "psn"=>"ID tidak ditemukan"));
            }
        } else {
            echo json_encode(array("statusCode"=>400, "fix"=>"error", "psn"=>"Token Error"));
        }
  } //end

  function turunkanbeam(){
        $id = $this->input->post('id');
        $det = date('Y-m-d');
        $this->db->query("UPDATE produksi_mesin_ajl SET proses='finish', tgl_akhir_produksi='$det' WHERE id_produksi_mesin='$id'");
        echo "sukses";
  } //end

  function simpanpotongan(){
        $id_produksi_mesin = $this->input->post('id_produksi_mesin');
        $id_beam_sizing = $this->input->post('id_beam_sizing');
        $kons = $this->input->post('kons');
        $beam = $this->input->post('beam');
        $ukrptg = $this->input->post('ukrptg');
        $tglptg = $this->input->post('tglptg');
        $grub = $this->input->post('grub');
        $ket = $this->input->post('keter');
        $users_data = $this->input->post('users_data');
        if($id_produksi_mesin!="" AND $id_beam_sizing!="" AND $kons!="" AND $beam!="" AND $ukrptg!="" AND $tglptg!="" AND $grub!=""){
            $this->data_model->saved('produksi_mesin_ajl_potongan', [
                'id_produksi_mesin' => $id_produksi_mesin,
                'id_beam_sizing' => $id_beam_sizing,
                'tgl_potong' => $tglptg,
                'waktu_potong' => date('Y-m-d H:i:s'),
                'ukuran_meter' => $ukrptg,
                'shift' => $grub,
                'operator' => $users_data,
                'konstruksi' => $kons,
                'no_beam' => $beam,
                'keter' => $ket
            ]);
            redirect(base_url('operator/mesin/'.sha1($id_produksi_mesin)));
        } else {
            echo "<div style='width:100%;height:100vh;display:flex;flex-direction:column;justify-content:center;align-items:center;font-size:2em;padding:0 20px;box-sizing:border-box;'>";
            echo "<span style='font-size:3em;color:red;'>Error!!</span>";
            echo "<span style='width:100%;text-align:center;'>Anda tidak mengisi data dengan benar</span>";
            echo "<a href='#' onclick='".base_url('operator/mesin/'.sha1($id_produksi_mesin))."' style='text-decoration:none;padding:20px 30px;border-radius:5px;background:#0280f5;margin-top:50px;color:#fff;'>Kembali</a>";
            echo "</div>";
        }

  } //end

  function hapuspotongan(){
        $id=$this->input->post('id');
        $this->db->query("DELETE FROM produksi_mesin_ajl_potongan WHERE id_potongan='$id'");
        echo "success";
  } //end

  function stopmesin(){
        $waktu = date('Y-m-d H:i:s');
        $id=$this->input->post('id');
        $txt=$this->input->post('txt');
        $this->data_model->updatedata('id_produksi_mesin',$id,'produksi_mesin_ajl',['proses'=>'stop']);
        $this->data_model->saved('produksi_mesin_ajl_rwyt', [
            'id_produksi_mesin' => $id,
            'jenis_riwayat' => 'Mesin Stop',
            'keterwyt' => $txt,
            'timestamprwyt'  => $waktu,
            'operator_login' => $this->session->userdata('nama')
        ]);
        echo "success";
  } //end

  function cekkeadaanmesin(){
        $id=$this->input->post('id');
        $cek_proses = $this->data_model->get_byid('produksi_mesin_ajl', ['id_produksi_mesin'=>$id])->row("proses");
        if($cek_proses == "onproses"){
            echo '<span style="font-weight:bold;">Input Potongan Kain</span>';
            echo '<span style="background:green;color:#fff;font-size:10px;padding:3px 10px;border-radius:4px;">Mesin On</span>';   
        }
        if($cek_proses == "stop"){
            echo '<span style="font-weight:bold;">Input Potongan Kain</span>';
            echo '<span style="background:red;color:#fff;font-size:10px;padding:3px 10px;border-radius:4px;">Mesin Off</span>';   
        }
        if($cek_proses == "finish"){
            echo '<span style="font-weight:bold;">Input Potongan Kain</span>';
            echo '<span style="background:#ccc;color:#000;font-size:10px;padding:3px 10px;border-radius:4px;">Finish</span>';   
        }
  } //end
  function ceknyalakanmesin(){
        $waktu = date('Y-m-d H:i:s');
        $id=$this->input->post('id');
        $this->data_model->updatedata('id_produksi_mesin',$id,'produksi_mesin_ajl',['proses'=>'onproses']);
        $this->data_model->saved('produksi_mesin_ajl_rwyt', [
            'id_produksi_mesin' => $id,
            'jenis_riwayat' => 'Mesin Jalan',
            'keterwyt' => 'null',
            'timestamprwyt' => $waktu,
            'operator_login' => $this->session->userdata('nama')
        ]);
        echo "success";
        echo "success";
  } //end 
  function selesaiakanmesin(){
        $id=$this->input->post('id');
        $waktu = date('Y-m-d H:i:s');
        $this->data_model->updatedata('id_produksi_mesin',$id,'produksi_mesin_ajl',['proses'=>'finish']);
        $this->data_model->saved('produksi_mesin_ajl_rwyt', [
            'id_produksi_mesin' => $id,
            'jenis_riwayat' => 'Selesai Produksi',
            'keterwyt' => 'Proses produksi mesin telah selesai',
            'timestamprwyt' => $waktu,
            'operator_login' => $this->session->userdata('nama')
        ]);
        echo "success";
  } //end

  function changeBeamToAJL(){
        $id = $this->input->post('selection');
        $ex = explode(' - ', $id);
        $nobeam = $ex[1];
        $kons = $ex[2];
        // $xx = explode('/', $ex[0]);
        // $tgl = $xx[2]."-".$xx[1]."-".$xx[0];
        $okaid = $ex[0];
        $cekdt = $this->data_model->get_byid('v_beam_sizing2', ['oka'=>$okaid,'konstruksi'=>$kons,'kode_beam'=>$nobeam]);
        $id_beam_sizing = $cekdt->row("id_beam_sizing");
        $lusi = $cekdt->row("ukuran_panjang");
        $kons2 = $this->data_model->get_byid('beam_sizing', ['id_beam_sizing'=>$id_beam_sizing])->row("konstruksi");
        $sizusers = $this->data_model->get_byid('beam_sizing', ['id_beam_sizing'=>$id_beam_sizing])->row("usersr");
        if($sizusers == "ga"){
            $nama_opt_sizing = "Grub A";
        } elseif($sizusers == "gb") {
            $nama_opt_sizing = "Grub B";
        } elseif($sizusers == "gc"){
            $nama_opt_sizing = "Grub C";
        } else {
            $nama_opt_sizing = $sizusers;
        }
        $cek_potongan = $this->data_model->get_byid('produksi_mesin_ajl_potongan', ['id_beam_sizing'=>$id_beam_sizing]);
        $jml_potongan = 0;
        foreach($cek_potongan->result() as $ty){
            $jml_potongan += $ty->ukuran_meter;
        }
        if($lusi > $jml_potongan) {
            $sts = "oke";
        } else {
            $sts = "habis";
        }
        $lusi1 = $lusi - $jml_potongan;
        $cekbeamdipakai = $this->db->query("SELECT * FROM produksi_mesin_ajl WHERE id_beam_sizing='$id_beam_sizing' AND proses IN ('onproses','stop')");
        if($cekbeamdipakai->num_rows() == 0){
            if($cekdt->num_rows() == 1){
                echo json_encode(array("statusCode"=>200, "id"=>$id, "kons"=>$kons2, "id_beam_sizing"=>$id_beam_sizing, "lusi"=>$lusi1, "sts"=>$sts, "optsiz"=>$nama_opt_sizing));
            } else {
                echo json_encode(array("statusCode"=>400, "id"=>$id, "psn"=>"error", "id_beam_sizing"=>"error"));
            }
        } else {
            $cekbeamdipakai2 = $this->db->query("SELECT * FROM produksi_mesin_ajl WHERE id_beam_sizing='$id_beam_sizing' AND proses IN ('onproses','stop') ORDER BY id_produksi_mesin DESC LIMIT 1");
            $nomcnow = $cekbeamdipakai2->row("no_mesin");
            $idprodmesin = $cekbeamdipakai2->row("id_produksi_mesin"); 
            $tues = $this->db->query("SELECT * FROM `produksi_mesin_ajl_rwyt` WHERE `id_produksi_mesin` = '$idprodmesin' AND `jenis_riwayat` LIKE 'Naik Beam'");
            $ops2 = $tues->row("keterwyt");
            $ops2 = str_replace("Operator", "Beamstell", $ops2);
            $txtx = "Beam sedang di pakai di mesin ".$nomcnow." (".$ops2.")";
            echo json_encode(array("statusCode"=>400, "id"=>$id, "psn"=>$txtx, "id_beam_sizing"=>"error"));
        }
  } //end
  function addprosesnaikbeamstart(){
        $kode = $this->input->post('kodeproses');
        $nomc = $this->input->post('nomc');
        $txt = $this->input->post('txt');
        $alldt = $this->data_model->get_byid('produksi_mesin_ajl', ['no_mesin'=>$nomc]);
        foreach($alldt->result() as $vl){
            $kd = $vl->kode_proses;
            $id = $vl->id_produksi_mesin;
            if($kd != $kode){
                $this->data_model->updatedata('id_produksi_mesin',$id,'produksi_mesin_ajl',['proses'=>'finish']);
            }
        }
        $this->db->query("UPDATE produksi_mesin_ajl SET proses='onproses' WHERE no_mesin='$nomc' AND kode_proses='$kode'");
        $idp = $this->data_model->get_byid('produksi_mesin_ajl', ['no_mesin'=>$nomc,'kode_proses'=>$kode])->row('id_produksi_mesin');
        $id = sha1($idp);
        $waktu = date('Y-m-d H:i:s');
        $this->data_model->saved('produksi_mesin_ajl_rwyt', [
            'id_produksi_mesin' => $idp,
            'jenis_riwayat' => 'Start Mesin',
            'keterwyt' => $txt,
            'timestamprwyt' => $waktu,
            'operator_login' => $this->session->userdata('nama')
        ]);
        echo json_encode(array("statusCode"=>200, "idp"=>$id, "psn"=>"success", "id_beam_sizing"=>"error"));
  } //end
  function addprosesnaikbeam(){
        $kode = $this->input->post('kodeproses');
        $idbeam = $this->input->post('idbeam');
        $nomc = $this->input->post('nomc');
        $kons = $this->input->post('kons');
        $pjglusi = $this->input->post('lusi');
        $helai = $this->input->post('helai');
        $opts = $this->input->post('opts');
        $asalbeam = "brj";
        $startt = "null";
        if($kode!="" AND $idbeam!="" AND $nomc!="" AND $kons!="" AND $pjglusi!="" AND $helai!=""){
            $dtlist = [
                'tgl_produksi' => date('Y-m-d'),
                'waktu_awal_produksi' => date('Y-m-d H:i:s'),
                'id_beam_sizing' => $idbeam,
                'konstruksi' => $kons,
                'lusi' => '0',
                'pakan' => '0',
                'sisir' => '0',
                'pick' => '0',
                'pjg_lusi' => $pjglusi,
                'sizing' => '0',
                'jml_helai' => $helai,
                'no_mesin' => $nomc,
                'proses' => 'stop',
                'tgl_akhir_produksi' => '0000-00-00',
                'end_proses' => 'null',
                'kode_proses' => $kode,
                'asal_beam' => $asalbeam,
                'waktu_awal_startmesin' => $startt
            ];
            $this->data_model->saved('produksi_mesin_ajl',$dtlist);
            $cek_idproduksi_mesin = $this->data_model->get_byid('produksi_mesin_ajl', $dtlist)->row("id_produksi_mesin");
            $waktu = date('Y-m-d H:i:s');
            $this->data_model->saved('produksi_mesin_ajl_rwyt', [
                'id_produksi_mesin' => $cek_idproduksi_mesin,
                'jenis_riwayat' => 'Naik Beam',
                'keterwyt' => 'Operator '.$opts.'',
                'timestamprwyt' => $waktu,
                'operator_login' => $this->session->userdata('nama')
            ]);
            echo json_encode(array("statusCode"=>200, "id"=>"e", "psn"=>"success", "id_beam_sizing"=>"error"));
        } else {
            echo json_encode(array("statusCode"=>400, "id"=>"e", "psn"=>"Anda tidak mengisi dengan benar", "id_beam_sizing"=>"error"));
        }
  } //end
  function showsizingtable(){
        $id = $this->input->post('id');
        if($id == "null"){
            $cekTable = $this->db->query("SELECT * FROM produksi_sizing ORDER BY tgl_produksi DESC");
        } else {
            $cekTable = $this->db->query("SELECT * FROM produksi_sizing WHERE oka = '$id' ORDER BY tgl_produksi DESC");
        }
            ?>
            <div style="width:100%;overflow-x:auto;">
                <table border="1" style="min-width:100%;border-collapse:collapse;font-size:12px;border:1px solid #fff;background:#f0f0f2;">
                    <thead>
                        <tr>
                            <th style="padding:3px;">No</th>
                            <th style="padding:3px;">Tanggal</th>
                            <th style="padding:3px;">OKA</th>
                            <th style="padding:3px;">Konstruksi</th>
                            <th style="padding:3px;">Jumlah Beam</th>
                        </tr>
                    </thead>
                    <tbody>

            <?php
        if($cekTable->num_rows() > 0){
            $no=1;
            foreach($cekTable->result() as $vl):
            $x = explode('-',$vl->tgl_produksi);
            $thn = date('Y');
            if($thn == $x[0]){
                $prt = $x[2]." ".$this->data_model->printBln2($x[1]);
            } else {
                $prt = $x[2]."/".$x[1]."/".$x[0];
            }
            $idsizing = $vl->id_sizing;
            ?>
            <tr>
                <th><?=$no;?></th>
                <td style="text-align:center;padding:3px;"><?=$prt;?></td>
                <td style="text-align:center;padding:3px;"><?=$vl->oka;?></td>
                <td style="text-align:center;padding:3px;"><?=$vl->konstruksi;?></td>
                <td style="text-align:center;padding:3px;">
                    <a href="<?=base_url('operator/produksi/sizing/'.$idsizing);?>" style="text-decoration:none;background:#7814db;color:#fff;padding:1px 5px;font-size:9px;border-radius:3px;">
                    <?=$vl->jumlah_beam_sizing;?>
                    </a>
                </td>
            </tr>
            <?php $no++;
            endforeach;
        } else {
            echo "<tr><td colspan='5'>Tidak ada data</td></tr>";
        }
        echo "</tbody></table></div>";
  } //end

  function prosesSizingOpt(){
        $kode = $this->input->post('kodeproses');
        $kons = $this->input->post('kons');
        $oka = $this->input->post('oka');
        $tgl = $this->input->post('tgl');
        $user = $this->input->post('user');
        if($kode!="" AND $kons!="" AND $oka!="" AND $tgl!="" AND $user!=""){
            $cekOka = $this->data_model->get_byid('produksi_sizing', ['oka'=>$oka])->num_rows();
            if($cekOka == 0){
            $dtlist = [
                'id_beamwar'=>0,
                'tgl_produksi'=>$tgl,
                'oka'=>$oka,
                'konstruksi'=>$kons,
                'jml_kanji'=>0,
                'batubara'=>0,
                'waste_awal'=>0,
                'waste_akhir'=>0,
                'jumlah_beam_sizing'=>0,
                'tms_tmp' => date('Y-m-d H:i:s'),
                'kode_proses'=>$kode,
                'saved_yes'=>'saved',
                'beam_from'=>'brj'
            ];
            $this->data_model->saved('produksi_sizing',$dtlist);
            $idproses = $this->data_model->get_byid('produksi_sizing',$dtlist)->row("id_sizing");
            echo json_encode(array("statusCode"=>200, "psn"=>$idproses));
            } else {
                echo json_encode(array("statusCode"=>400, "psn"=>"OKA sudah ada di dalam data produksi"));
            }
        } else {
            echo json_encode(array("statusCode"=>400, "psn"=>"Anda tidak memasukan data dengan benar"));
        }
  } //end
  function savedoptbeamsizing(){
        $idsizing = $this->input->post('id');
        $kodebeam = $this->input->post('kodebeam');
        $ukr = $this->input->post('ukr');
        $kons = $this->input->post('kons');
        $draft = $this->input->post('draftid');
        $tgl = $this->input->post('tgl');
        $opts = $this->input->post('opts');
        if($opts == ""){
            $optsr = $this->session->userdata('username');
        } else {
            $optsr = $opts;
        }
        if($idsizing!="" AND $kodebeam!="" AND $ukr!="" AND $kons!="" AND $draft!="" AND $tgl!=""){
            $cek_kdbeam = $this->data_model->get_byid('beam_sizing', ['id_sizing'=>$idsizing, 'kode_beam'=>$kodebeam, 'tgl_beam'=>$tgl])->num_rows();
            if($cek_kdbeam == 0){
                $dtlist = [
                    'id_sizing'=>$idsizing,
                    'kode_beam'=>$kodebeam,
                    'ukuran_panjang'=>$ukr,
                    'draft'=>$draft,
                    'kode_proses_ajl'=>'null',
                    'konstruksi'=>$kons,
                    'tgl_beam'=>$tgl,
                    'usersr'=> $this->session->userdata('username'),
                    'nmopt' => $opts
                ];
                $this->data_model->saved('beam_sizing', $dtlist);
                $jmlbeam = $this->data_model->get_byid('beam_sizing', ['id_sizing'=>$idsizing])->num_rows();
                $this->data_model->updatedata('id_sizing',$idsizing,'produksi_sizing',['jumlah_beam_sizing'=>$jmlbeam]);
                echo json_encode(array("statusCode"=>200, "psn"=>"success"));
            } else {
                echo json_encode(array("statusCode"=>400, "psn"=>"Kode Beam sudah di input"));
            }
        } else {
            echo json_encode(array("statusCode"=>400, "psn"=>"Anda tidak memasukan data dengan benar"));
        }
  } //end
  function delbeamsizingopt(){
        $id = $this->input->post('id');
        $idsizing = $this->input->post('idsizing');
        $this->db->query("DELETE FROM beam_sizing WHERE id_beam_sizing='$id'");
        $jmlbeam = $this->data_model->get_byid('beam_sizing', ['id_sizing'=>$idsizing])->num_rows();
        $this->data_model->updatedata('id_sizing',$idsizing,'produksi_sizing',['jumlah_beam_sizing'=>$jmlbeam]);
        echo "success";
  } //end

  function cekkonstr(){
        $id = $this->input->post('selection');
        $pick = $this->data_model->get_byid('list_konstruksi', ['konstruksi'=>$id]);
        if($pick->num_rows() == 1){
            $picks = $pick->row("pick");
            echo json_encode(array("statusCode"=>200, "psn"=>$picks));
        } else {
            echo json_encode(array("statusCode"=>400, "psn"=>"Konstruksi tidak ditemukan"));
        }
  } //emd

  function mesinceks(){
        $id = $this->input->post('hasilinput');
        $cekmesin = $this->data_model->get_byid('table_mesin', ['no_mesin'=>$id]);
        if($cekmesin->num_rows() == 1){
            echo json_encode(array("statusCode"=>200, "psn"=>"oke"));
        } else {
            echo json_encode(array("statusCode"=>400, "psn"=>"error"));
        }
  } //end
  
  function updatewarpingnew(){
        $tgl = $this->input->post('tgl_prod');
        $jns = $this->input->post('jenis_mesin');
        $crl = $this->input->post('creel');
        $jml = $this->input->post('jml_beam');
        $kode = $this->input->post('kode_proses');
        $tipebeam = $this->input->post('tipebeam');
        $dtlist = [
            'jenis_mesin' => $jns,
            'jml_creel' => $crl,
            'tgl_produksi' => $tgl
        ];
        $this->data_model->updatedata('kode_proses',$kode, 'produksi_warping', $dtlist);
        if($tipebeam == 1){
            $idbeamwar = $this->input->post('idbeamwar');
            $kodebeam = $this->input->post('kodebeam');
            $pjgbeam = $this->input->post('pjgbeam');
            for($i=0; $i < count($idbeamwar) ; $i++){
                $data = [
                    'kode_beam' => $kodebeam[$i],
                    'ukuran_panjang' => $pjgbeam[$i]
                ];
                $this->data_model->updatedata('id_beamwar', $idbeamwar[$i], 'beam_warping', $data);   
            }
            echo "1. ";
        }
        //echo "Sukses Update";
        redirect("https://ajl.rdgjt.com/data/warping/".$kode."");
  } //end

    function updateKonstruksi(){
        $id = $this->input->post('id', TRUE);
        $mc = $this->input->post('msn', TRUE);
        $idsha = $this->input->post('idsha', TRUE);
        $cekData = $this->data_model->get_byid('produksi_mesin_ajl',['id_produksi_mesin'=>$id]);
        if($cekData->num_rows() == 1){
        } else {
            $cekData = $this->data_model->get_byid('produksi_mesin_ajl',['sha1(id_produksi_mesin)'=>$idsha]);
        }
        
            if($cekData->num_rows() == 1){
                $_idReal = $cekData->row("id_produksi_mesin");
                //$_kons = $cekData->row("konstruksi");
                $_kons = strtoupper(str_replace([' ', '-', '.', ','], '', $cekData->row("konstruksi")));
                $this->data_model->updatedata('id_produksi_mesin',$_idReal,'produksi_mesin_ajl',['konstruksi'=>$_kons]);
                $_pick = $cekData->row("pick");
                $_pjg_lusi = $cekData->row("pjg_lusi");
                $_proses = $cekData->row("proses");
                ?>
                <p style="font-weight:bold;margin-bottom:15px;font-size:23px;">NOMOR MESIN <?=$mc;?></p>
                <div style="width: 100%; display: flex; align-items: center; margin-bottom: 10px; font-family: 'Noto Sans', sans-serif;">
                    <label for="konstruksiID" style="margin-right: 10px; white-space: nowrap; font-weight: 500;width:35%;text-align:left;">ID</label>
                    <input 
                        type="text" 
                        id="idID" 
                        style="flex: 1; padding: 8px 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; outline-color: #4a90e2;"
                        value="<?=$_idReal;?>"
                        placeholder="Masukan ID" disabled
                    >
                </div>
                <div style="width: 100%; display: flex; align-items: center; margin-bottom: 10px; font-family: 'Noto Sans', sans-serif;">
                    <label for="konstruksiID" style="margin-right: 10px; white-space: nowrap; font-weight: 500;width:35%;text-align:left;">Konstruksi</label>
                    <input 
                        type="text" 
                        id="konstruksiID" 
                        style="flex: 1; padding: 8px 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; outline-color: #4a90e2;"
                        value="<?=$_kons;?>"
                        placeholder="Masukan konstruksi"
                    >
                </div>
                <div style="width: 100%; display: flex; align-items: center; margin-bottom: 10px; font-family: 'Noto Sans', sans-serif;">
                    <label for="pickID" style="margin-right: 10px; white-space: nowrap; font-weight: 500;width:35%;text-align:left;">Pick</label>
                    <input 
                        type="text" 
                        id="pickID" 
                        style="flex: 1; padding: 8px 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; outline-color: #4a90e2;"
                        value="<?=$_pick;?>"
                        placeholder="Masukan pick"
                    >
                </div>
                <div style="width: 100%; display: flex; align-items: center; margin-bottom: 10px; font-family: 'Noto Sans', sans-serif;">
                    <label for="pjgLUSI" style="margin-right: 10px; white-space: nowrap; font-weight: 500;width:35%;text-align:left;">Panjang Lusi</label>
                    <input 
                        type="text" 
                        id="pjgLUSI" 
                        style="flex: 1; padding: 8px 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; outline-color: #4a90e2;"
                        value="<?=$_pjg_lusi;?>"
                        placeholder="Masukan panjang lusi"
                    >
                </div>
                <div style="width: 100%; display: flex; align-items: center; margin-bottom: 10px; font-family: 'Noto Sans', sans-serif;">
                    <label for="pjgLUSI" style="margin-right: 10px; white-space: nowrap; font-weight: 500;width:35%;text-align:left;">Status</label>
                    <?php if($_proses == "stop"){ ?>
                    <span style="background:red;color:#fff;padding:3px 10px;border-radius:4px;">Stop</span>
                    <?php } ?>
                    <?php if($_proses == "onproses"){ ?>
                    <span style="background:green;color:#fff;padding:3px 10px;border-radius:4px;">Sedang Proses</span>
                    <?php } ?>
                    <?php if($_proses == "finish"){ ?>
                    <span style="background:orange;color:#fff;padding:3px 10px;border-radius:4px;">Selesai Proses</span>
                    <?php } ?>
                </div>
                <input type="hidden" id="idReal" value="<?=$_idReal;?>">
                <div style="width:100%;display:flex;justify-content:flex-end;">
                    <div class="btn-simpan" onclick="handleClick(this)">
                        <span class="btn-text">Simpan</span>
                        <span class="spinner"></span>
                    </div>
                </div>
                <div style="width:100%;margin:10px 0;border-top:1px solid #ccc;text-align:left;padding-top:15px;overflow-x:auto;">
                    <div class="table-container">
                    <table>
                        <tr>
                            <th colspan="5" style="padding:10px 0 0 10px;">Riwayat Mesin</th>
                        </tr>
                        <?php
                            $rwyt = $this->data_model->get_byid('produksi_mesin_ajl_rwyt', ['id_produksi_mesin'=>$_idReal])->result();
                            $r=1;
                            foreach($rwyt as $v):
                            $x = explode(' ',$v->timestamprwyt);
                            $xx = explode('-', $x[0]);
                        ?>
                        <tr>
                            <td><?=$r;?></td>
                            <td><?=$v->jenis_riwayat;?></td>
                            <td><?=$v->keterwyt;?></td>
                            <td><?=$xx[2].'/'.$xx[1].'/'.$xx[0].' '.$x[1];?></td>
                            <td><?=$v->operator_login;?></td>
                        </tr>
                        <?php $r++; endforeach; ?>
                    </table>
                    </div>
                    
                </div>
                <?php
            } else {
                echo "<font style='color:red;'>Token Erorr, {".$id."} - {".$mc."}</font>";
            }
    }
    function saveUpdatePick(){
        $id = $this->input->post('idAsli', TRUE);
        $kons = $this->input->post('kons', TRUE);
        $pick = $this->input->post('pick', TRUE);
        $orang = $this->session->userdata('nama');
        $_kons = strtoupper(str_replace([' ', '-', '.', ','], '', $kons));
        $xxt = "";
        $cekId = $this->data_model->get_byid('produksi_mesin_ajl',['id_produksi_mesin'=>$id])->row_array();
        $konsold = $cekId['konstruksi'];
        $pickold = $cekId['pick'];

        if($konsold != $_kons){
            $xxt .= "Update konstruksi ".$konsold." -> ".$_kons." ";
        }
        if($pickold != $pick){
            $xxt .= "Update pick ".$pickold." -> ".$pick."";
        }
        $this->data_model->saved('produksi_mesin_ajl_rwyt',[
            'id_produksi_mesin' => $id,
            'jenis_riwayat' => 'Update Mesin',
            'keterwyt' => $xxt,
            'timestamprwyt' => date('Y-m-d H:i:s'),
            'operator_login' => $orang
        ]);
        $this->data_model->updatedata('id_produksi_mesin',$id,'produksi_mesin_ajl',['konstruksi'=>$_kons,'pick'=>$pick]);
        echo "success";
    }
  

}
?>