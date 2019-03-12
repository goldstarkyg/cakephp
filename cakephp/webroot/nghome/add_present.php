<?php
include'connection.php';
if(isset($_REQUEST['id'])){$user_id=$_REQUEST['id'];}
if(isset($_REQUEST['class'])){$class_id=$_REQUEST['class'];}
if(isset($_REQUEST['date'])){$attendance_date=$_REQUEST['date'];}
if(isset($_REQUEST['status'])){$status=$_REQUEST['status'];}
$get_record="SELECT * FROM `gym_member` WHERE `id` = $user_id";
$Select_query=$conn->query($get_record);

if(mysqli_num_rows($Select_query) > 0){

	$result['status']='1';

	$result['error']='';
	while($get_data=mysqli_fetch_assoc($Select_query))
	{
		$role_name = $get_data['role_name'];
		        
		$get_record1="SELECT * FROM `gym_attendance` WHERE `user_id` = $user_id AND `class_id` = $class_id AND `attendance_date` = '".$attendance_date."'";

		$Select_query1=$conn->query($get_record1);
        
        if(mysqli_num_rows($Select_query1) > 0){
            $sql="UPDATE `gym_attendance` SET `status`='$status',`role_name`='$role_name' WHERE user_id = $user_id AND class_id = $class_id AND `attendance_date` = '".$attendance_date."'";
        }else{
 			$sql="INSERT INTO `gym_attendance`(`user_id`,`class_id`,`attendance_date`,`status`,`attendance_by`,`role_name`) VALUES ('$user_id','$class_id','$attendance_date','$status','$user_id','$role_name')";
        } 
        
        $conn->query($sql);           
        
	}
	
}else{
	$result['status']='0';
	$result['error']='User is not exsit!';	
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