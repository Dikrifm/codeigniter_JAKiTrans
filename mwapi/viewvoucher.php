<?php 
require_once 'koneksi.php'; 

//$query = "SELECT * FROM kodepromo ORDER BY id_promo";
$fitur = $_POST['fitur'];
$query = "SELECT * FROM kodepromo 
WHERE ( fitur LIKE '%$fitur%')
and ( type_promo = 'fix' OR type_promo = 'persen' )
and ( status = '1')";
$result = mysqli_query($konek,$query);

$array = array();

while ($row  = mysqli_fetch_assoc($result))
{
	$array[] = $row; 
}


echo ($result) ? 
json_encode(array("kode" => 1, "result"=>$array)) :
json_encode(array("kode" => 0, "pesan"=>"data tidak ditemukan"));


?>
