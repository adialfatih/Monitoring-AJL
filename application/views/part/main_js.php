<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js" integrity="sha256-IW9RTty6djbi3+dyypxajC14pE6ZrP53DLfY9w40Xn4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
<script>
    <?php
        $kdrol = $this->db->query("SELECT nama_benang FROM penerimaan_benang ORDER BY nama_benang");
        if($kdrol->num_rows() > 0){
            $ar_kdrol = array();
            foreach($kdrol->result() as $val){
                $data_nama = '"'.$val->nama_benang.'"';
                if(in_array($data_nama, $ar_kdrol)){} else {
                $ar_kdrol[] = '"'.$val->nama_benang.'"'; }
            }
            $im_kons = implode(',', $ar_kdrol);
        } else {
            $im_kons = '"Belum ada data"';
        }
    ?>
        const autoCompleteJS = new autoComplete({
            placeHolder: "Masukan Nama Benang",
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
        function hitungNetto() {
            var baleValue = document.getElementById("bale").value;
            var nettoValue = parseFloat(baleValue) * 181.44;
            var formattedNetto = isNaN(nettoValue) ? "0" : (nettoValue % 1 === 0 ? nettoValue.toFixed(0) : nettoValue.toFixed(2));
            document.getElementById("netto").value = formattedNetto;
        }
        function submitKarung(){
            var nama = $('#autoComplete').val(); 
            var bale = $('#bale').val();
            var netto = $('#netto').val();
            var karung = $('#karung').val();
            var ne = $('#ne').val();
            var tgl = $('#tgl').val();
            if(nama!="" && bale!="" && netto!="" && karung!="" && ne!="" && tgl!=""){
                
                $.ajax({
                    url:"<?=base_url('proses/inputbenang');?>",
                    type: "POST",
                    data: {"nama" : nama,"bale":bale,"netto":netto,"karung":karung,"ne":ne,"tgl":tgl},
                    cache: false,
                    success: function(dataResult){
                        let timerInterval;
                        Swal.fire({
                            title: "Menyiapkan Kode Karung",
                            html: "Please Wait <b></b> milliseconds.",
                            timer: 2000,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading();
                                const timer = Swal.getPopup().querySelector("b");
                                timerInterval = setInterval(() => {
                                timer.textContent = `${Swal.getTimerLeft()}`;
                                }, 100);
                            },
                            willClose: () => {
                                clearInterval(timerInterval);
                            }
                            }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                console.log("I was closed by the timer");
                            }
                        });
                        $('#dataKArung').html(dataResult);
                        $('#idSubmitForm').hide();
                        
                    }
                });
            } else {
                Swal.fire({
                    title: "Error",
                    text: "Isi Data Dengan Benar!",
                    icon: "error"
                });
            }
        }
        function btnSimpanSizeshif(){
            var tgl = $('#tgl_prod').val(); 
            var hcb1 = $('#hcb1').val();
            var hcb2 = $('#hcb2').val();
            var hcb3 = $('#hcb3').val();
            var ket = $('#ket1').val();
            if(tgl!="" && hcb1!="" && hcb2!="" && hcb3!=""){
                $.ajax({
                    url:"<?=base_url('proses/inputsizpershift');?>",
                    type: "POST",
                    data: {"tgl" : tgl,"hcb1":hcb1,"hcb2":hcb2,"hcb3":hcb3,"ket":ket},
                    cache: false,
                    success: function(dataResult){
                        var dataResult = JSON.parse(dataResult);
                        if(dataResult.statusCode==200){ 
                            Swal.fire({
                                title: "Success",
                                text: ""+dataResult.psn+"",
                                icon: "success"
                            });
                            location.reload();
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
                    title: "Error",
                    text: "Isi Data Dengan Benar!",
                    icon: "error"
                });
            }
        }
        function btnSimpanWarpshif(){
            var tgl = $('#tgl_prod').val(); 
            var hcb1 = $('#hcb1').val();
            var hcb2 = $('#hcb2').val();
            var hcb3 = $('#hcb3').val();
            var kwt1 = $('#kwt1').val();
            var kwt2 = $('#kwt2').val();
            var kwt3 = $('#kwt3').val();
            var ket = $('#ket1').val();
            if(tgl!="" && hcb1!="" && hcb2!="" && hcb3!="" && kwt1!="" && kwt2!="" && kwt3!=""){
                $.ajax({
                    url:"<?=base_url('proses/inputwarpershift');?>",
                    type: "POST",
                    data: {"tgl" : tgl,"hcb1":hcb1,"hcb2":hcb2,"hcb3":hcb3,"kwt1":kwt1,"kwt2":kwt2,"kwt3":kwt3,"ket":ket},
                    cache: false,
                    success: function(dataResult){
                        var dataResult = JSON.parse(dataResult);
                        if(dataResult.statusCode==200){ 
                            Swal.fire({
                                title: "Success",
                                text: ""+dataResult.psn+"",
                                icon: "success"
                            });
                            location.reload();
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
                    title: "Error",
                    text: "Isi Data Dengan Benar!",
                    icon: "error"
                });
            }
        }
        var inputElement1 = document.getElementById("hcb1");
        inputElement1.addEventListener("input", function(event) {
            var inputValue = this.value;
            inputValue = inputValue.replace(/\s/g, '');
            inputValue = inputValue.replace(/\D/g, '');
            var formattedValue = formatRibuan(inputValue);
            this.value = formattedValue;
        });
        var inputElement2 = document.getElementById("hcb2");
        inputElement2.addEventListener("input", function(event) {
            var inputValue = this.value;
            inputValue = inputValue.replace(/\s/g, '');
            inputValue = inputValue.replace(/\D/g, '');
            var formattedValue = formatRibuan(inputValue);
            this.value = formattedValue;
        });
        var inputElement3 = document.getElementById("hcb3");
        inputElement3.addEventListener("input", function(event) {
            var inputValue = this.value;
            inputValue = inputValue.replace(/\s/g, '');
            inputValue = inputValue.replace(/\D/g, '');
            var formattedValue = formatRibuan(inputValue);
            this.value = formattedValue;
        });
        var inputElement4 = document.getElementById("kwt1");
        inputElement4.addEventListener("input", function(event) {
            var inputValue = this.value;
            inputValue = inputValue.replace(/\s/g, '');
            inputValue = inputValue.replace(/\D/g, '');
            var formattedValue = formatRibuan(inputValue);
            this.value = formattedValue;
        });
        var inputElement5 = document.getElementById("kwt2");
        inputElement5.addEventListener("input", function(event) {
            var inputValue = this.value;
            inputValue = inputValue.replace(/\s/g, '');
            inputValue = inputValue.replace(/\D/g, '');
            var formattedValue = formatRibuan(inputValue);
            this.value = formattedValue;
        });
        var inputElement6 = document.getElementById("kwt3");
        inputElement6.addEventListener("input", function(event) {
            var inputValue = this.value;
            inputValue = inputValue.replace(/\s/g, '');
            inputValue = inputValue.replace(/\D/g, '');
            var formattedValue = formatRibuan(inputValue);
            this.value = formattedValue;
        });

        // Fungsi untuk memformat ribuan
        function formatRibuan(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
</script>
</body>
</html>