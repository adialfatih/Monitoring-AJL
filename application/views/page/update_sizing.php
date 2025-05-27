<?php
    if($cekhasil == "null"){
        $kodeproses = "null";
        $tgl_produksi = "null";
        $oka = "null";
        $kons = "null";
        $kanji = "null";
        $batubara = "null";
        $waste_awal = "null";
        $waste_akhir = "null";
        $jml_beam_sizing = "null";
        $id_sizing = "null";
    } else {
        $kodeproses = $cekhasil['kode_proses'];
        $ex =explode('-', $cekhasil['tgl_produksi']);
        $tgl_produksi = $ex[2]."-".$this->data_model->printBln2($ex[1])."-".$ex[0];
        $oka = $cekhasil['oka'];
        $kons = $cekhasil['konstruksi'];
        $kanji = $cekhasil['jml_kanji'];
        $batubara = $cekhasil['batubara'];
        $waste_awal = $cekhasil['waste_awal'];
        $waste_akhir = $cekhasil['waste_akhir'];
        $jml_beam_sizing = $cekhasil['jumlah_beam_sizing'];
        $id_sizing = $cekhasil['id_sizing'];
    }
    //echo $kodeproses;
    $cek_beamsiz = $this->data_model->get_byid('beam_sizing', ['id_sizing'=>$id_sizing]);
?>
<div class="formdata">
                <!-- <div class="notifikasi-sukses" style="background:#02a82e;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Ini pemberitahuan notifikasi
                </div>
                <div class="notifikasi-gagal" style="background:#de071c;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Ini pemberitahuan notifikasi
                </div> -->
                <?php if($notif=="true"){ ?>
                <div class="notifikasi-sukses" style="background:#02a82e;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Berhasil menyimpan produksi sizing.
                </div>
                <?php } ?>
                <?php if($cekhasil=="null"){ ?>
                <div class="notifikasi-sukses" style="background:#de071c;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Produksi Sizing tidak ditemukan. Token Error
                </div>
                <?php } ?>
                <h1>Produksi Sizing tes</h1>
                <form action="<?=base_url('proses/updatesizing');?>" method="post" name="fr1">
                <input type="hidden" id="kode_proses" name="kode_proses" value="<?=$kodeproses;?>">
                <input type="hidden" id="idsizing" name="idsizing" value="<?=$id_sizing;?>">
                <div class="forminput mt-20">
                    <label for="tgl_prod">Tanggal Produksi</label>
                    <input type="date" class="sm" value="<?=$cekhasil['tgl_produksi'];?>" name="tgl_prod" id="tgl_prod">
                </div>
                <div class="forminput mt-20">
                    <label for="autoComplete">Beam Warping</label>
                    <div class="autoComplete_wrapper">
                        <input id="autoComplete" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off" name="beamwarping">
                    </div>
                </div>
                <div class="forminput" id="tesIdWarping">
                    <label for="okaid">&nbsp;</label>
                    <table class="table" border="1" style="width:600px;border-collapse:collapse;margin-top:20px;border:2px solid #ccc;">
                        <thead>
                            <tr style="background:#dae0eb;">
                                <th style="padding:5px;">No</th>
                                <th style="padding:5px;">Kode/No Beam</th>
                                <th style="padding:5px;">Ukuran</th>
                                <th style="padding:5px;">Mesin</th>
                                <th style="padding:5px;">Produksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="forminput mt-20">
                    <label>Sisa Beam Warping</label>
                    <div style="display:flex;flex-direction:column;">
                        <label for="kd_beam1" style="font-size:10px;">Kode Beam</label>
                        <input type="text" class="sm" id="kdbeam23">
                        <input type="hidden" id="kodesiz">
                        <input type="hidden" id="idbeamwar988">
                    </div>
                    <div style="display:flex;flex-direction:column;margin-left:10px;">
                        <label for="kd_beam2" style="font-size:10px;">Sisa</label>
                        <input type="text" class="sm" id="sisa2">
                    </div>
                    <button type="button" style="margin-left:10px;outline:none;border:none;padding:5px 8px;cursor:pointer;border-radius:4px;background:#325ea8;color:#fff;" onclick="simpansisa()">Simpan Sisa</button>
                </div>
                <div class="forminput mt-20">
                    <label for="okaid">OKA</label>
                    <input type="text" class="sm" value="<?=$oka;?>" name="oka" id="okaid" required>
                </div>
                <div class="forminput mt-20">
                    <label for="konsid">Kode Konstruksi</label>
                    <input type="text" class="sm" value="<?=$kons;?>" name="konsid" id="konsid" required>
                </div>
                <div class="forminput mt-20">
                    <label for="kanji">Jumlah Kanji (Kg)</label>
                    <input type="text" class="sm" value="<?=$kanji;?>" name="kanji" id="kanji" required>
                </div>
                <div class="forminput mt-20">
                    <label for="batubara">Batu Bara (Kg)</label>
                    <input type="text" class="" value="<?=$batubara;?>" name="batubara" id="batubara" required>
                </div>
                <div class="forminput mt-20">
                    <label for="wasteaw">Waste Awal (Kg)</label>
                    <input type="text" class="" value="<?=$waste_awal;?>" name="wasteaw" id="wasteaw" required>
                </div>
                <div class="forminput mt-20">
                    <label for="wasteak">Wasti Akhir (Kg)</label>
                    <input type="text" class="" value="<?=$waste_akhir;?>" name="wasteak" id="wasteak" required>
                </div>
                <div class="forminput mt-20">
                    <label for="jmlbeam"><strong>JUMLAH BEAM</strong></label>
                    <input type="text" class="sm" value="<?=$jml_beam_sizing;?>" name="jmlbeam" id="jmlbeam" readonly>
                </div>
                <!-- <div class="forminput mt-20">
                    <label for="mtr_beam">Meter per Beam</label>
                    <input type="text" class="sm" placeholder="Masukan Panjang Beam" name="mtr_beam" id="mtr_beam" inputmode="numeric">
                </div> -->
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
                echo '<input type="hidden" name="typeofbeam" value="0">';
                $n=1; for ($i=0; $i <$jml_beam_sizing ; $i++) { ?>
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
                    echo '<input type="hidden" name="typeofbeam" value="1">';
                    foreach($cek_beamsiz->result() as $val){
                ?>
                <h1>Beam Sizing <?=$n;?></h1>
                <input type="hidden" name="idbeamsz[]" value="<?=$val->id_beam_sizing;?>">
                <div class="forminput mt-20">
                    <label for="np<?=$n;?>">Nomor/Kode</label>
                    <input type="text" list="nps2" class="sm" value="<?=$val->kode_beam;?>" name="kodebeam[]" id="np<?=$n;?>" required>
                    
                </div>
                <div class="forminput mt-10">
                    <label for="lssd<?=$n;?>">Konstruksi</label>
                    <input type="text" list="listkons" class="sm" value="<?=$val->konstruksi;?>" name="konstr[]" id="lssd<?=$n;?>" required>
                    
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
                <button class="sbmit" type="submit" id="btnSimpanWarping">Simpan</button>
                <button type="button" id="btnDelSizing" style="background:#eb0915;outline:none;border:1px solid #fff;color:#ffff;padding:10px;border-radius:5px;cursor:pointer;margin-left:10px;margin-top:5px;">Hapus Produksi</button>
                </form>
            </div>

        </div>
    </div>