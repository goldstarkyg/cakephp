<?php
include('connection.php');
$id=$_REQUEST['id'];
$sql="SELECT `member_type` FROM `gym_member` WHERE `id`=$id";
$res=$conn->query($sql);
if ($res->num_rows > 0) {
	$result['status']='1';
	$result['error']='';
	$mType=$res->fetch_assoc()['member_type'];
	if($mType=='Member')
	{
		$result['result'][]="Paypal";
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