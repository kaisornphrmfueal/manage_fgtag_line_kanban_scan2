<?php
	//START CREATE FILE --------------------
		$strFileName = "111.txt";
		$objFopen = fopen($strFileName, 'w');
		$strText1 = "Name Plate : \r\n";
		fwrite($objFopen, $strText1);
		$strText2 = "Battery Serial No. : \r\n";
		fwrite($objFopen, $strText2);
		//if($objFopen){echo "File writed.";}	else{echo "File can not write";}
		fclose($objFopen);
	//END  CREATE FILE --------------------
?>

