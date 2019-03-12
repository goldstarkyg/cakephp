<?php
include('connection.php');
if(isset($_REQUEST['measurement'])){$measurement=$_REQUEST['measurement'];}
if(isset($_REQUEST['result'])){$result=$_REQUEST['result'];}
if(isset($_REQUEST['user_id'])){$user_id=$_REQUEST['user_id'];}
if(isset($_REQUEST['date'])){$date=$_REQUEST['date'];}
if(isset($_REQUEST['filename'])){$filename=$_REQUEST['filename'];}
//if(isset($_REQUEST['image'])){$image=$_REQUEST['image'];}
if(isset($_REQUEST['created_by'])){$created_by=$_REQUEST['created_by'];}
$d=date_create($date);
$date=date_format($d,"Y-m-d");
$created_date = date('Y-m-d');
$sql="INSERT INTO `gym_measurement`
(`result_measurment`, `result`, `user_id`, `result_date`, `image`, `created_by`, `created_date`)
 VALUES ('$measurement',$result,$user_id,'$date','$filename',$created_by,'$created_date')";
$result=array();
if ($conn->query($sql)) {
	$result['status']='1';
	$result['error']='';
} 
else
{
	$result['status']='0';
	$result['error']='Something getting wrong!!';
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