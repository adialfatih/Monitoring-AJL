<form style="width:100%;" method="post" action="<?=base_url('proses/update_pembelian');?>">
<input type="hidden" name="id_penerimaan" value="<?=$cekhasil['id_penerimaan'];?>">
<div class="formdata">
                <!-- <div class="notifikasi-sukses" style="background:#02a82e;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Ini pemberitahuan notifikasi
                </div>
                <div class="notifikasi-gagal" style="background:#de071c;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Ini pemberitahuan notifikasi
                </div> -->
                <?php if($notif=="true"){ ?>
                <div class="notifikasi-sukses" style="background:#02a82e;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Berhasil menyimpan update pembelian benang.
                </div>
                <?php } ?>
                <?php if($cekhasil=="null"){ ?>
                <div class="notifikasi-gagal" style="background:#de071c;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Data pembelian tidak ditemukan
                </div>
                <?php } ?>
                <h1>Update Data Pembelian Benang</h1>
                <div class="forminput mt-20">
                    <label for="autoComplete">Nama Benang</label>
                    <div class="autoComplete_wrapper">
                        <input id="autoComplete" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off" name="nama_benang" value="<?=$cekhasil['nama_benang'];?>" required>
                    </div>
                </div>
                <div class="forminput mt-20">
                    <label for="bale">Jumlah Bale</label>
                    <input type="text" class="sm" placeholder="Masukan Jumlah Bale" name="bale" id="bale" inputmode="numeric" oninput="hitungNetto()" value="<?=$cekhasil['jumlah_bale'];?>" required>
                </div>
                <div class="forminput mt-20">
                    <label for="netto">Netto</label>
                    <?php $netto_fixed = number_format($cekhasil['netto'],2,'.',''); ?>
                    <input type="text" class="sm" placeholder="Netto" name="netto" id="netto" inputmode="numeric" value="<?=$netto_fixed;?>" readonly>
                    <span class="smout">Jumlah Bale x 181.44</span>
                </div>
                <div class="forminput mt-20">
                    <label for="karung">Jumlah Karung</label>
                    <input type="text" class="sm" placeholder="Masukan Jumlah Karung" name="karung" id="karung" inputmode="numeric" value="<?=$cekhasil['jumlah_karung'];?>" required>
                </div>
                <div class="forminput mt-20">
                    <label for="ne">NE</label>
                    <input type="text" class="sm" placeholder="Masukan NE" name="ne" id="ne" inputmode="numeric" value="<?=$cekhasil['en_ee'];?>" required>
                </div>
                <div class="forminput mt-20">
                    <label for="tgl">Tanggal Pembelian</label>
                    <input type="date" class="sm" placeholder="Masukan NE" name="tgl" id="tgl" value="<?=$cekhasil['tanggal_penerimaan'];?>" required>
                </div>
                <button class="sbmit" type="submit">Simpan</button>
                <div class="karungan">
                    <?php
                        $all_karung = $this->data_model->get_byid('karung_benang', ['kode_pembelian'=>$cekhasil['kode_karung']]);
                        $no = 1;
                        foreach($all_karung->result() as $val):
                        ?>
                        <div class="box-karung">
                            <span><?=$val->kode_karung;?></span>
                            <label for="beratkarung<?=$no;?>">Berat Karung (Kg)</label>
                            <input type="text" id="beratkarung<?=$no;?>" name="beratkarung[]" value="<?=$val->berat_karung;?>" class="jumlah_berat">
                            <label for="jumlahcones<?=$no;?>">Jumlah Cones</label>
                            <input type="text" name="cones[]" id="jumlahcones<?=$no;?>" value="<?=$val->jumlah_cones;?>">
                            <?php if($no==1){} else { ?>
                                <div><input type="checkbox" id="cekbox<?=$no;?>" onchange="autofillUkuran('<?=$no;?>')"><label for="ckbox<?=$no;?>">Sama dengan sebelumnya</label></div>  
                            <?php } ?>
                            <input type="hidden" value="<?=$val->kode_karung;?>" name="kodekarung[]">
                        </div>
                        
                    <?php $no++; endforeach; ?>
                    <p>Total Berat Karung <span id="total_berat">0</span></p>
                </div>
            </div>
        </div>
    </div>
</form>
    <script>
                    function autofillUkuran(index) {
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