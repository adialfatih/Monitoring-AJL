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
                if($sess_user == "septi" OR $sess_user == "admin" OR $sess_user == "adel"){
                    echo "<h1>Input Produksi Sizing Pershift</h1>";
                    ?>
                <input type="hidden" id="kode_proses" name="kode_proses" value="<?=$kodeproses;?>">
                <div class="forminput mt-20">
                    <label for="tgl_prod">Tanggal Produksi</label>
                    <input type="date" class="sm" placeholder="Masukan tanggal" name="tgl_prod" id="tgl_prod" >
                </div>
                <div class="forminput mt-20">
                    <label for="prod1">Produksi Sizing</label>
                    <div style="display:flex;flex-direction:column;">
                        <label for="hcb1" style="color:#020202;font-size:10px;">Shift 1</label>
                        <input type="text" class="sm owek" placeholder="Produksi Shif 1" id="hcb1">
                    </div>
                    <div style="display:flex;flex-direction:column;margin-left:10px;">
                        <label for="hcb2" style="color:#020202;font-size:10px;">Shift 2</label>
                        <input type="text" class="sm owek" placeholder="Produksi Shif 2" id="hcb2">
                    </div>
                    <div style="display:flex;flex-direction:column;margin-left:10px;">
                        <label for="hcb3" style="color:#020202;font-size:10px;">Shift 3</label>
                        <input type="text" class="sm owek" placeholder="Produksi Shif 3" id="hcb3">
                    </div>
                </div>
                
                <div class="forminput mt-20">
                    <label for="ket1">Keterangan</label>
                    <textarea name="ket" id="ket1" style="width:300px;height:70px;padding:8px;outline:none;border:1px solid #ccc;border-radius:4px;" placeholder="Masukan keterangan, Jika Libur maka produksi di isi dengan nol"></textarea>
                </div>
                
                <p>&nbsp;</p>
                <button class="sbmit" type="submit" onclick="btnSimpanSizeshif()">Simpan</button>
                    <?php
                } else {
                    echo "<h1>Produksi Sizing Pershift</h1>";
                }
                ?>
                <!-- <h1>Input Produksi Sizing Pershift</h1> -->
                
                <div style="display:flex;align-items:center;margin-top:30px;">
                    <label for="bulan" style="font-size:14px;">Tampilkan Bulan</label>
                    <?php
                    $urir = $this->uri->segment(2);
                    ?>
                    <select name="bulan" id="blnid" style="margin-left:20px;" onchange="chgbln(this.value)">
                        <option value="">--Pilih Bulan--</option>
                        <option value="01" <?=$urir=="01" ? 'selected':'';?>>Januari</option>
                        <option value="02" <?=$urir=="02" ? 'selected':'';?>>Februari</option>
                        <option value="03" <?=$urir=="03" ? 'selected':'';?>>Maret</option>
                        <option value="04" <?=$urir=="04" ? 'selected':'';?>>April</option>
                        <option value="05" <?=$urir=="05" ? 'selected':'';?>>Mei</option>
                        <option value="06" <?=$urir=="06" ? 'selected':'';?>>Juni</option>
                        <option value="07" <?=$urir=="07" ? 'selected':'';?>>Juli</option>
                        <option value="08" <?=$urir=="08" ? 'selected':'';?>>Agustus</option>
                        <option value="09" <?=$urir=="09" ? 'selected':'';?>>September</option>
                        <option value="10" <?=$urir=="10" ? 'selected':'';?>>Oktober</option>
                        <option value="11" <?=$urir=="11" ? 'selected':'';?>>November</option>
                        <option value="12" <?=$urir=="12" ? 'selected':'';?>>Desember</option>
                    </select>
                </div>
                <script>
                    function chgbln(bln){
                        document.location.href = "<?=base_url('sizing-pershift/');?>"+bln+"";
                    }
                </script>
                <div style="width:100%;overflow-x:auto;margin-top:30px;">
                    <table border="1" style="border-collapse:collapse;border:1px solid #fff;">
                        <thead>
                            <tr style="background:#aeb1b5;color:#000;border:1px solid #fff;">
                                <th style="font-size:14px;padding:5px;">No.</th>
                                <th style="font-size:14px;padding:5px;">Tanggal</th>
                                <th style="font-size:14px;padding:5px;width:100px;">Shift 1</th>
                                <th style="font-size:14px;padding:5px;width:100px;">Shift 2</th>
                                <th style="font-size:14px;padding:5px;width:100px;">Shift 3</th>
                                <th style="font-size:14px;padding:5px;width:100px;">Total</th>
                                <th style="font-size:14px;padding:5px;">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $ar = array(
                                '00' => 'NaN', '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                            );
                            $tanggal_sekarang = date('Y-m-d');
                            
                            if($urir=="01" OR $urir=="02" OR $urir=="03" OR $urir=="04" OR $urir=="05" OR $urir=="06" OR $urir=="07" OR $urir=="08" OR $urir=="09" OR $urir=="10" OR $urir=="11" OR $urir=="12"){
                            $bulan_sekarang = $urir;
                            } else {
                            $bulan_sekarang = date('m');
                            }
                            $tahun_sekarang = date('Y');
                            $jumlah_hari = cal_days_in_month(CAL_GREGORIAN, $bulan_sekarang, $tahun_sekarang);
                            $total_produksi = 0;
                            for ($i = 1; $i <= $jumlah_hari; $i++) {
                                $tanggal = sprintf('%04d-%02d-%02d', $tahun_sekarang, $bulan_sekarang, $i);
                                $ex=explode('-', $tanggal);
                                $print_tgl = $ex[2]." ".$ar[$ex[1]]." ".$ex[0];
                                $cekdt = $this->data_model->get_byid('produksi_sizing_perif', ['tgl'=>$tanggal]);
                                if($cekdt->num_rows() == 0){
                            ?>
                            <tr style="background:#e3e3e3;">
                                <td style="font-size:12px;padding:5px;text-align:center;"><?=$i;?></td>
                                <td style="font-size:12px;padding:5px;"><?=$print_tgl;?></td>
                                <td style="font-size:12px;padding:5px;">-</td>
                                <td style="font-size:12px;padding:5px;">-</td>
                                <td style="font-size:12px;padding:5px;">-</td>
                                <td style="font-size:12px;padding:5px;">-</td>
                                <td style="font-size:12px;padding:5px;">-</td>
                            </tr>
                            <?php
                                } else {                     
                                $total_hcb = $cekdt->row("siz1") + $cekdt->row("siz2") + $cekdt->row("siz3");
                                $total_produksi+=$total_hcb;
                            ?>
                            <tr style="background:#e3e3e3;">
                                <td style="font-size:12px;padding:5px;text-align:center;"><?=$i;?></td>
                                <td style="font-size:12px;padding:5px;"><?=$print_tgl;?></td>
                                <td style="font-size:12px;padding:5px;text-align:center;"><?=number_format($cekdt->row("siz1"));?></td>
                                <td style="font-size:12px;padding:5px;text-align:center;"><?=number_format($cekdt->row("siz2"));?></td>
                                <td style="font-size:12px;padding:5px;text-align:center;"><?=number_format($cekdt->row("siz3"));?></td>
                                <td style="font-size:12px;padding:5px;text-align:center;"><?=number_format($total_hcb);?></td>
                                <td style="font-size:12px;padding:5px;"><?=$cekdt->row("ket");?></td>

                            </tr>
                            <?php }
                            }
                            ?>
                            <tr style="background:#e3e3e3;">
                                <th style="font-size:12px;padding:5px;text-align:center;" colspan="5">Total</th>
                                <th style="font-size:12px;padding:5px;text-align:center;"><?=number_format($total_produksi);?></th>
                                <td style="font-size:12px;padding:5px;"></td>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>