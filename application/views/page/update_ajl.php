<div class="formdata">
                <!-- <div class="notifikasi-sukses" style="background:#02a82e;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Ini pemberitahuan notifikasi
                </div>
                <div class="notifikasi-gagal" style="background:#de071c;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Ini pemberitahuan notifikasi
                </div> -->
                <?php if($notif=="true"){ ?>
                <div class="notifikasi-sukses" style="background:#02a82e;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Berhasil menyimpan produksi AJL.
                </div>
                <?php } 
                $idbeam = $val['id_beam_sizing'];
                $idid = $val['id_produksi_mesin'];
                $beam_sizing = $this->db->query("SELECT id_beam_sizing,kode_beam FROM v_beam_sizing2 WHERE id_beam_sizing='$idbeam'")->row("kode_beam");
                $tglToday = date('Y-m-d');
                ?>
                <h1>Produksi Mesin AJL</h1>
                <form action="<?=base_url('proses/updatestartmesinajl');?>" method="post" name="fr1">
                <input type="hidden" id="kode_proses" name="kode_proses" value="<?=$kodeproses;?>" required>
                <input type="hidden" id="id_beam_sizing" name="id_beamsizing" value="<?=$idbeam;?>" required>
                <input type="hidden" id="id_prod_mesin" name="id_prod_mesin" value="<?=$idid;?>" required>
                <div class="forminput mt-20">
                    <label for="tgl_prod">Tanggal Produksi</label>
                    <input type="date" class="sm" value="<?=$val['tgl_produksi'];?>" name="tgl_produksi" id="tgl_prod">
                </div>
                <div class="forminput mt-20">
                    <label for="mcid">No MC</label>
                    <input type="text" class="sm" value="<?=$val['no_mesin'];?>" name="no_mesin" id="mcid" required>
                </div>
                
                <div class="forminput mt-20">
                    <label for="autoComplete">Beam Sizing</label>
                    <div class="autoComplete_wrapper">
                        <input id="autoComplete" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off" value="<?=$beam_sizing;?>" name="beamwarping" readonly>
                    </div>
                </div>
               
                <div class="forminput mt-20">
                    <label for="konsid">Konstruksi</label>
                    <input type="text" class="sm" value="<?=$val['konstruksi'];?>" name="konstruksi" id="konsid" required>
                </div>
                <div class="forminput mt-20">
                    <label for="lusiid">Lusi</label>
                    <input type="text" class="sm" value="<?=$val['lusi'];?>" name="lusi" id="lusiid" >
                </div>
                <div class="forminput mt-20">
                    <label for="pakan">Pakan</label>
                    <input type="text" class="sm" value="<?=$val['pakan'];?>" name="pakan" id="pakan" >
                </div>
                <div class="forminput mt-20">
                    <label for="sisir">Sisir</label>
                    <input type="text" class="sm" value="<?=$val['sisir'];?>" name="sisir" id="sisir" >
                </div>
                <div class="forminput mt-20">
                    <label for="pick">Pick</label>
                    <input type="text" class="sm" value="<?=$val['pick'];?>" name="pick" id="pick" >
                </div>
                <div class="forminput mt-20">
                    <label for="lusiid2">Panjang Lusi</label>
                    <input type="text" class="sm" value="<?=$val['pjg_lusi'];?>" name="pjg_lusi" id="lusiid2" >
                </div>
                <div class="forminput mt-20">
                    <label for="sizing">Sizing</label>
                    <input type="text" class="sm" value="<?=$val['sizing'];?>" name="sizing" id="sizing" >
                </div>
                <div class="forminput mt-20">
                    <label for="helai">Jumlah Lusi / Helai</label>
                    <input type="text" class="sm" value="<?=$val['jml_helai'];?>" name="jml_helai" id="helai" >
                </div>
                <hr class="mt-20">
                <h1>Potongan Kain</h1>
                
                <table class="table" border="1" style="width:100%;border-collapse:collapse;margin-top:20px;border:2px solid #ccc;">
                        <thead>
                            <tr style="background:#dae0eb;">
                                <!-- <th style="padding:5px;">No.</th> -->
                                <th style="padding:5px;">No.</th>
                                <th style="padding:5px;">Beam</th>
                                <th style="padding:5px;">Konstruksi</th>
                                <th style="padding:5px;">Tanggal Potong</th>
                                <th style="padding:5px;">Shift</th>
                                <th style="padding:5px;">Ukuran (Mtr)</th>
                                <th style="padding:5px;">Operator</th>
                                <th style="padding:5px;">#</th>
                            </tr>
                        </thead>
                        <tbody id="bodytablePotongan">
                            <tr>
                                <td colspan="8" style="padding:5px;">Loading...</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td style="padding:5px;"></td>
                                <td style="padding:5px;">
                                    <input type="text" placeholder="Beam" style="padding:5px;outline:none;border:1px solid #ccc;border-radius:3px;" id="id_beams">
                                </td>
                                <td style="padding:5px;">
                                    <input type="text" placeholder="Konstruksi" style="padding:5px;outline:none;border:1px solid #ccc;border-radius:3px;" id="id_kons">
                                </td>
                                <td style="padding:5px;">
                                    <input type="date" value="<?=$tglToday;?>" style="padding:5px;outline:none;border:1px solid #ccc;border-radius:3px;" id="id_tglptg">
                                </td>
                                <td style="padding:5px;">
                                    <select style="padding:5px;outline:none;border:1px solid #ccc;border-radius:3px;" id="id_shift">
                                        <option value="">Pilih Shift</option> 
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                    </select>
                                </td>
                                <td style="padding:5px;">
                                    <input type="number" placeholder="Ukuran Potong" style="padding:5px;outline:none;border:1px solid #ccc;border-radius:3px;" id="id_ukr">
                                </td>
                                <td style="padding:5px;">
                                    <input type="text" placeholder="Operator" style="padding:5px;outline:none;border:1px solid #ccc;border-radius:3px;" id="id_opt">
                                </td>
                                <td style="padding:5px;">
                                    <a href="#" style="padding:4px 10px;background:#4287f5;color:#fff;text-decoration:none;border-radius:5px;" id="potongKain">+ Simpan</a>
                                </td>
                            </tr>
                        </tfoot>
                </table>
                <hr class="mt-20">
                <h1>Input Pemaikaian Pakan</h1>
                <div class="forminput mt-20">
                    <label for="setCones">Jenis Cones</label>
                    <select name="setCones" id="setCones" class="sm" style="padding:7px;border-radius:4px;border:1px solid #ccc;outline:none;">
                        <option value="">-Pilih-</option>
                        <option value="new">Cones Baru</option>
                        <option value="old">Cones Bekas Warping</option>
                    </select>
                </div>
                <div class="forminput mt-20" id="conesBaruBekas1" style="display:none;">
                    <label for="cdCones29old">Kode Cones</label>
                    <input type="text" list="cdConess" class="sm" placeholder="Masukan Kode Cones" name="cd_cones" id="cdCones29old">
                </div>
                <datalist id="cdConess">
                    <?php $ls = $this->db->query("SELECT * FROM baku_warping GROUP BY id_produksi_warping"); 
                        foreach($ls->result() as $g){
                            echo '<option value="'.$g->id_produksi_warping.'">';
                        }
                    ?>
                </datalist>
                <div class="forminput mt-20" id="conesBaruField1" style="display:none;">
                    <label for="cdCones21new">Kode Karung</label>
                    <input type="text" list="cdKarungs" class="sm" placeholder="Masukan Kode Karung" name="cdCones21new" id="cdCones21new">
                </div>
                <datalist id="cdKarungs">
                    <?php $ls = $this->db->query("SELECT * FROM karung_benang WHERE status_karung='masih'"); 
                        foreach($ls->result() as $g){
                            echo '<option value="'.$g->kode_karung.'">';
                        }
                    ?>
                </datalist>
                <div class="forminput mt-20">
                    <label for="jmlNewCones">Jumlah Cones</label>
                    <input type="text" class="sm" placeholder="Masukan Jumlah Cones" name="jmlNewCones" id="jmlNewCones">
                </div>
                <div class="forminput mt-20">
                    <label for="brtCones">Berat Cones</label>
                    <input type="text" class="sm" placeholder="Masukan Berat Cones" name="brt_cones" id="brtCones">
                </div>
                <div class="forminput mt-20">
                    <label for="brtConessm">&nbsp;</label>
                    <a href="javascript:;" id="iptPakanSaved" style="padding:4px 10px;background:#32a852;color:#fff;text-decoration:none;border-radius:5px;">Input Pakan</a>
                </div>
                <table class="table" border="1" style="width:100%;border-collapse:collapse;margin-top:20px;border:2px solid #ccc;">
                        <thead>
                            <tr style="background:#dae0eb;">
                                <!-- <th style="padding:5px;">No.</th> -->
                                <th style="padding:5px;">No.</th>
                                <th style="padding:5px;">Jenis Cones</th>
                                <th style="padding:5px;">Kode</th>
                                <th style="padding:5px;">Jumlah Cones</th>
                                <th style="padding:5px;">Total Berat (Kg)</th>
                                <th style="padding:5px;">#</th>
                            </tr>
                        </thead>
                        <tbody id="bodytableBakuAjl">
                            <tr>
                                <td colspan="7" style="padding:5px;">Loading...</td>
                            </tr>
                        </tbody>
                </table>
                <div class="karungan" id="meter_perbeam">
                    <!-- <div class="box-karung">
                        <span>Beam 01</span>
                        <label for="tes">Meter per Beam</label>
                        <input type="text" id="tes" placeholder="Masukan ukuran">
                    </div>
                    <div class="box-karung">
                        <span>Beam 01</span>
                        <label for="tes">Meter per Beam</label>
                        <input type="text" id="tes" placeholder="Masukan ukuran">
                    </div> -->
                </div>
                <p>&nbsp;</p>
                <button class="sbmit" type="submit" id="btnSimpanWarping">Simpan</button>
                <a href="javascript:;" style="text-decoration:none;background:green;padding:3px 10px;border-radius:3px;color:#fff;margin-left:10px;" onclick="tanyakan('<?=$id_produksi_mesin;?>')">Selesai Produksi</a>
                </form>
               
            </div>

        </div>
    </div>