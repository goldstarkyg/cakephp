<?php
include('connection.php');
$id=$_REQUEST['id'];
$id=explode(",",$id);
$sets=$_REQUEST['sets'];
$sets=explode(",",$sets);
$reps=$_REQUEST['reps'];
$reps=explode(",",$reps);
$time=$_REQUEST['time'];
$time=explode(",",$time);
$kg=$_REQUEST['kg'];
$kg=explode(",",$kg);

for($i=0;$i<sizeof($kg);$i++)
{
		$sql="UPDATE `gym_user_workout` SET `sets`=$sets[$i],`reps`=$reps[$i],
		`kg`=$kg[$i],`rest_time`=$time[$i] WHERE `id`=$id[$i]";
		if($conn->query($sql))
		{
			$result['status']='1';
			$result['error']="";
		}
		else
		{
			$result['status']='0';
			$result['error']="Something getting wrong!";
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