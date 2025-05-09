<div class="formdata">
                <!-- <div class="notifikasi-sukses" style="background:#02a82e;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Ini pemberitahuan notifikasi
                </div>
                <div class="notifikasi-gagal" style="background:#de071c;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Ini pemberitahuan notifikasi
                </div> -->
                <?php if($notif=="true"){ ?>
                <div class="notifikasi-sukses" style="background:#02a82e;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Berhasil menyimpan pembelian benang.
                </div>
                <?php } ?>
                <h1>Input Produksi Warping</h1>
                <input type="hidden" id="kode_proses" name="kode_proses" value="<?=$kodeproses;?>">
                <div class="forminput mt-20">
                    <label for="tgl_prod">Tanggal Produksi</label>
                    <input type="date" class="sm" placeholder="Masukan tanggal" name="tgl_prod" id="tgl_prod" onchange="saveDate(this.value)">
                </div>
                <div class="forminput mt-20">
                    <label for="autoComplete">Jenis Mesin</label>
                    <div class="autoComplete_wrapper">
                        <input id="autoComplete" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off" name="nama_benang" onchange="saveMc(this.value)" required>
                    </div>
                </div>
                <div class="forminput mt-20">
                    <label for="creel">Jumlah Creel</label>
                    <input type="text" class="sm" placeholder="Masukan Jumlah Creel" name="creel" id="creel" inputmode="numeric" onchange="saveCreel(this.value)">
                </div>
                <div class="forminput mt-20">
                    <label for="jumlahss">Jumlah Beam</label>
                    <input type="text" class="sm" placeholder="Masukan Jumlah Beam" name="jml_beam" id="jumlahss" inputmode="numeric" oninput="generateForms_2(this.value)" onkeyup="generateForms_2(this.value)">
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
                
            </div>

        </div>
    </div>