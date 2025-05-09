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
                <h1>Input Data Pembelian Benang</h1>
                <div class="forminput mt-20">
                    <label for="autoComplete">Nama Benang</label>
                    <div class="autoComplete_wrapper">
                        <input id="autoComplete" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off" name="nama_benang">
                    </div>
                </div>
                <div class="forminput mt-20">
                    <label for="bale">Jumlah Bale</label>
                    <input type="text" class="sm" placeholder="Masukan Jumlah Bale" name="bale" id="bale" inputmode="numeric" oninput="hitungNetto()">
                </div>
                <div class="forminput mt-20">
                    <label for="netto">Netto</label>
                    <input type="text" class="sm" placeholder="Netto" name="netto" id="netto" inputmode="numeric" readonly>
                    <span class="smout">Jumlah Bale x 181.44</span>
                </div>
                <div class="forminput mt-20">
                    <label for="karung">Jumlah Karung</label>
                    <input type="text" class="sm" placeholder="Masukan Jumlah Karung" name="karung" id="karung" inputmode="numeric">
                </div>
                <div class="forminput mt-20">
                    <label for="ne">NE</label>
                    <input type="text" class="sm" placeholder="Masukan NE" name="ne" id="ne" inputmode="numeric">
                </div>
                <div class="forminput mt-20">
                    <label for="tgl">Tanggal Pembelian</label>
                    <input type="date" class="sm" placeholder="Masukan NE" name="tgl" id="tgl">
                </div>
                <button class="sbmit" id="idSubmitForm" onclick="submitKarung()">Submit</button>
                <div class="karungan" id="dataKArung">
                    <!-- <div class="box-karung">
                        <span>Kode XE1</span>
                        <label for="beratkarung">Berat Karung</label>
                        <input type="text" id="beratkarung">
                        <label for="jumlahcones">Jumlah Cones</label>
                        <input type="text" name="cones" id="jumlahcones">
                        <div><input type="checkbox" id="ckbox"><label for="ckbox">Sama dengan sebelumnya</label></div>
                    </div> -->
                    
                </div>
            </div>
        </div>
    </div>