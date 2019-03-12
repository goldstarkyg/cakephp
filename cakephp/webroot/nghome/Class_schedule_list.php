<?php
include'connection.php';
$id=$_REQUEST['id'];
$get_record="SELECT gmc.*,cs.class_name,csl.* FROM `gym_member_class` as gmc,class_schedule as cs,class_schedule_list as csl WHERE gmc.assign_class=cs.id and csl.class_id=gmc.assign_class and gmc.member_id=$id";
$select_query=$conn->query($get_record);
$result=array();
if(mysqli_num_rows($select_query) > 0){
	$result['status']='1';
	$result['error']='';
	while($get_data=mysqli_fetch_assoc($select_query)){
		$new_days=implode(",",json_decode($get_data['days']));
		$get_data['days']=$new_days;
		$result['result'][]=$get_data;
	}
}else
{
	$result['status']='0';
	$result['error']='Record Not Found';
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