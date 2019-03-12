<?php 

require_once 'connection.php';
define('PROJECT_ROOT', dirname(dirname(__FILE__)));
$new_name = "";
$image = $_REQUEST["image"];
$name = $_REQUEST["name"];
$path=PROJECT_ROOT."/upload/".$name;
//$path = "D:/xampp/htdocs/gym_master/webroot/upload/".$name;
$file=file_put_contents($path,base64_decode($image));
if($file)
{
	$result['status']="1";
	$result['imageName']=$name;
	$result['error']="";
	
}
else
{
	$result['status']="0";
	$result['imageName']="";
	$result['error']="Image is empty/not accessible";
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