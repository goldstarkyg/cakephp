<?php
include'connection.php';
if(isset($_REQUEST['username'])){$uname=$_REQUEST['username'];}
if(isset($_REQUEST['password'])){$password=$_REQUEST['password'];}
if(isset($_REQUEST['push_token'])){$push_token=$_REQUEST['push_token'];}
$get_record="SELECT * FROM `gym_member` WHERE `username` = '$uname'" ;
$Select_query=$conn->query($get_record);
$error = 1;	
$result=array();

if(mysqli_num_rows($Select_query) > 0){

	$result['status']='1';

	$result['error']='';
	while($get_data=mysqli_fetch_assoc($Select_query))
	{
		$hash = $get_data['password'];
		$get_data['Image']=$image_path.$get_data['image'];
		//$result['result'][]=$get_data;		
        
        if(password_verify($password,$hash))
		{
			$hash = $get_data['password'];
			$get_data['Image']=$image_path.$get_data['image'];
			$result['result'][]=$get_data;	
			$error = 0;

            $member_Id = $get_data['id'];
			$role_name = $get_data['role_name'];

			$fcm_record="SELECT `push_token` FROM `gym_member_fcm` WHERE `member_id` = '$member_Id'";
			$fcm_query=$conn->query($fcm_record);
			
			if(mysqli_num_rows($fcm_query) > 0){
		        $sql="UPDATE `gym_member_fcm` SET `push_token`='$push_token',`role_name`='$role_name' WHERE member_id=$member_Id";
			}else{
				$sql="INSERT INTO `gym_member_fcm`(`member_id`,`push_token`,`role_name`,`created_date`) VALUES ('$member_Id','$push_token','$role_name',CURRENT_DATE)";
			}
			$conn->query($sql);		

			// if($get_data['activated']==0)
			// {
			// 	$result['status']='0';
			// 	$result['error']='Your account not activated yet!';
			// 	$result['result']=array();
			// 	$error=2;
			// }
		}
	}
	
}

if($error == 1 )
{	
	$result['status']='0';
	$result['error']='Username or password are wrong!';
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
?>