<div class="formdata">
                <!-- <div class="notifikasi-sukses" style="background:#02a82e;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Ini pemberitahuan notifikasi
                </div>
                <div class="notifikasi-gagal" style="background:#de071c;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Ini pemberitahuan notifikasi
                </div> -->
                <?php if($notif=="true"){ ?>
                <div class="notifikasi-sukses" style="background:#02a82e;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Berhasil menyimpan data operator produksi.
                </div>
                <?php } ?>
                <h1>Data Operator Produksi</h1>
                <form action="<?=base_url('proses/addoper');?>" method="post" name="fr1">
                <input type="hidden" id="idop" name="idop" value="0">
                <div class="forminput mt-20">
                    <label for="opnm">Nama Lengkap Operator</label>
                    <input type="text" placeholder="Masukan Nama Operator" name="namal" id="opnm" required>
                </div>
                <div class="forminput mt-20">
                    <label for="psnm">Username</label>
                    <input type="text" placeholder="Masukan Username untuk login operator" name="user" id="psnm" required>
                </div>
                <div class="forminput mt-20">
                    <label for="oppas">Password Login</label>
                    <input type="text" placeholder="Masukan Password Operator" name="pass" id="oppas" required>
                </div>
                
                <p>&nbsp;</p>
                <button class="sbmit" type="submit" id="btnSimpanWarping">Simpan</button>
                </form>
                <div class="table-responsive" style="width:100%;overflow-x:auto;">
                    <table class="table" border="1" style="width:100%;border-collapse:collapse;margin-top:20px;border:2px solid #ccc;">
                        <thead>
                            <tr style="background:#dae0eb;">
                                <!-- <th style="padding:5px;">No.</th> -->
                                <th style="padding:5px;">No.</th>
                                <th style="padding:5px;">Nama Operator</th>
                                <th style="padding:5px;">Username</th>
                                <th style="padding:5px;">Password</th>
                                <th style="padding:5px;">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $dtop = $this->data_model->get_record('operator_mesin'); 
                            if($dtop->num_rows() > 0 ){
                                foreach($dtop->result() as $n => $val){
                                   ?>
                                   <tr>
                                    <td style="padding:5px;text-align:center;"><?=$n+1;?></td>
                                    <td style="padding:5px;text-align:center;"><?=$val->nama_lengkap;?></td>
                                    <td style="padding:5px;text-align:center;"><?=$val->username;?></td>
                                    <td style="padding:5px;text-align:center;"><?=$val->password;?></td>
                                    <td style="padding:5px;text-align:center;">
                                        <a href="javascript:;" onclick="updlop('<?=$val->id_op;?>','<?=$val->password;?>','<?=$val->nama_lengkap;?>','<?=$val->username;?>')" style="padding:2px 10px;background:#32a852;color:#fff;text-decoration:none;border-radius:2px;font-size:11px;">Edit</a>
                                        <a href="javascript:;" onclick="delop('<?=$val->id_op;?>')" style="padding:2px 10px;background:red;color:#fff;text-decoration:none;border-radius:2px;font-size:11px;">Hapus</a>
                                    </td>
                                   </tr>
                                   <?php
                                }
                            } else {
                            ?>
                            <tr>
                                <td style="padding:5px;" colspan="5">Tidak ada data</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>