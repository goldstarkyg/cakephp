<?php
include("connection.php");
if(isset($_REQUEST['mp_id'])){$id=$_REQUEST['mp_id'];}
$sql="SELECT * FROM `gym_message` WHERE `receiver`= $id AND `status`=1";
 $result=array();
$result1=$conn->query($sql);
if ($result1->num_rows > 0) {
	$result['status']='1';
	$result['error']='';
    while($row = $result1->fetch_assoc()) 
	{
		$sender=$row['sender'];
		$sql="SELECT `email` FROM `gym_member` WHERE id= $sender";
		$r=$conn->query($sql)->fetch_assoc();
		$row['email']=$r['email'];
		$date = new DateTime($row['date']);
		$new_date = $date->format('d/m/Y,h:i:s a');
		$row['date']=$new_date;
		$result['result'][]=$row;
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