<div class="formdata">
                <!-- <div class="notifikasi-sukses" style="background:#02a82e;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Ini pemberitahuan notifikasi
                </div>
                <div class="notifikasi-gagal" style="background:#de071c;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Ini pemberitahuan notifikasi
                </div> -->
                <?php if($notif=="true"){ ?>
                <div class="notifikasi-sukses" style="background:#02a82e;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Berhasil menyimpan produksi AJL.
                </div>
                <?php } ?>
                <h1>Input Produksi Mesin AJL</h1>
                <form action="<?=base_url('proses/startmesinajl');?>" method="post" name="fr1">
                <input type="hidden" id="kode_proses" name="kode_proses" value="<?=$kodeproses;?>" required>
                <input type="hidden" id="id_beam_sizing" name="id_beamsizing" value="0" required>
                <div class="forminput mt-20">
                    <label for="tgl_prod">Tanggal Produksi</label>
                    <input type="date" class="sm" placeholder="Masukan tanggal" name="tgl_produksi" id="tgl_prod">
                </div>
                <div class="forminput mt-20">
                    <label for="mcid">No MC</label>
                    <input type="text" class="sm" placeholder="Masukan Nomor Mesin" name="no_mesin" id="mcid" required>
                </div>
                
                <div class="forminput mt-20">
                    <label for="autoComplete">Beam Sizing</label>
                    <div class="autoComplete_wrapper">
                        <input id="autoComplete" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off" name="beamwarping" required>
                    </div>
                </div>
               
                <div class="forminput mt-20">
                    <label for="konsid">Konstruksi</label>
                    <input type="text" class="sm" placeholder="Masukan Konstruksi" name="konstruksi" id="konsid" required>
                </div>
                <div class="forminput mt-20">
                    <label for="lusiid">Lusi</label>
                    <input type="text" class="sm" placeholder="Masukan Lusi" name="lusi" id="lusiid" >
                </div>
                <div class="forminput mt-20">
                    <label for="pakan">Pakan</label>
                    <input type="text" class="sm" placeholder="Masukan Pakan" name="pakan" id="pakan" >
                </div>
                <div class="forminput mt-20">
                    <label for="sisir">Sisir</label>
                    <input type="text" class="sm" placeholder="Masukan Sisir" name="sisir" id="sisir" >
                </div>
                <div class="forminput mt-20">
                    <label for="pick">Pick</label>
                    <input type="text" class="sm" placeholder="Masukan Pick" name="pick" id="pick" >
                </div>
                <div class="forminput mt-20">
                    <label for="lusiid2">Panjang Lusi</label>
                    <input type="text" class="sm" placeholder="Masukan Lusi" name="pjg_lusi" id="lusiid2" >
                </div>
                <div class="forminput mt-20">
                    <label for="sizing">Sizing</label>
                    <input type="text" class="sm" placeholder="Masukan Sizing" name="sizing" id="sizing" >
                </div>
                <div class="forminput mt-20">
                    <label for="helai">Jumlah Lusi / Helai</label>
                    <input type="text" class="sm" placeholder="Masukan Jumlah Lusi" name="jml_helai" id="helai" >
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