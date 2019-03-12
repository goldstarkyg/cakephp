<?php
include('connection.php');
$id=$_REQUEST['id'];
$sql="SELECT `id`, `title` FROM `activity` WHERE `cat_id`=$id";
$res=$conn->query($sql);
if ($res->num_rows > 0) {
	$result['status']='1';
	$result['error']='';
    while($row = $res->fetch_assoc()) 
	{
		$row['title']=trim($row['title']);
		$result['result']['activity'][]=$row;
	}
}
else
{
	$result['status']='0';
	$result['error']='No Activity Found!';
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