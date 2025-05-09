<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js" integrity="sha256-IW9RTty6djbi3+dyypxajC14pE6ZrP53DLfY9w40Xn4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
<script>
    <?php
        $kdrol = $this->db->query("SELECT nama_baku FROM data_baku ORDER BY nama_baku");
        if($kdrol->num_rows() > 0){
            $ar_kdrol = array();
            foreach($kdrol->result() as $val){
                $data_nama = '"'.$val->nama_baku.'"';
                if(in_array($data_nama, $ar_kdrol)){} else {
                $ar_kdrol[] = '"'.$val->nama_baku.'"'; }
            }
            $im_kons = implode(',', $ar_kdrol);
        } else {
            $im_kons = '"Belum ada data"';
        }
    ?>
        const autoCompleteJS = new autoComplete({
            placeHolder: "Masukan Nama Barang",
            data: {
                src: [<?=$im_kons;?>],
                cache: true,
            },
            resultItem: {
                highlight: true
            },
            events: {
                input: {
                    selection: (event) => {
                        const selection = event.detail.selection.value;
                        autoCompleteJS.input.value = selection;
                    }
                }
            }
        });
        
        // Fungsi untuk memformat ribuan
        function formatRibuan(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
        function submitData(){
            var namabaku = $('#autoComplete').val();
            var jns = $('#jnsid').val();
            var tgl = $('#tglid').val();
            var jmlbeli = $('#jmlbeli').val();
            var satuan = $('#satuan').val();
            var supp = $('#supp').val();
            var sj = $('#sj').val();
            var ket = $('#ket').val();
            if(namabaku!="" && jns!="" && tgl!="" && jmlbeli!="" && satuan!="" && supp!="" && sj!=""){
                $.ajax({
                    url:"<?=base_url('data/savedbaku');?>",
                    type: "POST",
                    data: {"namabaku" : namabaku,"jns":jns,"tgl":tgl,"jmlbeli":jmlbeli,"satuan":satuan,"supp":supp,"sj":sj,"ket":ket},
                    cache: false,
                    success: function(dataResult){
                        var dataResult = JSON.parse(dataResult);
                        if(dataResult.statusCode==200){ 
                            Swal.fire({
                                title: "Saved",
                                text: ""+dataResult.psn+"",
                                icon: "success"
                            });
                            $('#autoComplete').val('');
                            $('#jnsid').val('');
                            $('#tglid').val('');
                            $('#jmlbeli').val('');
                            $('#satuan').val('');
                            $('#supp').val('');
                            $('#sj').val('');
                            $('#ket').val('');
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: ""+dataResult.psn+"",
                                icon: "error"
                            });
                        }
                    }
                });
            } else {
                Swal.fire({
                    text: "Anda harus mengisi semua data.",
                    icon: "error"
                });
            }
        }
        function hps(id,nm,tgl){
            Swal.fire({
            title: "Hapus Pembelian ?",
            text: "Anda akan menghapus data pembelian "+nm+" tanggal "+tgl+"",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Hapus"
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url:"<?=base_url('data/deltbaku');?>",
                    type: "POST",
                    data: {"id" : id},
                    cache: false,
                    success: function(dataResult){
                        Swal.fire({
                            title: "Deleted!",
                            text: "Berhasil menghapus data",
                            icon: "success"
                        });
                        $('#trid'+id+'').hide(500);
                    }
                });
            }
            });
        }
</script>
</body>
</html>