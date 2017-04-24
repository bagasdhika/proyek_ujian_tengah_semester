<?php
require_once('Enhanced_CS.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>STEMMING</title>
</head>
<body align="center" style="background-color: pink">
<h3>SISTEM PENCARI DOKUMEN TEKS</h3>
<h4>STBI - UNISBANK INFORMATION RETRIVAL</h4>
<form method="post" action="">
<input type="text" name="kata" id="kata" size="20" value="<?php if(isset($_POST['kata'])){ echo $_POST['kata']; }else{ echo '';}?>">
<input class="btnForm" type="submit" name="submit" value="Submit"/>
</form>
<?php
if(isset($_POST['kata'])){
	$teksAsli = $_POST['kata'];
	echo "Teks asli : ".$teksAsli.'<br/>';
	$stemming = Enhanced_CS($teksAsli);
	echo "Kata dasar : ".$stemming.'<br/>';
}
?>
<br>
<br>
<a href="upload.php"><input type="button" value="Upload File"/></a>
<a href="query.php"><input type="button" value="Search Keyword"/></a>
<a href="download.php"><input type="button" value="Download File"/></a>
<a href="hitungbobot.php"><input type="button" value="Info Bobot"/></a>
<h3>By YULIANA CAHYATI</h3>
<h3>15.01.65.0022</h3>
</body>
</html>