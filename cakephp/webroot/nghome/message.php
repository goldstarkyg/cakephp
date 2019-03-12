<?php
include("connection.php");
if(isset($_REQUEST['mp_id'])){$id=$_REQUEST['mp_id'];}
$sql="SELECT * FROM `membership_payment` WHERE `mp_id`= $id";
 $result=array();
$result1=$conn->query($sql);
if ($result1->num_rows > 0) {
	$result['status']='1';
	$result['error']='';
    while($row = $result1->fetch_assoc()) 
	{
		$result['result'][]=$row;
	}
} 
else
{
	$result['status']='0';
	$result['error']='Username Or Password are wrong';
	$result['result']=array();
	
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
$conn->close();
?>