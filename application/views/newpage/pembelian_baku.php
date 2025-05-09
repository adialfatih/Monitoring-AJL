<div class="formdata">
                <!-- <div class="notifikasi-sukses" style="background:#02a82e;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Ini pemberitahuan notifikasi
                </div>
                <div class="notifikasi-gagal" style="background:#de071c;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Ini pemberitahuan notifikasi
                </div> -->
                <?php if($notif=="true"){ ?>
                <div class="notifikasi-sukses" style="background:#02a82e;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Berhasil menyimpan pembelian bahan baku.
                </div>
                <?php } ?>
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <h1>Data Pembelian Bahan Baku</h1>
                    <a href="<?=base_url('pembelian-baku');?>">
                    <button class="sbmit" id="idSubmitForm" style="width:200px;">Input Pembelian</button></a>
                </div>
                <div class="table-responsive" style="width:100%;overflow-x:auto;">
                    <table class="table" border="1" style="width:100%;border-collapse:collapse;margin-top:20px;border:2px solid #ccc;">
                        <thead>
                            <tr style="background:#dae0eb;">
                                <th style="padding:5px;">No.</th>
                                <th style="padding:5px;">Tanggal Pembelian</th>
                                <th style="padding:5px;">Jenis</th>
                                <th style="padding:5px;">Nama Barang</th>
                                <th style="padding:5px;">Jumlah</th>
                                <th style="padding:5px;">Supplier</th>
                                <th style="padding:5px;">Surat Jalan</th>
                                <th style="padding:5px;">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $cek = $this->db->query("SELECT * FROM data_baku ORDER by tgl_beli DESC");
                                if($cek->num_rows() > 0){
                                    $no=1;
                                    foreach($cek->result() as $val):
                                    $x = explode('-', $val->tgl_beli);
                                    $printTgl = $x[2]." ".$this->data_model->printBln2($x[1])." ".$x[0];
                                    echo "<tr id='trid".$val->id_pembaku."'>";
                                    echo "<td style='padding:5px;text-align:center;'>".$no."</td>";
                                    echo "<td style='padding:5px;'>".$printTgl."</td>";
                                    echo "<td style='padding:5px;'>".$val->jenis_baku."</td>";
                                    echo "<td style='padding:5px;'>".$val->nama_baku."</td>";
                                    echo "<td style='padding:5px;'>".$val->jumlah_beli." ".$val->satuan."</td>";
                                    echo "<td style='padding:5px;'>".$val->supplier."</td>";
                                    echo "<td style='padding:5px;'>".$val->sj."</td>";
                                    ?>
                                    <td style="padding:5px;text-align:center;">
                                        <a href="#" style="text-decoration:none;background:#de2c1f;color:#fff;font-size:11px;padding:3px 10px;border-radius:3px;" onclick="hps('<?=$val->id_pembaku;?>','<?=$val->nama_baku;?>','<?=$printTgl;?>')">Hapus</a>
                                    </td>
                                    <?php
                                    echo "</tr>";
                                    $no++;
                                    endforeach;
                                } else {
                                    echo "<tr>";
                                    echo "<td colspan='7' style='padding:5px;'>Belum ada data Pembelian Bahan Baku</td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>