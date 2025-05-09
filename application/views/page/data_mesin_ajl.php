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
                $cek_mesin = $this->db->query("SELECT * FROM table_mesin ORDER BY no_mesin ");
                $cek_data = $this->data_model->get_byid('produksi_mesin_ajl',['proses'=>'onproses']);
                ?>
                
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <h1>Produksi Mesin AJL</h1>
                    <a href="<?=base_url('produksi-ajl');?>">
                    <button class="sbmit" id="idSubmitForm" style="width:200px;">Input Produksi</button></a>
                </div>
                <div style="width:100%">
                    <table style="width:100%;border-collapse:collapse;margin-top:30px;border:1px solid #fff;" border="1">
                        <thead>
                            <tr style="background:#348be0;color:#fff;">
                                <td style="padding:5px;text-align:center;">No.</td>
                                <td style="padding:5px;text-align:center;">No MC</td>
                                <td style="padding:5px;text-align:center;">Konstruksi</td>
                                <td style="padding:5px;text-align:center;">OKA</td>
                                <td style="padding:5px;text-align:center;">Beam</td>
                                <td style="padding:5px;text-align:center;">Lusi</td>
                                <td style="padding:5px;text-align:center;">Sisa Lusi</td>
                                <td style="padding:5px;text-align:center;">Naik Beam</td>
                                <td style="padding:5px;text-align:center;">Estimasi Habis Beam</td>
                                <td style="padding:5px;text-align:center;">Status</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $ar = ["01"=>"Jan", "02"=>"Feb","03"=>"Mar","04"=>"Apr","05"=>"Mei","06"=>"Jun","07"=>"Jul","08"=>"Ags","09"=>"Sep","10"=>"Okt","11"=>"Nov","12"=>"Des",];
                            foreach ($cek_mesin->result() as $no => $value):
                                $mc = $value->no_mesin;
                                $ajl = $this->db->query("SELECT * FROM produksi_mesin_ajl WHERE no_mesin='$mc' ORDER BY id_produksi_mesin DESC LIMIT 1")->row_array();
                                $id_ajl = $ajl['id_produksi_mesin'];
                                $idbeam = $ajl['id_beam_sizing'];
                                $siz = $this->data_model->get_byid('v_beam_sizing2', ['id_beam_sizing'=>$idbeam])->row_array();
                                $beamsizing = $siz['kode_beam'];
                                $oka = $siz['oka'];
                                $status = $ajl['proses'];
                                $start_mesin = $this->data_model->get_byid('produksi_mesin_ajl_rwyt', ['id_produksi_mesin'=>$id_ajl, 'jenis_riwayat'=>'Start Mesin']);
                                if($start_mesin->num_rows() == 1){
                                    $k = explode(' ', $start_mesin->row("timestamprwyt"));
                                    $t = explode('-', $k[0]);
                                    $printTgl = $t[2]." ".$ar[$t[1]]." ".$t[0];
                                    $w = explode(':', $k[1]);
                                    $prinJam = $w[0].":".$w[1];
                                    $naik_beam = $printTgl.", ".$prinJam;
                                } else {
                                    $naik_beam = "<span style='font-size:12px;color:red;'>Tidak Terdeteksi</span>";
                                }
                                $potongan_kain = $this->data_model->get_byid('produksi_mesin_ajl_potongan', ['id_produksi_mesin'=>$id_ajl]);
                                if($potongan_kain->num_rows() > 0){
                                    $_nilaipot = 0;
                                    foreach($potongan_kain->result() as $vp){
                                        $_nilaipot+= $vp->ukuran_meter;
                                        
                                    }
                                    $potn = "ada";
                                    $sisa_lusi = $ajl['pjg_lusi'] - $_nilaipot;
                                } else {
                                    $potn = "tidak ada";
                                    $sisa_lusi = $ajl['pjg_lusi'];
                                }
                                $produksi_hari_itu = $this->db->query("SELECT id_loom,no_mesin,produksi FROM loom_counter WHERE no_mesin='$mc' ORDER BY id_loom DESC LIMIT 1")->row("produksi");
                                $pjg_lusi_10persen = ($sisa_lusi * 10) / 100;
                                $rumus1 = $sisa_lusi - $pjg_lusi_10persen;
                                $rumus2 = $rumus1 - $produksi_hari_itu;
                                $rumus3 = $rumus2 / 245;
                            ?>
                            <tr class="trtrbgoke">
                                <td style="padding:5px;text-align:center;"><?=$no+1;?></td>
                                <?php if($status == "onproses"){ ?>
                                <td style="padding:5px;text-align:center;"><a href="<?=base_url('data/produksi/mesin/'.$id_ajl);?>" style="text-decoration:none;color:#425466;"><?=strtoupper($mc);?></a></td>
                                <td style="padding:5px;text-align:center;"><?=$ajl['konstruksi'];?></td>
                                <td style="padding:5px;text-align:center;"><?=$oka;?></td>
                                <td style="padding:5px;text-align:center;"><?=$beamsizing;?></td>
                                <td style="padding:5px;text-align:center;"><?=number_format($ajl['pjg_lusi']);?></td>
                                <td style="padding:5px;text-align:center;">
                                    <?php
                                    if($potn == "ada"){
                                        //echo $sisa_lusi;
                                        if($sisa_lusi < 0){
                                            echo "Error";
                                        } else {
                                            echo number_format($sisa_lusi);
                                        }
                                    } else {
                                        echo number_format($ajl['pjg_lusi']);
                                    }
                                    ?>
                                </td>
                                <td style="padding:5px;text-align:center;"><?=$naik_beam;?></td>
                                <td style="padding:5px;text-align:center;"><?=round($rumus3);?> Hari</td>
                                <?php } else { ?>
                                    <td style="padding:5px;text-align:center;"><a href="<?=base_url('data/produksi/mesin/'.$id_ajl);?>" style="text-decoration:none;color:red;"><?=strtoupper($mc);?></a></td>
                                    <td style="padding:5px;font-size:13px;color:red;" colspan="7">
                                         
                                    </td>
                                <?php } ?>
                                <td style="padding:7px;text-align:center;">
                                    <?php
                                    
                                        if($status == "onproses"){
                                            echo "<span style='font-size:11px;background:green;color:#fff;padding:2px 10px;border-radius:3px;'>ON</span>";
                                        } else {
                                            echo "<span style='font-size:11px;background:red;color:#fff;padding:2px 10px;border-radius:3px;'>OFF</span>";
                                        }
                                    ?>
                                </td>
                            </tr>
                            <?php
                            endforeach;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>