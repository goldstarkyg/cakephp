<?php
include("connection.php");
$sql="SELECT `id`,`class_name` FROM `class_schedule`";
$res=$conn->query($sql);
$result=array();
if ($res->num_rows > 0) 
{
	$result['status']='1';
	$result['error']='';
	while($row = $res->fetch_assoc())
	{
		$result['result']['classes'][]=$row;
	}

}
else
{
	$result['status']='0';
	$result['error']='No records!';
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
$conn->close();
?>