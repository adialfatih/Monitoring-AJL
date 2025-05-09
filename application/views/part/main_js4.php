<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js" integrity="sha256-IW9RTty6djbi3+dyypxajC14pE6ZrP53DLfY9w40Xn4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
<script>
    <?php
        $kdrol = $this->db->query("SELECT * FROM karung_benang WHERE status_karung='masih'");
        if($kdrol->num_rows() > 0){
            $ar_kdrol = array();
            foreach($kdrol->result() as $val){
                
                $kode_beam = $val->nama_benang." - ".$val->kode_karung;
                $data_nama = ''.$kode_beam.'';
                if(in_array($data_nama, $ar_kdrol)){} else {
                $ar_kdrol[] = '"'.$data_nama.'"'; }
            }
            $im_kons = implode(',', $ar_kdrol);
        } else {
            $im_kons = '"Belum ada data"';
        }
    ?>
        const autoCompleteJS = new autoComplete({
            placeHolder: "Masukan Kode Karung",
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
                        $.ajax({
                            url:"<?=base_url('prosesajax/cariKarung');?>",
                            type: "POST",
                            data: {"kode" : selection},
                            cache: false,
                            success: function(dataResult){
                                var dataResult = JSON.parse(dataResult);
                                if(dataResult.statusCode==200){
                                    if(dataResult.fix == "yes"){
                                        $('#kode_karung').val(''+dataResult.kodeKarung);
                                        $('#jml_kones').val(''+dataResult.sisaCones);
                                    } else {
                                        Swal.fire({
                                            title: "Erorr",
                                            text: "Cones di dalam karung telah habis",
                                            icon: "warning"
                                        });
                                        $('#autoComplete').val('');
                                        $('#kode_karung').val('');
                                        $('#jml_kones').val('0');
                                    }
                                } else {
                                    Swal.fire({
                                        title: "Erorr",
                                        text: "Kode Karung Tidak Ditemukan",
                                        icon: "warning"
                                    });
                                    $('#autoComplete').val('');
                                    $('#kode_karung').val('');
                                    $('#jml_kones').val('0');
                                }
                            }
                        });
                    }
                }
            }
        });
        function changeKodeKarung(data){
            if(data==""){
                $('#autoComplete').val('');
                $('#kode_karung').val('');
                $('#jml_kones').val('0');
            } else {
                $.ajax({
                            url:"<?=base_url('prosesajax/cariKarung');?>",
                            type: "POST",
                            data: {"kode" : data},
                            cache: false,
                            success: function(dataResult){
                                var dataResult = JSON.parse(dataResult);
                                if(dataResult.statusCode==200){
                                    if(dataResult.fix == "yes"){
                                        $('#kode_karung').val(''+dataResult.kodeKarung);
                                        $('#jml_kones').val(''+dataResult.sisaCones);
                                    } else {
                                        Swal.fire({
                                            title: "Erorr",
                                            text: "Cones di dalam karung telah habis",
                                            icon: "warning"
                                        });
                                        $('#autoComplete').val('');
                                        $('#kode_karung').val('');
                                        $('#jml_kones').val('0');
                                    }
                                } else {
                                    Swal.fire({
                                        title: "Erorr",
                                        text: "Kode Karung Tidak Ditemukan",
                                        icon: "warning"
                                    });
                                    $('#autoComplete').val('');
                                    $('#kode_karung').val('');
                                    $('#jml_kones').val('0');
                                }
                            }
                        });
            }
        }
        function loadPemakaianBakuWarping(){
            var id = $('#idwarping').val();
            $.ajax({
                url:"<?=base_url('prosesajax/loadPemakaianBakuWarping');?>",
                type: "POST",
                data: {"id" : id},
                cache: false,
                success: function(dataResult){
                    $('#bodytable1').html(dataResult);
                    $('#autoComplete').val('');
                    $('#kode_karung').val('');
                    $('#jml_kones').val('0');
                }
            });
        }
        loadPemakaianBakuWarping();
        function submitCones(){
            var jmlCreel = $('#jmlCreel').val();
            var namaBenang = $('#autoComplete').val();
            var kodeKarung = $('#kode_karung').val();
            var jumlahCones = $('#jml_kones').val();
            var idwarping = $('#idwarping').val();
            if(namaBenang!="" && kodeKarung!="" && jumlahCones!="" && idwarping!=""){
                $.ajax({
                    url:"<?=base_url('prosesajax/submitCones');?>",
                    type: "POST",
                    data: {"idwarping" : idwarping,"namaBenang" : namaBenang, "kodeKarung":kodeKarung, "jumlahCones":jumlahCones, "jmlCreel":jmlCreel},
                    cache: false,
                    success: function(dataResult){
                        var dataResult = JSON.parse(dataResult);
                        if(dataResult.statusCode==200){ 
                            Swal.fire({
                                position: "top-end",
                                icon: "success",
                                title: ""+dataResult.psn+"",
                                showConfirmButton: false,
                                timer: 1000
                            });
                            loadPemakaianBakuWarping();
                        } else {
                            Swal.fire({
                                title: "Erorr",
                                text: ""+dataResult.psn+"",
                                icon: "warning"
                            });
                        }
                    }
                });
            } else {
                Swal.fire({
                    title: "Erorr",
                    text: "Anda tidak mengisi data dengan benar",
                    icon: "warning"
                });
                $('#kode_karung').val('');
                $('#jml_kones').val('');
                $('#autoComplete').val('');
            }
        }
        function deletKarung(id,kode,cones){
            Swal.fire({
                title: "Hapus Pemakaian Benang?",
                text: ""+cones+" Cones akan di kembalikan ke karung "+kode+"",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url:"<?=base_url('prosesajax/kembalikancones');?>",
                        type: "POST",
                        data: {"id" : id, "kode":kode, "cones":cones},
                        cache: false,
                        success: function(dataResult){
                            Swal.fire({
                            title: "Deleted!",
                            text: ""+cones+" Cones dikembalikan",
                            icon: "success"
                            });
                            loadPemakaianBakuWarping();
                        }
                    });
                    
                }
            });
        }
</script>
</body>
</html>