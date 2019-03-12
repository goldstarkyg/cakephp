<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

define("GOOGLE_API_KEY", "AAAAklb_MN0:APA91bF2anpw5fl5gCPzCcQErJ6lRV5PZ-rs9gywkaEuiJpFo5iShlgb244telOPggZQB1ryXuS8CRwTZqMa7-RapD2zHc2Ue9mhbAoCXD8ZDFvN01F9bqQxg5UZh8fZ6YPZJzsYPC9A"); 

class GymMessageController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent("GYMFunction");
		$session = $this->request->session()->read("User");
		$uid = intval($session["id"]); /* Current userid */
		$query = $this->GymMessage->find("all")->where(["receiver"=>$uid,"status"=> 0]);
		$unread_messages = $query->count();
		$this->set("unread_messages",$unread_messages);
	}
	
	public function index()
	{
		// $conn = ConnectionManager::get('default');
		// $new_field = "activated";
		// $sql = "ALTER TABLE gym_member  ADD  {$new_field} INT NULL DEFAULT 0";
		// $sql1 = "ALTER TABLE `gym_member` DROP `activated123`";
		// $sql2 = "SELECT * FROM gym_member";
		// $stmt = $conn->execute($sql);
		// $stmt = $conn->execute($sql);
		// $results = $stmt->fetchAll('assoc');	
		// debug($results);die;
	}
	function send_notification ($tokens, $message)
	{  
		$url = 'https://fcm.googleapis.com/fcm/send';
		$fields = array(
			 'registration_ids' => $tokens,
			 'data' => $message
			);

		$headers = array(
			'Authorization:key =' . GOOGLE_API_KEY,
			'Content-Type: application/json'
			);

	   $ch = curl_init();
	   curl_setopt($ch, CURLOPT_URL, $url);
	   curl_setopt($ch, CURLOPT_POST, true);
	   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  
	   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	   $push_result = curl_exec($ch);           
	   if ($push_result === FALSE) {
	       die('Curl failed: ' . curl_error($ch));
	   }
	   curl_close($ch);      
	   return json_encode($push_result);
	}

	public function composeMessage()
    {
		$session = $this->request->session()->read("User");
		if($session["role_name"] == "member" && !$this->GYMFunction->getSettings("enable_message"))
		{
			return $this->redirect(["action"=>"inbox"]);
		}
		$classes = $this->GymMessage->ClassSchedule->find("list",["keyField"=>"id","valueField"=>"class_name"])->toArray();
		$classes["all"] = "All";
		
		$this->set("classes",$classes);
		if($this->request->is("post"))
		{
			$date = date("Y-m-d H:i:s");			
			$role = $this->request->data["receiver"];	
			$message_subject = $this->request->data["subject"];
			$message_comment = $this->request->data["message_body"];		
			if($role == 'member' || $role == 'staff_member' || $role == 'accountant'|| $role == 'administrator')
			{
				$member_ids = $this->GymMessage->GymMember->find("all")->where(["role_name"=>$role])->select(["id"])->hydrate(false)->toArray();
				$records = array();				
				if(!empty($member_ids))
				{					
					foreach($member_ids as $key => $value)
					{
						$mid = $value["id"];
						$data = array();
						$data["sender"] = $session["id"]; /* current userid*/
						$data["receiver"] = $mid;
						$data["date"] = $date;
						$data["subject"] = $this->request->data["subject"];
						$data["message_body"] = $this->GYMFunction->sanitize_string($this->request->data["message_body"]);
						$data["subject"] = base64_encode($data["subject"]);
						$data["message_body"] = base64_encode($data["message_body"]);
						//$data["status"] =  0;
						$data["status"] =  1;
						$data["badge"] =  1;
						$records[] = $data;
						
					}
				}
				
				$rows = $this->GymMessage->newEntities($records);
				foreach($rows as $row)
				{
					if($this->GymMessage->save($row))
					{$saved = true;} else{$saved = false;}
				}
			}
			else
			{				
				$mid = $this->request->data["receiver"];
				$this->request->data["date"] = $date;
				$this->request->data["sender"] = $session["id"]; /* current userid*/
				//$this->request->data["status"] = 0;
				$this->request->data["status"] = 1;
                $this->request->data["badge"] = 1;
                $this->request->data["subject"] = base64_encode($this->request->data["subject"]);
          		$this->request->data["message_body"] = base64_encode($this->request->data["message_body"]);          		
				$row = $this->GymMessage->newEntity();
				$row = $this->GymMessage->patchEntity($row,$this->request->data);
				if($this->GymMessage->save($row))
				{$saved = true;}else{$saved = false;}				 

			}
			
			if($this->request->data["class_id"] == "all")
			{
				$member_ids = $this->GymMessage->GymMember->find("all")->where(["role_name"=>"member"])->select(["id"])->hydrate(false)->toArray();
				$records = array();
				if(!empty($member_ids))
				{					
					foreach($member_ids as $key => $value)
					{
						$mid = $value["id"];
						$data = array();
						$data["sender"] = $session["id"]; /* current userid*/
						$data["receiver"] = $mid;
						$data["date"] = $date;
						$data["subject"] = $this->request->data["subject"];
						$data["message_body"] = $this->GYMFunction->sanitize_string($this->request->data["message_body"]);
						$data["subject"] = base64_encode($data["subject"]);
						$data["message_body"] = base64_encode($data["message_body"]);
						//$data["status"] =  0;
						$data["status"] =  1;
						$data["badge"] =  1;
						$records[] = $data;						
					}
				}
				
				$rows = $this->GymMessage->newEntities($records);
				foreach($rows as $row)
				{
					if($this->GymMessage->save($row))
					{$saved = true;} else{$saved = false;}
				}
				
			}
			else if($this->request->data["class_id"] != "")
			{
				$class_id = $this->request->data["class_id"];
				$member_ids = $this->GymMessage->GymMember->find("all")->where(["role_name"=>"member","assign_class"=>$class_id])->select(["id"])->hydrate(false)->toArray();
				
				$records = array();
				if(!empty($member_ids))
				{					
					foreach($member_ids as $key => $value)
					{
						$mid = $value["id"];
						$data = array();
						$data["sender"] = $session["id"]; /* current userid*/
						$data["receiver"] = $mid;
						$data["date"] = $date;
						$data["subject"] = $this->request->data["subject"];
						$data["message_body"] = $this->GYMFunction->sanitize_string($this->request->data["message_body"]);
						$data["subject"] = base64_encode($data["subject"]);
						$data["message_body"] = base64_encode($data["message_body"]);
						//$data["status"] =  0;
						$data["status"] =  1;
						$data["badge"] =  1;
						$records[] = $data;
						
					}
				}
				
				$rows = $this->GymMessage->newEntities($records);
				foreach($rows as $row)
				{
					if($this->GymMessage->save($row))
					{$saved = true;} else{$saved = false;}
				}	
				
			}
			
			if($saved)
			{
				$this->Flash->success(__("Success! Message Sent Successfully."));

				$conn = ConnectionManager::get('default');				

				//$push_tokens = array();
                
				if($role == 'member' || $role == 'staff_member' || $role == 'accountant'|| $role == 'administrator'){
					
					$fcm_record="SELECT `push_token`,`member_id` FROM `gym_member_fcm` WHERE `role_name` = '$role'";					
				    $fcm_query = $conn->execute($fcm_record);
			        $fcm_data = $fcm_query->fetchAll('assoc');						
					foreach($fcm_data as $row)
					{  
						$push_tokens = array();
					 	$push_tokens[] = $row["push_token"];

					 	$badge_record = $this->GymMessage->find("all")->where(["receiver"=>$row["member_id"],"badge"=>1])->select(["id"])->hydrate(false)->toArray();
                        $badge_count = sizeof($badge_record);		
					 	
					 	$push_message = array("subject" => $message_subject,"comment" => $message_comment,"badge" => $badge_count, 'vibrate' => 1, 'sound' => 1);
						$this->send_notification($push_tokens, $push_message);			 	
					}						
				}else{
					                				
					$fcm_record="SELECT `push_token`,`member_id` FROM `gym_member_fcm` WHERE `member_id` = '$role'";					
				    $fcm_query = $conn->execute($fcm_record);
			        $fcm_data = $fcm_query->fetchAll('assoc');	
			        					
					foreach($fcm_data as $row)
					{  
						$push_tokens = array();
					 	$push_tokens[] = $row["push_token"];

					 	$badge_record = $this->GymMessage->find("all")->where(["receiver"=>$row["member_id"],"badge"=>1])->select(["id"])->hydrate(false)->toArray();
                        $badge_count = sizeof($badge_record);
					 	
					 	$push_message = array("subject" => $message_subject,"comment" => $message_comment,"badge" => $badge_count, 'vibrate' => 1, 'sound' => 1);
				        $this->send_notification($push_tokens, $push_message);					 	
					}	
				}
    
				//$push_message = array("subject" => $message_subject,"comment" => $message_comment,"badge" => 1, 'vibrate' => 1, 'sound' => 1);

				//$this->send_notification($push_tokens, $push_message);
							
			}
			else
			{$this->Flash->error(__("Error! Message Couldn't be Sent, Please Try Again."));}			
		}
    }
	
	public function inbox()
    {
		$session = $this->request->session()->read("User");
		$uid = $session["id"]; /* Current userid */
		$messages = $this->GymMessage->find("all")->contain(["GymMember"])->where(["receiver"=>$uid])->select($this->GymMessage)->select(["GymMember.first_name","GymMember.last_name"])->hydrate(false)->toArray();
		$this->set("messages",$messages);		
    }
	
	public function sent()
    {   
		$session = $this->request->session()->read("User");   
		$uid = $session["id"]; /* Current userid */
		//$messages = $this->GymMessage->find("all")->where(["GymMessage.sender"=>$uid])->limit(30)->select($this->GymMessage);
		$messages = $this->GymMessage->find("all", array('order'=>array('GymMessage.id ASC')))->where(["GymMessage.sender"=>$uid])->select($this->GymMessage);
		//$messages = $this->GymMessage->find("all")->where(["GymMessage.sender"=>$uid])->select($this->GymMessage);
		$messages = $messages->leftjoin(["GymMember"=>"gym_member"],
									  ["GymMember.id = GymMessage.receiver"])->select(["GymMember.first_name","GymMember.last_name"])->hydrate(false)->toArray();
		$this->set("messages",$messages);		
    }	
	
    public function viewMessage($vid)
    {
		$data = $this->GymMessage->find("all")->where(["GymMessage.id"=>intval($vid)])->contain(["GymMember"])->select($this->GymMessage)->select(["GymMember.first_name","GymMember.last_name","GymMember.email"])->hydrate(false)->toArray();
		$this->set("data",$data[0]);	
		$row = $this->GymMessage->get($vid);
		$row->status = 1;
		$this->GymMessage->save($row);
	}  
	
	public function viewSentMessage($vid)
    {
		$data = $this->GymMessage->find("all")->where(["GymMessage.id"=>intval($vid)])->select($this->GymMessage);
		$data = $data->leftjoin(["GymMember"=>"gym_member"],
								["GymMember.id = GymMessage.receiver"])->select(["GymMember.first_name","GymMember.last_name","GymMember.email"])->hydrate(false)->toArray();
		$temp = $data[0]["GymMember"];
		unset($data[0]["GymMember"]);
		$data[0]["gym_member"] = $temp;		
		$this->set("data",$data[0]);
		$this->render("viewMessage");
	}
	
	public function deleteMessage($did)
	{
		$row = $this->GymMessage->get($did);
		if($this->GymMessage->delete($row))
		{
			$this->Flash->success(__("Success! Message Deleted Successfully."));
			return $this->redirect(["action"=>"inbox"]);
		}
	}
	public function deleteMessageAll($dids)
	{
		$del = 0;
		$ids = explode(',',$dids);
		foreach ($ids as $did) {
			$row = $this->GymMessage->get($did);
			$this->GymMessage->delete($row);
		}
		
		$this->Flash->success(__("Success! Selected Messages Deleted Successfully."));
		return $this->redirect(["action"=>"sent"]);
	}	
}
?>