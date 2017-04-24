
<?php
////
function hitungsim($query) {
	//ambil jumlah total dokumen yang telah diindex (tbindex atau tbvektor), n
$host='localhost';
$user='root';
$pass='';
$database='uts_dbstbi';

// echo "hitung sim";

$conn=mysqli_connect($host,$user,$pass);
mysqli_select_db($database);
	// $konek = mysqli_connect("localhost","root","","uts_dbstbi");
	$sqlnya="SELECT Count(*) as n FROM tbvektor";
	$resn = mysqli_query($conn,$sqlnya);
	$rown = mysqli_fetch_array($resn);	
	$n = $rown['n'];
	//echo "hasil tbvektor";
	
	// print_r($resn);
	
	//terapkan preprocessing terhadap $query
	$aquery = explode(" ", $query);
	
	//hitung panjang vektor query
	$panjangQuery = 0;
	$aBobotQuery = array();
	
	for ($i=0; $i<count($aquery); $i++) {
		//hitung bobot untuk term ke-i pada query, log(n/N);
		//hitung jumlah dokumen yang mengandung term tersebut
		$sqlnya1="SELECT Count(*) as N from tbindex WHERE Term like '%$aquery[$i]%'";
		$resNTerm = mysqli_query($conn,$sqlnya1);
//		echo "query >SELECT Count(*) as N from tbindex WHERE Term like '%$aquery[$i]%'";
		$rowNTerm = mysqli_fetch_array($resNTerm);	
		$NTerm = $rowNTerm['N'] ;
		
		$idf = log($n/$NTerm);
		
		//simpan di array		
		$aBobotQuery[] = $idf;
		
		$panjangQuery = $panjangQuery + $idf * $idf;		
	}
	
	$panjangQuery = sqrt($panjangQuery);
	
	$jumlahmirip = 0;
	
	//ambil setiap term dari DocId, bandingkan dengan Query
	$sqlnya2="SELECT * FROM tbvektor ORDER BY DocId";
	$resDocId = mysqli_query($conn,$sqlnya2);
	while ($rowDocId = mysqli_fetch_array($resDocId)) {
	
		$dotproduct = 0;
			
		$docId = $rowDocId['DocId'];
		$panjangDocId = $rowDocId['Panjang'];
		$sqlnya3="SELECT * FROM tbindex WHERE DocId = '$docId'";
		$resTerm = mysqli_query($conn,$sqlnya3);
	//	echo "query ->SELECT * FROM tbindex WHERE DocId = '$docId'".'<br>';
		
		
		while ($rowTerm = mysqli_fetch_array($resTerm)) {
			for ($i=0; $i<count($aquery); $i++) {
				//jika term sama
				//echo "1-->".$rowTerm['Term'];
			//	echo "2-->".	$aquery[$i].'<br>';
				
				if ($rowTerm['Term'] == $aquery[$i]) {
					$dotproduct = $dotproduct + $rowTerm['Bobot'] * $aBobotQuery[$i];		
		//			echo "hasil =".$dotproduct.'<br>';
			//		echo "1-->".$rowTerm['Term'];
			//	echo "2-->".	$aquery[$i].'<br>';
					
				} //end if
					else
					{
					}
			} //end for $i		
		} //end while ($rowTerm)
		
		if ($dotproduct != 0) {
			$sim = $dotproduct / ($panjangQuery * $panjangDocId);	
			//echo "insert >>INSERT INTO tbcache (Query, DocId, Value) VALUES ('$query', '$docId', $sim)";
			//simpan kemiripan > 0  ke dalam tbcache
			$sqlnya4="INSERT INTO tbcache (Query, DocId, Value) VALUES ('$query', '$docId', $sim)";
			$resInsertCache = mysqli_query($conn,$sqlnya4);
			$jumlahmirip++;
		} 
			
	if ($jumlahmirip == 0) {
		$sqlnya5="INSERT INTO tbcache (Query, DocId, Value) VALUES ('$query', 0, 0)";
		$resInsertCache = mysqli_query($conn,$sqlnya5);
	}	
	} //end while $rowDocId
	
		
} //end hitungSim()





////
$host='localhost';
$user='root';
$pass='';
$database='uts_dbstbi';
$keyword=$_POST['keyword'];;
// $conn=mysqli_connect($host,$user,$pass);
// mysqli_select_db($database);
$conn=mysqli_connect('localhost','root','','uts_dbstbi');
$sql = "SELECT *  FROM tbcache WHERE Query = '$keyword' ORDER BY Value DESC";
$resCache = mysqli_query($conn,$sql);
	$num_rows = mysqli_num_rows($resCache);
	if ($num_rows >0) {

		//tampilkan semua berita yang telah terurut
		while ($rowCache = mysqli_fetch_array($resCache)) {
			$docId = $rowCache['DocId'];
			$sim = $rowCache['Value'];
					
				//ambil berita dari tabel tbberita, tampilkan
				//echo ">>>SELECT nama_file,deskripsi FROM upload WHERE nama_file = '$docId'";
				$sql1="SELECT nama_file,deskripsi FROM upload WHERE nama_file = '$docId'";
				$resBerita = mysqli_query($conn,$sql1);
				$rowBerita = mysqli_fetch_array($resBerita);
					
				$judul = $rowBerita['nama_file'];
				$berita = $rowBerita['deskripsi'];
					
				print($docId . ". (" . $sim . ") <font color=blue><b><a href=" . $judul . "> </b></font><br />");
				print($berita . "<hr /></a>"); 		
			
		}//end while (rowCache = mysql_fetch_array($resCache))
	}
		else
		{
		hitungsim($keyword);
		//pasti telah ada dalam tbcache	
		$sql2="SELECT *  FROM tbcache WHERE Query = '$keyword' ORDER BY Value DESC";
		$resCache = mysqli_query($conn,$sql2);
		$num_rows = mysqli_num_rows($resCache);
		
		while ($rowCache = mysqli_fetch_array($resCache)) {
			$docId = $rowCache['DocId'];
			$sim = $rowCache['Value'];
					
				//ambil berita dari tabel tbberita, tampilkan
				$sql3="SELECT nama_file,deskripsi FROM upload WHERE nama_file = '$docId'";
				$resBerita = mysqli_query($conn,$sql3);
				$rowBerita = mysqli_fetch_array($resBerita);
					
				$judul = $rowBerita['nama_file'];
				$berita = $rowBerita['deskripsi'];
					
				print($docId . ". (" . $sim . ") <font color=blue><b><a href=" . $judul . "> </b></font><br />");
				print($berita . "<hr /></a>");
		
		} //end while
		}

?>