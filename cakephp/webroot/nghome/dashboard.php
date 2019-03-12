<?php
include("connection.php");  
$id=$_REQUEST['id'];   

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

$sql="SELECT COUNT(*)  FROM `gym_member` WHERE `role_name` = 'member'";
$res=$conn->query($sql);
$result['member'] = mysqli_fetch_array($res)[0];
$sql="SELECT COUNT(*)  FROM `gym_member` WHERE `role_name` = 'staff_member'";
$res=$conn->query($sql);
$result['staff_member'] = mysqli_fetch_array($res)[0];
$sql="SELECT COUNT(*) FROM `gym_group`";
$res=$conn->query($sql);
$result['group'] = mysqli_fetch_array($res)[0];
$sql="SELECT COUNT(*) FROM `gym_message` WHERE `receiver`=$id";
$res=$conn->query($sql);
$result['message'] = mysqli_fetch_array($res)[0];

$sql="SELECT `birth_date`,`first_name`,`last_name` FROM `gym_member`";
$res=$conn->query($sql);
$birthdate = array();
if($res->num_rows>0)
{   
        while($r=$res->fetch_assoc())
	{
		$row['name']=$r['first_name']." ".$r['last_name'];
		$time=strtotime($r['birth_date']);
		$month=date("m",$time);
		$day=date("d",$time);
		$year=date("Y");
		$row['birth_date']=$year."-".$month."-".$day;
		array_push($birthdate,$row);
        //$result['Birthdate'][]=$row;
	}
}
$result['Birthdate']=$birthdate;

$sql="SELECT `notice_title`,`comment`,`start_date`,`end_date` FROM `gym_notice` WHERE `notice_for`='all'";
$res=$conn->query($sql);
$notice = array();
if($res->num_rows>0)
{
	while($r=$res->fetch_assoc())
	{
		$begin = new DateTime($r['start_date']);
		$end = new DateTime($r['end_date']);
		$end = $end->modify( '+1 day' ); 
		$interval = new DateInterval('P1D');
		$daterange = new DatePeriod($begin, $interval ,$end);
		foreach($daterange as $date){
			 $r1['date']=$date->format("Y-m-d");
			 $r1['title']=$r['notice_title']."\n\n".$r['comment'];
             array_push($notice,$r1);
			 //$result['notice'][]=$r1;
		}
	}
	
}
$result['notice']=$notice;

$sql="SELECT `event_name`,`event_date`,`start_time`,`end_time`,gym_event_place.place FROM `gym_reservation` 
LEFT JOIN gym_event_place ON gym_reservation.`place_id` = gym_event_place.id ";

$res=$conn->query($sql);
$reservation = array();
if($res->num_rows>0)
{
	while($r=$res->fetch_assoc())
	{
			if (is_null($r['place']))  
				$r['place']="undefined";
            array_push($reservation,$r);
            //$result['reservation'][]=$r;
	}
	
}
$result['reservation']=$reservation;

echo json_encode(utf8ize($result));

?>