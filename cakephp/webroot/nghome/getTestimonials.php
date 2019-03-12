<?php
include("connection.php");
$query="SELECT * FROM `gym_testimonials`";
$res=$conn->query($query);
$result=array();
if ($res->num_rows > 0) 
{
	
	$result['status']='1';
	$result['error']='';
	while($row = $res->fetch_assoc())
	{
		$r['id']=$row['id'];
		$r['name']=$row['name'];
		$r['photo']=$row['photo'];
		$r['description']=$row['description'];
		$result['result'][]=$r;
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