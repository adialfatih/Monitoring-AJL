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
                <?php } 
                
                $cek_data = $this->data_model->get_record('v_beam_sizing');
                ?>
                <h1>Beam Sizing</h1>
                
                <div class="karungan" id="dataKArung">
                    <?php foreach($cek_data->result() as $val): 
                        $ex = explode('-', $val->tgl_produksi);
                        $printBln = $ex[2]."-".$this->data_model->printBln2($ex[1])."-".$ex[0];
                    ?>
                    <div class="box-karung">
                        <span style="font-size:17px;"><?=$val->kode_beam;?></span>
                        <label for="totalkarung">Panjang (Mtr)</label>
                        <input type="text" style="color:#124fb0;" id="totalkarung" value="<?=number_format($val->ukuran_panjang,0,',','.');?>" readonly>
                        <label for="beratkarung" class="mt-10">Tanggal Produksi</label>
                        <input type="text" style="color:#124fb0;" id="beratkarung" value="<?=$printBln;?>" readonly>
                        <!-- <label for="jumlahcones" class="mt-10">Jenis Mesin</label>
                        <input type="text" name="cones" style="color:#124fb0;" id="jumlahcones" value="<=$val->jenis_mesin;?>" readonly> -->
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>