<?php
// define("GOOGLE_API_KEY", "AAAAklb_MN0:APA91bF2anpw5fl5gCPzCcQErJ6lRV5PZ-rs9gywkaEuiJpFo5iShlgb244telOPggZQB1ryXuS8CRwTZqMa7-RapD2zHc2Ue9mhbAoCXD8ZDFvN01F9bqQxg5UZh8fZ6YPZJzsYPC9A"); 
include('connection.php');
$sql="SELECT `id`, `name` FROM `category`";
$res=$conn->query($sql);
if ($res->num_rows > 0) {
	$result['status']='1';
	$result['error']='';
    while($row = $res->fetch_assoc()) 
	{	
		$sql="SELECT `id`, `title` FROM `activity` WHERE `cat_id`='".$row['id']."'";
		$res1=$conn->query($sql);
		if ($res1->num_rows > 0) {
			while($r = $res1->fetch_assoc()) 
			{
				$r['cat_id']=$row['id'];
				$result['result'][]=$r;
			}
		}
	}
}
else
{
	$result['status']='0';
	$result['error']='No Activity Found!';
	$result['result']=array();
}

// function send_notification ($tokens, $message)
// {  
// 	$url = 'https://fcm.googleapis.com/fcm/send';
// 	$fields = array(
// 		 'registration_ids' => $tokens,
// 		 'data' => $message
// 		);

// 	$headers = array(
// 		'Authorization:key =' . GOOGLE_API_KEY,
// 		'Content-Type: application/json'
// 		);

//    $ch = curl_init();
//    curl_setopt($ch, CURLOPT_URL, $url);
//    curl_setopt($ch, CURLOPT_POST, true);
//    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  
//    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
//    $push_result = curl_exec($ch);           
//    if ($push_result === FALSE) {
//        die('Curl failed: ' . curl_error($ch));
//    }
//    curl_close($ch);      
//    return json_encode($push_result);
// }

// $push_tokens = array();

// $push_token = "eFSJJEJMnV4:APA91bGi2Z4U5zWwidQvJXr_i8HlRg2SuLEKCL3DFaZmz9ga_UE29Eb1E0oj8amGzhYP23WA9KyrE0OIRdHD_GROEPkuFRLpQ02rJ2wadp8WqpWNmUwM-0E5D9HR9_guNHwjxecedA7Q";
// array_push($push_tokens,$push_token);

// $message_content = "new notification!!!";
// $push_message = array("name" => $message_content);

// $push_result = send_notification($push_tokens, $push_message);
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