<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

Class GymEmailController  extends AppController
{
	
	public function mailsend()
	{		
		$session = $this->request->session()->read("User");
		if($session["role_name"] == "administrator")
		{
			$table = TableRegistry::get("GymMember");
			$data = $table->find("all")->where(["role_name"=>"member"])->hydrate(false)->toArray();
		}
		else if($session["role_name"] == "member"){
			$uid = intval($session["id"]);
			if($this->GYMFunction->getSettings("member_can_view_other"))
			{
				$table = TableRegistry::get("GymMember");
				$data = $table->find("all")->where(["role_name"=>"member"])->hydrate(false)->toArray();
			}else{
				$table = TableRegistry::get("GymMember");
				$data = $table->find("all")->where(["id"=>$uid])->hydrate(false)->toArray();
			}
			
		}
		else if($session["role_name"] == "staff_member"){
			$uid = intval($session["id"]);
			if($this->GYMFunction->getSettings("staff_can_view_own_member"))
			{
				$table = TableRegistry::get("GymMember");
				$data = $table->find("all")->where(["assign_staff_mem"=>$uid])->hydrate(false)->toArray();
			}else{
				$table = TableRegistry::get("GymMember");
				$data = $table->find("all")->where(["role_name"=>"member"])->hydrate(false)->toArray();				
			}
		}
		else{
			$table = TableRegistry::get("GymMember");
			$data = $table->find("all")->where(["role_name"=>"member"])->hydrate(false)->toArray();
			if(!empty($this->GYMFunction->getSettings("gym_logo"))){
                $data['image'] = $this->GYMFunction->getSettings("gym_logo");
			}
		}
				
		$this->set("data",$data);		

		if($this->request->is("post"))
		{
			
			
				$mails =  $this->request->data['mails_to'];
				//$mail_subject =  $this->request->data['mail_subject'];
				$mail_body =  $this->request->data['mail_body'];
                
				$mail_list=explode(",",$mails);
			
			    $sys_email = $this->GYMFunction->getSettings("email");
				$sys_name = $this->GYMFunction->getSettings("name");
				//$reminder_message = $this->GYMFunction->getSettings("reminder_message");

			    foreach ($mail_list as $mail) {
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			        $headers .= "From: {$sys_name} <{$sys_email}>" . "\r\n";					
			        
			        $message = "<html></body>";
					$message .= "{$mail_body} ". "\n";	
					$message .= "</html></body>";				
					
					@mail($mail,_("iDancefit"),$message,$headers);
			   	}

				$this->Flash->success(__("Email sent successfully."));
				
			
		}

	}
	public function composeMessage()
    {
		$session = $this->request->session()->read("User");
		if($session["role_name"] == "member" && !$this->GYMFunction->getSettings("enable_message"))
		{
			return $this->redirect(["action"=>"sent"]);
		}
		$classes = $this->GymEmail->ClassSchedule->find("list",["keyField"=>"id","valueField"=>"class_name"])->toArray();
		$classes["all"] = "All";
		
		$this->set("classes",$classes);
		if($this->request->is("post"))
		{
			$date = date("Y-m-d H:i:s");			
			$role = $this->request->data["receiver"];	
			//$message_subject = $this->request->data["subject"];
			$message_comment = $this->request->data["message_body"];
					
			if($role == 'member' || $role == 'staff_member' || $role == 'accountant'|| $role == 'administrator')
			{
				$member_ids = $this->GymEmail->GymMember->find("all")->where(["role_name"=>$role])->select(["id"])->hydrate(false)->toArray();
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
						$data["subject"] = "";
						$data["message_body"] = $this->GYMFunction->sanitize_string($this->request->data["message_body"]);
						$data["message_body"] = $data["message_body"];						
						$data["status"] =  1;
						$data["badge"] =  1;
						$records[] = $data;
						
					}
				}
				
				$rows = $this->GymEmail->newEntities($records);
				foreach($rows as $row)
				{
					if($this->GymEmail->save($row))
					{$saved = true;} else{$saved = false;}
				}
			}
			else
			{	
				$mid = $this->request->data["receiver"];
				$this->request->data["date"] = $date;
				$this->request->data["sender"] = $session["id"]; /* current userid*/
				$this->request->data["status"] = 1;
                $this->request->data["badge"] = 1;                
          		$this->request->data["message_body"] = $this->request->data["message_body"];          		
				$row = $this->GymEmail->newEntity();
				$row = $this->GymEmail->patchEntity($row,$this->request->data);
				if($this->GymEmail->save($row))
				{$saved = true;}else{$saved = false;}				 

			}
			
			// if($this->request->data["class_id"] == "all")
			// {
			// 	$member_ids = $this->GymEmail->GymMember->find("all")->where(["role_name"=>"member"])->select(["id"])->hydrate(false)->toArray();
			// 	$records = array();
			// 	if(!empty($member_ids))
			// 	{					
			// 		foreach($member_ids as $key => $value)
			// 		{
			// 			$mid = $value["id"];
			// 			$data = array();
			// 			$data["sender"] = $session["id"]; /* current userid*/
			// 			$data["receiver"] = $mid;
			// 			$data["date"] = $date;		
			// 			$data["subject"] = "";				
			// 			$data["message_body"] = $this->GYMFunction->sanitize_string($this->request->data["message_body"]);
			// 			$data["message_body"] = $data["message_body"];
			// 			$data["status"] =  1;
			// 			$data["badge"] =  1;
			// 			$records[] = $data;						
			// 		}
			// 	}
				
			// 	$rows = $this->GymEmail->newEntities($records);
			// 	foreach($rows as $row)
			// 	{
			// 		if($this->GymEmail->save($row))
			// 		{$saved = true;} else{$saved = false;}
			// 	}
				
			// }
			// else if($this->request->data["class_id"] != "")
			// {
			// 	$class_id = $this->request->data["class_id"];
			// 	$member_ids = $this->GymEmail->GymMember->find("all")->where(["role_name"=>"member","assign_class"=>$class_id])->select(["id"])->hydrate(false)->toArray();
				
			// 	$records = array();
			// 	if(!empty($member_ids))
			// 	{					
			// 		foreach($member_ids as $key => $value)
			// 		{
			// 			$mid = $value["id"];
			// 			$data = array();
			// 			$data["sender"] = $session["id"]; /* current userid*/
			// 			$data["receiver"] = $mid;
			// 			$data["date"] = $date;		
			// 			$data["subject"] = "";				
			// 			$data["message_body"] = $this->GYMFunction->sanitize_string($this->request->data["message_body"]);						
			// 			$data["message_body"] = $data["message_body"];
			// 			$data["status"] =  1;
			// 			$data["badge"] =  1;
			// 			$records[] = $data;
						
			// 		}
			// 	}
				
			// 	$rows = $this->GymEmail->newEntities($records);
			// 	foreach($rows as $row)
			// 	{
			// 		if($this->GymEmail->save($row))
			// 		{$saved = true;} else{$saved = false;}
			// 	}	
				
			// }
			$saved = false;
			if($saved)
			{
				$conn = ConnectionManager::get('default');

				if($role == 'member' || $role == 'staff_member' || $role == 'accountant'|| $role == 'administrator'){
					
					$email_record="SELECT `email` FROM `gym_member` WHERE `role_name` = '$role'";					
				    $email_query = $conn->execute($email_record);
			        $email_data = $email_query->fetchAll('assoc');						
					
                    $mail_body =  $this->request->data['message_body'];		                
					$sys_email = $this->GYMFunction->getSettings("email");
					$sys_name = $this->GYMFunction->getSettings("name");
					//$reminder_message = $this->GYMFunction->getSettings("reminder_message");
					foreach($email_data as $row)
					{  
	                    $mail = $row["email"];
	                    $headers = "MIME-Version: 1.0" . "\r\n";
	                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
				        $headers .= "From: {$sys_name} <{$sys_email}>" . "\r\n";					
				        
				        $message = "<html></body>";
						$message .= "{$mail_body} ". "\n";	
						$message .= "</html></body>";				
						
						@mail($mail,_("iDancefit"),$message,$headers);				   	
					}						

					$this->Flash->success(__("Success! Email sent successfully."));

				}else{
					                				
					$email_record="SELECT `email` FROM `gym_member` WHERE `id` = '$role'";					
				    $email_query = $conn->execute($email_record);
			        $email_data = $email_query->fetchAll('assoc');	
			        					
					$mail_body =  $this->request->data['message_body'];		                
					$sys_email = $this->GYMFunction->getSettings("email");
					$sys_name = $this->GYMFunction->getSettings("name");
					//$reminder_message = $this->GYMFunction->getSettings("reminder_message");
					foreach($email_data as $row)
					{  
	                    $mail = $row["email"];
	                    $headers = "MIME-Version: 1.0" . "\r\n";
	                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
				        $headers .= "From: {$sys_name} <{$sys_email}>" . "\r\n";					
				        
				        $message = "<html></body>";
						$message .= "{$mail_body} ". "\n";	
						$message .= "</html></body>";				
						
						@mail($mail,_("iDancefit"),$message,$headers);				   	
					}						

					$this->Flash->success(__("Success! Email sent successfully."));
				}
				
							
			}
			else
			{$this->Flash->error(__("Error! Email Couldn't be Sent, Please Try Again."));}			
		}
    }
	public function sent()
    {   
		$session = $this->request->session()->read("User");   
		$uid = $session["id"]; /* Current userid */
		
		$messages = $this->GymEmail->find("all", array('order'=>array('GymEmail.id ASC')))->where(["GymEmail.sender"=>$uid])->select($this->GymEmail);
		
		$messages = $messages->leftjoin(["GymMember"=>"gym_member"],
									  ["GymMember.id = GymEmail.receiver"])->select(["GymMember.first_name","GymMember.last_name"])->hydrate(false)->toArray();
		$this->set("messages",$messages);		
    }	
	public function deleteMessage($did)
	{
		$row = $this->GymEmail->get($did);
		if($this->GymEmail->delete($row))
		{
			$this->Flash->success(__("Success! Email Deleted Successfully."));
			return $this->redirect(["action"=>"sent"]);
		}
	}
	public function deleteMessageAll($dids)
	{
		$del = 0;
		$ids = explode(',',$dids);
		foreach ($ids as $did) {
			$row = $this->GymEmail->get($did);
			$this->GymEmail->delete($row);
		}
		
		$this->Flash->success(__("Success! Selected Emails Deleted Successfully."));
		return $this->redirect(["action"=>"sent"]);
	}

}