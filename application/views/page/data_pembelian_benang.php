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
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <h1>Data Pembelian Benang</h1>
                    <a href="<?=base_url('pembelian-benang');?>">
                    <button class="sbmit" id="idSubmitForm" style="width:200px;">Input Pembelian</button></a>
                </div>
                <div class="table-responsive" style="width:100%;overflow-x:auto;">
                    <table class="table" border="1" style="width:100%;border-collapse:collapse;margin-top:20px;border:2px solid #ccc;">
                        <thead>
                            <tr style="background:#dae0eb;">
                                <th style="padding:5px;">No.</th>
                                <th style="padding:5px;">Tanggal Pembelian</th>
                                <th style="padding:5px;">Nama Benang</th>
                                <th style="padding:5px;">Kode Pembelian</th>
                                <th style="padding:5px;">Bale</th>
                                <th style="padding:5px;">Netto</th>
                                <th style="padding:5px;">NE</th>
                                <th style="padding:5px;">Jumlah Karung</th>
                                <th style="padding:5px;">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $dataquery = $this->db->query("SELECT * FROM penerimaan_benang ORDER BY tanggal_penerimaan DESC");
                            if($dataquery->num_rows() > 0){
                                $no=1;
                                foreach($dataquery->result() as $val):
                                $ex = explode('-', $val->tanggal_penerimaan);
                                $printBln = $ex[2]." ".$this->data_model->printBln2($ex[1])." ".$ex[0];
                            ?>
                            <tr>
                                <td style="padding:5px;text-align:center;"><?=$no;?>.</td>
                                <td style="padding:5px;"><?=$printBln;?></td>
                                <td style="padding:5px;"><?=$val->nama_benang;?></td>
                                <td style="padding:5px;"><?=$val->kode_karung;?></td>
                                <td style="padding:5px;"><?=$val->jumlah_bale;?> Bale</td>
                                <td style="padding:5px;"><?=$val->netto;?> Kg</td>
                                <td style="padding:5px;"><?=$val->en_ee;?></td>
                                <?php
                                    //mari kita hitung berat total pada karung nya.
                                    $kd = $val->kode_karung;
                                    $total_berat = $this->db->query("SELECT SUM(berat_karung) AS ukr FROM karung_benang WHERE kode_pembelian='$kd'")->row("ukr");
                                    $total_berat2 = round($total_berat,2);
                                    $minus = floatval($val->netto) - floatval($total_berat2);
                                    if($minus == 0){ 
                                        echo "<td style='padding:5px;'>";
                                        echo $val->jumlah_karung;
                                        echo "</td>"; 
                                    } else { 
                                        //$minus_notif="<small style='color:red;>(Berat tidak sesuai)</small>'"; 
                                        echo "<td style='padding:5px;'>";
                                        echo "".$val->jumlah_karung." ";
                                        echo "<small style='color:red;'>(";
                                        echo $total_berat2;
                                        echo " Kg)</small>";
                                        echo "</td>"; 
                                    }
                                ?>
                                <td style="padding:5px;"><a href="<?=base_url('data/pembelian/'.sha1($val->id_penerimaan));?>" style="background:#e89e09;padding: 2px 10px;font-size:12px;text-decoration:none;color:#000;border-radius:5px;">Edit</a></td>
                            </tr>
                            <?php $no++; endforeach; } else { echo "<td colspan='7' style='padding:5px;'>Tidak ada data pembelian benang</td>"; }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>