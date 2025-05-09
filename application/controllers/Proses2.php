<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proses2 extends CI_Controller
{

function __construct()
{
    parent::__construct();
    $this->load->model('data_model');
    date_default_timezone_set("Asia/Jakarta");
}

    function index(){
        echo "Not Index...";
    }
    function addoptmsn(){
        $group = $this->input->post('group');
        $op = $this->input->post('nmopt');
        $reli = $this->input->post('reliver');
        $this->data_model->saved('operator_produksi', [
            'nama_operator' => $op,
            'grub' => $group,
            'reliver' => $reli,
        ]);
        echo "Oke";
    } //end

    function deloptmsn(){
        $id = $this->input->post('id');
        $this->db->query("DELETE FROM operator_produksi WHERE id_opprod='$id'");
        echo "oke";
    } //end

    function inputproduksimesin(){
        $grub = $this->input->post('grub');
        $tgl = $this->input->post('tgl');
        $shift = $this->input->post('shift');
        $opt = $this->input->post('opt');
        $waktuinput = date('Y-m-d H:i:s');
        $dtlist = [
            'tgl_produksi' => $tgl,
            'shift' => $shift,
            'yg_input' => $opt,
            'waktu_input' => $waktuinput,
            'grub_op' => $grub
        ];
        $dtlist2 = [
            'tgl_produksi' => $tgl,
            'shift' => $shift
        ];
        $zpm = $this->data_model->get_byid('z_prodmesin', $dtlist2)->num_rows();
        if($zpm == 1){
            $id_zpm = $this->data_model->get_byid('z_prodmesin', $dtlist2)->row('id_zpm');
            $grubdb = $this->data_model->get_byid('z_prodmesin', $dtlist2)->row('grub_op');
            if($grubdb == $grub){
                echo json_encode(array("statusCode"=>200, "fix"=>"fix", "psn"=>$id_zpm));
            } else {
                echo json_encode(array("statusCode"=>500, "fix"=>$grubdb, "psn"=>$id_zpm));
            }
            
        } else {
            $this->data_model->saved('z_prodmesin', $dtlist);
            $id_zpm = $this->data_model->get_byid('z_prodmesin', $dtlist2)->row('id_zpm');
            echo json_encode(array("statusCode"=>200, "fix"=>"fix", "psn"=>$id_zpm));
        }
        
    } //end

    function showopt(){
        $grub = $this->input->post('grub');
        $zpm = $this->input->post('idzpmokesd');
        $opt = $this->data_model->get_byid('operator_produksi',['grub'=>$grub]);
        $cekzpm = $this->data_model->get_byid('z_report_produksi', ['id_zpm'=>$zpm])->num_rows();
        if($cekzpm > 0){
            $_nproses = "updated";
        } else {
            $_nproses = "saved";
        }
        ?>
        <form action="<?=base_url('proses2/simpanproduksi') ?>" method="post" style="width:100%;display:flex;flex-direction:column;">
            <?php $no=1; foreach($opt->result() as $op): 
            $_idopt = $op->id_opprod;
            $cek_isiansebelum = $this->db->query("SELECT * FROM z_report_produksi WHERE id_opprod='$op->id_opprod' ORDER BY id_report DESC LIMIT 1");
            if($cek_isiansebelum->num_rows() == 1){
                $blok_mesin = $cek_isiansebelum->row('no_mesin');
            } else {
                $blok_mesin = "null";
            }
            ?>
            <div style="width:100%;padding:10px;display:flex;align-items:center;justify-content:space-between;">
                <?php if($op->reliver == "n"){ ?>
                <label for="tes<?=$no;?>" style="font-size:13px;width:50%;font-weight:bold;"><?=$op->nama_operator;?></label>
                <?php } else { ?>
                <label for="tes<?=$no;?>" style="font-size:13px;width:50%;font-weight:bold;"><?=$op->nama_operator;?> <font style='color:red;'>*</font></label>
                <?php } ?>
                <select id="tes<?=$no;?>" style="width:50%;padding:10px;border:1px solid #ccc;outline:none;border-radius:5px;font-size:12px;" name="nmmesin[]" required>
                    <option value="" <?=$blok_mesin=='null' ? 'selected':'';?>>Pilih</option>
                    <option value="Libur" <?=$blok_mesin=='Libur' ? 'selected':'';?>>Libur</option>
                    <?php
                    $plot_mesin = $this->data_model->get_record('plotmesin');
                    foreach($plot_mesin->result() as $pm){
                        //echo "<option value='".$pm->namablok."'>".$pm->namablok."</option>";
                        ?>
                        <option value="<?=$pm->namablok;?>" <?=($pm->namablok == $blok_mesin) ? 'selected':'';?>> <?=$pm->namablok;?> </option>
                        <?php
                    }
                    ?>

                </select>
                <input type="hidden" name="idopt[]" value="<?=$_idopt;?>">
            </div>
            <?php $no++; endforeach; ?>
            <small><font style="color:red;margin-left:10px;">*</font> Operator Reliver</small>
            <input type="hidden" name="idzpm" value="<?=$zpm;?>">
            <input type="hidden" name="nproses" value="<?=$_nproses;?>">
            <button type="submit" class="clsBtnClick">Simpan</button>
        </form>
        <?php
    } //end

    function simpanproduksi(){
        $id_operator = $this->input->post('idopt');
        $mesin = $this->input->post('nmmesin');
        $idzpm = $this->input->post('idzpm');
        $nproses = $this->input->post('nproses');
        $_aa = $this->data_model->get_byid('z_prodmesin', ['id_zpm'=>$idzpm]);
        $_tgl_produksi = $_aa->row("tgl_produksi");
        $_shift = $_aa->row("shift");
        $cc = $this->data_model->get_byid('loom_counter', ['tgl'=>$_tgl_produksi,'shift_mesin'=>$_shift])->num_rows();
        if($cc > 0){
            for ($i=0; $i < count($id_operator); $i++) { 
                if($mesin[$i] == "Libur"){
                    $jml_mesin = 0;
                    $produksi = 0;
                    $eff = 0;
                } else {
                    $mc = $this->data_model->get_byid('plotmesin', ['namablok'=>$mesin[$i]])->row('list_mesin');
                    $x = explode('-', $mc);
                    $jml_mesin = count($x);
                    $produksi = 0;
                    $eff = 0;
                    for ($a=0; $a <$jml_mesin ; $a++) { 
                        $_prod = $this->data_model->get_byid('loom_counter', ['tgl'=>$_tgl_produksi,'shift_mesin'=>$_shift,'no_mesin'=>$x[$a]])->row('produksi');
                        $_eff = $this->data_model->get_byid('loom_counter', ['tgl'=>$_tgl_produksi,'shift_mesin'=>$_shift,'no_mesin'=>$x[$a]])->row('eff');
                        //echo "$_prod - $_eff <br>";
                        $produksi+=$_prod;
                        $eff+=$_eff;
                    }
                }
                if($nproses == "saved"){
                    $dtlist = [
                        'id_zpm' => $idzpm,
                        'id_opprod' => $id_operator[$i],
                        'no_mesin' => $mesin[$i],
                        'jml_mesin' => $jml_mesin,
                        'produksi' => $produksi,
                        'effisiensi' => $eff,
                    ];
                    $this->data_model->saved('z_report_produksi', $dtlist);
                } else {
                    $this->db->query("UPDATE z_report_produksi SET no_mesin='$mesin[$i]', jml_mesin='$jml_mesin', produksi='$produksi', effisiensi='$eff' WHERE id_zpm='$idzpm' AND id_opprod='$id_operator[$i]'");
                }
                
                
            } //end for
            redirect(base_url('produksi-mesin'));
        } else {
            echo "<div style='width:100%;height:100vh;display:flex;justify-content:center;align-items:center;font-size:45px;padding:20px;'>";
            echo "Produksi Loom Counter untuk tanggal ".$_tgl_produksi." dan Shift ".$_shift." belum di input!!!";
            echo "</div>";
        }
    }

}