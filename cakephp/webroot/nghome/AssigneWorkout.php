<?php
include'connection.php';
$id=$_REQUEST['id'];
$sql="SELECT `id` FROM `gym_assign_workout` WHERE `user_id` =$id";
$select_query=$conn->query($sql);
if(mysqli_num_rows($select_query) > 0){
	$result['status']='1';
	$result['error']='';
	while($get_data=mysqli_fetch_assoc($select_query)){
			$id=$get_data['id'];
			$sql="SELECT `workout_name`,`day_name`,`reps`,`sets`,`kg`,`time` FROM `gym_workout_data` WHERE `workout_id`=$id";
			$query=$conn->query($sql);
			if(mysqli_num_rows($query) > 0)
			{
				while($r=mysqli_fetch_assoc($query))
				{
					$r['workout_name']=workout($r['workout_name']);
					$result['result'][]=$r;
				}
		}
}
}
else
{
	$result['status']='0';
	$result['error']='No Records!';
}
function workout($wid)
{
	global $conn;
	$sql="SELECT `title` FROM `activity` WHERE `id`=$wid";
	$select_query=$conn->query($sql);
	$get_data=mysqli_fetch_assoc($select_query);
	return $get_data['title'];
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



