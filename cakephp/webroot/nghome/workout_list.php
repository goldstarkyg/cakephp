<?php
include('connection.php');
if(isset($_REQUEST['id'])){$id=$_REQUEST['id'];}
$query="SELECT `record_date` FROM `gym_daily_workout` WHERE `member_id`=6";
$res=$conn->query($query);
if ($res->num_rows > 0) {
	$result['status']='1';
	$result['error']='';

    while($row = $res->fetch_assoc()) 
	{
		$d = date_parse_from_format("Y-m-d", $row['record_date']);
	
		//$result[$d['year']][$d['month']][][$d['day']]=$d['month'];.
		$dateObj   = DateTime::createFromFormat('!m', $d['month']);
		$monthName = $dateObj->format('F');
		$month[]=$d['year'].' '.$monthName;
		//$result[$d['year'].' '.$monthName][]=$d['day'];
		
	}
	$month=array_unique($month);
	$result['result']=array_values($month);
	
} 
else
{
	$result['status']='0';
	$result['error']='No records!';
	$result['result']=array();
	
}
//echo $result['2016 August'][0];
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