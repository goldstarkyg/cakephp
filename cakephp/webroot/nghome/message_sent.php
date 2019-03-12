<?php
include("connection.php");
if(isset($_REQUEST['id'])){$id=$_REQUEST['id'];}
$sql="SELECT * FROM `gym_message` WHERE `sender`= '$id' ORDER BY `date` DESC";
$result1=$conn->query($sql);
$result=array();
if ($result1->num_rows > 0) {
	$result['status']='1';
	$result['error']='';
    while($row = $result1->fetch_assoc()) 
	{
		
		$query="SELECT `image`,`first_name`,`last_name` FROM `gym_member` WHERE `id`='".$row['receiver']."'";
		$result2=$conn->query($query);
		if ($result2->num_rows > 0)
		{
			$r1=$result2->fetch_assoc();
			$row['sender']=$r1['first_name']." ".$r1['last_name'];
			$row['image']=$image_path.$r1['image'];
			$date = new DateTime($row['date']);
			$new_date = $date->format('h:i a');
			$row['time']=$new_date;
			$row['date'] = $date->format('d-M-Y h:i a');
			$result['result']['messageInbox'][]=$row;
		}		
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