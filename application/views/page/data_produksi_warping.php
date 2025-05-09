<div class="formdata">
                <!-- <div class="notifikasi-sukses" style="background:#02a82e;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Ini pemberitahuan notifikasi
                </div>
                <div class="notifikasi-gagal" style="background:#de071c;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Ini pemberitahuan notifikasi
                </div> -->
                <?php if($notif=="true"){ ?>
                <div class="notifikasi-sukses" style="background:#02a82e;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Berhasil menyimpan produksi warping.
                </div>
                <?php } 
                $mm=date('m');
                ?>
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <div style="display:flex;flex-direction:column;">
                        <h1>Data Produksi Warping</h1>
                        <small>Menampilkan data produksi warping.</small>
                    </div>
                    <div style="display:flex;align-items:center;">
                        <a href="<?=base_url('warping-pershift/'.$mm);?>">
                        <button id="idSubmitForm3" style="width:100px;outline:none;margin-right:10px;padding:5px;cursor:pointer;">Input Pershift</button></a>
                        <a href="<?=base_url('warping');?>">
                        <button class="sbmit" id="idSubmitForm2" style="width:200px;">Input Produksi</button></a>
                    </div>
                </div>
                <div class="table-responsive" style="width:100%;overflow-x:auto;">
                    <table class="table" border="1" style="width:100%;border-collapse:collapse;margin-top:20px;border:2px solid #ccc;">
                        <thead>
                            <tr style="background:#dae0eb;">
                                <!-- <th style="padding:5px;">No.</th> -->
                                <th style="padding:5px;">ID</th>
                                <th style="padding:5px;">Tanggal Produksi</th>
                                <th style="padding:5px;">Jenis Mesin</th>
                                <th style="padding:5px;">Creel</th>
                                <th style="padding:5px;">Jumlah Beam</th>
                                <th style="padding:5px;">Total Panjang (Mtr)</th>
                                <th style="padding:5px;">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $dataquery = $this->db->query("SELECT * FROM produksi_warping WHERE jenis_mesin!='null' AND tgl_produksi!='null' ORDER BY tgl_produksi DESC");
                            if($dataquery->num_rows() > 0){
                                $no=1;
                                foreach($dataquery->result() as $val):
                                $ex = explode('-', $val->tgl_produksi);
                                $printBln = $ex[2]." ".$this->data_model->printBln2($ex[1])." ".$ex[0];
                                $idwarping = $val->id_produksi_warping;
                                $ttl_pjg = $this->db->query("SELECT SUM(ukuran_panjang) AS ukr FROM beam_warping WHERE id_produksi_warping='$idwarping'")->row("ukr");
                            ?>
                            <tr>
                                <!-- <td style="padding:5px;text-align:center;"><=$no;?>.</td> -->
                                <td style="padding:5px;text-align:center;background:#dae0eb;"><strong><?=$val->id_produksi_warping;?></strong></td>
                                <td style="padding:5px;"><?=$printBln;?></td>
                                <td style="padding:5px;"><?=$val->jenis_mesin;?></td>
                                <td style="padding:5px;"><?=$val->jml_creel;?></td>
                                <td style="padding:5px;"><?=$val->jml_beam;?></td>
                                <td style="padding:5px;"><?=number_format($ttl_pjg,0,',','.');?></td>
                                
                                <td style="padding:5px;"><a href="<?=base_url('data/warping/'.$val->kode_proses);?>" style="background:#e89e09;padding: 2px 10px;font-size:12px;text-decoration:none;color:#000;border-radius:5px;">Edit</a></td>
                            </tr>
                            <?php $no++; endforeach; } else { echo "<td colspan='7' style='padding:5px;'>Tidak ada data pembelian benang</td>"; }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>