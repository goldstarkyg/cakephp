<?php
include("connection.php");
$role=$_REQUEST['role'];
// if($role=='0'){$role="member";}
// elseif($role=='1'){$role="staff_member";}
// elseif($role=='2'){$role="accountant";}
// elseif($role=='3'){$role="administrator";} 
// else{$role="member";}
if($role=='0'){$role="staff_member";}
elseif($role=='1'){$role="administrator";}
else{$role="staff_member";}
$query="SELECT `id`,`first_name`,`last_name` FROM `gym_member` WHERE `role_name`='$role'";
$res=$conn->query($query);
$result=array();
if ($res->num_rows > 0) 
{
	$result['status']='1';
	$result['error']='';
	while($row = $res->fetch_assoc())
	{
		$r['name']=$row['first_name']." ".$row['last_name'];
		if($role=='administrator'){$r['name']='Administrator';}
		$r['id']=$row['id'];
		$result['result']['members'][]=$r;
	}
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