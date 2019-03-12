<?php
include'connection.php';
$id=$_REQUEST['id'];

$username=$_REQUEST['username'];
$first_name=$_REQUEST['first_name'];
$last_name=$_REQUEST['last_name'];
$address=$_REQUEST['address'];
$email=$_REQUEST['email'];
$mobile=$_REQUEST['mobile'];
$birth_date=$_REQUEST['birth_date'];
$password=$_REQUEST['password'];
//$sql="UPDATE `gym_member` SET `username`='$username' WHERE id=10";
if($password=="")
{
	$sql="UPDATE `gym_member` SET `username`='$username',`first_name`='$first_name',`last_name`='$last_name',`address`='$address', 
   `email`='$email',`birth_date`='$birth_date',`mobile`='$mobile' WHERE id=$id";
}
else{
	$password = password_hash($password,PASSWORD_DEFAULT);
	$sql="UPDATE `gym_member` SET `username`='$username',`first_name`='$first_name',`last_name`='$last_name',`address`='$address', 
`email`='$email',`birth_date`='$birth_date',`password`='$password',`mobile`='$mobile' WHERE id=$id";
}

if($conn->query($sql))
{

	$result['status']="1";
	$result['error']="Insert Record";
}
else
{
	$result['status']="0";
	$result['error']="Something getting wrong!";
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