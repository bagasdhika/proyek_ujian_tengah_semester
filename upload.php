<html>
<title>Form Upload</title>
<body align="center" style="background-color: orange">
<form enctype="multipart/form-data" method="POST" action="hasil_upload.php">
File yang di upload : <input type="file" name="fupload"><br>
Deskripsi File : <br>
<textarea name="deskripsi" rows="8" cols="40"></textarea><br>
<input type=submit value=Upload>
<a href="index.php"><input type="button" value="<< Kembali"/></a>
</form>