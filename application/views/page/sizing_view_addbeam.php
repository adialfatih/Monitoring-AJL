<div class="formdata">
<?php 
$dtlis = $this->data_model->get_byid('produksi_sizing', ['kode_proses'=>$kodeproses])->row_array();
$ex = explode('-', $dtlis['tgl_produksi']);
$printBln = $ex[2]."-".$this->data_model->printBln2($ex[1])."-".$ex[0];
$jmlBeam = $dtlis['jumlah_beam_sizing'];
$id_sizing = $dtlis['id_sizing'];
$cek_beamsiz = $this->data_model->get_byid('beam_sizing', ['id_sizing'=>$id_sizing]);
?>
                <!-- <div class="notifikasi-sukses" style="background:#02a82e;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Ini pemberitahuan notifikasi
                </div>
                <div class="notifikasi-gagal" style="background:#de071c;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Ini pemberitahuan notifikasi
                </div> -->
                <?php if($notif=="true"){ ?>
                <div class="notifikasi-sukses" style="background:#02a82e;color:#fff;padding:15px;border-radius:5px;font-size:14px;">
                    Berhasil menyimpan proses Sizing.
                </div>
                <?php } ?>
                <form method="post" action="<?=base_url('proses/savedbeamsizing');?>" name="fr1">
                <h1>Input Produksi Sizing Tanggal <?=$printBln;?></h1>
                <input type="hidden" id="kode_proses" name="kode_proses" value="<?=$kodeproses;?>">
                <input type="hidden" id="id_sizing" name="id_sizing" value="<?=$id_sizing;?>">
                <datalist id="listkons">
            <option value="SM02">
            <option value="SM03">
            <option value="SM04">
            <option value="SM05A">
            <option value="SM05B">
            <option value="SM05C">
            <option value="SM05D">
            <option value="SM05F">
            <option value="SM05G">
            <option value="SM05H">
            <option value="SM13">
            <option value="SM13A">
            <option value="SM13B">
            <option value="SM13C">
            <option value="SM13C">
            <option value="SM15">
            <option value="SM15A">
            <option value="SM15B">
            <option value="SM15C">
            <option value="SM15D">
            <option value="SM15H">
            <option value="SM15J">
            <option value="SM15K">
            <option value="SM15L">
            <option value="SM16">
            <option value="SM17">
            <option value="SM17A">
            <option value="SM18">
            <option value="SM16A">
            <option value="SM16B">
            <option value="SM19">
            <option value="SM05K">
            <option value="SM13D">
            <option value="SM09A">
            <option value="SM03A">
            <option value="SM05J">
            <option value="SM20">
            <option value="SM13E">
            <option value="SM05L">
            <option value="SM05L_RJS">
            <option value="SM13F">
            <option value="SM13G">
            <option value="SM13H">
            <option value="SM13J">
            <option value="SM05M">
            <option value="SM17B">
            <option value="SM05B_RJS">
            <option value="SM13K">
            <option value="SM05GS">
            <option value="SM13L">
        </datalist>
                <!-- pembuatan list nama kode warping -->
                <?php 
                $listnama = $this->db->query("SELECT * FROM beam_sizing GROUP BY kode_beam");
                ?>
                    <datalist id="nps2">
                        <?php foreach($listnama->result() as $ls): ?>
                        <option value="<?=$ls->kode_beam;?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                <!-- end list nama -->
                <?php 
                if($cek_beamsiz->num_rows() == 0){
                $n=1; for ($i=0; $i <$jmlBeam ; $i++) { ?>
                <h1>Beam Sizing <?=$n;?></h1>
                <div class="forminput mt-20">
                    <label for="np<?=$n;?>">Nomor/Kode</label>
                    <input type="text" list="nps2" class="sm" placeholder="Masukan Kode Beam" name="kodebeam[]" id="np<?=$n;?>" required>
                    
                </div>
                <div class="forminput mt-10">
                    <label for="lssd<?=$n;?>">Konstruksi</label>
                    <input type="text" list="listkons" class="sm" placeholder="Masukan Kode Konstruksi" name="konstr[]" id="lssd<?=$n;?>" required>
                    
                </div>
                <div class="forminput mt-10">
                    <label for="np2-<?=$n;?>">Ukuran/Panjang</label>
                    <input type="text" class="sm" placeholder="Masukan Panjang Beam" name="pjgbeam[]" id="np2-<?=$n;?>" required>
                </div>
                <div class="forminput mt-10">
                    <label for="draf2-<?=$n;?>">Draft</label>
                    <input type="text" class="sm" placeholder="Masukan Draft" name="draft[]" id="draf2-<?=$n;?>" required>
                </div>
                <?php $n++; } 
                } else {
                    $n=1;
                    foreach($cek_beamsiz->result() as $val){
                ?>
                <h1>Beam Sizing <?=$n;?></h1>
                <div class="forminput mt-20">
                    <label for="np<?=$n;?>">Nomor/Kode</label>
                    <input type="text" list="nps2" class="sm" value="<?=$val->kode_beam;?>" name="kodebeam[]" id="np<?=$n;?>" required>
                    
                </div>
                <div class="forminput mt-10">
                    <label for="lssd<?=$n;?>">Konstruksi</label>
                    <input type="text" list="listkons" class="sm" placeholder="Masukan Kode Konstruksi" name="konstr[]" id="lssd<?=$n;?>" required>
                    
                </div>
                <div class="forminput mt-10">
                    <label for="np2-<?=$n;?>">Ukuran/Panjang</label>
                    <input type="text" class="sm" value="<?=$val->ukuran_panjang;?>" name="pjgbeam[]" id="np2-<?=$n;?>" required>
                </div>
                <div class="forminput mt-10">
                    <label for="draf2-<?=$n;?>">Draft</label>
                    <input type="text" class="sm" value="<?=$val->draft;?>" name="draft[]" id="draf2-<?=$n;?>" required>
                </div>
                <?php $n++;
                    }
                }
                ?>
                <!-- <div class="forminput mt-20">
                    <label for="mtr_beam">Meter per Beam</label>
                    <input type="text" class="sm" placeholder="Masukan Panjang Beam" name="mtr_beam" id="mtr_beam" inputmode="numeric">
                </div> -->
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
                </form>
            </div>

        </div>
    </div>