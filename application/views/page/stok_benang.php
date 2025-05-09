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
                $nama_benang = array();
                $cek_pembelian = $this->data_model->get_record('penerimaan_benang');
                foreach($cek_pembelian->result() as $val):
                    if(in_array($val->nama_benang, $nama_benang)){} else {
                        $nama_benang[] = $val->nama_benang;
                    }
                endforeach;
                ?>
                <h1>Stok Benang</h1>
                
                <div class="karungan" id="dataKArung">
                    <?php foreach($nama_benang as $bng): 
                        //$total_karung = $this->data_model->get_byid('karung_benang', ['nama_benang'=>$bng, 'status_karung'=>'masih'])->num_rows();
                        $total_karung = $this->db->query("SELECT * FROM `karung_benang` WHERE `nama_benang` = '$bng' AND `status_karung` = 'masih' AND jumlah_cones > 0 ");
                        $jumlah_karung = 0; $sisa_cones_nilai=0; $total_berat=0;
                        foreach($total_karung->result() as $pal){
                            $jumlah_cones = $pal->jumlah_cones;
                            $cones_terpakai = $pal->cones_terpakai;
                            $berat_percones = floatval($pal->berat_karung) / intval($jumlah_cones);
                            $sisa_cones = intval($jumlah_cones) - intval($cones_terpakai);
                            if($sisa_cones > 0){
                                $jumlah_karung++;
                                $sisa_cones_nilai = $sisa_cones_nilai + $sisa_cones;
                            }
                            $berat_ini = $sisa_cones * $berat_percones;
                            $total_berat = $total_berat + $berat_ini;
                        }
                        //$total_berat_karung = $this->db->query("SELECT SUM(berat_karung) AS ukr FROM karung_benang WHERE nama_benang='$bng' AND status_karung='masih'")->row("ukr");
                        //$total_cones = $this->db->query("SELECT SUM(jumlah_cones) AS ukr FROM karung_benang WHERE nama_benang='$bng' AND status_karung='masih'")->row("ukr");
                    ?>
                    <div class="box-karung">
                        <span><?=$bng;?></span>
                        <label for="totalkarung">Total Karung</label>
                        <input type="text" id="totalkarung" value="<?=$jumlah_karung;?>" readonly>
                        <label for="beratkarung">Berat Karung</label>
                        <input type="text" id="beratkarung" value="<?=number_format($total_berat,2,',','.');?>" readonly>
                        <label for="jumlahcones">Jumlah Total Cones tes</label>
                        <input type="text" name="cones" id="jumlahcones" value="<?=number_format($sisa_cones_nilai,0,',','.');?>" readonly>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>