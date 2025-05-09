<?php 
$dtlis = $this->data_model->get_byid('produksi_warping', ['kode_proses'=>$kodeproses])->row_array();
$ex = explode('-', $dtlis['tgl_produksi']);
$printBln = $ex[2]."-".$this->data_model->printBln2($ex[1])."-".$ex[0];
$jmlBeam = $dtlis['jml_beam'];
$id_warping = $dtlis['id_produksi_warping'];
$cek_beamwar = $this->data_model->get_byid('beam_warping', ['id_produksi_warping'=>$id_warping]);
?>
<div class="formdata">
                <!-- <div class="notifikasi-sukses" style="background:#02a82e;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Ini pemberitahuan notifikasi
                </div>
                <div class="notifikasi-gagal" style="background:#de071c;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Ini pemberitahuan notifikasi
                </div> -->
                <form name="fr1" action="<?=base_url('proses/updatewarpingnew');?>" method="post">
                <?php if($notif=="true"){ ?>
                <div class="notifikasi-sukses" style="background:#02a82e;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Berhasil menyimpan pembelian benang.
                </div>
                <?php } ?>
                <?php if($cekhasil=="null"){ ?>
                <div class="notifikasi-sukses" style="background:#de071c;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Data tidak ditemukan.
                </div>
                <?php } ?>
                <h1>Update Produksi Warping</h1>
                <input type="hidden" id="kode_proses" name="kode_proses" value="<?=$kodeproses;?>">
                <div class="forminput mt-20">
                    <label for="tgl_prod">Tanggal Produksi</label>
                    <input type="date" class="sm" value="<?=$cekhasil['tgl_produksi'];?>" name="tgl_prod" id="tgl_prod" required>
                </div>
                <div class="forminput mt-20">
                    <label for="autoComplete">Jenis Mesin</label>
                    <div class="autoComplete_wrapper">
                        <input id="autoComplete" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off" name="jenis_mesin" value="<?=$cekhasil['jenis_mesin'];?>" required>
                    </div>
                </div>
                <div class="forminput mt-20">
                    <label for="creel">Jumlah Creel</label>
                    <input type="text" class="sm" value="<?=$cekhasil['jml_creel'];?>" name="creel" id="creel" inputmode="numeric" >
                </div>
                <div class="forminput mt-20">
                    <label for="jumlahss">Jumlah Beam</label>
                    <input type="text" class="sm" value="<?=$cekhasil['jml_beam'];?>" name="jml_beam" id="jumlahss" inputmode="numeric" readonly>
                </div>
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
                <!-- tes -->
                <!-- <h1>Input Produksi Warping Tanggal <=$printBln;?></h1> -->
                <input type="hidden" id="kode_proses2" name="kode_proses2" value="<?=$kodeproses;?>">
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
                <input type="hidden" name="tipebeam" value="0">
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
                    echo '<input type="hidden" name="tipebeam" value="1">';
                    foreach($cek_beamwar->result() as $val){
                ?>
                <h1>Beam <?=$n;?></h1>
                
                
                <div class="forminput mt-20">
                    <label for="np<?=$n;?>">Nomor/Kode</label>
                    <input type="text" list="nps" class="sm" value="<?=$val->kode_beam;?>" name="kodebeam[]" id="np<?=$n;?>" required>
                    <input type="hidden" name="idbeamwar[]" value="<?=$val->id_beamwar;?>">
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
                <!-- tes -->
                <div style="display:flex;align-items:center;">
                <button class="sbmit" type="submit" id="btnSimpanWarping">Simpan</button>
                <button type="button" id="btnSimpanWarping23" style="background:#eb0915;outline:none;border:1px solid #fff;color:#ffff;padding:10px;border-radius:5px;cursor:pointer;margin-left:10px;margin-top:5px;">Hapus Produksi</button>
                <a href="<?=base_url('data/bahanbaku/warping/'.sha1($id_warping));?>">
                <button type="button" id="btn-bahanbaku" style="background:#069e13;outline:none;border:1px solid #fff;color:#ffff;padding:10px;border-radius:5px;cursor:pointer;margin-left:10px;margin-top:5px;">Penggunaan Bahan Baku</button>
                </div></a>
                </form>
            </div>

        </div>
    </div>