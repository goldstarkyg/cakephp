<?php
include("connection.php");
$sql="SELECT `name`, `start_year`, `address`, `office_number`, `country`,
 `email`, `weight`, `height`, `chest`, `waist`, `thing`, `arms`, `fat`, 
`paypal_email`, `currency`, `left_header`  FROM `general_setting`";
$res=$conn->query($sql);
$result=array();
if ($res->num_rows > 0) {
	$result['status']='1';
	$result['error']='';
	while($get_data=mysqli_fetch_assoc($res))
	{
			$result['result']=$get_data;					
	}
}
else
{
	$result['status']='0';
	$result['error']='No data!';
}
function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}
echo json_encode(utf8ize($result));
?>