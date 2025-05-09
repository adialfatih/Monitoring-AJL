<?php 
$dtlis2 = $this->data_model->get_byid('produksi_warping', ['sha1(id_produksi_warping)'=>$dataid]);
$dtlis = $dtlis2->row_array();
$ex = explode('-', $dtlis['tgl_produksi']);
$printBln = $ex[2]."-".$this->data_model->printBln2($ex[1])."-".$ex[0];
$jmlBeam = $dtlis['jml_beam'];
$id_warping = $dtlis['id_produksi_warping'];
?>
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
                <?php if($dtlis2->num_rows() == 0){ ?>
                <div class="notifikasi-sukses" style="background:#de071c;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Data tidak ditemukan.
                </div>
                <?php } ?>
                <h1>Update Bahan Baku</h1>
                <input type="hidden" id="kode_proses" name="kode_proses" value="<?=$kodeproses;?>">
                <div class="forminput mt-20">
                    <label for="jumlahss">ID Produksi</label>
                    <input type="text" class="sm" value="<?=$id_warping;?>" id="idwarping" readonly>
                </div>
                <div class="forminput mt-20">
                    <label for="tgl_prod">Tanggal Produksi</label>
                    <input type="text" value="<?=$printBln;?>" readonly>
                </div>
                <div class="forminput mt-20">
                    <label for="tgl_prod">Jenis Mesin</label>
                    <input type="text" value="<?=$dtlis['jenis_mesin'];?>" readonly>
                </div>
                <div class="forminput mt-20">
                    <label for="creel">Jumlah Creel</label>
                    <input type="text" class="sm" value="<?=$dtlis['jml_creel'];?>" id="jmlCreel" readonly>
                </div>
                <hr class="mt-20">
                <p class="mt-20">Masukan kode karung dan jumlah Cones yang akan di pakai untuk bahan baku.</p>
                <div class="forminput mt-20">
                    <label for="autoComplete">Kode Karung</label>
                    <div class="autoComplete_wrapper">
                        <input id="autoComplete" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off" name="nama_benang" value="<?=$cekhasil['jenis_mesin'];?>" onchange="changeKodeKarung(this.value)" required>
                    </div>
                </div>
                <input type="hidden" id="kode_karung" value="0">
                <div class="forminput mt-20">
                    <label for="creel">Jumlah Pemakaian Cones</label>
                    <input type="text" class="sm" value="0" name="kones" id="jml_kones">
                    <span style="padding:5px 10px;border-radius:5px;font-size:12px;cursor:pointer;margin-left:20px;background:#0eab38;color:#fff;" onclick="submitCones();">Submit</span>
                </div>
                <table class="table" border="1" style="width:100%;border-collapse:collapse;margin-top:20px;border:2px solid #ccc;">
                        <thead>
                            <tr style="background:#dae0eb;">
                                <!-- <th style="padding:5px;">No.</th> -->
                                <th style="padding:5px;">No.</th>
                                <th style="padding:5px;">Nama Benang</th>
                                <th style="padding:5px;">Kode Karung</th>
                                <th style="padding:5px;">Jumlah Cones</th>
                                <th style="padding:5px;">Total Berat Cones</th>
                                <th style="padding:5px;">#</th>
                            </tr>
                        </thead>
                        <tbody id="bodytable1">
                            <tr>
                                <td colspan="5" style="padding:5px;">Loading...</td>
                            </tr>
                        </tbody>
                </table>
                <?php
                    $bakuajl = $this->db->query("SELECT * FROM baku_ajl WHERE kode = '$id_warping' AND jns_cones='old'");
                    if($bakuajl->num_rows() > 0){
                ?>
                <hr class="mt-20">
                <p class="mt-20">Tabel Pemakaian Sisa Cones</p>
                <table class="table" border="1" style="width:100%;border-collapse:collapse;margin-top:20px;border:2px solid #ccc;">
                        <thead>
                            <tr style="background:#dae0eb;">
                                <!-- <th style="padding:5px;">No.</th> -->
                                <th style="padding:5px;">No.</th>
                                <th style="padding:5px;">Nomor Mesin</th>
                                <th style="padding:5px;">Tanggal Produksi</th>
                                <th style="padding:5px;">Jumlah Cones</th>
                                <th style="padding:5px;">Total Berat Cones (Kg)</th>
                                <th style="padding:5px;">Waktu Pemakaian</th>
                                <th style="padding:5px;">#</th>
                            </tr>
                        </thead>
                        <tbody id="bodytable2">
                            <?php $nk=1; 
                            $all_jml_cones = 0;
                            $all_brt_cones = 0;
                            foreach($bakuajl->result() as $nol): 
                            $id_prodajl = $nol->id_produksi_mesin;
                            $dt_nk = $this->data_model->get_byid('produksi_mesin_ajl', ['id_produksi_mesin'=>$id_prodajl])->row_array();
                            $ex5 = explode('-', $dt_nk['tgl_produksi']);
                            $printTgl5 = $ex5[2]."-".$this->data_model->printBln2($ex5[1])."-".$ex5[0];
                            $ex6 = explode(' ', $nol->tm_stmp);
                            $ex6_tgl = explode('-', $ex6[0]);
                            ?>
                            <tr>
                                <td style="padding:5px;text-align:center;"><?=$nk;?></td>
                                <td style="padding:5px;text-align:center;"><?=$dt_nk['no_mesin'];?></td>
                                <td style="padding:5px;text-align:center;"><?=$printTgl5;?></td>
                                <td style="padding:5px;text-align:center;"><?=$nol->jumlah_cones;?></td>
                                <td style="padding:5px;text-align:center;"><?=$nol->berat_cones;?></td>
                                <td style="padding:5px;text-align:center;"><?=$ex6_tgl[2]."-".$this->data_model->printBln2($ex6_tgl[1])."-".$ex6_tgl[0]." ".$ex6[1];?></td>
                            </tr>
                            <?php 
                            $all_jml_cones+=$nol->jumlah_cones;
                            $all_brt_cones+=$nol->berat_cones;
                            $nk++; endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" style="padding:5px;text-align:center;">Total</th>
                                <th style="padding:5px;text-align:center;"><?=$all_jml_cones;?></th>
                                <th style="padding:5px;text-align:center;"><?=$all_brt_cones;?></th>
                                <th colspan="2" style="padding:5px;text-align:center;"></th>
                            </tr>
                        </tfoot>
                </table>
                <?php } ?>
                <p>&nbsp;</p>
                <!-- tes -->
                <div style="display:flex;align-items:center;">
                <button class="sbmit" type="submit" id="btnSimpanWarping">Simpan</button>
                <button type="button" id="btnSimpanWarping23" style="background:#eb0915;outline:none;border:1px solid #fff;color:#ffff;padding:10px;border-radius:5px;cursor:pointer;margin-left:10px;margin-top:5px;" onclick="history.back()">Batal</button>
                
                
            </div>

        </div>
    </div>