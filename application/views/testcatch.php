<?php 
    echo base_url();

    $qrpath   = "http://localhost/codeigniter_JAKiTrans/images/qr/qr-230802561730.png";
	$logopath = "http://localhost/codeigniter_JAKiTrans/images/logo.png";
    $qrlogo3   = "http://localhost/codeigniter_JAKiTrans/images/qr/qrlogo3.png";
	$Content  = "qr logo 1";
    
	//QRcode::png($Content,$buatFolder.'qrlogo2.png',QR_ECLEVEL_H,12,2);
    
	$QR   = imagecreatefrompng($qrpath);
	$logo = imagecreatefromstring(file_get_contents($logopath));
    
	imagecolortransparent($logo, imagecolorallocatealpha($logo, 0, 0, 0, 200));
	imagealphablending($logo, false);
	imagesavealpha($logo, true);
    
	$QR_width    = imagesx($QR);
	$QR_height   = imagesy($QR);
	$logo_width  = imagesx($logo);
	$logo_height = imagesy($logo);
	
	$logo_qr_width = $QR_width/4;
	$scale = $logo_width/$logo_qr_width;
	$logo_qr_height = $logo_height/$scale;
	
	imagecopyresampled($QR,$logo,$QR_width/2.5,$QR_height/2.5, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
	imagepng($QR, FCPATH.'images/qr/qrlogo3.png');
	
	echo "<img src=". $qrlogo3 ." />";//"<img src='http://localhost/codeigniter_JAKiTrans/images/qr/qrlogo3.png'/>";
    
?>    


