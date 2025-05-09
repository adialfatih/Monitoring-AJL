<div class="formdata">
                <!-- <div class="notifikasi-sukses" style="background:#02a82e;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Ini pemberitahuan notifikasi
                </div>
                <div class="notifikasi-gagal" style="background:#de071c;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Ini pemberitahuan notifikasi
                </div> -->
                <?php if($notif=="true"){ ?>
                <div class="notifikasi-sukses" style="background:#02a82e;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Berhasil menyimpan pembelian Bahan Baku.
                </div>
                <?php } ?>
                <h1>Input Data Pembelian Bahan Baku</h1>
                <div class="forminput mt-20">
                    <label for="autoComplete">Nama Barang</label>
                    <div class="autoComplete_wrapper">
                        <input id="autoComplete" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off" name="nama_baku" required>
                    </div>
                </div>
                <div class="forminput mt-20">
                    <label for="jnsid">Jenis Barang</label>
                    <select name="jns" id="jnsid" style="width:200px;padding:10px;outline:none;border:1px solid #ccc;border-radius:4px;" required>
                        <option value="">-- Pilih --</option>
                        <option value="Batu Bara">Batu Bara</option>
                        <option value="Obat Kanji">Obat Kanji</option>
                    </select>
                </div>
                <div class="forminput mt-20">
                    <label for="tglid">Tanggal Pembelian</label>
                    <input type="date" class="sm" placeholder="Tanggal" name="tglid" id="tglid" required>
                   
                </div>
                <div class="forminput mt-20">
                    <label for="jmlbeli">Jumlah Pembelian</label>
                    <input type="text" class="sm" placeholder="Masukan Jumlah Pembelian" name="jmlbeli" id="jmlbeli">
                    <select name="satuan" id="satuan" style="width:100px;padding:7px 10px;outline:none;border:1px solid #ccc;border-radius:4px;margin-left:10px;" required>
                        <option value="">-- Pilih --</option>
                        <option value="KG">KG</option>
                        <option value="Bale">Bale</option>
                        <option value="Pcs">Pcs</option>
                        <option value="Ton">Ton</option>
                        <option value="M">Meter</option>
                    </select>
                </div>
                <div class="forminput mt-20">
                    <label for="supp">Supplier</label>
                    <input type="text" placeholder="Masukan Nama Supplier" name="supp" id="supp" required>
                   
                </div>
                <div class="forminput mt-20">
                    <label for="sj">Surat Jalan</label>
                    <input type="text" placeholder="Masukan Nomor Surat Jalan" name="sj" id="sj" required>
                   
                </div>
                <div class="forminput mt-20">
                    <label for="ket">Keterangan</label>
                    <textarea name="ket" id="ket" style="width:350px;height:50px;padding:7px 10px;outline:none;border:1px solid #ccc;border-radius:4px;" placeholder="Tambahkan Keterangan"></textarea>
                   
                </div>
                <button class="sbmit" id="idSubmitForm" onclick="submitData()">Submit</button>
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