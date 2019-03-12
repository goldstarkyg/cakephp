<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
//use Cake\App\Controller;

class GymNutritionController extends AppController
{
	public function initialize()
	{
		parent::initialize();		
		$this->loadComponent("GYMFunction");
	}
	
	public function nutritionList()
	{
		$ids = '0';
		$session = $this->request->session()->read("User");
		if($session["role_name"] == "staff_member")
		{
			if($this->GYMFunction->getSettings("staff_can_view_own_member"))
			{

				$data = $this->GymNutrition->find("all")->contain(["GymMember"])->where(['GymNutrition.user_id NOT IN' => $ids])->where(["GymMember.assign_staff_mem"=>$session["id"]])->select($this->GymNutrition)->group("user_id");
				$data = $data->select(["GymMember.first_name","GymMember.last_name","GymMember.image","GymMember.member_id","GymMember.intrested_area"])->hydrate(false)->toArray();
			}else{
				$data = $this->GymNutrition->find("all")->contain(["GymMember"])->where(['GymNutrition.user_id NOT IN' => $ids])->select($this->GymNutrition)->group("user_id");
				$data = $data->select(["GymMember.first_name","GymMember.last_name","GymMember.image","GymMember.member_id","GymMember.intrested_area"])->hydrate(false)->toArray();
			}
		}
		else{			
			$data = $this->GymNutrition->find("all")->contain(["GymMember"])->where(['GymNutrition.user_id NOT IN' => $ids])->select($this->GymNutrition)->group(["user_id","GymNutrition.id"]);
			$data = $data->select(["GymMember.first_name","GymMember.last_name","GymMember.image","GymMember.member_id","GymMember.intrested_area"])->hydrate(false)->toArray();
		}
		// general nutrition 
		$default_data =  Array('user_id'=>0,'gym_member'=>Array('image' => 'logo.png','first_name'=>'Default','last_name'=>'','member_id'=>'for general nutrition'));

		array_unshift($data , $default_data); 	
		$this->set("data",$data);
	}
	
	public function addNutritionSchedule()
	{
		$session = $this->request->session()->read("User");
		$this->set("edit",false);
		$this->set("title",__("Add Nutrition Schedule"));

		if($session["role_name"] == "staff_member")
		{
			if($this->GYMFunction->getSettings("staff_can_view_own_member"))
			{
				$members = $this->GymNutrition->GymMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member","assign_staff_mem"=>$session["id"]]);
				$members = $members->select(["id",'name'=>$members->func()->concat(['first_name'=>'literal',' ','last_name'=>'literal'])])->hydrate(false)->toArray();
			}
			else{
				$members = $this->GymNutrition->GymMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member"]);
				$members = $members->select(["id",'name'=>$members->func()->concat(['first_name'=>'literal',' ','last_name'=>'literal'])])->hydrate(false)->toArray();
			}
		}
		else{
			$members = $this->GymNutrition->GymMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member"]);
			$members = $members->select(["id",'name'=>$members->func()->concat(['first_name'=>'literal',' ','last_name'=>'literal'])])->hydrate(false)->toArray();
		}
		
		$a = ["0"=>"Default ( general nutrition )"];
		$members  = $a + $members;
		$this->set("members",$members);
		
		if($this->request->is("post"))
		{	
			$data = $this->request->data; 	
       
			$user_name = 'Default';
			if($data['user_id'] != 0){
                $user = $this->GymNutrition->GymMember->get($data['user_id']);	 	
			    $user_name = $user->first_name." ".$user->last_name;
			}			
            
			$nutrition = $this->GymNutrition->find()->where(["user_id"=>$data['user_id']])->hydrate(false)->toArray();
			
            if(!empty($nutrition)){
               $nutrition = $this->GymNutrition->get($nutrition[0]['id']);
               $data["created_by"] = $session["id"];							
			   $data["created_date"] = date("Y-m-d");     
               $nutrition = $this->GymNutrition->patchEntity($nutrition,$data);

                $image1 = $this->GYMFunction->uploadImage($this->request->data["image1"]);
				$data['image1'] = (!empty($image1)) ? $image1 : "meal.jpg";   
				$image2 = $this->GYMFunction->uploadImage($this->request->data["image2"]);
				$data['image2'] = (!empty($image2)) ? $image2 : "meal.jpg";   
				$image3 = $this->GYMFunction->uploadImage($this->request->data["image3"]);
				$data['image3'] = (!empty($image3)) ? $image3 : "meal.jpg";   
				$image4 = $this->GYMFunction->uploadImage($this->request->data["image4"]);
				$data['image4'] = (!empty($image4)) ? $image4 : "meal.jpg";   
				$image5 = $this->GYMFunction->uploadImage($this->request->data["image5"]);
				$data['image5'] = (!empty($image5)) ? $image5 : "meal.jpg";   
				$image6 = $this->GYMFunction->uploadImage($this->request->data["image6"]);
				$data['image6'] = (!empty($image6)) ? $image6 : "meal.jpg";   
				$image7 = $this->GYMFunction->uploadImage($this->request->data["image7"]);
				$data['image7'] = (!empty($image7)) ? $image7 : "meal.jpg";


                if($this->GymNutrition->save($nutrition))
				{   
					$nutrition_schedule = $this->GymNutrition->GymNutritionData->find()->where(["nutrition_id"=>$nutrition['id']])->hydrate(false)->toArray();
					
					if(!empty($nutrition_schedule)){   
						$nutrition_data = $this->GymNutrition->GymNutritionData->get($nutrition_schedule[0]['id']);
						
						$nutrition_data['user_id'] = $data['user_id'];	
						$nutrition_data['user_name'] = $user_name;			
						$nutrition_data['start_date'] = $nutrition->start_date;
						$nutrition_data['expire_date'] = $nutrition->expire_date;
						$nutrition_data["created_date"] = date("Y-m-d");
						if(!empty($image1))
						    $nutrition_data['image1'] = $image1;
						if(!empty($image2))
							$nutrition_data['image2'] = $image2;
						if(!empty($image3))
							$nutrition_data['image3'] = $image3;
						if(!empty($image4))
							$nutrition_data['image4'] = $image4;
						if(!empty($image5))
							$nutrition_data['image5'] = $image5;
						if(!empty($image6))
							$nutrition_data['image6'] = $image6;
						if(!empty($image7))
							$nutrition_data['image7'] = $image7;
						$nutrition_data['breakfast1'] = $data['breakfast1'];
						$nutrition_data['breakfast2'] = $data['breakfast2'];
						$nutrition_data['breakfast3'] = $data['breakfast3'];
						$nutrition_data['breakfast4'] = $data['breakfast4'];
						$nutrition_data['breakfast5'] = $data['breakfast5'];
						$nutrition_data['breakfast6'] = $data['breakfast6'];
						$nutrition_data['breakfast7'] = $data['breakfast7'];
						$nutrition_data['lunch1'] = $data['lunch1'];
						$nutrition_data['lunch2'] = $data['lunch2'];
						$nutrition_data['lunch3'] = $data['lunch3'];
						$nutrition_data['lunch4'] = $data['lunch4'];
						$nutrition_data['lunch5'] = $data['lunch5'];
						$nutrition_data['lunch6'] = $data['lunch6'];
						$nutrition_data['lunch7'] = $data['lunch7'];
						$nutrition_data['dinner1'] = $data['dinner1'];
						$nutrition_data['dinner2'] = $data['dinner2'];
						$nutrition_data['dinner3'] = $data['dinner3'];
						$nutrition_data['dinner4'] = $data['dinner4'];
						$nutrition_data['dinner5'] = $data['dinner5'];
						$nutrition_data['dinner6'] = $data['dinner6'];
						$nutrition_data['dinner7'] = $data['dinner7'];  							
										
						if($this->GymNutrition->GymNutritionData->save($nutrition_data))
						{   
							$this->Flash->success(__("Success"));	
					 		return $this->redirect(["action"=>"nutritionList"]);
						}else{
							$this->Flash->error(__("Error! Nutrition Schedule couldn't saved. Please try again."));
						}
					}else{
						$this->Flash->error(__("Error! Nutrition Schedule couldn't saved. Please try again."));
					}
					
				}else{
					$this->Flash->error(__("Error! Nutrition Schedule couldn't saved. Please try again."));
				}

               
            }else{
                
           	    $row = $this->GymNutrition->newEntity();
           	    $data["created_by"] = $session["id"];							
				$data["created_date"] = date("Y-m-d");					

				$image1 = $this->GYMFunction->uploadImage($this->request->data["image1"]);
				$data['image1'] = (!empty($image1)) ? $image1 : "meal.jpg";   
				$image2 = $this->GYMFunction->uploadImage($this->request->data["image2"]);
				$data['image2'] = (!empty($image2)) ? $image2 : "meal.jpg";   
				$image3 = $this->GYMFunction->uploadImage($this->request->data["image3"]);
				$data['image3'] = (!empty($image3)) ? $image3 : "meal.jpg";   
				$image4 = $this->GYMFunction->uploadImage($this->request->data["image4"]);
				$data['image4'] = (!empty($image4)) ? $image4 : "meal.jpg";   
				$image5 = $this->GYMFunction->uploadImage($this->request->data["image5"]);
				$data['image5'] = (!empty($image5)) ? $image5 : "meal.jpg";   
				$image6 = $this->GYMFunction->uploadImage($this->request->data["image6"]);
				$data['image6'] = (!empty($image6)) ? $image6 : "meal.jpg";   
				$image7 = $this->GYMFunction->uploadImage($this->request->data["image7"]);
				$data['image7'] = (!empty($image7)) ? $image7 : "meal.jpg";  
					
                $row = $this->GymNutrition->patchEntity($row,$data);

				if($this->GymNutrition->save($row))
				{
					$nutrition_data = array();
					$nutrition_data['user_id'] = $data['user_id'];
					$nutrition_data['user_name'] = $user_name;
					$nutrition_data['nutrition_id'] = $row->id;
					$nutrition_data['start_date'] = $row->start_date;
					$nutrition_data['expire_date'] = $row->expire_date;
					$nutrition_data["created_date"] = date("Y-m-d");
					$nutrition_data['image1'] = $image1;
					$nutrition_data['image2'] = $image2;
					$nutrition_data['image3'] = $image3;
					$nutrition_data['image4'] = $image4;
					$nutrition_data['image5'] = $image5;
					$nutrition_data['image6'] = $image6;
					$nutrition_data['image7'] = $image7;
					$nutrition_data['breakfast1'] = $data['breakfast1'];
					$nutrition_data['breakfast2'] = $data['breakfast2'];
					$nutrition_data['breakfast3'] = $data['breakfast3'];
					$nutrition_data['breakfast4'] = $data['breakfast4'];
					$nutrition_data['breakfast5'] = $data['breakfast5'];
					$nutrition_data['breakfast6'] = $data['breakfast6'];
					$nutrition_data['breakfast7'] = $data['breakfast7'];
					$nutrition_data['lunch1'] = $data['lunch1'];
					$nutrition_data['lunch2'] = $data['lunch2'];
					$nutrition_data['lunch3'] = $data['lunch3'];
					$nutrition_data['lunch4'] = $data['lunch4'];
					$nutrition_data['lunch5'] = $data['lunch5'];
					$nutrition_data['lunch6'] = $data['lunch6'];
					$nutrition_data['lunch7'] = $data['lunch7'];
					$nutrition_data['dinner1'] = $data['dinner1'];
					$nutrition_data['dinner2'] = $data['dinner2'];
					$nutrition_data['dinner3'] = $data['dinner3'];
					$nutrition_data['dinner4'] = $data['dinner4'];
					$nutrition_data['dinner5'] = $data['dinner5'];
					$nutrition_data['dinner6'] = $data['dinner6'];
					$nutrition_data['dinner7'] = $data['dinner7'];  	
					
					$row_detail = $this->GymNutrition->GymNutritionData->newEntity($nutrition_data);	
					//$row_detail = $this->GymNutrition->GymNutritionData->patchEntity($row_detail,$nutrition_data);				
					if($this->GymNutrition->GymNutritionData->save($row_detail))
					{   
						$this->Flash->success(__("Success"));	
				 		return $this->redirect(["action"=>"nutritionList"]);
					}else{
						$this->Flash->error(__("Error! Nutrition Schedule couldn't saved. Please try again."));
					}
					
				}else{
					$this->Flash->error(__("Error! Nutrition Schedule couldn't saved. Please try again."));
				}
				// if($save)
				// {				
				// 	if($this->nutrition_detail($nid,$n_image,$data['activity_list']))
				// 	{
				// 		$this->Flash->success(__("Success"));	
				// 		return $this->redirect(["action"=>"nutritionList"]);
				// 	}
				// 	else{
				// 		$this->Flash->error(__("Error! Nutrition data couldn't saved. Please try again."));				
				// 	}				
				// }else{
				// 		$this->Flash->error(__("Error! Nutrition Schedule couldn't saved. Please try again."));				
				// }
           }

			
			
		}
	}
	public function deleteNutrition($did)
	{
		$row = $this->GymNutrition->get($did);		
		$this->GymNutrition->GymNutritionData->deleteAll(["nutrition_id"=>$did]);
		if($this->GymNutrition->delete($row))
		{
			$this->Flash->success(__("Success! Nutrition Deleted Successfully."));
			return $this->redirect(["action"=>"nutritionList"]);
		}
	}
	public function nutrition_detail($nutrition_id,$nutrition_image,$activity_list)
	{
		foreach($activity_list as $val)
			{
				$data_value = json_decode($val);
				$phpobj[] = json_decode(stripslashes($val),true);				
			}
			
			$final_array = array();
			$resultarray =array();
			
			foreach($phpobj as $index => $value)
			{
				$day = array();
				$activity = array();
				foreach($value as $key => $val)
				{
					
					if($key == "days")
					{	foreach($val as $val1)
						{
							$day['day'][] =$val1['day_name'] ;
						}	
					}
					if($key == "activity")
					{
						foreach($val as $val2)
						{
							
							
							$activity['activity'][] =array('activity'=>$val2['activity']['activity'],
														'value'=>$val2['activity']['value']
														
							) ;
						}
					}
				}
				$resultarray[] = array_merge($day, $activity);
			}
			
		$work_outdata = $resultarray;		
		if(!empty($work_outdata))
		{
			$workout_data = array();
			foreach($work_outdata as  $value)
			{
				foreach($value['day'] as $day)
				{
					foreach($value['activity']  as $actname)
					{
						$workout_data['day_name'] = $day;
						$workout_data['nutrition_time'] = $actname['activity'];
						$workout_data['nutrition_value'] = $actname['value'];
					
						$workout_data['nutrition_id'] = $nutrition_id;
						$workout_data['image'] = $nutrition_image;
						$workout_data['created_date'] = date("Y-m-d");
						$workout_data['create_by'] = 1;						
						$rws[] = $workout_data;							
					}
				}				
			}			
		}		
	
		$ma_row = $this->GymNutrition->GymNutritionData->newEntities($rws);
		foreach($ma_row as $m_row)
		{
			if($this->GymNutrition->GymNutritionData->save($m_row))
			{
				$success = 1;
			}else{
				$success = 0;
			}
		}
	
		return $success;
	}
	
	public function viewNutrition($user_id)
	{   
		$nutrition_schedule = $this->GymNutrition->GymNutritionData->find()->where(["user_id"=>$user_id])->hydrate(false)->toArray();
		
		if(!empty($nutrition_schedule)){
			 $this->set("edit",true);
		     $this->set("title",__("View Nutrition Schedule"));
             $data = $this->GymNutrition->GymNutritionData->get($nutrition_schedule[0]['id']); 
             $this->set("data",$data);
		}else{
			$session = $this->request->session()->read("User");
			$this->set("edit",false);
			$this->set("title",__("Add Nutrition Schedule"));

			if($session["role_name"] == "staff_member")
			{
				if($this->GYMFunction->getSettings("staff_can_view_own_member"))
				{
					$members = $this->GymNutrition->GymMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member","assign_staff_mem"=>$session["id"]]);
					$members = $members->select(["id",'name'=>$members->func()->concat(['first_name'=>'literal',' ','last_name'=>'literal'])])->hydrate(false)->toArray();
				}
				else{
					$members = $this->GymNutrition->GymMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member"]);
					$members = $members->select(["id",'name'=>$members->func()->concat(['first_name'=>'literal',' ','last_name'=>'literal'])])->hydrate(false)->toArray();
				}
			}
			else{
				$members = $this->GymNutrition->GymMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member"]);
				$members = $members->select(["id",'name'=>$members->func()->concat(['first_name'=>'literal',' ','last_name'=>'literal'])])->hydrate(false)->toArray();
			}
			
			$this->set("members",$members);
			$this->Flash->error(__("Error! There is no Nutrition Schedule Data."));
		    $this->render("addnutritionSchedule");
		}				
	}
	
	public function memberNutrition()
	{
		$session = $this->request->session()->read("User");
		$id = $session["id"];
		$data = $this->GymNutrition->find()->where(["GymNutrition.user_id"=>$id])->select(["GymNutrition.start_date","GymNutrition.expire_date"]);
		$data = $data->leftjoin(["GymNutritionData"=>"gym_nutrition_data"],
								["GymNutritionData.nutrition_id = GymNutrition.id"]
								)->select($this->GymNutrition->GymNutritionData)->hydrate(false)->toArray();
		
		$nutrition_data = array();
		foreach($data as $key=>$value)
		{ 			
			foreach($value as $k=>$v)
			{ 
				if($k == "GymNutritionData"){
					$wid = $v["nutrition_id"];					
					if($wid != "")
					{
						$nutrition_data[$wid]["start_date"]= $value["start_date"];					
						$nutrition_data[$wid]["expire_date"]= $value["expire_date"];						
						$nutrition_data[$wid][]=$v;							
					}
				}				
			}			
		}		
		$this->set("nutrition_data",$nutrition_data);
	}
	
	public function printNutrition()
	{
		$session = $this->request->session()->read("User");
		$id = $session["id"];
		$data = $this->GymNutrition->find()->where(["GymNutrition.user_id"=>$id])->select(["GymNutrition.start_date","GymNutrition.expire_date"]);
		$data = $data->leftjoin(["GymNutritionData"=>"gym_nutrition_data"],
								["GymNutritionData.nutrition_id = GymNutrition.id"]
								)->select($this->GymNutrition->GymNutritionData)->hydrate(false)->toArray();
		
		$nutrition_data = array();
		foreach($data as $key=>$value)
		{ 			
			foreach($value as $k=>$v)
			{ 
				if($k == "GymNutritionData"){
					$wid = $v["nutrition_id"];					
					if($wid != "")
					{
						$nutrition_data[$wid]["start_date"]= $value["start_date"];					
						$nutrition_data[$wid]["expire_date"]= $value["expire_date"];						
						$nutrition_data[$wid][]=$v;							
					}
				}				
			}			
		}		
		$this->set("nutrition_data",$nutrition_data);
	}
	
	public function isAuthorized($user)
	{
		$role_name = $user["role_name"];
		$curr_action = $this->request->action;
		$members_actions = ["memberNutrition","printNutrition"];
		// $staff_actions = ["nutritionList","addnutritionSchedule","nutrition_detail","viewNutirion"];
		$acc_actions = ["nutritionList"];
		switch($role_name)
		{			
			CASE "member":
				if(in_array($curr_action,$members_actions))
				{return true;}else{return $this->redirect(["action"=>"memberNutrition"]);}
				
			break;
			
			// CASE "staff_member":
				// if(in_array($curr_action,$staff_actions))
				// {return true;}else{ return false;}
			// break;
			
			CASE "accountant":
				if(in_array($curr_action,$acc_actions))
				{return true;}else{return false;}
			break;
		}
		return parent::isAuthorized($user);
	}
}