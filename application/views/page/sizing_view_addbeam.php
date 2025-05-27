<div class="formdata">
<?php 
$dtlis = $this->data_model->get_byid('produksi_sizing', ['kode_proses'=>$kodeproses])->row_array();
$ex = explode('-', $dtlis['tgl_produksi']);
$printBln = $ex[2]."-".$this->data_model->printBln2($ex[1])."-".$ex[0];
$jmlBeam = $dtlis['jumlah_beam_sizing'];
$id_sizing = $dtlis['id_sizing'];
$cek_beamsiz = $this->data_model->get_byid('beam_sizing', ['id_sizing'=>$id_sizing]);
?>
                <!-- <div class="notifikasi-sukses" style="background:#02a82e;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Ini pemberitahuan notifikasi
                </div>
                <div class="notifikasi-gagal" style="background:#de071c;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Ini pemberitahuan notifikasi
                </div> -->
                <?php if($notif=="true"){ ?>
                <div class="notifikasi-sukses" style="background:#02a82e;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Berhasil menyimpan proses Sizing.
                </div>
                <?php } ?>
                <form method="post" action="<?=base_url('proses/savedbeamsizing');?>" name="fr1">
                <h1>Input Produksi Sizing Tanggal <?=$printBln;?></h1>
                <input type="hidden" id="kode_proses" name="kode_proses" value="<?=$kodeproses;?>">
                <input type="hidden" id="id_sizing" name="id_sizing" value="<?=$id_sizing;?>">
                <datalist id="listkons">
                    <option value="RJ02">
                    <option value="RJ03">
                    <option value="RJ04">
                    <option value="RK05A">
                    <option value="RJ05B">
                    <option value="RJ05C">
                    <option value="RJ05D">
                    <option value="RJ05F">
                    <option value="RJ05G">
                    <option value="RJ05H">
                    <option value="RJ13">
                    <option value="RJ13A">
                    <option value="RJ13B">
                    <option value="RJ13C">
                    <option value="RJ13C">
                    <option value="RJ15">
                    <option value="RJ15A">
                    <option value="RJ15B">
                    <option value="RJ15C">
                    <option value="RJ15D">
                    <option value="RJ15H">
                    <option value="RJ15J">
                    <option value="RJ15K">
                    <option value="RJ15L">
                    <option value="RJ16">
                    <option value="RJ17">
                    <option value="RJ17A">
                    <option value="RJ18">
                    <option value="RJ16A">
                    <option value="RJ16B">
                    <option value="RJ19">
                    <option value="RJ05K">
                    <option value="RJ13D">
                    <option value="RJ09A">
                    <option value="RJ03A">
                    <option value="RJ05J">
                    <option value="RJ20">
                    <option value="RJ13E">
                    <option value="RJ05L">
                    <option value="RJ05L_RJS">
                    <option value="RJ13F">
                    <option value="RJ13G">
                    <option value="RJ13H">
                    <option value="RJ13J">
                    <option value="RJ05M">
                    <option value="RJ17B">
                    <option value="RJ05B_RJS">
                    <option value="RJ13K">
                    <option value="RJ05GS">
                    <option value="RJ13L">
                </datalist>
                <!-- pembuatan list nama kode warping -->
                <?php 
                $listnama = $this->db->query("SELECT * FROM beam_sizing GROUP BY kode_beam");
                ?>
                    <datalist id="nps2">
                        <?php foreach($listnama->result() as $ls): ?>
                        <option value="<?=$ls->kode_beam;?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                <!-- end list nama -->
                <?php 
                if($cek_beamsiz->num_rows() == 0){
                $n=1; for ($i=0; $i <$jmlBeam ; $i++) { ?>
                <h1>Beam Sizing <?=$n;?></h1>
                <div class="forminput mt-20">
                    <label for="np<?=$n;?>">Nomor/Kode</label>
                    <input type="text" list="nps2" class="sm" placeholder="Masukan Kode Beam" name="kodebeam[]" id="np<?=$n;?>" required>
                    
                </div>
                <div class="forminput mt-10">
                    <label for="lssd<?=$n;?>">Konstruksi</label>
                    <input type="text" list="listkons" class="sm" placeholder="Masukan Kode Konstruksi" name="konstr[]" id="lssd<?=$n;?>" required>
                    
                </div>
                <div class="forminput mt-10">
                    <label for="np2-<?=$n;?>">Ukuran/Panjang</label>
                    <input type="text" class="sm" placeholder="Masukan Panjang Beam" name="pjgbeam[]" id="np2-<?=$n;?>" required>
                </div>
                <div class="forminput mt-10">
                    <label for="draf2-<?=$n;?>">Draft</label>
                    <input type="text" class="sm" placeholder="Masukan Draft" name="draft[]" id="draf2-<?=$n;?>" required>
                </div>
                <?php $n++; } 
                } else {
                    $n=1;
                    foreach($cek_beamsiz->result() as $val){
                ?>
                <h1>Beam Sizing <?=$n;?></h1>
                <div class="forminput mt-20">
                    <label for="np<?=$n;?>">Nomor/Kode</label>
                    <input type="text" list="nps2" class="sm" value="<?=$val->kode_beam;?>" name="kodebeam[]" id="np<?=$n;?>" required>
                    
                </div>
                <div class="forminput mt-10">
                    <label for="lssd<?=$n;?>">Konstruksi</label>
                    <input type="text" list="listkons" class="sm" placeholder="Masukan Kode Konstruksi" name="konstr[]" id="lssd<?=$n;?>" required>
                    
                </div>
                <div class="forminput mt-10">
                    <label for="np2-<?=$n;?>">Ukuran/Panjang</label>
                    <input type="text" class="sm" value="<?=$val->ukuran_panjang;?>" name="pjgbeam[]" id="np2-<?=$n;?>" required>
                </div>
                <div class="forminput mt-10">
                    <label for="draf2-<?=$n;?>">Draft</label>
                    <input type="text" class="sm" value="<?=$val->draft;?>" name="draft[]" id="draf2-<?=$n;?>" required>
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