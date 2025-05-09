<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap4.min.css">
    
</head>
<body>
    Cari Per Tanggal <input type="date" name="dated" id="dated" onchange="tes(this.value)">
    <br>
    <br>
    <span id="idtglssd">Menampilkan Data Bulan April</span>
    <br>
    <br>
    <table border="1" id="myTable" class="table table-bordered">
        <thead>
        <tr>
            <th>No.</th>
            <th>Operator</th>
            <th>Grub</th>
            <th>Total Produksi</th>
        </tr></thead>
        <tbody id="idbodyoke">
        <?php
            $dtop = $this->db->query("SELECT * FROM operator_produksi");
            $n=1;
            foreach($dtop->result() as $tes):
                $idopt = $tes->id_opprod;
                echo "<tr>";
                echo "<td>".$n."</td>";
                if($tes->reliver == "n"){
                echo "<td>".$tes->nama_operator."</td>";
                } else {
                echo "<td style='color:red;'>".$tes->nama_operator." (Reliver)</td>";
                }
                echo "<td>".$tes->grub."</td>";

                $querys = $this->db->query("SELECT SUM(produksi) AS prod FROM v_prodmesin WHERE YEAR(tgl_produksi) = '2024' AND MONTH(tgl_produksi) = '04' AND id_opprod = '$idopt'")->row("prod");
                echo "<td>".round($querys,1)."</td>";
                echo "</tr>";
                $n++;
            endforeach;
            
        ?>
        </tbody>
    </table>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.min.css"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.min.js"></script>
    <script>
        let table = new DataTable('#myTable');
        function tes(dated){
            //alert('sd '+dated);
            var exdt = dated.split('-');
            var tglblnthn = exdt[2]+'-'+exdt[1]+'-'+exdt[0];
            $('#idtglssd').text('Menampilkan data tanggal : '+tglblnthn);
            $('#idbodyoke').html('<tr><td colspan="4">Loading...</td></tr>');
            $.ajax({
                    url:"https://ajl.rdgjt.com/apldtcs/dtview",
                    type: "POST",
                    data: {"proses" : dated},
                        cache: false,
                        success: function(dataResult){
                            $('#myTable').html(dataResult);
                        }
                });
        }
    </script>
</body>
</html>