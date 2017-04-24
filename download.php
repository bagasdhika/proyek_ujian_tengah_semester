<html>
<title>Aplikasi Download</title>
<body align="center" style="background-color: blue">
<?php
  $konek = mysqli_connect("localhost","root","","uts_dbstbi");

  $query = "SELECT * FROM upload ORDER BY id_upload DESC";
  $hasil = mysqli_query($konek, $query);

  while ($r = mysqli_fetch_array($hasil)){
    echo "Nama File : <b>$r[nama_file]</b> <br>";
    echo "Deskripsi : $r[deskripsi] <br>";
    echo "<a href=\"simpan.php?file=$r[nama_file]\">Download File</a><hr><br>";
  }
?>
<a href="index.php"><input type="button" value="<< Kembali"/></a>
</body>
</html>