<div class="formdata">
<?php 
$dtlis = $this->data_model->get_byid('produksi_warping', ['kode_proses'=>$kodeproses])->row_array();
$ex = explode('-', $dtlis['tgl_produksi']);
$printBln = $ex[2]."-".$this->data_model->printBln2($ex[1])."-".$ex[0];
$jmlBeam = $dtlis['jml_beam'];
$id_warping = $dtlis['id_produksi_warping'];
$cek_beamwar = $this->data_model->get_byid('beam_warping', ['id_produksi_warping'=>$id_warping]);
?>
                <!-- <div class="notifikasi-sukses" style="background:#02a82e;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Ini pemberitahuan notifikasi
                </div>
                <div class="notifikasi-gagal" style="background:#de071c;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Ini pemberitahuan notifikasi
                </div> -->
                <?php if($notif=="true"){ ?>
                <div class="notifikasi-sukses" style="background:#02a82e;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Berhasil menyimpan proses warping.
                </div>
                <?php } ?>
                <form method="post" action="<?=base_url('proses/savedbeamwarping');?>" name="fr1">
                <h1>Input Produksi Warping Tanggal <?=$printBln;?></h1>
                <input type="hidden" id="kode_proses" name="kode_proses" value="<?=$kodeproses;?>">
                <!-- pembuatan list nama kode warping -->
                <?php 
                $listnama = $this->db->query("SELECT * FROM beam_warping GROUP BY kode_beam");
                ?>
                    <datalist id="nps">
                        <?php foreach($listnama->result() as $ls): ?>
                        <option value="<?=$ls->kode_beam;?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                <!-- end list nama -->
                <?php 
                if($cek_beamwar->num_rows() == 0){
                $n=1; for ($i=0; $i <$jmlBeam ; $i++) { ?>
                <h1>Beam <?=$n;?></h1>
                <div class="forminput mt-20">
                    <label for="np<?=$n;?>">Nomor/Kode</label>
                    <input type="text" list="nps" class="sm" placeholder="Masukan Kode Beam" name="kodebeam[]" id="np<?=$n;?>" required>
                    
                </div>
                <div class="forminput mt-10">
                    <label for="np2-<?=$n;?>">Ukuran/Panjang</label>
                    <input type="text" class="sm" placeholder="Masukan Panjang Beam" name="pjgbeam[]" id="np2-<?=$n;?>" required>
                </div>
                <?php $n++; } 
                } else {
                    $n=1;
                    foreach($cek_beamwar->result() as $val){
                ?>
                <h1>Beam <?=$n;?></h1>
                <div class="forminput mt-20">
                    <label for="np<?=$n;?>">Nomor/Kode</label>
                    <input type="text" list="nps" class="sm" value="<?=$val->kode_beam;?>" name="kodebeam[]" id="np<?=$n;?>" required>
                    
                </div>
                <div class="forminput mt-10">
                    <label for="np2-<?=$n;?>">Ukuran/Panjang</label>
                    <input type="text" class="sm" value="<?=$val->ukuran_panjang;?>" name="pjgbeam[]" id="np2-<?=$n;?>" required>
                </div>
                <?php $n++;
                    }
                }
                ?>
                <!-- <div class="forminput mt-20">
                    <label for="mtr_beam">Meter per Beam</label>
                    <input type="text" class="sm" placeholder="Masukan Panjang Beam" name="mtr_beam" id="mtr_beam" inputmode="numeric">
                </div> -->
                <div class="karungan" id="meter_perbeam">
                    <!-- <div class="box-karung">
                        <span>Beam 01</span>
                        <label for="tes">Meter per Beam</label>
                        <input type="text" id="tes" placeholder="Masukan ukuran">
                    </div>
                    <div class="box-karung">
                        <span>Beam 01</span>
                        <label for="tes">Meter per Beam</label>
                        <input type="text" id="tes" placeholder="Masukan ukuran">
                    </div> -->
                </div>
                <p>&nbsp;</p>
                <button class="sbmit" type="submit" id="btnSimpanWarping">Simpan</button>
                </form>
            </div>

        </div>
    </div>