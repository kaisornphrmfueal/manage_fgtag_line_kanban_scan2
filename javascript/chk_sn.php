<?php
	 include("../connect.in.php");

$partnum = $_GET[partnum];

	 $sql = "SELECT * FROM cidata.ci_part_std_buying WHERE part_num = '$partnum'";
	$query = mysqli_query($con, $sql);
	$num_v=mysqli_num_rows($query);
	$obj = mysqli_fetch_array($query);

	if($num_v <>0)
		{
			echo $obj['part_name'];
		} else {
			echo "NO";
		}
	
?>