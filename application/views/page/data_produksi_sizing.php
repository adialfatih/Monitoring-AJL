<div class="formdata">
                <!-- <div class="notifikasi-sukses" style="background:#02a82e;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Ini pemberitahuan notifikasi
                </div>
                <div class="notifikasi-gagal" style="background:#de071c;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Ini pemberitahuan notifikasi
                </div> -->
                <?php if($notif=="true"){ ?>
                <div class="notifikasi-sukses" style="background:#02a82e;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Berhasil menyimpan produksi Sizing.
                </div>
                <?php } 
                $mm = date('m');
                ?>
                
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <div style="display:flex;flex-direction:column;">
                        <h1>Data Produksi Sizing</h1>
                        <small>Menampilkan data produksi Sizing.</small>
                    </div>
                    <div style="display:flex;align-items:center;">
                        <a href="<?=base_url('sizing-pershift/'.$mm);?>">
                        <button id="idSubmitForm3" style="width:100px;outline:none;margin-right:10px;padding:5px;cursor:pointer;">Input Pershift</button></a>
                        <a href="<?=base_url('sizing');?>">
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
                                <th style="padding:5px;">OKA</th>
                                <th style="padding:5px;">Konstruksi</th>
                                <th style="padding:5px;">Beam Sizing</th>
                                <th style="padding:5px;">Total Panjang (Mtr)</th>
                                <th style="padding:5px;">Total Draft</th>
                                <th style="padding:5px;">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $dataquery = $this->db->query("SELECT * FROM produksi_sizing WHERE tgl_produksi!='0000-00-00' AND oka!='null' AND konstruksi!='null' ORDER BY tgl_produksi DESC");
                            if($dataquery->num_rows() > 0){
                                $no=1;
                                foreach($dataquery->result() as $val):
                                $ex = explode('-', $val->tgl_produksi);
                                $printBln = $ex[2]." ".$this->data_model->printBln2($ex[1])." ".$ex[0];
                                $idsizing = $val->id_sizing;
                                $ttl_pjg = $this->db->query("SELECT SUM(ukuran_panjang) AS ukr FROM beam_sizing WHERE id_sizing='$idsizing'")->row("ukr");
                                $ttl_draft = $this->db->query("SELECT SUM(draft) AS ukr FROM beam_sizing WHERE id_sizing='$idsizing'")->row("ukr");
                            ?>
                            <tr>
                                <!-- <td style="padding:5px;text-align:center;"><=$no;?>.</td> -->
                                <td style="padding:5px;text-align:center;background:#dae0eb;"><strong><?=$idsizing;?></strong></td>
                                <td style="padding:5px;"><?=$printBln;?></td>
                                <td style="padding:5px;"><?=$val->oka;?></td>
                                <td style="padding:5px;"><?=$val->konstruksi;?></td>
                                <td style="padding:5px;"><?=$val->jumlah_beam_sizing;?></td>
                                <td style="padding:5px;"><?=number_format($ttl_pjg,0,',','.');?></td>
                                <td style="padding:5px;"><?=number_format($ttl_draft,0,',','.');?></td>
                                
                                <td style="padding:5px;"><a href="<?=base_url('data/sizing/'.sha1($idsizing));?>" style="background:#e89e09;padding: 2px 10px;font-size:12px;text-decoration:none;color:#000;border-radius:5px;">Edit</a></td>
                            </tr>
                            <?php $no++; endforeach; } else { echo "<td colspan='7' style='padding:5px;'>Tidak ada data produksi</td>"; }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>