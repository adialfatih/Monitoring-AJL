<?php
    $this->db->query("DELETE FROM produksi_sizing WHERE tgl_produksi='0000-00-00' AND oka='null' AND konstruksi='null'");
    $this->data_model->saved('produksi_sizing', [
        'id_beamwar' => '0',
        'tgl_produksi' => '0000-00-00',
        'oka' => 'null',
        'konstruksi' => 'null',
        'jml_kanji' => '0',
        'batubara' => '0',
        'waste_awal' => '0',
        'waste_akhir' => '0',
        'jumlah_beam_sizing' => '0',
        'kode_proses' => $kodeproses
    ]);
    // $this->db->query("SELECT * FROM beam_warping WHERE kode_proses_sizing!='null'");
    // $this->db->query("UPDATE beam_warping SET kode_proses_sizing='null' WHERE kode_proses_sizing NOT IN (SELECT kode_proses FROM produksi_sizing)");
?>
<div class="formdata">
                <!-- <div class="notifikasi-sukses" style="background:#02a82e;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Ini pemberitahuan notifikasi
                </div>
                <div class="notifikasi-gagal" style="background:#de071c;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Ini pemberitahuan notifikasi
                </div> -->
                <?php if($notif=="true"){ ?>
                <div class="notifikasi-sukses" style="background:#02a82e;color:#fff;padding:15px;border-radius:5px;font-size:14px;">Berhasil menyimpan pembelian benang.</div>
                <?php } ?>
                <h1>Input Produksi Sizing</h1>
                <form action="<?=base_url('proses/savesizing');?>" method="post" name="fr1">
                <input type="hidden" id="kode_proses" name="kode_proses" value="<?=$kodeproses;?>">
                <div class="forminput mt-20">
                    <label for="tgl_prod">Tanggal Produksi</label>
                    <input type="date" class="sm" placeholder="Masukan tanggal" name="tgl_prod" id="tgl_prod">
                </div>
                <div class="forminput mt-20" >
                    <label for="beamfrom">Jenis Beam</label>
                    <select name="beamfrom" id="beamfrom" style="width:100px;padding:5px;outline:none;border:1px solid #ccc;border-radius:5px;">
                        <option value="brj">BRJ</option>
                        <option value="bsm">BSM</option>
                    </select>
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
                    <label for="okaid">OKA</label>
                    <input type="text" class="sm" placeholder="Masukan OKA" name="oka" id="okaid" required>
                </div>
                <div class="forminput mt-20">
                    <label for="konsid">Kode Konstruksi</label>
                    <input type="text" class="sm" placeholder="Masukan Konstruksi" name="konsid" id="konsid" required>
                </div>
                <div class="forminput mt-20">
                    <label for="kanji">Jumlah Kanji (Kg)</label>
                    <input type="text" class="sm" placeholder="Masukan Kanji" name="kanji" id="kanji" required>
                </div>
                <div class="forminput mt-20">
                    <label for="batubara">Batu Bara (Kg)</label>
                    <input type="text" class="" placeholder="Masukan Penggunaan Batu Bara" name="batubara" id="batubara" required>
                </div>
                <div class="forminput mt-20">
                    <label for="wasteaw">Waste Awal (Kg)</label>
                    <input type="text" class="" placeholder="Masukan Nilai Waste Awal" name="wasteaw" id="wasteaw" required>
                </div>
                <div class="forminput mt-20">
                    <label for="wasteak">Wasti Akhir (Kg)</label>
                    <input type="text" class="" placeholder="Masukan Nilai Waste Akhir" name="wasteak" id="wasteak" required>
                </div>
                <div class="forminput mt-20">
                    <label for="jmlbeam"><strong>JUMLAH BEAM</strong></label>
                    <input type="text" class="sm" placeholder="Masukan Jumlah Beam" name="jmlbeam" id="jmlbeam" required>
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
                <button class="sbmit" type="submit" id="btnSimpanWarping">Simpan</button>
                </form>
            </div>

        </div>
    </div>