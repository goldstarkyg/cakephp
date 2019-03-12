<?php
include('connection.php');
if(isset($_REQUEST['id'])){$id=$_REQUEST['id'];}
$query="SELECT `id`,`result_measurment`,`result`,`result_date`,`image` FROM `gym_measurement` WHERE `user_id`=$id";
$res=$conn->query($query);
if ($res->num_rows > 0) {
	$result['status']='1';
	$result['error']='';
    while($row = $res->fetch_assoc()) 
	{
		$row['image']=$image_path.$row['image'];
		$result['result']['measurement'][]=$row;
	}
} 
else
{
	$result['status']='0';
	$result['error']='No records!';
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