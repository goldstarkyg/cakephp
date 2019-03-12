<?php
include'connection.php';
$id=$_REQUEST['id'];
$sql="SELECT `assign_class` FROM `gym_member_class` WHERE `member_id`=$id";
$select_query=$conn->query($sql);
$result=array();
if(mysqli_num_rows($select_query) > 0){
	while($get_data=mysqli_fetch_assoc($select_query)){
		$r[]=$get_data['assign_class'];
	}
}
for($i=0;$i<sizeof($r);$i++)
{
$get_record="SELECT class_schedule.id,class_schedule.class_name,gym_member.first_name,gym_member.last_name,
class_schedule.start_time,class_schedule.end_time,class_schedule.location FROM 
`class_schedule` INNER JOIN gym_member ON gym_member.id=class_schedule.assign_staff_mem WHERE 
class_schedule.id ='".$r[$i]."'";
$select_query=$conn->query($get_record);

if(mysqli_num_rows($select_query) > 0){
	$result['status']='1';
	$result['error']='';
	while($get_data=mysqli_fetch_assoc($select_query)){
	
		$result['result'][]=$get_data;
	}
}else{
	$result['status']='0';
	$result['error']='Record Not Found';
	$result['result']=array();
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