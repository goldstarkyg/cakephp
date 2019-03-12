<?php
include('connection.php');
$id=$_REQUEST['id'];
$sql="DELETE FROM `gym_measurement` WHERE `id`=$id"; 
$result=array();
if ($conn->query($sql)) {
	$result['status']='1';
	$result['error']='';
} 
else
{
	$result['status']='0';
	$result['error']='Something getting wrong!!';
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