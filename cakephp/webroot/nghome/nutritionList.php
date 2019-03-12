<?php
include'connection.php';
$id=$_REQUEST['id'];
$sql1="SELECT `id` FROM `gym_nutrition` WHERE `user_id`=$id";
$select_query=$conn->query($sql1);
$result=array();
if(mysqli_num_rows($select_query) > 0){
	$result['status']='1';
	$result['error']=''; //`day_name`, `nutrition_time`, `nutrition_value`,`image`
	$r=mysqli_fetch_assoc($select_query);

	//while($r=mysqli_fetch_assoc($select_query)){
		$sql="SELECT * FROM `gym_nutrition_data` 
		WHERE `nutrition_id`='".$r['id']."' ORDER BY CASE WHEN day_name = 'Sunday' THEN 1 WHEN day_name = 'Monday' THEN 2 WHEN day_name = 'Tuesday'	THEN 3 WHEN day_name = 'Wednesday' THEN 4 WHEN day_name = 'Thursday' THEN 5 WHEN day_name = 'Friday' THEN 6 WHEN day_name = 'Saturday' THEN 7 END ASC";
		$query=$conn->query($sql);
		if(mysqli_num_rows($query) > 0){
			while($r=mysqli_fetch_assoc($query)){
				if($r['image1'] == "") $r['image1'] = $server_path.$image_pa."meal.jpg";
				else  $r['image1'] = $server_path.$image_pa.$r['image1'];
				if($r['image2'] == "") $r['image2'] = $server_path.$image_pa."meal.jpg";
				else  $r['image2'] = $server_path.$image_pa.$r['image2'];
				if($r['image3'] == "") $r['image3'] = $server_path.$image_pa."meal.jpg";
				else  $r['image3'] = $server_path.$image_pa.$r['image3'];
				if($r['image4'] == "") $r['image4'] = $server_path.$image_pa."meal.jpg";
				else  $r['image4'] = $server_path.$image_pa.$r['image4'];
				if($r['image5'] == "") $r['image5'] = $server_path.$image_pa."meal.jpg";
				else  $r['image5'] = $server_path.$image_pa.$r['image5'];
				if($r['image6'] == "") $r['image6'] = $server_path.$image_pa."meal.jpg";
				else  $r['image6'] = $server_path.$image_pa.$r['image6'];
				if($r['image7'] == "") $r['image7'] = $server_path.$image_pa."meal.jpg";
				else  $r['image7'] = $server_path.$image_pa.$r['image7'];
								
				$result['result']=$r;
			}
		}
	//}
}else{
	$sql2="SELECT `id` FROM `gym_nutrition` WHERE `user_id`=0";
    $select_query=$conn->query($sql2);
    
    if(mysqli_num_rows($select_query) > 0){
		$result['status']='1';
		$result['error']=''; //`day_name`, `nutrition_time`, `nutrition_value`,`image`
		$r=mysqli_fetch_assoc($select_query);

	
		$sql="SELECT * FROM `gym_nutrition_data` 
		WHERE `nutrition_id`='".$r['id']."' ORDER BY CASE WHEN day_name = 'Sunday' THEN 1 WHEN day_name = 'Monday' THEN 2 WHEN day_name = 'Tuesday'	THEN 3 WHEN day_name = 'Wednesday' THEN 4 WHEN day_name = 'Thursday' THEN 5 WHEN day_name = 'Friday' THEN 6 WHEN day_name = 'Saturday' THEN 7 END ASC";
		$query=$conn->query($sql);
		if(mysqli_num_rows($query) > 0){
			while($r=mysqli_fetch_assoc($query)){
				if($r['image1'] == "") $r['image1'] = $server_path.$image_pa."meal.jpg";
				else  $r['image1'] = $server_path.$image_pa.$r['image1'];
				if($r['image2'] == "") $r['image2'] = $server_path.$image_pa."meal.jpg";
				else  $r['image2'] = $server_path.$image_pa.$r['image2'];
				if($r['image3'] == "") $r['image3'] = $server_path.$image_pa."meal.jpg";
				else  $r['image3'] = $server_path.$image_pa.$r['image3'];
				if($r['image4'] == "") $r['image4'] = $server_path.$image_pa."meal.jpg";
				else  $r['image4'] = $server_path.$image_pa.$r['image4'];
				if($r['image5'] == "") $r['image5'] = $server_path.$image_pa."meal.jpg";
				else  $r['image5'] = $server_path.$image_pa.$r['image5'];
				if($r['image6'] == "") $r['image6'] = $server_path.$image_pa."meal.jpg";
				else  $r['image6'] = $server_path.$image_pa.$r['image6'];
				if($r['image7'] == "") $r['image7'] = $server_path.$image_pa."meal.jpg";
				else  $r['image7'] = $server_path.$image_pa.$r['image7'];
								
				$result['result']=$r;
			}
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