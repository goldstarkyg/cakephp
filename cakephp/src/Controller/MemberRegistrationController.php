<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

Class MemberRegistrationController  extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Csrf');
		$this->loadComponent("GYMFunction");		
	}
	
	public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['index','getMembershipEndDate','addPaymentHistory','attendance','getUserByFinger','setUserByFinger']);
		if (in_array($this->request->action, ['getMembershipEndDate','getUserByFinger','setUserByFinger'])) {
			$this->eventManager()->off($this->Csrf);
		}
    }
	
	public function index()
	{		
		//$this->viewBuilder()->layout('login');

		$lastid = $this->MemberRegistration->GymMember->find("all",["fields"=>"id"])->last();
		$lastid = ($lastid != null) ? $lastid->id + 1 : 01 ;

		$member = $this->MemberRegistration->GymMember->newEntity();
		$m = date("d");
		$y = date("y");
		$prefix = "M".$lastid;
		$member_id = $prefix.$m.$y;

		$this->set("member_id",$member_id);
		$classes = $this->MemberRegistration->GymMember->ClassSchedule->find("list",["keyField"=>"id","valueField"=>"class_name"]);
		$groups = $this->MemberRegistration->GymMember->GymGroup->find("list",["keyField"=>"id","valueField"=>"name"]);
		$interest = $this->MemberRegistration->GymMember->GymInterestArea->find("list",["keyField"=>"id","valueField"=>"interest"]);
		$source = $this->MemberRegistration->GymMember->GymSource->find("list",["keyField"=>"id","valueField"=>"source_name"]);
		$membership = $this->MemberRegistration->GymMember->Membership->find("list",["keyField"=>"id","valueField"=>"membership_label"]);


		$this->set("classes",$classes);
		$this->set("groups",$groups);
		$this->set("interest",$interest);
		$this->set("source",$source);
		$this->set("membership",$membership);
		$this->set("edit",false);
		if($this->request->is("post"))
		{
			$this->request->data['member_id'] = $member_id;
			
            $img_data = $this->request->data['imageinput']; 
  	        if(!empty($img_data)){
	  	        list($type, $img_data) = explode(';', $img_data);
				list(, $img_data)      = explode(',', $img_data);
				$img_data = base64_decode($img_data);

	            $img_name = time() . "_" . rand(000000, 999999). ".png";

				file_put_contents(WWW_ROOT . "/upload/".$img_name, $img_data); 
				$this->request->data['image'] = $img_name;
  	        }else{
	  	        $image = $this->GYMFunction->uploadImage($this->request->data['image']);			
				$this->request->data['image'] = (!empty($image)) ? $image : "logo.png";	
  	        }

			$this->request->data['birth_date'] = date("Y-m-d",strtotime($this->request->data['birth_date']));
			$this->request->data['created_date'] = date("Y-m-d");
			$this->request->data['assign_group'] = json_encode($this->request->data['assign_group']);
			$this->request->data['membership_status'] = "Prospect";
			$this->request->data["role_name"]="member";

			$card_image = $this->GYMFunction->uploadImage($this->request->data['card_image']);
			$this->request->data['card_image'] = (!empty($card_image)) ? $card_image : "card-image.png";

			$member = $this->MemberRegistration->GymMember->patchEntity($member,$this->request->data);

			if($this->MemberRegistration->GymMember->save($member))
			{
				$this->request->data['member_id'] = $member->id;
				$this->GYMFunction->add_membership_history($this->request->data);
				if($this->addPaymentHistory($this->request->data))
				{
					// $this->Flash->success(__("Success! Record Saved Successfully."));
				}

				if(!empty($this->request->data["assign_class"]))
				{
					foreach($this->request->data["assign_class"] as $class)
					{
						$new_row = $this->MemberRegistration->GymMemberClass->newEntity();
						$data = array();
						$data["member_id"] = $member->id;
						$data["assign_class"] = $class;
						$new_row = $this->MemberRegistration->GymMemberClass->patchEntity($new_row,$data);
						$this->MemberRegistration->GymMemberClass->save($new_row);
					}
				}

				$sys_email = $this->GYMFunction->getSettings("email");
				$sys_name = $this->GYMFunction->getSettings("name");
				$email_comment = $this->GYMFunction->getSettings("reminder_message");
				$headers = "From: {$sys_name} <{$sys_email}>" . "\r\n";
				$message = "Hello {$this->request->data["first_name"]}, ". "\n";
				$message .= "{$email_comment}". "\n";
				$message .= "Your Username : {$this->request->data['username']} ". "\n";
				$message .= "Your Password : {$this->request->data['password']} ";
				$message .= "You can login once after admin review your account and activates it. ". "\n";
				$message .= "Thank You.";
			
				@mail($this->request->data["email"],_("New Registration : {$sys_name}"),$message,$headers);

				$this->Flash->success(__("Registration completed successfully. Please Check email"));
				// echo "<script>alert('Success! Registration completed successfully.');</script>";
				//Send Mail

				// return $this->redirect(["action"=>"regComplete"]);
			}else
			{
				if($member->errors())
				{
					foreach($member->errors() as $error)
					{
						foreach($error as $key=>$value)
						{
							$this->Flash->error(__($value));
						}
					}
				}
			}
		}/*else{
				return $this->redirect(["controller"=>"users","action"=>"login"]);
		}*/
	}


	public function attendance()
	{
		$this->viewBuilder()->layout('login');
	}
	
	public function setUserByFinger(){
		if($this->request->is("ajax"))
		{
			$finger_id = $this->request->data["id"];
			$user_id = $this->request->data["userid"];

			$gym_member = TableRegistry::get("GymMember");

			$query = $gym_member->query();
			$result = $query->update()
					->set(['fingerprint_id' => $finger_id])
					->where(['id' =>$user_id])
					->execute();
			echo $result;

		}
		$this->autoRender = false;

	}
	public function getUserByFinger(){
		if($this->request->is("ajax"))
		{
			$finger_id = $this->request->data["id"];
			$gym_member = TableRegistry::get("GymMember");

			$users = $gym_member->find()->where(["fingerprint_id"=>$finger_id])->hydrate(false)->toArray()[0];
			if(isset($users)) {
				$attendanceTable = TableRegistry::get('GymAttendance');

				$today = date("Y-m-d");
				$attd = $attendanceTable->find()->where(["user_id"=> $users['id'] , "attendance_date"=>$today])->hydrate(false)->toArray()[0];
				if(!isset($attd)) {
					$attendance = $attendanceTable->newEntity();
					$attendance->user_id = $users['id'];
					$attendance->attendance_date = $today;
					$attendance->attendance_by = 1;
					$attendance->role_name = 'member';
					$attendance->status = 'Present';
					$attendance->class_id = $users['assign_class'];

					if ($attendanceTable->save($attendance)) {

					}


				}

	                   if(!isset($users['card_image'])){
					$users['card_image'] = 'card-image.png';
				}
				if($users['card_image'] == ''){
					$users['card_image'] = 'card-image.png';
				}
                $date = date_create($users['birth_date']);

	                 
				$users['birth_date'] =  $date->format('d/m/Y');

				?>

				<div id="CardDialog" style="max-height: 225px;" title="User Information">
					<img id='bk_card' src='<?php echo '/dev/webroot/upload/' . $users['card_image']; ?>'
						 style='width:100%'/>

					<div class='member-card-info'
						 style="position: relative;  bottom: 180px;    text-align: left;  left: 29%;  font-size: 18px;">
						<div class='member-card-info-item'>
							<span>Name: </span> <span
									id='name'><?php echo $users['first_name'] . ' ' . $users['last_name']; ?></span>
						</div>
						<div class='member-card-info-item'>
							<span>Birthday: </span> <span id='birthday'><?php echo $users['birth_date']; ?></span>
						</div>
						<div class='member-card-info-item'>
							<span>Gender: </span> <span id='gender'><?php echo $users['gender']; ?></span>
						</div>

						<div class='member-card-info-item'>
							<span>Address: </span> <span id='address'><?php echo $users['address']; ?></span>
						</div>
					</div>
				</div>
				<?php
			}
		}
		$this->autoRender = false;
	}

	private function addPaymentHistory($data)
	{
		$row = $this->MemberRegistration->MembershipPayment->newEntity();
		$save["member_id"] = $data["member_id"];
		$save["membership_id"] = $data["selected_membership"];
		$save["membership_amount"] = $this->GYMFunction->get_membership_amount($data["selected_membership"]);
		$save["paid_amount"] = 0;
		$save["start_date"] = $data["membership_valid_from"];
		$save["end_date"] = $data["membership_valid_to"];
		/* $save["membership_status"] = $data["membership_status"]; */
		$save["payment_status"] = 0;
		$save["created_date"] = date("Y-m-d");
		/* $save["created_dby"] = 1; */
		$row = $this->MemberRegistration->MembershipPayment->patchEntity($row,$save);
		if($this->MemberRegistration->MembershipPayment->save($row))
		{return true;}else{return false;}
	}
	
	
	public function regComplete()
	{
		$this->autoRender = false;
		echo "<br><p><i><strong>Success!</strong> Registration completed successfully.</i></p>";
		echo "<p><i><a href='{$this->request->base}/Users'>Click Here</a> to Redirect on login page.</i></p>";
	}
	
	public function getMembershipEndDate()
	{
		$this->autoRender=false;
		
		if($this->request->is("ajax"))
		{
			// $format = $this->GYMFunction->date_format();
			// $format = str_ireplace(array("yyyy","yy","dd","mm"),array("y","y","d","m"),$format);
			// $format = str_replace("yy","Y",$format);
			// $format = str_replace("dd","d",$format);
			// $format = str_replace("mm","m",$format);
			$date = $this->request->data["date"];
			$date = str_replace("/","-",$date);
			$membership_id = $this->request->data["membership"];
			$date1 = date("Y-m-d",strtotime($date));
			$membership_table =  TableRegistry::get("Membership");
			$row = $membership_table->get($membership_id)->toArray();
			$period = $row["membership_length"];
			$end_date = date("Y-m-d",strtotime($date1 . " + {$period} days"));
			echo $end_date;
			// echo "Asd";
			die;
		}
	}

}